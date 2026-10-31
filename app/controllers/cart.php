<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/cart.php';
require_once __DIR__ . '/../../core/Response.php';

header('Content-Type: application/json; charset=UTF-8');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id = null;
if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($id === false) {
        Response::json(400, ['success' => false, 'message' => 'Invalid cart ID']);
    }
}

$userId = null;
if (isset($_GET['user_id'])) {
    $userId = filter_var($_GET['user_id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($userId === false) {
        Response::json(400, ['success' => false, 'message' => 'Invalid user ID']);
    }
}

function jsonBody(): array
{
    try {
        $data = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        Response::json(400, ['success' => false, 'message' => 'Invalid JSON body']);
    }

    if (!is_array($data)) {
        Response::json(400, ['success' => false, 'message' => 'JSON body must be a JSON object']);
    }

    return $data;
}

function cartPositiveInt(mixed $value, string $field): int
{
    $validated = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($validated === false) {
        Response::json(422, ['success' => false, 'message' => "$field must be a positive integer"]);
    }

    return $validated;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    $db = (new Database())->connect();
    $carts = new Cart($db);

    switch ($method) {
        case 'GET':
            if ($id !== null) {
                $cart = $carts->getByID($id);
                if ($cart === null) {
                    Response::json(404, ['success' => false, 'message' => 'Cart not found']);
                }
                Response::json(200, ['success' => true, 'data' => $cart]);
            }

            if ($userId === null) {
                Response::json(400, ['success' => false, 'message' => 'Cart ID or user_id is required']);
            }
            $cart = $carts->getByUserId($userId);
            if ($cart === null) {
                Response::json(404, ['success' => false, 'message' => 'Cart not found']);
            }
            Response::json(200, ['success' => true, 'data' => $cart]);

        case 'POST':
            $body = jsonBody();
            $newUserId = cartPositiveInt($body['user_id'] ?? null, 'user_id');

            // Reuse this browser session's cart when one has already been created.
            if (isset($_SESSION['cart_id'])) {
                $existingCart = $carts->getByID((int) $_SESSION['cart_id']);
                if ($existingCart !== null) {
                    Response::json(200, ['success' => true, 'message' => 'Cart already exists', 'data' => $existingCart]);
                }
                unset($_SESSION['cart_id']);
            }

            $newId = $carts->create($newUserId);
            $_SESSION['cart_id'] = $newId;

            Response::json(201, ['success' => true, 'message' => 'Cart created successfully', 'data' => [
                'id' => $newId,
                'user_id' => $newUserId,
            ]]);


        case 'DELETE':
            if ($id === null) {
                Response::json(400, ['success' => false, 'message' => 'Cart ID is required']);
            }
            if ($carts->getByID($id) === null) {
                Response::json(404, ['success' => false, 'message' => 'Cart not found']);
            }
            $carts->delete($id);
            Response::json(200, ['success' => true, 'message' => 'Cart deleted successfully']);

        default:
            header('Allow: GET, POST, DELETE');
            Response::json(405, ['success' => false, 'message' => 'Method not allowed']);
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    Response::json($e->getCode() === '23000' ? 409 : 500, [
        'success' => false,
        'message' => $e->getCode() === '23000' ? 'Operation violates a database constraint' : 'A database error occurred'
    ]);
} catch (Throwable $e) {
    error_log($e->getMessage());
    Response::json(500, ['success' => false, 'message' => 'An internal server error occurred']);
}

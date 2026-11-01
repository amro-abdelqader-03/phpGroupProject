<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/cart-item.php';
require_once __DIR__ . '/../../core/Response.php';
require_once __DIR__ . '/../../public/session.php';

header('Content-Type: application/json; charset=UTF-8');

function cartItemJsonBody(): array
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

function cartItemPositiveInt(mixed $value, string $field): int
{
    $validated = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($validated === false) {
        Response::json(422, ['success' => false, 'message' => "$field must be a positive integer"]);
    }

    return $validated;
}

$id = null;
if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($id === false) {
        Response::json(400, ['success' => false, 'message' => 'Invalid cart item ID']);
    }
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$userId = filter_var($_SESSION['user_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($userId === false) {
    Response::json(401, ['success' => false, 'message' => 'Please sign in to use your shopping bag.']);
}

try {
    $db = (new Database())->connect();
    $cartItems = new CartItem($db);
    $ownsCart = static function (int $cartId) use ($db, $userId): bool {
        $stmt = $db->prepare('SELECT 1 FROM carts WHERE id = ? AND user_id = ?');
        $stmt->execute([$cartId, $userId]);
        return (bool) $stmt->fetchColumn();
    };

    switch ($method) {
        case 'GET':
            if ($id !== null) {
                $item = $cartItems->getByID($id);
                if ($item === null || !$ownsCart((int) $item['cart_id'])) {
                    Response::json(404, ['success' => false, 'message' => 'Cart item not found']);
                }
                Response::json(200, ['success' => true, 'data' => $item]);
            }

            if (!isset($_GET['cart_id'])) {
                Response::json(400, ['success' => false, 'message' => 'Cart ID is required']);
            }
            $cartId = cartItemPositiveInt($_GET['cart_id'], 'cart_id');
            if (!$ownsCart($cartId)) {
                Response::json(404, ['success' => false, 'message' => 'Cart not found']);
            }
            $items = $cartItems->getByCartID($cartId);
            Response::json(200, ['success' => true, 'count' => count($items), 'data' => $items]);

        case 'POST':
            $body = cartItemJsonBody();
            $cartId = cartItemPositiveInt($body['cart_id'] ?? null, 'cart_id');
            $productId = cartItemPositiveInt($body['product_id'] ?? null, 'product_id');
            $variantId = isset($body['variant_id']) && $body['variant_id'] !== null
                ? cartItemPositiveInt($body['variant_id'], 'variant_id') : null;
            $quantity = cartItemPositiveInt($body['quantity'] ?? 1, 'quantity');
            if ($quantity > 99) {
                Response::json(422, ['success' => false, 'message' => 'quantity must not exceed 99']);
            }
            if (!$ownsCart($cartId)) {
                Response::json(404, ['success' => false, 'message' => 'Cart not found']);
            }
            $newId = $cartItems->addToCart($cartId, $productId, $variantId, $quantity);

            Response::json(201, ['success' => true, 'message' => 'Cart item added successfully', 'data' => [
                'id' => $newId, 'cart_id' => $cartId, 'product_id' => $productId,
                'variant_id' => $variantId, 'quantity' => $quantity
            ]]);

        case 'PUT':
            if ($id === null) {
                Response::json(400, ['success' => false, 'message' => 'Cart item ID is required']);
            }
            $item = $cartItems->getByID($id);
            if ($item === null || !$ownsCart((int) $item['cart_id'])) {
                Response::json(404, ['success' => false, 'message' => 'Cart item not found']);
            }
            $body = cartItemJsonBody();
            $variantId = array_key_exists('variant_id', $body) && $body['variant_id'] !== null
                ? cartItemPositiveInt($body['variant_id'], 'variant_id') : null;
            $quantity = cartItemPositiveInt($body['quantity'] ?? null, 'quantity');
            if ($quantity > 99) {
                Response::json(422, ['success' => false, 'message' => 'quantity must not exceed 99']);
            }
            $cartItems->update($id, $variantId, $quantity);
            Response::json(200, ['success' => true, 'message' => 'Cart item updated successfully', 'data' => [
                'id' => $id, 'variant_id' => $variantId, 'quantity' => $quantity
            ]]);

        case 'DELETE':
            if ($id === null) {
                Response::json(400, ['success' => false, 'message' => 'Cart item ID is required']);
            }
            $item = $cartItems->getByID($id);
            if ($item === null || !$ownsCart((int) $item['cart_id']) || !$cartItems->delete($id)) {
                Response::json(404, ['success' => false, 'message' => 'Cart item not found']);
            }
            Response::json(200, ['success' => true, 'message' => 'Cart item deleted successfully']);

        default:
            header('Allow: GET, POST, PUT, DELETE');
            Response::json(405, ['success' => false, 'message' => 'Method not allowed']);
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    Response::json($e->getCode() === '23000' ? 409 : 500, [
        'success' => false,
        'message' => $e->getCode() === '23000' ? 'Operation violates a database constraint' : 'A database error occurred'
    ]);
} catch (DomainException $e) {
    Response::json(422, ['success' => false, 'message' => $e->getMessage()]);
} catch (Throwable $e) {
    error_log($e->getMessage());
    Response::json(500, ['success' => false, 'message' => 'An internal server error occurred']);
}

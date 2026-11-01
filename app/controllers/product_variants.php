<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/product_variants.php';
require_once __DIR__ . '/../../core/Response.php';
require_once __DIR__ . '/../../public/session.php';


header('Content-Type: application/json; charset=UTF-8');

function readPositiveIntQuery(string $key, string $label): ?int
{
    if (!isset($_GET[$key])) {
        return null;
    }

    $value = filter_var($_GET[$key], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($value === false) {
        Response::json(400, ['success' => false, 'message' => "Invalid $label"]);
    }

    return $value;
}

$id = readPositiveIntQuery('id', 'variant ID');
$productId = readPositiveIntQuery('product_id', 'product ID');
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    $db = (new Database())->connect();
    $variantsModel = new Product_variants($db);

    switch ($method) {
        case 'GET':
            if ($id !== null) {
                $variant = $variantsModel->getById($id);
                if ($variant === null) {
                    Response::json(404, ['success' => false, 'message' => 'Product variant not found']);
                }
                Response::json(200, ['success' => true, 'data' => $variant]);
            }

            if ($productId === null) {
                Response::json(400, ['success' => false, 'message' => 'Variant ID or product_id is required']);
            }

            $variants = $variantsModel->getByProduct($productId);
            Response::json(200, [
                'success' => true,
                'count' => count($variants),
                'data' => $variants,
            ]);

        default:
            header('Allow: GET');
            Response::json(405, ['success' => false, 'message' => 'Method not allowed']);
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    Response::json(500, ['success' => false, 'message' => 'A database error occurred']);
} catch (Throwable $e) {
    error_log($e->getMessage());
    Response::json(500, ['success' => false, 'message' => 'An internal server error occurred']);
}

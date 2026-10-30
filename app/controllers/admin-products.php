<?php

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../models/admin-product.php";
require_once __DIR__ . "/../../core/Response.php";
header("Content-Type: application/json; charset=UTF-8");


// Read and validate product ID
$id = null;

if (isset($_GET["id"])) {
    $id = filter_var(
        $_GET["id"],
        FILTER_VALIDATE_INT,
        ["options" => ["min_range" => 1]]
    );

    if ($id === false) {
        Response::json(400, [
            "success" => false,
            "message" => "Invalid product ID"
        ]);
    }
}

// Read JSON request body
function getJsonBody(): array
{
    $rawBody = file_get_contents("php://input");

    try {
        $data = json_decode(
            $rawBody,
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    } catch (JsonException $e) {
        Response::json(400, [
            "success" => false,
            "message" => "Invalid JSON body"
        ]);
    }

    if (!is_array($data)) {
        Response::json(400, [
            "success" => false,
            "message" => "JSON body must be a JSON object"
        ]);
    }

    return $data;
}

// Validate product fields
// Validate product fields
function validateProductsFields(array $data): array
{
    $product_name = $data["product_name"] ?? "";
    $description = $data["description"] ?? "";
    $subcategory_id = $data["subcategory_id"] ?? null;
    $cost_price = $data["cost_price"] ?? null;
    $price = $data["price"] ?? null;

    if (!is_string($product_name) || !is_string($description)) {
        Response::json(422, [
            "success" => false,
            "message" => "Name and description must be strings"
        ]);
    }

    $product_name = trim($product_name);
    $description = trim($description);

    if ($product_name === "" || $description === "") {
        Response::json(422, [
            "success" => false,
            "message" => "Name and description are required"
        ]);
    }

    return [
        "product_name" => $product_name,
        "description" => $description,
        "subcategory_id" => $subcategory_id,
        "cost_price" => $cost_price,
        "price" => $price
    ];
}

// Read HTTP method
$method = $_SERVER["REQUEST_METHOD"];

// Connect to database
try {
    $database = new Database();
    $pdo = $database->connect();

    // Create Products model
    $ProductsModel = new Admin_product($pdo);

    switch ($method) {

        // GET: Retrieve Products
        case "GET":

            if ($id !== null) {
                $product = $ProductsModel->getById($id);

                if ($product === null) {
                    Response::json(404, [
                        "success" => false,
                        "message" => "product not found"
                    ]);
                }

                Response::json(200, [
                    "success" => true,
                    "data" => $product
                ]);
            }

            $Products = $ProductsModel->getAll();

            Response::json(200, [
                "success" => true,
                "count" => count($Products),
                "data" => $Products
            ]);

        // POST: Create product
        case "POST":

            $body = getJsonBody();
            $data = validateProductsFields($body);

            // $newId = $ProductsModel->create(
            //     $data["name"],
            //     $data["description"],
            //     $data["status"]
            // );

            Response::json(201, [
                "success" => true,
                "message" => "product created successfully",
                "data" => [
                    "id" => $newId,
                    "name" => $data["name"],
                    "description" => $data["description"],
                    "status" => $data["status"]
                ]
            ]);

        // PUT: Update product
        // PUT: Update product
        case "PUT":

            if ($id === null) {
                Response::json(400, [
                    "success" => false,
                    "message" => "product ID is required"
                ]);
            }

            $body = getJsonBody();
            $data = validateProductsFields($body);

            $existingproduct = $ProductsModel->getById($id);

            if ($existingproduct === null) {
                Response::json(404, [
                    "success" => false,
                    "message" => "product not found"
                ]);
            }

            $ProductsModel->update(
                $id,
                $data["product_name"],
                $data["description"],
                $data["subcategory_id"],
                $data["cost_price"],
                $data["price"]
            );

            Response::json(200, [
                "success" => true,
                "message" => "product updated successfully",
                "data" => [
                    "id" => $id,
                    "product_name" => $data["product_name"],
                    "description" => $data["description"],
                    "subcategory_id" => $data["subcategory_id"],
                    "cost_price" => $data["cost_price"],
                    "price" => $data["price"]
                ]
            ]);

        // DELETE: Delete product
        case "DELETE":

            if ($id === null) {
                Response::json(400, [
                    "success" => false,
                    "message" => "product ID is required"
                ]);
            }

            $existingproduct = $ProductsModel->getById($id);

            if ($existingproduct === null) {
                Response::json(404, [
                    "success" => false,
                    "message" => "product not found"
                ]);
            }

            $ProductsModel->delete($id);

            Response::json(200, [
                "success" => true,
                "message" => "product deleted successfully"
            ]);

        // Unsupported HTTP method
        default:

            header("Allow: GET, POST, PUT, DELETE");

            Response::json(405, [
                "success" => false,
                "message" => "Method not allowed"
            ]);
    }

} catch (PDOException $e) {

    error_log($e->getMessage());

    if ($e->getCode() === "23000") {
        Response::json(409, [
            "success" => false,
            "message" => "Operation violates a database constraint"
        ]);
    }

    Response::json(500, [
        "success" => false,
        "message" => "A database error occurred"
    ]);

} catch (Throwable $e) {

    error_log($e->getMessage());

    Response::json(500, [
        "success" => false,
        "message" => "An internal server error occurred"
    ]);
}

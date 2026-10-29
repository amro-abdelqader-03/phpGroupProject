
<?php

require_once  "../app/config/Database.php";
require_once  "../app/models/admin-product.php";
require_once "Response.php";

header("Content-Type: application/json; charset=UTF-8 ");


// Connect to database
try {
    $database = new Database();
    $pdo = $database->connect();

} catch (Throwable $e) {
    error_log($e->getMessage());

    Response::json(500, [
        "success" => false,
        "message" => "Unable to connect to the database"
    ]);
}

// Create product object
$productModel = new Admin_product($pdo);

// Read HTTP method
$method = $_SERVER["REQUEST_METHOD"];

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

// Validate common product fields
function validateProductFields(array $data): array
{
    $name = $data["name"] ?? "";
    $description = $data["description"] ?? "";
    $subcategory = $data["subcategory"] ?? "";
    $price = $data["price"] ?? "";
    $price_cost = $data["price_cost"] ?? "";
    $status = $data["status"] ?? "";

    if (!is_string($name) ||
        !is_string($description) ||
        !is_string($status)) {
        Response::json(422, [
            "success" => false,
            "message" => "name, description and status must be strings"
        ]);
    }

    $name = trim($name);
    $description = trim($description);
    $status = trim($status);

    if ($name === "" || $description === "" || $status === "") {
        Response::json(422, [
            "success" => false,
            "message" => "name, description and status are required"
        ]);
    }

    return [
        "name" => $name,
        "email" => $description,
        "course" => $status
    ];
}

try {

    switch ($method) {

        // GET: Retrieve products
        case "GET":

            if ($id !== null) {
                $product = $productModel->getById($id);

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

            $products = $productModel->getAll();

            Response::json(200, [
                "success" => true,
                "count" => count($products),
                "data" => $products
            ]);

        // POST: Create product
        case "POST":

            $body = getJsonBody();
            $data = validateproductFields($body);

            $newId = $productModel->create(
                $data["name"],
                $data["description"],
                $data["subcategory_id"],
                $data["price"],
                $data["price_cost"],
            );

            Response::json(201, [
                "success" => true,
                "message" => "product created successfully",
                "data" => [
                    "id" => $newId,
                    "name" => $data["name"],
                    "description" => $data["description"],
                    "subcategory_id" => $data["subcategory_id"],
                    "price"=> $data["price"],
                    "price_cost" => $data["price_cost"],
                ]
            ]);

        // PUT: Update product
        case "PUT":

            if ($id === null) {
                Response::json(400, [
                    "success" => false,
                    "message" => "product ID is required"
                ]);
            }

            $body = getJsonBody();
            $data = validateProductFields($body);

            $existingproduct = $productModel->getById($id);

            if ($existingproduct === null) {
                Response::json(404, [
                    "success" => false,
                    "message" => "product not found"
                ]);
            }

            $productModel->update(
                $id,
                $data["name"],
                $data["description"],
                $data["status"]
            );

            Response::json(200, [
                "success" => true,
                "message" => "product updated successfully",
                "data" => [
                    "id" => $id,
                    "name" => $data["name"],
                    "description" => $data["description"],
                    "status" => $data["status"],
                    "created_at" => $existingproduct["created_at"] ?? null
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

            $existingproduct = $productModel->getById($id);

            if ($existingproduct === null) {
                Response::json(404, [
                    "success" => false,
                    "message" => "product not found"
                ]);
            }

            $productModel->delete($id);

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
            "message" => "Email already exists or violates a database constraint"
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

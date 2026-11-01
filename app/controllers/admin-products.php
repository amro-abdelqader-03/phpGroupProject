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
    $image_url = $data["image_url"] ?? "";

    if (!is_string($product_name) || !is_string($description)) {
        Response::json(422, [
            "success" => false,
            "message" => "Name and description must be strings"
        ]);
    }

    $product_name = trim($product_name);
    $description = trim($description);

    if ($product_name === "") {
        Response::json(422, [
            "success" => false,
            "message" => "Product name is required"
        ]);
    }

    $subcategory_id = filter_var($subcategory_id, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
    if ($subcategory_id === false || !is_numeric($price) || !is_numeric($cost_price) || (float)$price < 0 || (float)$cost_price < 0) {
        Response::json(422, ["success" => false, "message" => "Enter a valid subcategory and non-negative prices"]);
    }
    if (!is_string($image_url) || strlen($image_url) > 500 || ($image_url !== "" && filter_var($image_url, FILTER_VALIDATE_URL) === false && !str_starts_with($image_url, "/"))) {
        Response::json(422, ["success" => false, "message" => "Enter a valid image URL or site path"]);
    }

    return [
        "product_name" => $product_name,
        "description" => $description,
        "subcategory_id" => $subcategory_id,
        "cost_price" => $cost_price,
        "price" => (float)$price,
        "image_url" => $image_url
    ];
}

function saveUploadedImage(array $file): string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        Response::json(422, ["success" => false, "message" => "An image could not be uploaded"]);
    }
    if (($file['size'] ?? 0) > 8 * 1024 * 1024 || !is_uploaded_file($file['tmp_name'] ?? '')) {
        Response::json(422, ["success" => false, "message" => "Images must be smaller than 8 MB"]);
    }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $extensions = ["image/jpeg" => "jpg", "image/png" => "png", "image/webp" => "webp", "image/gif" => "gif"];
    if (!isset($extensions[$mime])) {
        Response::json(422, ["success" => false, "message" => "Use a JPG, PNG, WEBP, or GIF image"]);
    }

    $relativeDirectory = "Data/uploads/products";
    $directory = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR, $relativeDirectory);
    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
        throw new RuntimeException("Could not create product image directory");
    }
    $filename = bin2hex(random_bytes(16)) . "." . $extensions[$mime];
    if (!move_uploaded_file($file['tmp_name'], $directory . DIRECTORY_SEPARATOR . $filename)) {
        throw new RuntimeException("Could not store product image");
    }
    return "/" . $relativeDirectory . "/" . $filename;
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
            $body = $_POST;
            $data = validateProductsFields($body);
            if (!isset($_FILES['primary_image'], $_FILES['extra_image_1'], $_FILES['extra_image_2'])) {
                Response::json(422, ["success" => false, "message" => "Choose a primary image and the two required additional images"]);
            }
            $images = [["url" => saveUploadedImage($_FILES['primary_image'])]];
            for ($i = 1; $i <= 4; $i++) {
                if (!isset($_FILES["extra_image_$i"])) {
                    if ($i <= 2) {
                        Response::json(422, ["success" => false, "message" => "Choose the two required additional images"]);
                    }
                    continue;
                }
                $images[] = ["url" => saveUploadedImage($_FILES["extra_image_$i"])];
            }
            $newId = $ProductsModel->create(
                $data["product_name"],
                $data["description"],
                $data["subcategory_id"],
                $data["price"],
                (float)$data["cost_price"],
                $images
            );

            Response::json(201, [
                "success" => true,
                "message" => "product created successfully",
                "data" => [
                    "id" => $newId,
                    "product_name" => $data["product_name"],
                    "description" => $data["description"],
                    "subcategory_id" => $data["subcategory_id"],
                    "price" => $data["price"],
                    "cost_price" => $data["cost_price"],
                    "image_url" => $images[0]["url"]
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

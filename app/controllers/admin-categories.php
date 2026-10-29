
<?php

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../models/admin-categories.php";
require_once __DIR__ . "/../../core/Response.php";
header("Content-Type: application/json; charset=UTF-8");

// Read and validate category ID
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
            "message" => "Invalid category ID"
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

// Validate category fields
function validateCategoriesFields(array $data): array
{
    $name = $data["name"] ?? "";
    $description = $data["description"] ?? "";
    $status = $data["status"] ?? "";

    if (
        !is_string($name) ||
        !is_string($description) ||
        !is_string($status)
    ) {
        Response::json(422, [
            "success" => false,
            "message" => "Name, description and status must be strings"
        ]);
    }

    $name = trim($name);
    $description = trim($description);
    $status = strtolower(trim($status));

    if ($name === "" || $description === "" || $status === "") {
        Response::json(422, [
            "success" => false,
            "message" => "Name, description and status are required"
        ]);
    }

    if (!in_array($status, ["active", "inactive"], true)) {
        Response::json(422, [
            "success" => false,
            "message" => "Status must be active or inactive"
        ]);
    }

    return [
        "name" => $name,
        "description" => $description,
        "status" => $status
    ];
}

// Read HTTP method
$method = $_SERVER["REQUEST_METHOD"];

// Connect to database
try {
    $database = new Database();
    $pdo = $database->connect();

    // Create categories model
    $categoriesModel = new categories($pdo);

    switch ($method) {

        // GET: Retrieve categories
        case "GET":

            if ($id !== null) {
                $category = $categoriesModel->get_by_id($id);

                if ($category === null) {
                    Response::json(404, [
                        "success" => false,
                        "message" => "Category not found"
                    ]);
                }

                Response::json(200, [
                    "success" => true,
                    "data" => $category
                ]);
            }

            $categories = $categoriesModel->get_Allcategory();

            Response::json(200, [
                "success" => true,
                "count" => count($categories),
                "data" => $categories
            ]);

        // POST: Create category
        case "POST":

            $body = getJsonBody();
            $data = validateCategoriesFields($body);

            $newId = $categoriesModel->create(
                $data["name"],
                $data["description"],
                $data["status"]
            );

            Response::json(201, [
                "success" => true,
                "message" => "Category created successfully",
                "data" => [
                    "id" => $newId,
                    "name" => $data["name"],
                    "description" => $data["description"],
                    "status" => $data["status"]
                ]
            ]);

        // PUT: Update category
        case "PUT":

            if ($id === null) {
                Response::json(400, [
                    "success" => false,
                    "message" => "Category ID is required"
                ]);
            }

            $body = getJsonBody();
            $data = validateCategoriesFields($body);

            $existingCategory = $categoriesModel->get_by_id($id);

            if ($existingCategory === null) {
                Response::json(404, [
                    "success" => false,
                    "message" => "Category not found"
                ]);
            }

            $categoriesModel->update(
                $id,
                $data["name"],
                $data["description"],
                $data["status"]
            );

            Response::json(200, [
                "success" => true,
                "message" => "Category updated successfully",
                "data" => [
                    "id" => $id,
                    "name" => $data["name"],
                    "description" => $data["description"],
                    "status" => $data["status"]
                ]
            ]);

        // DELETE: Delete category
        case "DELETE":

            if ($id === null) {
                Response::json(400, [
                    "success" => false,
                    "message" => "Category ID is required"
                ]);
            }

            $existingCategory = $categoriesModel->get_by_id($id);

            if ($existingCategory === null) {
                Response::json(404, [
                    "success" => false,
                    "message" => "Category not found"
                ]);
            }

            $categoriesModel->delete($id);

            Response::json(200, [
                "success" => true,
                "message" => "Category deleted successfully"
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

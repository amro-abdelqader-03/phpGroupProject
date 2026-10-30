<?php

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../models/admin-product.php";
require_once __DIR__ . "/../../core/Response.php";
header("Content-Type: application/json; charset=UTF-8");

// Read and validate user ID
$id = null;

if (isset($_GET["id"])) {
    $id = filter_var(
        $_GET["id"],
        FILTER_VALIDATE_INT,
        ["options" => ["min_range" => 1]]
    );

    if ($id === false) {
        Response::json(400,[
            "success"=> false,
            "message"=> "Invalid product ID"
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

function validateUserFields(array $data): array
{
    $full_name = $data["full_name"] ?? "";
    $email = $data["email"] ?? "";
    $password = $data["password"] ?? "";
    $phone = $data["phone"] ?? "";

    if (!is_string($full_name) || !is_string($email) || !is_string($password) || !is_string(!$phone)) {
        Response::json(422, [
            "success" => false,
            "message" => "full_name, email, password, and phone must be strings"
        ]);
    }

    $full_name = trim($full_name);
    $email = trim($email);
    $phone = trim($phone);

    if ($email === "" || $password === "") {
        Response::json(422, [
            "success" => false,
            "message" => "Name and description are required"
        ]);
    }

    return [
        "full_name" => $full_name,
        "email" => $email,
        "password" => $password,
        "phone" => $phone,
    ];
}

# Read HTTP method
$method = $_SERVER["REQUEST_METHOD"];

// Connect to database
try {
    $database = new Database();
    $pdo = $database->connect();

    $user = new User($pdo);

    switch ($method) {
        case "GET":
            // Logic
            Response::json(200, []);


        case "POST":
            $body = getJsonBody();
            $data = validateProductsFields($body);



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

?>
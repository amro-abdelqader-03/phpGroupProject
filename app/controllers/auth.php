<?php

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/../../core/Response.php";
require_once __DIR__ . '/../../public/session.php';

header("Content-Type: application/json; charset=UTF-8");

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
            "message" => "Invalid user ID"
        ]);
    }
}

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

$method = $_SERVER["REQUEST_METHOD"];

try {
    $database = new Database();
    $pdo = $database->connect();

    $user = new User($pdo);

    switch ($method) {
        case "GET":
            Response::json(200, []);
            break;

        case "POST":
            $body = getJsonBody();
            $action = $body["action"] ?? "register";

            if ($action === "login") {
                $email = trim($body["email"] ?? "");
                $password = $body["password"] ?? "";

                if ($email === "" || $password === "") {
                    Response::json(422, [
                        "success" => false,
                        "message" => "Email and password are required"
                    ]);
                }

                $userData = $user->findByEmail($email);

                if ($userData && password_verify($password, $userData["password_hash"])) {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }

                    session_regenerate_id(true);
                    unset($_SESSION['cart_id']);
                    $_SESSION['user_id'] = $userData["id"];
                    $_SESSION['user_name'] = $userData["full_name"];
                    $_SESSION['user_email'] = $userData["email"];

                    Response::json(200, [
                        "success" => true,
                        "message" => "Login successful",
                        "user" => [
                            "id" => $userData["id"],
                            "full_name" => $userData["full_name"],
                            "email" => $userData["email"]
                        ]
                    ]);
                } else {
                    Response::json(401, [
                        "success" => false,
                        "message" => "Invalid email or password"
                    ]);
                }

            } else {
                $full_name = trim($body["full_name"] ?? "");
                $email = trim($body["email"] ?? "");
                $password = $body["password"] ?? "";
                $phone = trim($body["phone"] ?? "");

                if ($email === "" || $password === "" || $full_name === "") {
                    Response::json(422, [
                        "success" => false,
                        "message" => "All required fields must be filled"
                    ]);
                }

                $userId = $user->create(
                    $full_name,
                    $email,
                    $password,
                    $phone !== "" ? $phone : null
                );

                Response::json(201, [
                    "success" => true,
                    "message" => "Account created successfully",
                    "user_id" => $userId
                ]);
            }
            break;

        default:
            Response::json(405, [
                "success" => false,
                "message" => "Method not allowed"
            ]);
            break;
    }

} catch (PDOException $e) {
    error_log($e->getMessage());

    if ($e->getCode() === "23000") {
        Response::json(409, [
            "success" => false,
            "message" => "Email already exists"
        ]);
    }

    Response::json(500, [
        "success" => false,
        "message" => "A database error occurred: " . $e->getMessage()
    ]);

} catch (Throwable $e) {
    error_log($e->getMessage());

    Response::json(500, [
        "success" => false,
        "message" => "An internal server error occurred: " . $e->getMessage()
    ]);
}
?>
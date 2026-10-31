
<?php

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../models/mensuits.php";
require_once __DIR__ . "/../../core/Response.php";

header("Content-Type: application/json; charset=UTF-8");

// Read and validate suits ID
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
            "message" => "Invalid suits ID"
        ]);
    }
}

// Read HTTP method
$method = $_SERVER["REQUEST_METHOD"];

// Connect to database
try {

    $database = new Database();
    $pdo = $database->connect();

    // Create suits model
    $suitsModel = new Mensuits($pdo);

    switch ($method) {

        // GET: Retrieve suits
        case "GET":

            // Retrieve one suit by ID
            if ($id !== null) {

                $suit = $suitsModel->getSuitById($id);

                if ($suit === false) {
                    Response::json(404, [
                        "success" => false,
                        "message" => "Suit not found"
                    ]);
                }

                // Retrieve suit images
                $images = $suitsModel->getSuitImages($id);

                // Retrieve suit variants
                $variants = $suitsModel->getSuitVariants($id);

                // Add images and variants to suit data
                $suit["images"] = $images;
                $suit["variants"] = $variants;

                Response::json(200, [
                    "success" => true,
                    "data" => $suit
                ]);
            }

            // Retrieve all suits
            $allSuits = $suitsModel->getAllSuits();

            Response::json(200, [
                "success" => true,
                "count" => count($allSuits),
                "data" => $allSuits
            ]);

        default:

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

?>
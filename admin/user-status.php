<?php
// =====================================================
// Admin - Change a user's status (Active / Inactive)
// This file has no HTML. It gets the form from the status
// popup in users.php, saves the change, then goes back.
// =====================================================

// STEP 1: Load the files we need
require_once __DIR__ . '/../app/config/Database.php';
require_once __DIR__ . '/../app/models/user.php';
require_once __DIR__ . '/includes/helpers.php';


// STEP 2: Only accept the form (POST). Opening this page in the browser just goes back.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: users.php");
    exit;
}


// STEP 3: Read what the form sent
$id = 0;
if (isset($_POST["id"])) {
    $id = (int) $_POST["id"];
}

$newStatus = null;
if (isset($_POST["new_status"])) {
    $newStatus = readStatus($_POST["new_status"]);   // 1, 0, or null if the value is wrong
}

// The filters and page the admin was on, so we can send them back to the same place
$returnSearch = "";
if (isset($_POST["return_search"])) {
    $returnSearch = trim($_POST["return_search"]);
}

$returnStatus = null;
if (isset($_POST["return_status"])) {
    $returnStatus = readStatus($_POST["return_status"]);
}

$returnCountry = "";
if (isset($_POST["return_country"])) {
    $returnCountry = trim($_POST["return_country"]);
}

$returnPage = 1;
if (isset($_POST["return_page"])) {
    $returnPage = (int) $_POST["return_page"];
}

$backLink = usersLink($returnPage, $returnSearch, $returnStatus, $returnCountry);


// STEP 4: Check the user exists and the new status is 1 or 0
$database = new Database();
$db = $database->connect();
$userModel = new User($db);

$user = $userModel->getById($id);

if ($user === null || $newStatus === null) {
    header("Location: " . $backLink . "&error=notfound");
    exit;
}


// STEP 5: Save the new status and go back to the list
$userModel->setStatus($id, $newStatus);

header("Location: " . $backLink . "&success=status");
exit;

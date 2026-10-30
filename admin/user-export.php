<?php
// =====================================================
// Admin - Export users to a CSV file
// Opened from the "Export CSV" button: user-export.php?status=1&country=Jordan
// It uses the same filters as the table, and downloads ALL matching users
// (not only the 5 on the current page). This file has no HTML.
// =====================================================

// STEP 1: Load the files we need
require_once __DIR__ . '/../app/config/Database.php';
require_once __DIR__ . '/../app/models/user.php';
require_once __DIR__ . '/includes/helpers.php';


// STEP 2: Connect to the database and create the User model
$database = new Database();
$db = $database->connect();
$userModel = new User($db);


// STEP 3: Read the filters from the URL (same as users.php)
$search = "";
if (isset($_GET["search"])) {
    $search = trim($_GET["search"]);
}

$country = "";
if (isset($_GET["country"])) {
    $country = trim($_GET["country"]);
}

$status = null;
if (isset($_GET["status"])) {
    $status = readStatus($_GET["status"]);
}


// STEP 4: Get ALL the users that match the filters
// We ask for as many users as there are, starting from the first one (offset 0)
$totalUsers = $userModel->countAll($search, $status, $country);
$users = $userModel->getAll($search, $status, $country, $totalUsers, 0);


// Spreadsheet programs like Excel run cells that start with = + - @ as formulas.
// A user could name themselves "=HYPERLINK(...)" to attack the admin, so we put a ' in front.
function csvSafe($text)
{
    if ($text === null) {
        return "";
    }

    $firstCharacter = substr($text, 0, 1);

    if (in_array($firstCharacter, ["=", "+", "-", "@"])) {
        return "'" . $text;
    }

    return $text;
}


// STEP 5: Tell the browser this is a file to download, not a page to show
// The file name has today's date, e.g. users-2026-10-30.csv
$fileName = "users-" . date("Y-m-d") . ".csv";

header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"" . $fileName . "\"");


// STEP 6: Write the CSV file
// php://output means "write straight to the download"
$file = fopen("php://output", "w");

// This invisible mark tells Excel the file is UTF-8, so Arabic names show correctly
fwrite($file, "\xEF\xBB\xBF");

// The first row is the column titles
fputcsv($file, ["ID", "Full Name", "Email", "Phone", "Status", "Country", "Total Orders", "Member Since"]);

// One row for each user
foreach ($users as $user) {

    if ($user["is_active"] == 1) {
        $statusText = "Active";
    } else {
        $statusText = "Inactive";
    }

    // fputcsv adds the commas and quotes for us
    fputcsv($file, [
        $user["id"],
        csvSafe($user["full_name"]),
        csvSafe($user["email"]),
        csvSafe($user["phone"]),
        $statusText,
        csvSafe($user["country"]),
        $user["total_orders"],
        date("Y-m-d", strtotime($user["created_at"])),
    ]);
}

fclose($file);
exit;

<?php
// =====================================================
// Helper functions used by the admin user pages
// =====================================================

// Makes text safe to print in HTML.
// Without this, a user could put <script> in their name and run code on the admin's page.
function safe($text)
{
    if ($text === null) {
        return "";
    }

    return htmlspecialchars($text, ENT_QUOTES, "UTF-8");
}

// Gets the first letters of a name: "Ahmed Hassan" -> "AH"
function getInitials($fullName)
{
    $words = explode(" ", trim($fullName));

    $firstWord = $words[0];
    $lastWord = $words[count($words) - 1];

    // mb_substr works with Arabic letters too (substr does not)
    $initials = mb_substr($firstWord, 0, 1);

    if (count($words) > 1) {
        $initials = $initials . mb_substr($lastWord, 0, 1);
    }

    return mb_strtoupper($initials);
}

// Gets a short code for a country: "United States" -> "US"
function getCountryCode($country)
{
    $codes = [
        "United States"        => "US",
        "United Kingdom"       => "UK",
        "United Arab Emirates" => "AE",
        "Saudi Arabia"         => "SA",
        "Jordan"               => "JO",
        "Canada"               => "CA",
        "Australia"            => "AU",
    ];

    if (isset($codes[$country])) {
        return $codes[$country];
    }

    // Country not in the list: use its first 2 letters
    return mb_strtoupper(mb_substr($country, 0, 2));
}

// Reads the status filter: "1" -> 1 (active), "0" -> 0 (inactive), anything else -> null (all)
function readStatus($value)
{
    if ($value === "1") {
        return 1;
    }
    if ($value === "0") {
        return 0;
    }
    return null;
}

// Puts the filters that are being used into an array (empty filters are left out)
// Example: filterParams("ahmed", null, "") -> ["search" => "ahmed"]
function filterParams($search, $status, $country)
{
    $filters = [];

    if ($search !== "") {
        $filters["search"] = $search;
    }
    if ($status !== null) {
        $filters["status"] = $status;
    }
    if ($country !== "") {
        $filters["country"] = $country;
    }

    return $filters;
}

// Makes a link back to the users list, and keeps the filters and page in it
// Example: usersLink(2, "ahmed", 1, "Jordan") -> "users.php?search=ahmed&status=1&country=Jordan&page=2"
function usersLink($pageNumber, $search, $status, $country)
{
    $filters = filterParams($search, $status, $country);
    $filters["page"] = $pageNumber;

    // http_build_query turns the array into "search=ahmed&page=2" and encodes spaces and symbols
    return "users.php?" . http_build_query($filters);
}

// Makes the "Export CSV" link, with the same filters as the table
// Example: exportLink("", 0, "Jordan") -> "user-export.php?status=0&country=Jordan"
function exportLink($search, $status, $country)
{
    $filters = filterParams($search, $status, $country);

    if (count($filters) === 0) {
        return "user-export.php";
    }

    return "user-export.php?" . http_build_query($filters);
}

// Checks the Add / Edit form and returns a list of error messages (empty list = no errors)
function validateUserForm($fullName, $email, $phone)
{
    $errors = [];

    if ($fullName === "") {
        $errors[] = "Full name is required.";
    } elseif (mb_strlen($fullName) > 255) {
        $errors[] = "Full name must be 255 characters or less.";
    }

    if ($email === "") {
        $errors[] = "Email is required.";
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors[] = "Email is not valid.";
    }

    if (mb_strlen($phone) > 20) {
        $errors[] = "Phone number must be 20 characters or less.";
    }

    return $errors;
}

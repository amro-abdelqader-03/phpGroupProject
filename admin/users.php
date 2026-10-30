<?php
// =====================================================
// Admin - Users page
// Shows all users in a table, with search, filters and pages.
// =====================================================

require_once __DIR__ . '/../app/config/Database.php';  
require_once __DIR__ . '/../app/models/user.php';       // the User model ( CRUD methods)
require_once __DIR__ . '/includes/helpers.php';         // safe(), getInitials(), usersLink()...


// STEP 2: Connect to the database and create the User model
$database = new Database();
$db = $database->connect();

$userModel = new User($db);


// STEP 3: Read the filters from the URL
// If a filter is not in the URL, we use an empty value ("").

$search = "";
if (isset($_GET["search"])) {
    $search = trim($_GET["search"]);
}

$country = "";
if (isset($_GET["country"])) {
    $country = trim($_GET["country"]);
}

// Status: null = all users, 1 = active only, 0 = inactive only
$status = null;
if (isset($_GET["status"])) {
    $status = readStatus($_GET["status"]);
}


// STEP 4: Work out the pages
// Example: 8 users, 5 per page -> 2 pages
$usersPerPage = 5;

$totalUsers = $userModel->countAll($search, $status, $country);   // how many users match the filters

$totalPages = ceil($totalUsers / $usersPerPage);   // ceil rounds up: 8 / 5 = 1.6 -> 2
if ($totalPages < 1) {
    $totalPages = 1;   // always at least 1 page, even with no users
}

// Read the page number from the URL, e.g. users.php?page=2 (default is page 1)
$currentPage = 1;
if (isset($_GET["page"])) {
    $currentPage = (int) $_GET["page"];   // (int) turns "2" into 2, and bad text like "abc" into 0
}

// Keep the page number between 1 and the last page
if ($currentPage < 1) {
    $currentPage = 1;
}
if ($currentPage > $totalPages) {
    $currentPage = $totalPages;
}

// How many users to skip: page 1 skips 0, page 2 skips 5, page 3 skips 10...
$offset = ($currentPage - 1) * $usersPerPage;


// STEP 5: Get the data from the database using the model
$users = $userModel->getAll($search, $status, $country, $usersPerPage, $offset);   // the users for this page
$countries = $userModel->getCountries();                                          // the list for "All Locations"

// For the footer text "Showing 6–8 of 8"
$firstShown = $offset + 1;
$lastShown = $offset + count($users);


// STEP 6: Message to show after Add / Edit / Status change
// The other pages send us back here with e.g. users.php?success=added
$message = "";
$messageType = "success";

if (isset($_GET["success"])) {
    if ($_GET["success"] === "added") {
        $message = "The user was added.";
    } elseif ($_GET["success"] === "updated") {
        $message = "The user was updated.";
    } elseif ($_GET["success"] === "status") {
        $message = "The user's status was changed.";
    }
}

if (isset($_GET["error"]) && $_GET["error"] === "notfound") {
    $message = "That user was not found.";
    $messageType = "danger";
}


// STEP 7: Show the page
$pageTitle = "Users Management";
$breadcrumb = "Users";
require __DIR__ . '/includes/header.php';   // <head>, sidebar and top bar
?>

                <!-- Page Header Title Area -->
                <div class="page-header">
                    <div>
                        <h1 class="page-title">Users</h1>
                        <p class="page-subtitle">Manage and monitor your registered users across all locations.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <!-- Downloads the users that match the current filters -->
                        <a href="<?php echo safe(exportLink($search, $status, $country)); ?>" class="btn btn-outline-custom">
                            <i class="bi bi-download me-1"></i> Export CSV
                        </a>
                        <a href="user-add.php" class="btn btn-brand">
                            <i class="bi bi-person-plus me-1"></i> Add User
                        </a>
                    </div>
                </div>

                <!-- Success / error message -->
                <?php if ($message !== "") { ?>
                    <div class="alert alert-<?php echo $messageType; ?>"><?php echo $message; ?></div>
                <?php } ?>

                <!-- ============ FILTERS ============ -->
                <!-- method="get" puts the filters in the URL, so PHP can read them with $_GET (STEP 3) -->
                <form method="get" action="users.php" class="filter-card">
                    <div class="row g-3">

                        <!-- Search box -->
                        <div class="col-lg-4 col-md-12">
                            <div class="filter-search">
                                <i class="bi bi-search"></i>
                                <input type="text" name="search" class="form-control"
                                       value="<?php echo safe($search); ?>"
                                       placeholder="Search by name, email, or phone number...">
                            </div>
                        </div>

                        <!-- Status filter -->
                        <div class="col-lg-2 col-md-3">
                            <select name="status" class="form-select form-select-custom">
                                <option value="">All Statuses</option>

                                <?php if ($status === 1) { ?>
                                    <option value="1" selected>Active</option>
                                <?php } else { ?>
                                    <option value="1">Active</option>
                                <?php } ?>

                                <?php if ($status === 0) { ?>
                                    <option value="0" selected>Inactive</option>
                                <?php } else { ?>
                                    <option value="0">Inactive</option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Location filter: one option for each country in the database -->
                        <div class="col-lg-2 col-md-3">
                            <select name="country" class="form-select form-select-custom">
                                <option value="">All Locations</option>

                                <?php foreach ($countries as $countryName) { ?>
                                    <?php if ($countryName === $country) { ?>
                                        <option value="<?php echo safe($countryName); ?>" selected><?php echo safe($countryName); ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo safe($countryName); ?>"><?php echo safe($countryName); ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Apply: sends the form, so the filters go into the URL -->
                        <div class="col-lg-2 col-md-3">
                            <button type="submit" class="btn btn-brand w-100">
                                <i class="bi bi-funnel me-1"></i> Apply
                            </button>
                        </div>

                        <!-- Reset: opens the page again with no filters in the URL -->
                        <div class="col-lg-2 col-md-3 text-end">
                            <a href="users.php" class="btn btn-reset w-100 justify-content-center">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset Filters
                            </a>
                        </div>

                    </div>
                </form>

                <!-- ============ USERS TABLE ============ -->
                <div class="table-card">
                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>USER</th>
                                    <th>EMAIL</th>
                                    <th>PHONE NUMBER</th>
                                    <th class="text-center">TOTAL ORDERS</th>
                                    <th>STATUS</th>
                                    <th>LOCATION</th>
                                    <th class="text-end">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>

                                <!-- If no users match the filters, show a message -->
                                <?php if (count($users) === 0) { ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">No users found.</td>
                                    </tr>
                                <?php } ?>

                                <!-- One table row for each user -->
                                <?php foreach ($users as $user) { ?>

                                    <?php
                                    // Prepare the values for this row before printing them

                                    // "Member since Jan 2026"
                                    $memberSince = date("M Y", strtotime($user["created_at"]));

                                    // Phone: show "—" if the user has no phone
                                    $phone = "—";
                                    if ($user["phone"] !== null && $user["phone"] !== "") {
                                        $phone = $user["phone"];
                                    }

                                    // Status badge: green "Active" or red "Inactive"
                                    if ($user["is_active"] == 1) {
                                        $statusText = "Active";
                                        $statusClass = "btn-status-pill";
                                    } else {
                                        $statusText = "Inactive";
                                        $statusClass = "btn-status-pill inactive";
                                    }
                                    ?>

                                    <tr>
                                        <!-- User: initials circle, name and "Member since" -->
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="user-initials"><?php echo safe(getInitials($user["full_name"])); ?></div>
                                                <div>
                                                    <div class="user-title"><?php echo safe($user["full_name"]); ?></div>
                                                    <div class="member-since">Member since <?php echo $memberSince; ?></div>
                                                </div>
                                            </div>
                                        </td>

                                        <td><?php echo safe($user["email"]); ?></td>

                                        <!-- text-nowrap keeps the phone number on one line -->
                                        <td class="text-nowrap"><?php echo safe($phone); ?></td>

                                        <td class="text-center">
                                            <span class="orders-pill"><?php echo $user["total_orders"]; ?></span>
                                        </td>

                                        <!-- Status: click the badge to open "Change Status".
                                             <details> opens and closes by itself, no JavaScript needed. -->
                                        <td>
                                            <details class="status-dropdown-wrapper">
                                                <summary class="<?php echo $statusClass; ?>">
                                                    <?php echo $statusText; ?> <i class="bi bi-chevron-down"></i>
                                                </summary>

                                                <!-- The buttons send this form to user-status.php -->
                                                <form method="post" action="user-status.php" class="status-popover">
                                                    <div class="popover-header-title">CHANGE STATUS</div>

                                                    <!-- Which user to change -->
                                                    <input type="hidden" name="id" value="<?php echo $user["id"]; ?>">

                                                    <!-- The current filters and page, so we come back to the same place -->
                                                    <input type="hidden" name="return_search" value="<?php echo safe($search); ?>">
                                                    <input type="hidden" name="return_status" value="<?php echo safe((string) $status); ?>">
                                                    <input type="hidden" name="return_country" value="<?php echo safe($country); ?>">
                                                    <input type="hidden" name="return_page" value="<?php echo $currentPage; ?>">

                                                    <?php if ($user["is_active"] == 1) { ?>
                                                        <button type="submit" name="new_status" value="1" class="popover-item selected">
                                                            <i class="bi bi-check-circle"></i> Keep Active
                                                        </button>
                                                        <button type="submit" name="new_status" value="0" class="popover-item text-danger">
                                                            <i class="bi bi-x-circle"></i> Set as Inactive
                                                        </button>
                                                    <?php } else { ?>
                                                        <button type="submit" name="new_status" value="1" class="popover-item">
                                                            <i class="bi bi-check-circle text-success"></i> Set as Active
                                                        </button>
                                                        <button type="submit" name="new_status" value="0" class="popover-item text-danger">
                                                            <i class="bi bi-x-circle"></i> Keep Inactive
                                                        </button>
                                                    <?php } ?>
                                                </form>
                                            </details>
                                        </td>

                                        <!-- Location: country code + country name, or "—" if no address -->
                                        <td>
                                            <?php if ($user["country"] !== null) { ?>
                                                <span class="country-code"><?php echo safe(getCountryCode($user["country"])); ?></span>
                                                <?php echo safe($user["country"]); ?>
                                            <?php } else { ?>
                                                —
                                            <?php } ?>
                                        </td>

                                        <!-- View and Edit: normal links with the user's id in the URL -->
                                        <td class="text-end text-nowrap">
                                            <a href="user-view.php?id=<?php echo $user["id"]; ?>" class="action-btn" title="View"><i class="bi bi-eye"></i></a>
                                            <a href="user-edit.php?id=<?php echo $user["id"]; ?>" class="action-btn" title="Edit"><i class="bi bi-pencil"></i></a>
                                        </td>
                                    </tr>

                                <?php } ?>

                            </tbody>
                        </table>
                    </div>

                    <!-- ============ FOOTER ============ -->
                    <div class="table-footer">

                        <!-- "Showing 6–8 of 8 registered users" -->
                        <div class="pagination-info">
                            <?php if (count($users) === 0) { ?>
                                Showing <strong>0</strong>
                            <?php } else { ?>
                                Showing <strong><?php echo $firstShown; ?>–<?php echo $lastShown; ?></strong>
                            <?php } ?>
                            of <strong><?php echo $totalUsers; ?></strong> registered users
                        </div>

                        <!-- Page buttons: normal links like users.php?page=2 (the filters stay in the link) -->
                        <ul class="custom-pagination">

                            <!-- Previous page arrow (greyed out on the first page) -->
                            <?php if ($currentPage > 1) { ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo safe(usersLink($currentPage - 1, $search, $status, $country)); ?>" title="Previous">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                </li>
                            <?php } else { ?>
                                <li class="page-item disabled">
                                    <span class="page-link"><i class="bi bi-chevron-left"></i></span>
                                </li>
                            <?php } ?>

                            <!-- One button for each page: 1, 2, 3... -->
                            <?php for ($pageNumber = 1; $pageNumber <= $totalPages; $pageNumber++) { ?>
                                <?php if ($pageNumber == $currentPage) { ?>
                                    <li class="page-item active">
                                        <span class="page-link"><?php echo $pageNumber; ?></span>
                                    </li>
                                <?php } else { ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo safe(usersLink($pageNumber, $search, $status, $country)); ?>"><?php echo $pageNumber; ?></a>
                                    </li>
                                <?php } ?>
                            <?php } ?>

                            <!-- Next page arrow (greyed out on the last page) -->
                            <?php if ($currentPage < $totalPages) { ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo safe(usersLink($currentPage + 1, $search, $status, $country)); ?>" title="Next">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            <?php } else { ?>
                                <li class="page-item disabled">
                                    <span class="page-link"><i class="bi bi-chevron-right"></i></span>
                                </li>
                            <?php } ?>

                        </ul>
                    </div>
                </div>

<?php require __DIR__ . '/includes/footer.php'; ?>

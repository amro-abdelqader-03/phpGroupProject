<?php
// =====================================================
// Admin - View one user
// Opened from the eye icon: user-view.php?id=5
// Shows the user's details, addresses and latest orders.
// =====================================================

// STEP 1: Load the files we need
require_once __DIR__ . '/../app/config/Database.php';
require_once __DIR__ . '/../app/models/user.php';
require_once __DIR__ . '/includes/helpers.php';



// STEP 2: Connect to the database and create the User model
$database = new Database();
$db = $database->connect();
$userModel = new User($db);


// STEP 3: Find the user from the id in the URL
$id = 0;
if (isset($_GET["id"])) {
    $id = (int) $_GET["id"];
}

$user = $userModel->getById($id);

if ($user === null) {
    header("Location: users.php?error=notfound");
    exit;
}


// STEP 4: Get the user's addresses and latest orders
$addresses = $userModel->getAddresses($id);
$orders = $userModel->getOrders($id, 10);


// STEP 5: Prepare the values to show
$memberSince = date("d M Y", strtotime($user["created_at"]));

$phone = "—";
if ($user["phone"] !== null && $user["phone"] !== "") {
    $phone = $user["phone"];
}

if ($user["is_active"] == 1) {
    $statusText = "Active";
    $statusClass = "btn-status-pill";
} else {
    $statusText = "Inactive";
    $statusClass = "btn-status-pill inactive";
}


// STEP 6: Show the page
$pageTitle = "View User";
$breadcrumb = "Users / View User";
require __DIR__ . '/includes/header.php';
?>

                <div class="page-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="user-initials user-initials-lg"><?php echo safe(getInitials($user["full_name"])); ?></div>
                        <div>
                            <h1 class="page-title"><?php echo safe($user["full_name"]); ?></h1>
                            <p class="page-subtitle">Member since <?php echo $memberSince; ?></p>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="users.php" class="btn btn-outline-custom">
                            <i class="bi bi-arrow-left me-1"></i> Back to Users
                        </a>
                        <!-- Opens the users list with this user's Edit popup open -->
                        <a href="users.php?edit=<?php echo $user["id"]; ?>#edit-user-<?php echo $user["id"]; ?>" class="btn btn-brand">
                            <i class="bi bi-pencil me-1"></i> Edit User
                        </a>
                    </div>
                </div>

                <!-- ============ DETAILS ============ -->
                <div class="form-card mb-4">
                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="detail-label">Email</div>
                            <div class="detail-value"><?php echo safe($user["email"]); ?></div>
                        </div>
                        <div class="col-md-3">
                            <div class="detail-label">Phone number</div>
                            <div class="detail-value"><?php echo safe($phone); ?></div>
                        </div>
                        <div class="col-md-2">
                            <div class="detail-label">Status</div>
                            <div class="detail-value"><span class="<?php echo $statusClass; ?>"><?php echo $statusText; ?></span></div>
                        </div>
                        <div class="col-md-2">
                            <div class="detail-label">Total orders</div>
                            <div class="detail-value"><?php echo $user["total_orders"]; ?></div>
                        </div>
                        <div class="col-md-2">
                            <div class="detail-label">User ID</div>
                            <div class="detail-value">#<?php echo $user["id"]; ?></div>
                        </div>
                    </div>
                </div>

                <!-- ============ ADDRESSES ============ -->
                <h2 class="section-title">Addresses</h2>
                <div class="table-card mb-4">
                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>NAME</th>
                                    <th>COUNTRY</th>
                                    <th>CITY</th>
                                    <th>STREET</th>
                                    <th>BUILDING</th>
                                    <th>PHONE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($addresses) === 0) { ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">This user has no addresses.</td>
                                    </tr>
                                <?php } ?>

                                <?php foreach ($addresses as $address) { ?>
                                    <tr>
                                        <td><?php echo safe($address["full_name"]); ?></td>
                                        <td>
                                            <span class="country-code"><?php echo safe(getCountryCode($address["country"])); ?></span>
                                            <?php echo safe($address["country"]); ?>
                                        </td>
                                        <td><?php echo safe($address["city"]); ?></td>
                                        <td><?php echo safe($address["street"]); ?></td>
                                        <td><?php echo $address["building"] ? safe($address["building"]) : "—"; ?></td>
                                        <td class="text-nowrap"><?php echo safe($address["phone"]); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ============ LATEST ORDERS ============ -->
                <h2 class="section-title">Latest Orders</h2>
                <div class="table-card">
                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>ORDER</th>
                                    <th>DATE</th>
                                    <th>STATUS</th>
                                    <th class="text-end">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($orders) === 0) { ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">This user has no orders yet.</td>
                                    </tr>
                                <?php } ?>

                                <?php foreach ($orders as $order) { ?>
                                    <tr>
                                        <td>#<?php echo $order["id"]; ?></td>
                                        <td><?php echo date("d M Y", strtotime($order["order_date"])); ?></td>
                                        <td><span class="badge-category"><?php echo safe($order["order_status"]); ?></span></td>
                                        <td class="text-end"><span class="price-text"><?php echo number_format($order["total_amount"], 2); ?> JOD</span></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

<?php require __DIR__ . '/includes/footer.php'; ?>

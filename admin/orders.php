<?php
session_start();

// Protect admin page
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../app/models/Order.php';
require_once __DIR__ . '/includes/helpers.php';

$orderModel = new Order();
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status = strtolower(trim($_GET['status'] ?? 'all'));
if (!in_array($status, ['all', 'pending', 'confirmed', 'shipped', 'delivered', 'cancelled'], true)) {
    $status = 'all';
}
$sort = $_GET['sort'] ?? 'newest';
if (!in_array($sort, ['newest', 'revenue'], true)) {
    $sort = 'newest';
}
$filterParams = [];
if ($search !== '') {
    $filterParams['search'] = $search;
}
if ($status !== 'all') {
    $filterParams['status'] = $status;
}
if ($sort !== 'newest') {
    $filterParams['sort'] = $sort;
}
$filterQuery = http_build_query($filterParams);

// Delete order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order_id'])) {
    try {
        $deleted = $orderModel->deleteOrder((int) $_POST['delete_order_id']);
        $deleteStatus = $deleted ? 'deleted' : 'protected';
    } catch (PDOException $e) {
        error_log($e->getMessage());
        $deleteStatus = 'failed';
    }

    $redirectParams = ['page' => max(1, (int) ($_GET['page'] ?? 1))] + $filterParams;
    $redirectParams['delete'] = $deleteStatus;
    header('Location: orders.php?' . http_build_query($redirectParams));
    exit;
}

$limit = 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$page = max(1, $page);
$offset = ($page - 1) * $limit;

$orders = $orderModel->getOrdersPaginated($limit, $offset, $search, $status, $sort);
$totalOrders = $orderModel->getTotalOrdersCount($search, $status);
$totalPages = (int) ceil($totalOrders / $limit);

// Shared admin layout settings
$pageTitle = 'Orders';
$breadcrumb = 'Orders';
$activePage = 'orders';
$pageCss = '../public/assets/css/order.css';

require_once __DIR__ . '/includes/header.php';
?>

<div class="page-content">

    <div class="page-heading">
        <div>
            <h1>Orders</h1>
            <p>View and manage customer orders.</p>
        </div>

        <button class="button button-export" id="export-orders" type="button">
            <span aria-hidden="true">⇩</span> Export CSV
        </button>
    </div>

    <?php if (($_GET['delete'] ?? '') === 'protected'): ?>
        <div class="order-delete-notice" role="alert">
            This order has payment records and cannot be deleted. Cancel the order to preserve its payment history.
        </div>
    <?php elseif (($_GET['delete'] ?? '') === 'failed'): ?>
        <div class="order-delete-notice" role="alert">
            The order could not be deleted because it is referenced by other records.
        </div>
    <?php endif; ?>

    <section class="orders-card" aria-label="Order list">

        <form method="GET" action="orders.php" class="filters">

            <label class="table-search">
                <span aria-hidden="true">⌕</span>

                <input
                    type="search"
                    name="search"
                    id="order-search"
                    value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Search by customer name or ID..."
                    aria-label="Search by customer name or ID"
                >
            </label>

            <label class="filter-control">
                <span class="filter-label">Status</span>

                <select id="status-filter" name="status" aria-label="Filter by status">
                    <option value="all" <?= $status === 'all' ? 'selected' : '' ?>>All statuses</option>
                    <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="confirmed" <?= $status === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                    <option value="shipped" <?= $status === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                    <option value="delivered" <?= $status === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                    <option value="cancelled" <?= $status === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </label>

            <label class="filter-control sort-control">
                <span class="filter-label">Sort by</span>

                <select id="sort-orders" name="sort" aria-label="Sort orders">
                    <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest first</option>
                    <option value="revenue" <?= $sort === 'revenue' ? 'selected' : '' ?>>Highest revenue</option>
                </select>
            </label>

            <button type="submit" class="button">Apply filters</button>

            <?php if ($filterParams): ?>
                <a
                    href="orders.php"
                    class="button"
                    style="align-self: center; text-decoration: none;"
                >
                    Clear
                </a>
            <?php endif; ?>

        </form>

        <div class="table-wrap">

            <table class="orders-table">

                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">User</th>
                        <th scope="col">Start Date</th>
                        <th scope="col">End Date</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="numeric">Total Price</th>
                        <th scope="col" class="numeric">Cost</th>
                        <th scope="col" class="numeric">Revenue</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>

                <tbody id="orders-body">

                    <?php if (!empty($orders)): ?>

                        <?php foreach ($orders as $index => $order): ?>

                            <?php
                                $currentStatus = strtolower($order['order_status'] ?? 'pending');
                                $isCancelled = ($currentStatus === 'cancelled');
                                $delay = ($index + 1) * 0.08;
                            ?>

                            <tr style="--row-delay: <?= $delay ?>s;">

                                <td>
                                    <strong class="order-id">
                                        #<?= (int) $order['id'] ?>
                                    </strong>
                                </td>

                                <td>
                                    <div class="customer">

                                        <span class="avatar-initials">
                                            <?= htmlspecialchars(
                                                strtoupper(substr($order['full_name'] ?? '', 0, 1)),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </span>

                                        <span>
                                            <?= htmlspecialchars(
                                                $order['full_name'] ?? '',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </span>

                                    </div>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $order['created_at'] ?? '',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>

                                <td>—</td>

                                <td>
                                    <select
                                        class="status-badge status-<?= htmlspecialchars($currentStatus, ENT_QUOTES, 'UTF-8') ?>"
                                        onchange="updateOrderStatus(<?= (int) $order['id'] ?>, this.value)"
                                        <?= $isCancelled ? 'disabled style="background-color: #ffe1df; color: #b32625; cursor: not-allowed;"' : '' ?>
                                    >

                                        <option value="pending" <?= $currentStatus === 'pending' ? 'selected' : '' ?>>
                                            Pending
                                        </option>

                                        <option value="confirmed" <?= $currentStatus === 'confirmed' ? 'selected' : '' ?>>
                                            Confirmed
                                        </option>

                                        <option value="shipped" <?= $currentStatus === 'shipped' ? 'selected' : '' ?>>
                                            Shipped
                                        </option>

                                        <option value="delivered" <?= $currentStatus === 'delivered' ? 'selected' : '' ?>>
                                            Delivered
                                        </option>

                                        <option value="cancelled" <?= $currentStatus === 'cancelled' ? 'selected' : '' ?>>
                                            Cancelled
                                        </option>

                                    </select>
                                </td>

                                <td class="numeric amount">
                                    $<?= number_format((float) ($order['total_amount'] ?? 0), 2) ?>
                                </td>

                                <td class="numeric cost-unavailable">
                                    $0.00
                                </td>

                                <td class="numeric muted-amount">
                                    $<?= number_format((float) ($order['total_amount'] ?? 0), 2) ?>
                                </td>

                                <td>

                                    <form
                                        class="delete-form"
                                        action="orders.php?page=<?= $page ?><?= $filterQuery !== '' ? '&' . htmlspecialchars($filterQuery, ENT_QUOTES, 'UTF-8') : '' ?>"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this order?');"
                                        style="display:inline;"
                                    >

                                        <input
                                            type="hidden"
                                            name="delete_order_id"
                                            value="<?= (int) $order['id'] ?>"
                                        >

                                        <button class="button-delete" type="submit">
                                            Delete
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td class="empty-state" colspan="9">
                                No orders found in database.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

        <footer class="table-footer">

            <p id="orders-summary">
                Total Orders Found:
                <strong><?= (int) $totalOrders ?></strong>
            </p>

            <?php if ($totalPages > 1): ?>

                <nav class="pagination" aria-label="Page navigation">

                    <?php $paginationQuery = $filterQuery !== '' ? '&' . htmlspecialchars($filterQuery, ENT_QUOTES, 'UTF-8') : ''; ?>

                    <?php if ($page > 1): ?>

                        <a
                            class="page-arrow"
                            href="orders.php?page=<?= $page - 1 ?><?= $paginationQuery ?>"
                        >
                            ‹
                        </a>

                    <?php else: ?>

                        <span class="page-arrow disabled">‹</span>

                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>

                        <a
                            href="orders.php?page=<?= $i ?><?= $paginationQuery ?>"
                            class="page-number <?= $page === $i ? 'current' : '' ?>"
                        >
                            <?= $i ?>
                        </a>

                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>

                        <a
                            class="page-arrow"
                            href="orders.php?page=<?= $page + 1 ?><?= $paginationQuery ?>"
                        >
                            ›
                        </a>

                    <?php else: ?>

                        <span class="page-arrow disabled">›</span>

                    <?php endif; ?>

                </nav>

            <?php endif; ?>

        </footer>

    </section>

</div>

<script>

// This function currently only logs the requested change.
// It does not save the new status to the database.

function updateOrderStatus(orderId, newStatus) {

    if (newStatus === 'cancelled') {

        if (!confirm('Are you sure you want to cancel this order? Once cancelled, it cannot be changed again.')) {
            location.reload();
            return;
        }

    }

    console.log(`Updating order ${orderId} to status: ${newStatus}`);

}


// Export Orders to CSV

document.getElementById('export-orders')?.addEventListener('click', function () {

    const rows = document.querySelectorAll('.orders-table tr');
    const csv = [];

    rows.forEach(row => {

        const cols = row.querySelectorAll('th, td');
        const rowData = [];

        cols.forEach((col, index) => {

            if (index < cols.length - 1) {

                const text = col.innerText.replace(/"/g, '""');
                rowData.push('"' + text + '"');

            }

        });

        csv.push(rowData.join(','));

    });

    const csvFile = new Blob(
        [csv.join('\n')],
        { type: 'text/csv;charset=utf-8;' }
    );

    const downloadLink = document.createElement('a');

    downloadLink.download =
        'orders_export_' + new Date().toISOString().slice(0, 10) + '.csv';

    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';

    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);

    window.URL.revokeObjectURL(downloadLink.href);

});

</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
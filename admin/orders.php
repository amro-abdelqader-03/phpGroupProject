<?php
require_once __DIR__ . '/../app/models/Order.php';

$orderModel = new Order();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order_id'])) {
    $orderModel->deleteOrder((int)$_POST['delete_order_id']);
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
    header("Location: orders.php?page=" . $currentPage . (!empty($searchQuery) ? "&search=" . urlencode($searchQuery) : ""));
    exit();
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$limit = 5; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

$orders = $orderModel->getOrdersPaginated($limit, $offset, $search);
$totalOrders = $orderModel->getTotalOrdersCount($search);
$totalPages = ceil($totalOrders / $limit);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Orders | CoreAdmin</title>
    <link rel="stylesheet" href="../public/assets/css/order.css">
</head>
<body class="admin-page">
<aside class="sidebar" id="sidebar">
    <a class="brand" href="../public/index.php" aria-label="CoreAdmin home">
        <span class="brand-mark">img</span><span class="brand-name">CoreAdmin</span>
    </a>
    <div class="nav-caption">Navigation</div>
    <nav class="side-nav" aria-label="Admin navigation">
        <a href="dashboard.php"><span class="nav-icon">▦</span>Dashboard</a>
        <a href="users.php"><span class="nav-icon">♙</span>Users</a>
        <a href="categories.php"><span class="nav-icon">▤</span>Categories</a>
        <a href="products.php"><span class="nav-icon">▣</span>Products</a>
        <a href="orders.php" class="active" aria-current="page"><span class="nav-icon">▧</span>Orders</a>
    </nav>
    <div class="sidebar-bottom"><span class="collapse-icon">☰</span> Collapse Sidebar <kbd>⌘[</kbd></div>
</aside>

<div class="workspace">
    <header class="topbar">
        <button class="menu-button" type="button" aria-label="Toggle navigation">☰</button>
        <div class="breadcrumbs"><span>Home</span><span class="crumb-divider">/</span><strong>Orders</strong></div>
    </header>

    <main class="main-content">
        <div class="page-heading">
            <div><h1>Orders</h1><p>View and manage customer orders.</p></div>
            <button class="button button-export" id="export-orders" type="button"><span aria-hidden="true">⇩</span> Export CSV</button>
        </div>

        <section class="orders-card" aria-label="Order list">
            <!-- نموذج بحث يعمل عند الضغط على Enter أو النقر على زر البحث -->
            <form method="GET" action="orders.php" class="filters">
                <label class="table-search">
                    <span aria-hidden="true">⌕</span>
                    <input type="search" name="search" id="order-search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by customer name or ID..." aria-label="Search by customer name or ID">
                </label>
                <label class="filter-control">
                    <span class="filter-label">Status</span>
                    <select id="status-filter" aria-label="Filter by status">
                        <option value="all">All statuses</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="pending">Pending</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </label>
                <label class="filter-control sort-control">
                    <span class="filter-label">Sort by</span>
                    <select id="sort-orders" aria-label="Sort orders">
                        <option value="newest">Newest first</option>
                        <option value="revenue">Highest revenue</option>
                    </select>
                </label>
                <?php if (!empty($search)): ?>
                    <a href="orders.php" class="button" style="padding: 6px 12px; align-self: center; text-decoration: none; font-size: 13px;">Clear</a>
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
                                    $currentStatus = strtolower($order['order_status']);
                                    $endDate = $order['delivery_date'] ?? null;
                                    
                                    if ($currentStatus === 'pending' && !empty($endDate) && strtotime($endDate) < time()) {
                                        $currentStatus = 'confirmed';
                                    }
                                    
                                    $isCancelled = ($currentStatus === 'cancelled');
                                    $delay = ($index + 1) * 0.08;
                                ?>
                                <tr style="--row-delay: <?php echo $delay; ?>s;">
                                    <td><strong class="order-id">#<?php echo $order['id']; ?></strong></td>
                                    <td>
                                        <div class="customer">
                                            <span class="avatar-initials"><?php echo strtoupper(substr($order['full_name'], 0, 1)); ?></span>
                                            <span><?php echo htmlspecialchars($order['full_name']); ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                                    <td><?php echo !empty($order['delivery_date']) ? htmlspecialchars($order['delivery_date']) : '—'; ?></td>
                                    <td>
                                        <select class="status-badge status-<?php echo $currentStatus; ?>" 
                                                onchange="updateOrderStatus(<?php echo $order['id']; ?>, this.value)"
                                                <?php echo $isCancelled ? 'disabled style="background-color: #ffe1df; color: #b32625; cursor: not-allowed;"' : ''; ?>>
                                            <option value="pending" <?php echo ($currentStatus === 'pending') ? 'selected' : ''; ?>>Pending</option>
                                            <option value="confirmed" <?php echo ($currentStatus === 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                            <option value="cancelled" <?php echo ($currentStatus === 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                    </td>
                                    <td class="numeric amount">$<?php echo number_format($order['total_amount'], 2); ?></td>
                                    <td class="numeric cost-unavailable">$0.00</td>
                                    <td class="numeric muted-amount">$<?php echo number_format($order['total_amount'], 2); ?></td>
                                    <td>
                                        <form class="delete-form" action="orders.php?page=<?php echo $page; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this order?');" style="display:inline;">
                                            <input type="hidden" name="delete_order_id" value="<?php echo $order['id']; ?>">
                                            <button class="button-delete" type="submit">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td class="empty-state" colspan="9">No orders found in database.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <footer class="table-footer">
                <p id="orders-summary">Total Orders Found: <strong><?php echo $totalOrders; ?></strong></p>
                
                <?php if ($totalPages > 1): ?>
                    <nav class="pagination" aria-label="Page navigation">
                        <?php 
                            $paginationQuery = !empty($search) ? '&search=' . urlencode($search) : '';
                        ?>
                        <?php if ($page > 1): ?>
                            <a class="page-arrow" href="orders.php?page=<?php echo $page - 1; ?><?php echo $paginationQuery; ?>">‹</a>
                        <?php else: ?>
                            <span class="page-arrow disabled">‹</span>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="orders.php?page=<?php echo $i; ?><?php echo $paginationQuery; ?>" class="page-number <?php echo ($page == $i) ? 'current' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <a class="page-arrow" href="orders.php?page=<?php echo $page + 1; ?><?php echo $paginationQuery; ?>">›</a>
                        <?php else: ?>
                            <span class="page-arrow disabled">›</span>
                        <?php endif; ?>
                    </nav>
                <?php endif; ?>
            </footer>
        </section>
    </main>
</div>

<script>
function updateOrderStatus(orderId, newStatus) {
    if (newStatus === 'cancelled') {
        if (!confirm('Are you sure you want to cancel this order? Once cancelled, it cannot be changed again.')) {
            location.reload();
            return;
        }
    }
    console.log(`Updating order ${orderId} to status: ${newStatus}`);
}

// تفعيل زر التصدير إلى CSV
document.getElementById('export-orders').addEventListener('click', function () {
    const rows = document.querySelectorAll('.orders-table tr');
    let csv = [];
    
    rows.forEach(row => {
        let cols = row.querySelectorAll('th, td');
        let rowData = [];
        
        cols.forEach((col, index) => {
            if (index < cols.length - 1) {
                let text = col.innerText.replace(/"/g, '""');
                rowData.push('"' + text + '"');
            }
        });
        
        csv.push(rowData.join(','));
    });
    
    let csvFile = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
    let downloadLink = document.createElement('a');
    downloadLink.download = 'orders_export_' + new Date().toISOString().slice(0, 10) + '.csv';
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';
    
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
});
</script>
</body>
</html>
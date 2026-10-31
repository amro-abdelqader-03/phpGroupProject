<?php

session_start();

// Protect admin dashboard
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Database connection
require_once __DIR__ . '/../app/config/Database.php';

// Dashboard Controller
require_once __DIR__ . '/../app/controllers/AdminDashboardController.php';

// Connect to database
$db = (new Database())->connect();

// Create Controller Object
$controller = new AdminDashboardController($db);

// Get dashboard statistics
$stats = $controller->getStatistics();

// Get top selling products from database
$topProducts = $controller->getTopProductsByOrders();

// Prepare product data for Chart.js
$productNames = array_column($topProducts, 'name');

$productSales = array_map(
    'intval',
    array_column($topProducts, 'total_sold')
);

// Get monthly revenue from database
$monthlyRevenue = $controller->getMonthlyRevenue();

// Get top 5 products by gross profit
$topRevenueProducts = $controller->getTopProductsByRevenue();

// Get orders grouped by country
$ordersByCountry = $controller->getOrdersByCountry();

// Get latest 5 orders from database
$recentOrders = $controller->getRecentOrders();

// Shared admin layout settings
require_once __DIR__ . '/includes/helpers.php';

$pageTitle = "Dashboard";
$breadcrumb = "Dashboard";
$activePage = "dashboard";
$pageCss = "../public/assets/css/adminDashboard.css";

require_once __DIR__ . '/includes/header.php';

?>

<!-- Leaflet Map CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

<!-- Dashboard Content -->
<div class="page-content">

    <h1>Dashboard</h1>
    <p>Overview of your store's performance.</p>

    <!-- Dashboard Statistics Cards -->
    <section class="stats-grid">

        <!-- Total Revenue -->
        <div class="stat-card">
            <div class="stat-header">
                <span>TOTAL REVENUE</span>
                <div class="stat-icon revenue-icon">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>

            <h2>
                $<?= number_format($stats['revenue'], 2) ?>
            </h2>

            <p>Total store revenue</p>
        </div>

        <!-- Total Customers -->
        <div class="stat-card">
            <div class="stat-header">
                <span>TOTAL CUSTOMERS</span>
                <div class="stat-icon customers-icon">
                    <i class="bi bi-people"></i>
                </div>
            </div>

            <h2><?= number_format($stats['customers']) ?></h2>

            <p>Registered customers</p>
        </div>

        <!-- Total Products -->
        <div class="stat-card">
            <div class="stat-header">
                <span>TOTAL PRODUCTS</span>
                <div class="stat-icon products-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>

            <h2><?= number_format($stats['products']) ?></h2>

            <p>Available store products</p>
        </div>

        <!-- Total Orders -->
        <div class="stat-card">
            <div class="stat-header">
                <span>TOTAL ORDERS</span>
                <div class="stat-icon orders-icon">
                    <i class="bi bi-bag-check"></i>
                </div>
            </div>

            <h2><?= number_format($stats['orders']) ?></h2>

            <p>All customer orders</p>
        </div>

    </section>

    <!-- Revenue Overview -->
    <section class="revenue-section">

        <div class="revenue-header">
            <div>
                <h2>Revenue Overview</h2>
                <p>Track your store's monthly performance.</p>
            </div>
        </div>

        <div class="revenue-chart">
            <canvas id="revenueChart"></canvas>
        </div>

    </section>

    <!-- Product Analytics -->
    <section class="product-analytics-grid">

        <!-- Products by Revenue -->
        <div class="analytics-card">
            <div class="analytics-heading">
                <div>
                    <h2>Products by Gross Profit</h2>
                    <p>Top 5 products by gross profit</p>
                </div>
                <i class="bi bi-three-dots-vertical"></i>
            </div>

            <div class="revenue-products">

                <?php if (!empty($topRevenueProducts)): ?>

                    <?php
                    $maxProfit = max(
                        0,
                        ...array_map(
                            'floatval',
                            array_column($topRevenueProducts, 'total_revenue')
                        )
                    );
                    ?>

                    <?php foreach ($topRevenueProducts as $index => $product): ?>

                        <?php
                        $profit = (float) $product['total_revenue'];

                        $percentage = $maxProfit > 0
                            ? max(0, $profit / $maxProfit * 100)
                            : 0;
                        ?>

                        <div class="revenue-product">
                            <div class="product-line">
                                <span>
                                    <b>#<?= $index + 1 ?></b>
                                    <?= htmlspecialchars(
                                        $product['name'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </span>

                                <strong>
                                    $<?= number_format($profit, 2) ?>
                                </strong>
                            </div>

                            <div class="analytics-track">
                                <div style="width: <?= $percentage ?>%"></div>
                            </div>
                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <p>No delivered product sales yet.</p>

                <?php endif; ?>

            </div>

            <div class="analytics-footer">
                <span>Top 5 profitable products</span>
                <a href="products.php">View All Products →</a>
            </div>
        </div>

        <!-- Products by Number of Orders -->
        <div class="analytics-card">
            <div class="analytics-heading">
                <div>
                    <h2>Products by Number of Orders</h2>
                    <p>Top 5 products by number of orders</p>
                </div>
                <span class="analytics-period">All Time</span>
            </div>

            <div class="orders-chart-area">
                <canvas id="productOrdersChart"></canvas>
            </div>

            <div class="analytics-footer">
                <span>
                    Total Orders (All Statuses):
                    <?= number_format($stats['orders']) ?>
                </span>
            </div>
        </div>

    </section>

        <!-- Orders by Country -->
    <section class="country-section">

        <div class="analytics-heading">
            <div>
                <h2>Orders by Country</h2>
                <p>Global geographic distribution and order density</p>
            </div>
            <span class="analytics-period">All Regions</span>
        </div>

        <div class="country-layout">

            <div class="country-map-column">
                <div class="country-map">
                    <div id="ordersWorldMap"></div>

                    <div class="map-message">
                        <strong>Global Order Distribution</strong>
                        <span>Geographic overview · Demo</span>
                    </div>

                    <div class="map-legend">
                        Order Density
                        <div class="legend-gradient"></div>
                        <small>Low &nbsp;&nbsp;&nbsp;&nbsp; High</small>
                    </div>
                </div>

                <div class="country-map-footer">
                    <span>
                        Active Shipping Destinations:
                        <?= count($ordersByCountry) ?> countries
                    </span>
                </div>
            </div>

            <div class="markets-column">

                <div class="markets-heading">
                    <strong>Top Performing Markets</strong>
                    <span>Share of Total</span>
                </div>

                <?php
                // Total orders excluding cancelled orders
                $totalCountryOrders = array_sum(
                    array_map(
                        'intval',
                        array_column($ordersByCountry, 'total_orders')
                    )
                );

                // Show top 5 countries
                $topCountries = array_slice($ordersByCountry, 0, 5);

                // Country display codes
                $countryCodes = [
                    'Jordan' => 'JO',
                    'United States' => 'US',
                    'Germany' => 'DE',
                    'United Kingdom' => 'GB',
                    'France' => 'FR',
                    'Canada' => 'CA'
                ];
                ?>

                <?php if (!empty($topCountries)): ?>

                    <?php foreach ($topCountries as $country): ?>

                        <?php
                        $name = $country['country'];
                        $orders = (int) $country['total_orders'];
                        $sales = (float) $country['total_sales'];

                        $percentage = $totalCountryOrders > 0
                            ? ($orders / $totalCountryOrders) * 100
                            : 0;

                        $code = $countryCodes[$name] ?? '';
                        ?>

                        <div class="market-item">

                            <div class="market-title">
                                <strong>
                                    <?= htmlspecialchars($code, ENT_QUOTES, 'UTF-8') ?>
                                    &nbsp;
                                    <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>
                                </strong>

                                <b><?= number_format($percentage, 1) ?>%</b>
                            </div>

                            <div class="analytics-track">
                                <div style="width: <?= $percentage ?>%"></div>
                            </div>

                            <div class="market-meta">
                                <span><?= number_format($orders) ?> orders</span>
                                <span>$<?= number_format($sales, 2) ?> sales</span>
                            </div>

                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <p>No orders by country yet.</p>

                <?php endif; ?>

            </div>

        </div>
    </section>

    <!-- Recent Orders -->
    <section class="recent-orders-section">

        <div class="recent-orders-header">
            <div>
                <h2>Recent Orders</h2>
                <p>Latest customer orders.</p>
            </div>
        </div>

        <div class="orders-table-wrapper">

            <table class="orders-table">

                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($recentOrders)): ?>

                        <?php foreach ($recentOrders as $order): ?>

                            <?php
                            $status = $order['order_status'];

                            $allowedStatuses = [
                                'Pending',
                                'Confirmed',
                                'Shipped',
                                'Delivered',
                                'Cancelled'
                            ];

                            $statusClass = in_array($status, $allowedStatuses, true)
                                ? strtolower($status)
                                : '';
                            ?>

                            <tr>
                                <td>
                                    #ORD-<?= (int) $order['id'] ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $order['full_name'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        date('Y-m-d', strtotime($order['order_date'])),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>

                                <td>
                                    $<?= number_format(
                                        (float) $order['total_amount'],
                                        2
                                    ) ?>
                                </td>

                                <td>
                                    <span class="order-status <?= $statusClass ?>">
                                        <?= htmlspecialchars(
                                            $status,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </span>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="5" class="text-center">
                                No orders found.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </section>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

<!-- Revenue Overview Chart -->
<script>
    // Month labels
    const months = [
        'Jan', 'Feb', 'Mar',
        'Apr', 'May', 'Jun',
        'Jul', 'Aug', 'Sep',
        'Oct', 'Nov', 'Dec'
    ];

    // Monthly revenue from PHP / MySQL
    const monthlyRevenue = <?= json_encode(
        $monthlyRevenue,
        JSON_NUMERIC_CHECK
    ) ?>;

    const chartElement = document.getElementById('revenueChart');

    if (chartElement && typeof Chart !== 'undefined') {
        new Chart(chartElement, {
            type: 'line',

            data: {
                labels: months,
                datasets: [{
                    label: 'Monthly Revenue',
                    data: monthlyRevenue,
                    borderColor: '#5145e8',
                    backgroundColor: 'rgba(81, 69, 232, 0.08)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#5145e8'
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    }
                },

                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    },

                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
</script>

<!-- Products by Number of Orders Chart -->
<script>
    // Product names from PHP / MySQL
    const productNames = <?= json_encode(
        $productNames,
        JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
    ) ?>;

    // Number of orders from PHP / MySQL
    const productSales = <?= json_encode($productSales) ?>;

    const productOrdersCanvas =
        document.getElementById('productOrdersChart');

    if (productOrdersCanvas && typeof Chart !== 'undefined') {
        new Chart(productOrdersCanvas, {
            type: 'bar',

            data: {
                labels: productNames,
                datasets: [{
                    label: 'Number of Orders',
                    data: productSales,
                    backgroundColor: [
                        '#3730db',
                        '#4f46e5',
                        '#625af4',
                        '#aaa5fa',
                        '#d9d6ff'
                    ],
                    borderRadius: 7,
                    maxBarThickness: 45
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    }
                },

                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    },

                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#edf0f7'
                        },
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }
</script>

<!-- Leaflet Map JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Interactive World Map -->
<script>
    // Real orders data from MySQL
    const ordersByCountry = <?= json_encode(
        $ordersByCountry,
        JSON_HEX_TAG | JSON_HEX_AMP |
        JSON_HEX_APOS | JSON_HEX_QUOT
    ) ?>;

    // Match database country names with GeoJSON names
    const countryAliases = {
        'US': 'United States of America',
        'USA': 'United States of America',
        'United States': 'United States of America',
        'UK': 'United Kingdom',
        'GB': 'United Kingdom'
    };

    function normalizeCountry(name) {
        const value = String(name || '').trim();
        return (countryAliases[value] || value).toLowerCase();
    }

    const countryData = {};

    ordersByCountry.forEach(row => {
        const key = normalizeCountry(row.country);

        countryData[key] = {
            orders: Number(row.total_orders),
            sales: Number(row.total_sales)
        };
    });

    const maxOrders = Math.max(
        1,
        ...Object.values(countryData).map(item => item.orders)
    );

    const worldMap = L.map('ordersWorldMap', {
        center: [20, 0],
        zoom: 1,
        zoomControl: false,
        scrollWheelZoom: false,
        attributionControl: false
    });

    fetch('https://raw.githubusercontent.com/datasets/geo-countries/master/data/countries.geojson')
        .then(response => {
            if (!response.ok) {
                throw new Error('Could not load world map');
            }

            return response.json();
        })
        .then(data => {

            const countryMarkers = {
                'jordan': [31.95, 35.93],
                'united states of america': [39.5, -98.35],
                'germany': [51.16, 10.45],
                'united kingdom': [54.5, -3.4],
                'france': [46.6, 2.2],
                'canada': [56.13, -106.35]
            };

            const countryLayer = L.geoJSON(data, {
                style: function(feature) {
                    const name = normalizeCountry(
                        feature.properties.name
                    );

                    const info = countryData[name];
                    const density = info
                        ? info.orders / maxOrders
                        : 0;

                    return {
                        fillColor: !info
                            ? '#d9d6f8'
                            : density >= 0.7
                                ? '#5145e8'
                                : density >= 0.4
                                    ? '#8177f1'
                                    : '#aaa4f8',
                        fillOpacity: 0.9,
                        color: '#ffffff',
                        weight: 1
                    };
                },

                onEachFeature: function(feature, layer) {

                    if (feature.properties.name === 'Antarctica') {
                        layer.setStyle({
                            fillOpacity: 0,
                            opacity: 0
                        });
                    }

                    const countryName =
                        feature.properties.name || 'Unknown';

                    const info = countryData[
                        normalizeCountry(countryName)
                    ];

                    const orders = info ? info.orders : 0;
                    const sales = info ? info.sales : 0;

                    layer.bindPopup(`
                        <strong>${countryName}</strong>
                        <div>${orders.toLocaleString()} orders</div>
                        <div>$${sales.toLocaleString(
                            'en-US',
                            {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }
                        )} sales</div>
                    `);
                }
            }).addTo(worldMap);

            // Add interactive markers for countries with orders
            Object.entries(countryData).forEach(([country, info]) => {

                const coordinates = countryMarkers[country];

                if (!coordinates || info.orders <= 0) {
                    return;
                }

                const marker = L.circleMarker(coordinates, {
                    radius: 5 + Math.min(info.orders, 10),
                    color: '#ffffff',
                    weight: 2,
                    fillColor: '#5145e8',
                    fillOpacity: 0.95
                }).addTo(worldMap);

                const countryName = country
                    .split(' ')
                    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                    .join(' ');

                marker.bindPopup(`
                    <div class="stitch-map-popup">
                        <strong>${countryName}</strong>
                        <span>${info.orders.toLocaleString()} orders</span>
                        <span>$${info.sales.toLocaleString('en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        })} sales</span>
                    </div>
                `);

                marker.bindTooltip(
                    `${countryName}: ${info.orders} orders`,
                    { direction: 'top' }
                );
            });

            worldMap.fitBounds(
                [[-55, -170], [75, 180]],
                {
                    padding: [0, 0],
                    animate: false
                }
            );

            worldMap.setZoom(worldMap.getZoom() + 0.5);

        })
        .catch(error => {
            console.error('World map error:', error);
        });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
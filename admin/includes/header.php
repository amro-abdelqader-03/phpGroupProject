<?php
// =====================================================
// Top of every admin page: <head>, sidebar and top bar.
// Before including this file, set:
//   $pageTitle  - text for the browser tab, e.g. "Users"
//   $breadcrumb - text after "Home /" in the top bar, e.g. "Users"
// =====================================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoreAdmin - <?php echo safe($pageTitle); ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Our styles: admin.css is shared by all admin pages, users.css is only for this page -->
    <link rel="stylesheet" href="../public/assets/css/admin.css">
    <link rel="stylesheet" href="../public/assets/css/users.css">
</head>
<body>

    <div id="wrapper">
        <!-- Sidebar Navigation -->
        <aside id="sidebar">
            <div class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <i class="bi bi-layers-fill"></i>
                </div>
                <div class="fw-bold fs-5 text-dark">CoreAdmin</div>
            </div>

            <div class="sidebar-heading">Navigation</div>

            <ul class="sidebar-nav">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-grid-1x2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="users.php" class="nav-link active">
                        <i class="bi bi-people me-2"></i> Users
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-tags me-2"></i> Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a href="products.php" class="nav-link">
                        <i class="bi bi-box-seam me-2"></i> Products
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-cart3 me-2"></i> Orders
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                <button class="collapse-btn">
                    <span><i class="bi bi-layout-sidebar me-2"></i> Collapse Sidebar</span>
                    <span class="badge bg-light text-muted border">⌘[</span>
                </button>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div id="content-wrapper">

            <!-- Top Navigation Bar -->
            <header class="top-navbar">
                <div class="top-navbar-left">
                    <button class="btn p-0 text-muted border-0 d-lg-none" id="sidebarToggle">
                        <i class="bi bi-list fs-4"></i>
                    </button>
                    <button class="btn p-0 text-muted border-0 d-none d-lg-block">
                        <i class="bi bi-list fs-4"></i>
                    </button>
                    <div class="breadcrumb-nav">
                        Home / <span><?php echo safe($breadcrumb); ?></span>
                    </div>
                </div>

                <div class="top-navbar-right">
                    <div class="global-search d-none d-md-block">
                        <i class="bi bi-search"></i>
                        <input type="text" class="form-control" placeholder="Search orders, products, users...">
                    </div>

                    <button class="icon-btn" title="Notifications">
                        <i class="bi bi-bell"></i>
                        <span class="notification-badge">4</span>
                    </button>

                    <button class="icon-btn" title="Messages">
                        <i class="bi bi-envelope"></i>
                    </button>

                    <div class="dropdown">
                        <div class="user-profile dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-info d-none d-sm-flex">
                                <span class="user-name">Eleanor Vance</span>
                                <span class="user-role">Store Admin</span>
                            </div>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Main Page Content -->
            <main class="main-container">

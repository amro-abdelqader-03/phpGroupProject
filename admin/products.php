<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoreAdmin - Products Management</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-main: #f8f9fa;
            --sidebar-width: 240px;
            --brand-primary: #4f46e5;
            --brand-primary-hover: #4338ca;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --badge-blue-bg: #e0e7ff;
            --badge-blue-text: #4338ca;
            --badge-green-bg: #dcfce7;
            --badge-green-text: #15803d;
            --badge-red-bg: #fee2e2;
            --badge-red-text: #b91c1c;
            --active-nav-bg: #eef2ff;
            --active-nav-text: #4f46e5;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-main);
            color: var(--text-dark);
            overflow-x: hidden;
            font-size: 0.875rem;
        }

        /* Layout Structure */
        #wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        #sidebar {
            width: var(--sidebar-width);
            background: #ffffff;
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.625rem;
            border-bottom: 1px solid var(--border-color);
            height: 64px;
        }

        .sidebar-brand-icon {
            width: 32px;
            height: 32px;
            background: #4f46e5;
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .sidebar-heading {
            padding: 1.25rem 1.25rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0 0.75rem;
            margin: 0;
            flex-grow: 1;
        }

        .sidebar-nav .nav-item {
            margin-bottom: 0.25rem;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            padding: 0.625rem 0.75rem;
            color: #475569;
            font-weight: 500;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.15s ease-in-out;
        }

        .sidebar-nav .nav-link:hover {
            color: var(--text-dark);
            background-color: #f1f5f9;
        }

        .sidebar-nav .nav-link.active {
            color: var(--active-nav-text);
            background-color: var(--active-nav-bg);
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 0.75rem 1rem;
            border-top: 1px solid var(--border-color);
            background: #ffffff;
        }

        .collapse-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 0.5rem 0.75rem;
            background: transparent;
            border: none;
            color: var(--text-muted);
            font-size: 0.8125rem;
            font-weight: 500;
            border-radius: 6px;
        }

        .collapse-btn:hover {
            background-color: #f1f5f9;
            color: var(--text-dark);
        }

        /* Main Content Container */
        #content-wrapper {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Top Navbar */
        .top-navbar {
            height: 64px;
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .top-navbar-left {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .breadcrumb-nav {
            font-size: 0.875rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .breadcrumb-nav span {
            color: var(--text-dark);
            font-weight: 600;
        }

        .global-search {
            width: 320px;
            position: relative;
        }

        .global-search input {
            background-color: #f1f5f9;
            border: 1px solid transparent;
            border-radius: 8px;
            padding-left: 2.25rem;
            font-size: 0.8125rem;
            height: 38px;
        }

        .global-search input:focus {
            background-color: #ffffff;
            border-color: var(--brand-primary);
            box-shadow: none;
        }

        .global-search .bi-search {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .top-navbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .icon-btn {
            position: relative;
            background: transparent;
            border: none;
            color: #64748b;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .icon-btn:hover {
            background-color: #f1f5f9;
            color: var(--text-dark);
        }

        .notification-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            background-color: #ef4444;
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ffffff;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: 8px;
        }

        .user-profile:hover {
            background-color: #f1f5f9;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.8125rem;
            color: var(--text-dark);
        }

        .user-role {
            font-size: 0.7rem;
            color: var(--text-muted);
        }

        /* Main Body Area */
        .main-container {
            padding: 2rem 2.5rem;
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.25rem;
        }

        .page-subtitle {
            color: var(--text-muted);
            margin: 0;
            font-size: 0.875rem;
        }

        .btn-brand {
            background-color: var(--brand-primary);
            color: white;
            font-weight: 500;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            border: none;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .btn-brand:hover {
            background-color: var(--brand-primary-hover);
            color: white;
        }

        .btn-outline-custom {
            border: 1px solid var(--border-color);
            background: white;
            color: var(--text-dark);
            font-weight: 500;
            border-radius: 8px;
            padding: 0.5rem 1rem;
        }

        .btn-outline-custom:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: var(--text-dark);
        }

        /* Filter Card */
        .filter-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }

        .filter-search {
            position: relative;
        }

        .filter-search input {
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding-left: 2.25rem;
            font-size: 0.875rem;
            height: 40px;
        }

        .filter-search .bi-search {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .form-select-custom {
            height: 40px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            font-size: 0.875rem;
            color: #334155;
            background-color: #ffffff;
        }

        .btn-reset {
            height: 40px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background-color: #f8fafc;
            color: #475569;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0 1rem;
        }

        .btn-reset:hover {
            background-color: #f1f5f9;
            color: var(--text-dark);
        }

        /* Table Card & Content */
        .table-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            overflow: visible;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }

        .custom-table {
            width: 100%;
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .custom-table th {
            background-color: #f8fafc;
            color: #64748b;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.875rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
        }

        .custom-table td {
            padding: 1rem 1.25rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
            color: #334155;
        }

        .custom-table tr:last-child td {
            border-bottom: none;
        }

        /* Product Cell */
        .product-icon-box {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background-color: #e0e7ff;
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .product-title {
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 0.125rem;
            font-size: 0.875rem;
        }

        .product-sku {
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 500;
        }

        .description-text {
            color: #64748b;
            max-width: 240px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Badges */
        .badge-category {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 500;
            font-size: 0.75rem;
            padding: 0.35rem 0.65rem;
            border-radius: 6px;
        }

        .badge-out-stock {
            background-color: #fee2e2;
            color: #dc2626;
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.35rem 0.65rem;
            border-radius: 6px;
            display: inline-block;
        }

        .quantity-active {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: #334155;
        }

        .dot-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #22c55e;
            display: inline-block;
        }

        /* Status Dropdown Button */
        .btn-status-pill {
            background-color: var(--badge-green-bg);
            color: var(--badge-green-text);
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.3rem 0.75rem;
            border-radius: 20px;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .btn-status-pill::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: var(--badge-green-text);
        }

        .btn-status-pill.inactive {
            background-color: #f1f5f9;
            color: #64748b;
        }

        .btn-status-pill.inactive::before {
            background-color: #64748b;
        }

        /* Active Status Menu Popover Overlay Replica (Row 4) */
        .status-dropdown-wrapper {
            position: relative;
        }

        .status-popover {
            position: absolute;
            top: 100%;
            left: 0;
            margin-top: 0.5rem;
            width: 170px;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            z-index: 1050;
            padding: 0.5rem 0;
        }

        .popover-header-title {
            padding: 0.35rem 0.85rem;
            font-size: 0.65rem;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 0.05em;
        }

        .popover-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.45rem 0.85rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: #334155;
            text-decoration: none;
            cursor: pointer;
        }

        .popover-item:hover {
            background-color: #f8fafc;
        }

        .popover-item.selected {
            color: #16a34a;
            font-weight: 600;
        }

        /* Price styling */
        .price-text {
            font-weight: 700;
            color: #0f172a;
        }

        /* Pagination Footer */
        .table-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .pagination-info {
            color: #64748b;
            font-size: 0.8125rem;
        }

        .pagination-info strong {
            color: #0f172a;
        }

        .custom-pagination {
            display: flex;
            gap: 0.25rem;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .custom-pagination .page-item .page-link {
            border: none;
            background: transparent;
            color: #64748b;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.8125rem;
        }

        .custom-pagination .page-item .page-link:hover {
            background-color: #f1f5f9;
            color: var(--text-dark);
        }

        .custom-pagination .page-item.active .page-link {
            background-color: var(--brand-primary);
            color: white;
            font-weight: 600;
        }

        @media (max-width: 991.98px) {
            #sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            #sidebar.show {
                margin-left: 0;
            }
            #content-wrapper {
                margin-left: 0;
                width: 100%;
            }
            .global-search {
                width: 200px;
            }
        }
    </style>
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
                    <a href="#" class="nav-link">
                        <i class="bi bi-people me-2"></i> Users
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-tags me-2"></i> Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link active">
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
                        Home / <span>Products</span>
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
                            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&auto=format&fit=crop&q=80" alt="Eleanor Vance" class="user-avatar">
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
                
                <!-- Page Header Title Area -->
                <div class="page-header">
                    <div>
                        <h1 class="page-title">Products</h1>
                        <p class="page-subtitle">Manage your products, inventory, and pricing.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-custom">
                            <i class="bi bi-download me-1"></i> Export CSV
                        </button>
                        <button class="btn btn-brand">
                            <i class="bi bi-plus-lg me-1"></i> Add Product
                        </button>
                    </div>
                </div>

                <!-- Filter Card -->
                <div class="filter-card">
                    <div class="row g-3">
                        <div class="col-lg-5 col-md-12">
                            <div class="filter-search">
                                <i class="bi bi-search"></i>
                                <input type="text" class="form-control" placeholder="Search by product name or description...">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <select class="form-select form-select-custom">
                                <option selected>All Categories</option>
                                <option value="1">Electronics</option>
                                <option value="2">Clothing</option>
                                <option value="3">Home & Kitchen</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <select class="form-select form-select-custom">
                                <option selected>All Statuses</option>
                                <option value="1">Active</option>
                                <option value="2">Inactive</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-4 text-end">
                            <button class="btn btn-reset w-100 justify-content-center">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset Filters
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table Container Card -->
                <div class="table-card">
                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">ID</th>
                                    <th>NAME</th>
                                    <th>DESCRIPTION</th>
                                    <th>CATEGORY</th>
                                    <th>QUANTITY</th>
                                    <th>STATUS</th>
                                    <th class="text-end">PRICE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Row 1 -->
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="product-icon-box">
                                                <i class="bi bi-headphones"></i>
                                            </div>
                                            <div>
                                                <div class="product-title">Wireless Headphones</div>
                                                <div class="product-sku">SKU-WH-091</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="description-text">Premium wireless headphones...</div>
                                    </td>
                                    <td>
                                        <span class="badge-category">Electronics</span>
                                    </td>
                                    <td>
                                        <div class="quantity-active">
                                            <span class="dot-indicator"></span> 120
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn-status-pill dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Active
                                            </button>
                                            <ul class="dropdown-menu shadow-sm">
                                                <li><a class="dropdown-item active" href="#">Active</a></li>
                                                <li><a class="dropdown-item" href="#">Inactive</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="price-text">$89.00</span>
                                    </td>
                                </tr>

                                <!-- Row 2 -->
                                <tr>
                                    <td>2</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="product-icon-box">
                                                <i class="bi bi-smartwatch"></i>
                                            </div>
                                            <div>
                                                <div class="product-title">Smart Watch</div>
                                                <div class="product-sku">SKU-SW-204</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="description-text">Smart watch with fitness trackin...</div>
                                    </td>
                                    <td>
                                        <span class="badge-category">Electronics</span>
                                    </td>
                                    <td>
                                        <div class="quantity-active">
                                            <span class="dot-indicator"></span> 75
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn-status-pill dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Active
                                            </button>
                                            <ul class="dropdown-menu shadow-sm">
                                                <li><a class="dropdown-item active" href="#">Active</a></li>
                                                <li><a class="dropdown-item" href="#">Inactive</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="price-text">$129.00</span>
                                    </td>
                                </tr>

                                <!-- Row 3 -->
                                <tr>
                                    <td>3</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="product-icon-box">
                                                <i class="bi bi-tsherit"></i>
                                            </div>
                                            <div>
                                                <div class="product-title">Cotton T-Shirt</div>
                                                <div class="product-sku">SKU-CT-812</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="description-text">Comfortable everyday cotton T-...</div>
                                    </td>
                                    <td>
                                        <span class="badge-category">Clothing</span>
                                    </td>
                                    <td>
                                        <div class="quantity-active">
                                            <span class="dot-indicator"></span> 200
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn-status-pill dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Active
                                            </button>
                                            <ul class="dropdown-menu shadow-sm">
                                                <li><a class="dropdown-item active" href="#">Active</a></li>
                                                <li><a class="dropdown-item" href="#">Inactive</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="price-text">$19.99</span>
                                    </td>
                                </tr>

                                <!-- Row 4 (With Popover / Open Status Overlay as shown in reference image) -->
                                <tr>
                                    <td>4</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="product-icon-box">
                                                <i class="bi bi-square"></i>
                                            </div>
                                            <div>
                                                <div class="product-title">Office Chair</div>
                                                <div class="product-sku">SKU-OC-330</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="description-text">Ergonomic office chair with...</div>
                                    </td>
                                    <td>
                                        <span class="badge-category">Home & Kitchen</span>
                                    </td>
                                    <td>
                                        <span class="badge-out-stock">Out of Stock (0)</span>
                                    </td>
                                    <td>
                                        <div class="status-dropdown-wrapper">
                                            <button class="btn-status-pill inactive dropdown-toggle" type="button">
                                                Inactive
                                            </button>

                                            <!-- Replicated visible status change menu layer -->
                                            <div class="status-popover">
                                                <div class="popover-header-title">CHANGE STATUS</div>
                                                <div class="popover-item selected">
                                                    <i class="bi bi-check-circle"></i> Set as Active
                                                </div>
                                                <div class="popover-item">
                                                    <i class="bi bi-circle"></i> Keep Inactive
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="price-text">$149.00</span>
                                    </td>
                                </tr>

                                <!-- Row 5 -->
                                <tr>
                                    <td>5</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="product-icon-box">
                                                <i class="bi bi-mouse"></i>
                                            </div>
                                            <div>
                                                <div class="product-title">Gaming Mouse</div>
                                                <div class="product-sku">SKU-GM-550</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="description-text">High-precision gaming mouse...</div>
                                    </td>
                                    <td>
                                        <span class="badge-category">Electronics</span>
                                    </td>
                                    <td>
                                        <div class="quantity-active">
                                            <span class="dot-indicator"></span> 45
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn-status-pill dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Active
                                            </button>
                                            <ul class="dropdown-menu shadow-sm">
                                                <li><a class="dropdown-item active" href="#">Active</a></li>
                                                <li><a class="dropdown-item" href="#">Inactive</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="price-text">$49.99</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Footer -->
                    <div class="table-footer">
                        <div class="pagination-info">
                            Showing <strong>1-5</strong> of <strong>25</strong> products
                        </div>
                        <ul class="custom-pagination">
                            <li class="page-item"><a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">4</a></li>
                            <li class="page-item"><a class="page-link" href="#">5</a></li>
                            <li class="page-item"><a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a></li>
                        </ul>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar responsive toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        }
    </script>
</body>
</html>
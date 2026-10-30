<?php
$products = [];

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Products Management</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Our separate CSS file -->
    <link href="..\public\assets\css\products.css" rel="stylesheet">
</head>

<body>

    <div class="admin-shell">

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">

            <a class="brand" href="#">
                <span class="brand-mark">
                    <i class="bi bi-boxes"></i>
                </span>

                <b class="brand-name">CoreUI</b>
                <span class="brand-admin">CoreAdmin</span>
            </a>

            <div class="nav-label">NAVIGATION</div>

            <nav class="side-nav">
                <a href="#">
                    <i class="bi bi-grid-1x2"></i>
                    <span>Dashboard</span>
                </a>

                <a href="#">
                    <i class="bi bi-people"></i>
                    <span>Users</span>
                </a>

                <a href="categories.php">
                    <i class="bi bi-tags"></i>
                    <span>Categories</span>
                </a>

                <a class="active" href="products.php">
                    <i class="bi bi-box-seam"></i>
                    <span>Products</span>
                </a>

                <a href="#">
                    <i class="bi bi-receipt"></i>
                    <span>Orders</span>
                </a>
            </nav>

            <button class="collapse-sidebar" id="collapseSidebar" type="button">
                <i class="bi bi-layout-sidebar-inset"></i>
                <span>Collapse Sidebar</span>
            </button>

        </aside>

        <!-- Main content -->
        <main class="main-area">

            <!-- Topbar -->
            <header class="topbar">

                <button class="icon-button mobile-menu" id="mobileMenu" type="button" aria-label="Open menu">
                    <i class="bi bi-list"></i>
                </button>

                <button class="icon-button desktop-menu" id="desktopMenu" type="button" aria-label="Collapse menu">
                    <i class="bi bi-list"></i>
                </button>

                <div class="breadcrumbs">
                    Home
                    <i class="bi bi-chevron-right"></i>
                    <b>Products</b>
                </div>
            </header>

            <!-- Page content -->
            <section class="content-area">

                <!-- Page heading -->
                <div class="page-heading">

                    <div>
                        <h1>Products</h1>
                        <p>
                            Manage your store products, pricing, and revenue.
                        </p>
                    </div>

                    <div class="heading-actions">

                        <button class="btn btn-export" id="exportCsv" type="button">
                            <i class="bi bi-download"></i>
                            Export CSV
                        </button>

                        <button class="btn btn-primary-custom" type="button" data-bs-toggle="modal"
                            data-bs-target="#addModal">
                            <i class="bi bi-plus-lg"></i>
                            Add Product
                        </button>

                    </div>
                </div>

                <!-- Products table card -->
                <section class="table-card">

                    <!-- Search and filters -->
                    <div class="filter-bar">

                        <label class="table-search">
                            <i class="bi bi-search"></i>

                            <input type="search" id="searchInput"
                                placeholder="Search by product name or description..." aria-label="Search products">
                        </label>

                        <select class="form-select filter-select" id="sortSelect" aria-label="Sort products">
                            <option value="newest">Sort by: Newest</option>
                            <option value="oldest">Sort by: Oldest</option>
                            <option value="name">Name: A to Z</option>
                            <option value="revenue">Highest Revenue</option>
                        </select>

                        <button class="btn btn-reset" id="resetBtn" type="button">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            Reset
                        </button>

                    </div>

                    <!-- Table -->
                    <div class="table-responsive">

                        <table class="table products-table align-middle mb-0">

                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Product Name</th>
                                    <th>Description</th>
                                    <th>Subcategory ID</th>
                                    <th>Price</th>
                                    <th>Cost Price</th>
                                    <th>Revenue</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody id="productsBody">

                            </tbody>
                        </table>

                    </div>

                    <!-- Table footer -->
                    <footer class="table-footer">

                        <span id="summary">Showing products</span>

                        <div class="pagination-wrap" id="paginationWrap">
                            <!-- سيتم تعبئة أزرار التصفح ديناميكياً هنا -->
                        </div>
                    </footer>

                </section>
            </section>

        </main>
    </div>

    <!-- Add/Edit Product Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content category-modal">

                <form id="addForm">

                    <div class="modal-header">

                        <div>
                            <h2 class="modal-title fs-5">Add Product</h2>
                            <p class="modal-subtitle">
                                Create a new store product.
                            </p>
                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    </div>

                    <div class="modal-body">

                        <label class="form-label" for="prodName">
                            Product name
                        </label>
                        <input class="form-control mb-3" id="prodName" required maxlength="100"
                            placeholder="e.g. Wireless Mouse">

                        <label class="form-label" for="subcategoryId">
                            Subcategory ID
                        </label>
                        <input type="number" class="form-control mb-3" id="subcategoryId" required placeholder="e.g. 1">

                        <label class="form-label" for="prodDescription">
                            Description
                        </label>
                        <textarea class="form-control mb-3" id="prodDescription" rows="3"
                            placeholder="Brief description"></textarea>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label" for="prodPrice">Selling Price</label>
                                <input type="number" step="0.01" class="form-control mb-3" id="prodPrice" required placeholder="0.00">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="prodCostPrice">Cost Price</label>
                                <input type="number" step="0.01" class="form-control mb-3" id="prodCostPrice" required placeholder="0.00">
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button class="btn btn-primary-custom" type="submit">
                            Add Product
                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- Notification toast -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">

        <div id="uiToast" class="toast text-bg-dark border-0">

            <div class="toast-body" id="toastText">
                Done
            </div>

        </div>

    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        (() => {
            const body = document.getElementById('productsBody');
            const search = document.getElementById('searchInput');
            const sort = document.getElementById('sortSelect');
            const summary = document.getElementById('summary');
            const paginationWrap = document.getElementById('paginationWrap');
            let editingProductId = null;
            let currentPage = 1;
            const rowsPerPage = 10;

            const apiUrl = "http://localhost/phpGroupProject/app/controllers/admin-products.php";

            function notify(message) {
                document.getElementById('toastText').textContent = message;
                bootstrap.Toast.getOrCreateInstance(document.getElementById('uiToast')).show();
            }

            function escapeHtml(value) {
                return String(value ?? '').replace(/[&<>"']/g, character => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                }[character]));
            }

            function createSlug(name) {
                return String(name ?? '')
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-|-$/g, '');
            }

            function makeRow(id, productName, subcategoryId, price, costPrice, description) {
                const numPrice = Number(price) || 0;
                const numCostPrice = Number(costPrice) || 0;
                const revenue = numPrice - numCostPrice;

                const row = document.createElement('tr');
                row.dataset.id = id;
                row.dataset.name = String(productName).toLowerCase();
                row.dataset.description = String(description || '').toLowerCase();
                row.dataset.subcategory = subcategoryId;
                row.dataset.price = numPrice;
                row.dataset.costPrice = numCostPrice;
                row.dataset.revenue = revenue;

                row.innerHTML = `
                    <td class="id-cell">${escapeHtml(id)}</td>

                    <td>
                        <div class="category-name-cell">
                            <span class="category-icon">
                                <i class="bi bi-box-seam"></i>
                            </span>
                            <span class="category-name-text">
                                <b>${escapeHtml(productName)}</b>
                                <small>slug: ${escapeHtml(createSlug(productName))}</small>
                            </span>
                        </div>
                    </td>

                    <td class="description-cell">
                        ${escapeHtml(description || '—')}
                    </td>

                    <td>
                        <b class="product-count">${escapeHtml(subcategoryId)}</b>
                    </td>

                    <td>
                        <span>$${numPrice.toFixed(2)}</span>
                    </td>

                    <td>
                        <span>$${numCostPrice.toFixed(2)}</span>
                    </td>

                    <td>
                        <b class="${revenue >= 0 ? 'text-success' : 'text-danger'}">
                            $${revenue.toFixed(2)}
                        </b>
                    </td>

                    <td>
                        <div class="row-actions">
                            <button class="row-action edit-btn" type="button" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="row-action delete-btn" type="button" title="Delete">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </td>
                `;

                return row;
            }

            function filterRows() {
                const query = search.value.toLowerCase().trim();
                const rows = [...body.querySelectorAll('tr')];

                rows.sort((a, b) => {
                    if (sort.value === 'oldest') {
                        return Number(a.dataset.id) - Number(b.dataset.id);
                    }
                    if (sort.value === 'name') {
                        return a.dataset.name.localeCompare(b.dataset.name);
                    }
                    if (sort.value === 'revenue') {
                        return Number(b.dataset.revenue) - Number(a.dataset.revenue);
                    }
                    return Number(b.dataset.id) - Number(a.dataset.id);
                });

                // تصفية الصفوف حسب البحث
                const matchedRows = rows.filter(row => {
                    const matchesText =
                        row.dataset.name.includes(query) ||
                        row.dataset.description.includes(query);
                    return matchesText;
                });

                const totalPages = Math.ceil(matchedRows.length / rowsPerPage) || 1;
                if (currentPage > totalPages) currentPage = totalPages;
                if (currentPage < 1) currentPage = 1;

                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;

                // إعادة ترتيب وإظهار/إخفاء العناصر بناءً على الصفحة الحالية
                body.replaceChildren();
                rows.forEach(row => {
                    const isMatched = matchedRows.includes(row);
                    if (isMatched) {
                        const index = matchedRows.indexOf(row);
                        if (index >= start && index < end) {
                            row.hidden = false;
                            body.appendChild(row);
                        } else {
                            row.hidden = true;
                            body.appendChild(row);
                        }
                    } else {
                        row.hidden = true;
                        body.appendChild(row);
                    }
                });

                summary.textContent = `Showing ${matchedRows.length > 0 ? start + 1 : 0}-${Math.min(end, matchedRows.length)} of ${matchedRows.length} products`;
                renderPagination(totalPages);
            }

            function renderPagination(totalPages) {
                paginationWrap.replaceChildren();

                // زر السابق
                const prevBtn = document.createElement('button');
                prevBtn.className = 'page-link-custom';
                prevBtn.type = 'button';
                prevBtn.innerHTML = '‹ Previous';
                prevBtn.disabled = currentPage === 1;
                prevBtn.addEventListener('click', () => {
                    if (currentPage > 1) {
                        currentPage--;
                        filterRows();
                    }
                });
                paginationWrap.appendChild(prevBtn);

                // أرقام الصفحات
                for (let i = 1; i <= totalPages; i++) {
                    const pageBtn = document.createElement('button');
                    pageBtn.className = `page-number ${i === currentPage ? 'active' : ''}`;
                    pageBtn.type = 'button';
                    pageBtn.textContent = i;
                    pageBtn.addEventListener('click', () => {
                        currentPage = i;
                        filterRows();
                    });
                    paginationWrap.appendChild(pageBtn);
                }

                // زر التالي
                const nextBtn = document.createElement('button');
                nextBtn.className = 'page-link-custom';
                nextBtn.type = 'button';
                nextBtn.innerHTML = 'Next ›';
                nextBtn.disabled = currentPage === totalPages || totalPages === 0;
                nextBtn.addEventListener('click', () => {
                    if (currentPage < totalPages) {
                        currentPage++;
                        filterRows();
                    }
                });
                paginationWrap.appendChild(nextBtn);
            }

            search.addEventListener('input', () => {
                currentPage = 1;
                filterRows();
            });

            sort.addEventListener('change', () => {
                currentPage = 1;
                filterRows();
            });

            document.getElementById('resetBtn').addEventListener('click', () => {
                search.value = '';
                sort.value = 'newest';
                currentPage = 1;
                filterRows();
            });

            // إرسال إضافة/تعديل منتج
            document.getElementById('addForm').addEventListener('submit', async event => {
                event.preventDefault();

                const form = event.currentTarget;
                const productName = document.getElementById('prodName').value.trim();
                const subcategoryId = document.getElementById('subcategoryId').value.trim();
                const description = document.getElementById('prodDescription').value.trim();
                const price = document.getElementById('prodPrice').value.trim();
                const costPrice = document.getElementById('prodCostPrice').value.trim();

                if (!productName || !price || !costPrice || !subcategoryId) {
                    notify('Please fill all required fields.');
                    return;
                }

                const submitButton = form.querySelector('[type="submit"]');
                if (submitButton.disabled) return;
                submitButton.disabled = true;

                const isEditing = editingProductId !== null;

                try {
                    const response = await fetch(
                        isEditing
                            ? `${apiUrl}?id=${encodeURIComponent(editingProductId)}`
                            : apiUrl,
                        {
                            method: isEditing ? 'PUT' : 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                product_name: productName,
                                subcategory_id: Number(subcategoryId),
                                description: description,
                                price: Number(price),
                                cost_price: Number(costPrice)
                            })
                        }
                    );

                    const result = await response.json();

                    if (!response.ok || !result.success) {
                        throw new Error(result.message || 'Failed to save product.');
                    }

                    bootstrap.Modal.getOrCreateInstance(document.getElementById('addModal')).hide();
                    await loadProducts();
                    notify(isEditing ? 'Product updated successfully.' : 'Product created successfully.');

                } catch (error) {
                    console.error('Save product error:', error);
                    notify(error.message || 'Could not save product.');
                } finally {
                    submitButton.disabled = false;
                }
            });

            document.getElementById('addModal').addEventListener('hidden.bs.modal', () => {
                editingProductId = null;
                const modal = document.getElementById('addModal');

                modal.querySelector('.modal-title').textContent = 'Add Product';
                modal.querySelector('.modal-subtitle').textContent = 'Create a new store product.';
                modal.querySelector('[type="submit"]').textContent = 'Add Product';

                document.getElementById('addForm').reset();
            });

            // حذف المنتج
            body.addEventListener('click', async event => {
                const deleteButton = event.target.closest('.delete-btn');
                if (!deleteButton) return;

                const row = deleteButton.closest('tr');
                const productId = row.dataset.id;
                const productName = row.querySelector('.category-name-text b').textContent;

                if (!confirm(`Are you sure you want to delete "${productName}"?`)) return;
                if (deleteButton.disabled) return;
                deleteButton.disabled = true;

                try {
                    const response = await fetch(`${apiUrl}?id=${encodeURIComponent(productId)}`, {
                        method: 'DELETE'
                    });

                    const result = await response.json();

                    if (!response.ok || !result.success) {
                        throw new Error(result.message || 'Failed to delete product.');
                    }

                    await loadProducts();
                    notify('Product deleted successfully.');

                } catch (error) {
                    console.error('Delete product error:', error);
                    notify(error.message || 'Could not delete product.');
                    deleteButton.disabled = false;
                }
            });

            // تعديل المنتج وتعبئة حقول Modal
            body.addEventListener('click', event => {
                const editButton = event.target.closest('.edit-btn');
                if (!editButton) return;

                const row = editButton.closest('tr');
                editingProductId = row.dataset.id;

                document.getElementById('prodName').value = row.querySelector('.category-name-text b').textContent;
                document.getElementById('subcategoryId').value = row.dataset.subcategory;
                
                const description = row.querySelector('.description-cell').textContent.trim();
                document.getElementById('prodDescription').value = description === '—' ? '' : description;

                document.getElementById('prodPrice').value = row.dataset.price;
                document.getElementById('prodCostPrice').value = row.dataset.costPrice;

                const modalElement = document.getElementById('addModal');
                modalElement.querySelector('.modal-title').textContent = 'Edit Product';
                modalElement.querySelector('[type="submit"]').textContent = 'Save Changes';

                bootstrap.Modal.getOrCreateInstance(modalElement).show();
            });

            // تصدير CSV
            document.getElementById('exportCsv').addEventListener('click', () => {
                const rows = [...body.querySelectorAll('tr')].filter(row => !row.hidden);

                const data = [
                    ['ID', 'Product Name', 'Description', 'Subcategory ID', 'Price', 'Cost Price', 'Revenue'],
                    ...rows.map(row => [
                        row.dataset.id,
                        row.querySelector('.category-name-text b').textContent,
                        row.querySelector('.description-cell').textContent,
                        row.dataset.subcategory,
                        row.dataset.price,
                        row.dataset.costPrice,
                        row.dataset.revenue
                    ])
                ];

                const csv = data
                    .map(row => row.map(value => '"' + String(value).replace(/"/g, '""') + '"').join(','))
                    .join('\r\n');

                const blob = new Blob(['\ufeff' + csv], { type: 'text/csv;charset=utf-8;' });
                const url = URL.createObjectURL(blob);
                const link = document.createElement('a');

                link.href = url;
                link.download = 'products.csv';
                link.click();

                setTimeout(() => URL.revokeObjectURL(url), 0);
            });

            // التحكم بالقائمة الجانبية
            document.getElementById('collapseSidebar').addEventListener('click', () => {
                document.querySelector('.admin-shell').classList.toggle('sidebar-collapsed');
            });

            document.getElementById('desktopMenu').addEventListener('click', () => {
                document.querySelector('.admin-shell').classList.toggle('sidebar-collapsed');
            });

            document.getElementById('mobileMenu').addEventListener('click', () => {
                document.querySelector('.admin-shell').classList.toggle('sidebar-open');
            });

            // جلب البيانات وقراءتها بناءً على حقول قاعدة البيانات
            async function loadProducts() {
                try {
                    const response = await fetch(apiUrl);
                    const result = await response.json();

                    if (!response.ok || !result.success) {
                        throw new Error(result.message || 'Failed to load products');
                    }

                    body.replaceChildren();

                    result.data.forEach((product, index) => {
                        const row = makeRow(
                            product.id,
                            product.product_name,
                            product.subcategory_id,
                            product.price,
                            product.cost_price,
                            product.description
                        );

                        row.classList.add('stagger-item');
                        row.style.setProperty('--stagger-delay', `${index * 300}ms`);

                        body.appendChild(row);
                    });

                    filterRows();

                } catch (error) {
                    console.error('Error loading products:', error);
                    summary.textContent = 'Failed to load products';
                    notify('Could not load products from the server.');
                }
            }

            loadProducts();
        })();
    </script>
</body>

</html>
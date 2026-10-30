<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Categories Management</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Our separate CSS file -->
    <link href="..\public\assets\css\categories.css" rel="stylesheet">
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

                <a class="active" href="categories.php">
                    <i class="bi bi-tags"></i>
                    <span>Categories</span>
                </a>

                <a href="#">
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
                    <b>Categories</b>
                </div>
            </header>

            <!-- Page content -->
            <section class="content-area">

                <!-- Page heading -->
                <div class="page-heading">

                    <div>
                        <h1>Categories</h1>
                        <p>
                            Manage your product categories and organization hierarchy.
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
                            Add Category
                        </button>

                    </div>
                </div>

                <!-- Categories table card -->
                <section class="table-card">

                    <!-- Search and filters -->
                    <div class="filter-bar">

                        <label class="table-search">
                            <i class="bi bi-search"></i>

                            <input type="search" id="searchInput"
                                placeholder="Search by category name or description..." aria-label="Search categories">
                        </label>

                        <select class="form-select filter-select" id="sortSelect" aria-label="Sort categories">
                            <option value="newest">Sort by: Newest</option>
                            <option value="oldest">Sort by: Oldest</option>
                            <option value="name">Name: A to Z</option>
                            <option value="products">Most products</option>
                        </select>

                        <select class="form-select filter-select status-filter" id="statusFilter"
                            aria-label="Filter by status">
                            <option value="All">Status: All</option>
                            <option value="Active">Status: Active</option>
                            <option value="Inactive">Status: Inactive</option>
                        </select>

                        <button class="btn btn-reset" id="resetBtn" type="button">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            Reset
                        </button>

                    </div>

                    <!-- Table -->
                    <div class="table-responsive">

                        <table class="table categories-table align-middle mb-0">

                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Number of Products</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody id="categoriesBody">

                            </tbody>
                        </table>

                    </div>

                    <!-- Table footer -->
                    <footer class="table-footer">

                        <span id="summary">Showing categories</span>

                        <div class="pagination-wrap">

                            <button class="page-link-custom" type="button" disabled>
                                ‹ Previous
                            </button>

                            <button class="page-number active" type="button">1</button>
                            <button class="page-number" type="button">2</button>
                            <button class="page-number" type="button">3</button>
                            <button class="page-number" type="button">4</button>
                            <button class="page-number" type="button">5</button>

                            <button class="page-link-custom" type="button">
                                Next ›
                            </button>

                        </div>
                    </footer>

                </section>
            </section>

        </main>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content category-modal">

                <form id="addForm">

                    <div class="modal-header">

                        <div>
                            <h2 class="modal-title fs-5">Add Category</h2>
                            <p class="modal-subtitle">
                                Create a new product category.
                            </p>
                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    </div>

                    <div class="modal-body">

                        <label class="form-label" for="catName">
                            Category name
                        </label>

                        <input class="form-control mb-3" id="catName" required maxlength="100"
                            placeholder="e.g. Accessories">

                        <label class="form-label" for="catDescription">
                            Description
                        </label>

                        <textarea class="form-control mb-3" id="catDescription" rows="3"
                            placeholder="Brief description"></textarea>

                        <label class="form-label" for="catStatus">
                            Status
                        </label>

                        <select class="form-select" id="catStatus">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button class="btn btn-primary-custom" type="submit">
                            Add Category
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


    <script>
  // هاي اسمها IIFE، يعني دالة بتشتغل مباشرة أول ما المتصفح يقرأها  (()=>{})
 
(() => {
    // 1. HTML elements
    const body = document.getElementById('categoriesBody');
    const search = document.getElementById('searchInput');
    const status = document.getElementById('statusFilter');
    const sort = document.getElementById('sortSelect');
    const summary = document.getElementById('summary');
    let editingCategoryId = null;

    const apiUrl =
        "http://localhost/phpGroupProject/app/controllers/admin-categories.php";

    // 2. Show toast notification
    function notify(message) {
        document.getElementById('toastText').textContent = message;

        bootstrap.Toast
            .getOrCreateInstance(document.getElementById('uiToast'))
            .show();
    }

    // 3. Escape HTML special characters
    function escapeHtml(value) {
        return String(value).replace(/[&<>"']/g, character => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        }[character]));
    }

    // 4. Create category slug
    function createSlug(name) {
        return name
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-|-$/g, '');
    }

    // 5. Create a category table row
    function makeRow(id, name, description, categoryStatus, products = 0) {
        const row = document.createElement('tr');

        row.dataset.id = id;
        row.dataset.name = name.toLowerCase();
        row.dataset.description = description.toLowerCase();
        row.dataset.status = categoryStatus;
        row.dataset.products = products;

        row.innerHTML = `
            <td class="id-cell">${escapeHtml(id)}</td>

            <td>
                <div class="category-name-cell">
                    <span class="category-icon">
                        <i class="bi bi-tag"></i>
                    </span>

                    <span class="category-name-text">
                        <b>${escapeHtml(name)}</b>
                        <small>slug: ${escapeHtml(createSlug(name))}</small>
                    </span>
                </div>
            </td>

            <td class="description-cell">
                ${escapeHtml(description || '—')}
            </td>

            <td>
                <b class="product-count">${escapeHtml(products)}</b>
                <span class="muted-small">products</span>
            </td>

            <td>
                <span class="status-badge ${
                    categoryStatus === 'Active'
                        ? 'status-active'
                        : 'status-inactive'
                }">
                    ${escapeHtml(categoryStatus)}
                </span>
            </td>

            <td>
                <div class="row-actions">
                    <button
                        class="row-action edit-btn"
                        type="button"
                        title="Edit"
                    >
                        <i class="bi bi-pencil"></i>
                    </button>

                    <button
                        class="row-action delete-btn"
                        type="button"
                        title="Delete"
                    >
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>
            </td>
        `;

        return row;
    }

    // 6. Search, filter, sort, and update summary
    function filterRows() {
        const query = search.value.toLowerCase().trim();
        const selectedStatus = status.value;
        const rows = [...body.querySelectorAll('tr')];

        rows.sort((a, b) => {
            if (sort.value === 'oldest') {
                return Number(a.dataset.id) - Number(b.dataset.id);
            }

            if (sort.value === 'name') {
                return a.dataset.name.localeCompare(b.dataset.name);
            }

            if (sort.value === 'products') {
                return Number(b.dataset.products) -
                    Number(a.dataset.products);
            }

            return Number(b.dataset.id) - Number(a.dataset.id);
        });

        let visibleCount = 0;

        rows.forEach(row => {
            const matchesText =
                row.dataset.name.includes(query) ||
                row.dataset.description.includes(query);

            const matchesStatus =
                selectedStatus === 'All' ||
                row.dataset.status === selectedStatus;

            const shouldShow = matchesText && matchesStatus;

            row.hidden = !shouldShow;

            if (shouldShow) {
                visibleCount++;
            }

            body.appendChild(row);
        });

        summary.textContent =
            `Showing ${visibleCount} of ${rows.length} categories`;
    }

    // 7. Search, filter, and sort listeners
    search.addEventListener('input', filterRows);
    status.addEventListener('change', filterRows);
    sort.addEventListener('change', filterRows);

    // 8. Reset filters
    document.getElementById('resetBtn').addEventListener('click', () => {
        search.value = '';
        status.value = 'All';
        sort.value = 'newest';

        filterRows();
    });

    // 9. Prevent demo form submission until API integration
   

document.getElementById('addForm').addEventListener('submit', async event => {
    event.preventDefault();

    const form = event.currentTarget;
    const name = document.getElementById('catName').value.trim();
    const description = document.getElementById('catDescription').value.trim();
    const categoryStatus =
        document.getElementById('catStatus').value.toLowerCase();

    if (!name || !description) {
        notify('Please enter the category name and description.');
        return;
    }

    if (!['active', 'inactive'].includes(categoryStatus)) {
        notify('Please select a valid category status.');
        return;
    }

    const submitButton = form.querySelector('[type="submit"]');

    if (submitButton.disabled) return;

    submitButton.disabled = true;

    const isEditing = editingCategoryId !== null;

    try {
        const response = await fetch(
            isEditing
                ? `${apiUrl}?id=${encodeURIComponent(editingCategoryId)}`
                : apiUrl,
            {
                method: isEditing ? 'PUT' : 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name,
                    description,
                    status: categoryStatus
                })
            }
        );

        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(
                result.message || 'Failed to save category.'
            );
        }

        bootstrap.Modal
            .getOrCreateInstance(document.getElementById('addModal'))
            .hide();

        await loadCategories();

        notify(
            isEditing
                ? 'Category updated successfully.'
                : 'Category created successfully.'
        );

    } catch (error) {
        console.error('Save category error:', error);
        notify(error.message || 'Could not save category.');
    } finally {
        submitButton.disabled = false;
    }
});

document.getElementById('addModal').addEventListener('hidden.bs.modal', () => {
    editingCategoryId = null;

    const modal = document.getElementById('addModal');

    modal.querySelector('.modal-title').textContent = 'Add Category';
    modal.querySelector('.modal-subtitle').textContent =
        'Create a new product category.';
    modal.querySelector('[type="submit"]').textContent = 'Add Category';

    document.getElementById('addForm').reset();
});



 // delete button     
body.addEventListener('click', async event => {
    const deleteButton = event.target.closest('.delete-btn');

    // Ignore clicks that are not on a delete button
    if (!deleteButton) {
        return;
    }

    const row = deleteButton.closest('tr');
    const categoryId = row.dataset.id;
    const categoryName =
        row.querySelector('.category-name-text b').textContent;

    // Ask for confirmation
    const confirmed = confirm(
        `Are you sure you want to delete "${categoryName}"?`
    );

    if (!confirmed) {
        return;
    }

    // Prevent duplicate requests
    if (deleteButton.disabled) {
        return;
    }

    deleteButton.disabled = true;

    try {
        const response = await fetch(
            `${apiUrl}?id=${encodeURIComponent(categoryId)}`,
            {
                method: 'DELETE'
            }
        );

        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(
                result.message || 'Failed to delete category.'
            );
        }

        // Reload the real data from the API
        await loadCategories();

        notify('Category deleted successfully.');

    } catch (error) {
        console.error('Delete category error:', error);
        notify(error.message || 'Could not delete category.');
        deleteButton.disabled = false;
    }
});

//Update Button 

body.addEventListener('click', event => {
    const editButton = event.target.closest('.edit-btn');

    if (!editButton) {
        return;
    }

    const row = editButton.closest('tr');

    editingCategoryId = row.dataset.id;

    document.getElementById('catName').value =
        row.querySelector('.category-name-text b').textContent;

    const description = row.querySelector('.description-cell').textContent.trim();

    document.getElementById('catDescription').value =
        description === '—' ? '' : description;

    document.getElementById('catStatus').value =
        row.dataset.status;

    const modalElement = document.getElementById('addModal');

    modalElement.querySelector('.modal-title').textContent =
        'Edit Category';

    modalElement.querySelector('[type="submit"]').textContent =
        'Save Changes';

    bootstrap.Modal.getOrCreateInstance(modalElement).show();
});


    // 10. Export visible categories to CSV
    document.getElementById('exportCsv').addEventListener('click', () => {
        const rows = [...body.querySelectorAll('tr')]
            .filter(row => !row.hidden);

        const data = [
            ['ID', 'Name', 'Description', 'Products', 'Status'],

            ...rows.map(row => [
                row.dataset.id,
                row.querySelector('.category-name-text b').textContent,
                row.querySelector('.description-cell').textContent,
                row.dataset.products,
                row.dataset.status
            ])
        ];

        const csv = data
            .map(row => row
                .map(value => '"' + String(value).replace(/"/g, '""') + '"')
                .join(',')
            )
            .join('\r\n');

        const blob = new Blob(
            ['\ufeff' + csv],
            { type: 'text/csv;charset=utf-8;' }
        );

        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');

        link.href = url;
        link.download = 'categories.csv';
        link.click();

        setTimeout(() => URL.revokeObjectURL(url), 0);
    });

    // 11. Sidebar controls
    document.getElementById('collapseSidebar').addEventListener('click', () => {
        document.querySelector('.admin-shell')
            .classList.toggle('sidebar-collapsed');
    });

    document.getElementById('desktopMenu').addEventListener('click', () => {
        document.querySelector('.admin-shell')
            .classList.toggle('sidebar-collapsed');
    });

    document.getElementById('mobileMenu').addEventListener('click', () => {
        document.querySelector('.admin-shell')
            .classList.toggle('sidebar-open');
    });

    // 12. Load categories from API
    async function loadCategories() {
        try {
            const response = await fetch(apiUrl);
            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(
                    result.message || 'Failed to load categories'
                );
            }

            // بنفرّغ جسم الجدول من محتواه السابق
            body.replaceChildren();

            result.data.forEach((category, index) => {
                const categoryStatus =
                    category.status.toLowerCase() === 'active'
                        ? 'Active'
                        : 'Inactive';

                const row = makeRow(
                    category.id,
                    category.name,
                    category.description ?? '',
                    categoryStatus,
                    Number(category.products_count ?? 0)
                );

                // Staggered entrance animation
                row.classList.add('stagger-item');
                row.style.setProperty(
                    '--stagger-delay',
                    `${index * 300}ms`
                );

                body.appendChild(row);
            });

            filterRows();

        } catch (error) {
            console.error('Error loading categories:', error);
            summary.textContent = 'Failed to load categories';
            notify('Could not load categories from the server.');
        }
    }

    // 13. Initial load
    loadCategories();
})();

    </script>
</body>

</html>
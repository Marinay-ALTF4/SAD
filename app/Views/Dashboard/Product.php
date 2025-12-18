<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<?php
    if (! function_exists('formatPeso')) {
        function formatPeso(float $value): string
        {
            return '₱' . number_format($value, 2);
        }
    }

    if (! function_exists('categoryLabel')) {
        function categoryLabel(array $categories, string $key): string
        {
            return $categories[$key] ?? ucwords(str_replace('-', ' ', $key));
        }
    }

    $products      = $products ?? [];
    $bestSellers   = $bestSellers ?? [];
    $categories    = $categories ?? [];
    $statusOptions = $statusOptions ?? [];
    $stats = array_merge([
        'available' => 0,
        'out'       => 0,
        'total'     => 0,
    ], $stats ?? []);

    $statusBadges = [
        'Available'    => 'bg-success',
        'Out of Stock' => 'bg-secondary',
    ];
?>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Welcome, <?= session()->get('username')?>☕</h2>
        <a href="<?= base_url('dashboard') ?>">Dashboard</a>
        <a href="<?= base_url('orders') ?>">Orders</a>
        <a href="<?= base_url('product') ?>" class="active">Products</a>
        <a href="<?= base_url('expenses') ?>">Expenses</a>
        <?php if (session()->get('role') === 'admin'): ?>
            <a href="<?= base_url('reports') ?>">Reports</a>
            <a href="<?= base_url('settings') ?>">Settings</a>
        <?php endif; ?>
        <a href="<?= base_url('logout') ?>">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
            <div>
           
                <h2 class="title m-0">Signature Coffee & Kitchen</h2>
            </div>
            <button class="btn btn-primary btn-primary-custom product-modal-trigger" data-bs-toggle="modal" data-bs-target="#productModal">
                + Add Menu Item
            </button>
        </div>

        <?php if ($message = session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= esc($message) ?></div>
        <?php endif; ?>
        <?php if ($message = session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc($message) ?></div>
        <?php endif; ?>

        <!-- Summary -->
        <div class="row g-3 mb-4">
            <div class="col-md-4 col-sm-6">
                <div class="summary-card bg-success text-white text-center p-3 rounded">
                    <p class="mb-1 text-uppercase small">Available</p>
                    <h3 class="mb-0"><?= number_format($stats['available']) ?></h3>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="summary-card bg-secondary text-white text-center p-3 rounded">
                    <p class="mb-1 text-uppercase small">Out of Stock</p>
                    <h3 class="mb-0"><?= number_format($stats['out']) ?></h3>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="summary-card bg-primary text-white text-center p-3 rounded">
                    <p class="mb-1 text-uppercase small">Total Items</p>
                    <h3 class="mb-0"><?= number_format($stats['total']) ?></h3>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card-custom p-3 mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-lg-4 col-md-6">
                    <input type="text" id="productSearch" class="form-control" placeholder="Search drinks, pastries, add-ons...">
                </div>
                <div class="col-lg-4 col-md-3">
                    <select id="categoryFilter" class="form-select">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $key => $label): ?>
                            <option value="<?= esc($key) ?>"><?= esc($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-4 col-md-3">
                    <select id="statusFilter" class="form-select">
                        <option value="">All Status</option>
                        <?php foreach ($statusOptions as $status): ?>
                            <option value="<?= esc($status) ?>"><?= esc($status) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Best Sellers -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="title mb-0">Best Sellers</h4>
                
            </div>
            <div class="d-flex gap-3 flex-wrap">
                <?php if (empty($bestSellers)): ?>
                    <p class="text-muted">No best sellers yet. Edit a menu item and enable “Best Seller”.</p>
                <?php else: ?>
                    <?php foreach ($bestSellers as $item): ?>
                        <div class="card-featured p-3">
                            
                            <div class="card-body text-center">
                                <h6 class="text-uppercase text-muted small mb-1">
                                    <?= esc(categoryLabel($categories, $item['category'])) ?>
                                </h6>
                                <h3 class="mb-1"><?= esc($item['name']) ?></h3>
                                <p class="display-6 fw-bold"><?= formatPeso((float) $item['price']) ?></p>
                                <span class="badge <?= $statusBadges[$item['status']] ?? 'bg-secondary' ?>">
                                    <?= esc($item['status']) ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Table -->
        <div class="card-custom p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <h4 class="title mb-0">Product Inventory</h4>
               
            </div>
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="productsTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th width="200">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">No products yet. Start by adding your first drink.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $index => $product): ?>
                                <tr data-name="<?= esc(strtolower($product['name'])) ?>"
                                    data-category="<?= esc($product['category']) ?>"
                                    data-status="<?= esc($product['status']) ?>">
                                    <td><?= $index + 1 ?></td>
                                    <td class="fw-semibold"><?= esc($product['name']) ?></td>
                                    <td><?= esc(categoryLabel($categories, $product['category'])) ?></td>
                                    <td><?= formatPeso((float) $product['price']) ?></td>
                                    <td>
                                        <span class="badge <?= $statusBadges[$product['status']] ?? 'bg-secondary' ?>">
                                            <?= esc($product['status']) ?>
                                        </span>
                                        <?php if (! empty($product['best_seller'])): ?>
                                            <span class="badge bg-dark ms-1">Best Seller</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-2">
                                            <button class="btn btn-sm btn-outline-primary product-modal-trigger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#productModal"
                                                    data-product='<?= json_encode($product, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>'>
                                                Edit
                                            </button>
                                            <form method="post" action="<?= base_url('product/' . $product['id'] . '/delete') ?>" onsubmit="return confirm('Remove this product?');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="productForm" method="post" action="<?= base_url('product') ?>">
                    <?= csrf_field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Add Menu Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Drink / Dish Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Spanish Latte" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select" required>
                                <?php foreach ($categories as $key => $label): ?>
                                    <option value="<?= esc($key) ?>"><?= esc($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Price (₱)</label>
                                <input type="number" step="0.01" min="0" name="price" class="form-control" placeholder="185" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    <?php foreach ($statusOptions as $status): ?>
                                        <option value="<?= esc($status) ?>"><?= esc($status) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" id="bestSellerSwitch" name="best_seller" value="1">
                            <label class="form-check-label" for="bestSellerSwitch">Mark as Best Seller</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-primary-custom">Save Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const productForm = document.getElementById('productForm');
        const productModalEl = document.getElementById('productModal');
        const defaultProductAction = productForm.getAttribute('action');
        const modalTitle = productModalEl.querySelector('.modal-title');
        const bestSellerSwitch = document.getElementById('bestSellerSwitch');

        const resetProductForm = () => {
            productForm.reset();
            productForm.setAttribute('action', defaultProductAction);
            modalTitle.textContent = 'Add Menu Item';
            bestSellerSwitch.checked = false;
        };

        productModalEl.addEventListener('hidden.bs.modal', resetProductForm);

        productModalEl.addEventListener('show.bs.modal', event => {
            const trigger = event.relatedTarget;
            if (!trigger) return;

            const payload = trigger.getAttribute('data-product');
            if (!payload) {
                resetProductForm();
                return;
            }

            const product = JSON.parse(payload);
            modalTitle.textContent = `Edit ${product.name}`;
            productForm.setAttribute('action', `${defaultProductAction}/${product.id}`);
            productForm.name.value = product.name;
            productForm.category.value = product.category;
            productForm.price.value = product.price;
            productForm.status.value = product.status;
            bestSellerSwitch.checked = Number(product.best_seller) === 1;
        });

        const rows = Array.from(document.querySelectorAll('#productsTable tbody tr'));
        const searchInput = document.getElementById('productSearch');
        const categoryFilter = document.getElementById('categoryFilter');
        const statusFilter = document.getElementById('statusFilter');

        const applyFilters = () => {
            const query = (searchInput.value || '').toLowerCase();
            const category = categoryFilter.value;
            const status = statusFilter.value;

            rows.forEach(row => {
                const matchesSearch = row.dataset.name?.includes(query) ?? true;
                const matchesCategory = !category || row.dataset.category === category;
                const matchesStatus = !status || row.dataset.status === status;

                row.style.display = (matchesSearch && matchesCategory && matchesStatus) ? '' : 'none';
            });
        };

        searchInput.addEventListener('input', applyFilters);
        categoryFilter.addEventListener('change', applyFilters);
        statusFilter.addEventListener('change', applyFilters);
    </script>
</body>
</html>

<style>
    body {
        background-color: #f3e5d8;
        font-family: 'Poppins', sans-serif;
    }

    .sidebar {
        width: 250px;
        height: 100vh;
        background-color: #8b5e3c;
        padding: 20px;
        position: fixed;
        top: 0;
        left: 0;
        color: white;
    }

    .sidebar h2 {
        font-weight: 700;
        margin-bottom: 35px;
    }

    .sidebar a {
        color: #f3e5d8;
        text-decoration: none;
        display: block;
        padding: 14px 15px;
        font-size: 1.1rem;
        margin-bottom: 20px;
        border: 1px solid #5a3825;
        border-radius: 10px;
        background-color: #9b6b4a;
        transition: all 0.3s ease;
    }

    .sidebar a:hover,
    .sidebar a.active {
        color: #fff7ef;
        background-color: #7a4e2a;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .content {
        margin-left: 270px;
        padding: 30px;
    }

    .card-custom {
        border-radius: 15px;
        border: 1px solid #d8bfa7;
        background-color: #fff7ef;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .title {
        color: #5a3825;
        font-weight: 700;
    }

    .btn-primary-custom {
        background-color: #8b5e3c;
        border: none;
    }

    .card-featured {
        width: 240px;
        border-radius: 16px;
        background-color: #fff7ef;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        position: relative;
    }

    .card-featured .featured-tag {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 3px 8px;
        border-radius: 999px;
        background-color: #ffe8c5;
        color: #8b5e3c;
        font-weight: 700;
    }

    .summary-card h3 {
        font-weight: 700;
    }

    .table td, .table th {
        vertical-align: middle;
    }
</style>

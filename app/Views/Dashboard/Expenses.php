<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Expenses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<?php
    $categories = $categories ?? [];
    $expenses   = $expenses ?? [];
    $stats      = array_merge([
        'today' => 0,
        'month' => 0,
        'count' => 0,
        'total' => 0,
    ], $stats ?? []);
?>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Welcome, <?= session()->get('username')?>☕</h2>
        <a href="<?= base_url('dashboard') ?>">Dashboard</a>
        <a href="<?= base_url('orders') ?>">Orders</a>
        <a href="<?= base_url('product') ?>">Products</a>
        <a href="<?= base_url('expenses') ?>" class="active">Expenses</a>
        <a href="<?= base_url('reports') ?>">Reports</a>
        <a href="<?= base_url('settings') ?>">Settings</a>
        <a href="<?= base_url('logout') ?>">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
            <div>
           
                <h2 class="title m-0">Expenses Tracker</h2>
            </div>
            <button class="btn btn-success shadow-sm expense-modal-trigger" data-bs-toggle="modal" data-bs-target="#expenseModal">
                + Add Expense
            </button>
        </div>

        <?php if ($message = session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= esc($message) ?></div>
        <?php endif; ?>
        <?php if ($message = session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc($message) ?></div>
        <?php endif; ?>

        <!-- Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="summary-card bg-primary text-white text-center p-3 rounded-3">
                    <p class="mb-1 text-uppercase small">Today</p>
                    <h3 class="mb-0">₱<?= number_format($stats['today'], 2) ?></h3>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="summary-card bg-warning text-dark text-center p-3 rounded-3">
                    <p class="mb-1 text-uppercase small">This Month</p>
                    <h3 class="mb-0">₱<?= number_format($stats['month'], 2) ?></h3>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="summary-card bg-secondary text-white text-center p-3 rounded-3">
                    <p class="mb-1 text-uppercase small">Entries</p>
                    <h3 class="mb-0"><?= number_format($stats['count']) ?></h3>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="summary-card bg-success text-white text-center p-3 rounded-3">
                    <p class="mb-1 text-uppercase small">Total Recorded</p>
                    <h3 class="mb-0">₱<?= number_format($stats['total'], 2) ?></h3>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card-custom p-3 mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-lg-4 col-md-6">
                    <input type="text" id="expenseSearch" class="form-control" placeholder="Search description or notes...">
                </div>
                <div class="col-lg-4 col-md-3">
                    <select id="categoryFilter" class="form-select">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= esc($category) ?>"><?= esc($category) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-4 col-md-3">
                    <input type="month" id="monthFilter" class="form-control" value="<?= date('Y-m') ?>">
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h4 class="title mb-0">Expense Ledger</h4>
               
            </div>
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="expensesTable">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th class="text-end">Amount (₱)</th>
                            <th width="180">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($expenses)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">No expenses recorded yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($expenses as $expense): ?>
                                <tr data-description="<?= esc(strtolower($expense['description'])) ?>"
                                    data-category="<?= esc($expense['category']) ?>"
                                    data-month="<?= date('Y-m', strtotime($expense['date'])) ?>">
                                    <td><?= date('M d, Y', strtotime($expense['date'])) ?></td>
                                    <td><?= esc($expense['category']) ?></td>
                                    <td><?= esc($expense['description']) ?></td>
                                    <td class="text-end">₱<?= number_format($expense['amount'], 2) ?></td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-2">
                                            <button class="btn btn-sm btn-outline-primary expense-modal-trigger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#expenseModal"
                                                    data-expense='<?= json_encode($expense, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>'>
                                                Edit
                                            </button>
                                            <form method="post" action="<?= base_url('expenses/' . $expense['id'] . '/delete') ?>" onsubmit="return confirm('Delete this expense?');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <tfoot class="table-secondary">
                        <tr>
                            <th colspan="3" class="text-end">Total</th>
                            <th class="text-end">₱<?= number_format($stats['total'], 2) ?></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Expense Modal -->
    <div class="modal fade" id="expenseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="expenseForm" method="post" action="<?= base_url('expenses') ?>">
                    <?= csrf_field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Add Expense</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" required value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select" required>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= esc($category) ?>"><?= esc($category) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <input type="text" name="description" class="form-control" placeholder="e.g. Beans delivery, bar repairs" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Amount (₱)</label>
                            <input type="number" step="0.01" min="0" name="amount" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save Expense</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const expenseForm = document.getElementById('expenseForm');
    const expenseModalEl = document.getElementById('expenseModal');
    const defaultExpenseAction = expenseForm.getAttribute('action');
    const expenseModalTitle = expenseModalEl.querySelector('.modal-title');

    const resetExpenseForm = () => {
        expenseForm.reset();
        expenseForm.setAttribute('action', defaultExpenseAction);
        expenseModalTitle.textContent = 'Add Expense';
        expenseForm.date.value = '<?= date('Y-m-d') ?>';
    };

    expenseModalEl.addEventListener('hidden.bs.modal', resetExpenseForm);

    expenseModalEl.addEventListener('show.bs.modal', event => {
        const trigger = event.relatedTarget;
        if (!trigger) return;

        const payload = trigger.getAttribute('data-expense');
        if (!payload) {
            resetExpenseForm();
            return;
        }

        const expense = JSON.parse(payload);
        expenseModalTitle.textContent = 'Edit Expense';
        expenseForm.setAttribute('action', `${defaultExpenseAction}/${expense.id}`);
        expenseForm.date.value = expense.date;
        expenseForm.category.value = expense.category;
        expenseForm.description.value = expense.description;
        expenseForm.amount.value = expense.amount;
    });

    const rows = Array.from(document.querySelectorAll('#expensesTable tbody tr'));
    const searchInput = document.getElementById('expenseSearch');
    const categoryFilter = document.getElementById('categoryFilter');
    const monthFilter = document.getElementById('monthFilter');

    const applyFilters = () => {
        const query = (searchInput.value || '').toLowerCase();
        const category = categoryFilter.value;
        const month = monthFilter.value;

        rows.forEach(row => {
            if (!row.dataset.description) {
                row.style.display = '';
                return;
            }

            const matchesSearch = row.dataset.description.includes(query);
            const matchesCategory = !category || row.dataset.category === category;
            const matchesMonth = !month || row.dataset.month === month;

            row.style.display = (matchesSearch && matchesCategory && matchesMonth) ? '' : 'none';
        });
    };

    searchInput.addEventListener('input', applyFilters);
    categoryFilter.addEventListener('change', applyFilters);
    monthFilter.addEventListener('change', applyFilters);
</script>

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

    .sidebar a.active,
    .sidebar a:hover {
        color: #fff7ef;
        background-color: #7a4e2a;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .sidebar h2 {
        font-weight: 700;
        margin-bottom: 35px;
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

    .summary-card h3 {
        font-weight: 700;
    }

    table tbody tr td {
        vertical-align: middle;
    }
</style>

</body>
</html>

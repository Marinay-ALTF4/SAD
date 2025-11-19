<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Expenses - Coffee Shop POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

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
        <h2 class="title mb-4">Shop Expenses</h2>

        <!-- Add Expense Button -->
        <div class="mb-3 text-end">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                Add Expense
            </button>
        </div>

        <!-- Expenses Table Card -->
        <div class="card-custom p-4">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Amount (₱)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($expenses as $e): ?>
                    <tr>
                        <td><?= date('Y-m-d', strtotime($e['date'])) ?></td>
                        <td><?= $e['category'] ?></td>
                        <td><?= $e['description'] ?></td>
                        <td><?= number_format($e['amount'], 2) ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm">Edit</button>
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-secondary">
                        <th colspan="3" class="text-end">Total Expenses:</th>
                        <th colspan="2">₱<?= number_format($total_expenses, 2) ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Add Expense Modal -->
    <div class="modal fade" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="<?= base_url('expenses/add') ?>" method="POST">
              <div class="modal-header">
                <h5 class="modal-title" id="addExpenseModalLabel">Add New Expense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                    <div class="mb-3">
                        <label>Date</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Category</label>
                        <select name="category" class="form-control" required>
                            <option value="Ingredients">Ingredients</option>
                            <option value="Utilities">Utilities</option>
                            <option value="Rent">Rent</option>
                            <option value="Miscellaneous">Miscellaneous</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <input type="text" name="description" class="form-control" placeholder="Expense details" required>
                    </div>
                    <div class="mb-3">
                        <label>Amount (₱)</label>
                        <input type="number" name="amount" class="form-control" step="0.01" required>
                    </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-success">Add Expense</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              </div>
          </form>
        </div>
      </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
    body {
        background-color: #f3e5d8; /* Cream */
        font-family: 'Poppins', sans-serif;
    }

    /* Sidebar */
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

    .sidebar a.active {
        color: #fff7ef;
        background-color: #7a4e2a;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
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

    .sidebar a:hover {
        color: #fff7ef;
        background-color: #7a4e2a;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    /* Main content */
    .content {
        margin-left: 270px;
        padding: 30px;
    }

    /* Cards */
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
</style>

</body>
</html>

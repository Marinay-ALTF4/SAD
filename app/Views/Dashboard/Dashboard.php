<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Coffee Shop Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Welcome, <?= session()->get('username')?>☕</h2>
        <a href="<?= base_url('dashboard') ?>" class="active">Dashboard</a>
        <a href="<?= base_url('orders') ?>">Orders</a>
        <a href="<?= base_url('product') ?>">Products</a>
        <a href="<?= base_url('expenses') ?>">Expenses</a>
        <a href="<?= base_url('reports') ?>">Reports</a>
        <a href="<?= base_url('settings') ?>">Settings</a>
        <a href="<?= base_url('logout') ?>">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h2 class="title mb-4">Welcome to the Coffee Shop Dashboard</h2>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-4 card-custom">
                    <h4 class="mb-2">Today's Sales</h4>
                    <h2 class="title">₱3,240</h2>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-4 card-custom">
                    <h4 class="mb-2">Orders</h4>
                    <h2 class="title">58</h2>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-4 card-custom">
                    <h4 class="mb-2">New Customers</h4>
                    <h2 class="title">12</h2>
                </div>
            </div>
        </div>

        <div class="mt-5 card-custom p-4">
            <h4 class="title mb-3">Recent Orders</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Order</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Ana Santos</td>
                        <td>Cappuccino</td>
                        <td>₱120</td>
                        <td><span class="badge bg-success">Completed</span></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Mark Cruz</td>
                        <td>Iced Latte</td>
                        <td>₱140</td>
                        <td><span class="badge bg-warning">Pending</span></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Jessa Kim</td>
                        <td>Mocha</td>
                        <td>₱150</td>
                        <td><span class="badge bg-success">Completed</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>

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
        margin-left: 270px; /* slightly wider to account for borders */
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

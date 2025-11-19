<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Coffee Shop - Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
    <h2>Welcome, <?= session()->get('username')?>☕</h2>
            <a href="<?= base_url('dashboard') ?>">Dashboard</a>
        <a href="<?= base_url('orders') ?>">Orders</a>
        <a href="<?= base_url('product') ?>" class="active">Products</a> 
        <a href="<?= base_url('expenses') ?>">Expenses</a>
        <a href="<?= base_url('reports') ?>">Reports</a>
        <a href="<?= base_url('settings') ?>">Settings</a>
        <a href="<?= base_url('logout') ?>">Logout</a>
    </div>

   <!-- Main Content -->
<div class="content">
    <h2 class="title mb-4">Products</h2>

    <!-- Product Summary -->
    <div class="mb-4 d-flex gap-3 flex-wrap">
        <div class="summary-card bg-success text-white text-center p-3 rounded flex-fill">
            <h5>5</h5>
            <p>Available</p>
        </div>
        <div class="summary-card bg-secondary text-white text-center p-3 rounded flex-fill">
            <h5>2</h5>
            <p>Out of Stock</p>
        </div>
        <div class="summary-card bg-primary text-white text-center p-3 rounded flex-fill">
            <h5>7</h5>
            <p>Total Products</p>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <input type="text" class="form-control w-50" placeholder="Search products...">
        <select class="form-select w-25">
            <option value="">All Categories</option>
            <option value="coffee">Coffee</option>
            <option value="cold-drinks">Cold Drinks</option>
            <option value="pastry">Pastry</option>
        </select>
        <a href="<?= base_url('products/add') ?>" class="btn btn-primary" style="background-color:#8b5e3c; border:none;">
            + Add Product
        </a>
    </div>

    <!-- Featured Products -->
    <div class="d-flex mb-4 gap-3 flex-wrap">
        <div class="card-featured p-3">
            <img src="public\image\download (1).jpg" class="card-img-top" alt="Cappuccino">
            <div class="card-body text-center">
                <h6>Cappuccino</h6>
                <h2>₱120</h2>
            </div>
        </div>
        <div class="card-featured p-3">
            <img src="public\image\download (2).jpg" class="card-img-top" alt="Iced Latte">
            <div class="card-body text-center">
                <h6>Iced Latte</h6>
                <h2>₱140</h2>
            </div>
        </div>
        <div class="card-featured p-3">
            <img src="public\image\download (3).jpg" class="card-img-top" alt="Espresso">
            <div class="card-body text-center">
                <h6>Espresso</h6>
                <h2>₱85</h2>
            </div>
            
        </div>
    </div>

    <!-- Product List Table -->
    <div class="card-custom p-4">
        <h4 class="title mb-3">Product List</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th width="160px">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Cappuccino</td>
                    <td>Coffee</td>
                    <td>₱120</td>
                    <td><span class="badge bg-success">Available</span></td>
                    <td>
                        <a href="#" class="btn btn-sm btn-warning">Edit</a>
                        <a href="#" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Iced Latte</td>
                    <td>Cold Drinks</td>
                    <td>₱140</td>
                    <td><span class="badge bg-success">Available</span></td>
                    <td>
                        <a href="#" class="btn btn-sm btn-warning">Edit</a>
                        <a href="#" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Espresso</td>
                    <td>Pastry</td>
                    <td>₱85</td>
                    <td><span class="badge bg-secondary">Out of Stock</span></td>
                    <td>
                        <a href="#" class="btn btn-sm btn-warning">Edit</a>
                        <a href="#" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


<style>
    body {
        background-color: #f3e5d8;
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

    .sidebar a.active {
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

        /* Featured Product Cards */
        .card-featured {
        width: 200px;
        border-radius: 12px;
        background-color: #fff7ef;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        text-align: center;
    }
    .card-featured img {
        width: 100%;
        border-radius: 10px;
        margin-bottom: 8px;
    }

    /* Summary Cards */
    .summary-card h5 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
    }
    .summary-card p {
        margin: 0;
        font-size: 0.9rem;
    }
</style>

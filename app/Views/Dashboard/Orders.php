<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Coffee Shop - Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
  <h2>Welcome, <?= session()->get('username')?>☕</h2>   
   <a href="<?= base_url('dashboard') ?>" c>Dashboard</a>
    <a href="<?= base_url('orders') ?>" class="active">Orders</a>
    <a href="<?= base_url('product') ?>">Products</a>
    <a href="<?= base_url('expenses') ?>">Expenses</a>
    <a href="<?= base_url('report') ?>">Reports</a>
    <a href="<?= base_url('settings') ?>">Settings</a>
    <a href="<?= base_url('logout') ?>">Logout</a>
  </div>

  <!-- Main Content -->
  <div class="content">
    <h2 class="title mb-4">Orders</h2>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
      <div class="col-md-4">
        <div class="card-custom p-4 text-center">
          <h5>Today's Orders</h5>
          <h2 class="title">10</h2>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card-custom p-4 text-center">
          <h5>Pending Orders</h5>
          <h2 class="title">4</h2>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card-custom p-4 text-center">
          <h5>Completed Orders</h5>
          <h2 class="title">6</h2>
        </div>
      </div>
    </div>

    <!-- Filters & Create Order -->
    <div class="d-flex justify-content-between gap-2 flex-wrap mb-4">
      <div class="d-flex gap-2">
        <select class="form-select" style="max-width:200px;">
          <option value="">All Status</option>
          <option value="Pending">Pending</option>
          <option value="Completed">Completed</option>
          <option value="Cancelled">Cancelled</option>
        </select>
        <input type="text" class="form-control" placeholder="Search by customer..." style="max-width:200px;">
      </div>
      <a href="#" class="btn btn-primary btn-primary-custom">+ Create Order</a>
    </div>

    <!-- Pending / Cancelled Orders -->
    <div class="card-custom p-4 mb-4">
      <h4 class="title mb-3">Pending / Cancelled Orders</h4>
      <table class="table table-bordered table-hover align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Order Items</th>
            <th>Total</th>
            <th>Status</th>
            <th>Order Date</th>
            <th width="200px">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Ana Santos</td>
            <td>Cappuccino, Muffin</td>
            <td>₱200</td>
            <td><span class="badge bg-warning">Pending</span></td>
            <td>2025-11-19 10:30 AM</td>
            <td class="text-center">
              <a href="#" class="btn btn-sm btn-info">View</a>
              <a href="#" class="btn btn-sm btn-warning">Edit</a>
              <a href="#" class="btn btn-sm btn-danger">Delete</a>
            </td>   
          </tr>
          <tr>
            <td>2</td>
            <td>Jessa Kim</td>
            <td>Mocha</td>
            <td>₱150</td>
            <td><span class="badge bg-danger">Cancelled</span></td>
            <td>2025-11-18 04:15 PM</td>
            <td class="text-center">
              <a href="#" class="btn btn-sm btn-info">View</a>
              <a href="#" class="btn btn-sm btn-warning">Edit</a>
              <a href="#" class="btn btn-sm btn-danger">Delete</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Completed Orders -->
    <div class="card-custom p-4">
      <h4 class="title mb-3">Completed Orders</h4>
      <table class="table table-bordered table-hover align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Order Items</th>
            <th>Total</th>
            <th>Order Date</th>
            <th width="200px">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Mark Cruz</td>
            <td>Iced Latte</td>
            <td>₱140</td>
            <td>2025-11-19 11:00 AM</td>
            <td class="text-center">
              <a href="#" class="btn btn-sm btn-info">View</a>
              <a href="#" class="btn btn-sm btn-warning">Edit</a>
              <a href="#" class="btn btn-sm btn-danger">Delete</a>
            </td>
          </tr>
          <tr>
            <td>2</td>
            <td>John Doe</td>
            <td>Espresso, Croissant</td>
            <td>₱180</td>
            <td>2025-11-19 09:30 AM</td>
            <td class="text-center">
              <a href="#" class="btn btn-sm btn-info">View</a>
              <a href="#" class="btn btn-sm btn-warning">Edit</a>
              <a href="#" class="btn btn-sm btn-danger">Delete</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>

</body>
</html>

<style>
body {
  background-color: #f3e5d8;
  font-family: 'Poppins', sans-serif;
  margin: 0; /* remove default body margin */
}

/* Sidebar flush to edge */
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
.content { margin-left: 250px; padding: 30px; }

/* Cards */
.card-custom {
  border-radius: 15px;
  border: 1px solid #d8bfa7;
  background-color: #fff7ef;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.title { color: #5a3825; font-weight: 700; }

/* Buttons */
.btn-primary-custom {
  background-color: #8b5e3c;
  border: none;
}

/* Table badges */
.badge { font-size: 0.9rem; }
</style>

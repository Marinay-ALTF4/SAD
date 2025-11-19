    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <title>Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    </head>
    <body>

    <div class="sidebar">
    <h2>Welcome, <?= session()->get('username')?>☕</h2>
        <a href="<?= base_url('dashboard') ?>">Dashboard</a>
        <a href="<?= base_url('orders') ?>">Orders</a>
        <a href="<?= base_url('product') ?>">Products</a>
        <a href="<?= base_url('expenses') ?>">Expenses</a>
        <a href="<?= base_url('reports') ?>" class="active">Reports</a>
        <a href="<?= base_url('settings') ?>">Settings</a>
        <a href="<?= base_url('logout') ?>">Logout</a>
    </div>

    <div class="content">
        <h2 class="title mb-4">Reports</h2>

        <!-- Summary Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card-custom text-center p-4">
                    <h5>Total Sales Today</h5>
                    <h2>₱<?= number_format($total_sales_today,2) ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-custom text-center p-4">
                    <h5>Total Orders</h5>
                    <h2><?= $total_orders ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-custom text-center p-4">
                    <h5>Pending Orders</h5>
                    <h2><?= $pending_orders ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-custom text-center p-4">
                    <h5>Completed Orders</h5>
                    <h2><?= $completed_orders ?></h2>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card-custom p-4">
                    <h5>Daily Sales</h5>
                    <canvas id="dailySalesChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card-custom p-4">
                    <h5>Top Products</h5>
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Daily Sales Chart
    const dailySalesChart = new Chart(document.getElementById('dailySalesChart'), {
        type:'line',
        data: {
            labels: [<?php foreach($daily_sales_labels as $label){ echo "'$label',"; } ?>],
            datasets:[{
                label:'Sales (₱)',
                data: [<?php foreach($daily_sales_data as $val){ echo $val.","; } ?>],
                borderColor:'#8b5e3c', backgroundColor:'rgba(139,94,60,0.2)', tension:0.3
            }]
        },
        options: { responsive:true }
    });

    // Top Products Chart
    const topProductsChart = new Chart(document.getElementById('topProductsChart'), {
        type:'bar',
        data:{
            labels: [<?php foreach($top_products_labels as $label){ echo "'$label',"; } ?>],
            datasets:[{label:'Stock', data:[<?php foreach($top_products_data as $val){ echo $val.","; } ?>], backgroundColor:'#8b5e3c'}]
        },
        options: { responsive:true }
    });
    </script>

    </body>
    </html> 
    <style>
    body {
        background-color:#f3e5d8;
        font-family:'Poppins',sans-serif;
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
    }

    .sidebar a:hover {
        color: #fff7ef;
        background-color: #7a4e2a;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    /* Main content */
    .content {
        margin-left:270px;
        padding:30px;
    }

    /* Cards */
    .card-custom {
        border-radius: 15px;
        border: 1px solid #d8bfa7;
        background-color: #fff7ef;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 20px;
    }

    /* Card hover effect */
    .card-custom:hover {
        box-shadow: 0 6px 16px rgba(0,0,0,0.15);
    }

    /* Table styles */
    .table th {
        background-color: #8b5e3c;
        color: #fff7ef;
    }
    .table td, .table th {
        vertical-align: middle;
    }
    
    </style>
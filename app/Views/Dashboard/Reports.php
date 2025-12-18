<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reports</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<?php
    $dailySales       = $daily_sales ?? ['labels' => [], 'data' => []];
    $monthlySalesData = $monthly_sales_chart ?? ['labels' => [], 'data' => []];
    $topItems         = $top_items ?? [];
    $recentOrders     = $recent_orders ?? [];
?>

<div class="sidebar">
<h2>Welcome, <?= session()->get('username')?>☕</h2>
    <a href="<?= base_url('dashboard') ?>">Dashboard</a>
    <a href="<?= base_url('orders') ?>">Orders</a>
    <a href="<?= base_url('product') ?>">Products</a>
    <a href="<?= base_url('expenses') ?>">Expenses</a>
    <?php if (session()->get('role') === 'admin'): ?>
        <a href="<?= base_url('reports') ?>" class="active">Reports</a>
        <a href="<?= base_url('settings') ?>">Settings</a>
    <?php endif; ?>
    <a href="<?= base_url('logout') ?>">Logout</a>
</div>

<div class="content">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <h2 class="title mb-0">Performance Reports</h2>
        <button class="btn btn-outline-dark shadow-sm px-3 py-2 fw-semibold print-hidden" style="font-size: 0.95rem;" onclick="window.print()">
            Print Report
        </button>
    </div>

    <!-- Sales Summary -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card-custom text-center p-4">
                <p class="text-muted mb-1 text-uppercase small">Sales Today</p>
                <h2>₱<?= number_format($total_sales_today ?? 0, 2) ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-custom text-center p-4">
                <p class="text-muted mb-1 text-uppercase small">Weekly Sales</p>
                <h2>₱<?= number_format($weekly_sales ?? 0, 2) ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-custom text-center p-4">
                <p class="text-muted mb-1 text-uppercase small">Monthly Sales</p>
                <h2>₱<?= number_format($monthly_sales ?? 0, 2) ?></h2>
            </div>
        </div>
    </div>

    <!-- Orders Summary -->
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card-custom text-center p-4">
                <p class="text-muted mb-1 text-uppercase small">Total Orders</p>
                <h3><?= number_format($total_orders ?? 0) ?></h3>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card-custom text-center p-4">
                <p class="text-muted mb-1 text-uppercase small">Completed Orders</p>
                <h3><?= number_format($completed_orders ?? 0) ?></h3>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card-custom text-center p-4">
                <p class="text-muted mb-1 text-uppercase small">Cancelled Orders</p>
                <h3><?= number_format($cancelled_orders ?? 0) ?></h3>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card-custom text-center p-4">
                <p class="text-muted mb-1 text-uppercase small">Weekly Orders</p>
                <h3><?= number_format($weekly_orders ?? 0) ?></h3>
            </div>
        </div>
    </div>

    <!-- Charts (print on next page) -->
    <div class="row g-4 mb-4 print-break">
        <div class="col-lg-6">
            <div class="card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Daily Sales (Last 7 days)</h5>
                    <small class="text-muted">Line shows completed order revenue.</small>
                </div>
                <canvas id="dailySalesChart"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Monthly Sales Trend</h5>
                    <small class="text-muted">Past 6 months</small>
                </div>
                <canvas id="monthlySalesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recommendation space intentionally left minimal after removing tables -->
</div>

<script>
const dailySalesConfig = {
    type: 'line',
    data: {
        labels: <?= json_encode($dailySales['labels']) ?>,
        datasets: [{
            label: 'Sales (₱)',
            data: <?= json_encode(array_map('floatval', $dailySales['data'])) ?>,
            borderColor: '#8b5e3c',
            backgroundColor: 'rgba(139,94,60,0.15)',
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
};

const monthlySalesConfig = {
    type: 'bar',
    data: {
        labels: <?= json_encode($monthlySalesData['labels']) ?>,
        datasets: [{
            label: 'Sales (₱)',
            data: <?= json_encode(array_map('floatval', $monthlySalesData['data'])) ?>,
            backgroundColor: '#c2956c'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
};

new Chart(document.getElementById('dailySalesChart'), dailySalesConfig);
new Chart(document.getElementById('monthlySalesChart'), monthlySalesConfig);
</script>

<style>
body {
    background-color:#f3e5d8;
    font-family:'Poppins',sans-serif;
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
    margin-left:270px;
    padding:30px;
}

/* Print adjustments: hide sidebar and reset content width */
@media print {
    .sidebar {
        display: none !important;
    }
    .content {
        margin-left: 0 !important;
        width: 100% !important;
        padding: 0 10mm !important;
    }
    .print-break {
        page-break-before: always;
        break-before: page;
        page-break-inside: avoid;
    }
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

/* Hide controls when printing */
@media print {
    .print-hidden {
        display: none !important;
    }
}

.table thead th {
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #f3e5d8;
}
</style>

</body>
</html>
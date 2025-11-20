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
    <a href="<?= base_url('reports') ?>" class="active">Reports</a>
    <a href="<?= base_url('settings') ?>">Settings</a>
    <a href="<?= base_url('logout') ?>">Logout</a>
</div>

<div class="content">
    <h2 class="title mb-4">Performance Reports</h2>

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
                <p class="text-muted mb-1 text-uppercase small">Pending Orders</p>
                <h3><?= number_format($pending_orders ?? 0) ?></h3>
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
                <p class="text-muted mb-1 text-uppercase small">Weekly Orders</p>
                <h3><?= number_format($weekly_orders ?? 0) ?></h3>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row g-4 mb-4">
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

    <!-- Tables & Recommendation -->
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card-custom p-4 h-100">
                <h5 class="mb-3">Top Menu Items</h5>
                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th class="text-end">Qty Sold</th>
                                <th class="text-end">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($topItems)): ?>
                                <tr><td colspan="3" class="text-center text-muted">No completed orders yet.</td></tr>
                            <?php else: ?>
                                <?php foreach ($topItems as $item): ?>
                                    <tr>
                                        <td><?= esc($item['name']) ?></td>
                                        <td class="text-end"><?= number_format($item['quantity']) ?></td>
                                        <td class="text-end">₱<?= number_format($item['revenue'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card-custom p-4 h-100">
                <h5 class="mb-3">Recent Orders</h5>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentOrders)): ?>
                                <tr><td colspan="4" class="text-center text-muted">No orders yet.</td></tr>
                            <?php else: ?>
                                <?php foreach ($recentOrders as $order): ?>
                                    <tr>
                                        <td><?= esc($order['id']) ?></td>
                                        <td><?= esc($order['customer_name']) ?></td>
                                        <td><span class="badge <?= $order['status'] === 'Completed' ? 'bg-success' : ($order['status'] === 'Pending' ? 'bg-warning text-dark' : 'bg-danger') ?>"><?= esc($order['status']) ?></span></td>
                                        <td class="text-end">₱<?= number_format($order['total'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card-custom p-4 bg-light">
                <h5 class="mb-2">Recommendation</h5>
                <p class="mb-0"><?= esc($recommendation ?? 'Collect more data to unlock tailored insights.') ?></p>
            </div>
        </div>
    </div>
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

.table thead th {
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #f3e5d8;
}
</style>

</body>
</html>
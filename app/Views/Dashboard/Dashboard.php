<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Coffee Shop Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<?php
    if (! function_exists('renderOrderItems')) {
        function renderOrderItems($items): string
        {
            if (is_string($items)) {
                $items = json_decode($items, true) ?? [];
            }

            if (empty($items) || ! is_array($items)) {
                return '-';
            }

            $labels = array_map(static function ($item) {
                $quantity = isset($item['quantity']) ? (int) $item['quantity'] : 1;
                $label = $item['name'] ?? '';
                // Remove size from name if present (format: "Product Name (Size)")
                $label = preg_replace('/\s*\((Small|Medium|Large)\)$/', '', $label);

                return $quantity > 1 ? "{$label} x{$quantity}" : $label;
            }, $items);

            return implode(', ', $labels);
        }
    }

    if (! function_exists('renderOrderSizes')) {
        function renderOrderSizes($items): string
        {
            if (is_string($items)) {
                $items = json_decode($items, true) ?? [];
            }

            if (empty($items) || ! is_array($items)) {
                return '-';
            }

            $sizes = [];
            foreach ($items as $item) {
                $name = $item['name'] ?? '';
                $quantity = isset($item['quantity']) ? (int) $item['quantity'] : 1;
                
                // Extract size from name (format: "Product Name (Size)")
                $size = 'Small'; // Default
                if (preg_match('/\s*\((Small|Medium|Large)\)$/', $name, $matches)) {
                    $size = $matches[1];
                } elseif (isset($item['size'])) {
                    $size = $item['size'];
                }
                
                // Display size with quantity if multiple
                if ($quantity > 1) {
                    $sizes[] = "{$size} x{$quantity}";
                } else {
                    $sizes[] = $size;
                }
            }

            return implode(', ', $sizes);
        }
    }

    $todaySales   = $todaySales ?? 0;
    $totalOrders  = $totalOrders ?? 0;
    $newCustomers = $newCustomers ?? 0;
    $recentOrders = $recentOrders ?? [];
?>

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
                    <h2 class="title">₱<?= number_format($todaySales, 2) ?></h2>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-4 card-custom">
                    <h4 class="mb-2">Orders</h4>
                    <h2 class="title"><?= number_format($totalOrders) ?></h2>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-4 card-custom">
                    <h4 class="mb-2">New Customers</h4>
                    <h2 class="title"><?= number_format($newCustomers) ?></h2>
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
                        <th>Cup Size</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentOrders)): ?>
                        <tr>
                            <td colspan="6" class="text-center">No recent orders yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td><?= esc($order['id']) ?></td>
                                <td><?= esc($order['customer_name']) ?></td>
                                <td><?= esc(renderOrderItems($order['items'])) ?></td>
                                <td><?= esc(renderOrderSizes($order['items'])) ?></td>
                                <td>₱<?= number_format($order['total'], 2) ?></td>
                                <td>
                                    <?php
                                        $badge = [
                                            'Pending'   => 'bg-warning text-dark',
                                            'Completed' => 'bg-success',
                                            'Cancelled' => 'bg-danger',
                                        ][ $order['status'] ] ?? 'bg-secondary';
                                    ?>
                                    <span class="badge <?= $badge ?>"><?= esc($order['status']) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                $name = $item['name'] ?? '';
                // Remove size from name if present (format: "Product Name (Size)")
                $name = preg_replace('/\s*\((Small|Medium|Large)\)$/', '', $name);
                $quantity = isset($item['quantity']) ? (int) $item['quantity'] : 1;
                return $quantity > 1 ? "{$name} x{$quantity}" : $name;
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

    $pendingOrders   = $pendingOrders ?? [];
    $completedOrders = $completedOrders ?? [];
    $cancelledOrders = $cancelledOrders ?? [];
    $categories      = $categories ?? [];
    $products        = $products ?? [];
    $stats = array_merge([
        'todayOrders' => 0,
        'pending'     => 0,
        'completed'   => 0,
        'cancelled'   => 0,
    ], $stats ?? []);
?>

  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Welcome, <?= session()->get('username')?>☕</h2>
    <a href="<?= base_url('dashboard') ?>">Dashboard</a>
    <a href="<?= base_url('orders') ?>" class="active">Orders</a>
    <a href="<?= base_url('product') ?>">Products</a>
    <?php if (session()->get('role') === 'admin'): ?>
      <a href="<?= base_url('expenses') ?>">Expenses</a>
      <a href="<?= base_url('reports') ?>">Reports</a>
      <a href="<?= base_url('settings') ?>">Settings</a>
    <?php endif; ?>
    <a href="<?= base_url('logout') ?>">Logout</a>
  </div>

  <!-- Main Content -->
  <div class="content">
    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
      <h2 class="title m-0">Orders</h2>
      <button class="btn btn-primary btn-primary-custom order-modal-trigger" data-bs-toggle="modal" data-bs-target="#orderModal">
        + Create Order
      </button>
    </div>

    <?php if ($message = session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= esc($message) ?></div>
    <?php endif; ?>
    <?php if ($message = session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= esc($message) ?></div>
    <?php endif; ?>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
      <div class="col-md-3">
        <div class="card-custom p-4 text-center">
          <h5>Today's Orders</h5>
          <h2 class="title"><?= number_format($stats['todayOrders']) ?></h2>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card-custom p-4 text-center">
          <h5>Pending Orders</h5>
          <h2 class="title"><?= number_format($stats['pending']) ?></h2>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card-custom p-4 text-center">
          <h5>Completed Orders</h5>
          <h2 class="title"><?= number_format($stats['completed']) ?></h2>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card-custom p-4 text-center">
          <h5>Cancelled Orders</h5>
          <h2 class="title"><?= number_format($stats['cancelled']) ?></h2>
        </div>
      </div>
    </div>

    <!-- Pending Orders -->
    <div class="card-custom p-4 mb-4">
      <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h4 class="title mb-0">Pending Orders</h4>
       
      </div>
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Customer</th>
              <th>Order Items</th>
              <th>Cup Size</th>
              <th>Total</th>
              <th>Status</th>
              <th>Order Date</th>
              <th width="230">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($pendingOrders)): ?>
              <tr>
                <td colspan="8" class="text-center text-muted">No pending orders.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($pendingOrders as $order): ?>
                <tr>
                  <td><?= esc($order['id']) ?></td>
                  <td><?= esc($order['customer_name']) ?></td>
                  <td><?= esc(renderOrderItems($order['items'])) ?></td>
                  <td><?= esc(renderOrderSizes($order['items'])) ?></td>
                  <td>₱<?= number_format($order['total'], 2) ?></td>
                  <td width="170">
                    <form method="post" action="<?= base_url('orders/' . $order['id'] . '/status') ?>">
                      <?= csrf_field() ?>
                      <select name="status" class="form-select form-select-sm status-select" onchange="this.form.submit()">
                        <?php foreach (['Pending','Completed','Cancelled'] as $status): ?>
                          <option value="<?= $status ?>" <?= $status === $order['status'] ? 'selected' : '' ?>>
                            <?= $status ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </form>
                  </td>
                  <td><?= date('M d, Y g:i A', strtotime($order['order_date'])) ?></td>
                  <td class="text-center">
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                      <button class="btn btn-sm btn-info text-white order-view-trigger" data-bs-toggle="modal" data-bs-target="#viewModal"
                              data-order='<?= json_encode($order, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>'>
                        View
                      </button>
                      <button class="btn btn-sm btn-warning text-dark order-modal-trigger" data-bs-toggle="modal" data-bs-target="#orderModal"
                              data-order='<?= json_encode($order, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>'>
                        Edit
                      </button>
                      <form method="post" action="<?= base_url('orders/' . $order['id'] . '/delete') ?>" onsubmit="return confirm('Delete this order?');">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
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

    <!-- Completed Orders -->
    <div class="card-custom p-4 mb-4">
      <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h4 class="title mb-0">Completed Orders</h4>
        
      </div>
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Customer</th>
              <th>Order Items</th>
              <th>Cup Size</th>
              <th>Total</th>
              <th>Status</th>
              <th>Order Date</th>
              <th width="230">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($completedOrders)): ?>
              <tr>
                <td colspan="8" class="text-center text-muted">No completed orders yet.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($completedOrders as $order): ?>
                <tr>
                  <td><?= esc($order['id']) ?></td>
                  <td><?= esc($order['customer_name']) ?></td>
                  <td><?= esc(renderOrderItems($order['items'])) ?></td>
                  <td><?= esc(renderOrderSizes($order['items'])) ?></td>
                  <td>₱<?= number_format($order['total'], 2) ?></td>
                  <td width="170">
                    <form method="post" action="<?= base_url('orders/' . $order['id'] . '/status') ?>">
                      <?= csrf_field() ?>
                      <select name="status" class="form-select form-select-sm status-select" onchange="this.form.submit()">
                        <?php foreach (['Completed','Pending','Cancelled'] as $status): ?>
                          <option value="<?= $status ?>" <?= $status === $order['status'] ? 'selected' : '' ?>>
                            <?= $status ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </form>
                  </td>
                  <td><?= date('M d, Y g:i A', strtotime($order['order_date'])) ?></td>
                  <td class="text-center">
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                      <button class="btn btn-sm btn-info text-white order-view-trigger" data-bs-toggle="modal" data-bs-target="#viewModal"
                              data-order='<?= json_encode($order, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>'>
                        View
                      </button>
                      <button class="btn btn-sm btn-warning text-dark order-modal-trigger" data-bs-toggle="modal" data-bs-target="#orderModal"
                              data-order='<?= json_encode($order, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>'>
                        Edit
                      </button>
                      <form method="post" action="<?= base_url('orders/' . $order['id'] . '/delete') ?>" onsubmit="return confirm('Delete this order?');">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
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

    <!-- Cancelled Orders -->
    <div class="card-custom p-4">
      <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h4 class="title mb-0">Cancelled Orders</h4>
        
      </div>
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Customer</th>
              <th>Order Items</th>
              <th>Cup Size</th>
              <th>Total</th>
              <th>Status</th>
              <th>Order Date</th>
              <th width="230">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($cancelledOrders)): ?>
              <tr>
                <td colspan="8" class="text-center text-muted">No cancelled orders yet.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($cancelledOrders as $order): ?>
                <tr>
                  <td><?= esc($order['id']) ?></td>
                  <td><?= esc($order['customer_name']) ?></td>
                  <td><?= esc(renderOrderItems($order['items'])) ?></td>
                  <td><?= esc(renderOrderSizes($order['items'])) ?></td>
                  <td>₱<?= number_format($order['total'], 2) ?></td>
                  <td width="170">
                    <form method="post" action="<?= base_url('orders/' . $order['id'] . '/status') ?>">
                      <?= csrf_field() ?>
                      <select name="status" class="form-select form-select-sm status-select" onchange="this.form.submit()">
                        <?php foreach (['Cancelled','Pending','Completed'] as $status): ?>
                          <option value="<?= $status ?>" <?= $status === $order['status'] ? 'selected' : '' ?>>
                            <?= $status ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </form>
                  </td>
                  <td><?= date('M d, Y g:i A', strtotime($order['order_date'])) ?></td>
                  <td class="text-center">
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                      <button class="btn btn-sm btn-info text-white order-view-trigger" data-bs-toggle="modal" data-bs-target="#viewModal"
                              data-order='<?= json_encode($order, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>'>
                        View
                      </button>
                      <button class="btn btn-sm btn-warning text-dark order-modal-trigger" data-bs-toggle="modal" data-bs-target="#orderModal"
                              data-order='<?= json_encode($order, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>'>
                        Edit
                      </button>
                      <form method="post" action="<?= base_url('orders/' . $order['id'] . '/delete') ?>" onsubmit="return confirm('Delete this order?');">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
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

  <!-- Create/Edit Order Modal -->
  <div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <form id="orderForm" method="post" action="<?= base_url('orders') ?>">
          <?= csrf_field() ?>
          <div class="modal-header">
            <h5 class="modal-title">Create Order</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Customer (optional)</label>
              <input type="text" class="form-control" name="customer_name" placeholder="Walk-in Customer">
            </div>

            <div class="mb-3">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label mb-0">Order Items</label>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addItemBtn">+ Add Item</button>
              </div>
              <div class="table-responsive">
                <table class="table table-sm">
                  <thead>
                    <tr>
                      <th width="20%">Category</th>
                      <th width="25%">Product</th>
                      <th width="15%">Cup Size</th>
                      <th width="12%">Price</th>
                      <th width="12%">Qty</th>
                      <th width="10%"></th>
                    </tr>
                  </thead>
                  <tbody id="itemsBody"></tbody>
                </table>
              </div>
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                  <option value="Pending">Pending</option>
                  <option value="Completed">Completed</option>
                  <option value="Cancelled">Cancelled</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Total</label>
                <input type="text" class="form-control" id="orderTotal" value="₱0.00" readonly>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary btn-primary-custom">Save Order</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View Order Modal -->
  <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Order Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p><strong>Customer:</strong> <span id="viewCustomer"></span></p>
          <p><strong>Status:</strong> <span id="viewStatus" class="badge"></span></p>
          <p><strong>Order Date:</strong> <span id="viewDate"></span></p>
          <div class="mb-2">
            <strong>Items:</strong>
            <ul id="viewItems" class="mb-0"></ul>
          </div>
          <p class="mt-3"><strong>Total:</strong> <span id="viewTotal"></span></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-primary-custom order-modal-trigger" data-bs-target="#orderModal" data-bs-toggle="modal" data-bs-dismiss="modal">
            Edit Order
          </button>
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const orderForm = document.getElementById('orderForm');
    const modalElement = document.getElementById('orderModal');
    const viewModalElement = document.getElementById('viewModal');
    const itemsBody = document.getElementById('itemsBody');
    const totalField = document.getElementById('orderTotal');
    const defaultAction = orderForm.getAttribute('action');

    const viewModal = new bootstrap.Modal(viewModalElement);

    const statusBadgeClass = {
      Pending: 'bg-warning text-dark',
      Completed: 'bg-success',
      Cancelled: 'bg-danger'
    };

    // Products and categories data from PHP
    const categories = <?= json_encode($categories, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
    const products = <?= json_encode($products, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;

    // Build category options HTML
    const buildCategoryOptions = (selectedCategory = '') => {
      let options = '<option value="">Select Category</option>';
      for (const [key, label] of Object.entries(categories)) {
        options += `<option value="${key}" ${key === selectedCategory ? 'selected' : ''}>${label}</option>`;
      }
      return options;
    };

    // Build product options HTML filtered by category
    const buildProductOptions = (category = '', selectedProductId = '') => {
      let options = '<option value="">Select Product</option>';
      if (category) {
        const filteredProducts = products.filter(p => p.category === category);
        filteredProducts.forEach(product => {
          const isSelected = selectedProductId && product.id == selectedProductId ? 'selected' : '';
          options += `<option value="${product.id}" data-price="${product.price}" data-name="${product.name}" ${isSelected}>${product.name}</option>`;
        });
      }
      return options;
    };

    // Cup size pricing
    const sizePrices = {
      'Small': 0,
      'Medium': 10.00,
      'Large': 20.00
    };

    const calculateItemPrice = (basePrice, size) => {
      const base = parseFloat(basePrice) || 0;
      const sizePremium = sizePrices[size] || 0;
      return base + sizePremium;
    };

    const addItemRow = (item = {category: '', product_id: '', product_name: '', price: '', quantity: 1, size: 'Small'}) => {
      const index = itemsBody.children.length;
      const basePrice = item.base_price || '';
      const size = item.size || 'Small';
      const finalPrice = basePrice ? calculateItemPrice(basePrice, size) : '';
      
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>
          <select name="items[${index}][category]" class="form-select form-select-sm item-category" data-index="${index}">
            ${buildCategoryOptions(item.category ?? '')}
          </select>
        </td>
        <td>
          <select name="items[${index}][product_id]" class="form-select form-select-sm item-product" data-index="${index}" ${!item.category ? 'disabled' : ''}>
            ${buildProductOptions(item.category ?? '', item.product_id ?? '')}
          </select>
          <input type="hidden" name="items[${index}][name]" class="item-name" value="${item.product_name ?? ''}">
          <input type="hidden" name="items[${index}][base_price]" class="item-base-price" value="${basePrice}">
        </td>
        <td>
          <select name="items[${index}][size]" class="form-select form-select-sm item-size" data-index="${index}">
            <option value="Small" ${size === 'Small' ? 'selected' : ''}>Small</option>
            <option value="Medium" ${size === 'Medium' ? 'selected' : ''}>Medium (+₱10)</option>
            <option value="Large" ${size === 'Large' ? 'selected' : ''}>Large (+₱20)</option>
          </select>
        </td>
        <td>
          <input type="number" step="0.01" min="0" name="items[${index}][price]" class="form-control form-control-sm item-price" value="${finalPrice}" readonly>
        </td>
        <td>
          <input type="number" min="1" name="items[${index}][quantity]" class="form-control form-control-sm item-qty" value="${item.quantity ?? 1}">
        </td>
        <td class="text-center">
          <button type="button" class="btn btn-sm btn-outline-danger remove-item">&times;</button>
        </td>
      `;
      itemsBody.appendChild(row);
      
      // Attach event listeners
      const categorySelect = row.querySelector('.item-category');
      const productSelect = row.querySelector('.item-product');
      const sizeSelect = row.querySelector('.item-size');
      const priceInput = row.querySelector('.item-price');
      const basePriceInput = row.querySelector('.item-base-price');
      const nameInput = row.querySelector('.item-name');
      const qtyInput = row.querySelector('.item-qty');
      
      const updatePrice = () => {
        const basePrice = parseFloat(basePriceInput.value) || 0;
        const size = sizeSelect.value;
        const finalPrice = calculateItemPrice(basePrice, size);
        priceInput.value = basePrice > 0 ? finalPrice.toFixed(2) : '';
        recalcTotal();
      };
      
      categorySelect.addEventListener('change', function() {
        const category = this.value;
        if (category) {
          productSelect.innerHTML = buildProductOptions(category);
          productSelect.disabled = false;
        } else {
          productSelect.innerHTML = '<option value="">Select Product</option>';
          productSelect.disabled = true;
          basePriceInput.value = '';
          priceInput.value = '';
          nameInput.value = '';
        }
        recalcTotal();
      });
      
      productSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (option.value) {
          const basePrice = option.dataset.price || '';
          basePriceInput.value = basePrice;
          nameInput.value = option.dataset.name || '';
          updatePrice();
        } else {
          basePriceInput.value = '';
          priceInput.value = '';
          nameInput.value = '';
        }
        recalcTotal();
      });
      
      sizeSelect.addEventListener('change', updatePrice);
      qtyInput.addEventListener('input', recalcTotal);
    };

    const recalcTotal = () => {
      let total = 0;
      itemsBody.querySelectorAll('tr').forEach(row => {
        const price = parseFloat(row.querySelector('.item-price')?.value || 0);
        const qty = parseInt(row.querySelector('.item-qty')?.value || 0, 10);
        total += price * qty;
      });
      totalField.value = `₱${total.toFixed(2)}`;
    };

    const resetForm = () => {
      orderForm.reset();
      itemsBody.innerHTML = '';
      addItemRow();
      totalField.value = '₱0.00';
      orderForm.setAttribute('action', defaultAction);
      orderForm.querySelector('.modal-title').textContent = 'Create Order';
    };

    const populateForm = (order) => {
      orderForm.setAttribute('action', `${defaultAction}/${order.id}`);
      orderForm.querySelector('.modal-title').textContent = `Edit Order #${order.id}`;
      orderForm.customer_name.value = order.customer_name === 'Walk-in Customer' ? '' : order.customer_name;
      orderForm.status.value = order.status;

      itemsBody.innerHTML = '';
      const items = Array.isArray(order.items) ? order.items : JSON.parse(order.items || '[]');
      if (items.length === 0) {
        addItemRow();
      } else {
        // For existing orders, try to match products by name to get category and product_id
        items.forEach(item => {
          let itemName = item.name || '';
          let size = 'Small';
          
          // Extract size from name if present (format: "Product Name (Size)")
          const sizeMatch = itemName.match(/^(.+?)\s*\((Small|Medium|Large)\)$/);
          if (sizeMatch) {
            itemName = sizeMatch[1].trim();
            size = sizeMatch[2];
          } else if (item.size) {
            size = item.size;
          }
          
          const product = products.find(p => p.name === itemName);
          const orderPrice = parseFloat(item.price) || 0;
          
          // Calculate base price from order price and size
          let basePrice = product ? parseFloat(product.price) : orderPrice;
          if (product && size !== 'Small') {
            const sizePremium = size === 'Medium' ? 10 : size === 'Large' ? 20 : 0;
            basePrice = orderPrice - sizePremium;
            // Ensure base price is not negative
            if (basePrice < product.price) {
              basePrice = parseFloat(product.price);
            }
          }
          
          const itemData = {
            category: product ? product.category : '',
            product_id: product ? product.id : '',
            product_name: itemName,
            base_price: basePrice,
            price: orderPrice,
            quantity: item.quantity,
            size: size
          };
          addItemRow(itemData);
        });
      }
      recalcTotal();
    };

    const fillViewModal = (order) => {
      const items = Array.isArray(order.items) ? order.items : JSON.parse(order.items || '[]');
      document.getElementById('viewCustomer').textContent = order.customer_name;
      const badge = document.getElementById('viewStatus');
      badge.textContent = order.status;
      badge.className = `badge ${statusBadgeClass[order.status] || 'bg-secondary'}`;
      document.getElementById('viewDate').textContent = new Date(order.order_date).toLocaleString();
      const list = document.getElementById('viewItems');
      list.innerHTML = '';
      if (items.length === 0) {
        const li = document.createElement('li');
        li.textContent = 'No items listed';
        list.appendChild(li);
      } else {
        items.forEach(item => {
          const li = document.createElement('li');
          li.textContent = `${item.name} — ${item.quantity || 1} x ₱${parseFloat(item.price).toFixed(2)}`;
          list.appendChild(li);
        });
      }
      document.getElementById('viewTotal').textContent = `₱${parseFloat(order.total).toFixed(2)}`;
      // prepare edit button
      const editBtn = viewModalElement.querySelector('.order-modal-trigger');
      editBtn.setAttribute('data-order', JSON.stringify(order));
    };

    document.getElementById('addItemBtn').addEventListener('click', () => {
      addItemRow();
    });

    itemsBody.addEventListener('input', (event) => {
      if (event.target.classList.contains('item-qty')) {
        recalcTotal();
      }
    });

    itemsBody.addEventListener('click', (event) => {
      if (event.target.classList.contains('remove-item')) {
        event.target.closest('tr').remove();
        recalcTotal();
        if (itemsBody.children.length === 0) {
          addItemRow();
        }
      }
    });

    const orderModalInstance = new bootstrap.Modal(modalElement);

    modalElement.addEventListener('show.bs.modal', event => {
      const button = event.relatedTarget;
      if (!button) {
        resetForm();
        return;
      }
      const payload = button.getAttribute('data-order');
      if (!payload) {
        resetForm();
        return;
      }
      const order = JSON.parse(payload);
      populateForm(order);
    });

    modalElement.addEventListener('hidden.bs.modal', resetForm);

    viewModalElement.addEventListener('show.bs.modal', event => {
      const button = event.relatedTarget;
      if (!button) {
        return;
      }
      const payload = button.getAttribute('data-order');
      if (!payload) {
        return;
      }
      const order = JSON.parse(payload);
      fillViewModal(order);
    });
  </script>
</body>
</html>

<style>
body {
  background-color: #f3e5d8;
  font-family: 'Poppins', sans-serif;
  margin: 0;
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

.content { margin-left: 250px; padding: 30px; }

.card-custom {
  border-radius: 15px;
  border: 1px solid #d8bfa7;
  background-color: #fff7ef;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.title { color: #5a3825; font-weight: 700; }

.btn-primary-custom {
  background-color: #8b5e3c;
  border: none;
}

.badge { font-size: 0.9rem; }

.table-responsive {
  max-height: 500px;
}

.modal .table td {
  vertical-align: middle;
}
</style>

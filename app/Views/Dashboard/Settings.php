<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />


</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
    <h2>Welcome, <?= session()->get('username')?>☕</h2>
            <a href="<?= base_url('dashboard') ?>">Dashboard</a>
        <a href="<?= base_url('orders') ?>">Orders</a>
        <a href="<?= base_url('product') ?>">Products</a>
        <a href="<?= base_url('expenses') ?>">Expenses</a>
        <a href="<?= base_url('reports') ?>">Reports</a>
        <a href="<?= base_url('settings') ?>" class="active">Settings</a>
        <a href="<?= base_url('logout') ?>">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">

        <!-- Page Header -->
        <div class="page-header d-flex flex-column flex-md-row justify-content-md-between align-items-md-center gap-2 mb-4">
            <div>
                <h2 class="title mb-1">Coffee Shop Settings</h2>
                <p class="text-muted mb-0">Keep your store details, account info, and team organized.</p>
            </div>
            <span class="badge bg-secondary px-3 py-2">
                Last updated: <?= esc($settings['updated_at'] ?? '—') ?>
            </span>
        </div>

        <!-- Feedback Messages -->
        <?php if ($flash = session()->getFlashdata('success')): ?>
            <div class="alert alert-success mt-3"><?= esc($flash) ?></div>
        <?php endif; ?>
        <?php if ($flash = session()->getFlashdata('error')): ?>
            <div class="alert alert-danger mt-3"><?= esc($flash) ?></div>
        <?php endif; ?>
        <?php if (! empty($accountSuccess ?? null)): ?>
            <div class="alert alert-success mt-3"><?= esc($accountSuccess) ?></div>
        <?php endif; ?>
        <?php if (! empty($userFormSuccess ?? null)): ?>
            <div class="alert alert-success mt-3"><?= esc($userFormSuccess) ?></div>
        <?php endif; ?>
        <?php if (isset($validation) && $validation->getErrors()): ?>
            <div class="alert alert-danger mt-3">Please fix the highlighted fields below.</div>
        <?php endif; ?>

        <!-- ===================== GRID: 2x2 Boxes ===================== -->
        <div class="row g-4">

            <!-- Shop Settings -->
            <div class="col-md-6">
                <div class="card-custom p-4 h-100">
                    <h4 class="title mb-3">Shop Settings</h4>
                    <form method="post" action="<?= base_url('settings') ?>">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label" for="shopName">Shop Name</label>
                            <input type="text" class="form-control <?= isset($validation) && $validation->hasError('shop_name') ? 'is-invalid' : '' ?>" id="shopName" name="shop_name" value="<?= old('shop_name', $settings['shop_name'] ?? '') ?>" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="shopAddress">Shop Address</label>
                            <input type="text" class="form-control <?= isset($validation) && $validation->hasError('shop_address') ? 'is-invalid' : '' ?>" id="shopAddress" name="shop_address" value="<?= old('shop_address', $settings['shop_address'] ?? '') ?>" />
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="contactNumber">Contact Number</label>
                                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('contact_number') ? 'is-invalid' : '' ?>" id="contactNumber" name="contact_number" value="<?= old('contact_number', $settings['contact_number'] ?? '') ?>" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="openHours">Opening Hours</label>
                                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('opening_hours') ? 'is-invalid' : '' ?>" id="openHours" name="opening_hours" value="<?= old('opening_hours', $settings['opening_hours'] ?? '') ?>" />
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="form-label" for="defaultTax">Default Tax (%)</label>
                            <input type="number" step="0.01" class="form-control <?= isset($validation) && $validation->hasError('default_tax') ? 'is-invalid' : '' ?>" id="defaultTax" name="default_tax" value="<?= old('default_tax', $settings['default_tax'] ?? '') ?>" />
                        </div>
                        <button type="submit" class="btn btn-brown mt-4 w-100">Save Settings</button>
                    </form>
                </div>
            </div>

            <!-- Account Information -->
            <div class="col-md-6">
                <div class="card-custom p-4 h-100">
                    <h4 class="title mb-3">Account Information</h4>
                    <form method="post" action="<?= base_url('settings/account') ?>">
                        <?= csrf_field() ?>
                        <?php if (! empty($accountErrors)): ?>
                            <div class="alert alert-danger">Please resolve the highlighted account fields.</div>
                        <?php endif; ?>
                        <div class="mb-3">
                            <label class="form-label" for="accountUsername">Username</label>
                            <input type="text" class="form-control <?= isset($accountErrors['account_username']) ? 'is-invalid' : '' ?>" id="accountUsername" name="account_username" value="<?= old('account_username', $currentUser['username'] ?? '') ?>" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="accountEmail">Email</label>
                            <input type="email" class="form-control <?= isset($accountErrors['account_email']) ? 'is-invalid' : '' ?>" id="accountEmail" name="account_email" value="<?= old('account_email', $currentUser['email'] ?? '') ?>" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="accountPassword">New Password <small>(optional)</small></label>
                            <input type="password" class="form-control <?= isset($accountErrors['account_password']) ? 'is-invalid' : '' ?>" id="accountPassword" name="account_password" />
                        </div>
                        <button type="submit" class="btn btn-brown w-100">Update Account</button>
                    </form>
                </div>
            </div>

            <!-- Add New User -->
            <div class="col-md-6">
                <div class="card-custom p-4 h-100">
                    <h4 class="title mb-3">Add New User</h4>
                    <form method="post" action="<?= base_url('settings/users') ?>">
                        <?= csrf_field() ?>
                        <?php if (! empty($userErrors)): ?>
                            <div class="alert alert-danger">Please correct the user details below.</div>
                        <?php endif; ?>
                        <div class="mb-3">
                            <label class="form-label" for="newUsername">Username</label>
                            <input type="text" class="form-control <?= isset($userErrors['new_username']) ? 'is-invalid' : '' ?>" id="newUsername" name="new_username" value="<?= esc(old('new_username')) ?>" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="newEmail">Email</label>
                            <input type="email" class="form-control <?= isset($userErrors['new_email']) ? 'is-invalid' : '' ?>" id="newEmail" name="new_email" value="<?= esc(old('new_email')) ?>" />
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="newPassword">Password</label>
                                <input type="password" class="form-control <?= isset($userErrors['new_password']) ? 'is-invalid' : '' ?>" id="newPassword" name="new_password" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="newRole">Role</label>
                                <select class="form-control <?= isset($userErrors['new_role']) ? 'is-invalid' : '' ?>" id="newRole" name="new_role">
                                    <option value="admin" <?= old('new_role') === 'admin' ? 'selected' : '' ?>>Admin</option>
                                    <option value="staff" <?= old('new_role', 'staff') === 'staff' ? 'selected' : '' ?>>Staff</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-brown mt-3 w-100">Add User</button>
                    </form>
                </div>
            </div>

            <!-- User Management -->
            <div class="col-md-6">
                <div class="card-custom p-4 h-100">
                    <h4 class="title mb-3">User Management</h4>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (! empty($users)): ?>
                                    <?php foreach ($users as $index => $user): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= esc($user['username']) ?></td>
                                            <td><?= esc($user['email']) ?></td>
                                            <td><span class="badge bg-secondary text-uppercase"><?= esc($user['role']) ?></span></td>
                                            <td><?= esc(date('M d, Y', strtotime($user['created_at'] ?? 'now'))) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No users found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div> <!-- End Grid -->

    </div> <!-- End Content -->

</body>
</html>
<style>
        /* --- General Styles --- */
        body {
            background-color: #f3e5d8;
            font-family: 'Poppins', sans-serif;
        }

        /* --- Sidebar --- */
        .sidebar {
        width: 250px;
        height: 100vh;
        background-color: #8b5e3c;
        padding: 20px;
        position: fixed;
        top: 0; left: 0; color: white;
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
        /* --- Main Content --- */
        .content {
            margin-left: 270px;
            padding: 30px;
        }

        /* --- Cards --- */
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

        .form-label {
            color: #5a3825;
            font-weight: 500;
        }

        .btn-brown {
            background-color: #8b5e3c;
            color: white;
        }

        .btn-brown:hover {
            background-color: #6e462c;
        }

        /* --- Tables --- */
        .table thead th {
            background-color: #f3e5d8;
            color: #5a3825;
        }
    </style>
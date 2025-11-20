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
        <?php if (! empty($userManagementNotice ?? null)): ?>
            <div class="alert alert-success mt-3"><?= esc($userManagementNotice) ?></div>
        <?php endif; ?>
        <?php if (! empty($userManagementError ?? null)): ?>
            <div class="alert alert-danger mt-3"><?= esc($userManagementError) ?></div>
        <?php endif; ?>
        <?php if (isset($validation) && $validation->getErrors()): ?>
            <div class="alert alert-danger mt-3">Please fix the highlighted fields below.</div>
        <?php endif; ?>

        <!-- Add New User Section -->
        <div class="row g-4">
            <div class="col-12">
                <div class="card-custom p-4">
                    <h4 class="title mb-3">Add New User</h4>
                    <form method="post" action="<?= base_url('settings/users') ?>">
                        <?= csrf_field() ?>
                        <?php if (! empty($userErrors)): ?>
                            <div class="alert alert-danger">Please correct the user details below.</div>
                        <?php endif; ?>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="newUsername">Username</label>
                                <input type="text" class="form-control <?= isset($userErrors['new_username']) ? 'is-invalid' : '' ?>" id="newUsername" name="new_username" value="<?= esc(old('new_username')) ?>" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="newEmail">Email</label>
                                <input type="email" class="form-control <?= isset($userErrors['new_email']) ? 'is-invalid' : '' ?>" id="newEmail" name="new_email" value="<?= esc(old('new_email')) ?>" />
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="newPassword">Password</label>
                                <input type="password" class="form-control <?= isset($userErrors['new_password']) ? 'is-invalid' : '' ?>" id="newPassword" name="new_password" />
                            </div>
                            <div class="col-md-2">
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
        </div>

        <!-- User Management Section -->
        <div class="row g-4 mt-1">
            <div class="col-12">
                <div class="card-custom p-4">
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
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (! empty($users)): ?>
                                    <?php foreach ($users as $index => $user): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= esc($user['username']) ?></td>
                                            <td><?= esc($user['email']) ?></td>
                                            <td>
                                                <form method="post" action="<?= base_url('settings/users/' . $user['id'] . '/role') ?>" class="d-flex gap-2 align-items-center">
                                                    <?= csrf_field() ?>
                                                    <select name="role" class="form-select form-select-sm" <?= (int) $user['id'] === (int) session()->get('user_id') ? 'disabled' : '' ?>>
                                                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                                        <option value="staff" <?= $user['role'] === 'staff' ? 'selected' : '' ?>>Staff</option>
                                                    </select>
                                                    <button type="submit" class="btn btn-sm btn-outline-primary" <?= (int) $user['id'] === (int) session()->get('user_id') ? 'disabled' : '' ?>>Save</button>
                                                </form>
                                            </td>
                                            <td><?= esc(date('M d, Y', strtotime($user['created_at'] ?? 'now'))) ?></td>
                                            <td class="text-end">
                                                <div class="d-flex gap-2 justify-content-end flex-wrap">
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-secondary user-edit-trigger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editUserModal"
                                                            data-user='<?= json_encode([
                                                                'id'       => $user['id'],
                                                                'username' => $user['username'],
                                                                'email'    => $user['email'],
                                                            ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>'>
                                                        Edit Info
                                                    </button>
                                                    <form method="post" action="<?= base_url('settings/users/' . $user['id'] . '/delete') ?>" onsubmit="return confirm('Delete this user?');">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" <?= (int) $user['id'] === (int) session()->get('user_id') ? 'disabled' : '' ?>>Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No users found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div> <!-- End Content -->

</body>
</html>
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="editUserForm" action="">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="edit_username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="edit_email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password <small>(leave blank to keep current)</small></label>
                        <input type="password" class="form-control" name="edit_password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brown">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const editUserModal = document.getElementById('editUserModal');
    const editUserForm = document.getElementById('editUserForm');
    const defaultAction = '<?= base_url('settings/users') ?>';

    editUserModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        if (!button) return;
        const payload = button.getAttribute('data-user');
        if (!payload) return;

        const user = JSON.parse(payload);
        editUserForm.setAttribute('action', `${defaultAction}/${user.id}/profile`);
        editUserForm.edit_username.value = user.username;
        editUserForm.edit_email.value = user.email;
        editUserForm.edit_password.value = '';
    });

    editUserModal.addEventListener('hidden.bs.modal', () => {
        editUserForm.reset();
        editUserForm.setAttribute('action', defaultAction);
    });
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <div class="login-card">

        <img src="https://cdn-icons-png.flaticon.com/512/924/924514.png" class="coffee-icon" />
        <h3 class="login-title">Coffee Shop Login</h3>

        <form method="post" action="<?= base_url('login') ?>">
            <?= csrf_field() ?>

            <!-- 🔥 ERROR MESSAGE HERE (Above Email field) -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger text-center mb-3" role="alert">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <label for="login_identity" class="form-label">Email or Username</label>
                <input type="text" class="form-control" id="login_identity" name="login_identity" placeholder="Enter email or username" required />
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required />
            </div>

            <button type="submit" class="btn btn-brown w-100 mt-2">Login</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<style>
    body {
        background-color: #f3e5d8;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: 'Poppins', sans-serif;
    }
    .login-card {
        background-color: #fff7ef;
        border-radius: 20px;
        padding: 40px;
        width: 100%;
        max-width: 430px;
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        border: 2px solid #8b5e3c;
    }
    .login-title {
        color: #5a3825;
        font-weight: 700;
        text-align: center;
        margin-bottom: 25px;
        font-size: 1.7rem;
    }
    .btn-brown {
        background-color: #8b5e3c;
        color: white;
    }
    .btn-brown:hover {
        background-color: #6e462c;
        color: white;
    }
    label {
        color: #5a3825;
        font-weight: 500;
    }
    .coffee-icon {
        width: 70px;
        display: block;
        margin: 0 auto 15px auto;
    }
</style>

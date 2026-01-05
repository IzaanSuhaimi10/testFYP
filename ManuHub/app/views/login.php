<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ManuHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .login-card { max-width: 400px; border: none; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .btn-primary { background-color: #6d0828; border: none; padding: 12px; font-weight: 600; transition: 0.3s; }
        .btn-primary:hover { background-color: #4d061c; transform: translateY(-1px); }
        .form-control:focus { border-color: #6d0828; box-shadow: 0 0 0 0.25 cold-rgba(109, 8, 40, 0.25); }
        .logo-img { width: 120px; border-radius: 12px; margin-bottom: 20px; }
    </style>
</head>
<body class="d-flex align-items-center min-vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card login-card p-4 p-md-5 mx-auto">
                    <div class="text-center">
                        <a href="/manuhub/public">
                            <img src="../assets/images/manuhub_logo.jpeg" alt="ManuHub Logo" class="logo-img">
                        </a>
                        <h2 class="h4 fw-bold mb-3">Welcome Back</h2>
                        <p class="text-muted small mb-4">Login to continue your research journey</p>
                    </div>

                   <?php if (isset($data['error'])): ?>
    <div class="alert alert-danger py-2 small text-center" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i>
        <?php echo $data['error']; ?>
    </div>
<?php endif; ?>

<?php if (isset($data['success'])): ?>
    <div class="alert alert-success py-2 small text-center" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        <?php echo $data['success']; ?>
    </div>
<?php endif; ?>

                    <form name="loginForm" method="POST" action="/manuhub/public/index.php?action=login">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted">Email Address</label>
                            <input type="email" name="email" class="form-control" required placeholder="name@example.com">
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-muted">Password</label>
                            <input type="password" name="password" class="form-control" required placeholder="••••••••">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>
                    </form>

                    <div class="text-center">
                        <hr class="text-muted opacity-25">
                        <p class="small text-muted mb-0">Don't have an account? <a href="/manuhub/public/index.php?action=register" class="text-decoration-none fw-bold" style="color: #6d0828;">Register Now</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
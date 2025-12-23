<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../public/login.css">
</head>
<body>
    <div class="login-container">
        <a href="/manuhub/public">
            <img src="../assets/images/manuhub_logo.jpeg" alt="ManuHub Logo">
        </a>

        <h1>Login to Continue You Research Journey</h1>
        
        <?php if (isset($data['error'])): ?>
            <p style="color:red;"><?php echo $data['error']; ?></p>
        <?php endif; ?>

        <form name="loginForm" method="POST" action="/manuhub/public/index.php?action=login">
            <div class="input-container">
                <input type="email" name="email" required placeholder="Email"><br>
            </div>

            <div class="input-container">
                <input type="password" name="password" required placeholder="Password"><br>
            </div>

            <button type="submit">Login</button>
        </form>

        <hr>

        <p>Don't have an account? <a href="/manuhub/public/index.php?action=register">Register Now!</a></p>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../public/register.css">

    <script>
        // Client-side validation for the form
        function validateForm() {
            const username = document.forms["registerForm"]["username"].value;
            const email = document.forms["registerForm"]["email"].value;
            const password = document.forms["registerForm"]["password"].value;
            const confirmPassword = document.forms["registerForm"]["confirm_password"].value;

            // 1. Validate username (should not contain < or > or any tags)
            const usernameRegex = /<.*?>/;
            if (usernameRegex.test(username)) {
                alert("Username cannot contain < or > characters.");
                return false;
            }

            // 2. Validate email (basic format check)
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }

            // 3. Validate password (between 8 and 16 characters long and contains special characters)
            const passwordLengthRegex = /^.{8,16}$/; // Between 8 to 16 characters
            const passwordSpecialCharRegex = /^(?=.*[!@#$%^&*(),.?":{}|<>]).*$/; // Contains at least one special character

            if (!passwordLengthRegex.test(password)) {
                alert("Password must be between 8 and 16 characters long.");
                return false;
            }

            if (!passwordSpecialCharRegex.test(password)) {
                alert("Password must contain at least one special character.");
                return false;
            }

            // 4. Validate confirm password (must match the password)
            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }

            return true;  // If all checks pass
        }
    </script>
</head>
<body>
    <div class="register-container">

        <a href="/manuhub/public">
            <img src="../assets/images/manuhub_logo.jpeg" alt="ManuHub Logo">
        </a>

        <h1>Get Started Now</h1>
        
        <?php if (isset($data['error'])): ?>
            <p style="color:red;"><?php echo $data['error']; ?></p>
        <?php endif; ?>

        <form name="registerForm" method="POST" action="/manuhub/public/index.php?action=register" onsubmit="return validateForm()">
            <div class="input-container">
                <input type="email" name="email" required placeholder="Email"><br>
            </div>

            <div class="input-container">
                <input type="text" name="username" required placeholder="Username"><br>               
            </div>

            <div class="input-container">
                <input type="password" name="password" required placeholder="Password"><br>
            </div>

            <div class="input-container">
                <input type="password" name="confirm_password" required placeholder="Confirm Password"><br>
            </div>

            <button type="submit">Register</button>
        </form>

        <hr>

        <p>Already have an account? <a href="/manuhub/public/index.php?action=login">Log in Now!</a></p>
    </div>
</body>
</html>

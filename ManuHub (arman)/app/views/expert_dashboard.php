<?php
session_start();

// Check if the logged-in user is an expert
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'expert') {
    // Redirect to login page if not an expert
    header("Location: /manuhub/public/index.php?action=login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expert Dashboard</title>
    <style>
        /* Simple styling to make it look decent */
        body { font-family: sans-serif; padding: 20px; }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            margin-right: 10px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
        }
        .btn-home { background-color: #28a745; } /* Green */
        .btn-logout { background-color: #dc3545; } /* Red */
    </style>
</head>
<body>

    <h1>Welcome to the Expert Dashboard</h1>
    <p>You are logged in as: <strong><?php echo $_SESSION['username'] ?? 'Expert'; ?></strong></p>
    <p>Expert-specific content goes here.</p>

    <hr>

    <a href="/manuhub/public/index.php" class="btn btn-home">
        🏠 Go to Homepage (Use Scraper)
    </a>

    <a href="/manuhub/public/index.php?action=logout" class="btn btn-logout">
        Logout
    </a>

</body>
</html>
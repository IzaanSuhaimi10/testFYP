<?php

include('header.php'); 

session_start();

// Check if the logged-in user is an admin
if ($_SESSION['role'] !== 'admin') {
    // Redirect to login page if not an admin
    header("Location: /manuhub/public/index.php?action=login");
    exit();
}

echo "<h1>Welcome to the Admin Dashboard</h1>";
echo "<p>Admin-specific content goes here.</p>";

?>

<?php
session_start();

// Check if the logged-in user is an expert
if ($_SESSION['role'] !== 'expert') {
    // Redirect to login page if not an expert
    header("Location: /manuhub/public/index.php?action=login");
    exit();
}

echo "<h1>Welcome to the Expert Dashboard</h1>";
echo "<p>Expert-specific content goes here.</p>";
?>

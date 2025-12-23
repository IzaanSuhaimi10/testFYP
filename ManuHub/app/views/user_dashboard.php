<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>
    <?php include('header.php');?>

    <?php
    session_start();

    // Check if the logged-in user is a normal user
    if ($_SESSION['role'] !== 'normal_user') {
        // Redirect to login page if not a normal user
        header("Location: /manuhub/public/index.php?action=login");
        exit();
    }

    echo "<h1>Welcome to the Normal User Dashboard</h1>";
    echo "<p>Normal user-specific content goes here.</p>";
    ?>
    
    <?php include('footer.php'); ?>
   
</body>
</html>

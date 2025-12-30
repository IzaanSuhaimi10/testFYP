<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Expert</title>
</head>
<body>
    <h1>Add Expert</h1>
    
    <?php if (isset($data['error'])): ?>
        <p style="color:red;"><?php echo $data['error']; ?></p>
    <?php endif; ?>

    <form method="POST" action="/manuhub/public/index.php?action=add_expert">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" required><br>

        <button type="submit">Add Expert</button>
    </form>
</body>
</html>
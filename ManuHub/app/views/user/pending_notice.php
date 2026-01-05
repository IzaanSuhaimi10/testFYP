<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Pending - ManuHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { 
            font-family: 'Inter', sans-serif; background-color: #f8f9fa; 
            display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; 
        }
        .notice-card {
            background: white; padding: 40px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            text-align: center; max-width: 450px; border-top: 5px solid #f39c12;
        }
        .icon { font-size: 50px; color: #f39c12; margin-bottom: 20px; }
        h1 { font-size: 22px; color: #333; margin-bottom: 10px; }
        p { color: #666; line-height: 1.6; margin-bottom: 25px; }
        .btn-home { 
            background: #6d0828; color: white; padding: 12px 25px; border-radius: 50px; 
            text-decoration: none; font-weight: 600; display: inline-block;
        }
    </style>
</head>
<body>
    <div class="notice-card">
        <div class="icon"><i class="bi bi-hourglass-split"></i></div>
        <h1>Account Under Review</h1>
        <p>Thank you for joining ManuHub! To ensure the integrity of our research repository, an expert is currently verifying your identity document. You will gain full access once approved.</p>
        <a href="index.php?action=logout" class="btn-home">Back to Login</a>
    </div>
</body>
</html>
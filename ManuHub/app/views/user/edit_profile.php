<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - ManuHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        /* --- GLOBAL RESET (UNIFIED STYLE) --- */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { display: flex; height: 100vh; background-color: #f8f9fa; overflow: hidden; font-size: 14px; }
        
        /* SIDEBAR */
        .sidebar { width: 250px; min-width: 250px; background-color: #fff; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; padding: 25px; }
        .brand { font-size: 18px; font-weight: 800; margin-bottom: 40px; color: #333; letter-spacing: 0.5px; display: flex; align-items: center; gap: 10px; }
        .brand-logo { width: 32px; height: 32px; background: #e67e22; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 16px;}

        .menu-item { text-decoration: none; color: #666; padding: 12px 15px; margin-bottom: 8px; border-radius: 8px; font-size: 14px; transition: all 0.2s; display: flex; align-items: center; gap: 12px; font-weight: 500; }
        .menu-item:hover { background-color: #fff7ed; color: #e67e22; }
        .menu-item.active { background-color: #ffedd5; color: #c2410c; font-weight: 700; }
        .menu-item i { font-size: 18px; }

        /* MAIN CONTENT */
        .main-content { flex: 1; padding: 30px; overflow-y: auto; background-color: #f8f9fa; display: flex; flex-direction: column; gap: 25px; }

        /* PROFILE BANNER */
        .profile-banner {
            background: white; border-radius: 16px; padding: 25px;
            display: flex; align-items: center; gap: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #eee;
        }
        .avatar-large { 
            width: 70px; height: 70px; background: linear-gradient(135deg, #e67e22, #d35400); 
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 28px; font-weight: bold; color: white;
            box-shadow: 0 4px 10px rgba(230, 126, 34, 0.3);
        }

        /* LAYOUT GRID */
        .settings-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }
        @media (max-width: 900px) { .settings-grid { grid-template-columns: 1fr; } }

        /* CARDS */
        .card {
            background: white; border-radius: 16px; padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid #eee;
        }
        .card-header { margin-bottom: 25px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        .card-title { font-size: 16px; font-weight: 700; color: #333; display: flex; align-items: center; gap: 10px; }
        .card-icon { color: #e67e22; font-size: 18px; }

        /* FORM ELEMENTS */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 8px; }
        .form-input {
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: #fdfdfd; transition: 0.2s;
        }
        .form-input:focus { border-color: #e67e22; background: white; outline: none; box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.1); }

        .btn-save {
            background: #e67e22; color: white; border: none; padding: 12px 25px;
            border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px;
            transition: 0.2s; display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-save:hover { background: #d35400; transform: translateY(-1px); }

        /* ALERTS */
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand">
            <div class="brand-logo"><i class="bi bi-book-half"></i></div>
            MANUHUB
        </div>
        
        <a href="index.php?action=user_dashboard" class="menu-item">
            <i class="bi bi-grid-fill"></i> Dashboard
        </a>
        <a href="index.php?action=submit_manuscript" class="menu-item">
            <i class="bi bi-plus-square-fill"></i> Submit New
        </a>
        <a href="index.php?action=user_edit_profile" class="menu-item active">
            <i class="bi bi-person-circle"></i> Edit Profile
        </a>
        <a href="index.php" class="menu-item">
            <i class="bi bi-house"></i> Home Page
        </a>
        
        <a href="index.php?action=logout" class="menu-item" style="margin-top: auto; color: #c0392b;">
            <i class="bi bi-box-arrow-left"></i> Logout
        </a>
    </div>

    <div class="main-content">
        
        <div class="profile-banner">
            <div class="profile-left">
                <div class="avatar-large">
                    <?php echo strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)); ?>
                </div>
                <div>
                    <h2 style="font-size: 20px; font-weight: 700; color: #333;">Account Settings</h2>
                    <p style="color: #777; font-size: 13px;">Manage your personal information and security.</p>
                </div>
            </div>
        </div>

        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill"></i> Profile updated successfully!
            </div>
        <?php endif; ?>
        <?php if (isset($data['error'])): ?>
            <div class="alert alert-error">
                <i class="bi bi-exclamation-triangle-fill"></i> <?php echo $data['error']; ?>
            </div>
        <?php endif; ?>

        <form action="index.php?action=user_edit_profile" method="POST">
            <div class="settings-grid">
                
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="bi bi-person-lines-fill card-icon"></i> General Information
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-input" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-input" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" required>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="bi bi-shield-lock card-icon"></i> Change Password
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-input" placeholder="Leave blank to keep current">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-input" placeholder="Re-enter new password">
                    </div>

                    <div style="margin-top: 30px; text-align: right;">
                        <button type="submit" class="btn-save">
                            <i class="bi bi-save"></i> Update Profile
                        </button>
                    </div>
                </div>

            </div>
        </form>

    </div>

</body>
</html>
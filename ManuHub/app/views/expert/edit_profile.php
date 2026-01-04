<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - ManuHub Expert</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        /* --- GLOBAL RESET (MATCHING DASHBOARD) --- */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { display: flex; height: 100vh; background-color: #f8f9fa; overflow: hidden; font-size: 14px; }
        
        /* SIDEBAR */
        .sidebar { width: 250px; min-width: 250px; background-color: #fff; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; padding: 25px; }
        .brand { font-size: 18px; font-weight: 800; margin-bottom: 40px; color: #333; letter-spacing: 0.5px; display: flex; align-items: center; gap: 10px; }
        .brand-logo { width: 32px; height: 32px; background: #26c6da; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 16px;}

        .menu-item { text-decoration: none; color: #666; padding: 12px 15px; margin-bottom: 8px; border-radius: 8px; font-size: 14px; transition: all 0.2s; display: flex; align-items: center; gap: 12px; font-weight: 500; }
        .menu-item:hover { background-color: #f0fbfc; color: #00838f; }
        .menu-item.active { background-color: #e0f7fa; color: #006064; font-weight: 700; }
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
            width: 70px; height: 70px; background: linear-gradient(135deg, #26c6da, #00acc1); 
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 28px; font-weight: bold; color: white;
            box-shadow: 0 4px 10px rgba(38, 198, 218, 0.4);
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
        .card-icon { color: #26c6da; font-size: 18px; }

        /* FORM ELEMENTS */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 8px; }
        .form-input {
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: #fdfdfd; transition: 0.2s;
        }
        .form-input:focus { border-color: #26c6da; background: white; outline: none; box-shadow: 0 0 0 3px rgba(38, 198, 218, 0.1); }
        .form-input:disabled { background: #f0f0f0; color: #888; cursor: not-allowed; }

        .btn-save {
            background: #26c6da; color: white; border: none; padding: 12px 25px;
            border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px;
            transition: 0.2s; display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-save:hover { background: #00acc1; transform: translateY(-1px); }

        /* ALERTS */
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #e0f7fa; color: #006064; border: 1px solid #b2ebf2; }
        .alert-error { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }

    </style>
</head>
<body>

<div class="sidebar">
    <div class="brand">
        <div class="brand-logo"><i class="bi bi-journal-check"></i></div>
        MANUHUB
    </div>
    
    <?php $current_action = $_GET['action'] ?? ''; ?>

    <a href="index.php?action=expert_dashboard" 
       class="menu-item <?php echo ($current_action == 'expert_dashboard') ? 'active' : ''; ?>">
        <i class="bi bi-grid-fill"></i> Dashboard
    </a>

    <a href="index.php?action=expert_verification_manuscripts" 
       class="menu-item <?php echo ($current_action == 'expert_verification_manuscripts') ? 'active' : ''; ?>">
        <i class="bi bi-file-earmark-text"></i> Manuscripts
    </a>

    <a href="index.php?action=expert_verification_related" 
       class="menu-item <?php echo ($current_action == 'expert_verification_related') ? 'active' : ''; ?>">
        <i class="bi bi-link-45deg"></i> Sources
    </a>

    <a href="index.php?action=expert_verification_suggestions" 
       class="menu-item <?php echo ($current_action == 'expert_verification_suggestions') ? 'active' : ''; ?>">
        <i class="bi bi-pencil-square"></i> Suggestions
    </a>

    <a href="index.php?action=expert_verification_flags" 
       class="menu-item <?php echo ($current_action == 'expert_verification_flags') ? 'active' : ''; ?>">
        <i class="bi bi-flag-fill"></i> Flags
    </a>

    <a href="index.php" 
       class="menu-item <?php echo ($current_action == 'index' || $current_action == '') ? 'active' : ''; ?>">
        <i class="bi bi-house"></i> Home Page
    </a>

    <a href="index.php?action=logout" class="menu-item" style="margin-top: auto; color: #c0392b;">
        <i class="bi bi-box-arrow-left"></i> Logout
    </a>
</div>

    <div class="main-content">
        
        <div class="profile-banner">
            <div class="avatar-large">
                <?php echo strtoupper(substr($data['user']['username'] ?? 'E', 0, 1)); ?>
            </div>
            <div>
                <h2 style="font-size: 20px; font-weight: 700; color: #333;">Profile Settings</h2>
                <p style="color: #777; font-size: 13px;">Manage your account details and security preferences.</p>
            </div>
        </div>

        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill"></i> Profile details updated successfully!
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['error']) && $_GET['error'] == 'pass_mismatch'): ?>
            <div class="alert alert-error">
                <i class="bi bi-exclamation-triangle-fill"></i> Passwords do not match. Please try again.
            </div>
        <?php endif; ?>

        <form action="index.php?action=expert_profile" method="POST">
            <div class="settings-grid">
                
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="bi bi-person-vcard card-icon"></i> General Information
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Display Name</label>
                        <input type="text" name="username" class="form-input" value="<?php echo htmlspecialchars($data['user']['username']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-input" value="<?php echo htmlspecialchars($data['user']['email']); ?>" disabled>
                        <div style="font-size: 11px; color: #999; margin-top: 5px;">
                            <i class="bi bi-lock-fill"></i> Email cannot be changed. Contact Admin for help.
                        </div>
                    </div>

                    </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="bi bi-shield-lock card-icon"></i> Security & Password
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-input" placeholder="Leave blank to keep current password">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-input" placeholder="Re-enter new password">
                    </div>

                    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: right;">
                        <button type="submit" class="btn-save">
                            <i class="bi bi-save"></i> Save Changes
                        </button>
                    </div>
                </div>

            </div>
        </form>

    </div>

</body>
</html>
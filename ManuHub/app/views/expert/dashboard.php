<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expert Dashboard - ManuHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        /* --- GLOBAL RESET --- */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { display: flex; height: 100vh; background-color: #f8f9fa; overflow: hidden; font-size: 14px; }
        
        /* --- SIDEBAR --- */
        .sidebar { width: 250px; min-width: 250px; background-color: #fff; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; padding: 25px; }
        .brand { font-size: 18px; font-weight: 800; margin-bottom: 40px; color: #333; letter-spacing: 0.5px; display: flex; align-items: center; gap: 10px; }
        .brand-logo { width: 32px; height: 32px; background: #26c6da; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 16px;}

        .menu-item { text-decoration: none; color: #666; padding: 12px 15px; margin-bottom: 8px; border-radius: 8px; font-size: 14px; transition: all 0.2s; display: flex; align-items: center; gap: 12px; font-weight: 500; }
        .menu-item:hover { background-color: #f0fbfc; color: #00838f; }
        .menu-item.active { background-color: #e0f7fa; color: #006064; font-weight: 700; }
        .menu-item i { font-size: 18px; }

        /* --- MAIN CONTENT --- */
        .main-content { flex: 1; padding: 30px; overflow-y: auto; background-color: #f8f9fa; display: flex; flex-direction: column; gap: 25px; }

        .page-header { display: flex; justify-content: space-between; align-items: center; }
        .page-title { font-size: 24px; font-weight: 700; color: #222; }

        /* --- PROFILE BANNER --- */
        .profile-banner {
            background: white;
            border-radius: 16px;
            padding: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            border: 1px solid #eee;
        }

        .profile-left { display: flex; align-items: center; gap: 20px; }
        .avatar-large { 
            width: 60px; height: 60px; 
            background: linear-gradient(135deg, #26c6da, #00acc1); 
            border-radius: 50%; 
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; font-weight: bold; color: white;
            box-shadow: 0 4px 10px rgba(38, 198, 218, 0.4);
        }
        .profile-text h2 { font-size: 18px; font-weight: 700; color: #333; margin-bottom: 4px; }
        .profile-text p { color: #777; font-size: 13px; margin: 0; }
        .role-badge { 
            background: #e0f7fa; color: #006064; 
            padding: 4px 10px; border-radius: 20px; 
            font-size: 11px; font-weight: 700; text-transform: uppercase; 
            margin-left: 10px; display: inline-block;
        }

        .btn-edit-header {
            text-decoration: none; color: #555; 
            border: 1px solid #ddd; padding: 10px 20px; 
            border-radius: 8px; font-weight: 600; font-size: 13px;
            transition: 0.2s;
        }
        .btn-edit-header:hover { border-color: #26c6da; color: #26c6da; background: #f0fdfe; }

        /* --- DASHBOARD GRID --- */
        .dashboard-grid {
            display: grid; 
            grid-template-columns: repeat(3, 1fr); 
            gap: 25px; 
        }

        @media (max-width: 1200px) { .dashboard-grid { grid-template-columns: 1fr; } }

        /* --- CARDS --- */
        .card {
            background-color: #D1E6E5; 
            border-radius: 16px; padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            display: flex; flex-direction: column; 
            position: relative; overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .card h3 { font-size: 14px; color: #555; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 15px; }
        .big-number { font-size: 42px; font-weight: 800; color: #333; margin-bottom: 20px; line-height: 1; }
        
        .dark-table-container {
            background-color: #2c3e50; border-radius: 10px;
            padding: 15px; color: white; margin-top: auto;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .dark-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .dark-table td { padding: 10px 0; border-bottom: 1px solid #3e5871; color: #ecf0f1; }
        .dark-table tr:last-child td { border-bottom: none; }
        
        .btn-action {
            width: 100%; margin-top: 15px; padding: 10px;
            background: #20878b; color: white; border: none; border-radius: 8px;
            font-weight: 600; cursor: pointer; text-transform: uppercase; font-size: 12px;
            transition: 0.2s;
        }
        .btn-action:hover { background: #1a6f72; }

        /* --- VERIFIED CARD --- */
        .card-verified {
            background: linear-gradient(135deg, #1abc9c, #16a085);
            color: white;
        }
        .card-verified h3 { color: rgba(255,255,255,0.8); }
        .card-verified .big-number { color: white; }
        
        .card-bg-icon {
            position: absolute; right: -20px; bottom: -20px;
            font-size: 150px; color: rgba(255,255,255,0.1);
            transform: rotate(-15deg); pointer-events: none;
        }

        /* NEW: Stat Boxes for Contribution Breakdown */
        .stat-box {
            flex: 1; 
            background: rgba(255,255,255,0.2); 
            padding: 15px; 
            border-radius: 12px; 
            text-align: center;
            backdrop-filter: blur(5px);
        }
        .stat-box-num { font-size: 24px; font-weight: 800; }
        .stat-box-label { font-size: 11px; opacity: 0.9; text-transform: uppercase; font-weight: 600; margin-top: 2px; }

    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand">
            <div class="brand-logo"><i class="bi bi-journal-check"></i></div>
            MANUHUB
        </div>
        
        <a href="index.php?action=expert_dashboard" class="menu-item active">
            <i class="bi bi-grid-fill"></i> Dashboard
        </a>
        <a href="index.php?action=expert_profile" class="menu-item">
            <i class="bi bi-person-gear"></i> Edit Profile
        </a>
        <a href="index.php?action=expert_verification_manuscripts" class="menu-item">
            <i class="bi bi-file-earmark-text"></i> Manuscripts
        </a>
        <a href="index.php?action=expert_verification_related" class="menu-item">
            <i class="bi bi-link-45deg"></i> Sources
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
                    <?php echo strtoupper(substr($_SESSION['username'] ?? 'E', 0, 1)); ?>
                </div>
                <div class="profile-text">
                    <h2>
                        Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Expert'); ?>
                        <span class="role-badge">Expert Panel</span>
                    </h2>
                    <p><?php echo htmlspecialchars($_SESSION['email'] ?? 'expert@manuhub.com'); ?></p>
                </div>
            </div>
            <a href="index.php?action=expert_profile" class="btn-edit-header">Edit Profile</a>
        </div>

        <div class="dashboard-grid">
            
            <div class="card">
                <h3>Pending Manuscripts</h3>
                <div class="big-number"><?php echo count($data['manuscripts']); ?></div>
                
                <div class="dark-table-container">
                    <div style="font-size: 11px; text-transform: uppercase; color: #95a5a6; margin-bottom: 10px; font-weight: 700;">Latest Submissions</div>
                    <table class="dark-table">
                        <?php if (!empty($data['manuscripts'])): ?>
                            <?php $latestMs = array_slice($data['manuscripts'], 0, 3); foreach ($latestMs as $m): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(substr($m['Title'], 0, 18)) . '...'; ?></td>
                                <td align="right" style="color:#f1c40f;">Pending</td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td style="text-align:center; color:#7f8c8d; padding:10px;">No pending items</td></tr>
                        <?php endif; ?>
                    </table>
                </div>
                <button class="btn-action" onclick="location.href='index.php?action=expert_verification_manuscripts'">Evaluate Now</button>
            </div>

            <div class="card">
                <h3>Pending Sources</h3>
                <div class="big-number"><?php echo count($data['related_works']); ?></div>
                
                <div class="dark-table-container">
                    <div style="font-size: 11px; text-transform: uppercase; color: #95a5a6; margin-bottom: 10px; font-weight: 700;">Latest Submissions</div>
                    <table class="dark-table">
                        <?php if (!empty($data['related_works'])): ?>
                            <?php $latestRw = array_slice($data['related_works'], 0, 3); foreach ($latestRw as $rw): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(substr($rw['title'], 0, 18)) . '...'; ?></td>
                                <td align="right" style="color:#26c6da;">Pending</td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td style="text-align:center; color:#7f8c8d; padding:10px;">No pending items</td></tr>
                        <?php endif; ?>
                    </table>
                </div>
                <button class="btn-action" onclick="location.href='index.php?action=expert_verification_related'">Evaluate Now</button>
            </div>

            <div class="card card-verified">
                <i class="bi bi-patch-check-fill card-bg-icon"></i>
                
                <h3>Total Verified Contributions</h3>
                <div class="big-number"><?php echo $data['total_verified'] ?? 0; ?></div>
                
                <p style="font-size: 13px; opacity: 0.9; margin-bottom: 25px;">
                    Total submissions you have reviewed since joining the panel.
                </p>

                <div style="display: flex; gap: 15px; width: 100%; margin-top: auto;">
                    
                    <div class="stat-box">
                        <div class="stat-box-num"><?php echo $data['count_ms'] ?? 0; ?></div>
                        <div class="stat-box-label">Manuscripts</div>
                    </div>

                    <div class="stat-box">
                        <div class="stat-box-num"><?php echo $data['count_rw'] ?? 0; ?></div>
                        <div class="stat-box-label">Related Works</div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</body>
</html>
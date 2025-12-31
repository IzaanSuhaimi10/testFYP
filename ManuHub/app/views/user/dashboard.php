<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - ManuHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        /* --- GLOBAL RESET --- */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { display: flex; height: 100vh; background-color: #f8f9fa; overflow: hidden; font-size: 14px; }
        
        /* --- SIDEBAR --- */
        .sidebar { width: 250px; min-width: 250px; background-color: #fff; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; padding: 25px; }
        .brand { font-size: 18px; font-weight: 800; margin-bottom: 40px; color: #333; letter-spacing: 0.5px; display: flex; align-items: center; gap: 10px; }
        .brand-logo { width: 32px; height: 32px; background: #e67e22; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 16px;}

        .menu-item { text-decoration: none; color: #666; padding: 12px 15px; margin-bottom: 8px; border-radius: 8px; font-size: 14px; transition: all 0.2s; display: flex; align-items: center; gap: 12px; font-weight: 500; }
        .menu-item:hover { background-color: #fff7ed; color: #e67e22; }
        .menu-item.active { background-color: #ffedd5; color: #c2410c; font-weight: 700; }
        .menu-item i { font-size: 18px; }

        /* --- MAIN CONTENT --- */
        .main-content { flex: 1; padding: 30px; overflow-y: auto; background-color: #f8f9fa; display: flex; flex-direction: column; gap: 25px; }

        /* --- PROFILE BANNER (TOP) --- */
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
            background: linear-gradient(135deg, #e67e22, #d35400); 
            border-radius: 50%; 
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; font-weight: bold; color: white;
            box-shadow: 0 4px 10px rgba(230, 126, 34, 0.3);
        }
        .profile-text h2 { font-size: 18px; font-weight: 700; color: #333; margin-bottom: 4px; }
        .profile-text p { color: #777; font-size: 13px; margin: 0; }
        .role-badge { 
            background: #ffedd5; color: #c2410c; 
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
        .btn-edit-header:hover { border-color: #e67e22; color: #e67e22; background: #fffaf5; }

        /* --- DASHBOARD GRID --- */
        .dashboard-grid {
            display: grid; 
            grid-template-columns: repeat(3, 1fr); 
            gap: 25px; 
        }

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
        
        /* Dark Tables */
        .dark-table-container {
            background-color: #2c3e50; border-radius: 10px;
            padding: 15px; color: white; margin-top: auto;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .dark-table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .dark-table td { padding: 10px 0; border-bottom: 1px solid #3e5871; color: #ecf0f1; }
        .dark-table tr:last-child td { border-bottom: none; }
        
        .status-badge { font-weight: 700; font-size: 10px; text-transform: uppercase; }
        .status-pending { color: #f1c40f; }
        .status-approved { color: #2ecc71; }
        .status-rejected { color: #e74c3c; }

        .btn-action {
            width: 100%; margin-top: 15px; padding: 12px;
            background: #e67e22; color: white; border: none; border-radius: 8px;
            font-weight: 600; cursor: pointer; text-transform: uppercase; font-size: 12px;
            transition: 0.2s;
        }
        .btn-action:hover { background: #d35400; transform: translateY(-1px); }

        /* --- SUMMARY CARD --- */
        .card-summary {
            background: linear-gradient(135deg, #34495e, #2c3e50);
            color: white;
        }
        .card-summary h3 { color: rgba(255,255,255,0.7); }
        .card-summary .big-number { color: white; }
        .summary-box { background: rgba(255,255,255,0.1); padding: 15px; border-radius: 12px; margin-top: auto; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand">
            <div class="brand-logo"><i class="bi bi-book-half"></i></div>
            MANUHUB
        </div>
        
        <a href="index.php?action=dashboard" class="menu-item active">
            <i class="bi bi-grid-fill"></i> Dashboard
        </a>
        <a href="index.php?action=submit_manuscript" class="menu-item">
            <i class="bi bi-plus-square-fill"></i> Submit New
        </a>
        <a href="index.php?action=user_edit_profile" class="menu-item">
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
                <div class="profile-text">
                    <h2>
                        Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Researcher'); ?>
                        <span class="role-badge">Researcher</span>
                    </h2>
                    <p><?php echo htmlspecialchars($_SESSION['email'] ?? 'user@manuhub.com'); ?></p>
                </div>
            </div>
            <a href="index.php?action=user_edit_profile" class="btn-edit-header">Edit Profile</a>
        </div>

        <div class="dashboard-grid">
            
            <div class="card">
                <h3>My Manuscripts</h3>
                <div class="big-number"><?php echo count($data['submissions'] ?? []); ?></div>
                
                <div class="dark-table-container">
                    <div style="font-size: 10px; text-transform: uppercase; color: #95a5a6; margin-bottom: 10px; font-weight: 700;">Recent Tracking</div>
                    <table class="dark-table">
                        <?php if (!empty($data['submissions'])): ?>
                            <?php $myMs = array_slice($data['submissions'], 0, 3); foreach ($myMs as $m): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(substr($m['Title'], 0, 18)) . '...'; ?></td>
                                <td align="right" class="status-badge status-<?php echo strtolower($m['status']); ?>">
                                    <?php echo ucfirst($m['status']); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td style="text-align:center; color:#7f8c8d; padding:10px;">No submissions yet</td></tr>
                        <?php endif; ?>
                    </table>
                </div>
                <button class="btn-action" onclick="location.href='index.php?action=submit_manuscript'">Submit Manuscript</button>
            </div>

            <div class="card">
                <h3>My Sources</h3>
                <div class="big-number"><?php echo count($data['related_works'] ?? []); ?></div>
                
                <div class="dark-table-container">
                    <div style="font-size: 10px; text-transform: uppercase; color: #95a5a6; margin-bottom: 10px; font-weight: 700;">Recent Tracking</div>
                    <table class="dark-table">
                        <?php if (!empty($data['related_works'])): ?>
                            <?php $myRw = array_slice($data['related_works'], 0, 3); foreach ($myRw as $rw): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(substr($rw['title'], 0, 18)) . '...'; ?></td>
                                <td align="right" class="status-badge status-<?php echo strtolower($rw['status']); ?>">
                                    <?php echo ucfirst($rw['status']); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td style="text-align:center; color:#7f8c8d; padding:10px;">No sources added</td></tr>
                        <?php endif; ?>
                    </table>
                </div>
                <button class="btn-action" onclick="location.href='index.php'">Browse & Link</button>
            </div>

            <div class="card card-summary">
                <h3>Community Impact</h3>
                <div class="big-number">
                    <?php 
                        $approvedCount = 0;
                        if(!empty($data['submissions'])) {
                            foreach($data['submissions'] as $s) if($s['status'] == 'approved') $approvedCount++;
                        }
                        if(!empty($data['related_works'])) {
                            foreach($data['related_works'] as $r) if($r['status'] == 'approved') $approvedCount++;
                        }
                        echo $approvedCount;
                    ?>
                </div>
                <p style="font-size: 13px; opacity: 0.8; margin-bottom: 20px;">
                    Total approved contributions currently live on ManuHub.
                </p>

                <div class="summary-box">
                    <div class="summary-row">
                        <span style="opacity:0.8;">Join Date</span>
                        <span style="font-weight:700;"><?php echo date('M Y'); ?></span>
                    </div>
                    <div class="summary-row">
                        <span style="opacity:0.8;">Account Status</span>
                        <span style="font-weight:700; color:#2ecc71;">Active</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</body>
</html>
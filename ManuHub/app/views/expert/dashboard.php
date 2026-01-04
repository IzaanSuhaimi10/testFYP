<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expert Dashboard - ManuHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { display: flex; height: 100vh; background-color: #f8f9fa; overflow: hidden; font-size: 14px; }
        
        .sidebar { width: 250px; min-width: 250px; background-color: #fff; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; padding: 25px; }
        .brand { font-size: 18px; font-weight: 800; margin-bottom: 40px; color: #333; display: flex; align-items: center; gap: 10px; }
        .brand-logo { width: 32px; height: 32px; background: #26c6da; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; }
        .menu-item { text-decoration: none; color: #666; padding: 12px 15px; margin-bottom: 8px; border-radius: 8px; display: flex; align-items: center; gap: 12px; font-weight: 500; }
        .menu-item.active { background-color: #e0f7fa; color: #006064; font-weight: 700; }

        .main-content { flex: 1; padding: 30px; overflow-y: auto; display: flex; flex-direction: column; gap: 25px; }

        .profile-banner { background: white; border-radius: 16px; padding: 25px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #eee; }
        .avatar-large { width: 60px; height: 60px; background: linear-gradient(135deg, #26c6da, #00acc1); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; color: white; box-shadow: 0 4px 10px rgba(38, 198, 218, 0.4); }

        /* DASHBOARD LAYOUT (2x2 Left, 1 Vertical Right) */
        .dashboard-container { display: grid; grid-template-columns: 2fr 1fr; gap: 25px; align-items: start; }
        .left-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }

        .card { background-color: #D1E6E5; border-radius: 16px; padding: 25px; display: flex; flex-direction: column; border: 1px solid rgba(0,0,0,0.05); }
        .card h3 { font-size: 12px; color: #555; font-weight: 700; text-transform: uppercase; margin-bottom: 15px; }
        .big-number { font-size: 42px; font-weight: 800; color: #333; margin-bottom: 20px; line-height: 1; }
        
        .dark-table-container { background-color: #2c3e50; border-radius: 10px; padding: 15px; color: white; margin-top: auto; }
        .dark-table { width: 100%; border-collapse: collapse; font-size: 11px; }
        .dark-table td { padding: 10px 0; border-bottom: 1px solid #3e5871; color: #ecf0f1; }
        .dark-table tr:last-child td { border-bottom: none; }
        
        .btn-action { width: 100%; margin-top: 15px; padding: 12px; background: #20878b; color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; text-transform: uppercase; font-size: 11px; }

        /* VERIFIED SUMMARY CARD (RIGHT PANEL) */
        .card-summary { background: linear-gradient(135deg, #1abc9c, #16a085); color: white; height: 100%; min-height: 550px; display: flex; flex-direction: column; border-radius: 16px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .card-summary h3 { color: rgba(255,255,255,0.8); }
        .card-summary .big-number { color: white; font-size: 64px; }
        .summary-box { background: rgba(255,255,255,0.15); padding: 25px; border-radius: 12px; margin-top: auto; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 14px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 12px; }
        .summary-row:last-child { border: none; margin-bottom: 0; }
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
        <div style="display: flex; align-items: center; gap: 20px;">
            <div class="avatar-large"><?php echo strtoupper(substr($_SESSION['username'] ?? 'E', 0, 1)); ?></div>
            <div>
                <h2 style="font-size: 18px; font-weight: 700;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
                <p style="color: #777;"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
            </div>
        </div>
        <a href="index.php?action=expert_profile" style="text-decoration: none; color: #555; border: 1px solid #ddd; padding: 10px 20px; border-radius: 8px;">Edit Profile</a>
    </div>

    <div class="dashboard-container">
        <div class="left-grid">
            <div class="card">
                <h3>Pending Manuscripts</h3>
                <div class="big-number"><?php echo $data['count_pending_ms']; ?></div>
                <div class="dark-table-container">
                    <table class="dark-table">
                        <?php if (!empty($data['manuscripts'])): ?>
                            <?php foreach (array_slice($data['manuscripts'], 0, 2) as $m): ?>
                            <tr><td><?php echo htmlspecialchars(mb_strimwidth($m['Title'], 0, 18, "...")); ?></td></tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td style="text-align:center;">No pending items</td></tr>
                        <?php endif; ?>
                    </table>
                </div>
                <button class="btn-action" onclick="location.href='index.php?action=expert_verification_manuscripts'">Evaluate Now</button>
            </div>

            <div class="card">
                <h3>Pending Suggestions</h3>
                <div class="big-number"><?php echo $data['count_pending_sug']; ?></div>
                <div class="dark-table-container">
                    <table class="dark-table">
                        <?php if (!empty($data['suggestions'])): ?>
                            <?php foreach (array_slice($data['suggestions'], 0, 2) as $s): ?>
                            <tr><td><?php echo $s['field_name']; ?> edit</td></tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td style="text-align:center;">No new suggestions</td></tr>
                        <?php endif; ?>
                    </table>
                </div>
                <button class="btn-action" onclick="location.href='index.php?action=expert_verification_suggestions'">Review Edits</button>
            </div>

            <div class="card">
                <h3>Pending Sources</h3>
                <div class="big-number"><?php echo $data['count_pending_rw']; ?></div>
                <div class="dark-table-container">
                    <table class="dark-table">
                        <?php if (!empty($data['related_works'])): ?>
                            <?php foreach (array_slice($data['related_works'], 0, 2) as $r): ?>
                            <tr><td><?php echo htmlspecialchars(mb_strimwidth($r['title'], 0, 18, "...")); ?></td></tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td style="text-align:center;">No pending items</td></tr>
                        <?php endif; ?>
                    </table>
                </div>
                <button class="btn-action" onclick="location.href='index.php?action=expert_verification_related'">Evaluate Now</button>
            </div>

            <div class="card">
                <h3>Content Flags</h3>
                <div class="big-number"><?php echo $data['count_pending_flags']; ?></div>
                <div class="dark-table-container">
                    <table class="dark-table">
                        <?php if (!empty($data['flags'])): ?>
                            <?php foreach (array_slice($data['flags'], 0, 2) as $f): ?>
                            <tr><td>Flag #<?php echo $f['id']; ?></td>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td style="text-align:center;">No flags</td></tr>
                        <?php endif; ?>
                    </table>
                </div>
                <button class="btn-action" onclick="location.href='index.php?action=expert_verification_flags'">Moderate Now</button>
            </div>
        </div>

        <div class="card card-summary">
            <h3>Verified Contributions</h3>
            <div class="big-number"><?php echo $data['total_verified']; ?></div>
            <p style="font-size: 13px; opacity: 0.9; margin-bottom: 20px;">Combined total of your approved research reviews.</p>
            <div class="summary-box">
                <div class="summary-row"><span><i class="bi bi-file-earmark-text"></i> Manuscripts</span><strong><?php echo $data['count_ms_approved']; ?></strong></div>
                <div class="summary-row"><span><i class="bi bi-pencil-square"></i> Edits</span><strong><?php echo $data['count_sug_approved']; ?></strong></div>
                <div class="summary-row"><span><i class="bi bi-link-45deg"></i> Sources</span><strong><?php echo $data['count_rw_approved']; ?></strong></div>
                <div class="summary-row"><span><i class="bi bi-flag-fill"></i> Resolved Flags</span><strong><?php echo $data['count_flags_resolved']; ?></strong></div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
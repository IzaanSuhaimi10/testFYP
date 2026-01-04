<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - ManuHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        /* --- GLOBAL RESET --- */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { display: flex; height: 100vh; background-color: #f8f9fa; overflow: hidden; font-size: 14px; }
        
        /* SIDEBAR */
        .sidebar { width: 250px; min-width: 250px; background-color: #fff; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; padding: 25px; }
        .brand { font-size: 18px; font-weight: 800; margin-bottom: 40px; color: #333; display: flex; align-items: center; gap: 10px; }
        .brand-logo { width: 32px; height: 32px; background: #333; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; }
        .menu-item { text-decoration: none; color: #666; padding: 12px 15px; margin-bottom: 8px; border-radius: 8px; display: flex; align-items: center; gap: 12px; font-weight: 500; transition: 0.2s; }
        .menu-item.active { background-color: #D1E6E5; color: #004d40; font-weight: 700; }

        .main-content { flex: 1; padding: 30px; overflow-y: auto; display: flex; flex-direction: column; gap: 25px; }

        /* --- RESTORED PROFILE BANNER (Matching image_8ca785.png) --- */
        .profile-banner {
            background: white; border-radius: 16px; padding: 25px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #eee;
        }
        .profile-left { display: flex; align-items: center; gap: 20px; }
        .avatar-admin { 
            width: 60px; height: 60px; background: #333; border-radius: 50%; 
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; font-weight: bold; color: white;
        }
        .role-badge { 
            background: #e0f2f1; color: #00695c; padding: 4px 12px; border-radius: 20px; 
            font-size: 10px; font-weight: 800; text-transform: uppercase; margin-left: 10px;
        }
        .system-status-indicator { font-size: 12px; color: #888; text-align: right; }

        /* DASHBOARD LAYOUT */
        .dashboard-container { display: grid; grid-template-columns: 2fr 1fr; gap: 25px; align-items: start; }
        .left-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }

        /* CARDS */
        .card { background-color: #D1E6E5; border-radius: 16px; padding: 25px; display: flex; flex-direction: column; border: 1px solid rgba(0,0,0,0.05); height: 100%; }
        .card h3 { font-size: 12px; color: #555; font-weight: 700; text-transform: uppercase; margin-bottom: 15px; }
        .big-number { font-size: 42px; font-weight: 800; color: #222; margin-bottom: 20px; line-height: 1; }
        
        .dark-table-container { background-color: #2c3e50; border-radius: 10px; padding: 15px; color: white; margin-top: auto; }
        .dark-table { width: 100%; border-collapse: collapse; font-size: 11px; }
        .dark-table td { padding: 10px 0; border-bottom: 1px solid #3e5871; color: #ecf0f1; }
        
        .btn-action { width: 100%; margin-top: 15px; padding: 12px; background: #387c7e; color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; text-transform: uppercase; font-size: 11px; }

        /* SYSTEM HEALTH CARD (RIGHT) */
        .card-system { background: linear-gradient(135deg, #2c3e50, #1a252f); color: white; height: 100%; min-height: 550px; display: flex; flex-direction: column; border-radius: 16px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .card-system h3 { color: rgba(255,255,255,0.6); }
        .card-system .big-number { color: #26c6da; font-size: 64px; }
        .system-box { background: rgba(255,255,255,0.05); padding: 20px; border-radius: 12px; margin-top: auto; }
        .system-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 13px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 8px; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="brand"><div class="brand-logo"><i class="bi bi-shield-lock"></i></div> MANUHUB</div>
    <a href="index.php?action=admin_dashboard" class="menu-item active"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="index.php?action=admin_manuscripts" class="menu-item"><i class="bi bi-journal-text"></i> Oversight</a>
    <a href="index.php?action=admin_users" class="menu-item"><i class="bi bi-people-fill"></i> Users</a>
    <a href="index.php?action=admin_system" class="menu-item"><i class="bi bi-terminal"></i> Logs</a>
    <a href="index.php" class="menu-item"><i class="bi bi-house"></i> Home Page</a>
    <a href="index.php?action=logout" class="menu-item" style="margin-top: auto; color: #c0392b;"><i class="bi bi-box-arrow-left"></i> Logout</a>
</div>

<div class="main-content">
    
    <div class="profile-banner">
        <div class="profile-left">
            <div class="avatar-admin">A</div>
            <div>
                <h2 style="font-size: 18px; font-weight: 700; color: #333;">
                    System Administrator
                    <span class="role-badge">Root Access</span>
                </h2>
                <p style="color: #777; font-size: 13px; margin-top: 4px;">
                    Session ID: <?php echo $data['session_id']; ?> | <?php echo $data['server_date']; ?>
                </p>
            </div>
        </div>
        <div class="system-status-indicator">
            System Status: <span style="color: #2ecc71; font-weight: bold;">● Operational</span>
        </div>
    </div>

    <div class="dashboard-container">
        <div class="left-grid">
            <div class="card">
                <h3>Oversight Queue</h3>
                <div class="big-number"><?php echo $data['pending_total']; ?></div>
                <div class="dark-table-container">
                    <table class="dark-table">
                        <tr><td>Manuscripts Pending</td><td align="right" style="color:#f1c40f;"><?php echo $data['pending_ms']; ?></td></tr>
                        <tr><td>Sources Pending</td><td align="right" style="color:#f1c40f;"><?php echo $data['pending_rw']; ?></td></tr>
                        <tr><td>Flags Awaiting Action</td><td align="right" style="color:#e74c3c;"><?php echo $data['pending_flags']; ?></td></tr>
                    </table>
                </div>
                <button class="btn-action" onclick="location.href='index.php?action=admin_manuscripts'">Go to Oversight</button>
            </div>

            <div class="card">
                <h3>Total Accounts</h3>
                <div class="big-number"><?php echo $data['user_count']; ?></div>
                <div class="dark-table-container">
                    <table class="dark-table">
                        <?php foreach($data['latest_users'] as $u): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($u['username']); ?></td>
                            <td align="right" style="opacity:0.6;"><?php echo ucfirst($u['role']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <button class="btn-action" onclick="location.href='index.php?action=admin_users'">Manage Users</button>
            </div>

            <div class="card">
                <h3>Live Manuscripts</h3>
                <div class="big-number"><?php echo $data['live_ms_count']; ?></div>
                <div class="dark-table-container" style="background: #34495e;">
                    <div style="font-size: 10px; color:#95a5a6; margin-bottom:5px;">DATABASE STATUS</div>
                    <div style="font-size: 12px; color:#2ecc71;">● Connected & Live</div>
                </div>
                <button class="btn-action" onclick="location.href='index.php?action=manuscript_list'">Public Search</button>
            </div>

            <div class="card">
                <h3>System Activity</h3>
                <div class="big-number"><i class="bi bi-clock-history"></i></div>
                <div class="dark-table-container">
                    <table class="dark-table">
                        <?php foreach($data['latest_logs'] as $log): ?>
                        <tr>
                            <td><?php echo htmlspecialchars(mb_strimwidth($log['description'], 0, 25, "...")); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <button class="btn-action" onclick="location.href='index.php?action=admin_system'">Full Audit Logs</button>
            </div>
        </div>

        <div class="card-system">
            <h3>System Health</h3>
            <div class="big-number"><i class="bi bi-cpu"></i></div>
            <p style="font-size: 13px; opacity: 0.8; margin-bottom: 20px;">Monitoring authentication, security protocols, and server performance.</p>
            <div class="system-box">
                <div class="system-row"><span>Status</span><strong style="color:#2ecc71;">Operational</strong></div>
                <div class="system-row"><span>Role</span><strong>Administrator</strong></div>
                <div class="system-row"><span>Storage</span><strong>Active</strong></div>
                <div class="system-row"><span>Server Time</span><strong><?php echo date('H:i'); ?></strong></div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
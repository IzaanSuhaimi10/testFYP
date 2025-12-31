<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ManuHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        /* --- GLOBAL RESET --- */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { display: flex; height: 100vh; background-color: #f8f9fa; overflow: hidden; font-size: 14px; }
        
        /* --- SIDEBAR --- */
        .sidebar { width: 250px; min-width: 250px; background-color: #fff; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; padding: 25px; }
        .brand { font-size: 18px; font-weight: 800; margin-bottom: 40px; color: #333; letter-spacing: 0.5px; display: flex; align-items: center; gap: 10px; }
        .brand-logo { width: 32px; height: 32px; background: #333; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 16px;}

        .menu-item { text-decoration: none; color: #666; padding: 12px 15px; margin-bottom: 8px; border-radius: 8px; font-size: 14px; transition: all 0.2s; display: flex; align-items: center; gap: 12px; font-weight: 500; }
        .menu-item:hover { background-color: #f3f4f6; color: #000; }
        .menu-item.active { background-color: #D1E6E5; color: #004d40; font-weight: 700; }
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
        .avatar-admin { 
            width: 60px; height: 60px; 
            background: linear-gradient(135deg, #333, #555); 
            border-radius: 50%; 
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; font-weight: bold; color: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        .profile-text h2 { font-size: 18px; font-weight: 700; color: #333; margin-bottom: 4px; }
        .profile-text p { color: #777; font-size: 13px; margin: 0; }
        .role-badge { 
            background: #e0f2f1; color: #00695c; 
            padding: 4px 12px; border-radius: 20px; 
            font-size: 10px; font-weight: 800; text-transform: uppercase; 
            margin-left: 10px; display: inline-block;
        }

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
        .card h3 { font-size: 13px; color: #555; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 15px; }
        .big-number { font-size: 42px; font-weight: 800; color: #222; margin-bottom: 20px; line-height: 1; }
        
        /* Dark Tables (Logs/Activity) */
        .dark-table-container {
            background-color: #2c3e50; border-radius: 10px;
            padding: 15px; color: white; margin-top: auto;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .dark-table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .dark-table td { padding: 10px 0; border-bottom: 1px solid #3e5871; color: #ecf0f1; }
        .dark-table tr:last-child td { border-bottom: none; }
        
        .btn-action {
            width: 100%; margin-top: 15px; padding: 12px;
            background: #387c7e; color: white; border: none; border-radius: 8px;
            font-weight: 600; cursor: pointer; text-transform: uppercase; font-size: 12px;
            transition: 0.2s;
        }
        .btn-action:hover { background: #2b6062; transform: translateY(-1px); }

        /* --- SYSTEM STATUS CARD --- */
        .card-system {
            background: linear-gradient(135deg, #2c3e50, #1a252f);
            color: white;
        }
        .card-system h3 { color: rgba(255,255,255,0.6); }
        .card-system .big-number { color: #26c6da; }
        .system-box { background: rgba(255,255,255,0.05); padding: 15px; border-radius: 12px; margin-top: auto; }
        .system-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 12px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand">
            <div class="brand-logo"><i class="bi bi-shield-lock"></i></div>
            MANUHUB
        </div>
        
        <a href="index.php?action=admin_dashboard" class="menu-item active">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="index.php?action=admin_manuscripts" class="menu-item">
            <i class="bi bi-journal-text"></i> Oversight
        </a>
        <a href="index.php?action=admin_users" class="menu-item">
            <i class="bi bi-people-fill"></i> User Management
        </a>
        <a href="index.php?action=admin_system" class="menu-item">
            <i class="bi bi-terminal"></i> System Logs
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
                <div class="avatar-admin">A</div>
                <div class="profile-text">
                    <h2>
                        System Administrator
                        <span class="role-badge">Root Access</span>
                    </h2>
                    <p>Session ID: <?php echo session_id(); ?> | <?php echo date('d M Y'); ?></p>
                </div>
            </div>
            <div style="font-size: 12px; color: #888; text-align: right;">
                System Status: <span style="color: #2ecc71; font-weight: bold;">● Operational</span>
            </div>
        </div>

        <div class="dashboard-grid">
            
            <div class="card">
                <h3>Oversight Queue</h3>
                <div class="big-number"><?php echo ($data['pending_count'] ?? 0) + ($data['pending_rw_count'] ?? 0); ?></div>
                
                <div class="dark-table-container">
                    <div style="font-size: 10px; text-transform: uppercase; color: #95a5a6; margin-bottom: 10px; font-weight: 700;">Awaiting Expert Action</div>
                    <table class="dark-table">
                        <tr>
                            <td>Manuscripts Pending</td>
                            <td align="right" style="color:#f1c40f;"><?php echo $data['pending_count'] ?? 0; ?></td>
                        </tr>
                        <tr>
                            <td>Sources Pending</td>
                            <td align="right" style="color:#f1c40f;"><?php echo $data['pending_rw_count'] ?? 0; ?></td>
                        </tr>
                    </table>
                </div>
                <button class="btn-action" onclick="location.href='index.php?action=admin_manuscripts'">Go to Oversight</button>
            </div>

            <div class="card">
                <h3>Total Accounts</h3>
                <div class="big-number"><?php echo $data['user_count'] ?? 0; ?></div>
                
                <div class="dark-table-container">
                    <div style="font-size: 10px; text-transform: uppercase; color: #95a5a6; margin-bottom: 10px; font-weight: 700;">Latest Activity</div>
                    <table class="dark-table">
                        <?php if(!empty($data['latest_users'])): ?>
                            <?php foreach($data['latest_users'] as $u): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($u['username']); ?></td>
                                <td align="right" style="opacity:0.6;"><?php echo ucfirst($u['role']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="2" align="center">No active logs</td></tr>
                        <?php endif; ?>
                    </table>
                </div>
                <button class="btn-action" onclick="location.href='index.php?action=admin_users'">Manage Users</button>
            </div>

            <div class="card card-system">
                <h3>Security Logs</h3>
                <div class="big-number"><i class="bi bi-activity"></i></div>
                <p style="font-size: 13px; opacity: 0.8; margin-bottom: 20px;">
                    Monitoring authentication and data modification events.
                </p>

                <div class="system-box">
                    <div class="system-row">
                        <span style="opacity:0.6;">Last Login</span>
                        <span style="font-weight:700;">Admin</span>
                    </div>
                    <div class="system-row">
                        <span style="opacity:0.6;">Database</span>
                        <span style="font-weight:700; color:#2ecc71;">Connected</span>
                    </div>
                    <div class="system-row">
                        <span style="opacity:0.6;">Server Time</span>
                        <span style="font-weight:700;"><?php echo date('H:i T'); ?></span>
                    </div>
                </div>
                <button class="btn-action" style="background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2);" onclick="location.href='index.php?action=admin_system'">View All Logs</button>
            </div>

        </div>

    </div>
</body>
</html>
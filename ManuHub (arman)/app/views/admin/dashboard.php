<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>Admin Dashboard - ManuHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* --- RESET & LAYOUT --- */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { display: flex; height: 100vh; background-color: #f4f4f4; overflow: hidden; font-size: 14px; }
        
        /* --- SIDEBAR --- */
        .sidebar {
            width: 250px;
            min-width: 250px;
            background-color: #fff;
            border-right: 1px solid #ddd;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }
        .brand {
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 40px;
            color: #333;
            letter-spacing: 1px;
            display: flex; align-items: center; gap: 10px;
        }
        /* Logo Placeholder */
        .brand-logo { width: 30px; height: 30px; background: #e67e22; border-radius: 50%; }

        .menu-item {
            text-decoration: none;
            color: #555;
            padding: 12px 15px;
            margin-bottom: 8px;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
            display: flex; align-items: center;
        }
        .menu-item:hover { background-color: #f0f0f0; color: #000; }
        .menu-item.active {
            background-color: #D1E6E5; /* Light Teal */
            color: #004d40;
            font-weight: 600;
        }
        .menu-icon { width: 8px; height: 8px; background: #6FB1B3; border-radius: 50%; margin-right: 12px; }

        /* --- MAIN CONTENT --- */
        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto; 
            background-color: #fdfdfd;
        }

        /* --- HEADER --- */
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .page-title { font-size: 22px; font-weight: 700; color: #222; }
        .admin-profile { display: flex; align-items: center; gap: 12px; background: #fff; padding: 6px 15px; border-radius: 30px; border: 1px solid #eee; }
        .admin-avatar { width: 32px; height: 32px; background-color: #6FB1B3; border-radius: 50%; }

        /* --- DASHBOARD GRID --- */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Equal split */
            gap: 25px;
            margin-bottom: 25px;
        }

        .card {
            background-color: #D1E6E5; /* Your Theme Color */
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .card h3 { font-size: 16px; margin-bottom: 10px; color: #444; font-weight: 600; }
        .big-number { font-size: 42px; font-weight: 800; color: #222; margin-bottom: 20px; }

        /* --- TABLES --- */
        .dark-table-container {
            width: 100%;
            background-color: #333;
            border-radius: 10px;
            padding: 15px;
            color: white;
            font-size: 13px;
            margin-top: 10px;
        }
        .dark-table { width: 100%; border-collapse: collapse; }
        .dark-table th { text-align: left; padding: 8px; color: #bbb; font-size: 12px; border-bottom: 1px solid #444; }
        .dark-table td { padding: 8px; border-bottom: 1px solid #444; }
        .dark-table tr:last-child td { border-bottom: none; }
        
        .badge { padding: 3px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .badge-pending { background: #f39c12; color: white; }
        .badge-approved { background: #27ae60; color: white; }
        .badge-rejected { background: #c0392b; color: white; }

        .btn-view {
            background-color: #387c7e;
            color: white; border: none; padding: 8px 24px; border-radius: 6px;
            cursor: pointer; margin-top: 15px; font-size: 12px; font-weight: 600;
            transition: background 0.2s;
        }
        .btn-view:hover { background-color: #2b6062; }

        .log-list { list-style: none; text-align: left; width: 100%; padding: 0 10px; }
        .log-list li { margin-bottom: 8px; font-size: 13px; color: #333; display: flex; align-items: center; }
        .log-dot { min-width: 6px; height: 6px; background: #333; border-radius: 50%; margin-right: 10px; }

    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand">
            <div class="brand-logo"></div>
            <span>MANUHUB ADMIN</span>
        </div>
        
        <a href="index.php?action=admin_dashboard" class="menu-item active">
            <span class="menu-icon"></span> Dashboard
        </a>
        <a href="index.php" class="menu-item"> <span class="menu-icon"></span> Homepage
        </a>
        <a href="index.php?action=admin_manuscripts" class="menu-item">
            <span class="menu-icon"></span> Manuscript Oversight
        </a>
        <a href="index.php?action=admin_users" class="menu-item">
            <span class="menu-icon"></span> User Management
        </a>
        <a href="index.php?action=admin_system" class="menu-item">
            <span class="menu-icon"></span> System Management
        </a>

        <a href="index.php?action=logout" class="menu-item" style="margin-top: auto; color: #c0392b;">
            Logout
        </a>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <div class="page-title">DASHBOARD OVERVIEW</div>
            <div class="admin-profile">
                <div style="text-align: right; line-height: 1.2;">
                    <div style="font-size: 10px; color: #666;">Welcome back</div>
                    <div style="font-size: 13px; font-weight: bold;">Admin</div>
                </div>
                <div class="admin-avatar"></div>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="card">
                <h3>Pending Manuscripts</h3>
                <div class="big-number"><?php echo $data['pending_count'] ?? 0; ?></div>
                
                <div style="width: 100%; text-align: left; margin-bottom: 5px; font-weight: 600; font-size: 13px;">Latest Submissions</div>
                <div class="dark-table-container">
                    <table class="dark-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Title</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data['latest_submissions'])): ?>
                                <?php foreach ($data['latest_submissions'] as $sub): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($sub['username'] ?? 'User'); ?></td>
                                    <td><?php echo htmlspecialchars(substr($sub['Title'], 0, 12)) . '...'; ?></td>
                                    <td><span class="badge badge-<?php echo $sub['status']; ?>"><?php echo $sub['status']; ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" style="text-align:center; color:#777;">No pending items</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <button class="btn-view" onclick="location.href='index.php?action=admin_manuscripts'">View All</button>
            </div>

            <div class="card">
                <h3>Total Users</h3>
                <div class="big-number"><?php echo $data['user_count'] ?? 0; ?></div>

                <div style="width: 100%; text-align: left; margin-bottom: 5px; font-weight: 600; font-size: 13px;">System Logs</div>
                <ul class="log-list">
                    <?php if (!empty($data['latest_logs'])): ?>
                        <?php foreach ($data['latest_logs'] as $log): ?>
                        <li>
                            <div class="log-dot"></div>
                            <span>
                                <strong><?php echo date('H:i', strtotime($log['created_at'])); ?>:</strong> 
                                <?php echo htmlspecialchars($log['action']); ?>
                            </span>
                        </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li style="color:#666;">No recent logs.</li>
                    <?php endif; ?>
                </ul>
                <button class="btn-view" onclick="location.href='index.php?action=admin_system'">View Logs</button>
            </div>
        </div>

        <div class="card" style="align-items: flex-start; text-align: left;">
            <h3 style="width: 100%; text-align: center;">Latest Registered Users</h3>
            <div class="dark-table-container">
                <table class="dark-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['latest_users'])): ?>
                            <?php foreach ($data['latest_users'] as $u): ?>
                            <tr>
                                <td><?php echo $u['id'] ?? $u['user_id']; ?></td>
                                <td><?php echo htmlspecialchars($u['username']); ?></td>
                                <td><?php echo htmlspecialchars($u['email']); ?></td>
                                <td><?php echo ucfirst($u['role']); ?></td>
                                <td><?php echo date('d M Y', strtotime($u['created_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" style="text-align:center;">No users found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>
</html>
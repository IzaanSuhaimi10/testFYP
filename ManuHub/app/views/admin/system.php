<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Logs - ManuHub Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        /* --- GLOBAL RESET --- */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { display: flex; height: 100vh; background-color: #f8f9fa; overflow: hidden; font-size: 14px; }
        
        /* SIDEBAR */
        .sidebar { width: 250px; min-width: 250px; background-color: #fff; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; padding: 25px; }
        .brand { font-size: 18px; font-weight: 800; margin-bottom: 40px; color: #333; display: flex; align-items: center; gap: 10px; }
        .brand-logo { width: 32px; height: 32px; background: #333; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 16px;}
        .menu-item { text-decoration: none; color: #666; padding: 12px 15px; margin-bottom: 8px; border-radius: 8px; display: flex; align-items: center; gap: 12px; font-weight: 500; }
        .menu-item:hover { background-color: #f3f4f6; color: #000; }
        .menu-item.active { background-color: #D1E6E5; color: #004d40; font-weight: 700; }

        /* MAIN CONTENT */
        .main-content { flex: 1; padding: 30px; overflow-y: auto; background-color: #f8f9fa; display: flex; flex-direction: column; gap: 20px; }

        /* TOP BAR */
        .top-bar { display: flex; justify-content: space-between; align-items: center; }
        .page-title { font-size: 24px; font-weight: 700; color: #222; }

        /* FILTER BAR */
        .filter-bar {
            background: white; padding: 15px 20px; border-radius: 12px;
            display: flex; gap: 15px; align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03); border: 1px solid #eee;
        }
        .search-input { flex: 1; padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; }
        .filter-select { padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: white; cursor: pointer; }

        /* TABLE CONTAINER */
        .table-container {
            background-color: #2c3e50; border-radius: 12px;
            padding: 20px; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .dark-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .dark-table th { text-align: left; padding: 15px; color: #95a5a6; font-size: 11px; text-transform: uppercase; border-bottom: 1px solid #3e5871; }
        .dark-table td { padding: 15px; border-bottom: 1px solid #3e5871; vertical-align: top; }
        
        /* LOG BADGES */
        .log-badge { padding: 4px 10px; border-radius: 4px; font-size: 10px; font-weight: 800; text-transform: uppercase; }
        .type-login { background: rgba(52, 152, 219, 0.2); color: #3498db; border: 1px solid #3498db; }
        .type-upload { background: rgba(46, 204, 113, 0.2); color: #2ecc71; border: 1px solid #2ecc71; }
        .type-verification { background: rgba(155, 89, 182, 0.2); color: #9b59b6; border: 1px solid #9b59b6; }
        .type-delete { background: rgba(231, 76, 60, 0.2); color: #e74c3c; border: 1px solid #e74c3c; }
        .type-default { background: rgba(149, 165, 166, 0.2); color: #95a5a6; border: 1px solid #95a5a6; }

        .log-message { color: #ecf0f1; font-family: 'Courier New', Courier, monospace; font-size: 12px; }
        .log-timestamp { color: #95a5a6; font-size: 11px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand"><div class="brand-logo"><i class="bi bi-shield-lock"></i></div> MANUHUB</div>
        <a href="index.php?action=admin_dashboard" class="menu-item"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="index.php?action=admin_manuscripts" class="menu-item"><i class="bi bi-journal-text"></i> Oversight</a>
        <a href="index.php?action=admin_users" class="menu-item"><i class="bi bi-people-fill"></i> User Management</a>
        <a href="index.php?action=admin_system" class="menu-item active"><i class="bi bi-terminal"></i> System Logs</a>
        <a href="index.php" class="menu-item"><i class="bi bi-house"></i> Home Page</a>
        <a href="index.php?action=logout" class="menu-item" style="margin-top: auto; color: #c0392b;"><i class="bi bi-box-arrow-left"></i> Logout</a>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <h1 class="page-title">System Activity Logs</h1>
            <div style="font-size: 12px; color: #888;">Live feed of all administrative and user events.</div>
        </div>

        <div class="filter-bar">
            <i class="bi bi-search" style="color: #999;"></i>
            <input type="text" id="logSearch" class="search-input" placeholder="Search by activity or keyword..." onkeyup="filterLogs()">
            <select id="typeFilter" class="filter-select" onchange="filterLogs()">
                <option value="all">All Event Types</option>
                <option value="LOGIN">Logins</option>
                <option value="UPLOAD">Uploads</option>
                <option value="VERIFICATION">Verifications</option>
                <option value="DELETE">Deletions</option>
            </select>
        </div>

        <div class="table-container">
            <table class="dark-table" id="logTable">
                <thead>
                    <tr>
                        <th width="15%">Timestamp</th>
                        <th width="15%">Event Type</th>
                        <th width="70%">Activity Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['logs'])): ?>
                        <?php foreach($data['logs'] as $log): ?>
<tr class="log-row" data-type="<?php echo strtoupper($log['action'] ?? 'GENERAL'); ?>">
    <td class="log-timestamp">
        <?php echo date('d M Y', strtotime($log['created_at'] ?? 'now')); ?><br>
        <span style="opacity: 0.7;"><?php echo date('H:i:s', strtotime($log['created_at'] ?? 'now')); ?></span>
    </td>
    <td>
        <?php 
            // Use ?? to provide 'GENERAL' if 'action_type' is missing
            $type = strtoupper($log['action'] ?? 'GENERAL'); 
            $class = 'type-default';
            if($type == 'LOGIN') $class = 'type-login';
            if($type == 'UPLOAD') $class = 'type-upload';
        ?>
        <span class="log-badge <?php echo $class; ?>"><?php echo $type; ?></span>
    </td>
    <td class="log-message">
        <?php echo htmlspecialchars($log['description'] ?? 'No details available'); ?>
    </td>
</tr>
<?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3" align="center" style="padding: 40px; color: #7f8c8d;">No system logs available.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function filterLogs() {
            let search = document.getElementById("logSearch").value.toLowerCase();
            let type = document.getElementById("typeFilter").value.toUpperCase();
            let rows = document.querySelectorAll(".log-row");

            rows.forEach(row => {
                let rowType = row.getAttribute('data-type');
                let matchesSearch = row.innerText.toLowerCase().includes(search);
                let matchesType = (type === 'ALL' || rowType.includes(type));
                
                row.style.display = (matchesSearch && matchesType) ? "" : "none";
            });
        }
    </script>
</body>
</html>
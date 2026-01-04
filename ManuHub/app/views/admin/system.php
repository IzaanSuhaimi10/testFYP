<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Logs - ManuHub Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        /* --- GLOBAL RESET & SIDEBAR (Matching your Admin Style) --- */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { display: flex; height: 100vh; background-color: #f8f9fa; overflow: hidden; font-size: 14px; }
        .sidebar { width: 250px; min-width: 250px; background-color: #fff; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; padding: 25px; }
        .brand { font-size: 18px; font-weight: 800; margin-bottom: 40px; color: #333; display: flex; align-items: center; gap: 10px; }
        .brand-logo { width: 32px; height: 32px; background: #333; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; }
        .menu-item { text-decoration: none; color: #666; padding: 12px 15px; margin-bottom: 8px; border-radius: 8px; display: flex; align-items: center; gap: 12px; font-weight: 500; }
        .menu-item.active { background-color: #D1E6E5; color: #004d40; font-weight: 700; }

        .main-content { flex: 1; padding: 30px; overflow-y: auto; background-color: #f8f9fa; display: flex; flex-direction: column; gap: 20px; }

        /* TOP BAR */
        .top-bar { display: flex; justify-content: space-between; align-items: flex-start; }
        .page-title { font-size: 24px; font-weight: 800; color: #222; text-transform: uppercase; }

        .btn-clear-logs { 
            background: #c0392b; color: white; border: none; padding: 10px 20px; 
            border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.2s;
            display: flex; align-items: center; gap: 8px; font-size: 12px;
        }
        .btn-clear-logs:hover { background: #e74c3c; transform: translateY(-1px); }

        /* FILTER BAR */
        .filter-bar {
            background: white; padding: 15px 20px; border-radius: 12px;
            display: flex; gap: 15px; align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03); border: 1px solid #eee;
        }
        .search-input { flex: 1; padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; }
        .filter-select { padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: white; cursor: pointer; }

        /* TABLE CONTAINER */
        .table-container { background-color: #2c3e50; border-radius: 12px; padding: 20px; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .dark-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .dark-table th { text-align: left; padding: 15px; color: #95a5a6; font-size: 11px; text-transform: uppercase; border-bottom: 1px solid #3e5871; }
        .dark-table td { padding: 15px; border-bottom: 1px solid #3e5871; vertical-align: top; }
        
        /* LOG BADGES */
        .log-badge { padding: 4px 10px; border-radius: 4px; font-size: 10px; font-weight: 800; text-transform: uppercase; border: 1px solid; }
        .type-login { background: rgba(52, 152, 219, 0.1); color: #3498db; border-color: #3498db; }
        .type-upload { background: rgba(46, 204, 113, 0.1); color: #2ecc71; border-color: #2ecc71; }
        .type-system { background: rgba(155, 89, 182, 0.1); color: #9b59b6; border-color: #9b59b6; }
        .type-delete { background: rgba(231, 76, 60, 0.1); color: #e74c3c; border-color: #e74c3c; }
        .type-default { background: rgba(149, 165, 166, 0.1); color: #95a5a6; border-color: #95a5a6; }

        .log-message { color: #ecf0f1; font-family: 'Courier New', Courier, monospace; font-size: 12px; line-height: 1.5; }
        .log-timestamp { color: #95a5a6; font-size: 11px; font-weight: 600; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand"><div class="brand-logo"><i class="bi bi-shield-lock"></i></div> MANUHUB</div>
        <a href="index.php?action=admin_dashboard" class="menu-item"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="index.php?action=admin_manuscripts" class="menu-item"><i class="bi bi-journal-text"></i> Oversight</a>
        <a href="index.php?action=admin_users" class="menu-item"><i class="bi bi-people-fill"></i> Users</a>
        <a href="index.php?action=admin_system" class="menu-item active"><i class="bi bi-terminal"></i> Logs</a>
        <a href="index.php" class="menu-item"><i class="bi bi-house"></i> Home Page</a>
        <a href="index.php?action=logout" class="menu-item" style="margin-top: auto; color: #c0392b;"><i class="bi bi-box-arrow-left"></i> Logout</a>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <div>
                <h1 class="page-title">System Activity Logs</h1>
                <div style="font-size: 12px; color: #888; margin-top: 5px;">
                    Auto-cleaning logs older than 90 days. Total records: <?php echo count($data['logs']); ?>
                </div>
            </div>
            <button class="btn-clear-logs" onclick="if(confirm('Permanently clear all system logs? This cannot be undone.')) location.href='index.php?action=admin_clear_logs'">
                <i class="bi bi-trash3-fill"></i> Clear All Logs
            </button>
        </div>

        <div class="filter-bar">
            <i class="bi bi-search" style="color: #999;"></i>
            <input type="text" id="logSearch" class="search-input" placeholder="Search by activity description..." onkeyup="filterLogs()">
            <select id="typeFilter" class="filter-select" onchange="filterLogs()">
                <option value="all">All Event Types</option>
                <option value="LOGIN">Logins</option>
                <option value="UPLOAD">Uploads</option>
                <option value="SYSTEM">System/Auth</option>
                <option value="DELETE">Deletions</option>
                <option value="USER">User Status</option>
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
                        <?php foreach($data['logs'] as $log): 
                            $type = strtoupper($log['action'] ?? 'GENERAL'); 
                            $class = 'type-default';
                            if(strpos($type, 'LOGIN') !== false) $class = 'type-login';
                            elseif(strpos($type, 'UPLOAD') !== false) $class = 'type-upload';
                            elseif(strpos($type, 'SYSTEM') !== false) $class = 'type-system';
                            elseif(strpos($type, 'DELETE') !== false) $class = 'type-delete';
                        ?>
                        <tr class="log-row" data-type="<?php echo $type; ?>">
                            <td class="log-timestamp">
                                <?php echo date('d M Y', strtotime($log['created_at'])); ?><br>
                                <span style="opacity: 0.6; font-weight: normal;"><?php echo date('H:i:s', strtotime($log['created_at'])); ?></span>
                            </td>
                            <td>
                                <span class="log-badge <?php echo $class; ?>"><?php echo $type; ?></span>
                            </td>
                            <td class="log-message">
                                <?php echo htmlspecialchars($log['description'] ?? 'No details available'); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3" align="center" style="padding: 60px; color: #7f8c8d;">
                            <i class="bi bi-terminal" style="font-size: 3rem; display: block; margin-bottom: 10px; opacity: 0.3;"></i>
                            No system logs available.
                        </td></tr>
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
                let rowText = row.innerText.toLowerCase();
                let matchesSearch = rowText.includes(search);
                let matchesType = (type === 'ALL' || rowType.includes(type));
                
                row.style.display = (matchesSearch && matchesType) ? "" : "none";
            });
        }
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - ManuHub Admin</title>
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
        .menu-item.active { background-color: #D1E6E5; color: #004d40; font-weight: 700; }

        /* MAIN CONTENT */
        .main-content { flex: 1; padding: 30px; overflow-y: auto; background-color: #f8f9fa; display: flex; flex-direction: column; gap: 20px; }

        /* HEADER SECTION */
        .header-section { display: flex; flex-direction: column; gap: 15px; }
        .page-title { font-size: 24px; font-weight: 800; color: #222; text-transform: uppercase; letter-spacing: 1px; }
        
        .top-controls { display: flex; justify-content: space-between; align-items: center; }
        .filter-pills { display: flex; gap: 10px; }
        .pill { 
            padding: 8px 18px; border-radius: 20px; border: 1px solid #ddd; background: white; 
            color: #666; font-size: 12px; font-weight: 600; cursor: pointer; transition: 0.2s;
        }
        .pill.active { background: #387c7e; color: white; border-color: #387c7e; }

        .search-input { 
            width: 250px; padding: 10px 15px; border-radius: 20px; 
            border: 1px solid #ddd; font-size: 13px; outline: none;
        }

        /* TABLE STYLING (FROM SCREENSHOT) */
        .table-outer-wrapper {
            background-color: #D1E6E5; padding: 15px; border-radius: 16px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .table-container { background-color: #2c3e50; border-radius: 12px; padding: 20px; color: white; }
        
        .dark-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .dark-table th { text-align: left; padding: 15px; color: #95a5a6; font-size: 11px; text-transform: uppercase; border-bottom: 1px solid #3e5871; }
        .dark-table td { padding: 15px; border-bottom: 1px solid #3e5871; vertical-align: middle; }
        
        /* DATA STYLE */
        .col-id { color: #95a5a6; font-weight: 600; }
        .col-username { font-weight: 700; color: #fff; }
        .col-email { color: #bdc3c7; }

        /* ROLE BADGES (COLORS FROM SCREENSHOT) */
        .badge { padding: 4px 12px; border-radius: 20px; font-size: 9px; font-weight: 800; text-transform: uppercase; }
        .badge-expert { background: #3498db; color: white; }
        .badge-researcher { background: #2ecc71; color: white; }
        .badge-admin { background: #9b59b6; color: white; }

        /* STATUS */
        .status-container { display: flex; align-items: center; gap: 8px; font-size: 12px; }
        .status-dot { width: 8px; height: 8px; border-radius: 50%; }
        .dot-active { background: #2ecc71; box-shadow: 0 0 8px rgba(46, 204, 113, 0.6); }
        .dot-inactive { background: #95a5a6; }

        /* ACTIONS */
        .btn-delete { background: #c0392b; color: white; border: none; padding: 6px 12px; border-radius: 5px; font-size: 11px; font-weight: 700; cursor: pointer; }
        .btn-delete:hover { background: #e74c3c; }
        .protected-text { color: #7f8c8d; font-style: italic; font-size: 11px; }

    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand"><div class="brand-logo"><i class="bi bi-shield-lock"></i></div> MANUHUB</div>
        <a href="index.php?action=admin_dashboard" class="menu-item"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="index.php?action=admin_manuscripts" class="menu-item"><i class="bi bi-journal-text"></i> Oversight</a>
        <a href="index.php?action=admin_users" class="menu-item active"><i class="bi bi-people-fill"></i> User Management</a>
        <a href="index.php?action=admin_system" class="menu-item"><i class="bi bi-terminal"></i> System Logs</a>
        <a href="index.php" class="menu-item"><i class="bi bi-house"></i> Home Page</a>
        <a href="index.php?action=logout" class="menu-item" style="margin-top: auto; color: #c0392b;"><i class="bi bi-box-arrow-left"></i> Logout</a>
    </div>

    <div class="main-content">
        <div class="header-section">
            <h1 class="page-title">User Management</h1>
            <div class="top-controls">
                <div class="filter-pills">
                    <div class="pill active" onclick="filterByRole('all', this)">All Users</div>
                    <div class="pill" onclick="filterByRole('normal_user', this)">Researchers</div>
                    <div class="pill" onclick="filterByRole('expert', this)">Experts</div>
                    <div class="pill" onclick="filterByRole('admin', this)">Admins</div>
                </div>
                <input type="text" id="userSearch" class="search-input" placeholder="Search name or email..." onkeyup="filterUsers()">
            </div>
        </div>

        <div class="table-outer-wrapper">
            <div class="table-container">
                <table class="dark-table" id="userTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($data['users'])): ?>
                            <?php foreach($data['users'] as $u): ?>
                            <tr class="user-row" data-role="<?php echo strtolower($u['role']); ?>">
                                <td class="col-id">#<?php echo $u['user_id']; ?></td>
                                <td class="col-username"><?php echo htmlspecialchars($u['username']); ?></td>
                                <td class="col-email"><?php echo htmlspecialchars($u['email']); ?></td>
                                <td>
                                    <?php 
                                        $roleLabel = strtoupper($u['role']);
                                        $roleClass = 'badge-researcher';
                                        if($u['role'] === 'expert') $roleClass = 'badge-expert';
                                        if($u['role'] === 'admin') $roleClass = 'badge-admin';
                                        if($u['role'] === 'normal_user') $roleLabel = 'RESEARCHER';
                                    ?>
                                    <span class="badge <?php echo $roleClass; ?>"><?php echo $roleLabel; ?></span>
                                </td>
                                <td>
                                    <div class="status-container">
                                        <div class="status-dot <?php echo ($u['status'] === 'active') ? 'dot-active' : 'dot-inactive'; ?>"></div>
                                        <?php echo ucfirst($u['status']); ?>
                                    </div>
                                </td>
                                <td style="color: #95a5a6;"><?php echo date('d M Y', strtotime($u['created_at'] ?? 'now')); ?></td>
                                <td>
                                    <?php if($u['role'] !== 'admin'): ?>
                                        <button class="btn-delete" onclick="if(confirm('Delete user permanently?')) location.href='index.php?action=admin_delete_user&id=<?php echo $u['user_id']; ?>'">Delete</button>
                                    <?php else: ?>
                                        <span class="protected-text">(Protected)</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function filterUsers() {
            let val = document.getElementById("userSearch").value.toLowerCase();
            document.querySelectorAll(".user-row").forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(val) ? "" : "none";
            });
        }

        function filterByRole(role, el) {
            document.querySelectorAll(".pill").forEach(p => p.classList.remove("active"));
            el.classList.add("active");
            document.querySelectorAll(".user-row").forEach(row => {
                let userRole = row.getAttribute("data-role");
                row.style.display = (role === "all" || userRole === role) ? "" : "none";
            });
        }
    </script>
</body>
</html>
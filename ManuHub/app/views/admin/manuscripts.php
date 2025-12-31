<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Oversight - ManuHub Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        /* --- GLOBAL RESET --- */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { display: flex; height: 100vh; background-color: #f8f9fa; overflow: hidden; font-size: 14px; }
        
        /* SIDEBAR */
        .sidebar { width: 250px; min-width: 250px; background-color: #fff; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; padding: 25px; }
        .brand { font-size: 18px; font-weight: 800; margin-bottom: 40px; color: #333; letter-spacing: 0.5px; display: flex; align-items: center; gap: 10px; }
        .brand-logo { width: 32px; height: 32px; background: #333; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 16px;}

        .menu-item { text-decoration: none; color: #666; padding: 12px 15px; margin-bottom: 8px; border-radius: 8px; font-size: 14px; transition: all 0.2s; display: flex; align-items: center; gap: 12px; font-weight: 500; }
        .menu-item:hover { background-color: #f3f4f6; color: #000; }
        .menu-item.active { background-color: #D1E6E5; color: #004d40; font-weight: 700; }

        /* MAIN CONTENT */
        .main-content { flex: 1; padding: 30px; overflow-y: auto; background-color: #f8f9fa; display: flex; flex-direction: column; gap: 20px; position: relative; }

        /* TOP BAR */
        .top-bar { display: flex; justify-content: space-between; align-items: center; }
        .page-title { font-size: 24px; font-weight: 700; color: #222; }

        /* TABS NAVIGATION */
        .tabs-container { display: flex; gap: 10px; margin-bottom: 5px; }
        .tab-btn {
            padding: 10px 25px; border-radius: 10px 10px 0 0; border: none;
            background: #eee; color: #777; font-weight: 600; cursor: pointer; transition: 0.2s;
        }
        .tab-btn.active { background: #2c3e50; color: white; }

        /* FILTER BAR */
        .filter-bar {
            background: white; padding: 15px 20px; border-radius: 0 12px 12px 12px;
            display: flex; gap: 15px; align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03); border: 1px solid #eee;
        }
        .search-input { flex: 1; padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; }
        .search-input:focus { outline: none; border-color: #26c6da; }
        .filter-select { padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: white; cursor: pointer; }

        /* TABLE CONTAINER */
        .table-container {
            background-color: #2c3e50; border-radius: 12px;
            padding: 20px; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 80px;
        }
        .dark-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .dark-table th { text-align: left; padding: 15px; color: #95a5a6; font-size: 11px; text-transform: uppercase; border-bottom: 1px solid #3e5871; }
        .dark-table td { padding: 15px; border-bottom: 1px solid #3e5871; color: #ecf0f1; vertical-align: top; }
        
        /* BADGES */
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 800; text-transform: uppercase; }
        .badge-pending { background: #f39c12; color: white; }
        .badge-approved { background: #27ae60; color: white; }
        .badge-rejected { background: #c0392b; color: white; }

        /* ACTIONS */
        .btn-view { background: #26c6da; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 12px; margin-right: 5px; }
        .btn-delete { background: rgba(192, 57, 43, 0.2); color: #e74c3c; border: 1px solid #c0392b; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 12px; }
        .btn-delete:hover { background: #c0392b; color: white; }

        /* MODAL STYLES */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); display: none; justify-content: center; align-items: center; z-index: 1000; backdrop-filter: blur(4px); }
        .modal-box { background: white; width: 600px; max-width: 90%; padding: 30px; border-radius: 16px; position: relative; max-height: 90vh; overflow-y: auto; color: #333; }
        .modal-header { font-size: 20px; font-weight: 800; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        .modal-row { display: grid; grid-template-columns: 140px 1fr; margin-bottom: 12px; }
        .modal-label { font-weight: 600; color: #888; font-size: 11px; text-transform: uppercase; }
        .modal-close { position: absolute; top: 20px; right: 25px; font-size: 24px; cursor: pointer; color: #ccc; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand"><div class="brand-logo"><i class="bi bi-shield-lock"></i></div> MANUHUB</div>
        <a href="index.php?action=admin_dashboard" class="menu-item"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="index.php?action=admin_manuscripts" class="menu-item active"><i class="bi bi-journal-text"></i> Oversight</a>
        <a href="index.php?action=admin_users" class="menu-item"><i class="bi bi-people-fill"></i> User Management</a>
        <a href="index.php?action=admin_system" class="menu-item"><i class="bi bi-terminal"></i> System Logs</a>
        <a href="index.php" class="menu-item"><i class="bi bi-house"></i> Home Page</a>
        <a href="index.php?action=logout" class="menu-item" style="margin-top: auto; color: #c0392b;"><i class="bi bi-box-arrow-left"></i> Logout</a>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <div class="page-title">System Oversight</div>
        </div>

        <div class="tabs-container">
            <button class="tab-btn active" id="tab-ms" onclick="switchTab('manuscripts')">Manuscripts</button>
            <button class="tab-btn" id="tab-rw" onclick="switchTab('related')">Related Sources</button>
        </div>

        <div class="filter-bar">
            <i class="bi bi-search" style="color: #999;"></i>
            <input type="text" id="adminSearch" class="search-input" placeholder="Search by title or submitter..." onkeyup="filterAdminTable()">
            <select id="statusFilter" class="filter-select" onchange="filterAdminTable()">
                <option value="all">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>

        <div id="manuscriptSection" class="table-container">
            <table class="dark-table" id="msTable">
                <thead>
                    <tr>
                        <th>Submitter</th>
                        <th>Manuscript Title</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['submissions'])): ?>
                        <?php foreach($data['submissions'] as $row): ?>
                        <tr class="admin-row" data-status="<?php echo strtolower($row['status']); ?>" 
                            data-title="<?php echo htmlspecialchars($row['Title']); ?>"
                            data-author="<?php echo htmlspecialchars($row['Author'] ?? 'Unknown'); ?>"
                            data-loc="<?php echo htmlspecialchars($row['Location_of_Manuscript'] ?? '-'); ?>"
                            data-desc="<?php echo htmlspecialchars($row['Description'] ?? '-'); ?>">
                            <td><?php echo htmlspecialchars($row['username'] ?? 'User'); ?></td>
                            <td><?php echo htmlspecialchars($row['Title']); ?></td>
                            <td><span class="badge badge-<?php echo strtolower($row['status']); ?>"><?php echo $row['status']; ?></span></td>
                            <td>
                                <button type="button" class="btn-view" onclick="openAdminModal(this)">Details</button>
                                <button class="btn-delete" 
        onclick="if(confirm('Delete permanently from system and search?')) 
        location.href='index.php?action=admin_delete_submission&id=<?php echo $row['id']; ?>'">
    <i class="bi bi-trash"></i>
</button>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

       <div id="relatedSection" class="table-container" style="display: none;">
            <table class="dark-table" id="rwTable">
                <thead>
                    <tr>
                        <th>Submitter</th>
                        <th>Source Title</th>
                        <th>Linked To</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['related_works'])): ?>
                        <?php foreach($data['related_works'] as $rw): ?>
                        <tr class="admin-row" 
                            data-status="<?php echo strtolower($rw['status']); ?>"
                            data-title="<?php echo htmlspecialchars($rw['title']); ?>"
                            data-url="<?php echo htmlspecialchars($rw['url']); ?>"
                            data-desc="<?php echo htmlspecialchars($rw['description'] ?? 'No additional description provided.'); ?>"
                            data-linked="<?php echo htmlspecialchars($rw['manuscript_title'] ?? 'ID: '.$rw['manuscript_id']); ?>">
                            
                            <td><?php echo htmlspecialchars($rw['username'] ?? 'User'); ?></td>
                            <td><?php echo htmlspecialchars($rw['title']); ?></td>
                            <td style="opacity: 0.7;"><?php echo htmlspecialchars($rw['manuscript_title'] ?? 'ID: '.$rw['manuscript_id']); ?></td>
                            <td><span class="badge badge-<?php echo strtolower($rw['status']); ?>"><?php echo $rw['status']; ?></span></td>
                            <td>
                                <button type="button" class="btn-view" onclick="openAdminSourceModal(this)">Details</button>
                                <button class="btn-delete" onclick="if(confirm('Delete permanently?')) location.href='index.php?action=admin_delete_rw&id=<?php echo $rw['id']; ?>'">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    <div class="modal-overlay" id="adminModal">
        <div class="modal-box">
            <span class="modal-close" onclick="closeAdminModal()">&times;</span>
            <div class="modal-header">Manuscript Overview</div>
            <div id="adminModalContent"></div>
        </div>
    </div>

    <script>
        function switchTab(type) {
            document.getElementById('manuscriptSection').style.display = (type === 'manuscripts') ? 'block' : 'none';
            document.getElementById('relatedSection').style.display = (type === 'related') ? 'block' : 'none';
            document.getElementById('tab-ms').classList.toggle('active', type === 'manuscripts');
            document.getElementById('tab-rw').classList.toggle('active', type === 'related');
            filterAdminTable();
        }

        function filterAdminTable() {
            let search = document.getElementById("adminSearch").value.toLowerCase();
            let status = document.getElementById("statusFilter").value.toLowerCase();
            let visibleTable = document.querySelector('.table-container[style*="block"]') || document.getElementById('manuscriptSection');
            let rows = visibleTable.querySelectorAll(".admin-row");

            rows.forEach(row => {
                let rowStatus = row.getAttribute('data-status');
                let matchesSearch = row.innerText.toLowerCase().includes(search);
                let matchesStatus = (status === 'all' || rowStatus === status);
                row.style.display = (matchesSearch && matchesStatus) ? "" : "none";
            });
        }

        function openAdminModal(btn) {
            let row = btn.closest('tr');
            let html = `
                <div class="modal-row"><div class="modal-label">Title</div><div>${row.getAttribute('data-title')}</div></div>
                <div class="modal-row"><div class="modal-label">Author</div><div>${row.getAttribute('data-author')}</div></div>
                <div class="modal-row"><div class="modal-label">Location</div><div>${row.getAttribute('data-loc')}</div></div>
                <div style="margin-top:15px; background:#f9f9f9; padding:15px; border-radius:8px;">
                    <div class="modal-label">Description</div>
                    <div style="font-size:13px; margin-top:5px;">${row.getAttribute('data-desc')}</div>
                </div>`;
            document.getElementById('adminModalContent').innerHTML = html;
            document.getElementById('adminModal').style.display = 'flex';
        }

        // NEW: MODAL FOR RELATED WORKS
        function openAdminSourceModal(btn) {
            let row = btn.closest('tr');
            let html = `
                <div class="modal-row"><div class="modal-label">Source Title</div><div style="font-weight:700;">${row.getAttribute('data-title')}</div></div>
                <div class="modal-row"><div class="modal-label">Reference URL</div><div><a href="${row.getAttribute('data-url')}" target="_blank" style="color:#26c6da;">${row.getAttribute('data-url')}</a></div></div>
                <div class="modal-row"><div class="modal-label">Connected To</div><div>${row.getAttribute('data-linked')}</div></div>
                <div style="margin-top:15px; background:#f9f9f9; padding:15px; border-radius:8px;">
                    <div class="modal-label">Admin/User Notes</div>
                    <div style="font-size:13px; margin-top:5px; color:#555;">${row.getAttribute('data-desc')}</div>
                </div>`;
            document.getElementById('adminModalContent').innerHTML = html;
            document.querySelector('.modal-header').innerText = "Source Overview";
            document.getElementById('adminModal').style.display = 'flex';
        }

        function closeAdminModal() { document.getElementById('adminModal').style.display = 'none'; }
    </script>
</body>
</html>
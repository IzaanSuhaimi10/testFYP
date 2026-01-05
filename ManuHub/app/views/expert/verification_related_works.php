<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Sources - ManuHub Expert</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        /* --- GLOBAL RESET (MATCHING MANUSCRIPTS) --- */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { display: flex; height: 100vh; background-color: #f8f9fa; overflow: hidden; font-size: 14px; }
        
        /* SIDEBAR */
        .sidebar { width: 250px; min-width: 250px; background-color: #fff; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; padding: 25px; }
        .brand { font-size: 18px; font-weight: 800; margin-bottom: 40px; color: #333; letter-spacing: 0.5px; display: flex; align-items: center; gap: 10px; }
        .brand-logo { width: 32px; height: 32px; background: #26c6da; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 16px;}

        .menu-item { text-decoration: none; color: #666; padding: 12px 15px; margin-bottom: 8px; border-radius: 8px; font-size: 14px; transition: all 0.2s; display: flex; align-items: center; gap: 12px; font-weight: 500; }
        .menu-item:hover { background-color: #f0fbfc; color: #00838f; }
        .menu-item.active { background-color: #e0f7fa; color: #006064; font-weight: 700; }
        .menu-item i { font-size: 18px; }

        /* MAIN CONTENT */
        .main-content { flex: 1; padding: 30px; overflow-y: auto; background-color: #f8f9fa; display: flex; flex-direction: column; gap: 20px; position: relative; }

        /* HEADER */
        .top-bar { display: flex; justify-content: space-between; align-items: center; }
        .page-title { font-size: 24px; font-weight: 700; color: #222; }
        .admin-profile { display: flex; align-items: center; gap: 12px; background: #fff; padding: 6px 15px; border-radius: 30px; border: 1px solid #eee; }
        .admin-avatar { width: 32px; height: 32px; background-color: #26c6da; border-radius: 50%; }

        /* FILTER BAR */
        .filter-bar {
            background: white; padding: 15px 20px; border-radius: 12px;
            display: flex; gap: 15px; align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03); border: 1px solid #eee;
        }
        .search-input {
            flex: 1; padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;
        }
        .search-input:focus { outline: none; border-color: #26c6da; }
        
        .filter-select {
            padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: white; cursor: pointer;
        }

        /* TABLE STYLING */
        .table-container {
            background-color: #2c3e50; border-radius: 12px;
            padding: 20px; color: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            flex: 1; display: flex; flex-direction: column;
        }

        .dark-table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .dark-table th { text-align: left; padding: 15px; color: #95a5a6; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #3e5871; }
        .dark-table td { padding: 15px; border-bottom: 1px solid #3e5871; color: #ecf0f1; vertical-align: top; }
        .dark-table tr:hover { background: rgba(255,255,255,0.05); }

        .status-select {
            padding: 6px 10px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 12px; width: 100%;
        }
        .status-select.pending { background: #fffbe6; color: #d48806; }
        .status-select.approved { background: #f6ffed; color: #389e0d; }
        .status-select.rejected { background: #fff1f0; color: #cf1322; }

        /* ACTION BUTTONS */
        .link-btn {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(46, 204, 113, 0.15); color: #2ecc71;
            padding: 6px 12px; border-radius: 6px; text-decoration: none;
            font-size: 11px; font-weight: 600; margin-top: 5px; transition: 0.2s; border: 1px solid rgba(46, 204, 113, 0.2);
        }
        .link-btn:hover { background: rgba(46, 204, 113, 0.25); color: white; }

        .btn-view-details {
            background: #26c6da; color: white; border: none;
            padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 600;
            cursor: pointer; margin-top: 5px; margin-right: 5px; display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-view-details:hover { background: #00acc1; }

        /* FLOATING SAVE BUTTON */
        .btn-save-float {
            position: fixed; bottom: 30px; right: 30px;
            background: #26c6da; color: white; border: none;
            padding: 15px 30px; border-radius: 50px;
            font-size: 16px; font-weight: 700; cursor: pointer;
            box-shadow: 0 4px 20px rgba(38, 198, 218, 0.4);
            display: flex; align-items: center; gap: 10px;
            transition: transform 0.2s;
            z-index: 100;
        }
        .btn-save-float:hover { transform: translateY(-3px); background: #00acc1; }

        .alert-box {
            background: #e0f7fa; color: #006064; padding: 15px; border-radius: 8px; border: 1px solid #b2ebf2; margin-bottom: 10px;
            display: flex; align-items: center; gap: 10px;
        }

        /* --- MODAL STYLES --- */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.6); display: none; justify-content: center; align-items: center; z-index: 1000;
            backdrop-filter: blur(4px);
        }
        .modal-box {
            background: white; width: 550px; max-width: 90%; padding: 30px;
            border-radius: 16px; box-shadow: 0 20px 50px rgba(0,0,0,0.3); position: relative;
            animation: slideUp 0.3s ease;
        }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        .modal-header { font-size: 20px; font-weight: 800; margin-bottom: 20px; color: #333; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        .modal-row { display: grid; grid-template-columns: 120px 1fr; margin-bottom: 12px; font-size: 14px; }
        .modal-label { font-weight: 600; color: #888; font-size: 12px; text-transform: uppercase; padding-top: 3px; }
        .modal-value { color: #333; line-height: 1.5; font-weight: 500; word-break: break-all; }
        
        .modal-close {
            position: absolute; top: 20px; right: 25px; font-size: 24px; cursor: pointer; color: #ccc; transition: 0.2s;
        }
        .modal-close:hover { color: #333; }

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


     <a href="index.php?action=expert_verification_users" 
       class="menu-item <?php echo ($current_action == 'expert_verification_users') ? 'active' : ''; ?>">
        <i class="bi bi-people-fill"></i> Researcher IDs
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
        
        <div class="top-bar">
            <div class="page-title">Sources Evaluation</div>
            <div class="admin-profile">
                <div style="text-align: right; line-height: 1.2;">
                    <div style="font-size: 10px; color: #666;">Welcome back</div>
                    <div style="font-size: 13px; font-weight: bold;"><?php echo htmlspecialchars($_SESSION['username'] ?? 'Expert'); ?></div>
                </div>
                <div class="admin-avatar"></div>
            </div>
        </div>

        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'saved'): ?>
            <div class="alert-box">
                <i class="bi bi-check-circle-fill"></i> Changes saved successfully!
            </div>
        <?php endif; ?>

        <div class="filter-bar">
            <i class="bi bi-search" style="color: #999;"></i>
            <input type="text" id="searchInput" class="search-input" placeholder="Search by title, user, or linked manuscript..." onkeyup="filterTable()">
            <div style="border-left: 1px solid #eee; height: 30px;"></div>
            <select id="statusFilter" class="filter-select" onchange="filterTable()">
                <option value="all">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>

        <form method="POST" action="index.php?action=expert_verification_related" style="display: flex; flex-direction: column; flex: 1;">
            
            <div class="table-container">
                <table class="dark-table" id="evaluationTable">
                    <thead>
                        <tr>
                            <th width="20%">Submitted By</th>
                            <th width="35%">Source Details</th>
                            <th width="30%">Linked To</th>
                            <th width="15%">Decision</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['list'])): ?>
                            <?php foreach ($data['list'] as $row): ?>
                            
                            <tr class="item-row" data-status="<?php echo strtolower($row['status']); ?>"
                                data-title="<?php echo htmlspecialchars($row['title']); ?>"
                                data-url="<?php echo htmlspecialchars($row['url']); ?>"
                                data-desc="<?php echo htmlspecialchars($row['description'] ?? 'No description.'); ?>"
                                data-linked="<?php echo htmlspecialchars($row['manuscript_title'] ?? 'ID: '.$row['manuscript_id']); ?>"
                                data-user="<?php echo htmlspecialchars($row['username'] ?? 'Unknown'); ?>"
                            >
                                
                                <td>
                                    <div style="font-weight: 700; margin-bottom: 2px;"><?php echo htmlspecialchars($row['username'] ?? 'Unknown'); ?></div>
                                    <div style="font-size: 11px; color: #95a5a6;"><?php echo date('d M Y', strtotime($row['created_at'])); ?></div>
                                </td>

                                <td>
                                    <div style="font-size: 15px; font-weight: 600; color: #fff; margin-bottom: 6px;">
                                        <?php echo htmlspecialchars($row['title']); ?>
                                    </div>
                                    
                                    <div style="display: flex; flex-wrap: wrap;">
                                        <button type="button" class="btn-view-details" onclick="openModal(this)">
                                            <i class="bi bi-eye-fill"></i> Details
                                        </button>

                                        <a href="<?php echo htmlspecialchars($row['url']); ?>" target="_blank" class="link-btn">
                                            <i class="bi bi-box-arrow-up-right"></i> Visit Link
                                        </a>
                                    </div>
                                </td>

                                <td style="color: #bdc3c7; font-size: 13px;">
                                    <span style="font-size: 10px; text-transform: uppercase; color: #7f8c8d; font-weight: 700;">Connected To:</span><br>
                                    <i class="bi bi-link-45deg"></i> 
                                    <?php echo htmlspecialchars($row['manuscript_title'] ?? 'Manuscript ID: '.$row['manuscript_id']); ?>
                                </td>
                                
                                <td>
                                    <select name="status[<?php echo $row['id']; ?>]" class="status-select <?php echo $row['status']; ?>" onchange="updateColor(this)">
                                        <option value="pending" <?php if($row['status']=='pending') echo 'selected'; ?>>Pending</option>
                                        <option value="approved" <?php if($row['status']=='approved') echo 'selected'; ?>>Approved</option>
                                        <option value="rejected" <?php if($row['status']=='rejected') echo 'selected'; ?>>Rejected</option>
                                    </select>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" style="text-align:center; padding: 40px; color: #7f8c8d;">No sources found for evaluation.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn-save-float">
                <i class="bi bi-save-fill"></i> Save Changes
            </button>
        
        </form>
    </div>

    <div class="modal-overlay" id="infoModal">
        <div class="modal-box">
            <span class="modal-close" onclick="closeModal()">&times;</span>
            <div class="modal-header">Source Details</div>
            
            <div id="modalContent">
                </div>
        </div>
    </div>

    <script>
        // 1. OPEN MODAL LOGIC
        function openModal(btn) {
            var row = btn.closest('tr');

            // Retrieve data
            var title = row.getAttribute('data-title');
            var url = row.getAttribute('data-url');
            var desc = row.getAttribute('data-desc');
            var linked = row.getAttribute('data-linked');
            var user = row.getAttribute('data-user');

            var html = '';
            html += `<div class="modal-row"><div class="modal-label">Source Title</div><div class="modal-value">${title}</div></div>`;
            html += `<div class="modal-row"><div class="modal-label">URL</div><div class="modal-value"><a href="${url}" target="_blank" style="color:#26c6da;">${url}</a></div></div>`;
            html += `<div class="modal-row"><div class="modal-label">Linked To</div><div class="modal-value">${linked}</div></div>`;
            html += `<div class="modal-row"><div class="modal-label">Submitted By</div><div class="modal-value">${user}</div></div>`;
            
            html += `<div style="margin-top:15px; border-top:1px solid #eee; padding-top:10px;">
                        <div class="modal-label" style="margin-bottom:5px;">Description / Notes</div>
                        <div class="modal-value" style="font-size:13px; color:#555;">${desc}</div>
                     </div>`;

            document.getElementById('modalContent').innerHTML = html;
            document.getElementById('infoModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('infoModal').style.display = 'none';
        }

        window.onclick = function(event) {
            var modal = document.getElementById('infoModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // 2. SEARCH & COLOR LOGIC
        function updateColor(select) {
            select.className = 'status-select ' + select.value;
        }

        function filterTable() {
            var input = document.getElementById('searchInput').value.toLowerCase();
            var status = document.getElementById('statusFilter').value.toLowerCase();
            var rows = document.querySelectorAll('#evaluationTable tbody tr.item-row');

            rows.forEach(function(row) {
                var text = row.innerText.toLowerCase();
                var rowStatus = row.getAttribute('data-status');
                var matchesSearch = text.includes(input);
                var matchesStatus = (status === 'all') || (rowStatus === status);

                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>

</body>
</html>
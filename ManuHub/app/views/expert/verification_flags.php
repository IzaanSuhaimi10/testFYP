<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Flags - ManuHub Expert</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        /* --- GLOBAL RESET (MATCHING MANUSCRIPTS & SUGGESTIONS) --- */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { display: flex; height: 100vh; background-color: #f8f9fa; overflow: hidden; font-size: 14px; }
        
        /* SIDEBAR */
        .sidebar { width: 250px; min-width: 250px; background-color: #fff; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; padding: 25px; }
        .brand { font-size: 18px; font-weight: 800; margin-bottom: 40px; color: #333; letter-spacing: 0.5px; display: flex; align-items: center; gap: 10px; }
        .brand-logo { width: 32px; height: 32px; background: #26c6da; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 16px;}

        .menu-item { text-decoration: none; color: #666; padding: 12px 15px; margin-bottom: 8px; border-radius: 8px; font-size: 14px; transition: all 0.2s; display: flex; align-items: center; gap: 12px; font-weight: 500; }
        .menu-item:hover { background-color: #f0fbfc; color: #00838f; }
        .menu-item.active { background-color: #e0f7fa; color: #006064; font-weight: 700; }

        /* MAIN CONTENT */
        .main-content { flex: 1; padding: 30px; overflow-y: auto; background-color: #f8f9fa; display: flex; flex-direction: column; gap: 20px; position: relative; }

        /* TOP BAR */
        .top-bar { display: flex; justify-content: space-between; align-items: center; }
        .page-title { font-size: 24px; font-weight: 700; color: #222; }

        /* TABLE STYLING */
        .table-container {
            background-color: #2c3e50; border-radius: 12px;
            padding: 20px; color: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            flex: 1; display: flex; flex-direction: column;
        }

        .dark-table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .dark-table th { text-align: left; padding: 15px; color: #95a5a6; font-size: 12px; text-transform: uppercase; border-bottom: 1px solid #3e5871; }
        .dark-table td { padding: 15px; border-bottom: 1px solid #3e5871; color: #ecf0f1; vertical-align: top; }

        .status-select {
            padding: 6px 10px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 12px; width: 100%;
        }
        .status-select.pending { background: #fffbe6; color: #d48806; }
        .status-select.resolved { background: #fff1f0; color: #cf1322; } /* Red for deletion/action */
        .status-select.dismissed { background: #f6ffed; color: #389e0d; } /* Green for keeping it */

        /* FLAG SPECIFIC */
        .reason-box {
            background: rgba(231, 76, 60, 0.15);
            padding: 12px;
            border-radius: 8px;
            border-left: 4px solid #e74c3c;
            margin-top: 5px;
            font-style: italic;
            color: #ecf0f1;
        }
        .work-link {
            color: #26c6da;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
        }
        .work-link:hover { text-decoration: underline; }

        /* FLOATING SAVE BUTTON */
        .btn-save-float {
            position: fixed; bottom: 30px; right: 30px;
            background: #26c6da; color: white; border: none;
            padding: 15px 30px; border-radius: 50px;
            font-size: 16px; font-weight: 700; cursor: pointer;
            box-shadow: 0 4px 20px rgba(38, 198, 218, 0.4);
            display: flex; align-items: center; gap: 10px;
            z-index: 100;
        }
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
    <div class="top-bar">
        <div class="page-title">Content Moderation (Flags)</div>
    </div>

    <div style="background: white; padding: 15px 20px; border-radius: 12px; display: flex; gap: 15px; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.03); border: 1px solid #eee; margin-bottom: 20px;">
        <i class="bi bi-search" style="color: #999;"></i>
        <input type="text" id="flagSearch" style="flex: 1; padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;" placeholder="Search by reporter, item title, or reason..." onkeyup="filterFlags()">
        
        <div style="border-left: 1px solid #eee; height: 30px;"></div>
        
        <select id="statusFilter" style="padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: white; cursor: pointer;" onchange="filterFlags()">
            <option value="all">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="resolved">Remove Item</option>
            <option value="dismissed">Dismissed</option>
        </select>
    </div>

    <form method="POST" action="index.php?action=expert_verification_flags">
        <div class="table-container" style="min-height: 450px;"> <table class="dark-table" id="flagsTable">
                <thead>
                    <tr>
                        <th width="20%">Reporter</th>
                        <th width="30%">Reported Content</th>
                        <th width="35%">Reason for Flag</th>
                        <th width="15%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['list'])): ?>
                        <?php foreach ($data['list'] as $row): ?>
                        <tr class="item-row" data-status="<?php echo strtolower($row['status']); ?>">
                            <td>
                                <div style="font-weight: 700;"><?php echo htmlspecialchars($row['username'] ?? 'Anonymous User'); ?></div>
                                <div style="font-size: 11px; color: #95a5a6;"><?php echo date('d M Y', strtotime($row['created_at'])); ?></div>
                            </td>
                            <td>
                                <div style="font-size: 11px; text-transform: uppercase; color: #95a5a6;">Reported Item:</div>
                                <div style="font-weight: 600; color: #fff; margin-bottom: 4px;">
                                    <?php echo htmlspecialchars($row['work_title'] ?? 'N/A'); ?>
                                </div>
                                <a href="<?php echo htmlspecialchars($row['work_url'] ?? '#'); ?>" target="_blank" class="work-link">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> Visit Source
                                </a>
                            </td>
                            <td>
                                <div style="font-size: 11px; text-transform: uppercase; color: #95a5a6; margin-bottom: 5px;">User Feedback:</div>
                                <div class="reason-box">"<?php echo htmlspecialchars($row['reason']); ?>"</div>
                            </td>
                            <td>
                                <select name="status[<?php echo $row['id']; ?>]" class="status-select <?php echo $row['status']; ?>" onchange="updateColor(this)">
                                    <option value="pending" <?php if($row['status']=='pending') echo 'selected'; ?>>Pending</option>
                                    <option value="resolved" <?php if($row['status']=='resolved' || $row['status']=='reviewed') echo 'selected'; ?>>Remove Item</option>
                                    <option value="dismissed" <?php if($row['status']=='dismissed') echo 'selected'; ?>>Dismiss Flag</option>
                                </select>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                        <tr id="noResultsRow" style="display: none;">
                            <td colspan="4" style="text-align:center; padding: 60px; color: #7f8c8d;">
                                <i class="bi bi-flag" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                                No moderation reports match your current filter.
                            </td>
                        </tr>
                    <?php else: ?>
                        <tr><td colspan="4" style="text-align:center; padding: 40px; color: #7f8c8d;">No pending reports found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <button type="submit" class="btn-save-float">
            <i class="bi bi-shield-check"></i> Process Reports
        </button>
    </form>
</div>

    <script>
function filterFlags() {
    var input = document.getElementById('flagSearch').value.toLowerCase();
    var status = document.getElementById('statusFilter').value.toLowerCase();
    var rows = document.querySelectorAll('.item-row');
    var noResults = document.getElementById('noResultsRow');
    var visibleCount = 0;

    rows.forEach(function(row) {
        var text = row.innerText.toLowerCase();
        var rowStatus = row.getAttribute('data-status');
        
        var matchesSearch = text.includes(input);
        
        // Match 'resolved' or 'reviewed' for the 'Remove Item' filter option
        var normalizedStatus = (rowStatus === 'reviewed') ? 'resolved' : rowStatus;
        var matchesStatus = (status === 'all') || (normalizedStatus === status);

        if (matchesSearch && matchesStatus) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    if (noResults) {
        noResults.style.display = (visibleCount === 0) ? '' : 'none';
    }
}

function updateColor(select) {
    select.className = 'status-select ' + select.value;
}
</script>
</body>
</html>
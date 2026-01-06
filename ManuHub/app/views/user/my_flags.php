<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Flags - ManuHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        /* --- GLOBAL RESET --- */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { display: flex; height: 100vh; background-color: #f8f9fa; overflow: hidden; font-size: 14px; }
        
        /* SIDEBAR */
        .sidebar { width: 250px; min-width: 250px; background-color: #fff; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; padding: 25px; }
        .brand { font-size: 18px; font-weight: 800; margin-bottom: 40px; color: #333; letter-spacing: 0.5px; display: flex; align-items: center; gap: 10px; }
        .brand-logo { width: 32px; height: 32px; background: #e67e22; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 16px;}
        .menu-item { text-decoration: none; color: #666; padding: 12px 15px; margin-bottom: 8px; border-radius: 8px; font-size: 14px; transition: all 0.2s; display: flex; align-items: center; gap: 12px; font-weight: 500; }
        .menu-item:hover { background-color: #fff7ed; color: #e67e22; }
        .menu-item.active { background-color: #ffedd5; color: #c2410c; font-weight: 700; }

        /* MAIN CONTENT */
        .main-content { flex: 1; padding: 30px; overflow-y: auto; background-color: #f8f9fa; display: flex; flex-direction: column; gap: 20px; }
        .page-title { font-size: 24px; font-weight: 700; color: #222; }

        /* FILTER BAR */
        .filter-bar { background: white; padding: 15px 20px; border-radius: 12px; display: flex; gap: 15px; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.03); border: 1px solid #eee; }
        .search-input { flex: 1; padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; }
        .filter-select { padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: white; cursor: pointer; }

        /* TABLE CARD */
        .card { background: white; border-radius: 16px; padding: 0; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid #eee; overflow: hidden; }
        .user-table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .user-table th { text-align: left; padding: 18px 20px; background: #fafafa; color: #95a5a6; font-size: 11px; text-transform: uppercase; border-bottom: 1px solid #eee; }
        .user-table td { padding: 18px 20px; border-bottom: 1px solid #f9f9f9; color: #333; }

        /* STATUS BADGES */
        .status-badge { font-weight: 700; font-size: 10px; text-transform: uppercase; padding: 5px 12px; border-radius: 20px; display: inline-block; }
        .status-pending { background: #fffbe6; color: #d48806; border: 1px solid #ffe58f; }
        .status-resolved { background: #f6ffed; color: #389e0d; border: 1px solid #b7eb8f; }
        .status-dismissed { background: #fff1f0; color: #cf1322; border: 1px solid #ffa39e; }
        
        .report-reason { background: #fdf2f2; border-left: 3px solid #ef4444; padding: 10px; border-radius: 4px; font-size: 12px; color: #b91c1c; margin-top: 5px; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="brand">
        <div class="brand-logo"><i class="bi bi-book-half"></i></div>
        MANUHUB
    </div>
    
    <?php $current_action = $_GET['action'] ?? ''; ?>
    <a href="index.php?action=user_dashboard" class="menu-item">
        <i class="bi bi-grid-fill"></i> Dashboard
    </a>
    <a href="index.php?action=my_manuscripts" class="menu-item">
        <i class="bi bi-file-earmark-text"></i> My Manuscripts
    </a>
    <a href="index.php?action=my_suggestions" class="menu-item">
        <i class="bi bi-pencil-square"></i> My Edits
    </a>
    <a href="index.php?action=my_sources" class="menu-item">
        <i class="bi bi-link-45deg"></i> My Sources
    </a>
    <a href="index.php?action=my_flags" class="menu-item active">
        <i class="bi bi-flag-fill"></i> My Flags
    </a>
    <div style="border-top: 1px solid #eee; margin: 15px 0;"></div>
    <a href="index.php?action=submit_manuscript" class="menu-item">
        <i class="bi bi-plus-square-fill"></i> Submit New
    </a>
    <a href="index.php?action=user_edit_profile" class="menu-item">
        <i class="bi bi-person-circle"></i> My Profile
    </a>
    <a href="index.php" class="menu-item"><i class="bi bi-house"></i> Home Page</a>
    <a href="index.php?action=logout" class="menu-item" style="margin-top: auto; color: #c0392b;"><i class="bi bi-box-arrow-left"></i> Logout</a>
</div>

<div class="main-content">
    <div class="page-header">
        <h2 class="page-title">My Content Reports</h2>
    </div>

    <div class="filter-bar">
        <i class="bi bi-search" style="color: #999;"></i>
        <input type="text" id="searchInput" class="search-input" placeholder="Search reports..." onkeyup="filterUserTable()">
        <div style="border-left: 1px solid #eee; height: 30px;"></div>
        <select id="statusFilter" class="filter-select" onchange="filterUserTable()">
            <option value="all">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="resolved">Action Taken</option>
            <option value="dismissed">Dismissed</option>
        </select>
    </div>

    <div class="card">
        <table class="user-table" id="flagsTable">
            <thead>
                <tr>
                    <th width="30%">Reported Item</th>
                    <th width="40%">Reason for Flag</th>
                    <th width="15%">Status</th>
                    <th width="15%" style="text-align: right;">Date Reported</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data['list'])): ?>
                    <?php foreach ($data['list'] as $row): ?>
                        <tr class="item-row" 
                                data-status="<?php echo strtolower($row['status']); ?>" 
                                data-type="<?php echo htmlspecialchars($row['target_type'] ?? 'related_work'); ?>">
                            <td>
    <div style="font-weight: 700; color: #333; margin-bottom: 4px;">
        <?php echo htmlspecialchars($row['work_title'] ?? 'Unknown Work'); ?>
    </div>
    
    <?php 
        $type = strtolower($row['target_type'] ?? 'related_work'); 
        $isCitation = ($type === 'citation');
        // Matching Expert Dashboard colors for consistency
        $badgeBg = $isCitation ? '#f3e8ff' : '#e0f2fe'; 
        $badgeText = $isCitation ? '#9b59b6' : '#3498db'; 
        $label = $isCitation ? 'Citation' : 'Related Source';
    ?>
    <span style="font-size: 9px; font-weight: 800; text-transform: uppercase; padding: 2px 8px; border-radius: 4px; background: <?php echo $badgeBg; ?>; color: <?php echo $badgeText; ?>;">
        <?php echo $label; ?>
    </span>
</td>
                            <td>
                                <div class="report-reason">
                                    <strong>Flag:</strong> <?php echo htmlspecialchars($row['reason']); ?>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($row['status']); ?>">
                                    <?php 
                                        if($row['status'] == 'resolved') echo 'Action Taken';
                                        elseif($row['status'] == 'dismissed') echo 'Dismissed';
                                        else echo 'Pending';
                                    ?>
                                </span>
                            </td>
                            <td style="text-align: right; color: #999; font-size: 12px;">
                                <?php echo date('d M Y', strtotime($row['created_at'])); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr id="emptyRow"><td colspan="4" style="text-align: center; padding: 60px; color: #999;">No content reported yet.</td></tr>
                <?php endif; ?>
                <tr id="noResultsRow" style="display: none;"><td colspan="4" style="text-align: center; padding: 40px; color: #999;">No results match your filter.</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
function filterUserTable() {
    var input = document.getElementById('searchInput').value.toLowerCase();
    var statusFilter = document.getElementById('statusFilter').value.toLowerCase();
    var rows = document.querySelectorAll('.item-row');
    var visibleCount = 0;

    rows.forEach(function(row) {
        var rowText = row.innerText.toLowerCase();
        var rowStatus = row.getAttribute('data-status').toLowerCase();
        var matchesSearch = rowText.includes(input);
        var matchesStatus = (statusFilter === 'all') || (rowStatus === statusFilter);

        if (matchesSearch && matchesStatus) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    var noResultsRow = document.getElementById('noResultsRow');
    if (noResultsRow) {
        noResultsRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
    }
}
</script>

</body>
</html>
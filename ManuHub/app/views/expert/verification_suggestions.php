<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Suggestions - ManuHub Expert</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        /* --- GLOBAL RESET (MATCHING YOUR DASHBOARD) --- */
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

        /* HEADER */
        .top-bar { display: flex; justify-content: space-between; align-items: center; }
        .page-title { font-size: 24px; font-weight: 700; color: #222; }

        /* TABLE STYLING */
        .table-container {
    background-color: #2c3e50; 
    border-radius: 12px;
    padding: 20px; 
    color: white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    flex: 1; 
    display: flex; 
    flex-direction: column;
    min-height: 400px; /* This keeps the background visible when empty */
}

        .dark-table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .dark-table th { text-align: left; padding: 15px; color: #95a5a6; font-size: 12px; text-transform: uppercase; border-bottom: 1px solid #3e5871; }
        .dark-table td { padding: 15px; border-bottom: 1px solid #3e5871; color: #ecf0f1; vertical-align: top; }

        .status-select {
            padding: 6px 10px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 12px; width: 100%;
        }
        .status-select.pending { background: #fffbe6; color: #d48806; }
        .status-select.approved { background: #f6ffed; color: #389e0d; }
        .status-select.rejected { background: #fff1f0; color: #cf1322; }

        /* SUGGESTION SPECIFIC */
        .suggestion-box {
            background: rgba(0,0,0,0.2);
            padding: 12px;
            border-radius: 8px;
            border-left: 4px solid #26c6da;
            margin-top: 5px;
        }
        .preview-img {
            max-height: 120px;
            border-radius: 4px;
            margin-top: 10px;
            border: 1px solid rgba(255,255,255,0.1);
        }

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
        <div class="page-title">Community Suggestions Review</div>
    </div>

    <div style="background: white; padding: 15px 20px; border-radius: 12px; display: flex; gap: 15px; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.03); border: 1px solid #eee; margin-bottom: 20px;">
        <i class="bi bi-search" style="color: #999;"></i>
        <input type="text" id="suggestionSearch" style="flex: 1; padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;" placeholder="Search by manuscript title, user, or field..." onkeyup="filterSuggestions()">
        
        <div style="border-left: 1px solid #eee; height: 30px;"></div>
        
        <select id="statusFilter" style="padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: white; cursor: pointer;" onchange="filterSuggestions()">
            <option value="all">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
        </select>
    </div>

    <form method="POST" action="index.php?action=expert_verification_suggestions">
        <div class="table-container">
            <table class="dark-table" id="suggestionsTable">
                <thead>
                    <tr>
                        <th width="20%">Contributor</th>
                        <th width="25%">Manuscript</th>
                        <th width="40%">Proposed Change</th>
                        <th width="15%">Decision</th>
                    </tr>
                </thead>
                <tbody>
    <?php if (!empty($data['list'])): ?>
        <?php foreach ($data['list'] as $row): ?>
            <tr class="item-row" data-status="<?php echo strtolower($row['status']); ?>">
                <td>
                    <div style="font-weight: 700;"><?php echo htmlspecialchars($row['username'] ?? 'Anonymous'); ?></div>
                    <div style="font-size: 11px; color: #95a5a6;"><?php echo date('d M Y', strtotime($row['created_at'])); ?></div>
                </td>
                <td>
                    <div style="font-size: 11px; text-transform: uppercase; color: #95a5a6;">Manuscript Title:</div>
                    <div style="font-weight: 600; color: #fff;"><?php echo htmlspecialchars($row['manuscript_title']); ?></div>
                    <div style="font-size: 11px; color: #26c6da;">ID: #<?php echo $row['manuscript_id']; ?></div>
                </td>
                <td>
    <span class="badge bg-secondary mb-2" style="font-size: 10px;">
        <?php echo strtoupper($row['field_name']); ?>
    </span>
    <div class="suggestion-box">
        <?php if ($row['field_name'] === 'Cover Image'): ?>
            <div style="font-size: 11px; color: #95a5a6; margin-bottom: 5px;">Suggested Cover Image:</div>
            
            <?php if (!empty($row['suggested_image'])): ?>
                <?php if ($row['file_exists']): ?>
                    <span class="badge bg-success mb-2" style="font-size: 9px;">
                        <i class="bi bi-check-circle-fill"></i> FILE FOUND IN DIRECTORY
                    </span>
                    <br>
                    <img src="../assets/images/<?php echo $row['suggested_image']; ?>" 
                         class="preview-img" alt="Cover Preview">
                <?php else: ?>
                    <span class="badge bg-danger mb-2" style="font-size: 9px;">
                        <i class="bi bi-exclamation-triangle-fill"></i> FILE MISSING ON SERVER
                    </span>
                    <div style="color: #e74c3c; font-size: 11px; font-style: italic;">
                        Error: File was not found in assets/images/
                    </div>
                <?php endif; ?>
                
            <?php else: ?>
                <div style="color: #e74c3c; font-style: italic;">No image filename provided.</div>
            <?php endif; ?>

        <?php else: ?>
            <div style="font-size: 11px; color: #95a5a6;">Suggested Text:</div>
            <div style="color: #2ecc71; font-weight: 500;">"<?php echo htmlspecialchars($row['suggested_value']); ?>"</div>
        <?php endif; ?>
    </div>
</td>
                <td>
                    <select name="status[<?php echo $row['suggestion_id']; ?>]" class="status-select <?php echo $row['status']; ?>" onchange="updateColor(this)">
                        <option value="pending" <?php if($row['status']=='pending') echo 'selected'; ?>>Pending</option>
                        <option value="approved" <?php if($row['status']=='approved') echo 'selected'; ?>>Approved</option>
                        <option value="rejected" <?php if($row['status']=='rejected') echo 'selected'; ?>>Rejected</option>
                    </select>
                </td>
            </tr>
        <?php endforeach; ?>

        <tr id="noResultsRow" style="display: none;">
            <td colspan="4" style="text-align:center; padding: 60px; color: #7f8c8d;">
                <i class="bi bi-search" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                No suggestions found matching your search.
            </td>
        </tr>

    <?php else: ?>
        <tr><td colspan="4" style="text-align:center; padding: 40px; color: #7f8c8d;">No pending suggestions for review.</td></tr>
    <?php endif; ?>
</tbody>
            </table>
        </div>

        <button type="submit" class="btn-save-float">
            <i class="bi bi-save-fill"></i> Save Decisions
        </button>
    </form>
</div>

    <script>
function filterSuggestions() {
    var input = document.getElementById('suggestionSearch').value.toLowerCase();
    var status = document.getElementById('statusFilter').value.toLowerCase();
    var rows = document.querySelectorAll('.item-row');
    var noResults = document.getElementById('noResultsRow');
    var visibleCount = 0;

    rows.forEach(function(row) {
        var text = row.innerText.toLowerCase();
        var rowStatus = row.getAttribute('data-status');
        
        var matchesSearch = text.includes(input);
        var matchesStatus = (status === 'all') || (rowStatus === status);

        if (matchesSearch && matchesStatus) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    // Show or hide the "No Results" message based on the count
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
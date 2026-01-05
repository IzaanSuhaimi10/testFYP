<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Researchers - ManuHub Expert</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        /* --- SHARED STYLING --- */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { display: flex; height: 100vh; background-color: #f8f9fa; overflow: hidden; font-size: 14px; }
        
        .sidebar { width: 250px; min-width: 250px; background-color: #fff; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; padding: 25px; }
        .brand { font-size: 18px; font-weight: 800; margin-bottom: 40px; color: #333; letter-spacing: 0.5px; display: flex; align-items: center; gap: 10px; }
        .brand-logo { width: 32px; height: 32px; background: #26c6da; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 16px;}
        .menu-item { text-decoration: none; color: #666; padding: 12px 15px; margin-bottom: 8px; border-radius: 8px; font-size: 14px; transition: all 0.2s; display: flex; align-items: center; gap: 12px; font-weight: 500; }
        .menu-item.active { background-color: #e0f7fa; color: #006064; font-weight: 700; }

        .main-content { flex: 1; padding: 30px; overflow-y: auto; background-color: #f8f9fa; display: flex; flex-direction: column; gap: 20px; position: relative; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; }
        .page-title { font-size: 24px; font-weight: 700; color: #222; }

        .filter-bar { background: white; padding: 15px 20px; border-radius: 12px; display: flex; gap: 15px; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.03); border: 1px solid #eee; }
        .search-input { flex: 1; padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; }
        .filter-select { padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: white; cursor: pointer; }

        .table-container { background-color: #2c3e50; border-radius: 12px; padding: 20px; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.05); flex: 1; display: flex; flex-direction: column; }
        .dark-table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .dark-table th { text-align: left; padding: 15px; color: #95a5a6; font-size: 12px; text-transform: uppercase; border-bottom: 1px solid #3e5871; }
        .dark-table td { padding: 15px; border-bottom: 1px solid #3e5871; color: #ecf0f1; vertical-align: top; }

        .status-select { padding: 6px 10px; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; font-size: 12px; width: 100%; }
        .status-select.pending { background: #fffbe6; color: #d48806; }
        .status-select.active { background: #f6ffed; color: #389e0d; }
        .status-select.inactive { background: #fff1f0; color: #cf1322; }

        .btn-view-doc { background: #26c6da; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; }

        /* FLOATING SAVE BUTTON */
        .btn-save-float {
            position: fixed; bottom: 30px; right: 30px;
            background: #26c6da; color: white; border: none;
            padding: 15px 30px; border-radius: 50px;
            font-size: 16px; font-weight: 700; cursor: pointer;
            box-shadow: 0 4px 20px rgba(38, 198, 218, 0.4);
            display: flex; align-items: center; gap: 10px;
            transition: transform 0.2s; z-index: 100;
        }
        .btn-save-float:hover { transform: translateY(-3px); background: #00acc1; }

        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); display: none; justify-content: center; align-items: center; z-index: 1000; backdrop-filter: blur(4px); }
        .modal-box { background: white; width: 700px; max-width: 90%; padding: 20px; border-radius: 16px; position: relative; box-shadow: 0 20px 50px rgba(0,0,0,0.3); }
        .modal-header { font-size: 18px; font-weight: 800; margin-bottom: 15px; color: #333; display: flex; justify-content: space-between; align-items: center; }
        .doc-preview-container { width: 100%; height: 450px; background: #eee; border-radius: 8px; overflow: hidden; display: flex; justify-content: center; align-items: center; }
        .doc-preview-container img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .doc-preview-container iframe { width: 100%; height: 100%; border: none; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="brand"><div class="brand-logo"><i class="bi bi-journal-check"></i></div> MANUHUB</div>
    <?php $current_action = $_GET['action'] ?? ''; ?>
    <a href="index.php?action=expert_dashboard" class="menu-item <?= $current_action == 'expert_dashboard' ? 'active' : '' ?>"><i class="bi bi-grid-fill"></i> Dashboard</a>
    <a href="index.php?action=expert_verification_users" class="menu-item <?= $current_action == 'expert_verification_users' ? 'active' : '' ?>"><i class="bi bi-people-fill"></i> Researcher IDs</a>
    <a href="index.php?action=expert_verification_manuscripts" class="menu-item <?= $current_action == 'expert_verification_manuscripts' ? 'active' : '' ?>"><i class="bi bi-file-earmark-text"></i> Manuscripts</a>
    <a href="index.php?action=expert_verification_related" class="menu-item <?= $current_action == 'expert_verification_related' ? 'active' : '' ?>"><i class="bi bi-link-45deg"></i> Sources</a>
    <a href="index.php?action=expert_verification_suggestions" class="menu-item <?= $current_action == 'expert_verification_suggestions' ? 'active' : '' ?>"><i class="bi bi-pencil-square"></i> Suggestions</a>
    <a href="index.php?action=expert_verification_flags" class="menu-item <?= $current_action == 'expert_verification_flags' ? 'active' : '' ?>"><i class="bi bi-flag-fill"></i> Flags</a>
    <a href="index.php" class="menu-item"><i class="bi bi-house"></i> Home Page</a>
    <a href="index.php?action=logout" class="menu-item" style="margin-top: auto; color: #c0392b;"><i class="bi bi-box-arrow-left"></i> Logout</a>
</div>

<div class="main-content">
    <div class="top-bar"><div class="page-title">Researcher Verification Queue</div></div>

    <div class="filter-bar">
        <i class="bi bi-search" style="color: #999;"></i>
        <input type="text" id="userSearch" class="search-input" placeholder="Search by name or email..." onkeyup="filterUsers()">
        <div style="border-left: 1px solid #eee; height: 30px;"></div>
        <select id="statusFilter" class="filter-select" onchange="filterUsers()">
            <option value="all">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>

    <form method="POST" action="index.php?action=expert_process_user_verifications" style="display: flex; flex-direction: column; flex: 1;">
        <div class="table-container">
            <table class="dark-table" id="userTable">
                <thead>
                    <tr>
                        <th width="25%">Applicant</th>
                        <th width="30%">Email Address</th>
                        <th width="20%">Verification Document</th>
                        <th width="25%">Decision</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['list'])): ?>
                        <?php foreach ($data['list'] as $user): ?>
                        <tr class="item-row" data-status="<?= strtolower($user['status']); ?>">
                            <td>
                                <div style="font-weight: 700;"><?php echo htmlspecialchars($user['username']); ?></div>
                                <div style="font-size: 11px; color: #95a5a6;">Applied: <?php echo date('d M Y', strtotime($user['created_at'])); ?></div>
                            </td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <button type="button" class="btn-view-doc" onclick="openDocModal('<?= $user['verification_doc']; ?>', '<?= htmlspecialchars($user['username']); ?>')">
                                    <i class="bi bi-eye-fill"></i> View ID
                                </button>
                            </td>
                            <td>
                                <select name="status[<?= $user['user_id']; ?>]" class="status-select <?= strtolower($user['status']); ?>" onchange="updateColor(this)">
                                    <option value="pending" <?= $user['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="active" <?= $user['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="inactive" <?= $user['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                </select>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="text-align:center; padding: 60px; color: #7f8c8d;">No pending verifications.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <button type="submit" class="btn-save-float">
            <i class="bi bi-save-fill"></i> Save Changes
        </button>
    </form>
</div>

<div class="modal-overlay" id="docModal">
    <div class="modal-box">
        <div class="modal-header">
            <span id="modalTitle">Verification Document</span>
            <i class="bi bi-x-lg" style="cursor:pointer;" onclick="closeDocModal()"></i>
        </div>
        <div class="doc-preview-container" id="docPreview"></div>
    </div>
</div>

<script>
    function updateColor(select) {
        select.className = 'status-select ' + select.value;
    }

    function filterUsers() {
        let input = document.getElementById('userSearch').value.toLowerCase();
        let status = document.getElementById('statusFilter').value.toLowerCase();
        let rows = document.querySelectorAll('.item-row');

        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            let rowStatus = row.getAttribute('data-status');
            
            let matchesSearch = text.includes(input);
            let matchesStatus = (status === 'all') || (rowStatus === status);

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function openDocModal(fileName, username) {
        const preview = document.getElementById('docPreview');
        const title = document.getElementById('modalTitle');
        const ext = fileName.split('.').pop().toLowerCase();
        const filePath = `../public/uploads/verify/${fileName}`;
        
        title.innerText = `Verification ID: ${username}`;
        
        if (ext === 'pdf') {
            preview.innerHTML = `<iframe src="${filePath}"></iframe>`;
        } else {
            preview.innerHTML = `<img src="${filePath}" alt="ID Document">`;
        }
        
        document.getElementById('docModal').style.display = 'flex';
    }

    function closeDocModal() {
        document.getElementById('docModal').style.display = 'none';
        document.getElementById('docPreview').innerHTML = '';
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('docModal')) {
            closeDocModal();
        }
    }
</script>
</body>
</html>
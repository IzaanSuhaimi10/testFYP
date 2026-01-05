<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Oversight - ManuHub Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        /* --- GLOBAL RESET & SIDEBAR --- */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { display: flex; height: 100vh; background-color: #f8f9fa; overflow: hidden; font-size: 14px; }
        .sidebar { width: 250px; min-width: 250px; background-color: #fff; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; padding: 25px; }
        .brand { font-size: 18px; font-weight: 800; margin-bottom: 40px; color: #333; display: flex; align-items: center; gap: 10px; }
        .brand-logo { width: 32px; height: 32px; background: #333; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; }
        .menu-item { text-decoration: none; color: #666; padding: 12px 15px; margin-bottom: 8px; border-radius: 8px; display: flex; align-items: center; gap: 12px; font-weight: 500; transition: 0.2s; }
        .menu-item.active { background-color: #D1E6E5; color: #004d40; font-weight: 700; }

        .main-content { flex: 1; padding: 30px; overflow-y: auto; background-color: #f8f9fa; display: flex; flex-direction: column; gap: 20px; }

        /* TABS */
        .tabs-container { display: flex; gap: 5px; margin-top: 10px; }
        .tab-btn { padding: 12px 20px; border-radius: 10px 10px 0 0; border: none; background: #e0e0e0; color: #666; font-weight: 700; cursor: pointer; font-size: 12px; transition: 0.2s; }
        .tab-btn.active { background: #2c3e50; color: white; }

        /* FILTER BAR */
        .filter-bar { background: white; padding: 15px 20px; border-radius: 0 12px 12px 12px; display: flex; gap: 15px; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.03); border: 1px solid #eee; }
        .search-input { flex: 1; padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; }
        .filter-select { padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; background: white; cursor: pointer; }

        /* TABLE */
        .table-container { background-color: #2c3e50; border-radius: 12px; padding: 20px; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .dark-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .dark-table th { text-align: left; padding: 15px; color: #95a5a6; font-size: 11px; text-transform: uppercase; border-bottom: 1px solid #3e5871; }
        .dark-table td { padding: 15px; border-bottom: 1px solid #3e5871; color: #ecf0f1; vertical-align: middle; }
        
        /* Interactive Status Selects */
        .status-select {
            padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; border: none; cursor: pointer;
        }
        .status-select.pending { background: #f39c12; color: white; }
        .status-select.approved, .status-select.resolved { background: #27ae60; color: white; }
        .status-select.rejected, .status-select.dismissed { background: #c0392b; color: white; }

        .btn-view { background: #26c6da; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 11px; font-weight: 700; }

        /* Save Button */
        .btn-save-float {
            position: fixed; bottom: 30px; right: 30px;
            background: #27ae60; color: white; border: none;
            padding: 15px 30px; border-radius: 50px;
            font-size: 14px; font-weight: 700; cursor: pointer;
            box-shadow: 0 4px 20px rgba(39, 174, 96, 0.4);
            display: flex; align-items: center; gap: 10px; z-index: 100;
        }

        /* MODAL */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); display: none; justify-content: center; align-items: center; z-index: 1000; }
        .modal-box { background: white; width: 600px; padding: 30px; border-radius: 16px; position: relative; color: #333; }
        .modal-header { font-size: 18px; font-weight: 800; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; text-transform: uppercase; }
        .modal-row { display: grid; grid-template-columns: 130px 1fr; margin-bottom: 10px; font-size: 13px; }
        .modal-label { font-weight: 700; color: #888; text-transform: uppercase; font-size: 10px; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="brand"><div class="brand-logo"><i class="bi bi-shield-lock"></i></div> MANUHUB</div>
    <a href="index.php?action=admin_dashboard" class="menu-item"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="index.php?action=admin_manuscripts" class="menu-item active"><i class="bi bi-journal-text"></i> Oversight</a>
    <a href="index.php?action=admin_users" class="menu-item"><i class="bi bi-people-fill"></i> Users</a>
    <a href="index.php?action=admin_system" class="menu-item"><i class="bi bi-terminal"></i> Logs</a>
    <a href="index.php" class="menu-item"><i class="bi bi-house"></i> Home Page</a>
    <a href="index.php?action=logout" class="menu-item" style="margin-top: auto; color: #c0392b;"><i class="bi bi-box-arrow-left"></i> Logout</a>
</div>

<div class="main-content">
    <h1 style="font-size: 24px; font-weight: 800; color: #222;">SYSTEM OVERSIGHT</h1>

    <div class="tabs-container">
        <button class="tab-btn active" id="btn-ms" onclick="switchOversightTab('ms')">Manuscripts</button>
        <button class="tab-btn" id="btn-sug" onclick="switchOversightTab('sug')">Suggestions</button>
        <button class="tab-btn" id="btn-rw" onclick="switchOversightTab('rw')">Sources</button>
        <button class="tab-btn" id="btn-flags" onclick="switchOversightTab('flags')">Flags</button>
    </div>

    <div class="filter-bar">
        <i class="bi bi-search" style="color: #999;"></i>
        <input type="text" id="oversightSearch" class="search-input" placeholder="Search across all categories..." onkeyup="filterOversightTable()">
        <select id="statusFilter" class="filter-select" onchange="filterOversightTable()">
            <option value="all">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved/Resolved</option>
            <option value="rejected">Rejected/Dismissed</option>
        </select>
    </div>

    <form action="index.php?action=admin_verify_manuscripts" method="POST">
        
        <div id="sec-ms" class="table-container oversight-section">
            <table class="dark-table">
                <thead><tr><th>Submitter</th><th>Title</th><th>Status</th><th>Action</th></tr></thead>
                <tbody>
                    <?php foreach($data['submissions'] as $row): ?>
                    <tr class="ov-row" 
                        data-status="<?php echo strtolower($row['status']); ?>"
                        data-title="<?php echo htmlspecialchars($row['Title']); ?>"
                        data-user="<?php echo htmlspecialchars($row['username'] ?? 'User'); ?>"
                        data-author="<?php echo htmlspecialchars($row['Author'] ?? 'Unknown'); ?>"
                        data-loc="<?php echo htmlspecialchars($row['Location_of_Manuscript'] ?? '-'); ?>"
                        data-desc="<?php echo htmlspecialchars($row['Description'] ?? '-'); ?>">
                        
                        <td><?php echo htmlspecialchars($row['username'] ?? 'User'); ?></td>
                        <td><?php echo htmlspecialchars($row['Title']); ?></td>
                        <td>
                            <select name="status[<?php echo $row['id']; ?>]" class="status-select <?php echo strtolower($row['status']); ?>" onchange="this.className='status-select '+this.value">
                                <option value="pending" <?php echo ($row['status'] == 'pending') ? 'selected' : ''; ?>>PENDING</option>
                                <option value="approved" <?php echo ($row['status'] == 'approved') ? 'selected' : ''; ?>>APPROVE</option>
                                <option value="rejected" <?php echo ($row['status'] == 'rejected') ? 'selected' : ''; ?>>REJECT</option>
                            </select>
                            <div style="display:none; margin-top:5px;" class="rej-box">
                                <input type="text" name="comments[<?php echo $row['id']; ?>]" placeholder="Rejection reason..." style="font-size:10px; width:100%; padding:2px;">
                            </div>
                        </td>
                        <td>
                            <button type="button" class="btn-view" onclick="openManuscriptOvModal(this)">Details</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div id="sec-sug" class="table-container oversight-section" style="display:none;">
            <table class="dark-table" id="sugTable">
                <thead>
                    <tr><th>Submitter</th><th>Field</th><th>Target</th><th>Status</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php foreach($data['suggestions'] as $s): ?>
                    <tr class="ov-row" data-status="<?php echo strtolower($s['status']); ?>">
                        <td><?php echo htmlspecialchars($s['username']); ?></td>
                        <td><span style="color:#26c6da;font-weight:700;"><?php echo strtoupper($s['field_name']); ?></span></td>
                        <td><?php echo htmlspecialchars($s['manuscript_title']); ?></td>
                        <td>
                            <select name="sug_status[<?php echo $s['suggestion_id']; ?>]" class="status-select <?php echo strtolower($s['status']); ?>" onchange="this.className='status-select '+this.value">
                                <option value="pending" <?php echo ($s['status'] == 'pending') ? 'selected' : ''; ?>>PENDING</option>
                                <option value="approved" <?php echo ($s['status'] == 'approved') ? 'selected' : ''; ?>>APPROVE</option>
                                <option value="rejected" <?php echo ($s['status'] == 'rejected') ? 'selected' : ''; ?>>REJECT</option>
                            </select>
                        </td>
                        <td><button type="button" class="btn-view" onclick="openSuggestionModal(this)">Details</button></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div id="sec-rw" class="table-container oversight-section" style="display:none;">
            <table class="dark-table">
                <thead><tr><th>Submitter</th><th>Source Title</th><th>Status</th><th>Action</th></tr></thead>
                <tbody>
                    <?php foreach($data['related_works'] as $r): ?>
                    <tr class="ov-row" data-status="<?php echo strtolower($r['status']); ?>">
                        <td><?php echo htmlspecialchars($r['username']); ?></td>
                        <td><?php echo htmlspecialchars($r['title']); ?></td>
                        <td>
                             <select name="rw_status[<?php echo $r['id']; ?>]" class="status-select <?php echo strtolower($r['status']); ?>" onchange="this.className='status-select '+this.value">
                                <option value="pending" <?php echo ($r['status'] == 'pending') ? 'selected' : ''; ?>>PENDING</option>
                                <option value="approved" <?php echo ($r['status'] == 'approved') ? 'selected' : ''; ?>>APPROVE</option>
                                <option value="rejected" <?php echo ($r['status'] == 'rejected') ? 'selected' : ''; ?>>REJECT</option>
                            </select>
                        </td>
                        <td><button type="button" class="btn-view" onclick="openSourceOvModal(this)">Details</button></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div id="sec-flags" class="table-container oversight-section" style="display:none;">
            <table class="dark-table" id="flagsTable">
                <thead><tr><th>Reporter</th><th>Reason</th><th>Status</th><th>Action</th></tr></thead>
                <tbody>
                    <?php foreach($data['flags'] as $f): ?>
                    <tr class="ov-row" data-status="<?php echo strtolower($f['status']); ?>">
                        <td><?php echo htmlspecialchars($f['username']); ?></td>
                        <td><span style="color:#e74c3c;"><?php echo htmlspecialchars($f['reason']); ?></span></td>
                        <td>
                            <select name="flag_status[<?php echo $f['id']; ?>]" class="status-select <?php echo strtolower($f['status']); ?>" onchange="this.className='status-select '+this.value">
                                <option value="pending" <?php echo ($f['status'] == 'pending') ? 'selected' : ''; ?>>PENDING</option>
                                <option value="resolved" <?php echo ($f['status'] == 'resolved') ? 'selected' : ''; ?>>RESOLVED</option>
                                <option value="dismissed" <?php echo ($f['status'] == 'dismissed') ? 'selected' : ''; ?>>DISMISS</option>
                            </select>
                        </td>
                        <td><button type="button" class="btn-view" onclick="openFlagModal(this)">Details</button></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <button type="submit" class="btn-save-float">
            <i class="bi bi-shield-check"></i> Process Oversight Changes
        </button>

    </form>
</div>

<div class="modal-overlay" id="ovModal">
    <div class="modal-box">
        <span style="position:absolute;top:15px;right:20px;cursor:pointer;font-size:24px;" onclick="closeOvModal()">&times;</span>
        <div class="modal-header" id="ovHeader">Item Overview</div>
        <div id="ovContent"></div>
    </div>
</div>

<script>
    function switchOversightTab(tab) {
        document.querySelectorAll('.oversight-section').forEach(s => s.style.display = 'none');
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('sec-' + tab).style.display = 'block';
        document.getElementById('btn-' + tab).classList.add('active');
    }

    function filterOversightTable() {
        let search = document.getElementById("oversightSearch").value.toLowerCase();
        let status = document.getElementById("statusFilter").value.toLowerCase();
        let visibleSec = document.querySelector('.oversight-section[style*="block"]') || document.getElementById('sec-ms');
        
        visibleSec.querySelectorAll(".ov-row").forEach(row => {
            let rowStatus = row.getAttribute('data-status');
            let matchesSearch = row.innerText.toLowerCase().includes(search);
            let matchesStatus = (status === 'all' || rowStatus.includes(status));
            row.style.display = (matchesSearch && matchesStatus) ? "" : "none";
        });
    }

    // Modal display logic (unchanged to maintain functionality)
    function openSourceOvModal(btn) {
        let row = btn.closest('tr');
        let sourceUrl = row.getAttribute('data-url');
        let html = `
            <div class="modal-row"><div class="modal-label">SOURCE TITLE</div><div style="font-weight:700;">${row.getAttribute('data-title')}</div></div>
            <div class="modal-row"><div class="modal-label">SUBMITTED BY</div><div>${row.getAttribute('data-user')}</div></div>
            <div class="modal-row"><div class="modal-label">CONNECTED TO</div><div>${row.getAttribute('data-target')}</div></div>
            <div class="modal-row"><div class="modal-label">REFERENCE URL</div><div><a href="${sourceUrl}" target="_blank" style="color:#26c6da; font-weight:600;"><i class="bi bi-box-arrow-up-right"></i> Open Link</a></div></div>`;
        document.getElementById('ovContent').innerHTML = html;
        document.getElementById('ovHeader').innerText = "Source Oversight Details";
        document.getElementById('ovModal').style.display = 'flex';
    }

    function openSuggestionModal(btn) {
        let row = btn.closest('tr');
        let html = `
            <div class="modal-row"><div class="modal-label">FIELD NAME</div><div style="font-weight:700; color:#26c6da;">${row.getAttribute('data-field')}</div></div>
            <div class="modal-row"><div class="modal-label">SUBMITTED BY</div><div>${row.getAttribute('data-user')}</div></div>
            <div class="modal-row"><div class="modal-label">CONNECTED TO</div><div>${row.getAttribute('data-target')}</div></div>
            <div class="modal-row"><div class="modal-label">VALUE</div><div>${row.getAttribute('data-value')}</div></div>`;
        document.getElementById('ovContent').innerHTML = html;
        document.getElementById('ovHeader').innerText = "Suggestion Overview";
        document.getElementById('ovModal').style.display = 'flex';
    }

    function openFlagModal(btn) {
        let row = btn.closest('tr');
        let html = `
            <div class="modal-row"><div class="modal-label">REPORTED ITEM</div><div style="font-weight:700;">${row.getAttribute('data-item')}</div></div>
            <div class="modal-row"><div class="modal-label">REASON</div><div style="color:#c0392b;">${row.getAttribute('data-reason')}</div></div>`;
        document.getElementById('ovContent').innerHTML = html;
        document.getElementById('ovHeader').innerText = "Flag Overview";
        document.getElementById('ovModal').style.display = 'flex';
    }

    function openManuscriptOvModal(btn) {
        let row = btn.closest('tr');
        let html = `
            <div class="modal-row"><div class="modal-label">TITLE</div><div style="font-weight:700;">${row.getAttribute('data-title')}</div></div>
            <div class="modal-row"><div class="modal-label">AUTHOR</div><div>${row.getAttribute('data-author')}</div></div>
            <div class="modal-row"><div class="modal-label">DESC</div><div>${row.getAttribute('data-desc')}</div></div>`;
        document.getElementById('ovContent').innerHTML = html;
        document.getElementById('ovHeader').innerText = "Manuscript Oversight Details";
        document.getElementById('ovModal').style.display = 'flex';
    }

    function closeOvModal() { document.getElementById('ovModal').style.display = 'none'; }
</script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($data['manuscript']['Title']); ?> - ManuHub</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script type="text/javascript" src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>

    <style>
        :root {
            --primary-maroon: #6d0828;
            --accent-gold: #b58428;
            --bg-cream: #fcfaf7;
        }

        body { background-color: var(--bg-cream); font-family: 'Inter', sans-serif; color: #333; }

        /* Elegant Header Section */
        .metadata-header {
            background-color: var(--primary-maroon);
            color: white;
            padding: 60px 0;
            border-bottom: 5px solid var(--accent-gold);
            margin-bottom: 40px;
        }

        /* Unified Card Styling */
        .data-card {
            border: none;
            border-radius: 15px;
            background: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        /* Tab Navigation - Matching homepage buttons */
        .nav-tabs { border-bottom: none; }
        .nav-tabs .nav-link {
            color: #666;
            background-color: #eee;
            border: none;
            margin-right: 5px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 12px 20px;
            transition: 0.3s;
        }

        .nav-tabs .nav-link.active {
            background-color: white;
            color: var(--primary-maroon);
            border-top: 4px solid var(--primary-maroon);
            border-bottom: 3px solid white; /* Overlays the container border */
        }

        /* Information Labels */
        .info-label {
            color: var(--primary-maroon);
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .info-value { font-size: 1rem; margin-bottom: 15px; color: #444; }

        #network-graph {
    width: 100%;
    height: 500px; /* Ensure this is set */
    background-color: #fafafa;
    border-radius: 12px;
    border: 1px solid #eee;
    margin-top: 15px; /* Add space from the title */
}

.tab-pane#connections {
    padding: 20px;
    min-height: 600px;
}
        .btn-gold {
            background-color: var(--accent-gold);
            color: white;
            font-weight: 600;
            border-radius: 50px;
        }

        .btn-gold:hover { background-color: #9e7322; color: white; }

        .flag-trigger {
    transition: transform 0.2s ease;
    cursor: pointer;
    display: inline-block;
}

.flag-trigger:hover {
    transform: scale(1.2);
    color: #a71d2a !important; /* Darker red on hover */
}

.bg-maroon {
    background-color: var(--primary-maroon);
    color: white;
}
    </style>
</head>
<body>

<?php include('header.php'); ?>

<?php
// Function to handle empty fields and show edit icons for registered users
function displayField($value, $fieldName, $isLoggedIn) {
    if (!empty($value) && $value !== '-' && $value !== 'NULL') {
        return htmlspecialchars($value);
    } else {
        $msg = '<span class="text-muted fst-italic" style="font-size: 0.85rem;">Information missing</span>';
        if ($isLoggedIn) {
            $msg .= ' <a href="#" class="ms-1 edit-trigger text-decoration-none" data-field="'.$fieldName.'" data-bs-toggle="modal" data-bs-target="#suggestModal">
                        <i class="bi bi-pencil-square text-primary"></i>
                      </a>';
        }
        return $msg;
    }
}
$isLoggedIn = isset($_SESSION['user_id']);
?>

<div class="metadata-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8 text-center text-md-start">
                <a href="index.php?action=manuscript_list" class="text-white text-decoration-none mb-3 d-inline-block opacity-75">
                    <i class="bi bi-arrow-left me-2"></i> Back to Collection
                </a>
                <h1 class="display-5 fw-bold mb-0"><?php echo htmlspecialchars($data['manuscript']['Title']); ?></h1>
                <p class="lead mt-2 opacity-75">
    Subject: <?php echo htmlspecialchars($data['manuscript']['Subject']); ?>
    <?php if ($isLoggedIn): ?>
        <a href="#" class="ms-1 edit-trigger text-white text-decoration-none" 
           data-field="Subject" 
           data-bs-toggle="modal" 
           data-bs-target="#suggestModal">
            <i class="bi bi-pencil-square" style="font-size: 0.9rem;" title="Suggest a more accurate subject"></i>
        </a>
    <?php endif; ?>
</p>
            </div>
            <div class="col-md-4 text-center text-md-end mt-4 mt-md-0">
                <span class="badge p-2 px-3 fs-6" style="background-color: var(--accent-gold);">
                    <?php echo htmlspecialchars($data['manuscript']['Genre'] ?? 'Manuscript'); ?>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row">
        <div class="col-lg-4 mb-4">
    <div class="card data-card p-3 text-center">
        <div class="position-relative">
            <img src="../assets/images/<?php echo htmlspecialchars($data['manuscript']['file_path'] ?? 'default.jpg'); ?>" 
                 class="img-fluid rounded shadow-sm mb-3" alt="Manuscript Cover">
            
            <?php if ($isLoggedIn && ($data['manuscript']['file_path'] == 'default.jpg' || empty($data['manuscript']['file_path']))): ?>
                <div class="mt-2">
                    <a href="#" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#imageUploadModal">
                        <i class="bi bi-camera-fill me-1"></i> Contribute Cover
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <h5 class="fw-bold text-maroon mb-1 mt-3">
            <?php echo displayField($data['manuscript']['Author'], 'Author', $isLoggedIn); ?>
        </h5>
        <p class="text-muted small italic">Recorded Author / Scribe</p>
        
        <hr>
        
        <div class="d-flex justify-content-around">
            <div>
                <div class="info-label">Language</div>
                <div class="small fw-bold">
                    <?php echo displayField($data['manuscript']['Language'], 'Language', $isLoggedIn); ?>
                </div>
            </div>
            <div>
                <div class="info-label">ID</div>
                <div class="small fw-bold">#<?php echo $data['manuscript']['id']; ?></div>
            </div>
        </div>
    </div>
</div>

        <div class="col-lg-8">
            <ul class="nav nav-tabs" id="metadataTabs" role="tablist">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#about" type="button">About</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#location" type="button">Location</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#contributor" type="button">Contributor</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#related-work" type="button">Related Works</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#citation" type="button">Cited By</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#connections" type="button">Connections Map</button></li>
            </ul>

            <div class="tab-content data-card p-4 p-md-5" id="metadataTabsContent" style="border-top-left-radius: 0;">
                
                <div class="tab-pane fade show active" id="about">
    <h4 class="fw-bold mb-4" style="color: var(--primary-maroon);">Manuscript Information</h4>
    <div class="row">
        <div class="col-md-6">
            <div class="info-label">Title</div>
            <div class="info-value fw-bold"><?php echo htmlspecialchars($data['manuscript']['Title']); ?></div>
            
           <div class="info-label">Subject</div>
<div class="info-value">
    <?php echo htmlspecialchars($data['manuscript']['Subject'] ?? 'Information missing'); ?>
    <?php if ($isLoggedIn): ?>
        <a href="#" class="ms-1 edit-trigger text-decoration-none" 
           data-field="Subject" 
           data-bs-toggle="modal" 
           data-bs-target="#suggestModal">
            <i class="bi bi-pencil-square text-primary" title="Suggest a more accurate subject"></i>
        </a>
    <?php endif; ?>
</div>
        </div>
        <div class="col-md-6">
            <div class="info-label">Genre</div>
            <div class="info-value"><?php echo displayField($data['manuscript']['Genre'], 'Genre', $isLoggedIn); ?></div>
            
            <div class="info-label">Language</div>
            <div class="info-value"><?php echo displayField($data['manuscript']['Language'], 'Language', $isLoggedIn); ?></div>
        </div>
        <div class="col-12 mt-3">
            <div class="info-label">Description</div>
            <div class="info-value text-muted" style="text-align: justify; line-height: 1.7;">
                <?php echo displayField($data['manuscript']['Description'], 'Description', $isLoggedIn); ?>
            </div>
        </div>
    </div>
</div>

                <div class="tab-pane fade" id="location">
    <h4 class="fw-bold mb-4" style="color: var(--primary-maroon);">Repository Details</h4>
    
    <div class="info-label">Physical Location</div>
    <div class="info-value">
        <?php echo displayField($data['manuscript']['Location_of_Manuscript'], 'Location_of_Manuscript', $isLoggedIn); ?>
    </div>
    
    <div class="info-label">Country</div>
    <div class="info-value">
        <?php echo displayField($data['manuscript']['Country'], 'Country', $isLoggedIn); ?>
    </div>
    
    <div class="info-label">Call Number</div>
    <div class="info-value fw-bold">
        <?php echo displayField($data['manuscript']['Call_Number'], 'Call_Number', $isLoggedIn); ?>
    </div>
</div>

                <div class="tab-pane fade" id="contributor">
                    <h4 class="fw-bold mb-4" style="color: var(--primary-maroon);">Submission Audit</h4>
                    <div class="info-label">Metadata Contributed By</div>
                    <div class="info-value"><?php echo htmlspecialchars($data['manuscript']['contributor_name'] ?? 'Admin'); ?></div>
                    <div class="info-label">Database Record Created</div>
                    <div class="info-value"><?php echo date("F j, Y", strtotime($data['manuscript']['create_dat'])); ?></div>
                    <div class="info-label">Verification Status</div>
                    <div class="info-value">
                        <span class="badge bg-<?php echo ($data['manuscript']['status'] == 'approved') ? 'success' : 'warning'; ?>">
                            <?php echo strtoupper($data['manuscript']['status']); ?>
                        </span>
                    </div>
                </div>

                <div class="tab-pane fade" id="related-work">
    <h4 class="fw-bold mb-3" style="color: var(--primary-maroon);">Related Works</h4>
    
    <?php if ($isLoggedIn): ?>
        <div class="alert alert-light border-0 shadow-sm d-flex align-items-center p-2 mb-4" style="border-left: 4px solid #dc3545 !important;">
            <i class="bi bi-info-circle-fill text-danger me-3 fs-5"></i>
            <div class="small">
                <strong>Help us improve:</strong> If you find an item that is not related to this manuscript, please click the <i class="bi bi-flag text-danger"></i> icon to notify our experts.
            </div>
        </div>
    <?php endif; ?>
<div class="list-group list-group-flush mb-4">
    <?php if (!empty($data['related_works'])): ?>
        <?php 
        $hasRelated = false;
        foreach ($data['related_works'] as $work): 
            // FILTER: Skip items that are marked as citations
            if (!isset($work['type']) || $work['type'] !== 'citation'): 
                $hasRelated = true;
        ?>
            <div class="list-group-item bg-transparent px-0 py-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="<?php echo htmlspecialchars($work['url']); ?>" target="_blank" class="fw-bold text-decoration-none" style="color: var(--primary-maroon);">
                            <i class="bi bi-file-earmark-pdf me-2"></i><?php echo htmlspecialchars($work['title']); ?>
                        </a>
                        <?php if ($isLoggedIn): ?>
                            <a href="#" class="ms-2 text-danger flag-trigger" 
   data-work-id="<?php echo $work['id']; ?>" 
   data-work-title="<?php echo htmlspecialchars($work['title']); ?>" 
   data-target-type="related_work" data-bs-toggle="modal" data-bs-target="#flagModal">
    <i class="bi bi-flag" title="Report as irrelevant"></i>
</a>
                        <?php endif; ?>
                        <small class="text-muted d-block mt-1"><?php echo htmlspecialchars($work['url']); ?></small>
                    </div>
                    <span class="badge rounded-pill bg-light text-primary border border-primary">
                        <?php echo htmlspecialchars($work['category'] ?? 'Research'); ?>
                    </span>
                </div>
            </div>
        <?php endif; endforeach; ?>
        <?php if (!$hasRelated): ?>
            <p class="text-muted italic py-3 text-center">No open-access resources found for this title.</p>
        <?php endif; ?>
    <?php else: ?>
        <p class="text-muted italic py-3 text-center">No open-access resources found for this title.</p>
    <?php endif; ?>
</div>

    <hr class="my-5">

    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="card border-0 shadow-sm" style="background-color: #fcfaf7; border-radius: 12px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3" style="color: var(--primary-maroon);">
                    <i class="bi bi-plus-circle-fill me-2"></i>Contribute a Resource
                </h5>
                <p class="text-muted small mb-4">Help the research community by sharing digitized versions or relevant papers.</p>
                
                <form action="index.php?action=submit_related_work" method="POST">
                    <input type="hidden" name="manuscript_id" value="<?php echo $data['manuscript']['id']; ?>">

                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="info-label">Resource Title</label>
                            <input type="text" name="work_title" class="form-control" placeholder="e.g., Digitized copy from National Library" required>
                        </div>
                        <div class="col-md-5">
                            <label class="info-label">Direct Link (URL)</label>
                            <input type="url" name="work_url" class="form-control" placeholder="https://..." required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-gold w-100 py-2">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-light text-center border p-4 shadow-sm" style="border-radius: 12px; border-style: dashed !important;">
            <i class="bi bi-info-circle text-primary fs-4 mb-2 d-block"></i>
            <p class="mb-0">Found a related resource? 
                <a href="index.php?action=login" class="fw-bold text-decoration-none" style="color: var(--primary-maroon);">Login</a> to contribute to this manuscript's metadata.
            </p>
        </div>
    <?php endif; ?>
</div>

<div class="tab-pane fade" id="citation">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0" style="color: var(--primary-maroon);">Academic Citations</h4>
        
        <?php if ($isLoggedIn): ?>
            <form method="POST" class="d-inline">
                <input type="hidden" name="trigger_scan" value="true">
                <button type="submit" class="btn btn-gold btn-sm rounded-pill px-3 shadow-sm">
                    <i class="bi bi-search me-1"></i> Scan for Citations
                </button>
            </form>
        <?php endif; ?>
    </div>

    <?php if ($isLoggedIn): ?>
        <div class="alert alert-light border-0 shadow-sm d-flex align-items-center p-2 mb-4" style="border-left: 4px solid #dc3545 !important;">
            <i class="bi bi-shield-exclamation text-danger me-3 fs-5"></i>
            <div class="small">
                <strong>Help us improve:</strong> Notice a citation that doesn't belong here? Use the flag icon to notify our experts.
            </div>
        </div>
    <?php endif; ?>

    <div class="list-group list-group-flush">
        <?php 
        $hasCitations = false;
        if (!empty($data['related_works'])): 
            foreach ($data['related_works'] as $work): 
                // Only show items specifically labeled as 'citation'
                if (isset($work['type']) && $work['type'] === 'citation'): 
                    $hasCitations = true;
        ?>
            <div class="list-group-item bg-transparent px-0 py-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="<?php echo htmlspecialchars($work['url']); ?>" target="_blank" class="fw-bold text-decoration-none text-dark">
                            <i class="bi bi-quote me-2 text-gold"></i><?php echo htmlspecialchars($work['title']); ?>
                        </a>
                        <?php if ($isLoggedIn): ?>
                            <a href="#" class="ms-2 text-danger flag-trigger" 
   data-work-id="<?php echo $work['id']; ?>" 
   data-work-title="<?php echo htmlspecialchars($work['title']); ?>" 
   data-target-type="citation" data-bs-toggle="modal" data-bs-target="#flagModal">
    <i class="bi bi-flag"></i>
</a>
                        <?php endif; ?>
                        <small class="text-muted d-block mt-1"><?php echo htmlspecialchars($work['url']); ?></small>
                    </div>
                </div>
            </div>
        <?php 
                endif; 
            endforeach; 
        endif; 

        if (!$hasCitations): 
        ?>
            <p class="text-muted italic py-4 text-center">No academic citations found for this manuscript yet.</p>
        <?php endif; ?>
    </div>
</div>
               <div class="tab-pane fade" id="connections" style="min-height: 600px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0" style="color: var(--primary-maroon);">Visualization Mapping</h4>
        <span class="badge bg-light text-dark border"><i class="bi bi-info-circle me-1"></i> Interactive</span>
    </div>
    
    <p class="text-muted small mb-4">
        Visualizing manuscripts in the Union Catalogue related by Subject. Click on connected nodes to explore their metadata.
    </p>

    <div id="network-graph" style="width: 100%; height: 500px; background-color: #fafafa; border-radius: 12px; border: 1px solid #eee; position: relative;"></div>
</div>

            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // 1. Move drawGraph outside or ensure it's defined before usage
function drawGraph() {
    var container = document.getElementById('network-graph');
    if (!container || container.children.length > 0) return;

    // Set height to ensure visibility
    container.style.height = "500px";

    var mainNodeId = <?php echo $data['manuscript']['id']; ?>;
    var mainTitle = "<?php echo addslashes($data['manuscript']['Title']); ?>";

    var nodes = [{
        id: mainNodeId, 
        label: mainTitle.length > 20 ? mainTitle.substring(0, 20) + '...' : mainTitle, 
        title: mainTitle,
        color: { background: '#6d0828', border: '#b58428' },
        font: { color: '#6e6d6dff', size: 16 },
        shape: 'dot', size: 40
    }];
    
    var edges = [];

    <?php if (!empty($data['connections'])): ?>
        <?php foreach ($data['connections'] as $conn): ?>
            nodes.push({
                id: <?php echo $conn['id']; ?>,
                label: "<?php echo addslashes(mb_strimwidth($conn['Title'], 0, 15, "...")); ?>",
                title: "<?php echo addslashes($conn['Title']); ?>",
                color: { background: '#b58428', border: '#6d0828' },
                font: { size: 14, color: '#333' },
                shape: 'dot', size: 25
            });
            edges.push({ from: mainNodeId, to: <?php echo $conn['id']; ?>, color: '#6d0828', length: 200 });
        <?php endforeach; ?>
    <?php endif; ?>

    var data = { nodes: new vis.DataSet(nodes), edges: new vis.DataSet(edges) };
    var options = {
        physics: { barnesHut: { gravitationalConstant: -3000, springLength: 150 } },
        interaction: { hover: true }
    };
    
    var network = new vis.Network(container, data, options);
    
    network.on("click", function(p) { 
        if (p.nodes.length && p.nodes[0] != mainNodeId) {
            window.location.href = "index.php?action=metadata&id=" + p.nodes[0];
        }
    });
}

// Logic to trigger the graph when the tab is clicked
document.addEventListener("DOMContentLoaded", function() {
    const tabEls = document.querySelectorAll('button[data-bs-toggle="tab"]');
    tabEls.forEach(tabEl => {
        tabEl.addEventListener('shown.bs.tab', function (event) {
            const target = event.target.getAttribute('data-bs-target');
            if (target === '#connections') {
                // Check if graph is already drawn; if not, draw it.
                // If it IS drawn, we tell Vis.js to redraw to fit the new tab size.
                if (document.getElementById('network-graph').children.length === 0) {
                    setTimeout(drawGraph, 200); 
                } else {
                    // This is the key: it forces the graph to recalculate its 
                    // size now that the white tab is visible.
                    window.network && window.network.redraw();
                }
            }
        });
    });
});
// 2. Tab Management & Event Listeners
document.addEventListener("DOMContentLoaded", function() {
    
    // --- 1. HANDLE TAB PERSISTENCE ON RELOAD ---
    // This checks the URL for #location, #citation, etc.
    const currentHash = window.location.hash;
    if (currentHash) {
        // Find the button that corresponds to this hash
        const targetTab = document.querySelector('button[data-bs-target="' + currentHash + '"]');
        if (targetTab) {
            // Use Bootstrap's API to show the tab
            const tabInstance = bootstrap.Tab.getOrCreateInstance(targetTab);
            tabInstance.show();
            
            // Special Case: If it's connections, draw the graph
            if (currentHash === '#connections') {
                setTimeout(drawGraph, 300);
            }
        }
    }

    // --- 2. UPDATE URL HASH WHEN CLICKING TABS ---
    const tabEls = document.querySelectorAll('button[data-bs-toggle="tab"]');
    tabEls.forEach(tabEl => {
        tabEl.addEventListener('shown.bs.tab', function (event) {
            const target = event.target.getAttribute('data-bs-target');
            
            // Update the URL in the address bar without reloading
            history.replaceState(null, null, target);

            // Trigger graph only if connections is selected
            if (target === '#connections') {
                drawGraph();
            }
        });
    });

    // --- 3. FIELD EDIT LOGIC (KEEP AS IS) ---
   // Location: metadata.php (within the <script> section)
document.querySelectorAll('.edit-trigger').forEach(trigger => {
    trigger.addEventListener('click', function() {
        const fieldName = this.getAttribute('data-field');
        
        // Find the text near this trigger
        // We look for the parent "info-value" div and get the text inside it
        const parentDiv = this.closest('.info-value');
        let currentText = parentDiv ? parentDiv.innerText.replace('Information missing', '').trim() : '';
        
        // Update Modal Fields
        document.getElementById('modalFieldName').value = fieldName;
        document.getElementById('displayFieldName').innerText = fieldName;
        document.getElementById('suggestionInput').value = "";

        // Update Reference Display
        const refContainer = document.getElementById('currentValueContainer');
        const refText = document.getElementById('currentValueText');

        if (currentText && currentText !== "") {
            refText.innerText = currentText;
            refContainer.style.display = 'block';
        } else {
            refContainer.style.display = 'none';
        }
    });
});

    // Locate your flag-trigger listener in the <script> tag
document.querySelectorAll('.flag-trigger').forEach(trigger => {
    trigger.addEventListener('click', function() {
        const workId = this.getAttribute('data-work-id');
        const workTitle = this.getAttribute('data-work-title');
        
        // --- NEW LOGIC: Capture the type from the trigger ---
        const targetType = this.getAttribute('data-target-type'); 
        
        // Populate the modal fields
        document.getElementById('flagWorkId').value = workId;
        document.getElementById('flagWorkTitle').innerText = workTitle;
        
        // Set the hidden type field for the controller
        document.getElementById('flagTargetType').value = targetType; 
    });
});
});
</script>

<div class="modal fade" id="suggestModal" tabindex="-1" aria-labelledby="suggestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white" style="background-color: var(--primary-maroon);">
                <h5 class="modal-title fw-bold" id="suggestModalLabel"><i class="bi bi-pencil-square me-2"></i>Contribute Information</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="index.php?action=submit_suggestion" method="POST">
                <div class="modal-body p-4">
    <input type="hidden" name="manuscript_id" value="<?php echo $data['manuscript']['id']; ?>">
    <input type="hidden" name="field_name" id="modalFieldName">
    
    <div class="mb-3">
        <p class="text-muted small mb-1">You are contributing information for:</p>
        <h6 id="displayFieldName" class="fw-bold text-dark text-uppercase" style="letter-spacing: 1px;"></h6>
    </div>

    <div id="currentValueContainer" class="mb-3 p-2 rounded border" style="background-color: #f8f9fa; display: none;">
        <label class="info-label" style="font-size: 0.65rem; color: #666;">Current Record:</label>
        <div id="currentValueText" class="small fw-bold text-muted"></div>
    </div>
    
    <div class="mb-3">
        <label class="info-label d-block">Your Suggestion</label>
        <textarea name="suggested_value" id="suggestionInput" class="form-control" rows="5" required placeholder="Enter the corrected or missing information..."></textarea>
    </div>
    
    <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-0" style="background-color: #e7f3ff;">
        <i class="bi bi-info-circle-fill me-2 fs-5 text-primary"></i>
        <small class="text-dark">Your contribution will be reviewed by an expert before being updated in the live catalogue.</small>
    </div>
</div>
                <div class="modal-footer border-0 py-3" style="background-color: #f8f9fa;">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gold rounded-pill px-4">Submit Suggestion</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="imageUploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: var(--primary-maroon);">
                <h5 class="modal-title fw-bold">Submit Cover Image</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="index.php?action=submit_suggestion" method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <input type="hidden" name="manuscript_id" value="<?php echo $data['manuscript']['id']; ?>">
                    
                    <input type="hidden" name="field_name" value="Cover Image">
                    
                    <div class="mb-3">
                        <label class="info-label">Select Manuscript Image</label>
                        <input type="file" name="manuscript_image" class="form-control" accept="image/*" required>
                        <div class="form-text small">Please upload a clear photo of the manuscript's first page or cover.</div>
                    </div>

                    <div class="alert alert-warning border-0 small py-2 mb-0">
                        <i class="bi bi-shield-check me-2"></i>Contributions will be reviewed by experts before being displayed live.
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gold rounded-pill px-4">Upload for Review</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="flagModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle me-2"></i>Report Irrelevant Content</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="index.php?action=submit_flag" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" name="work_id" id="flagWorkId">
                    <input type="hidden" name="manuscript_id" value="<?php echo $data['manuscript']['id']; ?>">

                    <input type="hidden" name="target_type" id="flagTargetType">
                    
                    <p class="small text-muted mb-3">You are reporting: <br><strong id="flagWorkTitle" class="text-dark"></strong></p>
                    
                    <div class="mb-3">
                        <label class="info-label">Reason for Flagging</label>
                        <textarea name="reason" class="form-control" rows="4" required 
                                  placeholder="Explain why this content is not related to this specific manuscript..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">Submit Flag</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
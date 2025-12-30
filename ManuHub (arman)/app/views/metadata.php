<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($data['manuscript']['Title']); ?></title>
    
    <link rel="stylesheet" href="../public/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css"> <script type="text/javascript" src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>

</head>
<body class="">

    <?php include('header.php'); ?>

    <div class="container mt-5 mb-5">
        <div class="text-center mb-5">
            <h1 class="fw-bold"><?php echo htmlspecialchars($data['manuscript']['Title']); ?></h1>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4 text-center">
                <div class="bg-white p-3 rounded shadow-sm">
                    <img src="../assets/images/<?php echo htmlspecialchars($data['manuscript']['image_url'] ?? 'default.jpg'); ?>" 
                         class="img-fluid rounded" 
                         alt="Manuscript Cover">
                    <p class="mt-3 text-muted fst-italic"><?php echo htmlspecialchars($data['manuscript']['Title']); ?></p>
                    <p class="mt-3 text-muted fst-italic"><?php echo htmlspecialchars($data['manuscript']['Subject']); ?></p>
                </div>
            </div>

            <div class="col-md-8">
                <ul class="nav nav-tabs border-bottom-0" id="metadataTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="about-tab" data-bs-toggle="tab" data-bs-target="#about" type="button">About</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="location-tab" data-bs-toggle="tab" data-bs-target="#location" type="button">Location</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="contributor-tab" data-bs-toggle="tab" data-bs-target="#contributor" type="button">Contributor</button>
                    </li>
                    <li class="nav-item">
                         <button class="nav-link" id="related-work-tab" data-bs-toggle="tab" data-bs-target="#related-work" type="button">Related Works</button>
                    </li>
                    
                    <li class="nav-item">
                         <button class="nav-link" id="citation-tab" data-bs-toggle="tab" data-bs-target="#citation" type="button">Cited By</button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" id="connections-tab" data-bs-toggle="tab" data-bs-target="#connections" type="button">Connections Map</button>
                    </li>
                </ul>

                <div class="tab-content bg-white p-4 rounded shadow-sm border" id="metadataTabsContent">
                    
                    <div class="tab-pane fade show active" id="about">
                        <table class="table table-bordered">
                            <tr><th class="bg-light w-25">Title</th><td><?php echo htmlspecialchars($data['manuscript']['Title']); ?></td></tr>
                            <tr><th class="bg-light">Author</th><td><?php echo htmlspecialchars($data['manuscript']['Author']); ?></td></tr>
                            <tr><th class="bg-light">Subject</th><td><?php echo htmlspecialchars($data['manuscript']['Subject']); ?></td></tr>
                            <tr><th class="bg-light">Description</th><td><?php echo nl2br(htmlspecialchars($data['manuscript']['Description'])); ?></td></tr>
                            <tr><th class="bg-light">Language</th><td><?php echo htmlspecialchars($data['manuscript']['Language'] ?? '-'); ?></td></tr>
                            <tr><th class="bg-light">Genre</th><td><?php echo htmlspecialchars($data['manuscript']['Genre'] ?? '-'); ?></td></tr>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="location">
                        <table class="table table-bordered">
                            <tr><th class="bg-light w-25">Location</th><td><?php echo htmlspecialchars($data['manuscript']['Location_of_Manuscript']); ?></td></tr>
                            <tr><th class="bg-light">Country</th><td><?php echo htmlspecialchars($data['manuscript']['Country']); ?></td></tr>
                            <tr><th class="bg-light">Call Number</th><td><?php echo htmlspecialchars($data['manuscript']['Call_Number'] ?? '-'); ?></td></tr>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="contributor">
                        <table class="table table-bordered">
                            <tr><th class="bg-light w-25">Name</th><td><?php echo htmlspecialchars($data['manuscript']['contributor_name'] ?? 'Admin'); ?></td></tr>
                            <tr><th class="bg-light">Upload Date</th><td><?php echo htmlspecialchars($data['manuscript']['create_dat']); ?></td></tr>
                            <tr><th class="bg-light">Status</th>
                                <td>
                                    <span class="badge bg-<?php echo ($data['manuscript']['status'] == 'approved') ? 'success' : 'warning'; ?>">
                                        <?php echo ucfirst($data['manuscript']['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="related-work">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Related Academic Resources</h5>
                            <span class="badge bg-secondary">Auto-Discovered</span>
                        </div>

                        <ul class="list-group mb-4">
                            <?php if (!empty($data['related_works'])): ?>
                                <?php foreach ($data['related_works'] as $work): ?>
                                    <?php if (!isset($work['type']) || $work['type'] != 'citation'): ?>
                                    <li class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between align-items-center">
                                            <h6 class="mb-1 text-primary me-3">
                                                <a href="<?php echo htmlspecialchars($work['url']); ?>" target="_blank" class="text-decoration-none text-primary">
                                                    <?php echo htmlspecialchars($work['title']); ?>
                                                </a>
                                            </h6>
                                            <?php 
                                                $badgeClass = 'bg-secondary';
                                                if ($work['category'] == 'PDF Document') $badgeClass = 'bg-danger';
                                                if ($work['category'] == 'Research Paper') $badgeClass = 'bg-primary';
                                            ?>
                                            <span class="badge <?php echo $badgeClass; ?> flex-shrink-0" style="min-width: 110px; text-align: center;">
                                                <?php echo htmlspecialchars($work['category']); ?>
                                            </span>
                                        </div>
                                        <small class="text-muted text-truncate d-block" style="max-width: 600px;">
                                            <?php echo htmlspecialchars($work['url']); ?>
                                        </small>
                                    </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="list-group-item text-center p-5">
                                    <p class="text-muted mb-0">We searched the web but couldn't find specific open-access resources for this title right now.</p>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div class="tab-pane fade" id="citation" role="tabpanel" aria-labelledby="citation-tab">
                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">Papers Citing This Manuscript</h5>
                                    
                                    <a href="index.php?action=auto_discover&id=<?php echo $data['manuscript']['id']; ?>&source=citation" 
                                       class="btn btn-sm btn-primary">
                                        <i class="bi bi-search"></i> Scan Citations
                                    </a>
                                </div>

                                <ul class="list-group list-group-flush">
                                    <?php 
                                    $hasCitations = false;
                                    if (!empty($data['related_works'])): 
                                        foreach ($data['related_works'] as $work): 
                                            // FILTER: Only show items where type is 'citation'
                                            if (isset($work['type']) && $work['type'] == 'citation'): 
                                                $hasCitations = true;
                                    ?>
                                        <li class="list-group-item px-0">
                                            <div class="d-flex w-100 justify-content-between align-items-center">
                                                <h6 class="mb-1 text-primary me-3">
                                                    <a href="<?php echo htmlspecialchars($work['url']); ?>" target="_blank" class="text-decoration-none text-primary">
                                                        <?php echo htmlspecialchars($work['title']); ?>
                                                    </a>
                                                </h6>
                                                <span class="badge bg-success flex-shrink-0" style="min-width: 110px; text-align: center;">
                                                    Cited By
                                                </span>
                                            </div>
                                            <small class="text-muted text-truncate d-block" style="max-width: 600px;">
                                                Source: <?php echo htmlspecialchars($work['url']); ?>
                                            </small>
                                        </li>
                                    <?php 
                                            endif; 
                                        endforeach; 
                                    endif;
                                    
                                    if (!$hasCitations): 
                                    ?>
                                        <li class="list-group-item text-center p-4 text-muted border-0 bg-light rounded">
                                            <i class="bi bi-journal-x mb-2" style="font-size: 2rem;"></i><br>
                                            No citations found yet.<br>
                                            <small>Click the "Scan Citations" button to search OpenAlex for papers referencing this title.</small>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="connections">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Knowledge Graph</h5>
                            <span class="badge bg-success">Similar Subject: <?php echo htmlspecialchars($data['manuscript']['Subject']); ?></span>
                        </div>
                        <p class="text-muted small">Visualizing other manuscripts in the ManuHub database that share the same subject.</p>
                        
                        <div id="network-graph"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // --- 1. DEFINE THE GRAPH FUNCTION ---
        function drawGraph() {
            var container = document.getElementById('network-graph');
            
            // Safety check
            if (container.children.length > 0) return;

            var mainNodeId = <?php echo $data['manuscript']['id']; ?>;
            // Get full title for tooltip, safe for JS
            var mainTitleFull = "<?php echo addslashes($data['manuscript']['Title']); ?>";
            // Create short label for display
            var mainLabel = mainTitleFull.length > 20 ? mainTitleFull.substring(0, 20) + '...' : mainTitleFull;
            
            var nodes = [];
            var edges = [];

            // --- ADD MAIN NODE (RED) ---
            nodes.push({
                id: mainNodeId, 
                label: mainLabel,       // Short Name
                title: mainTitleFull,   // Full Name (Tooltip on Hover)
                color: { background: '#e74c3c', border: '#c0392b' },
                font: { color: 'grey', size: 16, face: 'arial' },
                shape: 'dot',
                size: 40,               // Make main node slightly bigger
                borderWidth: 2,
                shadow: true
            });

            // --- ADD CONNECTED NODES (BLUE) ---
            <?php if (!empty($data['connections'])): ?>
                <?php foreach ($data['connections'] as $conn): ?>
                    
                    var fullTitle = "<?php echo addslashes($conn['Title']); ?>";
                    var shortLabel = fullTitle.length > 15 ? fullTitle.substring(0, 15) + '...' : fullTitle;

                    nodes.push({
                        id: <?php echo $conn['id']; ?>,
                        label: shortLabel,  // Short Name
                        title: fullTitle,   // Full Name (Tooltip)
                        color: { background: '#3498db', border: '#2980b9' },
                        font: { size: 14, color: '#333', background: 'white' }, // Clean text with white backing
                        shape: 'dot',
                        size: 25,
                        shadow: true
                    });

                    // Add the line connection
                    edges.push({ 
                        from: mainNodeId, 
                        to: <?php echo $conn['id']; ?>,
                        length: 250 // Force lines to be longer (pushes nodes away)
                    });
                <?php endforeach; ?>
            <?php endif; ?>

            var data = { nodes: new vis.DataSet(nodes), edges: new vis.DataSet(edges) };
            
            // --- "RESEARCH RABBIT" STYLE OPTIONS ---
            var options = {
                nodes: {
                    borderWidth: 2,
                    shapeProperties: {
                        interpolation: false    // Keeps images/shapes crisp
                    }
                },
                edges: {
                    width: 1,
                    color: { color: '#bdc3c7', opacity: 0.6 },
                    smooth: {
                        type: 'continuous' // Curvy lines look more organic
                    }
                },
                physics: { 
                    // 'forceAtlas2Based' is great for networks like Research Rabbit
                    solver: 'forceAtlas2Based',
                    forceAtlas2Based: {
                        gravitationalConstant: -100, // Strong repulsion (pushes nodes apart)
                        centralGravity: 0.005,       // Very weak pull to center (lets them float)
                        springConstant: 0.08,
                        springLength: 200,           // Long springs = spacious graph
                        damping: 0.4,
                        avoidOverlap: 1              // STRICTLY prevent nodes from covering each other
                    },
                    stabilization: { iterations: 150 } // Pre-calculate layout so it doesn't jump around
                },
                interaction: { 
                    hover: true,      // Enable the Tooltip
                    dragNodes: true,  // Allow user to rearrange them
                    zoomView: true,
                    dragView: true
                }
            };

            var network = new vis.Network(container, data, options);
            
            // Click Event to go to page
            network.on("click", function (params) {
                if (params.nodes.length > 0) {
                    var clickedId = params.nodes[0];
                    if (clickedId !== mainNodeId) {
                        window.location.href = "index.php?action=metadata&id=" + clickedId;
                    }
                }
            });
        }

        // --- 2. SETUP LISTENERS ---
        var tabList = document.querySelectorAll('button[data-bs-toggle="tab"]');
        tabList.forEach(function(tabEl) {
            tabEl.addEventListener('shown.bs.tab', function (event) {
                var targetId = event.target.getAttribute('data-bs-target');
                history.replaceState(null, null, targetId);

                if (targetId === '#connections') {
                    drawGraph();
                }
            });
        });

        // --- 3. CHECK HASH ON LOAD ---
        var hash = window.location.hash;
        if (hash) {
            var triggerEl = document.querySelector('button[data-bs-target="' + hash + '"]');
            if (triggerEl) {
                var tab = new bootstrap.Tab(triggerEl);
                tab.show(); 
            }
        }
    });
    </script>
</body>
</html>

<style>

    body {
    background-color: #fff8e1;  /*Very light, professional gray background */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333;
    line-height: 1.6;
    margin: 0;
    padding: 0;

    }

    /* Center content and add padding to non-full-width elements (like h2, search, etc.) */
    h1 {
        text-align: center;
        margin-top: 40px;
        margin-bottom: 20px;
        font-size: 40px;
        /* Kept the original green-ish color for now, but will be overwritten by .center-item h2 */
        color: #6d0828ff; 
    }

    .nav-tabs .nav-link { 
        color: #333; 
        background-color: #e0e0e0ff;
        border-color: #0000001f;
        border-width: 0.5px;
        font-weight: 500; 
    }

    .nav-tabs .nav-link.active { 
        color: #000; 
        border-bottom: 3px solid #f0ad4e; 
        font-weight: bold; 
    }

    .tab-content { 
        min-height: 300px; 
    }

    .badge { 
        font-weight: 500; 
        letter-spacing: 0.5px; 
    }

    #network-graph {
        width: 100%;
        height: 450px;
        border: 1px solid #e0e0e0;
        background-color: #fafafa;
        border-radius: 8px;
    }
</style>
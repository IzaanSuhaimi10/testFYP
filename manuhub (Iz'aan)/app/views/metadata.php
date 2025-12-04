<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($data['manuscript']['title']); ?></title>
    
    <link rel="stylesheet" href="../public/styles.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Custom tweaks to match your Figma clean look */
        .nav-tabs .nav-link { color: #333; font-weight: 500; }
        .nav-tabs .nav-link.active { color: #000; border-bottom: 3px solid #f0ad4e; font-weight: bold; }
        .tab-content { min-height: 300px; }
    </style>
</head>
<body class="bg-light">

    <?php include('header.php'); ?>

    <div class="container mt-5 mb-5">
        <div class="text-center mb-5">
            <h1 class="fw-bold"><?php echo htmlspecialchars($data['manuscript']['title']); ?></h1>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4 text-center">
                <div class="bg-white p-3 rounded shadow-sm">
                    <img src="assets/images/<?php echo htmlspecialchars($data['manuscript']['image_url'] ?? 'default.jpg'); ?>" 
                         class="img-fluid rounded" 
                         alt="Manuscript Cover">
                    <p class="mt-3 text-muted fst-italic"><?php echo htmlspecialchars($data['manuscript']['title']); ?></p>
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
                        <button class="nav-link" id="sources-tab" data-bs-toggle="tab" data-bs-target="#sources" type="button">Sources</button>
                    </li>
                </ul>

                <div class="tab-content bg-white p-4 rounded shadow-sm border" id="metadataTabsContent">
                    
                    <div class="tab-pane fade show active" id="about">
    <table class="table table-bordered">
        <tr><th class="bg-light w-25">Title</th><td><?php echo htmlspecialchars($data['manuscript']['title']); ?></td></tr>
        <tr><th class="bg-light">Author</th><td><?php echo htmlspecialchars($data['manuscript']['author']); ?></td></tr>
        <tr><th class="bg-light">Field</th><td><?php echo htmlspecialchars($data['manuscript']['field']); ?></td></tr>
        <tr><th class="bg-light">Description</th><td><?php echo nl2br(htmlspecialchars($data['manuscript']['description'])); ?></td></tr>
    </table>
</div>

                    <div class="tab-pane fade" id="location">
                        <table class="table table-bordered">
                            <tr><th class="bg-light w-25">Origin</th><td><?php echo htmlspecialchars($data['manuscript']['origin_place']); ?></td></tr>
                            <tr><th class="bg-light">Current Repository</th><td><?php echo htmlspecialchars($data['manuscript']['current_repository']); ?></td></tr>
                            <tr><th class="bg-light">City, State</th><td><?php echo htmlspecialchars($data['manuscript']['city_state']); ?></td></tr>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="contributor">
                        <table class="table table-bordered">
                            <tr><th class="bg-light w-25">Name</th><td><?php echo htmlspecialchars($data['manuscript']['contributor_name'] ?? 'Unknown'); ?></td></tr>
                            <tr><th class="bg-light">Upload Date</th><td><?php echo htmlspecialchars($data['manuscript']['created_at']); ?></td></tr>
                            <tr><th class="bg-light">Status</th>
                                <td>
                                    <span class="badge bg-<?php echo ($data['manuscript']['status'] == 'approved') ? 'success' : 'warning'; ?>">
                                        <?php echo ucfirst($data['manuscript']['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="sources">
                        <div class="list-group mb-4">
                            <?php if (empty($data['sources'])): ?>
                                <p class="text-muted">No external sources added yet.</p>
                            <?php else: ?>
                                <?php foreach ($data['sources'] as $source): ?>
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($source['category']); ?></h6>
                                        <small class="text-muted">Verified</small>
                                    </div>
                                    <p class="mb-1 text-primary">
                                        <a href="<?php echo htmlspecialchars($source['url']); ?>" target="_blank" class="text-decoration-none">
                                            <?php echo htmlspecialchars($source['title']); ?>
                                        </a>
                                    </p>
                                    <small class="text-muted"><?php echo htmlspecialchars($source['url']); ?></small>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <h6 class="card-title fw-bold mb-3">Add New Source (Auto-Fetch Title)</h6>
                                <form action="index.php?action=add_source" method="POST">
                                    <input type="hidden" name="manuscript_id" value="<?php echo $data['manuscript']['id']; ?>">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <input type="text" name="category" class="form-control" placeholder="Category (e.g. History)" required>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="url" name="url" class="form-control" placeholder="Paste URL here..." required>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-dark w-100">Add</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
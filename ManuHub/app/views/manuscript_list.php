<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Manuscripts - ManuHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-maroon: #6d0828;
            --accent-gold: #b58428;
        }

        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }

        .browse-header {
            background-color: var(--primary-maroon);
            color: white;
            padding: 60px 0;
            border-bottom: 4px solid var(--accent-gold);
            margin-bottom: 40px;
        }

        /* 2x10 Grid Card Styling */
        .result-card {
            border: none;
            border-radius: 12px;
            transition: 0.3s ease;
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border-top: 4px solid transparent;
            height: 100%; 
            display: flex;
            flex-direction: column;
        }

        .result-card:hover {
            transform: translateY(-5px);
            border-top: 4px solid var(--accent-gold);
            box-shadow: 0 8px 20px rgba(109, 8, 40, 0.1);
        }

        .btn-view-ms {
            background-color: var(--primary-maroon);
            color: white;
            border-radius: 50px;
            font-weight: 600;
            padding: 8px 20px;
            font-size: 0.85rem;
            transition: 0.3s;
            margin-top: auto; 
        }

        .btn-view-ms:hover {
            background-color: var(--accent-gold);
            color: white;
        }

        .search-stats {
            color: var(--primary-maroon);
            font-weight: 700;
            border-bottom: 2px solid var(--accent-gold);
            display: inline-block;
            margin-bottom: 20px;
        }

        /* Pagination Styling */
        .pagination .page-link {
            border: none;
            margin: 0 3px;
            border-radius: 8px !important;
            font-weight: 600;
            color: var(--primary-maroon);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary-maroon);
            color: white !important;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    
    <?php include('header.php'); ?>

    <div class="browse-header text-center">
        <div class="container">
            <h1 class="fw-bold">Manuscript Catalogue</h1>
            <p class="lead">Exploring the wealth of Malay heritage through recorded history.</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="row">
            <div class="col-lg-3 mb-4">
                <div class="card border-0 shadow-sm p-3" style="border-radius: 15px;">
                    <h5 class="fw-bold mb-3" style="color: var(--primary-maroon);">Refine Search</h5>
                    <form action="index.php" method="GET" id="searchForm">
                        <input type="hidden" name="action" value="manuscript_list">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Live Wildcard Search</label>
                            <input type="text" id="liveSearch" name="search" class="form-control" 
                                   value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" 
                                   placeholder="Type title or author...">
                            <div class="form-text text-muted mt-2" style="font-size: 0.75rem;">
                                Filtering results shown on this page.
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn text-white fw-bold shadow-sm" style="background-color: var(--accent-gold);">
                                <i class="bi bi-search me-1"></i> Apply Filters
                            </button>
                            
                            <a href="index.php?action=manuscript_list" class="btn btn-outline-secondary fw-bold shadow-sm">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset All
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="search-stats">
                    <span id="matchCount"><?php echo count($data['manuscripts'] ?? []); ?></span> Manuscripts Found
                </div>

                <div class="row g-4" id="manuscriptGrid">
                    <?php if (!empty($data['manuscripts'])): ?>
                        <?php foreach ($data['manuscripts'] as $ms): ?>
                            <div class="col-md-6 manuscript-item">
                                <div class="card result-card p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge" style="background-color: var(--accent-gold);">
                                            <?php echo htmlspecialchars($ms['Genre'] ?? 'General'); ?>
                                        </span>
                                    </div>
                                    <h5 class="fw-bold ms-title" style="color: var(--primary-maroon); min-height: 3rem;">
                                        <?php echo htmlspecialchars($ms['Title']); ?>
                                    </h5>
                                    <p class="text-muted small mb-3 ms-author">
                                        <i class="bi bi-person-fill me-1"></i> <?php echo htmlspecialchars($ms['Author'] ?? 'Unknown Author'); ?> <br>
                                        <i class="bi bi-journal-text me-1"></i> <?php echo htmlspecialchars($ms['Subject'] ?? 'N/A'); ?>
                                    </p>
                                    <a href="index.php?action=metadata&id=<?php echo $ms['id']; ?>" class="btn btn-view-ms w-100">
                                        View Metadata <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-5">
                            <div class="alert alert-info border-0 shadow-sm rounded-4 p-5">
                                <i class="bi bi-search fs-1 mb-3 d-block"></i>
                                <h4>No Manuscripts Found</h4>
                                <a href="index.php?action=manuscript_list" class="btn btn-outline-dark mt-2">Clear Search</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($data['totalPages'] > 1): ?>
                <nav aria-label="Page navigation" class="mt-5">
                    <ul class="pagination justify-content-center flex-wrap">
                        <li class="page-item <?php echo ($data['currentPage'] <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link shadow-sm" href="index.php?action=manuscript_list&page=<?php echo $data['currentPage'] - 1; ?>&search=<?php echo urlencode($data['search']); ?>">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>

                        <?php
                        $current = (int)$data['currentPage'];
                        $total = (int)$data['totalPages'];
                        $adjacents = 2;

                        if ($current > ($adjacents + 1)) {
                            echo '<li class="page-item"><a class="page-link shadow-sm" href="index.php?action=manuscript_list&page=1&search='.urlencode($data['search']).'">1</a></li>';
                            if ($current > ($adjacents + 2)) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }

                        $pmin = ($current > $adjacents) ? ($current - $adjacents) : 1;
                        $pmax = ($current < ($total - $adjacents)) ? ($current + $adjacents) : $total;

                        for ($i = $pmin; $i <= $pmax; $i++) {
                            $activeClass = ($current == $i) ? 'active' : '';
                            echo '<li class="page-item '.$activeClass.'">
                                    <a class="page-link shadow-sm" href="index.php?action=manuscript_list&page='.$i.'&search='.urlencode($data['search']).'">'.$i.'</a>
                                  </li>';
                        }

                        if ($current < ($total - $adjacents)) {
                            if ($current < ($total - $adjacents - 1)) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            echo '<li class="page-item"><a class="page-link shadow-sm" href="index.php?action=manuscript_list&page='.$total.'&search='.urlencode($data['search']).'">'.$total.'</a></li>';
                        }
                        ?>

                        <li class="page-item <?php echo ($data['currentPage'] >= $data['totalPages']) ? 'disabled' : ''; ?>">
                            <a class="page-link shadow-sm" href="index.php?action=manuscript_list&page=<?php echo $data['currentPage'] + 1; ?>&search=<?php echo urlencode($data['search']); ?>">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>

   <script>
const searchInput = document.getElementById('liveSearch');
const grid = document.getElementById('manuscriptGrid');
const countDisplay = document.getElementById('matchCount');

searchInput.addEventListener('input', function() {
    const term = this.value.trim();

    // Fetch from database via AJAX
    fetch(`index.php?action=live_search&term=${encodeURIComponent(term)}`)
        .then(response => response.json())
        .then(data => {
            grid.innerHTML = ''; // Clear current cards
            countDisplay.textContent = data.length;

            if (data.length > 0) {
                data.forEach(ms => {
                    grid.innerHTML += `
                        <div class="col-md-6 manuscript-item animate__animated animate__fadeIn">
                            <div class="card result-card p-4">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge" style="background-color: var(--accent-gold);">
                                        ${ms.Genre || 'General'}
                                    </span>
                                </div>
                                <h5 class="fw-bold ms-title" style="color: var(--primary-maroon); min-height: 3rem;">
                                    ${ms.Title}
                                </h5>
                                <p class="text-muted small mb-3 ms-author">
                                    <i class="bi bi-person-fill me-1"></i> ${ms.Author || 'Unknown Author'} <br>
                                    <i class="bi bi-journal-text me-1"></i> ${ms.Subject || 'N/A'}
                                </p>
                                <a href="index.php?action=metadata&id=${ms.id}" class="btn btn-view-ms w-100">
                                    View Metadata <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>`;
                });
            } else {
                grid.innerHTML = '<div class="col-12 text-center py-5"><p class="text-muted">No manuscripts found in the database.</p></div>';
            }
        });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
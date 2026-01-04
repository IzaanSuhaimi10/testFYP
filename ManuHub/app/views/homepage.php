<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ManuHub - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../public/styles.css">

    <style>
        :root {
            --primary-maroon: #6d0828;
            --accent-gold: #b58428;
            --bg-cream: #fff8e1;
        }

        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }

        /* Elegant Hero Section */
        .hero-banner {
            background: linear-gradient(rgba(109, 8, 40, 0.8), rgba(109, 8, 40, 0.8)), 
                        url('../assets/images/manuscript.jpg') center/cover;
            padding: 100px 0;
            color: white;
            border-bottom: 4px solid var(--accent-gold);
        }

        /* Professional Search Bar */
        .search-container {
            max-width: 700px;
            margin: -40px auto 50px;
            z-index: 10;
            position: relative;
        }
        .search-input-group {
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border-radius: 50px;
            overflow: hidden;
            background: white;
            padding: 8px;
        }
        .search-input-group input { border: none; padding-left: 25px; }
        .search-input-group .btn-search {
            background: var(--primary-maroon);
            color: white;
            border-radius: 50px !important;
            padding: 10px 30px;
            font-weight: 700;
        }

        /* Manuscript Cards */
        .ms-card {
            border: none;
            border-radius: 15px;
            transition: 0.3s;
            background: white;
            border-bottom: 4px solid var(--accent-gold);
            height: 100%;
        }
        .ms-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(109, 8, 40, 0.1);
        }
        .ms-card h5 { color: var(--primary-maroon); font-weight: 700; }
        .ms-category { color: var(--accent-gold); font-size: 11px; font-weight: 800; text-transform: uppercase; }

        /* Carousel Controls */
        .carousel-control-prev, .carousel-control-next {
            width: 50px;
            height: 50px;
            background: var(--primary-maroon);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 1;
        }

        .ms-card {
        border: none;
        border-radius: 15px;
        transition: 0.3s;
        background: white;
        border-bottom: 4px solid #b58428; /* Gold Accent */
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .ms-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(109, 8, 40, 0.1);
    }
    
    .see-more-btn {
        background-color: #6d0828; /* Maroon */
        color: white;
        padding: 12px 40px;
        border-radius: 50px;
        font-weight: 700;
        border: 2px solid #b58428; /* Gold */
        transition: 0.3s;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .see-more-btn:hover {
        background-color: #b58428;
        color: white;
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(181, 132, 40, 0.4);
    }
    </style>
</head>
<body>
    <?php include('header.php'); ?>

    <section class="hero-banner text-center">
        <div class="container">
            <h1 class="display-3 fw-bold">ManuHub</h1>
            <p class="lead">A collaborative Union Catalogue System for Malay Manuscripts</p>
        </div>
    </section>

    <div class="container search-container">
        <form action="index.php" method="GET">
            <input type="hidden" name="action" value="manuscript_list">
            <input type="hidden" name="page" value="1">
            <div class="input-group search-input-group">
                <input type="text" name="search" class="form-control" placeholder="Search Your Manuscript..." required>
                <button class="btn btn-search" type="submit">SEARCH</button>
            </div>
        </form>
    </div>

    <div class="container mb-5">
    <h2 class="text-center mb-5 fw-bold" style="color: #6d0828; text-transform: uppercase;">
        Collection
    </h2>
    
    <div id="manuscriptCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php if(!empty($data['manuscripts'])): ?>
                <?php 
                    // Use the existing collection from the Controller
                    $chunks = array_chunk($data['manuscripts'], 3); 
                    foreach ($chunks as $index => $chunk): 
                ?>
                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                    <div class="row g-4 justify-content-center px-5">
                        <?php foreach ($chunk as $ms): ?>
                            <div class="col-md-4">
                                <div class="card ms-card p-4 h-100">
                                    <div class="ms-category" style="color: #b58428; font-size: 11px; font-weight: 800; text-transform: uppercase;">
                                        <?php echo htmlspecialchars($ms['Genre'] ?? 'Manuscript'); ?>
                                    </div>
                                    <h5 class="mt-2" style="color: #6d0828; font-weight: 700;">
                                        <?php echo htmlspecialchars($ms['Title']); ?>
                                    </h5>
                                    <p class="text-muted small"><?php echo htmlspecialchars($ms['Subject']); ?></p>
                                    <a href="index.php?action=metadata&id=<?php echo $ms['id']; ?>" class="stretched-link text-decoration-none" style="color: #6d0828; font-weight: 600;">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <button class="carousel-control-prev" type="button" data-bs-target="#manuscriptCarousel" data-bs-slide="prev" style="width: 5%; background: rgba(109, 8, 40, 0.2); border-radius: 10px;">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#manuscriptCarousel" data-bs-slide="next" style="width: 5%; background: rgba(109, 8, 40, 0.2); border-radius: 10px;">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <div class="text-center mt-5">
        <a href="index.php?action=manuscript_list" class="btn see-more-btn">
            <i class="bi bi-arrow-right-circle-fill me-2"></i> SEE ALL MANUSCRIPTS
        </a>
    </div>

    <div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header py-3 text-white text-center" style="background-color: #6d0828; border-bottom: 4px solid #b58428;">
                    <h3 class="mb-0 fw-bold" style="letter-spacing: 1px;">ABOUT MANUHUB</h3>
                </div>
                
                <div class="card-body p-5" style="background-color: #ffffff;">
                    <div class="row">
                        <div class="col-md-6 border-end">
                            <h5 class="fw-bold mb-3" style="color: #6d0828;">Project Initiation</h5>
                            <p class="text-muted" style="text-align: justify; font-size: 0.95rem;">
                                This project was initiated by the <strong>Department of Library and Information Science, IIUM</strong> in early 2014 as part of student activities for the subject LISC 6191: Management of Islamic Manuscript Collections. Later in early 2016, this project was supported by a research grant from IIUM. The titles listed here were mostly taken from published catalogues and official list of manuscripts from the institutions concerned.
                            </p>
                        </div>
                        
                        <div class="col-md-6 ps-md-4">
                            <h5 class="fw-bold mb-3" style="color: #6d0828;">Our Objectives</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #b58428;"></i>To list all Malay manuscripts titles in Malaysia according to their format.</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #b58428;"></i>To help researchers identify title, location and subject.</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #b58428;"></i>To promote Malay manuscripts in the country.</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #b58428;"></i>To provide a domain for research for students.</li>
                            </ul>
                        </div>
                    </div>

                    <hr class="my-4" style="border-top: 2px solid #eee;">

                    <div class="text-center">
                        <p class="fst-italic text-secondary small">
                            This is an ongoing project that will continue for some years in order to improve the quality of the Union Catalogue. We plan to add new columns in the future especially books, thesis and articles relating to the particular manuscript titles. Comments and additional information are welcome.
                        </p>
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
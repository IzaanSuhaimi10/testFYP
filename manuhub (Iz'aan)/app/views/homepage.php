<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ManuHub</title>
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>
    <?php include('header.php'); ?>

    <!-- New Title and Description Section -->
    <div class="hero-section">
        <h1>ManuHub</h1>
        <p>A collaborative Union Catalogue System for Malay Manuscripts</p>
    </div>

    <div class="search-bar">
        <form action="/manuhub/public/index.php?action=search" method="POST">
            <input type="text" name="search" placeholder="Search Your Manuscript" required>
            <button type="submit">Search</button>
        </form>
    </div>

   <h2>Collection</h2>
    <div class="manuscript-collection">
        <?php foreach ($data['manuscripts'] as $manuscript): ?>
            <a href="index.php?action=metadata&id=<?php echo $manuscript['id']; ?>" style="text-decoration: none; color: inherit;">
                
                <div class="manuscript-item">
                    <img src="assets/images/<?php echo $manuscript['image_url']; ?>" alt="Manuscript Cover">
                    <h3><?php echo $manuscript['title']; ?></h3>
                    <p><?php echo $manuscript['author']; ?></p>
                    <p><?php echo $manuscript['year']; ?></p>
                </div>

            </a>
            <?php endforeach; ?>
    </div>

    <!-- See More Button -->
    <div class="see-more-container">
        <a href="/manuhub/public/index.php?action=manuscript_list&page=1">
            <button class="see-more-btn">See More</button>
        </a>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>

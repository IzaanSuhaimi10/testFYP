<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ManuHub - All Manuscripts</title>
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>
    <?php include('header.php'); ?>

    <h2>All Manuscripts</h2>
    
    <!-- Apply a unique class for manuscript list page -->
    <div class="manuscript-list-collection">
        <?php foreach ($data['manuscripts'] as $manuscript): ?>
            <a href="index.php?action=metadata&id=<?php echo $manuscript['id']; ?>" style="text-decoration: none; color: inherit;">
                
                <div class="manuscript-item">
                    <!-- <img src="../assets/images/<?php echo $manuscript['image_url']; ?>" alt="Manuscript Cover"> -->
                    <h3><?php echo $manuscript['Title']; ?></h3>
                    <p><?php echo $manuscript['Subject']; ?></p>
                    <!-- <p><?php echo $manuscript['year']; ?></p> -->
                </div>

            </a>
            <?php endforeach; ?>
    </div>

    <!-- Pagination Section -->
    <div class="pagination">
        <!-- Previous Page Button -->
        <?php if ($data['current_page'] > 1): ?>
            <a href="/manuhub/public/index.php?action=manuscript_list&page=<?php echo $data['current_page'] - 1; ?>">
                <button class="pagination-btn">Previous Page</button>
            </a>
        <?php endif; ?>

        <!-- Display Page Numbers -->
        <?php for ($i = 1; $i <= $data['total_pages']; $i++): ?>
            <a href="/manuhub/public/index.php?action=manuscript_list&page=<?php echo $i; ?>">
                <button class="pagination-btn <?php echo $i == $data['current_page'] ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </button>
            </a>
        <?php endfor; ?>

        <!-- Next Page Button -->
        <?php if ($data['current_page'] < $data['total_pages']): ?>
            <a href="/manuhub/public/index.php?action=manuscript_list&page=<?php echo $data['current_page'] + 1; ?>">
                <button class="pagination-btn">Next Page</button>
            </a>
        <?php endif; ?>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>
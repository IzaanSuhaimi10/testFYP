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

    <!-- Search Bar (optional, in case the user wants to change the search term) -->
    <div class="search-bar">
        <form action="/manuhub/public/index.php" method="GET">
            
            <input type="hidden" name="action" value="manuscript_list">
            <input type="hidden" name="page" value="1">
            
            <input type="text" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" placeholder="Search Your Manuscript" required>
            <button type="submit">Search</button>
        </form>
    </div>

    <h2>Search Results</h2>

    <div class="manuscript-list-collection">
        <?php foreach ($data['manuscripts'] as $manuscript): ?>
            <a href="index.php?action=metadata&id=<?php echo $manuscript['id']; ?>" style="text-decoration: none; color: inherit;">
                <div class="manuscript-item">
                    <h3><?php echo $manuscript['Title']; ?></h3>
                    <p><?php echo $manuscript['Subject']; ?></p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Pagination Section -->
    <div class="pagination">
        
        <?php 
        // Helper variables
        $searchParams = isset($data['search']) ? '&search=' . urlencode($data['search']) : '';
        $baseLink = 'index.php?action=manuscript_list' . $searchParams . '&page=';
        ?>

        <?php if ($data['current_page'] > 1): ?>
            <a href="<?php echo $baseLink . ($data['current_page'] - 1); ?>" class="pagination-btn">Previous</a>
        <?php endif; ?>

        <?php 
            $max_pages_to_show = 5;
            $start_page = max(1, $data['current_page'] - floor($max_pages_to_show / 2));
            $end_page = min($data['total_pages'], $data['current_page'] + floor($max_pages_to_show / 2));

            // Adjust window if we are at the edges
            if ($end_page - $start_page + 1 < $max_pages_to_show) {
                if ($start_page == 1) {
                    $end_page = min($data['total_pages'], $start_page + $max_pages_to_show - 1);
                } else {
                    $start_page = max(1, $end_page - $max_pages_to_show + 1);
                }
            }
        ?>

        <?php if ($start_page > 1): ?>
            <a href="<?php echo $baseLink . '1'; ?>" class="pagination-btn">1</a>
            <?php if ($start_page > 2): ?>
                <span class="pagination-ellipsis">...</span>
            <?php endif; ?>
        <?php endif; ?>

        <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
            <?php $activeClass = ($i == $data['current_page']) ? 'active' : ''; ?>
            <a href="<?php echo $baseLink . $i; ?>" class="pagination-btn <?php echo $activeClass; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($end_page < $data['total_pages']): ?>
            <?php if ($end_page < $data['total_pages'] - 1): ?>
                <span class="pagination-ellipsis">...</span>
            <?php endif; ?>
            <a href="<?php echo $baseLink . $data['total_pages']; ?>" class="pagination-btn"><?php echo $data['total_pages']; ?></a>
        <?php endif; ?>

        <?php if ($data['current_page'] < $data['total_pages']): ?>
            <a href="<?php echo $baseLink . ($data['current_page'] + 1); ?>" class="pagination-btn">Next</a>
        <?php endif; ?>

    </div>



    <?php include('footer.php'); ?>
</body>
</html>

<style>

    body {
    background-color: #fff8e1; /* Very light, professional gray background */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333;
    line-height: 1.6;
    margin: 0;
    padding: 0;

    }

    /* Center content and add padding to non-full-width elements (like h2, search, etc.) */
    h2 {
        text-align: center;
        margin-top: 40px;
        margin-bottom: 20px;
        font-size: 28px;
        /* Kept the original green-ish color for now, but will be overwritten by .center-item h2 */
        color: #6d0828ff; 
    }

    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 30px;
        margin-bottom: 30px;
    }

    .pagination-btn {
        padding: 10px 20px;
        margin: 0 8px;
        background-color: #c1a97aff;  /* Elegant muted teal for all page buttons */
        border: none;
        border-radius: 12px;  /* Soft rounded corners for a more polished feel */
        color: #fff;  /* White text for a clean look */
        font-size: 16px; /*Size of page number*/
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: normal;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);  /* Subtle shadow for a refined effect */
    }

    .pagination-btn:hover {
        background-color: #a06e1c;  /* Slightly darker, refined teal on hover */
        transform: translateY(-5px);  /* Subtle upward effect when hovered */
    }

    .pagination-btn.active {
        background-color: #b58428; /* Rich, contrasting navy blue for active page */
        font-weight: bold;
        padding: 12px 24px; /* Slightly larger for the active page */
        color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);  /* More prominent shadow for active button */
    }

    .pagination-ellipsis {
        padding: 12px 24px;
        color: #c1a97aff;  /* Match the elegant muted teal for ellipsis */
        font-weight: bold;
    }

    .pagination-btn:disabled {
        background-color: #D1E6E5; /* Soft muted grayish-teal for disabled buttons */
        cursor: not-allowed;
        color: #A2B1B1;  /* Lighter text color for disabled buttons */
    }

</style>
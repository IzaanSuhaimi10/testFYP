<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ManuHub</title>
    <link rel="stylesheet" href="../public/styles.css">
    <style>
        .manuscript-collection {
            display: flex;
            position: relative;
            overflow: hidden;
            width: 100%;
            /* Padding on the sides so the arrows don't cover the first/last box */
            padding: 0 50px; 
            box-sizing: border-box;
        }

        .manuscript-items-wrapper {
            display: flex;
            transition: transform 0.5s ease;
            /* THIS IS THE GAP BETWEEN BOXES */
            gap: 30px; 
            
            justify-content: center; 
            width: 100%;
            padding: 20px 0; /* Add top/bottom space so shadows don't get cut off */
        }

        /* The Link acts as the container */
        .manuscript-items-wrapper > a {
            display: block; /* Ensures the link respects the width/height */
            width: 18%; 
            text-decoration: none;
            color: inherit;
        }

        /* The Box itself */
        .manuscript-item {
            width: 100%; 
            height: 140px; /* Fixed height for uniformity */
            
            /* THIS IS THE GAP INSIDE THE BOX (Reduced as requested) */
            padding: 10px; 
            
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
            
            /* Adding a clean shadow to make the boxes pop out */
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }

        .manuscript-item:hover {
            transform: translateY(-5px); /* Moves up slightly when hovered */
            border-color: #333;
        }

        .manuscript-item h3 {
            font-size: 20px;
            margin: 0 0 5px 0;
            color: #000;
            font-weight: bold;
            /* Limit title to 2 lines */
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .manuscript-item p {
            font-size: 18px;
            color: #666;
            margin: 0;
        }

        /* Arrow Buttons */
        .arrow-btn {
            font-size: 30px;
            cursor: pointer;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            width: 40px;
            height: 40px;
            background: white;
            border: 1px solid #ccc;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        #left-arrow { left: 10px; }
        #right-arrow { right: 10px; }

        .arrow-btn:hover {
            background-color: #333;
            color: white;
        }
    </style>
</head>
<body>
    <?php include('header.php'); ?>

    <div class="hero-section">
        <h1>ManuHub</h1>
        <p>A collaborative Union Catalogue System for Malay Manuscripts</p>
    </div>

    <div class="search-bar">
        <form action="index.php" method="GET">
            
            <input type="hidden" name="action" value="manuscript_list">
            
            <input type="hidden" name="page" value="1">

            <input type="text" name="search" placeholder="Search Your Manuscript" required>
            <button type="submit">Search</button>
        </form>
    </div>

    <h2>Collection</h2>

    <div class="manuscript-collection">
        <span class="arrow-btn" id="left-arrow">&#8592;</span>

        <div class="manuscript-items-wrapper" id="manuscript-items">
            <?php 
            // 1. PHP DATA PREPARATION
            $filtered_manuscripts = array_filter($data['manuscripts'], function($manuscript) {
                // Handle different capitalization (Subject vs subject)
                $subj = $manuscript['Subject'] ?? $manuscript['subject'] ?? $manuscript['field'] ?? '-';
                return !empty($subj) && $subj != '-';
            });
            $filtered_manuscripts = array_slice($filtered_manuscripts, 0, 100);
            shuffle($filtered_manuscripts);
            $random_manuscripts = array_slice($filtered_manuscripts, 0, 30);
            
            // Note: The loop below is just a fallback. The JS will overwrite this almost instantly.
            foreach ($random_manuscripts as $manuscript): 
                $title = $manuscript['Title'] ?? $manuscript['title'] ?? 'Untitled';
                $subject = $manuscript['Subject'] ?? $manuscript['subject'] ?? $manuscript['field'] ?? '-';
            ?>
                <a href="index.php?action=metadata&id=<?php echo $manuscript['id']; ?>">
                    <div class="manuscript-item">
                        <h3><?php echo $title; ?></h3>
                        <p><?php echo $subject; ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <span class="arrow-btn" id="right-arrow">&#8594;</span>
    </div>

    <div class="see-more-container">
        <a href="/manuhub/public/index.php?action=manuscript_list&page=1">
            <button class="see-more-btn">See More</button>
        </a>
    </div>

    <?php include('footer.php'); ?>

    <script>
        // 2. JAVASCRIPT LOGIC
        let currentSlide = 0;
        // Pass the PHP array to JS safely
        const manuscripts = <?php echo json_encode($random_manuscripts); ?>;
        const itemsWrapper = document.getElementById('manuscript-items');
        const totalManuscripts = manuscripts.length;

        const displayManuscripts = () => {
            const start = currentSlide * 5;
            const end = start + 5;
            const manuscriptsToShow = manuscripts.slice(start, end);
            
            // Clear the PHP generated list so we can rebuild it with JS
            itemsWrapper.innerHTML = ''; 

            manuscriptsToShow.forEach(manuscript => {
                // A. Create the Link (Anchor)
                const anchor = document.createElement('a');
                
                // --- CRITICAL FIX: The correct URL structure ---
                // Using 'index.php?action=metadata&id=...' so the controller catches it
                anchor.href = `index.php?action=metadata&id=${manuscript['id']}`; 
                
                // Styling to match your layout
                anchor.style.textDecoration = 'none';
                anchor.style.color = 'inherit';
                
                // Add class for CSS targeting if needed
                anchor.className = 'generated-link';

                // B. Create the Item Div
                const manuscriptDiv = document.createElement('div');
                manuscriptDiv.className = 'manuscript-item';
                
                // Handle messy capitalization from DB
                const title = manuscript['Title'] || manuscript['title'] || 'Untitled';
                const subject = manuscript['Subject'] || manuscript['subject'] || manuscript['field'] || '-';

                manuscriptDiv.innerHTML = `<h3>${title}</h3><p>${subject}</p>`;
                
                // C. Assemble: Div goes inside Link, Link goes inside Wrapper
                anchor.appendChild(manuscriptDiv);
                itemsWrapper.appendChild(anchor);
            });
        };

        // Initialize
        displayManuscripts();

        // Slide Effect Logic
        const slideEffect = (direction) => {
            const slideDistance = direction === 'right' ? '-100%' : '100%';
            itemsWrapper.style.transition = 'transform 0.5s ease';
            itemsWrapper.style.transform = `translateX(${slideDistance})`;
            setTimeout(() => {
                itemsWrapper.style.transition = 'none';
                itemsWrapper.style.transform = 'translateX(0)';
            }, 500);
        };

        // Arrow Visibility Logic
        function toggleArrows() {
            const leftArrow = document.getElementById('left-arrow');
            const rightArrow = document.getElementById('right-arrow');

            if (currentSlide > 0) {
                leftArrow.style.display = 'block';
            } else {
                leftArrow.style.display = 'none';
            }

            if (currentSlide < Math.ceil(totalManuscripts / 5) - 1) {
                rightArrow.style.display = 'block';
            } else {
                rightArrow.style.display = 'none';
            }
        }

        // Arrow Click Events
        const rightBtn = document.getElementById('right-arrow');
        if(rightBtn) {
            rightBtn.addEventListener('click', () => {
                if (currentSlide < Math.ceil(totalManuscripts / 5) - 1) {
                    currentSlide++;
                    slideEffect('right');
                    displayManuscripts();
                    toggleArrows();
                }
            });
        }

        const leftBtn = document.getElementById('left-arrow');
        if(leftBtn) {
            leftBtn.addEventListener('click', () => {
                if (currentSlide > 0) {
                    currentSlide--;
                    slideEffect('left');
                    displayManuscripts();
                    toggleArrows();
                }
            });
        }
        
        // Run once on load to set initial arrow state
        toggleArrows();
    </script>
</body>
</html>
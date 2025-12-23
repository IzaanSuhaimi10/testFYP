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
            justify-content: center; /* Center the items */
        }
        .manuscript-items-wrapper {
            display: flex;
            transition: transform 0.5s ease;
            justify-content: center; /* Center the items */
            width: 100%;
        }

        /* Making the item clickable and centered */
        .manuscript-item {
            margin-right: 15px;
            width: 18%; /* Adjusted width to show 5 items */
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center; /* Center content vertically */
            align-items: center; /* Center content horizontally */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9; /* Add background color for better visibility */
            cursor: pointer; /* Make it look like a clickable link */
        }

        .manuscript-items-wrapper > div {
            display: flex;
            justify-content: center; /* Ensure the items are centered within each chunk */
        }

        /* Arrow Button Styling */
        .arrow-btn {
            font-size: 50px; /* Larger size for better visibility */
            cursor: pointer;
            position: fixed; /* Make the arrows stay fixed on the screen */
            top: 50%; /* Center vertically */
            z-index: 10;
            color: #333; /* Set default arrow color */
            background-color: rgba(255, 255, 255, 0.7); /* Slightly transparent background */
            border-radius: 50%; /* Make it round */
            padding: 15px; /* Increase the size of the button */
            transition: all 0.3s ease; /* Smooth transition for hover effect */
        }

        /* Left Arrow */
        #left-arrow {
            left: 20px; /* Position to the left */
        }

        /* Right Arrow */
        #right-arrow {
            right: 20px; /* Position to the right */
        }

        /* Hover effect for arrows */
        .arrow-btn:hover {
            background-color: rgba(0, 0, 0, 0.3); /* Darken background when hovered */
            color: #fff; /* Change arrow color to white on hover */
            transform: translateY(-50%) scale(1.2); /* Slight scale-up effect */
        }

    </style>
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
        <!-- Left Arrow (Initially hidden) -->
        <span class="arrow-btn" id="left-arrow">&#8592;</span>

        <div class="manuscript-items-wrapper" id="manuscript-items">
            <?php 
            // Filter manuscripts where 'Subject' is not null or '-'
            $filtered_manuscripts = array_filter($data['manuscripts'], function($manuscript) {
                return !empty($manuscript['Subject']) && $manuscript['Subject'] != '-';
            });

            // Slice the filtered array to get only the first 100 manuscripts
            $filtered_manuscripts = array_slice($filtered_manuscripts, 0, 100);

            // Shuffle the filtered manuscripts to pick 30 random ones
            shuffle($filtered_manuscripts);

            // Slice the shuffled array to get only the first 30 manuscripts
            $random_manuscripts = array_slice($filtered_manuscripts, 0, 30);

            // Loop through and display each manuscript with clickable link
            foreach ($random_manuscripts as $manuscript): ?>
                <a href="metadata.php?id=<?php echo $manuscript['id']; ?>" style="text-decoration: none; color: inherit;">
                    <div class="manuscript-item">
                        <h3><?php echo $manuscript['Title']; ?></h3>
                        <p><?php echo $manuscript['Subject']; ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Right Arrow -->
        <span class="arrow-btn" id="right-arrow">&#8594;</span>
    </div>

    <!-- See More Button (Fixed) -->
    <div class="see-more-container">
        <a href="/manuhub/public/index.php?action=manuscript_list&page=1">
            <button class="see-more-btn">See More</button>
        </a>
    </div>

    <?php include('footer.php'); ?>

    <script>
        let currentSlide = 0;
        const manuscripts = <?php echo json_encode($random_manuscripts); ?>;
        const itemsWrapper = document.getElementById('manuscript-items');
        const totalManuscripts = manuscripts.length;

        const displayManuscripts = () => {
            const start = currentSlide * 5;
            const end = start + 5;
            const manuscriptsToShow = manuscripts.slice(start, end);
            itemsWrapper.innerHTML = ''; // Clear current content

            manuscriptsToShow.forEach(manuscript => {
                const manuscriptDiv = document.createElement('div');
                manuscriptDiv.className = 'manuscript-item';
                manuscriptDiv.innerHTML = `<h3>${manuscript['Title']}</h3><p>${manuscript['Subject']}</p>`;
                itemsWrapper.appendChild(manuscriptDiv);
            });
        };

        // Initially display the first set of 5 manuscripts
        displayManuscripts();

        // Function to handle the slide effect (corrected direction)
        const slideEffect = (direction) => {
            const slideDistance = direction === 'right' ? '-100%' : '100%'; // Reverse slide direction
            itemsWrapper.style.transition = 'transform 0.5s ease'; // Transition for smooth slide
            itemsWrapper.style.transform = `translateX(${slideDistance})`; // Slide content
            setTimeout(() => {
                itemsWrapper.style.transition = 'none'; // Remove transition after the slide is complete
                itemsWrapper.style.transform = 'translateX(0)'; // Reset to original position
            }, 500);
        };

        // Show the right arrow if there are more manuscripts to show
        if (currentSlide < Math.ceil(totalManuscripts / 5) - 1) {
            document.getElementById('right-arrow').style.display = 'block';
        }

        // Handle right arrow click
        document.getElementById('right-arrow').addEventListener('click', () => {
            if (currentSlide < Math.ceil(totalManuscripts / 5) - 1) {
                currentSlide++;
                slideEffect('right'); // Right arrow moves left
                displayManuscripts();
                toggleArrows();
            }
        });

        // Handle left arrow click
        document.getElementById('left-arrow').addEventListener('click', () => {
            if (currentSlide > 0) {
                currentSlide--;
                slideEffect('left'); // Left arrow moves right
                displayManuscripts();
                toggleArrows();
            }
        });

        // Toggle visibility of arrows based on current slide
        function toggleArrows() {
            if (currentSlide > 0) {
                document.getElementById('left-arrow').style.display = 'block'; // Show left arrow
            } else {
                document.getElementById('left-arrow').style.display = 'none'; // Hide left arrow
            }

            if (currentSlide < Math.ceil(totalManuscripts / 5) - 1) {
                document.getElementById('right-arrow').style.display = 'block'; // Show right arrow
            } else {
                document.getElementById('right-arrow').style.display = 'none'; // Hide right arrow
            }
        }
    </script>
</body>
</html>

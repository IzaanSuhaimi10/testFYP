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

    <div class="hero-section">
        <h1><strong>ManuHub</strong></h1>
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

    
    <div class="center-item">
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
    </div>
    
    <div class="project-info-section">
        <p>
            This project was initiated by the Department of Library and Information Science, IIUM in early 2014 as part of student activities for the subject LISC 6191: Management of Islamic Manuscript Collections. Later in early 2016 this project was supported by a research grant from IIUM. The titles listed here were mostly taken from published catalogues and official list of manuscripts from the institutions concerned.
        </p>

        <h2>The Objectives of ManuHub</h2>
        <ul>
            <li>To list all Malay manuscripts titles in Malaysia according to their format,</li>
            <li>To help researchers to identify Malay manuscript’s title, location and subject,</li>
            <li>To promote Malay manuscripts in the country, and,</li>
            <li>To provide a domain for research on Malay manuscripts for students.</li>
        </ul>
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

<style>
    /* --- 1. GLOBAL ENHANCEMENTS --- */
    body {
        background-color: #f8f9fa; /* Very light, professional gray background */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
        line-height: 1.6;
        margin: 0;
        padding: 0;
        background-image: url('../assets/images/background_profile.jpg');
    }

    /* Center content and add padding to non-full-width elements (like h2, search, etc.) */
    h2 {
        text-align: center;
        margin-top: 40px;
        margin-bottom: 20px;
        font-size: 28px;
        /* Kept the original green-ish color for now, but will be overwritten by .center-item h2 */
        color: #0f7758ff; 
    }

    /* --- 2. HERO SECTION ENHANCEMENT --- */
    .hero-section {
        background-color: #6d0828b7; /* Deep primary color (Maroon) */
        color: #f0f0f0; /* Slightly brighter white for better contrast and readability */
        padding: 40px 20px; /* Slightly reduced padding to keep it tight */
        text-align: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Stronger shadow for depth */
        margin-top: 0px;
    }

    .hero-section h1 {
        font-size: 3.5em;
        margin: 0 0 10px 0;
        font-weight: 600; /* Bolder for impact */
        letter-spacing: 1px; /* Subtle spacing for title */
    }

    .hero-section p {
        font-size: 1.2em;
        opacity: 0.9;
        color: #f0f0f0;
    }

    /* --- NEW STYLES FOR CENTER ITEM WRAPPER (Now Soft Opaque White) --- */
    .center-item {
        max-width: 1400px; 
        width: 95%;
        margin: 0 auto;
        padding: 10px 20px 30px 10px; 
        
        /* IMPROVED OPAQUE BACKGROUND for better text contrast over background image */
        background-color: rgba(255, 255, 255, 0.9); /* Near-white, slightly opaque */
        color: #333; /* Reset text color for white background */
        
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15); /* Slightly lifted appearance */
        margin-bottom: 40px; 
    }

    /* Collection heading color adjustment for better visibility on opaque white */
    .center-item h2 {
        margin-top: 0; 
        padding-top: 20px;
        color: #6d0828ff; /* Use Primary Maroon for headings inside the white box */
        text-align: left; /* Aligned left for a modern feel */
        padding-left: 10px;
        border-bottom: 2px solid #b58428; /* Gold underline */
        width: fit-content;
        margin-left: auto;
        margin-right: auto;
    }
    
    /* --- 4. MANUSCRIPT CAROUSEL STYLES (Existing with minor refinement) --- */
    .manuscript-collection {
        display: flex;
        position: relative;
        overflow: hidden;
        width: 90%; 
        max-width: 1200px; 
        margin: 0 auto; 
        padding: 0 50px; 
        box-sizing: border-box;
    }

    .manuscript-items-wrapper {
        display: flex;
        transition: transform 0.5s ease;
        gap: 30px; 
        justify-content: center; 
        width: 100%;
        padding: 20px 0; 
    }

    /* The Link acts as the container */
    .manuscript-items-wrapper > a {
        display: block; 
        width: 18%; 
        text-decoration: none;
        color: inherit;
    }

    /* The Box itself */
    .manuscript-item {
        width: 100%; 
        height: 140px; 
        padding: 10px; 
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        
        border: 1px solid #e0e0e0; 
        border-radius: 10px;
        background-color: #fff;
        
        box-shadow: 0 6px 12px rgba(0,0,0,0.08); 
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .manuscript-item:hover {
        transform: translateY(-5px); 
        border-color: #b58428; /* Gold Accent on hover */
        box-shadow: 0 8px 16px rgba(181, 132, 40, 0.4); /* Subtle Gold hover glow */
    }

    .manuscript-item h3 {
        font-size: 20px;
        margin: 0 0 5px 0;
        color: #2c3e50; 
        font-weight: 600; 
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .manuscript-item p {
        font-size: 16px; 
        color: #7f8c8d; 
        margin: 0;
    }

    /* Arrow Buttons (Updated for better visibility on white/opaque background) */
    .arrow-btn {
        font-size: 30px;
        cursor: pointer;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        width: 45px; 
        height: 45px;
        background: rgba(109, 8, 40, 0.7); /* Use Maroon accent for arrows */
        color: white; /* White arrow symbol */
        border: 1px solid rgba(255, 255, 255, 0.8);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        transition: all 0.2s;
    }

    #left-arrow { left: 10px; }
    #right-arrow { right: 10px; }

    .arrow-btn:hover {
        background-color: #6d0828ff; /* Solid Maroon on hover */
        color: white;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }

    .see-more-container {
        text-align: center;
        margin: 30px 0 60px 0;
    }

    .see-more-btn {
        padding: 12px 30px;
        background-color: #b58428; /* Gold Accent for the main button */
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 18px;
        font-weight: bold;
        transition: background-color 0.3s;
    }

    .see-more-btn:hover {
        background-color: #a06e1c; /* Darker Gold on hover */
    }

    /* --- PROJECT INFO SECTION (Using the same Gold/Maroon theme) --- */
    .project-info-section {
        max-width: 1000px;
        margin: 40px auto 60px auto; 
        padding: 30px;
        background-color: #fff8e1; /* Very light cream/yellow for soft contrast */
        border-radius: 10px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1); 
        line-height: 1.7; 
        border: 1px solid #f0e6c4; /* Subtle matching border */
    }

    .project-info-section h2 {
        color: #6d0828ff; /* Maroon heading */
        border-bottom: 3px solid #b58428; /* Gold underline */
        padding-bottom: 12px;
        margin-bottom: 25px;
        font-size: 24px;
        text-align: left; 
    }

    .project-info-section p {
        font-size: 17px; 
        color: #34495e; 
        margin-bottom: 15px;
    }

    .project-info-section ul {
        list-style-type: none; 
        padding-left: 0;
    }

    .project-info-section ul li {
        font-size: 17px;
        color: #34495e;
        margin-bottom: 10px;
        padding-left: 30px; 
        position: relative;
        font-style: italic;
    }

    .project-info-section ul li::before {
        content: '✓'; 
        color: #b58428; /* NEW: Gold Checkmark to match accent */
        font-weight: bold;
        font-size: 1.2em;
        position: absolute;
        left: 0;
        top: 0; 
    }
</style>
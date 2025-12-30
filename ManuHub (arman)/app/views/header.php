<?php session_start(); ?> <!-- Start session to access session variables -->

<header>
    <div class="logo">
        <a href="/manuhub/public">ManuHub</a>
    </div>
    
    <div class="nav-links">
        <!-- Check if the user is logged in -->
        <?php if (isset($_SESSION['username'])): ?>

            <!-- If logged in, display username and menu -->
            <div class="user-info">
                <!-- Display username without clickability -->
                <span id="username" class="username"><?php echo 'Hello, ' . $_SESSION['username']; ?></span>

                <!-- Three horizontal lines (hamburger menu) -->
                <div class="menu-icon" id="menu-icon">&#9776;</div>
            </div>

            <!-- Dropdown menu -->
            <div class="dropdown-menu" id="dropdown-menu">
                <!-- Profile option for all users -->
                <!-- <a href="/manuhub/public/index.php?action=profile">Profile</a> -->
                 <!-- Conditionally show Manuscript Submission based on role -->
                <?php if ($_SESSION['role'] === 'normal_user'): ?>
                    <a href="/manuhub/public/index.php?action=profile">Profile</a>
                    <a href="/manuhub/public/index.php?action=manuscript_submission">Manuscript Submission</a>
                <?php endif; ?>

                <!-- Additional options for admin and expert roles -->
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="/manuhub/public/index.php?action=admin_dashboard">Admin Dashboard</a>
                <?php elseif ($_SESSION['role'] === 'expert'): ?>
                    <a href="/manuhub/public/index.php?action=profile">Profile</a>
                    <a href="/manuhub/public/index.php?action=manuscript_verification">Manuscript Verification</a>
                    <a href="/manuhub/public/index.php?action=msources_submission">Sources Submission</a>
                <?php endif; ?>

                <!-- Logout option -->
                <a href="/manuhub/public/index.php?action=logout" class="logout-btn">Logout</a>
            </div>

        <?php else: ?>
            <!-- If not logged in, show login and register links -->
            <a href="/manuhub/public/index.php?action=login">Login</a>
            <a href="/manuhub/public/index.php?action=register">Register</a>
        <?php endif; ?>
    </div>
</header>

<style>
    header {
        background-color: #6d0828ff;
        color: white;
        padding: 10px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    header .logo a {
        text-decoration: none;
        color: white;
        font-size: 24px;
        font-weight: bold;
    }

    header .nav-links a {
        color: white;
        margin-left: 10px;
        text-decoration: none;
        font-size: 16px;
    }

    /* Logout button */
    .logout-btn {
        background-color: #e74c3c;
        padding: 10px 15px;
        color: white;
        border-radius: 8px;  /* Round the corners for a cleaner look */
        text-decoration: none;
        display: block;
        margin-top: 10px; /* Add margin for a little space between items */
        font-weight: bold;
        text-align: center;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .logout-btn:hover {
        background-color: #c0392b;
        transform: scale(1.05); /* Subtle scale effect on hover */
    }

    /* Username */
    header .nav-links .user-info {
        display: flex;
        align-items: center;
        position: relative;
    }

    header .nav-links .username {
        font-size: 16px;
        margin-right: 20px; /* Added margin to create space between username and hamburger icon */
        pointer-events: none; /* Remove the clickability of the username */
    }

    /* Hamburger Menu (Three lines) */
    .menu-icon {
        font-size: 30px;
        cursor: pointer;
    }

    /* Dropdown Menu */
    .dropdown-menu {
        display: none;
        position: absolute;
        top: 30px;
        right: 0;
        background-color: #333;
        border-radius: 8px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        min-width: 200px;
        padding: 10px 0;
        z-index: 1;
    }

    .dropdown-menu a {
        color: white;
        padding: 10px 16px;
        text-decoration: none;
        display: block;
        transition: background-color 0.3s ease;
        border-radius: 8px;
        font-weight: normal;
    }

    .dropdown-menu a:hover {
        background-color: #575757;
    }

    /* Show menu icon and dropdown for logged in users */
    @media screen and (max-width: 768px) {
        .menu-icon {
            display: block;
        }

        .nav-links a {
            display: none; /* Hide default links on small screens */
        }
    }

    /* Add styles for when the dropdown is active */
    header .nav-links .dropdown-menu.active {
        display: block;
    }
</style>

<script>
    // JavaScript to handle dropdown visibility
    const menuIcon = document.getElementById('menu-icon');
    const dropdownMenu = document.getElementById('dropdown-menu');
    const username = document.getElementById('username');

    // Toggle dropdown menu on click of the hamburger menu
    menuIcon.addEventListener('click', () => {
        dropdownMenu.classList.toggle('active');  // Toggle visibility of the dropdown
    });

    // Prevent click on username
    username.addEventListener('click', (e) => {
        e.preventDefault();  // Disable clicking on the username
    });

    // Close the dropdown menu if clicked outside of it
    window.addEventListener('click', (event) => {
        if (!event.target.matches('#menu-icon') && !event.target.matches('#username') && !event.target.matches('.dropdown-menu') && !event.target.matches('.dropdown-menu a')) {
            dropdownMenu.classList.remove('active');  // Close the dropdown if clicked outside
        }
    });
</script>
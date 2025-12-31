<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?> 

<header>
    <div class="logo">
        <a href="index.php">ManuHub</a>
    </div>
    
    <div class="nav-links">
        <?php if (isset($_SESSION['username'])): ?>
            <div class="user-info">
                <span id="username" class="username">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></span>

                <div class="menu-icon" id="menu-icon">&#9776;</div>
            </div>

            <div class="dropdown-menu" id="dropdown-menu">
                
                <?php if ($_SESSION['role'] === 'normal_user'): ?>
                    <a href="index.php?action=user_dashboard">Profile</a>
                <?php endif; ?>

                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="index.php?action=admin_dashboard">Admin Dashboard</a>
                    <a href="index.php?action=admin_manuscripts">Manuscript Oversight</a>
                    <a href="index.php?action=admin_users">User Management</a>
                <?php endif; ?>

                <?php if ($_SESSION['role'] === 'expert'): ?>
                    <a href="index.php?action=expert_dashboard">Expert Dashboard</a>
                <?php endif; ?>

                <a href="index.php?action=logout" class="logout-btn">Logout</a>
            </div>

        <?php else: ?>
            <a href="index.php?action=login">Login</a>
            <a href="index.php?action=register">Register</a>
        <?php endif; ?>
    </div>
</header>

<style>
    /* ... (Keep your existing CSS exactly the same) ... */
    header { background-color: #6d0828ff; color: white; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
    header .logo a { text-decoration: none; color: white; font-size: 24px; font-weight: bold; }
    header .nav-links a { color: white; margin-left: 10px; text-decoration: none; font-size: 16px; }
    .logout-btn { background-color: #e74c3c; padding: 10px 15px; color: white; border-radius: 8px; text-decoration: none; display: block; margin-top: 10px; font-weight: bold; text-align: center; transition: background-color 0.3s ease, transform 0.3s ease; }
    .logout-btn:hover { background-color: #c0392b; transform: scale(1.05); }
    header .nav-links .user-info { display: flex; align-items: center; position: relative; }
    header .nav-links .username { font-size: 16px; margin-right: 20px; pointer-events: none; }
    .menu-icon { font-size: 30px; cursor: pointer; }
    .dropdown-menu { display: none; position: absolute; top: 30px; right: 0; background-color: #333; border-radius: 8px; box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); min-width: 200px; padding: 10px 0; z-index: 1000; }
    .dropdown-menu a { color: white; padding: 10px 16px; text-decoration: none; display: block; transition: background-color 0.3s ease; border-radius: 8px; font-weight: normal; }
    .dropdown-menu a:hover { background-color: #575757; }
    @media screen and (max-width: 768px) { .menu-icon { display: block; } .nav-links > a { display: none; } }
    header .nav-links .dropdown-menu.active { display: block; }
</style>

<script>
    const menuIcon = document.getElementById('menu-icon');
    const dropdownMenu = document.getElementById('dropdown-menu');
    if(menuIcon){
        menuIcon.addEventListener('click', () => {
            dropdownMenu.classList.toggle('active');
        });
    }
    window.addEventListener('click', (event) => {
        if (!event.target.matches('#menu-icon') && !event.target.matches('.dropdown-menu') && !event.target.matches('.dropdown-menu a')) {
            if(dropdownMenu && dropdownMenu.classList.contains('active')){
                dropdownMenu.classList.remove('active');
            }
        }
    });
</script>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Define a base path to avoid relative link issues
$basePath = "/manuhub/public/index.php";
?>
<nav class="navbar navbar-expand-lg sticky-top shadow-sm" style="background-color: #6d0828; border-bottom: 3px solid #b58428;">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo $basePath; ?>">
            <span class="fw-bold text-white fs-3" style="letter-spacing: 1px;">ManuHub</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="bi bi-list text-white fs-2"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link text-white px-3" href="<?php echo $basePath; ?>">
                        <i class="bi bi-house-door me-1"></i> Home
                    </a>
                </li>

                <?php if (isset($_SESSION['user_id']) && isset($_SESSION['role'])): ?>
                    <li class="nav-item dropdown ms-lg-3">
                        <a class="nav-link dropdown-toggle btn btn-outline-light px-4 rounded-pill text-white" 
                           href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 p-2">
                            <li>
                                <a class="dropdown-item rounded" href="<?php echo $basePath; ?>?action=<?php echo $_SESSION['role']; ?>_dashboard">
                                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item rounded text-danger" href="<?php echo $basePath; ?>?action=logout">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-warning px-4 rounded-pill fw-bold" 
                           style="background-color: #b58428; border: none; color: white;" 
                           href="<?php echo $basePath; ?>?action=login">
                            LOGIN
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php
require_once "../core/Controller.php";

class AdminController extends Controller {

    // --- 1. SECURITY CHECK ---
    private function checkAuth() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }

        // Ensure user is logged in AND is an admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }
    }

    // --- 2. DASHBOARD PAGE ---
    public function dashboard() {
        $this->checkAuth(); 

        $database = new Database();
        $db = $database->getConnection();
        
        // A. GET COUNTS
        // FIX: Updated table name to 'manuscripts_submission' (plural)
        $stmt = $db->query("SELECT COUNT(*) as total FROM manuscripts_submission WHERE status = 'pending'");
        $pendingCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        $stmt = $db->query("SELECT COUNT(*) as total FROM users");
        $userCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        // B. GET LATEST SUBMISSIONS
        // FIX: Updated table name to 'manuscripts_submission' (plural)
        // FIX: Used 'create_dat' as requested
        $querySub = "SELECT ms.*, u.username 
                     FROM manuscripts_submission ms 
                     LEFT JOIN users u ON ms.submitted_by = u.user_id 
                     ORDER BY ms.create_dat DESC LIMIT 4";
        $latestSubmissions = $db->query($querySub)->fetchAll(PDO::FETCH_ASSOC);

        // C. GET LATEST LOGS
        $queryLogs = "SELECT * FROM system_logs ORDER BY created_at DESC LIMIT 5";
        $latestLogs = $db->query($queryLogs)->fetchAll(PDO::FETCH_ASSOC);

        // D. GET LATEST USERS
        // This relies on the 'created_at' column you added to the users table
        $queryUsers = "SELECT * FROM users ORDER BY created_at DESC LIMIT 4";
        $latestUsers = $db->query($queryUsers)->fetchAll(PDO::FETCH_ASSOC);

        // E. LOAD THE VIEW
        $this->loadView('admin/dashboard', [
            'pending_count' => $pendingCount,
            'user_count' => $userCount,
            'latest_submissions' => $latestSubmissions,
            'latest_logs' => $latestLogs,
            'latest_users' => $latestUsers
        ]);
    }

    // --- 3. MANUSCRIPT OVERSIGHT PAGE ---
    public function manuscripts() {
        $this->checkAuth();
        // Placeholder for now
        echo "<h1>Manuscript Oversight Page - Coming Soon</h1>";
        // Later: $this->loadView('admin/manuscripts', $data);
    }

    // --- 4. USER MANAGEMENT PAGE ---
    public function users() {
        $this->checkAuth();
        // Placeholder for now
        echo "<h1>User Management Page - Coming Soon</h1>";
    }

    // --- 5. SYSTEM MANAGEMENT PAGE ---
    public function system() {
        $this->checkAuth();
        // Placeholder for now
        echo "<h1>System Management Page - Coming Soon</h1>";
    }
}
?>
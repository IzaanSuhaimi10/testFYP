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

    // --- 1. ADMIN DASHBOARD (Updated with Related Works Count) ---
  public function dashboard() {
        $this->checkAuth();
        $database = new Database();
        $db = $database->getConnection();

        // 1. COUNTS
        $userCount = $db->query("SELECT COUNT(*) as total FROM users")->fetch(PDO::FETCH_ASSOC)['total'];
        $pendingCount = $db->query("SELECT COUNT(*) as total FROM manuscripts_submission WHERE status = 'pending'")->fetch(PDO::FETCH_ASSOC)['total'];
        $pendingRwCount = $db->query("SELECT COUNT(*) as total FROM related_works_submission WHERE status = 'pending'")->fetch(PDO::FETCH_ASSOC)['total'];

        // 2. LATEST MANUSCRIPT SUBMISSIONS (Limit 5)
        $latestSub = $db->query("SELECT ms.*, u.username 
                                 FROM manuscripts_submission ms 
                                 LEFT JOIN users u ON ms.submitted_by = u.user_id 
                                 WHERE ms.status = 'pending' 
                                 ORDER BY ms.create_dat DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

        // 3. [NEW] LATEST RELATED WORKS (Limit 5)
        $latestRw = $db->query("SELECT rw.*, u.username 
                                FROM related_works_submission rw 
                                LEFT JOIN users u ON rw.submitted_by = u.user_id 
                                WHERE rw.status = 'pending' 
                                ORDER BY rw.created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

        // 4. LATEST SYSTEM LOGS (Limit 5)
        $latestLogs = $db->query("SELECT * FROM system_logs ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

        // 5. LATEST REGISTERED USERS (Limit 5)
        $latestUsers = $db->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

        // Load View
        $this->loadView('admin/dashboard', [
            'user_count' => $userCount,
            'pending_count' => $pendingCount,
            'pending_rw_count' => $pendingRwCount,
            'latest_submissions' => $latestSub,
            'latest_related_works' => $latestRw, // Passing new data
            'latest_logs' => $latestLogs,
            'latest_users' => $latestUsers
        ]);
    }

    // --- 3. MANUSCRIPT OVERSIGHT PAGE ---
   public function manuscripts() {
        $this->checkAuth();

        $database = new Database();
        $db = $database->getConnection();

        // 1. Fetch Manuscript Submissions
        $queryMS = "SELECT ms.*, u.username, u.email 
                  FROM manuscripts_submission ms 
                  LEFT JOIN users u ON ms.submitted_by = u.user_id 
                  ORDER BY ms.create_dat DESC";
        $submissions = $db->query($queryMS)->fetchAll(PDO::FETCH_ASSOC);

        // 2. Fetch Related Works Submissions
        $queryRW = "SELECT rw.*, u.username, m.Title as manuscript_title 
                    FROM related_works_submission rw
                    LEFT JOIN users u ON rw.submitted_by = u.user_id
                    LEFT JOIN manuscripts m ON rw.manuscript_id = m.id
                    ORDER BY rw.created_at DESC";
        $relatedWorks = $db->query($queryRW)->fetchAll(PDO::FETCH_ASSOC);

        $this->loadView('admin/manuscripts', [
            'submissions' => $submissions,
            'related_works' => $relatedWorks
        ]);
    }

    // --- 4. USER MANAGEMENT PAGE ---
    public function users() {
        $this->checkAuth();

        $database = new Database();
        $db = $database->getConnection();

        // Fetch ALL users, ordered by newest first
        $query = "SELECT * FROM users ORDER BY created_at DESC";
        $users = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $this->loadView('admin/users', [
            'users' => $users
        ]);
    }

    // --- DELETE USER ACTION ---
    public function deleteUser() {
        $this->checkAuth();

        $id = $_GET['id'] ?? null;

        // Prevent Admin from deleting themselves!
        if ($id == $_SESSION['user_id']) {
            echo "<script>alert('You cannot delete yourself!'); window.location.href='index.php?action=admin_users';</script>";
            exit();
        }

        if ($id) {
            $database = new Database();
            $db = $database->getConnection();

            // Delete the user
            $query = "DELETE FROM users WHERE user_id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                // Log the action
                require_once "../app/models/Logger.php";
                Logger::log("DELETE USER", "Admin deleted user ID #$id");

                header("Location: index.php?action=admin_users&msg=deleted");
            }
        }
    }

    // --- TOGGLE USER STATUS ---
public function toggleUserStatus() {
    $this->checkAuth();

    $id = $_GET['id'] ?? null;
    $currentStatus = $_GET['status'] ?? null;

    if ($id == $_SESSION['user_id']) {
        echo "<script>alert('You cannot suspend yourself!'); window.location.href='index.php?action=admin_users';</script>";
        exit();
    }

    if ($id && $currentStatus) {
        $database = new Database();
        $db = $database->getConnection();

        $newStatus = ($currentStatus === 'active') ? 'inactive' : 'active';

        $query = "UPDATE users SET status = :status WHERE user_id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':status', $newStatus);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            require_once "../app/models/Logger.php";
            $action = ($newStatus === 'active') ? "ACTIVATED" : "SUSPENDED";
            Logger::log("USER STATUS", "Admin $action user #$id");

            header("Location: index.php?action=admin_users&msg=status_updated");
            exit(); // Always exit after a header redirect
        }
    }
    
    // Fallback if something goes wrong
    header("Location: index.php?action=admin_users");
    exit();
}
    // --- 5. SYSTEM MANAGEMENT (LOGS) PAGE ---
    public function systemLogs() {
    $this->checkAuth();
    $database = new Database();
    $db = $database->getConnection();

    // IMPORTANT: Replace 'log_type' and 'activity' with your EXACT database column names
    $query = "SELECT created_at, action, description FROM system_logs ORDER BY created_at DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $this->loadView('admin/system', ['logs' => $logs]);
}
    // --- CLEAR LOGS (Maintenance) ---
    public function clearLogs() {
        $this->checkAuth();
        
        $database = new Database();
        $db = $database->getConnection();
        
        // Keep the last 10 logs for safety, delete the rest
        // (Or just TRUNCATE table if you want to wipe everything)
        $query = "DELETE FROM system_logs"; 
        $stmt = $db->prepare($query);
        
        if ($stmt->execute()) {
            require_once "../app/models/Logger.php";
            Logger::log("SYSTEM", "Admin cleared all system logs.");
            header("Location: index.php?action=admin_system&msg=logs_cleared");
        }
    }

    // --- DELETE SUBMISSION (Admin Override) ---
    public function deleteSubmission($id) {
    $this->checkAuth();
    $database = new Database();
    $db = $database->getConnection();

    try {
        $db->beginTransaction();

        // 1. Delete from the Admin/Expert Oversight table
        $query1 = "DELETE FROM manuscripts_submission WHERE id = :id";
        $stmt1 = $db->prepare($query1);
        $stmt1->execute([':id' => $id]);

        // 2. Delete from the Public Search table
        // IMPORTANT: Verify if your search table is named 'manuscripts' 
        // and if it uses 'id' or 'ms_id' as the column name.
        $query2 = "DELETE FROM manuscripts WHERE id = :id"; 
        $stmt2 = $db->prepare($query2);
        $stmt2->execute([':id' => $id]);

        $db->commit();

        // Redirect back to oversight
        header("Location: index.php?action=admin_manuscripts&status=deleted");
        exit();

    } catch (Exception $e) {
        $db->rollBack();
        die("Error deleting record: " . $e->getMessage());
    }
}
}
?>
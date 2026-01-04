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
    $db = (new Database())->getConnection();

    // Core Counts
    $userCount = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $msLiveCount = $db->query("SELECT COUNT(*) FROM manuscripts")->fetchColumn();
    
    // Pending Workload for Experts
    $pendingMs = $db->query("SELECT COUNT(*) FROM manuscripts_submission WHERE status = 'pending'")->fetchColumn();
    $pendingRw = $db->query("SELECT COUNT(*) FROM related_works_submission WHERE status = 'pending'")->fetchColumn();
    $pendingSug = $db->query("SELECT COUNT(*) FROM metadata_suggestions WHERE status = 'pending'")->fetchColumn();
    $pendingFlags = $db->query("SELECT COUNT(*) FROM content_flags WHERE status = 'pending'")->fetchColumn();

    // Activity Data
    $latestUsers = $db->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
    $latestLogs = $db->query("SELECT * FROM system_logs ORDER BY created_at DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);

    $this->loadView('admin/dashboard', [
        'user_count' => $userCount,
        'live_ms_count' => $msLiveCount,
        'pending_total' => ($pendingMs + $pendingRw + $pendingSug + $pendingFlags),
        'pending_ms' => $pendingMs,
        'pending_rw' => $pendingRw,
        'pending_sug' => $pendingSug,
        'pending_flags' => $pendingFlags,
        'latest_users' => $latestUsers,
        'latest_logs' => $latestLogs,
        'session_id' => session_id(), // For the profile banner
        'server_date' => date('d M Y') // For the profile banner
    ]);
}

    // --- 3. MANUSCRIPT OVERSIGHT PAGE ---
   public function manuscripts() {
    $this->checkAuth();
    $db = (new Database())->getConnection();

    // 1. Fetch Manuscript Submissions
    $submissions = $db->query("SELECT ms.*, u.username FROM manuscripts_submission ms 
                                LEFT JOIN users u ON ms.submitted_by = u.user_id 
                                ORDER BY ms.create_dat DESC")->fetchAll(PDO::FETCH_ASSOC);

    // 2. Fetch Related Works Submissions
    $relatedWorks = $db->query("SELECT rw.*, u.username, m.Title as manuscript_title 
                                FROM related_works_submission rw
                                LEFT JOIN users u ON rw.submitted_by = u.user_id
                                LEFT JOIN manuscripts m ON rw.manuscript_id = m.id
                                ORDER BY rw.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

    // 3. Fetch Metadata Suggestions (NEW)
    $suggestions = $db->query("SELECT s.*, u.username, m.Title as manuscript_title 
                               FROM metadata_suggestions s 
                               LEFT JOIN users u ON s.user_id = u.user_id 
                               LEFT JOIN manuscripts m ON s.manuscript_id = m.id
                               ORDER BY s.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

    // 4. Fetch Content Flags (NEW)
    $flags = $db->query("SELECT f.*, u.username, r.title as work_title 
                         FROM content_flags f 
                         LEFT JOIN users u ON f.user_id = u.user_id 
                         LEFT JOIN related_works r ON f.work_id = r.id
                         ORDER BY f.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

    $this->loadView('admin/manuscripts', [
        'submissions' => $submissions,
        'related_works' => $relatedWorks,
        'suggestions' => $suggestions,
        'flags' => $flags
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
    // --- AdminController.php ---

public function systemLogs() {
    $this->checkAuth();
    $db = (new Database())->getConnection();

    // --- FUNCTION 1: AUTO-CLEAR (OLDER THAN 3 MONTHS) ---
    // This runs silently every time the logs page is accessed
    $db->query("DELETE FROM system_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 3 MONTH)");

    // --- DATA FLOW CHECK ---
    // Ensure we are selecting 'action' and 'description' to match your view logic
    $query = "SELECT created_at, action, description FROM system_logs ORDER BY created_at DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $this->loadView('admin/system', ['logs' => $logs]);
}

// --- FUNCTION 2: MANUAL CLEAR LOGS ---
public function clearLogs() {
    $this->checkAuth();
    $db = (new Database())->getConnection();
    
    // Deletes all logs
    $query = "DELETE FROM system_logs";
    $stmt = $db->prepare($query);
    
    if ($stmt->execute()) {
        require_once "../app/models/Logger.php";
        // Log that the logs were cleared
        Logger::log("SYSTEM", "Admin manually cleared all system logs.");
        header("Location: index.php?action=admin_system&msg=logs_cleared");
        exit();
    }
}

    // --- DELETE SUBMISSION (Admin Override) ---
   public function deleteSubmission($id) {
    $this->checkAuth();
    $database = new Database();
    $db = $database->getConnection();

    try {
        // Start a transaction to ensure both deletes happen together
        $db->beginTransaction();

        // 1. Delete from the Admin/Expert Oversight table (Staging)
        $query1 = "DELETE FROM manuscripts_submission WHERE id = :id";
        $stmt1 = $db->prepare($query1);
        $stmt1->execute([':id' => $id]);

        // 2. Delete from the Public Search table (Live)
        // This ensures the item disappears from manuscript_list immediately
        $query2 = "DELETE FROM manuscripts WHERE id = :id"; 
        $stmt2 = $db->prepare($query2);
        $stmt2->execute([':id' => $id]);

        $db->commit();

        // Log the action for the system logs
        require_once "../app/models/Logger.php";
        Logger::log("DELETE", "Admin permanently removed manuscript submission and live record #$id");

        // Redirect back to oversight with success message
        header("Location: index.php?action=admin_manuscripts&status=deleted");
        exit();

    } catch (Exception $e) {
        // If anything fails, undo any partial deletions
        $db->rollBack();
        die("Error deleting record: " . $e->getMessage());
    }
}
}
?>
<?php
require_once "../core/Controller.php";

class ExpertController extends Controller {

    private function checkExpertAuth() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'expert') {
            header("Location: index.php?action=login");
            exit();
        }
    }

    // --- 1. DASHBOARD ---
    public function dashboard() {
        $this->checkExpertAuth();
        $database = new Database();
        $db = $database->getConnection();
        
        // 1. Fetch Pending Manuscripts
        $stmtMS = $db->query("SELECT ms.*, u.username FROM manuscripts_submission ms 
                              LEFT JOIN users u ON ms.submitted_by = u.user_id 
                              WHERE ms.status = 'pending' ORDER BY ms.create_dat DESC");
        
        // 2. Fetch Pending Related Works
        $stmtRW = $db->query("SELECT rw.*, u.username, m.Title as manuscript_title FROM related_works_submission rw 
                              LEFT JOIN users u ON rw.submitted_by = u.user_id 
                              LEFT JOIN manuscripts m ON rw.manuscript_id = m.id
                              WHERE rw.status = 'pending' ORDER BY rw.created_at DESC");

        // 3. Count Verified Items Separately (Not Pending)
        $countVerifiedMS = $db->query("SELECT COUNT(*) as total FROM manuscripts_submission WHERE status != 'pending'")->fetch(PDO::FETCH_ASSOC)['total'];
        $countVerifiedRW = $db->query("SELECT COUNT(*) as total FROM related_works_submission WHERE status != 'pending'")->fetch(PDO::FETCH_ASSOC)['total'];
        
        $totalVerified = $countVerifiedMS + $countVerifiedRW;

        $this->loadView('expert/dashboard', [
            'manuscripts' => $stmtMS->fetchAll(PDO::FETCH_ASSOC),
            'related_works' => $stmtRW->fetchAll(PDO::FETCH_ASSOC),
            'total_verified' => $totalVerified,
            'count_ms' => $countVerifiedMS,
            'count_rw' => $countVerifiedRW
        ]);
    }

    // --- 2. MANUSCRIPT VERIFICATION ---
    public function verifyManuscripts() {
        $this->checkExpertAuth();
        $database = new Database();
        $db = $database->getConnection();

        // HANDLE BATCH SAVE
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['status']) && is_array($_POST['status'])) {
                foreach ($_POST['status'] as $id => $newStatus) {
                    
                    // Update Status in Submission Table
                    $updateStmt = $db->prepare("UPDATE manuscripts_submission SET status = :status WHERE id = :id");
                    $updateStmt->execute([':status' => $newStatus, ':id' => $id]);

                    // If Approved, Copy to Live Table
                    if ($newStatus === 'approved') {
                        $this->approveManuscriptToLive($db, $id);
                    }
                }
            }
            header("Location: index.php?action=expert_verification_manuscripts&msg=saved");
            exit();
        }

        // SHOW PAGE
        $query = "SELECT ms.*, u.username FROM manuscripts_submission ms 
                  LEFT JOIN users u ON ms.submitted_by = u.user_id 
                  ORDER BY FIELD(ms.status, 'pending', 'approved', 'rejected'), ms.create_dat DESC";
        $list = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $this->loadView('expert/verification_manuscripts', ['list' => $list]);
    }

    // [CRITICAL UPDATE] Helper to move manuscript to live table
    private function approveManuscriptToLive($db, $submissionId) {
        $stmt = $db->prepare("SELECT * FROM manuscripts_submission WHERE id = :id");
        $stmt->execute([':id' => $submissionId]);
        $sub = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($sub) {
            // Check duplicates
            $check = $db->prepare("SELECT id FROM manuscripts WHERE Title = :t");
            $check->execute([':t' => $sub['Title']]);
            if($check->rowCount() > 0) return; 

            $sql = "INSERT INTO manuscripts (Title, Description, Location_of_Manuscript, Country, Subject, Call_Number, Author, Language, Genre, file_path, submitted_by, create_dat)
                    VALUES (:title, :desc, :loc, :country, :subject, :call, :author, :lang, :genre, :file, :uid, NOW())";
            
            $ins = $db->prepare($sql);
            
            // Execute with default values to prevent "Cannot be null" errors
            $ins->execute([
                ':title' => $sub['Title'], 
                ':desc' => $sub['Description'] ?? '', 
                ':loc' => $sub['Location_of_Manuscript'] ?? 'Not Specified',
                ':country' => $sub['Country'] ?? 'Malaysia', 
                ':subject' => $sub['Subject'] ?? 'General', 
                ':call' => $sub['Call_Number'] ?? '-',
                ':author' => $sub['Author'] ?? 'Unknown', 
                ':lang' => $sub['Language'] ?? 'Malay', 
                ':genre' => $sub['Genre'] ?? 'Manuscript',
                ':file' => $sub['file_path'] ?? null, 
                ':uid' => $sub['submitted_by']
            ]);
        }
    }

    // --- 3. RELATED WORKS VERIFICATION ---
    public function verifyRelatedWorks() {
        $this->checkExpertAuth();
        $database = new Database();
        $db = $database->getConnection();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['status']) && is_array($_POST['status'])) {
                foreach ($_POST['status'] as $id => $newStatus) {
                    $updateStmt = $db->prepare("UPDATE related_works_submission SET status = :status WHERE id = :id");
                    $updateStmt->execute([':status' => $newStatus, ':id' => $id]);

                    if ($newStatus === 'approved') {
                        $this->approveRelatedWorkToLive($db, $id);
                    }
                }
            }
            header("Location: index.php?action=expert_verification_related&msg=saved");
            exit();
        }

        $query = "SELECT rw.*, u.username, m.Title as manuscript_title 
                  FROM related_works_submission rw 
                  LEFT JOIN users u ON rw.submitted_by = u.user_id 
                  LEFT JOIN manuscripts m ON rw.manuscript_id = m.id
                  ORDER BY FIELD(rw.status, 'pending', 'approved', 'rejected'), rw.created_at DESC";
        $list = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $this->loadView('expert/verification_related_works', ['list' => $list]);
    }

    private function approveRelatedWorkToLive($db, $submissionId) {
        $stmt = $db->prepare("SELECT * FROM related_works_submission WHERE id = :id");
        $stmt->execute([':id' => $submissionId]);
        $sub = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($sub) {
            $sql = "INSERT INTO related_works (manuscript_id, title, url, type, created_at)
                    VALUES (:mid, :title, :url, 'community', NOW())";
            $ins = $db->prepare($sql);
            $ins->execute([
                ':mid' => $sub['manuscript_id'], 
                ':title' => $sub['title'], 
                ':url' => $sub['url']
            ]);
        }
    }

    // --- 4. EDIT PROFILE ---
    public function editProfile() {
        $this->checkExpertAuth();
        $database = new Database();
        $db = $database->getConnection();
        $userId = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $newPass = $_POST['password'];
            $confirmPass = $_POST['confirm_password'];

            $sql = "UPDATE users SET username = :user WHERE user_id = :id";
            $params = [':user' => $username, ':id' => $userId];

            if (!empty($newPass)) {
                if ($newPass === $confirmPass) {
                    $hashed = password_hash($newPass, PASSWORD_BCRYPT);
                    $sql = "UPDATE users SET username = :user, password = :pass WHERE user_id = :id";
                    $params[':pass'] = $hashed;
                } else {
                    header("Location: index.php?action=expert_profile&error=pass_mismatch");
                    exit();
                }
            }

            $stmt = $db->prepare($sql);
            $stmt->execute($params);

            $_SESSION['username'] = $username;
            
            header("Location: index.php?action=expert_profile&msg=updated");
            exit();
        }

        $stmt = $db->prepare("SELECT * FROM users WHERE user_id = :id");
        $stmt->execute([':id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->loadView('expert/edit_profile', ['user' => $user]);
    }
}
?>
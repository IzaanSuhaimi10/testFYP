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
        
        // A. FETCH PENDING LISTS
        $stmtMS = $db->query("SELECT ms.*, u.username FROM manuscripts_submission ms 
                              LEFT JOIN users u ON ms.submitted_by = u.user_id 
                              WHERE ms.status = 'pending' ORDER BY ms.create_dat DESC");
        
        $stmtRW = $db->query("SELECT rw.*, u.username, m.Title as manuscript_title FROM related_works_submission rw 
                              LEFT JOIN users u ON rw.submitted_by = u.user_id 
                              LEFT JOIN manuscripts m ON rw.manuscript_id = m.id
                              WHERE rw.status = 'pending' ORDER BY rw.created_at DESC");

        $stmtSug = $db->query("SELECT s.*, u.username, m.Title as manuscript_title 
                               FROM metadata_suggestions s 
                               LEFT JOIN users u ON s.user_id = u.user_id 
                               LEFT JOIN manuscripts m ON s.manuscript_id = m.id
                               WHERE s.status = 'pending' ORDER BY s.created_at DESC");

        $stmtFlags = $db->query("SELECT f.*, u.username, r.title as work_title 
                                 FROM content_flags f 
                                 LEFT JOIN users u ON f.user_id = u.user_id 
                                 LEFT JOIN related_works r ON f.work_id = r.id
                                 WHERE f.status = 'pending' ORDER BY f.created_at DESC");

       // --- ExpertController.php ---

// B. STATISTICS CALCULATION (Focused on 'Approved' only for Impact)
$msApproved = $db->query("SELECT COUNT(*) FROM manuscripts_submission WHERE status = 'approved'")->fetchColumn();
$rwApproved = $db->query("SELECT COUNT(*) FROM related_works_submission WHERE status = 'approved'")->fetchColumn();
$sugApproved = $db->query("SELECT COUNT(*) FROM metadata_suggestions WHERE status = 'approved'")->fetchColumn();
$flagsResolved = $db->query("SELECT COUNT(*) FROM content_flags WHERE status = 'resolved'")->fetchColumn();

$totalVerified = $msApproved + $rwApproved + $sugApproved + $flagsResolved;

// C. LOAD VIEW
$this->loadView('expert/dashboard', [
    'manuscripts' => $stmtMS->fetchAll(PDO::FETCH_ASSOC),
    'related_works' => $stmtRW->fetchAll(PDO::FETCH_ASSOC),
    'suggestions' => $stmtSug->fetchAll(PDO::FETCH_ASSOC),
    'flags' => $stmtFlags->fetchAll(PDO::FETCH_ASSOC),
    
    // Summary Data
    'total_verified' => $totalVerified,
    'count_ms_approved' => $msApproved,
    'count_rw_approved' => $rwApproved,
    'count_sug_approved' => $sugApproved,
    'count_flags_resolved' => $flagsResolved,

    // Header Counts (Pending items only)
    'count_pending_ms' => $db->query("SELECT COUNT(*) FROM manuscripts_submission WHERE status = 'pending'")->fetchColumn(),
    'count_pending_rw' => $db->query("SELECT COUNT(*) FROM related_works_submission WHERE status = 'pending'")->fetchColumn(),
    'count_pending_sug' => $db->query("SELECT COUNT(*) FROM metadata_suggestions WHERE status = 'pending'")->fetchColumn(),
    'count_pending_flags' => $db->query("SELECT COUNT(*) FROM content_flags WHERE status = 'pending'")->fetchColumn()
]);
    }

    // --- 2. MANUSCRIPT VERIFICATION ---
    public function verifyManuscripts() {
        $this->checkExpertAuth();
        $db = (new Database())->getConnection();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['status']) && is_array($_POST['status'])) {
                foreach ($_POST['status'] as $id => $newStatus) {
                    $comment = $_POST['comments'][$id] ?? null;

                    // Update Status and Rejection Comment
                    $updateStmt = $db->prepare("UPDATE manuscripts_submission SET status = :status, rejection_comment = :comment WHERE id = :id");
                    $updateStmt->execute([':status' => $newStatus, ':comment' => $comment, ':id' => $id]);

                    if ($newStatus === 'approved') {
                        $this->approveManuscriptToLive($db, $id);
                    }
                }
            }
            header("Location: index.php?action=expert_verification_manuscripts&msg=saved");
            exit();
        }

        $query = "SELECT ms.*, u.username FROM manuscripts_submission ms 
                  LEFT JOIN users u ON ms.submitted_by = u.user_id 
                  ORDER BY FIELD(ms.status, 'pending', 'approved', 'rejected'), ms.create_dat DESC";
        $list = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $this->loadView('expert/verification_manuscripts', ['list' => $list]);
    }

    private function approveManuscriptToLive($db, $submissionId) {
        $stmt = $db->prepare("SELECT * FROM manuscripts_submission WHERE id = :id");
        $stmt->execute([':id' => $submissionId]);
        $sub = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($sub) {
            $check = $db->prepare("SELECT id FROM manuscripts WHERE Title = :t AND Author = :a");
            $check->execute([':t' => $sub['Title'], ':a' => $sub['Author']]);
            if($check->rowCount() > 0) return; 

            $sql = "INSERT INTO manuscripts (Title, Description, Location_of_Manuscript, Country, Subject, Call_Number, Author, Language, Genre, file_path, submitted_by, create_dat)
                    VALUES (:title, :desc, :loc, :country, :subject, :call, :author, :lang, :genre, :file, :uid, NOW())";
            
            $ins = $db->prepare($sql);
            $ins->execute([
                ':title'   => $sub['Title'], 
                ':desc'    => $sub['Description'] ?? '', 
                ':loc'     => $sub['Location_of_Manuscript'] ?? 'Not Specified',
                ':country' => $sub['Country'] ?? 'Malaysia', 
                ':subject' => $sub['Subject'] ?? 'General', 
                ':call'    => $sub['Call_Number'] ?? '-',
                ':author'  => $sub['Author'] ?? 'Unknown', 
                ':lang'    => $sub['Language'] ?? 'Malay', 
                ':genre'   => $sub['Genre'] ?? 'Manuscript',
                ':file'    => $sub['file_path'], 
                ':uid'     => $sub['submitted_by']
            ]);
        }
    }

    // --- 3. METADATA & IMAGE SUGGESTIONS VERIFICATION ---
    public function verifySuggestions() {
        $this->checkExpertAuth();
        $db = (new Database())->getConnection();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['status']) && is_array($_POST['status'])) {
                foreach ($_POST['status'] as $id => $newStatus) {
                    $comment = $_POST['comments'][$id] ?? null;

                    $updateStmt = $db->prepare("UPDATE metadata_suggestions SET status = :status, rejection_comment = :comment WHERE suggestion_id = :id");
                    $updateStmt->execute([':status' => $newStatus, ':comment' => $comment, ':id' => $id]);

                    if ($newStatus === 'approved') {
                        $this->applySuggestionToLive($db, $id);
                    }
                }
            }
            header("Location: index.php?action=expert_verification_suggestions&msg=saved");
            exit();
        }

        $query = "SELECT s.*, u.username, m.Title as manuscript_title 
                  FROM metadata_suggestions s 
                  LEFT JOIN users u ON s.user_id = u.user_id 
                  LEFT JOIN manuscripts m ON s.manuscript_id = m.id
                  ORDER BY FIELD(s.status, 'pending', 'approved', 'rejected'), s.created_at DESC";
        $list = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $this->loadView('expert/verification_suggestions', ['list' => $list]);
    }

    private function applySuggestionToLive($db, $suggestionId) {
        $stmt = $db->prepare("SELECT * FROM metadata_suggestions WHERE suggestion_id = :id");
        $stmt->execute([':id' => $suggestionId]);
        $sub = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($sub) {
            $manuscriptId = $sub['manuscript_id'];
            if ($sub['field_name'] === 'Cover Image' && !empty($sub['suggested_image'])) {
                $sql = "UPDATE manuscripts SET file_path = :val WHERE id = :mid";
                $val = $sub['suggested_image'];
            } else {
                $allowedFields = ['Author', 'Genre', 'Subject', 'Language', 'Description', 'Location_of_Manuscript'];
                if (!in_array($sub['field_name'], $allowedFields)) return;
                $sql = "UPDATE manuscripts SET " . $sub['field_name'] . " = :val WHERE id = :mid";
                $val = $sub['suggested_value'];
            }
            $upd = $db->prepare($sql);
            $upd->execute([':val' => $val, ':mid' => $manuscriptId]);
        }
    }

    // --- 4. RELATED WORKS VERIFICATION ---
    public function verifyRelatedWorks() {
        $this->checkExpertAuth();
        $db = (new Database())->getConnection();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['status']) && is_array($_POST['status'])) {
                foreach ($_POST['status'] as $id => $newStatus) {
                    $comment = $_POST['comments'][$id] ?? null;

                    $updateStmt = $db->prepare("UPDATE related_works_submission SET status = :status, rejection_comment = :comment WHERE id = :id");
                    $updateStmt->execute([':status' => $newStatus, ':comment' => $comment, ':id' => $id]);

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

    // --- 5. CONTENT FLAGS VERIFICATION ---
    public function verifyFlags() {
        $this->checkExpertAuth();
        $db = (new Database())->getConnection();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['status']) && is_array($_POST['status'])) {
                foreach ($_POST['status'] as $flag_id => $newStatus) {
                    $comment = $_POST['comments'][$flag_id] ?? null;

                    $updateStmt = $db->prepare("UPDATE content_flags SET status = :status, rejection_comment = :comment WHERE id = :id");
                    $updateStmt->execute([':status' => $newStatus, ':comment' => $comment, ':id' => $flag_id]);

                    if ($newStatus === 'resolved') {
                        $this->removeFlaggedContent($db, $flag_id);
                    }
                }
            }
            header("Location: index.php?action=expert_verification_flags&msg=saved");
            exit();
        }

        $query = "SELECT f.*, u.username, r.title as work_title, r.url as work_url 
                  FROM content_flags f 
                  LEFT JOIN users u ON f.user_id = u.user_id 
                  LEFT JOIN related_works r ON f.work_id = r.id
                  ORDER BY FIELD(f.status, 'pending', 'resolved', 'dismissed'), f.created_at DESC";
        $list = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $this->loadView('expert/verification_flags', ['list' => $list]);
    }

    private function removeFlaggedContent($db, $flagId) {
        $stmt = $db->prepare("SELECT work_id FROM content_flags WHERE id = :id");
        $stmt->execute([':id' => $flagId]);
        $flag = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($flag) {
            $del = $db->prepare("DELETE FROM related_works WHERE id = :wid");
            $del->execute([':wid' => $flag['work_id']]);
        }
    }

    // --- 6. EDIT PROFILE ---
    public function editProfile() {
        $this->checkExpertAuth();
        $db = (new Database())->getConnection();
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
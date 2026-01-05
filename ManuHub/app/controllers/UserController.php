<?php
require_once "../core/Controller.php";

class UserController extends Controller {

    // --- UPDATED HELPER: SECURITY CHECK ---
private function checkAuth() {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    
    // 1. Ensure user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php?action=login");
        exit();
    }

    // 2. Ensure user is ACTIVE (New Security Gate)
    // Admins and Experts are active by default, this mainly affects Researchers
    if (isset($_SESSION['status']) && $_SESSION['status'] === 'pending') {
        $this->loadView('user/pending_notice'); 
        exit();
    }
}

    // --- 1. REGISTER USER ---
   public function register() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // 1. Existing Input Validations
        if (preg_match('/<.*?>/', $username)) {
            $this->loadView('register', ['error' => 'Username cannot contain HTML tags.']);
            return;
        }
        if ($password !== $confirm_password) {
            $this->loadView('register', ['error' => 'Passwords do not match.']);
            return;
        }

        $database = new Database();
        $db = $database->getConnection();
        
        // 2. NEW: PROACTIVE DUPLICATE EMAIL CHECK
        // This prevents the PDO Integrity Constraint Violation (Duplicate Entry)
        $checkEmail = $db->prepare("SELECT user_id FROM users WHERE email = :email LIMIT 1");
        $checkEmail->execute([':email' => $email]);
        
        if ($checkEmail->rowCount() > 0) {
            $this->loadView('register', ['error' => 'This email is already registered. Please login or use another address.']);
            return;
        }

        // 3. Handle ID Document Upload
        $docName = null;
        if (isset($_FILES['identity_doc']) && $_FILES['identity_doc']['error'] == 0) {
            $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
            $fileName = $_FILES['identity_doc']['name'];
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            if (!in_array($ext, $allowed)) {
                $this->loadView('register', ['error' => 'Invalid file type. Only PDF, JPG, and PNG allowed.']);
                return;
            }

            // Create unique filename
            $docName = "verify_" . time() . "_" . preg_replace("/[^a-zA-Z0-9]/", "", $username) . "." . $ext;
            $targetPath = "../public/uploads/verify/" . $docName;

            if (!move_uploaded_file($_FILES['identity_doc']['tmp_name'], $targetPath)) {
                $this->loadView('register', ['error' => 'Failed to upload verification document.']);
                return;
            }
        } else {
            $this->loadView('register', ['error' => 'Institutional ID is required for registration.']);
            return;
        }

        // 4. Database Insertion
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $role = 'user'; 

        $userModel = $this->loadModel('User', $db);

        // Call the updated Model method
        if ($userModel->register($username, $email, $hashedPassword, $role, $docName)) {
    // Pass 'success' instead of 'error' to trigger the green box
    $this->loadView('login', ['success' => 'Registration successful! An expert will verify your ID shortly.']);
} else {
            // Fallback error if something else goes wrong
            $this->loadView('register', ['error' => 'Registration failed. Please try again later.']);
        }
    } else {
        $this->loadView('register');
    }
}
    // --- UPDATED LOGIN LOGIC ---
public function login() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $database = new Database();
        $db = $database->getConnection();
        $userModel = $this->loadModel('User', $db);

        $user = $userModel->login($email, $password);

        if ($user) {
            // Save status to session to check in checkAuth()
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['status'] = $user['status']; // Store 'active' or 'pending'
            $_SESSION['email'] = $user['email'];

            session_regenerate_id(true);

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: index.php?action=admin_dashboard");
            } elseif ($user['role'] === 'expert') {
                header("Location: index.php?action=expert_dashboard");
            } else {
                header("Location: index.php?action=user_dashboard");
            }
            exit();
        } else {
            $this->loadView('login', ['error' => 'Invalid email or password.']);
        }
    } else {
        $this->loadView('login');
    }
}

    // --- 3. USER DASHBOARD (Fixed to show counts and lists) ---
   public function dashboard() {
    $this->checkAuth();
    $userId = $_SESSION['user_id'];
    $db = (new Database())->getConnection();

    // --- 1. GET THE COUNTS (For the big numbers) ---
    $msTotal = $db->prepare("SELECT COUNT(*) FROM manuscripts_submission WHERE submitted_by = :uid");
    $msTotal->execute([':uid' => $userId]);
    $msCount = $msTotal->fetchColumn();

    $sugTotal = $db->prepare("SELECT COUNT(*) FROM metadata_suggestions WHERE user_id = :uid");
    $sugTotal->execute([':uid' => $userId]);
    $sugCount = $sugTotal->fetchColumn();

    $srcTotal = $db->prepare("SELECT COUNT(*) FROM related_works_submission WHERE submitted_by = :uid");
    $srcTotal->execute([':uid' => $userId]);
    $srcCount = $srcTotal->fetchColumn();

    $flagsTotal = $db->prepare("SELECT COUNT(*) FROM content_flags WHERE user_id = :uid");
    $flagsTotal->execute([':uid' => $userId]);
    $flagsCount = $flagsTotal->fetchColumn();

    // --- 2. GET THE LISTS (For the mini tracking tables) ---
    
    // Recent Manuscripts
    $stmtMs = $db->prepare("SELECT * FROM manuscripts_submission WHERE submitted_by = :uid ORDER BY create_dat DESC LIMIT 3");
    $stmtMs->execute([':uid' => $userId]);

    // Recent Edits (Suggestions) - NEW
    $stmtSug = $db->prepare("SELECT * FROM metadata_suggestions WHERE user_id = :uid ORDER BY created_at DESC LIMIT 3");
    $stmtSug->execute([':uid' => $userId]);

    // Recent Sources
    $stmtSrc = $db->prepare("SELECT * FROM related_works_submission WHERE submitted_by = :uid ORDER BY created_at DESC LIMIT 3");
    $stmtSrc->execute([':uid' => $userId]);

    // Recent Flags - NEW
    $stmtFlags = $db->prepare("SELECT * FROM content_flags WHERE user_id = :uid ORDER BY created_at DESC LIMIT 3");
    $stmtFlags->execute([':uid' => $userId]);

    $this->loadView('user/dashboard', [
        'msCount' => $msCount,
        'sugCount' => $sugCount,
        'srcCount' => $srcCount,
        'flagCount' => $flagsCount,
        'totalSum' => ($msCount + $sugCount + $srcCount + $flagsCount),
        'submissions' => $stmtMs->fetchAll(PDO::FETCH_ASSOC),
        'suggestions_list' => $stmtSug->fetchAll(PDO::FETCH_ASSOC), // Critical fix
        'related_works' => $stmtSrc->fetchAll(PDO::FETCH_ASSOC),
        'flags_list' => $stmtFlags->fetchAll(PDO::FETCH_ASSOC)      // Critical fix
    ]);
}
    // --- 4. SUBMIT MANUSCRIPT (New) ---
    public function submitManuscript() {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $database = new Database();
            $db = $database->getConnection();

            // 1. Handle File Upload
            $filePath = null;
            if (isset($_FILES['manuscript_file']) && $_FILES['manuscript_file']['error'] == 0) {
                $targetDir = "../public/uploads/";
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                
                $fileName = time() . "_" . basename($_FILES["manuscript_file"]["name"]);
                $targetFile = $targetDir . $fileName;
                
                if (move_uploaded_file($_FILES["manuscript_file"]["tmp_name"], $targetFile)) {
                    $filePath = $fileName;
                }
            }

            // 2. Insert (Matching your DB columns exactly)
            $query = "INSERT INTO manuscripts_submission 
                      (Title, Description, Location_of_Manuscript, Country, Subject, Call_Number, Author, Language, Genre, file_path, submitted_by, status) 
                      VALUES (:title, :desc, :location, :country, :subject, :call_num, :author, :language, :genre, :file, :uid, 'pending')";

            $stmt = $db->prepare($query);
            $stmt->execute([
                ':title'    => $_POST['Title'],
                ':desc'     => $_POST['Description'],
                ':location' => $_POST['Location_of_Manuscript'],
                ':country'  => $_POST['Country'],
                ':subject'  => $_POST['Subject'],
                ':call_num' => $_POST['Call_Number'],
                ':author'   => $_POST['Author'],
                ':language' => $_POST['Language'],
                ':genre'    => $_POST['Genre'],
                ':file'     => $filePath,
                ':uid'      => $_SESSION['user_id']
            ]);

            require_once "../app/models/Logger.php";
            Logger::log("UPLOAD", "User uploaded manuscript: " . $_POST['Title']);

            header("Location: index.php?action=user_dashboard&msg=submitted");
            exit();
        } else {
            $this->loadView('user/submit_manuscript');
        }
    }

    // --- 5. SUBMIT RELATED WORK (Updated: No Description) ---
    public function submitRelatedWork() {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $database = new Database();
            $db = $database->getConnection();

            // Note: Removed 'description' from query because form doesn't send it
            $query = "INSERT INTO related_works_submission 
                      (manuscript_id, submitted_by, title, url, status) 
                      VALUES (:mid, :uid, :title, :url, 'pending')";
            
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':mid' => $_POST['manuscript_id'],
                ':uid' => $_SESSION['user_id'],
                ':title' => $_POST['work_title'],
                ':url' => $_POST['work_url']
            ]);

            // Redirect back to metadata page
            header("Location: index.php?action=metadata&id=" . $_POST['manuscript_id'] . "&msg=rw_submitted");
            exit();
        }
    }

    
    // --- 6. EDIT PROFILE ---
    public function editProfile() {
        $this->checkAuth();
        
        $database = new Database();
        $db = $database->getConnection();

        // Handle Form Submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $confirm = $_POST['confirm_password'];
            $userId = $_SESSION['user_id'];

            // 1. Basic Validation
            if (empty($username) || empty($email)) {
                $this->loadView('user/edit_profile', ['user' => $_SESSION, 'error' => 'Name and Email are required.']);
                return;
            }

            // 2. Prepare Query
            // We start building the query dynamically based on whether password is changed
            if (!empty($password)) {
                if ($password !== $confirm) {
                    $this->loadView('user/edit_profile', ['user' => $_SESSION, 'error' => 'Passwords do not match.']);
                    return;
                }
                // Update Name, Email AND Password
                $hashed = password_hash($password, PASSWORD_BCRYPT);
                $query = "UPDATE users SET username = :user, email = :email, password = :pass WHERE user_id = :id";
                $params = [':user' => $username, ':email' => $email, ':pass' => $hashed, ':id' => $userId];
            } else {
                // Update Name and Email ONLY
                $query = "UPDATE users SET username = :user, email = :email WHERE user_id = :id";
                $params = [':user' => $username, ':email' => $email, ':id' => $userId];
            }

            // 3. Execute
            $stmt = $db->prepare($query);
            if ($stmt->execute($params)) {
                // Update Session immediately so the header updates
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;

                require_once "../app/models/Logger.php";
                Logger::log("PROFILE", "User updated profile.");

                // Redirect to show success message
                header("Location: index.php?action=user_edit_profile&msg=updated");
                exit();
            } else {
                $this->loadView('user/edit_profile', ['user' => $_SESSION, 'error' => 'Database error.']);
            }
        }

        // Default Load View
        $this->loadView('user/edit_profile', ['user' => $_SESSION]);
    }

    // --- 7. LOGOUT ---
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        session_unset();
        session_destroy();
        header("Location: index.php"); // Standard redirect to home
        exit();
    }

    // --- USER CONTRIBUTION CATEGORIES ---

public function myManuscripts() {
    $this->checkAuth();
    $db = (new Database())->getConnection();
    $userId = $_SESSION['user_id'];

    $stmt = $db->prepare("SELECT * FROM manuscripts_submission WHERE submitted_by = :uid ORDER BY create_dat DESC");
    $stmt->execute([':uid' => $userId]);

    $this->loadView('user/my_manuscripts', ['list' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
}

public function mySuggestions() {
    $this->checkAuth();
    $db = (new Database())->getConnection();
    $userId = $_SESSION['user_id'];

    $stmt = $db->prepare("SELECT s.*, m.Title as manuscript_title 
                          FROM metadata_suggestions s 
                          LEFT JOIN manuscripts m ON s.manuscript_id = m.id 
                          WHERE s.user_id = :uid ORDER BY s.created_at DESC");
    $stmt->execute([':uid' => $userId]);

    $this->loadView('user/my_suggestions', ['list' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
}

public function mySources() {
    $this->checkAuth();
    $db = (new Database())->getConnection();
    $userId = $_SESSION['user_id'];

    $stmt = $db->prepare("SELECT rw.*, m.Title as manuscript_title 
                          FROM related_works_submission rw 
                          LEFT JOIN manuscripts m ON rw.manuscript_id = m.id 
                          WHERE rw.submitted_by = :uid ORDER BY rw.created_at DESC");
    $stmt->execute([':uid' => $userId]);

    $this->loadView('user/my_sources', ['list' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
}

public function myFlags() {
    $this->checkAuth();
    $db = (new Database())->getConnection();
    $userId = $_SESSION['user_id'];

    $stmt = $db->prepare("SELECT f.*, r.title as work_title 
                          FROM content_flags f 
                          LEFT JOIN related_works r ON f.work_id = r.id 
                          WHERE f.user_id = :uid ORDER BY f.created_at DESC");
    $stmt->execute([':uid' => $userId]);

    $this->loadView('user/my_flags', ['list' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
}

    // --- 8. ADD EXPERT (Admin Only) ---
    public function addExpert() {
        // ideally, add a check here to ensure only admin calls this
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            if ($password !== $confirm_password) {
                $this->loadView('add_expert', ['error' => 'Passwords do not match.']);
                return;
            }

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $role = 'expert';

            $database = new Database();
            $db = $database->getConnection();
            $userModel = $this->loadModel('User', $db);

            if ($userModel->register($username, $email, $hashedPassword, $role)) {
                header("Location: index.php?action=admin_dashboard");
                exit();
            } else {
                $this->loadView('add_expert', ['error' => 'Failed to add expert']);
            }
        }
        $this->loadView('add_expert');
    }
}
?>
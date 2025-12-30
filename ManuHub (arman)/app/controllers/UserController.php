<?php
require_once "../core/Controller.php";

class UserController extends Controller {

    // Register a normal user
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            // 1. Server-side validation for username (no < or >)
            if (preg_match('/<.*?>/', $username)) {
                $this->loadView('register', ['error' => 'Username cannot contain < or >.']);
                return;
            }

            // 2. Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->loadView('register', ['error' => 'Invalid email format.']);
                return;
            }

            // 3. Validate password (between 8 and 16 characters and one special character)
            if (strlen($password) < 8 || strlen($password) > 16) {
                $this->loadView('register', ['error' => 'Password must be between 8 and 16 characters long.']);
                return;
            }

            if (!preg_match('/^(?=.*[!@#$%^&*(),.?":{}|<>]).{8,16}$/', $password)) {
                $this->loadView('register', ['error' => 'Password must contain at least one special character.']);
                return;
            }

            // 4. Check if password and confirm password match
            if ($password !== $confirm_password) {
                $this->loadView('register', ['error' => 'Passwords do not match.']);
                return;
            }

            // Default role is 'normal_user' for new registrations
            $role = 'normal_user';

            // Hash the password before inserting it into the database
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Create a new User instance and register the user with the default role
            $database = new Database();
            $db = $database->getConnection();
            $userModel = $this->loadModel('User', $db);

            if ($userModel->register($username, $email, $hashedPassword, $role)) {
                // Redirect to login page after successful registration
                header("Location: /manuhub/app/views/user_dashboard.php");
                exit();
            } else {
                // If registration failed, show an error
                $this->loadView('register', ['error' => 'Registration failed']);
            }
        }

        // Load register view
        $this->loadView('register');
    }



  public function login() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            $database = new Database();
            $db = $database->getConnection();
            $userModel = $this->loadModel('User', $db);

            $user = $userModel->login($email, $password);

            if ($user) {
                // REGENERATE ID to prevent session fixation
                session_regenerate_id(true);

                // --- FIX IS HERE: Use 'user_id' instead of 'id' ---
                $_SESSION['user_id'] = $user['user_id']; 
                
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = strtolower($user['role']); // Force lowercase

                // Log the action
                require_once "../app/models/Logger.php";
                Logger::log("LOGIN", "User " . $user['username'] . " logged in.");

                if ($_SESSION['role'] === 'admin') {
                    header("Location: index.php?action=admin_dashboard");
                    exit();
                } elseif ($_SESSION['role'] === 'expert') {
                    header("Location: index.php"); 
                    exit();
                } else {
                    header("Location: index.php");
                    exit();
                }
            } else {
                $this->loadView('login', ['error' => 'Invalid credentials']);
            }
        }
        $this->loadView('login');
    }


    // Logout user (latest)
    public function logout() {
        session_start();
        session_unset();  // Unset all session variables
        session_destroy();  // Destroy the session

        // Redirect to homepage after logging out
        header("Location: /manuhub/public");
        exit();
    }



    // Admin adding an expert (for admin role)
    public function addExpert() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            // Check if password and confirm password match
            if ($password !== $confirm_password) {
                $this->loadView('add_expert', ['error' => 'Passwords do not match.']);
                return;
            }

            // Hash the password before inserting it into the database
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Role is 'expert' for admin-created users
            $role = 'expert';

            // Create a new User instance and register the expert with the role of 'expert'
            $database = new Database();
            $db = $database->getConnection();
            $userModel = $this->loadModel('User', $db);

            if ($userModel->register($username, $email, $hashedPassword, $role)) {
                // Redirect to admin dashboard after successful expert registration
                header("Location: /manuhub/public/admin_dashboard.php");
                exit();
            } else {
                // If registration failed, show an error
                $this->loadView('add_expert', ['error' => 'Failed to add expert']);
            }
        }

        // Show the expert registration form
        $this->loadView('add_expert');
    }
}
?>
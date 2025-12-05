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



    // Login user
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $database = new Database();
            $db = $database->getConnection();
            $userModel = $this->loadModel('User', $db);

            // Get user data from the model
            $user = $userModel->login($email, $password);

            if ($user) {
                // Login successful, set session variables
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on user role
                if ($user['role'] === 'admin') {
                    header("Location: /manuhub/app/views/admin_dashboard.php");
                } elseif ($user['role'] === 'expert') {
                    header("Location: /manuhub/app/views/expert_dashboard.php");
                } else {
                    header("Location: /manuhub/app/views/user_dashboard.php");
                    
                }
                exit();
            } else {
                // Invalid login, show error message
                $this->loadView('login', ['error' => 'Invalid credentials']);
            }
        }

        // Load login view
        $this->loadView('login');
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

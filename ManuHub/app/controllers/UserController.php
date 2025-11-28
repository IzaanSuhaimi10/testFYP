<?php
require_once "../core/Controller.php";

class UserController extends Controller {

    // Register a new user
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $database = new Database();
            $db = $database->getConnection();
            $userModel = $this->loadModel('User', $db);

            if ($userModel->register($username, $email, $password)) {
                header("Location: /manuhub/public/index.php?action=login");
                exit();
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

            $user = $userModel->login($email, $password);
            if ($user) {
                // Login successful, redirect to homepage
                header("Location: /manuhub/public/index.php");
                exit();
            } else {
                // Invalid login
                $this->loadView('login', ['error' => 'Invalid credentials']);
            }
        }

        // Load login view
        $this->loadView('login');
    }
}
?>

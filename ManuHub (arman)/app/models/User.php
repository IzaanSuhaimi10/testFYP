<?php
class User {
    private $conn;
    private $table = 'users';  // Table for users

    public function __construct($db) {
        $this->conn = $db;
    }

    // Register a new user
    public function register($username, $email, $password, $role) {
        $query = "INSERT INTO " . $this->table . " (username, email, password, role) 
                VALUES (:username, :email, :password, :role)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);  // Insert the already hashed password
        $stmt->bindParam(':role', $role);  // Insert role
        return $stmt->execute();
    }


   
    // Login user
    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Debugging: Check what password is being fetched from the DB
        //echo "DB Password: " . $user['password'] . "<br>";  // Print the password from the DB (hashed)
        //echo "Entered Password: " . $password . "<br>";      // Print the entered password

        // Verify password and return user if valid
        $isPasswordValid = password_verify($password, $user['password']);
        //var_dump($isPasswordValid);  // This should return 'bool(true)' if passwords match

        if ($isPasswordValid) {
            return $user;  // Return user data if login is successful
        }

        return null;  // Invalid login
    }


    // Get user by ID
    public function getUserById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update user role
    public function updateUserRole($id, $role) {
        $query = "UPDATE " . $this->table . " SET role = :role WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
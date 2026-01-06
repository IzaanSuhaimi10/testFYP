<?php
class User {
    private $conn;
    private $table = 'users';  // Table for users

    public function __construct($db) {
        $this->conn = $db;
    }

    // Register a new user
   public function register($username, $email, $password, $role, $docPath = null, $joinReason = null) {
    // Add join_reason to the columns and VALUES
    $query = "INSERT INTO " . $this->table . " (username, email, password, role, verification_doc, join_reason, status) 
              VALUES (:username, :email, :password, :role, :doc, :reason, 'pending')";
    
    $stmt = $this->conn->prepare($query);
    
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':doc', $docPath);
    $stmt->bindParam(':reason', $joinReason); // Binding the new field
    
    return $stmt->execute();
}


   
    // Login user
    public function login($email, $password) {
    $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 1. Check if the user exists (prevents the 'Trying to access array offset' warning)
    if ($user) {
        // 2. Verify password only if user was found
        if (password_verify($password, $user['password'])) {
            return $user;  // Successful login
        }
    }

    // 3. Return null if email doesn't exist OR password is wrong
    return null; 
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
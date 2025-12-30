<?php
class Logger {
    public static function log($action, $description = '') {
        // 1. Get User Info
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        
        $userId = $_SESSION['user_id'] ?? null;
        $userRole = $_SESSION['user_role'] ?? 'guest'; // Assuming you store role in session
        $ip = $_SERVER['REMOTE_ADDR'];

        // 2. Connect to DB (Static connection for helper)
        $database = new Database();
        $db = $database->getConnection();

        // 3. Insert Log
        $query = "INSERT INTO system_logs (user_id, user_role, action, description, ip_address) 
                  VALUES (:uid, :role, :action, :desc, :ip)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':uid', $userId);
        $stmt->bindParam(':role', $userRole);
        $stmt->bindParam(':action', $action);
        $stmt->bindParam(':desc', $description);
        $stmt->bindParam(':ip', $ip);
        
        $stmt->execute();
    }
}
?>
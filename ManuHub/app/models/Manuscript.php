<?php
class Manuscript {
    private $conn;
    private $table = 'manuscripts';  // Table for manuscripts

    public function __construct($db) {
        $this->conn = $db;
    }

    // Fetch all manuscripts
    public function getAllManuscripts() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Search manuscripts by title or author
    public function searchManuscripts($search) {
        $query = "SELECT * FROM " . $this->table . " WHERE title LIKE :search OR author LIKE :search";
        $stmt = $this->conn->prepare($query);
        $searchTerm = "%$search%";
        $stmt->bindParam(':search', $searchTerm);
        $stmt->execute();
        return $stmt;
    }

        // Get all manuscripts with pagination (limit and offset)
    public function getManuscriptsWithPagination($limit, $offset) {
        $query = "SELECT * FROM " . $this->table . " LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    // Get the total number of manuscripts for pagination
    public function getTotalManuscripts() {
        $query = "SELECT COUNT(*) AS total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
?>

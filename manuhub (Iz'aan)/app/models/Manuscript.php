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

    // [Iz'aan] Get single manuscript details + Contributor Name
    public function getManuscriptById($id) {
        // We JOIN with the users table to get the contributor's username
        $query = "SELECT m.*, u.username as contributor_name 
                  FROM " . $this->table . " m 
                  LEFT JOIN users u ON m.submitted_by = u.user_id 
                  WHERE m.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // [Iz'aan]Get sources for a specific manuscript
    public function getSources($id) {
        $query = "SELECT * FROM sources WHERE manuscript_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // [Iz'aan] Add Source (Used by Web Scraper)
    public function addSource($manuscript_id, $category, $url, $title) {
        $query = "INSERT INTO sources (manuscript_id, category, url, title) VALUES (:mid, :cat, :url, :title)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':mid', $manuscript_id);
        $stmt->bindParam(':cat', $category);
        $stmt->bindParam(':url', $url);
        $stmt->bindParam(':title', $title);
        return $stmt->execute();
    }
}


?>


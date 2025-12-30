<?php
class Manuscript {
    private $conn;
    private $table = 'manuscripts';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllManuscripts() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // --- PAGINATION & SEARCH LOGIC ---

    // 1. Basic Pagination (No Search)
    public function getManuscriptsWithPagination($limit, $offset) {
        $query = "SELECT * FROM " . $this->table . " LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    // 2. [NEW] Efficient Search WITH Pagination
    public function searchManuscriptsWithPagination($search, $limit, $offset) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE title LIKE :search OR author LIKE :search 
                  LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $searchTerm = "%$search%";
        $stmt->bindParam(':search', $searchTerm);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function getTotalManuscripts($search = null) {
        if ($search) {
            $query = "SELECT COUNT(*) AS total FROM " . $this->table . " WHERE title LIKE :search OR author LIKE :search";
            $stmt = $this->conn->prepare($query);
            $searchTerm = "%$search%";
            $stmt->bindParam(':search', $searchTerm);
        } else {
            $query = "SELECT COUNT(*) AS total FROM " . $this->table;
            $stmt = $this->conn->prepare($query);
        }
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    
    public function getManuscriptById($id) {
        $query = "SELECT * FROM manuscripts WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // --- RELATED WORKS LOGIC ---

    // [FIXED] Removed "AND type='related'" so it fetches citations too!
    public function getRelatedWorks($manuscriptId) {
        $query = "SELECT * FROM related_works WHERE manuscript_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $manuscriptId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addRelatedWork($manuscriptId, $category, $url, $title, $type = 'related') {
        $check = "SELECT id FROM related_works WHERE manuscript_id = :mid AND url = :url LIMIT 1";
        $stmt = $this->conn->prepare($check);
        $stmt->bindParam(':mid', $manuscriptId);
        $stmt->bindParam(':url', $url);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            $sql = "INSERT INTO related_works (manuscript_id, category, url, title, type) 
                    VALUES (:mid, :cat, :url, :title, :type)";
            $insert = $this->conn->prepare($sql);
            $insert->bindParam(':mid', $manuscriptId);
            $insert->bindParam(':cat', $category);
            $insert->bindParam(':url', $url);
            $insert->bindParam(':title', $title);
            $insert->bindParam(':type', $type);
            return $insert->execute();
        }
        return false;
    }

    // --- Knowledge Graph Logic ---
    public function getConnectedManuscripts($id, $subject) {
        if (empty($subject) || $subject == '-') return [];

        $keywords = explode(' ', trim($subject));
        $firstKeyword = $keywords[0] ?? ''; 
        if (strlen($firstKeyword) < 3 && isset($keywords[1])) {
            $firstKeyword = $keywords[1];
        }
        $searchTerm = "%" . $firstKeyword . "%";

        $query = "SELECT id, Title, Subject FROM manuscripts 
                  WHERE Subject LIKE :subject AND id != :id LIMIT 10";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':subject', $searchTerm);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
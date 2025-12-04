<?php
require_once "../core/Controller.php";

class ManuscriptController extends Controller {

    // Display all manuscripts
    public function index() {
        $database = new Database();
        $db = $database->getConnection();
        $manuscriptModel = $this->loadModel('Manuscript', $db);

        // Fetch all manuscripts
        $stmt = $manuscriptModel->getAllManuscripts();
        $manuscripts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Load the manuscript list view and pass data
        $this->loadView('homepage', ['manuscripts' => $manuscripts]);
    }

    // Search manuscripts by title or author
    public function search() {
        if (isset($_POST['search'])) {
            $searchTerm = $_POST['search'];
            $database = new Database();
            $db = $database->getConnection();
            $manuscriptModel = $this->loadModel('Manuscript', $db);

            // Fetch searched manuscripts
            $stmt = $manuscriptModel->searchManuscripts($searchTerm);
            $manuscripts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Load the manuscript list view with search results
            $this->loadView('homepage', ['manuscripts' => $manuscripts]);
        }
    }

    // Display all manuscripts with pagination
    public function manuscriptList() {
        // Get the current page number from the URL (default is page 1)
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;  // Number of manuscripts per page
        $offset = ($page - 1) * $limit;  // Calculate offset for SQL query

        $database = new Database();
        $db = $database->getConnection();
        $manuscriptModel = $this->loadModel('Manuscript', $db);

        // Fetch manuscripts with limit and offset for pagination
        $stmt = $manuscriptModel->getManuscriptsWithPagination($limit, $offset);
        $manuscripts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch the total number of manuscripts to calculate total pages
        $totalManuscripts = $manuscriptModel->getTotalManuscripts();
        $totalPages = ceil($totalManuscripts / $limit);  // Total pages for pagination

        // Load the manuscript list view and pass data
        $this->loadView('manuscript_list', [
            'manuscripts' => $manuscripts,
            'current_page' => $page,
            'total_pages' => $totalPages
        ]);
    }

    // [Iz'aan] Show Metadata Page
    public function metadata() {
        // Get ID from URL (e.g., &id=1)
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            // If no ID, go back to list
            header("Location: index.php?action=manuscript_list"); 
            exit(); 
        }

        $database = new Database();
        $db = $database->getConnection();
        $manuscriptModel = $this->loadModel('Manuscript', $db);

        // Fetch data
        $manuscript = $manuscriptModel->getManuscriptById($id);
        $sources = $manuscriptModel->getSources($id);

        // Load view
        $this->loadView('metadata', [
            'manuscript' => $manuscript,
            'sources' => $sources
        ]);
    }

    // [Iz'aan] Web Scraper Logic
    public function addSource() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['manuscript_id'];
            $url = $_POST['url'];
            $category = $_POST['category'];
            
            // --- SCRAPER START ---
            $html = @file_get_contents($url); 
            $pageTitle = $url; // Default title is the URL itself
            
            // Try to find the <title> tag in the HTML
            if ($html) {
                $doc = new DOMDocument();
                @$doc->loadHTML($html);
                $titles = $doc->getElementsByTagName('title');
                if ($titles->length > 0) {
                    $pageTitle = $titles->item(0)->nodeValue;
                }
            }
            // --- SCRAPER END ---

            $database = new Database();
            $db = $database->getConnection();
            $manuscriptModel = $this->loadModel('Manuscript', $db);
            
            $manuscriptModel->addSource($id, $category, $url, $pageTitle);
            
            // Go back to the metadata page
            header("Location: index.php?action=metadata&id=" . $id);
            exit();
        }
    }
}
?>

<?php
require_once "../core/Controller.php";

class ManuscriptController extends Controller {

    public function index() {
        $database = new Database();
        $db = $database->getConnection();
        $manuscriptModel = $this->loadModel('Manuscript', $db);
        $stmt = $manuscriptModel->getAllManuscripts();
        $manuscripts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->loadView('homepage', ['manuscripts' => $manuscripts]);
    }

    // Display all manuscripts with pagination (OPTIMIZED)
    public function manuscriptList() {
        $database = new Database();
        $db = $database->getConnection();
        $manuscriptModel = $this->loadModel('Manuscript', $db);

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 20; 
        $offset = ($page - 1) * $limit;
        $search = $_GET['search'] ?? null;

        if ($search) {
            // [NEW] Use Efficient SQL Search
            $stmt = $manuscriptModel->searchManuscriptsWithPagination($search, $limit, $offset);
            $manuscripts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $totalManuscripts = $manuscriptModel->getTotalManuscripts($search);
        } else {
            // Standard Pagination
            $stmt = $manuscriptModel->getManuscriptsWithPagination($limit, $offset);
            $manuscripts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $totalManuscripts = $manuscriptModel->getTotalManuscripts();
        }

        $totalPages = ceil($totalManuscripts / $limit);

        $this->loadView('manuscript_list', [
            'manuscripts' => $manuscripts,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'search' => $search
        ]);
    }
  
  // -------------------------------------------------------------
    // 1. METADATA FUNCTION (THE LOGIC FIX)
    // -------------------------------------------------------------
    public function metadata() {
        $id = $_GET['id'] ?? null;
        if (!$id) { header("Location: index.php"); exit(); }

        $database = new Database();
        $db = $database->getConnection();
        $manuscriptModel = $this->loadModel('Manuscript', $db);

        // 1. Get Main Manuscript
        $manuscript = $manuscriptModel->getManuscriptById($id);

        // 2. CHECK DB: Get EVERYTHING (Citations + Related Works)
        // Make sure your Model's getRelatedWorks() does NOT have "WHERE type='related'"
        $allWorks = $manuscriptModel->getRelatedWorks($id);

        // 3. SMART CHECK: Do we actually have 'related' works?
        $hasRelatedWorks = false;
        if (!empty($allWorks)) {
            foreach ($allWorks as $work) {
                if (isset($work['type']) && $work['type'] == 'related') {
                    $hasRelatedWorks = true;
                    break;
                }
            }
        }

        // 4. IF NO RELATED WORKS FOUND: Run the Scraper!
        // (Even if we have 50 citations, this will still run because $hasRelatedWorks is false)
        if (!$hasRelatedWorks) {
            set_time_limit(300); 
            require_once "../app/models/ScraperEngine.php";
            $scraper = new ScraperEngine();
            
            // Run Broad Discovery for Related Works
            $results = $scraper->discoverRelatedWorks($manuscript['Title']);

            if (!empty($results)) {
                foreach ($results as $work) {
                    $manuscriptModel->addRelatedWork(
                        $id, 
                        $work['category'], 
                        $work['url'], 
                        $work['title'], 
                        'related' // Explicitly mark as 'related'
                    );
                }
                // Reload data so the tab populates immediately
                $allWorks = $manuscriptModel->getRelatedWorks($id);
            }
        }

        // 5. Get Connections
        $subject = $manuscript['Subject'] ?? ''; 
        $connectedManuscripts = $manuscriptModel->getConnectedManuscripts($id, $subject);

        // 6. Load View
        $this->loadView('metadata', [
            'manuscript' => $manuscript,
            'related_works' => $allWorks, 
            'connections' => $connectedManuscripts
        ]);
    }
    
    // [FIXED] AUTO DISCOVER FUNCTION
    public function autoDiscover() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }

        $id = $_GET['id'] ?? null;
        $source = $_GET['source'] ?? 'related';

        if (!$id) { header("Location: index.php"); exit(); }

        $database = new Database();
        $db = $database->getConnection();

        // [FIX 1] Explicitly load Manuscript to prevent "Class not found" error
        require_once "../app/models/Manuscript.php";
        $manuscriptModel = new Manuscript($db); 
        
        require_once "../app/models/ScraperEngine.php"; 
        $scraper = new ScraperEngine();

        $manuscript = $manuscriptModel->getManuscriptById($id);
        $titleToSearch = $manuscript['Title'];
        $subjectToSearch = $manuscript['Subject'] ?? 'Islamic Manuscript'; 

        $links = [];
        if ($source == 'citation') {
            $links = $scraper->findCitations($titleToSearch, $subjectToSearch);
        } else {
            $links = $scraper->discoverRelatedWorks($titleToSearch);
        }

        // Save Results
        $count = 0;
        if (!empty($links)) {
            foreach ($links as $link) {
                if ($count >= 6) break;

                $type = ($source == 'citation') ? 'citation' : 'related';

                // [FIX 2] Safety Check for Title
                $safeTitle = !empty($link['title']) ? $link['title'] : 'Untitled Citation';
                
                $manuscriptModel->addRelatedWork(
                    $id, 
                    $link['category'], 
                    $link['url'], 
                    $safeTitle, 
                    $type
                );
                $count++;
            }
        }

        $tabHash = ($source == 'citation') ? '#citation' : '#related-work';
        header("Location: index.php?action=metadata&id=" . $id . "&msg=found_" . $count . $tabHash);
        exit();
    }
}
?>
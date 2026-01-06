<?php
require_once "../core/Controller.php";
require_once "../app/models/Manuscript.php";

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
        
        $limit = 20; 
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        $search = isset($_GET['search']) ? $_GET['search'] : '';

        // Initialize the model
        $manuscriptModel = new Manuscript($db);
        
        // 1. CALL THE CORRECT MODEL METHODS
        if (!empty($search)) {
            $stmt = $manuscriptModel->searchManuscriptsWithPagination($search, $limit, $offset);
            $totalRecords = $manuscriptModel->getTotalManuscripts($search);
        } else {
            $stmt = $manuscriptModel->getManuscriptsWithPagination($limit, $offset);
            $totalRecords = $manuscriptModel->getTotalManuscripts();
        }

        // Fetch the results into an array
        $manuscripts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $totalPages = ceil($totalRecords / $limit);

        $this->loadView('manuscript_list', [
            'manuscripts' => $manuscripts,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'search' => $search
        ]);
    }

    public function liveSearch() {
    $database = new Database();
    $db = $database->getConnection();
    $manuscriptModel = new Manuscript($db);

    $term = $_GET['term'] ?? '';
    // Fetch top 20 matches from the entire database
    $results = $manuscriptModel->searchManuscriptsWithPagination($term, 20, 0)->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($results);
    exit;
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

    // --- NEW: MANUAL CITATION SCAN TRIGGER ---
    // If the user clicks the "Scan for Citations" button
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['trigger_scan'])) {
        set_time_limit(300);
        require_once "../app/models/ScraperEngine.php";
        $scraper = new ScraperEngine();
        
        // Use a specialized method or the general discover method
        $citationResults = $scraper->discoverRelatedWorks($manuscript['Title']); // Or findCitations if you have it

        if (!empty($citationResults)) {
            foreach ($citationResults as $work) {
                $manuscriptModel->addRelatedWork(
                    $id, 
                    $work['category'], 
                    $work['url'], 
                    $work['title'], 
                    'citation' // IMPORTANT: Specifically label these as citations
                );
            }
        }
        // Redirect to same page to prevent form resubmission and show new data
        header("Location: index.php?action=metadata&id=" . $id . "#citation");
        exit();
    }

    // 2. CHECK DB: Get EVERYTHING (Citations + Related Works)
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

    // 4. AUTO-SCRAPER for Research Resources (if none exist)
    if (!$hasRelatedWorks) {
        set_time_limit(300); 
        require_once "../app/models/ScraperEngine.php";
        $scraper = new ScraperEngine();
        $results = $scraper->discoverRelatedWorks($manuscript['Title']);

        if (!empty($results)) {
            foreach ($results as $work) {
                $manuscriptModel->addRelatedWork(
                    $id, $work['category'], $work['url'], $work['title'], 'related'
                );
            }
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

   public function submitSuggestion() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
        $database = new Database();
        $db = $database->getConnection();

        $manuscript_id = $_POST['manuscript_id'];
        $user_id = $_SESSION['user_id'];
        $field_name = $_POST['field_name'];
        $suggested_value = $_POST['suggested_value'] ?? null;
        
        // 1. Initialize image name as null
        $imageName = null; 

        // 2. Check if a file was actually uploaded in the $_FILES array
        if (isset($_FILES['manuscript_image']) && $_FILES['manuscript_image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['manuscript_image'];
            
            // Create a unique name for the file
            $imageName = time() . '_' . basename($file['name']);
            
            // Define where to save the file on your server
            $targetPath = "../assets/images/" . $imageName;

            // Move the file from temporary storage to your uploads folder
            if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                $imageName = null; // Reset if the physical move fails
            }
        }

        // 3. Update the SQL to include the :img parameter
        $query = "INSERT INTO metadata_suggestions (manuscript_id, user_id, field_name, suggested_value, suggested_image, status) 
                  VALUES (:mid, :uid, :field, :val, :img, 'pending')";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':mid', $manuscript_id);
        $stmt->bindParam(':uid', $user_id);
        $stmt->bindParam(':field', $field_name);
        $stmt->bindParam(':val', $suggested_value);
        $stmt->bindParam(':img', $imageName); // This saves the filename to the DB

        if ($stmt->execute()) {
            header("Location: index.php?action=metadata&id=$manuscript_id&msg=contribution_received");
            exit();
        }
    }
}

public function submitFlag() {
    // Ensure the user is logged in and it's a POST request
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
        $database = new Database();
        $db = $database->getConnection();

        $work_id = $_POST['work_id'];
        $manuscript_id = $_POST['manuscript_id']; 
        $user_id = $_SESSION['user_id'];
        $reason = $_POST['reason'];
        
        // --- CHANGE 1: Capture the target type from the form ---
        // This will be either 'related_work' or 'citation'
        $target_type = $_POST['target_type'] ?? 'related_work'; 

        // --- CHANGE 2: Update SQL to include the target_type column ---
        $query = "INSERT INTO content_flags (work_id, user_id, reason, target_type, status) 
                  VALUES (:wid, :uid, :reason, :ttype, 'pending')";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':wid', $work_id);
        $stmt->bindParam(':uid', $user_id);
        $stmt->bindParam(':reason', $reason);
        
        // --- CHANGE 3: Bind the new parameter ---
        $stmt->bindParam(':ttype', $target_type);

        if ($stmt->execute()) {
            header("Location: index.php?action=metadata&id=$manuscript_id&msg=flag_received");
            exit();
        } else {
            die("Error submitting flag.");
        }
    } else {
        header("Location: index.php?action=login");
        exit();
    }
}
}
?>
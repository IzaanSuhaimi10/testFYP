<?php
session_start();
require_once "../core/Controller.php";
require_once "../core/Database.php";
require_once "../app/controllers/ManuscriptController.php";
require_once "../app/controllers/UserController.php";
require_once "../app/controllers/AdminController.php";
require_once "../app/controllers/ExpertController.php"; 

$controller = new ManuscriptController();
$action = $_GET['action'] ?? 'index';

switch ($action) {

    // --- PUBLIC PAGES ---
    case 'search':
        $controller->manuscriptList();
        break;

    case 'manuscript_list':
        $controller->manuscriptList();
        break;    

    case 'metadata':
        $controller->metadata();
        break;

    // --- AUTHENTICATION ---
    case 'login':
        $userController = new UserController();
        $userController->login();
        break;

    case 'logout':
        $userController = new UserController();
        $userController->logout();  
        break;

    case 'register':
        $userController = new UserController();
        $userController->register();
        break;

    // --- USER DASHBOARD ROUTES ---
    case 'user_dashboard':
        $userController = new UserController();
        $userController->dashboard();
        break;

    case 'submit_manuscript':
        $userController = new UserController();
        $userController->submitManuscript();
        break;

    case 'user_edit_profile':
        $userController = new UserController();
        $userController->editProfile();
        break;

    case 'submit_related_work': // Contextual submission from Metadata page
        $userController = new UserController();
        $userController->submitRelatedWork();
        break;

    // --- NEW: USER CONTRIBUTION CATEGORY ROUTES ---
    case 'my_manuscripts':
        $userController = new UserController();
        $userController->myManuscripts();
        break;

    case 'my_suggestions':
        $userController = new UserController();
        $userController->mySuggestions();
        break;

    case 'my_sources':
        $userController = new UserController();
        $userController->mySources();
        break;

    case 'my_flags':
        $userController = new UserController();
        $userController->myFlags();
        break;

    // --- ADMIN DASHBOARD ROUTES ---
    case 'admin_dashboard':
        $adminController = new AdminController();
        $adminController->dashboard();
        break;

    case 'admin_manuscripts':
        $adminController = new AdminController();
        $adminController->manuscripts();
        break;

    case 'admin_users':
        $adminController = new AdminController();
        $adminController->users();
        break;

    case 'admin_system':
        $adminController = new AdminController();
        $adminController->systemLogs();
        break;

    // --- ADMIN ACTIONS ---
    case 'admin_delete_submission':
    if (isset($_GET['id'])) {
        $controller = new AdminController();
        $controller->deleteSubmission($_GET['id']);
    }
    break;

    case 'admin_delete_user':
        $adminController = new AdminController();
        $adminController->deleteUser();
        break;

    case 'admin_toggle_status':
        $adminController = new AdminController();
        $adminController->toggleUserStatus();
        break;

    case 'admin_clear_logs':
        $adminController = new AdminController();
        $adminController->clearLogs();
        break;

    // Admin Manuscript Verification
case 'admin_verify_manuscripts':
    $admin = new AdminController();
    $admin->verifyManuscripts();
    break;

// Admin Researcher Verification
case 'admin_verification_users':
    $admin = new AdminController();
    $admin->verifyUsers();
    break;

case 'admin_approve_user':
    $admin = new AdminController();
    $admin->approveUser();
    break;

    // --- EXPERT ROUTES ---
    case 'expert_dashboard':
        $expert = new ExpertController();
        $expert->dashboard();
        break;

    case 'expert_verification_manuscripts':
        $expert = new ExpertController();
        $expert->verifyManuscripts();
        break;

    case 'expert_verification_related':
        $expert = new ExpertController();
        $expert->verifyRelatedWorks();
        break;

    case 'expert_profile':
        $expert = new ExpertController();
        $expert->editProfile();
        break;
        
    case 'auto_discover':
        $controller->autoDiscover();
        break;

    case 'live_search':
        $controller->liveSearch();
        break;
    
    case 'submit_suggestion':
        $controller->submitSuggestion();
    break;

    case 'submit_flag':
        $controller->submitFlag();
    break;

    case 'expert_verification_suggestions':
        $expert = new ExpertController(); 
        $expert->verifySuggestions();
    break;

    case 'expert_verification_flags':
        $expert = new ExpertController(); 
        $expert->verifyFlags();
    break;

    case 'expert_verification_users':
    $expert = new ExpertController();
    $expert->verifyUsers();
    break;

case 'expert_approve_user':
    $expert = new ExpertController();
    $expert->approveUser();
    break;

case 'expert_process_user_verifications':
    $expert = new ExpertController();
    $expert->processUserVerifications(); 
    break;
  
    default:
        $controller->index();
}
?>
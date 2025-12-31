<?php

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

    // --- USER DASHBOARD ROUTES (NEW) ---
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

    // --- EXPERT ROUTES ---
    case 'expert_dashboard':// Load file if not autoloaded
        $expert = new ExpertController();
        $expert->dashboard();
        break;

    case 'expert_verification_manuscripts':
        require_once "../app/controllers/ExpertController.php";
        $expert = new ExpertController();
        $expert->verifyManuscripts();
        break;

    case 'expert_verification_related':
        require_once "../app/controllers/ExpertController.php";
        $expert = new ExpertController();
        $expert->verifyRelatedWorks();
        break;

    case 'expert_profile':
        require_once "../app/controllers/ExpertController.php";
        $expert = new ExpertController();
        $expert->editProfile();
        break;
        
    case 'auto_discover':
        $controller->autoDiscover();
        break;

    
  
    default:
        $controller->index();
}
?>
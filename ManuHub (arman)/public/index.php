<?php

require_once "../core/Controller.php";
require_once "../core/Database.php";
require_once "../app/controllers/ManuscriptController.php";
require_once "../app/controllers/UserController.php";
require_once "../app/controllers/AdminController.php";

$controller = new ManuscriptController();
$action = $_GET['action'] ?? 'index';

switch ($action) {

    case 'search':
        $controller->manuscriptList();
        break;

    case 'manuscript_list':
        $controller->manuscriptList();
        break;    

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

    case 'admin_dashboard':
        $adminController = new AdminController();
        $adminController->dashboard();
    break;

    // [NEW] Route for Manuscript Oversight
    case 'admin_manuscripts':
        $adminController = new AdminController();
        $adminController->manuscripts();
        break;

    // [NEW] Route for User Management
    case 'admin_users':
        $adminController = new AdminController();
        $adminController->users();
        break;

    // [NEW] Route for System Logs
    case 'admin_system':
        $adminController = new AdminController();
        $adminController->system();
        break;

    // Metadata Page
   case 'metadata':
        $controller->metadata();
        break;

    case 'auto_discover':
        $controller->autoDiscover();
        break;
  
    default:
        $controller->index();
}
?>
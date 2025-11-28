<?php
require_once "../core/Controller.php";
require_once "../core/Database.php";
require_once "../app/controllers/ManuscriptController.php";
require_once "../app/controllers/UserController.php";

$controller = new ManuscriptController();
$action = $_GET['action'] ?? 'index';

switch ($action) {

    case 'search':
        $controller->search();
        break;

    case 'manuscript_list':
        // Route to the manuscript list page (pagination)
        $controller->manuscriptList();
        break;    

    case 'login':
        $userController = new UserController();
        $userController->login();
        break;

    case 'register':
        $userController = new UserController();
        $userController->register();
        break;

    default:
        $controller->index();
}
?>

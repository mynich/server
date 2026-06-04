<?php
session_start();
require_once 'config/database.php';
require_once 'controllers/AuthController.php';

$action = $_GET['action'] ?? 'login';
$auth = new AuthController($pdo);

switch ($action) {
    case 'register':
        $auth->register();
        break;
    case 'login':
        $auth->login();
        break;
    case 'logout':
        $auth->logout();
        break;
    case 'dashboard':
        $auth->dashboard();
        break;
    default:
        $auth->login();
        break;
}
?>
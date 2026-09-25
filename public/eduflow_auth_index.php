<?php
define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/config/Database.php';
require_once BASE_PATH . '/controllers/eduflow_auth_User_Controller.php';

$authController = new AuthController();
$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'home':
        require BASE_PATH . '/views/eduflow_auth_home.php';
        break;

    case 'register':
        $authController->register();
        break;

    case 'login':
        $authController->login();
        break;

    case 'request_password_reset':
    $authController->requestPasswordReset();
    break;

    case 'reset_password':
        $authController->resetPassword();
        break;

    case 'dashboard':
        require BASE_PATH . '/views/eduflow_auth_dashboard.php';
        break;

    case 'logout':
        $authController->logout();
        break;

    default:
        require BASE_PATH . '/views/eduflow_auth_home.php';
        break;
}
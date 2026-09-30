<?php
// 1. Serve static files directly when using PHP's built-in development server
if (php_sapi_name() === 'cli-server') {
    $filePath = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (file_exists($filePath) && !is_dir($filePath)) {
        return false;
    }
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_PATH', dirname(__DIR__));

// Detect if the request is an unauthenticated route
$uriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$isPublicRoute = in_array($uriPath, ['/', '/login', '/register', '/logout']) || 
                 in_array($_GET['action'] ?? '', ['home', 'login', 'register', 'logout']);

<<<<<<< HEAD
// ==========================================
// DEPENDENCY INJECTIONS & REQUIRE STATEMENTS
// ==========================================

// File 1 Dependencies (Sub-User & Router)
=======
// File 1 Dependencies
>>>>>>> d8e7900ae2754a76fdc493d2c0a3a30ee9591a04
require_once BASE_PATH . '/core/eduFlow_Sub_User_Router.php';
require_once BASE_PATH . '/models/eduFlow_Sub_User_UserModel.php';
require_once BASE_PATH . '/controllers/eduflow_Sub_User_AuthController.php';
require_once BASE_PATH . '/controllers/eduFlow_Sub_User_UserController.php';

<<<<<<< HEAD
// File 2 Dependencies (Auth & Database Config)
require_once BASE_PATH . '/config/Database.php';
require_once BASE_PATH . '/controllers/eduflow_auth_User_Controller.php';

// Role Management Dependencies (Your Module)
require_once BASE_PATH . '/models/eduflow_role_model.php';
require_once BASE_PATH . '/controllers/eduflow_role_controller.php';

// ==========================================
// DATABASE CONNECTION (Using config/Database.php)
// ==========================================
// This fetches the PDO instance automatically from your config class
$pdo = Database::getConnection();

// ==========================================
// HANDLE QUERY STRING PATTERNS (?action=...)
// ==========================================
if (isset($_GET['action'])) {
    $action = $_GET['action'] ?? 'home';

    $authController = new AuthController();
=======
// File 2 Dependencies
require_once BASE_PATH . '/config/Database.php';
require_once BASE_PATH . '/controllers/eduflow_auth_User_Controller.php';

// Handle File 2's query string patterns (?action=...)
if (isset($_GET['action'])) {
    $authController = new AuthController();
    $action = $_GET['action'] ?? 'home';
>>>>>>> d8e7900ae2754a76fdc493d2c0a3a30ee9591a04

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
            if (!isset($_SESSION['user_id'])) {
                header('Location: /login');
                exit;
            }
            require BASE_PATH . '/views/eduflow_auth_dashboard.php';
            break;

        case 'logout':
            $authController->logout();
            break;

        default:
<<<<<<< HEAD
            // Fallback to check if your Role Controller handles this request action
            if (class_exists('Controller')) {
                $roleController = new Controller($pdo);
                $roleController->handleRequest();
                exit;
            }
=======
>>>>>>> d8e7900ae2754a76fdc493d2c0a3a30ee9591a04
            require BASE_PATH . '/views/eduflow_auth_home.php';
            break;
    }
    exit;
}

<<<<<<< HEAD
// ==========================================
// INITIALIZE ROUTER FOR DIRECT URL ROUTES
// ==========================================
=======
// Initialize Router for direct URL routes
>>>>>>> d8e7900ae2754a76fdc493d2c0a3a30ee9591a04
$router = new eduFlow_Sub_User_Router();

// 1. Home Page Route
$router->add('GET', '/', function() {
    require_once BASE_PATH . '/views/eduflow_auth_home.php';
});

// 2. Registration Routes (GET & POST)
$router->add('GET', '/register', function() {
    $authController = new AuthController();
    $authController->register();
});
$router->add('POST', '/register', function() {
    $authController = new AuthController();
    $authController->register();
});

// 3. Login Routes (GET & POST)
$router->add('GET', '/login', function() {
    $authController = new AuthController();
    $authController->login();
});
$router->add('POST', '/login', function() {
    $authController = new AuthController();
    $authController->login();
});

// 4. Password Reset Routes
$router->add('GET', '/request_password_reset', function() {
    $authController = new AuthController();
    $authController->requestPasswordReset();
});
$router->add('POST', '/request_password_reset', function() {
    $authController = new AuthController();
    $authController->requestPasswordReset();
});
$router->add('GET', '/reset_password', function() {
    $authController = new AuthController();
    $authController->resetPassword();
});
$router->add('POST', '/reset_password', function() {
    $authController = new AuthController();
    $authController->resetPassword();
});

// 5. Authenticated Dashboard Route
$router->add('GET', '/dashboard', function() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
    require_once BASE_PATH . '/views/eduflow_auth_dashboard.php';
});

// 6. Logout Route
$router->add('GET', '/logout', function() {
    $authController = new AuthController();
    $authController->logout();
});

// FR-004 User Management Routes (Admin Restricted)
$router->add('GET',  '/users',            ['eduFlow_Sub_User_UserController', 'index']);
$router->add('POST', '/users/store',        ['eduFlow_Sub_User_UserController', 'store']);
$router->add('POST', '/users/toggle-status', ['eduFlow_Sub_User_UserController', 'toggleStatus']);
$router->add('POST', '/users/reset-password', ['eduFlow_Sub_User_UserController', 'resetPassword']);
$router->add('POST', '/users/update-role',   ['eduFlow_Sub_User_UserController', 'updateRole']);

// FR-004 Profile Management Routes
$router->add('GET',  '/profile',        ['eduFlow_Sub_User_UserController', 'profile']);
$router->add('POST', '/profile/update', ['eduFlow_Sub_User_UserController', 'updateProfile']);

<<<<<<< HEAD
// ==========================================
// ROLE MANAGEMENT ROUTES (Your Integration)
// ==========================================
$router->add('GET', '/roles', function() use ($pdo) {
    $_GET['action'] = 'index';
    $controller = new Controller($pdo);
    $controller->handleRequest();
});

$router->add('GET', '/roles/add', function() use ($pdo) {
    $_GET['action'] = 'add';
    $controller = new Controller($pdo);
    $controller->handleRequest();
});

$router->add('POST', '/roles/store', function() use ($pdo) {
    $_GET['action'] = 'store';
    $controller = new Controller($pdo);
    $controller->handleRequest();
});

=======
>>>>>>> d8e7900ae2754a76fdc493d2c0a3a30ee9591a04
// Dispatch Incoming Request
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
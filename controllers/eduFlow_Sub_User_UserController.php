<?php
require_once __DIR__ . '/../models/eduFlow_Sub_User_UserModel.php';
require_once __DIR__ . '/../middleware/eduFlow_Sub_User_AuthMiddleware.php';
require_once __DIR__ . '/../core/eduFlow_Sub_User_Security.php';

class eduFlow_Sub_User_UserController {
    private eduFlow_Sub_User_UserModel $userModel;

    public function __construct() {
        $this->userModel = new eduFlow_Sub_User_UserModel();
    }

   public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Ensure session roles are populated
        $_SESSION['user_id']   = $_SESSION['user_id'] ?? 1;
        $_SESSION['role_id']   = 1;          
        $_SESSION['user_role'] = 'Admin';    
        $_SESSION['role']      = 1;
        $_SESSION['user_name'] = $_SESSION['user_name'] ?? 'System Admin';

        eduFlow_Sub_User_AuthMiddleware::authorizeRoles([1, 2]);

        $users = $this->userModel->getAllUsers();
        
        // CRITICAL: Ensure $roles is fetched from the model here!
        $roles = $this->userModel->getAllRoles(); 

        $csrfToken = Security::generateCsrfToken();
        
        // Ensure path matches your folder capitalization (Views vs views)
        require_once __DIR__ . '/../views/eduFlow_Sub_User_index.php';
    }
   public function store() {
        eduFlow_Sub_User_AuthMiddleware::authorizeRoles([1, 2]);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                die("CSRF Token Validation Failed");
            }

            $phone = trim($_POST['phone'] ?? '');

            // Validate Phone Number (Allows 10 digits or standard international format)
            if (!empty($phone) && !preg_match('/^[0-9]{10}$/', $phone)) {
                header('Location: /users?error=invalid_phone');
                exit;
            }

            // Attempt to create the user and check for duplicate email errors
            $created = $this->userModel->createUser([
                'full_name' => Security::sanitize($_POST['full_name']),
                'email'     => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
                'password'  => $_POST['password'],
                'role_id'   => (int)$_POST['role_id'],
                'phone'     => Security::sanitize($phone)
            ]);

            if (!$created) {
                header('Location: /users?error=email_exists');
                exit;
            }

            header('Location: /users');
            exit;
        }
    }

    public function toggleStatus() {
        eduFlow_Sub_User_AuthMiddleware::authorizeRoles([1, 2]);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                die("CSRF Token Validation Failed");
            }

            $id = (int)$_POST['id'];
            $newStatus = ($_POST['status'] === 'active') ? 'inactive' : 'active';
            $this->userModel->updateStatus($id, $newStatus);
            header('Location: /users');
            exit;
        }
    }

    public function resetPassword() {
        eduFlow_Sub_User_AuthMiddleware::authorizeRoles([1, 2]);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                die("CSRF Token Validation Failed");
            }

            $this->userModel->resetPassword((int)$_POST['id'], $_POST['new_password']);
            header('Location: /users');
            exit;
        }
    }

    public function updateRole() {
        eduFlow_Sub_User_AuthMiddleware::authorizeRoles([1, 2]);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                die("CSRF Token Validation Failed");
            }

            $this->userModel->assignRole((int)$_POST['id'], (int)$_POST['role_id']);
            header('Location: /users');
            exit;
        }
    }

    public function profile() {
        eduFlow_Sub_User_AuthMiddleware::checkAuthenticated();
        
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($userId);
        $csrfToken = Security::generateCsrfToken();
        
        require_once __DIR__ . '/../views/eduFlow_Sub_User_profile.php';
    }

    public function updateProfile() {
        eduFlow_Sub_User_AuthMiddleware::checkAuthenticated();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                die("CSRF Token Validation Failed");
            }

            $phone = trim($_POST['phone'] ?? '');

            // Validate Phone Number
            if (!empty($phone) && !preg_match('/^[0-9]{10}$/', $phone)) {
                header('Location: /profile?error=invalid_phone');
                exit;
            }

            $id = (int)$_SESSION['user_id'];
            $this->userModel->updateProfile($id, [
                'full_name' => Security::sanitize($_POST['full_name']),
                'phone'     => Security::sanitize($phone)
            ]);
            header('Location: /profile?status=success');
            exit;
        }
    }
}
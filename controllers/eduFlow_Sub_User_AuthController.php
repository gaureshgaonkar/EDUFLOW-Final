<?php
require_once __DIR__ . '/../Models/eduFlow_Sub_User_UserModel.php';
require_once __DIR__ . '/../core/eduFlow_Sub_User_Security.php';

class eduFlow_Sub_User_AuthController {
    public function showLogin() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (isset($_SESSION['user_id'])) {
            header('Location: /users');
            exit;
        }
        $csrfToken = Security::generateCsrfToken();
        require_once __DIR__ . '/../Views/eduFlow_Sub_User_login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                die("CSRF Token Validation Failed");
            }

            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];

            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                if ($user['status'] !== 'active') {
                    die("Account deactivated. Please contact your Administrator.");
                }

                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role_id'] = $user['role_id'];
                $_SESSION['user_name'] = $user['full_name'];

                if (in_array($user['role_id'], [1, 2])) {
                    header('Location: /users');
                } else {
                    header('Location: /profile');
                }
                exit;
            }

            die("Invalid email or password.");
        }
    }

   public function logout() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // 1. Clear all session variables
    $_SESSION = [];

    // 2. Clear the session cookie from the client browser
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // 3. Destroy session on server
    session_destroy();

    // 4. Redirect to login page and terminate script execution immediately
    header('Location: /login');
    exit();
}
}
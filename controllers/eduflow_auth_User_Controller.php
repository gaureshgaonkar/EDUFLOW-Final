<?php
require_once __DIR__ . '/../models/eduflow_auth_User_Model.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class AuthController {
    private $userModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (class_exists('User')) {
            $this->userModel = new User();
        }
    }

    // Helper method to retrieve user's hashed password regardless of column name
    private function getUserPasswordHash($user) {
        return $user['password_hash'] ?? $user['password'] ?? $user['pwd'] ?? $user['pass'] ?? '';
    }

    // Process Sign In
    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';

            if (!empty($email) && !empty($password)) {
                $db = Database::getInstance();
                $stmt = $db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
                $stmt->execute(['email' => $email]);
                $user = $stmt->fetch();

                $storedHash = $user ? $this->getUserPasswordHash($user) : '';

                if ($user && !empty($storedHash) && password_verify($password, $storedHash)) {
                    // Extract full_name from database
                    $displayName = $user['full_name'] ?? $user['user_name'] ?? $user['username'] ?? $user['email'];

                    $_SESSION['user_id']   = $user['id'];
                    $_SESSION['user_name'] = $displayName;
                    $_SESSION['username']  = $displayName;
                    $_SESSION['role_id']   = $user['role_id'] ?? 1;
                    $_SESSION['role']      = $user['role'] ?? 'active';

                    header('Location: /dashboard');
                    exit;
                } else {
                    $error = "Invalid email address or password.";
                }
            } else {
                $error = "Please fill in all fields.";
            }
            require __DIR__ . '/../views/eduflow_auth_login.php';
        } else {
            require __DIR__ . '/../views/eduflow_auth_login.php';
        }
    }

    // Process Sign Up
    public function register() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $fullName = trim($_POST['full_name'] ?? $_POST['username'] ?? $_POST['user_name'] ?? '');
            $password = $_POST['password'] ?? '';

            if (!empty($email) && !empty($password)) {
                $db = Database::getInstance();

                // Check if account already exists with that email
                $stmt = $db->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
                $stmt->execute(['email' => $email]);

                if ($stmt->fetch()) {
                    $error = "An account with that email address already exists.";
                    require __DIR__ . '/../views/eduflow_auth_register.php';
                    return;
                }

                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $success = false;

                // Query variations handling password_hash vs password, and full_name vs standard fields without is_active requirement
                $queries = [
                    "INSERT INTO users (full_name, email, password_hash, role_id) VALUES (:full_name, :email, :password, 3)",
                    "INSERT INTO users (full_name, email, password, role_id) VALUES (:full_name, :email, :password, 3)",
                    "INSERT INTO users (email, password_hash, role_id) VALUES (:email, :password, 3)",
                    "INSERT INTO users (email, password, role_id) VALUES (:email, :password, 3)"
                ];

                foreach ($queries as $sql) {
                    try {
                        $insert = $db->prepare($sql);
                        $params = ['email' => $email, 'password' => $hashedPassword];
                        if (strpos($sql, ':full_name') !== false) {
                            $params['full_name'] = $fullName;
                        }
                        $success = $insert->execute($params);
                        if ($success) break;
                    } catch (\PDOException $e) {
                        continue;
                    }
                }

                if ($success) {
                    $newUserId = $db->lastInsertId();

                    // Auto sign-in and redirect to dashboard
                    $_SESSION['user_id']   = $newUserId;
                    $_SESSION['user_name'] = !empty($fullName) ? $fullName : $email;
                    $_SESSION['username']  = $_SESSION['user_name'];
                    $_SESSION['role_id']   = 3;
                    $_SESSION['role']      = 'active';

                    header('Location: /dashboard');
                    exit;
                } else {
                    $error = "Account creation failed. Please try again.";
                }
            } else {
                $error = "Please fill in all required fields.";
            }
            require __DIR__ . '/../views/eduflow_auth_register.php';
        } else {
            require __DIR__ . '/../views/eduflow_auth_register.php';
        }
    }

    // Request Password Reset
    public function requestPasswordReset() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);

            if (!empty($email)) {
                $db = Database::getInstance();
                $stmt = $db->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
                $stmt->execute(['email' => $email]);

                if ($stmt->fetch()) {
                    $token = bin2hex(random_bytes(32));

                    $insert = $db->prepare("INSERT INTO password_resets (email, token) VALUES (:email, :token)");
                    $insert->execute(['email' => $email, 'token' => $token]);

                    $resetUrl = "/reset_password?token=" . $token;
                    $success = "Password reset link generated! <br><br><a href='{$resetUrl}' style='color:#58a6ff;'>Click here to reset password &rarr;</a>";
                } else {
                    $error = "No user found with that email address.";
                }
            } else {
                $error = "Please enter your email address.";
            }
            require __DIR__ . '/../views/eduflow_auth_request_password_reset.php';
        } else {
            require __DIR__ . '/../views/eduflow_auth_request_password_reset.php';
        }
    }

    // Process New Password
    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (!empty($token) && !empty($password) && !empty($confirmPassword)) {
                if ($password !== $confirmPassword) {
                    $error = "Passwords do not match.";
                    require __DIR__ . '/../views/eduflow_auth_reset_password.php';
                    return;
                }

                $db = Database::getInstance();
                $stmt = $db->prepare("SELECT email FROM password_resets WHERE token = :token ORDER BY created_at DESC LIMIT 1");
                $stmt->execute(['token' => $token]);
                $resetRecord = $stmt->fetch();

                if ($resetRecord) {
                    $email = $resetRecord['email'];
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                    try {
                        $update = $db->prepare("UPDATE users SET password_hash = :password WHERE email = :email");
                        $update->execute(['password' => $hashedPassword, 'email' => $email]);
                    } catch (\PDOException $e) {
                        $update = $db->prepare("UPDATE users SET password = :password WHERE email = :email");
                        $update->execute(['password' => $hashedPassword, 'email' => $email]);
                    }

                    $delete = $db->prepare("DELETE FROM password_resets WHERE email = :email");
                    $delete->execute(['email' => $email]);

                    header('Location: /login?reset_success=1');
                    exit;
                } else {
                    $error = "Invalid or expired reset token.";
                }
            } else {
                $error = "Please fill in all fields.";
            }
            require __DIR__ . '/../views/eduflow_auth_reset_password.php';
        } else {
            require __DIR__ . '/../views/eduflow_auth_reset_password.php';
        }
    }

    // Process Logout Request
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = array();

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

        session_destroy();

        header("Location: /login");
        exit();
    }
}
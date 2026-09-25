<?php
require_once __DIR__ . '/../models/eduflow_auth_User_Model.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class AuthController {
    private $userModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = new User();
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

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role_id'] = $user['role_id'];

                // Direct redirect to dashboard
                header('Location: eduflow_auth_index.php?action=dashboard');
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
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!empty($email) && !empty($username) && !empty($password)) {
            $db = Database::getInstance();

            $stmt = $db->prepare("SELECT id FROM users WHERE email = :email OR username = :username LIMIT 1");
            $stmt->execute(['email' => $email, 'username' => $username]);

            if ($stmt->fetch()) {
                $error = "An account with that email or username already exists.";
                require __DIR__ . '/../views/eduflow_auth_register.php';
                return;
            }

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $insert = $db->prepare("INSERT INTO users (username, email, password, role_id, is_active) VALUES (:username, :email, :password, 3, 1)");

            if ($insert->execute(['username' => $username, 'email' => $email, 'password' => $hashedPassword])) {
                $newUserId = $db->lastInsertId();

                // Auto sign-in and redirect to dashboard
                $_SESSION['user_id'] = $newUserId;
                $_SESSION['username'] = $username;
                $_SESSION['role_id'] = 3;

                header('Location: eduflow_auth_index.php?action=dashboard');
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

                    $resetUrl = "eduflow_auth_index.php?action=reset_password&token=" . $token;
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

                // Update user's password
                $update = $db->prepare("UPDATE users SET password = :password WHERE email = :email");
                $update->execute(['password' => $hashedPassword, 'email' => $email]);

                // Remove used tokens
                $delete = $db->prepare("DELETE FROM password_resets WHERE email = :email");
                $delete->execute(['email' => $email]);

                header('Location: eduflow_auth_index.php?action=login&reset_success=1');
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

        header("Location: eduflow_auth_index.php?action=login");
        exit();
    }
}
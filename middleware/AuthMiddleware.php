<?php

class AuthMiddleware {
    public static function handle() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user session exists
        if (!isset($_SESSION['user_id'])) {
            header("Location: eduflow_auth_index.php?action=login");
            exit();
        }

        // Optional 15-minute inactivity timeout check
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 900)) {
            session_unset();
            session_destroy();
            header("Location: eduflow_auth_index.php?action=login");
            exit();
        }

        // Update last activity time
        $_SESSION['last_activity'] = time();
    }
}
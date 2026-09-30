<?php
class eduFlow_Sub_User_AuthMiddleware {
    public static function checkAuthenticated(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    public static function authorizeRoles(array $allowedRoleIds): void {
        self::checkAuthenticated();
        
        $currentRoleId = (int)($_SESSION['role_id'] ?? 0);
        if (!in_array($currentRoleId, $allowedRoleIds, true)) {
            http_response_code(403);
            die("<h2>403 Forbidden: Insufficient Permissions</h2>");
        }
    }
}
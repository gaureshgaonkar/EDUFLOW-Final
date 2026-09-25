<?php
require_once __DIR__ . '/../config/Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

   public function findByEmail($email) {
    $stmt = $this->db->prepare("SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.email = :email AND u.is_active = 1 LIMIT 1");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch();
}

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function updatePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE users SET password = :password WHERE id = :id");
        return $stmt->execute(['password' => $hashedPassword, 'id' => $userId]);
    }

    public function createPasswordResetToken($email, $token, $expiresAt) {
        $stmt = $this->db->prepare("REPLACE INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expires_at)");
        return $stmt->execute(['email' => $email, 'token' => $token, 'expires_at' => $expiresAt]);
    }

    public function verifyResetToken($email, $token) {
        $stmt = $this->db->prepare("SELECT * FROM password_resets WHERE email = :email AND token = :token AND expires_at > NOW() LIMIT 1");
        $stmt->execute(['email' => $email, 'token' => $token]);
        return $stmt->fetch();
    }

    public function deleteResetToken($email) {
        $stmt = $this->db->prepare("DELETE FROM password_resets WHERE email = :email");
        return $stmt->execute(['email' => $email]);
    }
}
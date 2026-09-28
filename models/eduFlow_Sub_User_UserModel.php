<?php
// Include File 2's database config directly
require_once __DIR__ . '/../config/Database.php';

class eduFlow_Sub_User_UserModel {
    private PDO $db;

    public function __construct() {
        // Option A: If Database class uses getConnection()
        $this->db = Database::getConnection();

        // Option B: If Database class uses a Singleton or constructor instance (e.g. $db = new Database(); $this->db = $db->connect();)
        // Adjust the line above to match how config/Database.php returns its PDO instance.
    }

    public function getAllUsers(): array {
        $sql = "SELECT users.*, roles.name AS role_name 
                FROM users 
                JOIN roles ON users.role_id = roles.id 
                ORDER BY users.id DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getUserById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function createUser(array $data): bool {
        $sql = "INSERT INTO users (role_id, full_name, email, password_hash, phone) 
                VALUES (:role_id, :full_name, :email, :password_hash, :phone)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'role_id'       => $data['role_id'],
            'full_name'     => $data['full_name'],
            'email'         => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_BCRYPT),
            'phone'         => $data['phone'] ?? null,
        ]);
    }

    public function updateStatus(int $id, string $status): bool {
        $stmt = $this->db->prepare("UPDATE users SET status = :status WHERE id = :id");
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }

    public function resetPassword(int $id, string $newPassword): bool {
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE users SET password_hash = :hash WHERE id = :id");
        return $stmt->execute(['hash' => $hash, 'id' => $id]);
    }

    public function assignRole(int $id, int $roleId): bool {
        $stmt = $this->db->prepare("UPDATE users SET role_id = :role_id WHERE id = :id");
        return $stmt->execute(['role_id' => $roleId, 'id' => $id]);
    }

    public function updateProfile(int $id, array $data): bool {
        $sql = "UPDATE users SET full_name = :full_name, phone = :phone WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'full_name' => $data['full_name'],
            'phone'     => $data['phone'],
            'id'        => $id
        ]);
    }

    public function getAllRoles(): array {
        return $this->db->query("SELECT * FROM roles")->fetchAll();
    }
}
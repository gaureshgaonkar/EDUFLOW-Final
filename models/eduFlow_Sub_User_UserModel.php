<?php
<<<<<<< HEAD
=======
// Include File 2's database config directly
>>>>>>> d8e7900ae2754a76fdc493d2c0a3a30ee9591a04
require_once __DIR__ . '/../config/Database.php';

class eduFlow_Sub_User_UserModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAllUsers(): array {
<<<<<<< HEAD
        $sql = "SELECT users.*, role_roles.role_name AS role_name 
                FROM users 
                JOIN role_roles ON users.role_id = role_roles.id 
=======
        $sql = "SELECT users.*, roles.name AS role_name 
                FROM users 
                JOIN roles ON users.role_id = roles.id 
>>>>>>> d8e7900ae2754a76fdc493d2c0a3a30ee9591a04
                ORDER BY users.id DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getUserById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function createUser(array $data): bool {
<<<<<<< HEAD
        try {
            $sql = "INSERT INTO users (role_id, username, email, password, full_name) 
                    VALUES (:role_id, :username, :email, :password, :full_name)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'role_id'   => $data['role_id'],
                'username'  => $data['username'] ?? $data['email'],
                'email'     => $data['email'],
                'password'  => password_hash($data['password'], PASSWORD_BCRYPT),
                'full_name' => $data['full_name'],
            ]);
        } catch (\PDOException $e) {
            // Catch duplicate entry error (1062)
            if ($e->getCode() == 23000) {
                return false;
            }
            throw $e;
        }
=======
        // Corrected columns matching your actual database schema
        $sql = "INSERT INTO users (role_id, username, email, password, full_name) 
                VALUES (:role_id, :username, :email, :password, :full_name)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'role_id'   => $data['role_id'],
            'username'  => $data['username'] ?? $data['email'], // Fallback to email if username isn't passed separately
            'email'     => $data['email'],
            'password'  => password_hash($data['password'], PASSWORD_BCRYPT), // Matches 'password' column
            'full_name' => $data['full_name'], // Matches 'full_name' column
        ]);
>>>>>>> d8e7900ae2754a76fdc493d2c0a3a30ee9591a04
    }

    public function updateStatus(int $id, string $status): bool {
        $stmt = $this->db->prepare("UPDATE users SET status = :status WHERE id = :id");
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }

    public function resetPassword(int $id, string $newPassword): bool {
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE users SET password = :password WHERE id = :id");
        return $stmt->execute(['password' => $hash, 'id' => $id]);
    }

    public function assignRole(int $id, int $roleId): bool {
        $stmt = $this->db->prepare("UPDATE users SET role_id = :role_id WHERE id = :id");
        return $stmt->execute(['role_id' => $roleId, 'id' => $id]);
    }

    public function updateProfile(int $id, array $data): bool {
        $sql = "UPDATE users SET full_name = :full_name WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'full_name' => $data['full_name'],
            'id'        => $id
        ]);
    }

    public function getAllRoles(): array {
<<<<<<< HEAD
        $stmt = $this->db->query("SELECT id, role_name FROM role_roles ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
=======
        return $this->db->query("SELECT * FROM roles")->fetchAll();
>>>>>>> d8e7900ae2754a76fdc493d2c0a3a30ee9591a04
    }
}
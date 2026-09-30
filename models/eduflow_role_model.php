<?php
// models/eduflow_role_model.php

require_once __DIR__ . '/../config/Database.php';

class Model {

    // Fetch all roles along with their assigned permissions aggregated into a string
    public static function getRolesWithPermissions() {
        global $conn;
        $sql = "SELECT r.id, r.role_name, 
                       GROUP_CONCAT(p.permission_name SEPARATOR ', ') AS permissions
                FROM role_roles r
                LEFT JOIN role_role_permissions rp ON r.id = rp.role_id
                LEFT JOIN role_permissions p ON rp.permission_id = p.id
                GROUP BY r.id, r.role_name
                ORDER BY r.id DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Fetch single role by ID
    public static function getRoleById($id) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM role_roles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Fetch array of assigned permission IDs for a specific role
    public static function getRolePermissionIds($role_id) {
        global $conn;
        $stmt = $conn->prepare("SELECT permission_id FROM role_role_permissions WHERE role_id = ?");
        $stmt->execute([$role_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Fetch all permissions in the library
    public static function getAllPermissions() {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM role_permissions ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Create a new Role and assign selected permissions (Atomic Transaction)
    public static function createRole($role_name, $permissions = []) {
        global $conn;
        try {
            // Case-insensitive duplicate check
            $checkStmt = $conn->prepare("SELECT id FROM role_roles WHERE LOWER(role_name) = LOWER(?)");
            $checkStmt->execute([$role_name]);
            if ($checkStmt->fetch()) {
                return false; // Role already exists
            }

            $conn->beginTransaction();

            $stmt = $conn->prepare("INSERT INTO role_roles (role_name) VALUES (?)");
            $stmt->execute([$role_name]);
            $role_id = $conn->lastInsertId();

            if (!empty($permissions) && $role_id) {
                $stmtPerm = $conn->prepare("INSERT INTO role_role_permissions (role_id, permission_id) VALUES (?, ?)");
                foreach ($permissions as $perm_id) {
                    $stmtPerm->execute([$role_id, intval($perm_id)]);
                }
            }

            $conn->commit();
            return true;
        } catch (Exception $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            return false;
        }
    }

    // Update Role name and re-assign permissions
    public static function updateRole($id, $role_name, $permissions = []) {
        global $conn;
        try {
            // Duplicate check excluding current role
            $checkStmt = $conn->prepare("SELECT id FROM role_roles WHERE LOWER(role_name) = LOWER(?) AND id != ?");
            $checkStmt->execute([$role_name, $id]);
            if ($checkStmt->fetch()) {
                return false;
            }

            $conn->beginTransaction();

            // Update role name
            $stmt = $conn->prepare("UPDATE role_roles SET role_name = ? WHERE id = ?");
            $stmt->execute([$role_name, $id]);

            // Clear existing permission mappings
            $delStmt = $conn->prepare("DELETE FROM role_role_permissions WHERE role_id = ?");
            $delStmt->execute([$id]);

            // Insert new mappings
            if (!empty($permissions)) {
                $stmtPerm = $conn->prepare("INSERT INTO role_role_permissions (role_id, permission_id) VALUES (?, ?)");
                foreach ($permissions as $perm_id) {
                    $stmtPerm->execute([$id, intval($perm_id)]);
                }
            }

            $conn->commit();
            return true;
        } catch (Exception $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            return false;
        }
    }

    // Delete a Role and its mapping associations
    public static function deleteRole($id) {
        global $conn;
        try {
            $conn->beginTransaction();
            $delMap = $conn->prepare("DELETE FROM role_role_permissions WHERE role_id = ?");
            $delMap->execute([$id]);

            $delRole = $conn->prepare("DELETE FROM role_roles WHERE id = ?");
            $delRole->execute([$id]);
            $conn->commit();
            return true;
        } catch (Exception $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            return false;
        }
    }

    // Create a single Permission with duplicate checking
    public static function createPermission($permission_name) {
        global $conn;
        try {
            $checkStmt = $conn->prepare("SELECT id FROM role_permissions WHERE LOWER(permission_name) = LOWER(?)");
            $checkStmt->execute([$permission_name]);
            if ($checkStmt->fetch()) {
                return false;
            }

            $stmt = $conn->prepare("INSERT INTO role_permissions (permission_name) VALUES (?)");
            return $stmt->execute([$permission_name]);
        } catch (Exception $e) {
            return false;
        }
    }

    // Update Permission Name
    public static function updatePermission($id, $permission_name) {
        global $conn;
        try {
            $checkStmt = $conn->prepare("SELECT id FROM role_permissions WHERE LOWER(permission_name) = LOWER(?) AND id != ?");
            $checkStmt->execute([$permission_name, $id]);
            if ($checkStmt->fetch()) {
                return false;
            }

            $stmt = $conn->prepare("UPDATE role_permissions SET permission_name = ? WHERE id = ?");
            return $stmt->execute([$permission_name, $id]);
        } catch (Exception $e) {
            return false;
        }
    }

    // Delete Permission and its reference links
    public static function deletePermission($id) {
        global $conn;
        try {
            $conn->beginTransaction();
            $delMap = $conn->prepare("DELETE FROM role_role_permissions WHERE permission_id = ?");
            $delMap->execute([$id]);

            $delPerm = $conn->prepare("DELETE FROM role_permissions WHERE id = ?");
            $delPerm->execute([$id]);
            $conn->commit();
            return true;
        } catch (Exception $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            return false;
        }
    }
}
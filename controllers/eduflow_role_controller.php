<?php
// controllers/eduflow_role_controller.php

class Controller {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function handleRequest() {
        $action = $_GET['action'] ?? 'index';

        switch ($action) {
            case 'index':
                $query = "SELECT r.id, r.role_name, GROUP_CONCAT(p.permission_name SEPARATOR ', ') AS permissions
                          FROM role_roles r
                          LEFT JOIN role_role_permissions rp ON r.id = rp.role_id
                          LEFT JOIN role_permissions p ON rp.permission_id = p.id
                          GROUP BY r.id, r.role_name
                          ORDER BY r.id ASC";
                $stmt = $this->pdo->query($query);
                $data = $stmt->fetchAll();

                $stmt_perms = $this->pdo->query("SELECT * FROM role_permissions ORDER BY permission_name ASC");
                $all_permissions = $stmt_perms->fetchAll();

                $msg = $_GET['msg'] ?? '';
                $error = $_GET['error'] ?? '';
                require __DIR__ . '/../views/eduflow_role_view_index.php';
                break;

            case 'add_role':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $role_name = trim($_POST['role_name'] ?? '');
                    $selected_permissions = $_POST['permissions'] ?? [];

                    if (!empty($role_name)) {
                        // Check for duplicate role name
                        $stmt_check = $this->pdo->prepare("SELECT id FROM role_roles WHERE role_name = ?");
                        $stmt_check->execute([$role_name]);
                        if ($stmt_check->fetch()) {
                            header("Location: /roles?open_modal=1&role_name=" . urlencode($role_name) . "&error=" . urlencode("Role '$role_name' already exists!"));
                            exit;
                        }

                        $stmt = $this->pdo->prepare("INSERT INTO role_roles (role_name) VALUES (?)");
                        $stmt->execute([$role_name]);
                        $role_id = $this->pdo->lastInsertId();

                        if (!empty($selected_permissions)) {
                            $stmt_ins = $this->pdo->prepare("INSERT INTO role_role_permissions (role_id, permission_id) VALUES (?, ?)");
                            foreach ($selected_permissions as $perm_id) {
                                $stmt_ins->execute([$role_id, $perm_id]);
                            }
                        }

                        header("Location: /roles?msg=" . urlencode("Role created successfully!"));
                        exit;
                    } else {
                        header("Location: /roles?open_modal=1&error=" . urlencode("Role name cannot be empty."));
                        exit;
                    }
                }
                header("Location: /roles");
                exit;

            case 'quick_add_permission_create_modal':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $permission_name = trim($_POST['permission_name'] ?? '');
                    $draft_role_name = trim($_POST['draft_role_name'] ?? '');

                    if (!empty($permission_name)) {
                        // Check for duplicate permission
                        $stmt_check = $this->pdo->prepare("SELECT id FROM role_permissions WHERE permission_name = ?");
                        $stmt_check->execute([$permission_name]);
                        $existing_perm = $stmt_check->fetch();

                        if ($existing_perm) {
                            header("Location: /roles?open_modal=1&role_name=" . urlencode($draft_role_name) . "&error=" . urlencode("Permission '$permission_name' already exists in library!"));
                            exit;
                        }

                        $stmt = $this->pdo->prepare("INSERT INTO role_permissions (permission_name) VALUES (?)");
                        if ($stmt->execute([$permission_name])) {
                            $permission_id = $this->pdo->lastInsertId();
                            header("Location: /roles?open_modal=1&new_perm_id=" . $permission_id . "&role_name=" . urlencode($draft_role_name) . "&msg=" . urlencode("Permission '$permission_name' saved to library!"));
                            exit;
                        }
                    }
                    header("Location: /roles?open_modal=1&role_name=" . urlencode($draft_role_name) . "&error=" . urlencode("Failed to add permission."));
                    exit;
                }
                break;

            case 'permissions':
                $query = "SELECT p.id, p.permission_name, GROUP_CONCAT(r.role_name SEPARATOR ', ') AS assigned_roles
                          FROM role_permissions p
                          LEFT JOIN role_role_permissions rp ON p.id = rp.permission_id
                          LEFT JOIN role_roles r ON rp.role_id = r.id
                          GROUP BY p.id, p.permission_name
                          ORDER BY p.id ASC";
                $stmt = $this->pdo->query($query);
                $permissions = $stmt->fetchAll();

                $msg = $_GET['msg'] ?? '';
                $error = $_GET['error'] ?? '';
                require __DIR__ . '/../views/eduflow_role_view_permissions.php';
                break;

            case 'add_permission':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $permission_name = trim($_POST['permission_name'] ?? '');
                    if (!empty($permission_name)) {
                        $stmt_check = $this->pdo->prepare("SELECT id FROM role_permissions WHERE permission_name = ?");
                        $stmt_check->execute([$permission_name]);
                        if ($stmt_check->fetch()) {
                            header("Location: /roles?action=permissions&error=" . urlencode("Permission '$permission_name' already exists!"));
                            exit;
                        }

                        $stmt = $this->pdo->prepare("INSERT INTO role_permissions (permission_name) VALUES (?)");
                        $stmt->execute([$permission_name]);
                        header("Location: /roles?action=permissions&msg=" . urlencode("Permission created successfully!"));
                        exit;
                    } else {
                        header("Location: /roles?action=permissions&error=" . urlencode("Permission name cannot be empty."));
                        exit;
                    }
                }
                header("Location: /roles?action=permissions");
                exit;

            case 'edit_role':
                $role_id = $_GET['id'] ?? null;
                if (!$role_id) {
                    header("Location: /roles?error=" . urlencode("Invalid role ID"));
                    exit;
                }

                $stmt_role = $this->pdo->prepare("SELECT * FROM role_roles WHERE id = ?");
                $stmt_role->execute([$role_id]);
                $role = $stmt_role->fetch();

                if (!$role) {
                    header("Location: /roles?error=" . urlencode("Role not found"));
                    exit;
                }

                $stmt_all_perms = $this->pdo->query("SELECT * FROM role_permissions ORDER BY permission_name ASC");
                $all_permissions = $stmt_all_perms->fetchAll();

                $stmt_assigned = $this->pdo->prepare("SELECT permission_id FROM role_role_permissions WHERE role_id = ?");
                $stmt_assigned->execute([$role_id]);
                $assigned_permission_ids = $stmt_assigned->fetchAll(PDO::FETCH_COLUMN);

                $msg = $_GET['msg'] ?? '';
                $error = $_GET['error'] ?? '';
                require __DIR__ . '/../views/eduflow_role_view_editrole.php';
                break;

            case 'update_role':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $role_id = $_POST['role_id'] ?? null;
                    $role_name = trim($_POST['role_name'] ?? '');
                    $selected_permissions = $_POST['permissions'] ?? [];

                    if ($role_id && !empty($role_name)) {
                        $stmt = $this->pdo->prepare("UPDATE role_roles SET role_name = ? WHERE id = ?");
                        $stmt->execute([$role_name, $role_id]);

                        $stmt_del = $this->pdo->prepare("DELETE FROM role_role_permissions WHERE role_id = ?");
                        $stmt_del->execute([$role_id]);

                        if (!empty($selected_permissions)) {
                            $stmt_ins = $this->pdo->prepare("INSERT INTO role_role_permissions (role_id, permission_id) VALUES (?, ?)");
                            foreach ($selected_permissions as $perm_id) {
                                $stmt_ins->execute([$role_id, $perm_id]);
                            }
                        }

                        header("Location: /roles?msg=" . urlencode("Role updated successfully!"));
                        exit;
                    }
                }
                header("Location: /roles?error=" . urlencode("Failed to update role."));
                exit;

            case 'quick_add_permission':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $role_id = $_POST['role_id'] ?? null;
                    $permission_name = trim($_POST['permission_name'] ?? '');

                    if (!empty($permission_name) && $role_id) {
                        $stmt_check = $this->pdo->prepare("SELECT id FROM role_permissions WHERE permission_name = ?");
                        $stmt_check->execute([$permission_name]);
                        $existing_perm = $stmt_check->fetch();

                        if ($existing_perm) {
                            header("Location: /roles?action=edit_role&id=" . $role_id . "&error=" . urlencode("Permission '$permission_name' already exists in library!"));
                            exit;
                        } else {
                            $stmt = $this->pdo->prepare("INSERT INTO role_permissions (permission_name) VALUES (?)");
                            if ($stmt->execute([$permission_name])) {
                                $permission_id = $this->pdo->lastInsertId();

                                $stmt_assign = $this->pdo->prepare("INSERT IGNORE INTO role_role_permissions (role_id, permission_id) VALUES (?, ?)");
                                $stmt_assign->execute([$role_id, $permission_id]);

                                header("Location: /roles?action=edit_role&id=" . $role_id . "&msg=" . urlencode("Permission created and assigned successfully!"));
                                exit;
                            }
                        }
                    }
                    header("Location: /roles?action=edit_role&id=" . $role_id . "&error=" . urlencode("Failed to add permission."));
                    exit;
                }
                break;

            case 'update_permission':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $permission_id = $_POST['permission_id'] ?? null;
                    $permission_name = trim($_POST['permission_name'] ?? '');
                    if ($permission_id && !empty($permission_name)) {
                        $stmt = $this->pdo->prepare("UPDATE role_permissions SET permission_name = ? WHERE id = ?");
                        $stmt->execute([$permission_name, $permission_id]);
                        header("Location: /roles?action=permissions&msg=" . urlencode("Permission updated!"));
                        exit;
                    }
                }
                header("Location: /roles?action=permissions&error=" . urlencode("Failed to update permission."));
                exit;

            case 'delete_permission':
                $permission_id = $_GET['id'] ?? null;
                if ($permission_id) {
                    $stmt = $this->pdo->prepare("DELETE FROM role_permissions WHERE id = ?");
                    $stmt->execute([$permission_id]);
                    header("Location: /roles?action=permissions&msg=" . urlencode("Permission deleted!"));
                    exit;
                }
                header("Location: /roles?action=permissions&error=" . urlencode("Failed to delete permission."));
                exit;

            case 'bulk_delete_permissions':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['permission_ids'])) {
                    $ids = implode(',', array_map('intval', $_POST['permission_ids']));
                    $this->pdo->query("DELETE FROM role_permissions WHERE id IN ($ids)");
                    header("Location: /roles?action=permissions&msg=" . urlencode("Selected permissions deleted!"));
                    exit;
                }
                header("Location: /roles?action=permissions&error=" . urlencode("No permissions selected."));
                exit;

            case 'delete':
                $role_id = $_GET['id'] ?? null;
                if ($role_id) {
                    $stmt = $this->pdo->prepare("DELETE FROM role_roles WHERE id = ?");
                    $stmt->execute([$role_id]);
                    header("Location: /roles?msg=" . urlencode("Role deleted successfully!"));
                    exit;
                }
                header("Location: /roles?error=" . urlencode("Failed to delete role."));
                exit;

            case 'bulk_delete_roles':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['role_ids'])) {
                    $ids = implode(',', array_map('intval', $_POST['role_ids']));
                    $this->pdo->query("DELETE FROM role_roles WHERE id IN ($ids)");
                    header("Location: /roles?msg=" . urlencode("Selected roles deleted successfully!"));
                    exit;
                }
                header("Location: /roles?error=" . urlencode("No roles selected."));
                exit;

            default:
                header("Location: /roles");
                exit;
        }
    }
}
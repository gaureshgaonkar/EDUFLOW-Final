<?php
define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/config/Database.php';

try {
    $db = Database::getInstance();

    // 1. Ensure primary role exists (System Administrator)
    $db->exec("INSERT INTO roles (id, name) VALUES (1, 'System Administrator') ON DUPLICATE KEY UPDATE name='System Administrator'");

    // 2. Default administrator credentials
    $email = 'admin@eduflow.com';
    $password = 'password123';
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // 3. Remove conflicting password resets & update/insert admin user
    $db->prepare("DELETE FROM password_resets WHERE email = :email")->execute(['email' => $email]);

    $stmt = $db->prepare("
        INSERT INTO users (email, password, role_id, is_active) 
        VALUES (:email, :password, 1, 1)
        ON DUPLICATE KEY UPDATE password = :password, role_id = 1, is_active = 1
    ");

    $stmt->execute([
        'email' => $email,
        'password' => $hashedPassword
    ]);

    echo "<h2>Success! User seeded successfully.</h2>";
    echo "<p>Email: <strong>admin@eduflow.com</strong></p>";
    echo "<p>Password: <strong>password123</strong></p>";
    echo "<p><a href='eduflow_auth_index.php?action=login'>Click here to Login</a></p>";

} catch (Exception $e) {
    echo "<h2>Error seeding user:</h2> " . $e->getMessage();
}
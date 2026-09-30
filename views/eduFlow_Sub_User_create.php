<?php 
$pageTitle = "Create User - EduFlow";
require_once __DIR__ . '/../layouts/eduFlow_Sub_User_header.php'; 
?>

<div class="card" style="max-width: 500px; margin: 0 auto;">
    <h2>FR-004: Create New User</h2>
    <form action="/users/store" method="POST" id="createUserForm">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        
        <label>Full Name</label>
        <input type="text" name="full_name" required>

        <label>Email Address</label>
        <input type="email" name="email" required>

        <label>Initial Password</label>
        <input type="password" name="password" id="password" required minlength="6">

        <label>Phone Number</label>
        <input type="text" name="phone">

       <label>Assign Role</label>
<select name="role_id" required>
    <option value="">-- Select Role --</option>
    <?php if (!empty($roles)): ?>
        <?php foreach ($roles as $role): ?>
            <option value="<?= $role['id'] ?? $role['role_id'] ?? '' ?>">
                <?= htmlspecialchars($role['role_name'] ?? $role['name'] ?? $role['title'] ?? 'Role #' . ($role['id'] ?? '')) ?>
            </option>
        <?php endforeach; ?>
    <?php else: ?>
        <option value="" disabled>⚠️ No roles found in database</option>
    <?php endif; ?>
</select>

        <button type="submit" class="btn btn-success" style="width: 100%;">Create User Account</button>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/eduFlow_Sub_User_footer.php'; ?>
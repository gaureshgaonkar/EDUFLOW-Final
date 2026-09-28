<?php 
$pageTitle = "Edit User - EduFlow";
require_once __DIR__ . '/../layouts/eduFlow_Sub_User_header.php'; 
?>

<div class="card" style="max-width: 500px; margin: 0 auto;">
    <h2>Edit User & Reset Password</h2>
    
    <!-- Role Update Form -->
    <form action="/users/update-role" method="POST">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        <input type="hidden" name="id" value="<?= $targetUser['id'] ?>">
        
        <label>User: <strong><?= htmlspecialchars($targetUser['full_name']) ?></strong></label><br>
        <label>Assigned Role</label>
        <select name="role_id" required>
            <?php foreach ($roles as $role): ?>
                <option value="<?= $role['id'] ?>" <?= $role['id'] == $targetUser['role_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($role['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 25px;">Update Role</button>
    </form>

    <hr style="border-top: 1px solid var(--border-color); margin-bottom: 25px;">

    <!-- Password Reset Form -->
    <form action="/users/reset-password" method="POST">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        <input type="hidden" name="id" value="<?= $targetUser['id'] ?>">

        <label>New Password</label>
        <input type="password" name="new_password" required minlength="6">

        <button type="submit" class="btn btn-danger" style="width: 100%;">Reset Password</button>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/eduFlow_Sub_User_footer.php'; ?>
<?php 
$pageTitle = "My Profile - EduFlow Enterprise";
require_once __DIR__ . '/../views/eduFlow_Sub_User_header.php'; 
?>

<div style="max-width: 600px; margin: 2rem auto;">
    <div class="card">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
            <div style="width: 56px; height: 56px; background-color: var(--primary-color); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700;">
                <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
            </div>
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 700;"><?= htmlspecialchars($user['full_name']) ?></h2>
                <p style="font-size: 0.875rem; color: var(--text-secondary);"><?= htmlspecialchars($user['email']) ?></p>
            </div>
        </div>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
            <div style="background: var(--success-bg); color: var(--success-text); padding: 0.75rem 1rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-size: 0.875rem;">
                ✓ Profile details successfully updated.
            </div>
        <?php endif; ?>

        <form action="/profile/update" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" required>
            </div>

            <div class="form-group">
                <label>Email Address (Institutional Identifier)</label>
                <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled style="background-color: #f1f5f9; cursor: not-allowed;">
            </div>

            <div class="form-group">
                <label>Phone Contact Number</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="+1 (555) 000-0000">
            </div>

            <div style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="width: 100%;">Save Profile Changes</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../views/eduFlow_Sub_User_footer.php'; ?>
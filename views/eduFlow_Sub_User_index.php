<?php 
$pageTitle = "FR-004 User Management - EduFlow";
require_once __DIR__ . '/../views/eduFlow_Sub_User_header.php'; 

// Safely calculate summary metrics
$totalUsers = count($users);
$activeUsers = count(array_filter($users, fn($u) => isset($u['status']) && $u['status'] === 'active'));
$inactiveUsers = $totalUsers - $activeUsers;
?>

<div class="page-header">
    <div>
        <h1 class="page-title">FR-004: User Management</h1>
        <p class="page-subtitle">Manage institutional accounts, assign role permissions, and control access levels.</p>
    </div>
</div>

<!-- Metrics Quick Summary Bar -->
<div class="metrics-grid">
    <div class="metric-card">
        <div class="title">Total System Users</div>
        <div class="value"><?= $totalUsers ?></div>
    </div>
    <div class="metric-card">
        <div class="title">Active Accounts</div>
        <div class="value" style="color: var(--success-text);"><?= $activeUsers ?></div>
    </div>
    <div class="metric-card">
        <div class="title">Deactivated Accounts</div>
        <div class="value" style="color: var(--danger-text);"><?= $inactiveUsers ?></div>
    </div>
</div>

<!-- Quick User Creation Panel -->
<div class="card">
    <div class="card-title">Provision New User Account</div>
    <form action="/users/store" method="POST">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        <div class="form-row">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Initial Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input 
                    type="tel" 
                    id="phone" name="phone" class="form-control" placeholder="Enter 10-digit phone number" pattern="[0-9]{10}"
                    title="Please enter a valid 10-digit mobile number"
                    maxlength="10"
                    required
                >
            </div>
            <div class="form-group">
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
            </div>
        </div>
        <div style="text-align: right; margin-top: 0.5rem;">
            <button type="submit" class="btn btn-primary">➕ Create Account</button>
        </div>
    </form>
</div>

<!-- Directory Table Card -->
<div class="card" style="padding: 0;">
    <div style="padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color);">
        <div class="card-title" style="margin: 0;">System User Directory</div>
        <input type="text" id="tableSearch" class="form-control search-box" placeholder="🔍 Search users..." style="margin: 0; width: 260px;">
    </div>

   <div class="table-responsive" style="border: none; border-radius: 0;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name & Contact</th>
                    <th>Role Assignment</th>
                    <th>Account Status</th>
                    <th>Administrative Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <?php 
                        $userId = $u['id'] ?? '';
                        $fullName = $u['full_name'] ?? ($u['name'] ?? 'Unknown User');
                        $email = $u['email'] ?? 'No Email Provided';
                        $status = $u['status'] ?? 'inactive';
                        $roleId = $u['role_id'] ?? '';
                    ?>
                    <tr>
                        <td><strong>#<?= htmlspecialchars($userId) ?></strong></td>
                        <td>
                            <div style="font-weight: 600; color: var(--text-primary);"><?= htmlspecialchars($fullName) ?></div>
                            <div style="font-size: 0.775rem; color: var(--text-secondary);"><?= htmlspecialchars($email) ?></div>
                        </td>
                        <td>
                            <!-- Assign Role Form -->
                            <form action="/users/update-role" method="POST" class="form-inline">
                                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($userId) ?>">
                                <select name="role_id" onchange="this.form.submit()" style="padding: 0.35rem 0.5rem; margin: 0; font-size: 0.8rem; width: auto;">
                                    <?php foreach ($roles as $r): ?>
                                        <option value="<?= $r['id'] ?>" <?= $r['id'] == $roleId ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($r['role_name'] ?? '') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </td>
                        <td>
                            <span class="badge badge-<?= htmlspecialchars($status) ?>">
                                <?= ucfirst($status) ?>
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 6px; align-items: center;">
                                <!-- Toggle Status Form -->
                                <form action="/users/toggle-status" method="POST" class="form-inline toggle-status-form">
                                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($userId) ?>">
                                    <input type="hidden" name="status" value="<?= htmlspecialchars($status) ?>">
                                    <button type="submit" class="btn btn-sm btn-outline">
                                        <?= $status === 'active' ? '🚫 Deactivate' : '✅ Activate' ?>
                                    </button>
                                </form>

                                <!-- Inline Reset Password Form -->
                                <form action="/users/reset-password" method="POST" class="form-inline">
                                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($userId) ?>">
                                    <input type="password" name="new_password" placeholder="New Password" required style="padding: 0.35rem 0.5rem; margin: 0; font-size: 0.8rem; width: 120px;">
                                    <button type="submit" class="btn btn-sm btn-danger">Reset Pass</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../views/eduFlow_Sub_User_footer.php'; ?>
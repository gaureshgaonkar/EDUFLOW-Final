<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IAQMS - Edit Role</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        html, body { width: 100%; overflow-x: hidden; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #0f172a; }
        .main-card { background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05); width: 100%; }
        .btn-primary-custom { background-color: #2563eb; color: #ffffff; border: none; border-radius: 10px; padding: 10px 18px; font-weight: 600; text-decoration: none; text-align: center; }
        .btn-primary-custom:hover { background-color: #1d4ed8; color: #ffffff; }
        .btn-outline-custom { background-color: #ffffff; color: #334155; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 18px; font-weight: 600; text-decoration: none; text-align: center; }
        .btn-outline-custom:hover { background-color: #f1f5f9; color: #0f172a; }
        .toast-popup-container { position: fixed; top: 16px; right: 16px; left: 16px; z-index: 9999; }
        @media (min-width: 576px) { .toast-popup-container { left: auto; top: 24px; right: 24px; } }
        .toast-popup { min-width: 280px; max-width: 400px; border-radius: 12px; padding: 14px 18px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); transition: all 0.3s ease; opacity: 1; transform: translateY(0); }
        .toast-popup.toast-hide { opacity: 0; transform: translateY(-12px); }
        .toast-success { background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .toast-danger { background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .form-check-input { cursor: pointer; width: 1.15em; height: 1.15em; }
    </style>
</head>
<body class="py-3 py-md-5">

    <div class="toast-popup-container">
        <?php if (!empty($msg)): ?>
            <div class="toast-popup toast-success d-flex align-items-center justify-content-between" id="toastPopup">
                <div><span class="fw-bold">✓ Success:</span> <?php echo htmlspecialchars($msg); ?></div>
                <button type="button" class="btn-close ms-3" onclick="dismissToast()"></button>
            </div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="toast-popup toast-danger d-flex align-items-center justify-content-between" id="toastPopup">
                <div><span class="fw-bold">⚠️ Error:</span> <?php echo htmlspecialchars($error); ?></div>
                <button type="button" class="btn-close ms-3" onclick="dismissToast()"></button>
            </div>
        <?php endif; ?>
    </div>

    <div class="container" style="max-width: 640px;">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
            <div>
                <h3 class="fw-bold m-0" style="color: #0f172a;">Edit Role</h3>
                <p class="text-muted small m-0 mt-1">Update role details and permissions assignment</p>
            </div>
            <a href="eduflow_role_index.php" class="btn btn-outline-custom">← Back</a>
        </div>

        <div class="main-card p-4 p-md-5">
            <form action="eduflow_role_index.php?action=update_role" method="POST">
                <input type="hidden" name="role_id" value="<?php echo $role['id']; ?>">

                <div class="mb-4">
                    <label class="form-label fw-semibold">Role Name</label>
                    <input type="text" name="role_name" class="form-control" value="<?php echo htmlspecialchars($role['role_name']); ?>" style="border-radius: 10px; padding: 10px 14px;" required>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-semibold m-0">Permissions Assignment</label>
                        <button type="button" class="btn btn-link btn-sm text-decoration-none fw-bold p-0" style="color: #2563eb;" data-bs-toggle="modal" data-bs-target="#quickAddPermissionModal">+ Create New Permission</button>
                    </div>

                    <div class="p-3 border rounded-3 bg-light" style="max-height: 280px; overflow-y: auto;">
                        <?php if (!empty($all_permissions)): ?>
                            <?php foreach ($all_permissions as $perm): ?>
                                <?php $is_checked = in_array($perm['id'], $assigned_permission_ids) ? 'checked' : ''; ?>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="<?php echo $perm['id']; ?>" id="perm_<?php echo $perm['id']; ?>" <?php echo $is_checked; ?>>
                                    <label class="form-check-label fw-medium text-dark" for="perm_<?php echo $perm['id']; ?>">
                                        <?php echo htmlspecialchars($perm['permission_name']); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="text-muted small">No permissions found in system.</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 pt-2">
                    <a href="eduflow_role_index.php" class="btn btn-light rounded-3 fw-semibold px-4 py-2">Cancel</a>
                    <button type="submit" class="btn btn-primary-custom px-4 py-2">Save Role Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Add Permission Modal -->
    <div class="modal fade" id="quickAddPermissionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Quick Create & Assign Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-4">
                    <form action="eduflow_role_index.php?action=quick_add_permission" method="POST">
                        <input type="hidden" name="role_id" value="<?php echo $role['id']; ?>">
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Permission Name</label>
                            <input type="text" name="permission_name" class="form-control" placeholder="e.g. export_audit_reports" style="border-radius: 10px; padding: 10px 14px;" required>
                        </div>
                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                            <button type="button" class="btn btn-light rounded-3 fw-semibold px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary-custom px-4">Create & Assign</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function dismissToast() {
            const toast = document.getElementById('toastPopup');
            if (toast) {
                toast.classList.add('toast-hide');
                setTimeout(() => toast.remove(), 300);
            }
        }

        setTimeout(() => {
            dismissToast();
            if (window.history.replaceState) {
                const url = new URL(window.location.href);
                url.searchParams.delete('msg');
                url.searchParams.delete('error');
                window.history.replaceState(null, '', url.pathname + url.search);
            }
        }, 2000);
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IAQMS - Manage Permissions Library</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        html, body { width: 100%; overflow-x: hidden; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #0f172a; }
        .main-card { background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05); width: 100%; }
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .table thead { background-color: #0f172a; color: #ffffff; }
        .table th { font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; padding: 14px 20px; border: none; white-space: nowrap; }
        .table td { padding: 16px 20px; vertical-align: middle; border-color: #f1f5f9; }
        .action-col { white-space: nowrap; text-align: right; }
        .badge-role { background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; font-weight: 500; font-size: 0.8rem; padding: 5px 12px; border-radius: 20px; display: inline-block; margin: 2px 0; }
        .badge-empty { background-color: #f1f5f9; color: #64748b; font-size: 0.8rem; padding: 5px 12px; border-radius: 20px; }
        .btn-primary-custom { background-color: #2563eb; color: #ffffff; border: none; border-radius: 10px; padding: 10px 18px; font-weight: 600; text-decoration: none; text-align: center; }
        .btn-primary-custom:hover { background-color: #1d4ed8; color: #ffffff; }
        .btn-outline-custom { background-color: #ffffff; color: #334155; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 18px; font-weight: 600; text-decoration: none; text-align: center; }
        .btn-outline-custom:hover { background-color: #f1f5f9; color: #0f172a; }
        .btn-edit-soft { background-color: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; border-radius: 8px; padding: 6px 14px; font-weight: 600; font-size: 0.85rem; text-decoration: none; display: inline-block; }
        .btn-danger-soft { background-color: #fef2f2; color: #dc2626; border: 1px solid #fecaca; border-radius: 8px; padding: 6px 14px; font-weight: 600; font-size: 0.85rem; text-decoration: none; display: inline-block; }
        .search-input { border-radius: 10px; border: 1px solid #cbd5e1; padding: 10px 16px; width: 100%; }
        .form-check-input { cursor: pointer; width: 1.15em; height: 1.15em; }
        .toast-popup-container { position: fixed; top: 16px; right: 16px; left: 16px; z-index: 9999; }
        @media (min-width: 576px) { .toast-popup-container { left: auto; top: 24px; right: 24px; } }
        .toast-popup { min-width: 280px; max-width: 400px; border-radius: 12px; padding: 14px 18px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); transition: all 0.3s ease; opacity: 1; transform: translateY(0); }
        .toast-popup.toast-hide { opacity: 0; transform: translateY(-12px); }
        .toast-success { background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .toast-danger { background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
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

    <div class="container" style="max-width: 960px;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold m-0" style="color: #0f172a;">Permissions Library</h3>
                <p class="text-muted small m-0 mt-1">Manage single permission definitions and review assigned roles across IAQMS</p>
            </div>
            <div class="d-flex flex-column flex-sm-row gap-2">
                <a href="eduflow_role_index.php" class="btn btn-outline-custom">← Back to Roles Dashboard</a>
                <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addPermissionModal">+ Create New Permission</button>
            </div>
        </div>

        <form action="eduflow_role_index.php?action=bulk_delete_permissions" method="POST" id="bulkPermissionsForm">
            <div class="mb-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                <button type="submit" id="deleteSelectedBtn" class="btn btn-danger-soft text-danger fw-bold" style="display: none;" onclick="return confirm('Are you sure you want to delete all selected permissions?')">
                    🗑️ Delete Selected (<span id="selectedCount">0</span>)
                </button>
                <div class="ms-auto w-100 w-sm-auto" style="max-width: 320px;">
                    <input type="text" id="searchPermissions" class="search-input" placeholder="🔍 Search permissions...">
                </div>
            </div>

            <div class="main-card">
                <div class="table-responsive">
                    <table class="table table-hover m-0" id="permissionsTable">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="form-check-input" id="selectAllPermissions"></th>
                                <th>Permission</th>
                                <th>Assigned Roles</th>
                                <th class="action-col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($permissions)): ?>
                                <?php foreach ($permissions as $perm): ?>
                                <tr class="permission-row">
                                    <td><input type="checkbox" name="permission_ids[]" value="<?php echo $perm['id']; ?>" class="form-check-input perm-checkbox"></td>
                                    <td class="perm-name"><span class="fw-medium text-dark"><?php echo htmlspecialchars($perm['permission_name']); ?></span></td>
                                    <td class="perm-roles">
                                        <?php if (!empty($perm['assigned_roles'])): ?>
                                            <?php foreach (explode(', ', $perm['assigned_roles']) as $role): ?>
                                                <span class="badge-role me-1"><?php echo htmlspecialchars($role); ?></span>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span class="badge-empty">Unassigned</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="action-col">
                                        <button type="button" class="btn btn-edit-soft me-1" data-bs-toggle="modal" data-bs-target="#editPermissionModal<?php echo $perm['id']; ?>">Edit</button>
                                        <a href="eduflow_role_index.php?action=delete_permission&id=<?php echo $perm['id']; ?>" class="btn btn-danger-soft" onclick="return confirm('Delete permission \'<?php echo htmlspecialchars($perm['permission_name']); ?>\'?')">Delete</a>
                                    </td>
                                </tr>

                                <!-- Edit Permission Modal -->
                                <div class="modal fade" id="editPermissionModal<?php echo $perm['id']; ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                                            <div class="modal-header border-bottom-0 pb-0">
                                                <h5 class="modal-title fw-bold">Edit Permission</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body py-4">
                                                <form action="eduflow_role_index.php?action=update_permission" method="POST">
                                                    <input type="hidden" name="permission_id" value="<?php echo $perm['id']; ?>">
                                                    <div class="mb-4">
                                                        <label class="form-label fw-semibold">Permission</label>
                                                        <input type="text" name="permission_name" class="form-control" value="<?php echo htmlspecialchars($perm['permission_name']); ?>" style="border-radius: 10px; padding: 10px 14px;" required>
                                                    </div>
                                                    <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                                                        <button type="button" class="btn btn-light rounded-3 fw-semibold px-4" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary-custom px-4">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-5 text-muted">No permissions found in system.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>

    <!-- Create Permission Modal -->
    <div class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Add New Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-4">
                    <form action="eduflow_role_index.php?action=add_permission" method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Permission</label>
                            <input type="text" name="permission_name" class="form-control" placeholder="e.g. export_audit_reports" style="border-radius: 10px; padding: 10px 14px;" required>
                        </div>
                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                            <button type="button" class="btn btn-light rounded-3 fw-semibold px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary-custom px-4">Create Permission</button>
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

        const selectAll = document.getElementById('selectAllPermissions');
        const permCheckboxes = document.querySelectorAll('.perm-checkbox');
        const deleteBtn = document.getElementById('deleteSelectedBtn');
        const countSpan = document.getElementById('selectedCount');

        function updateDeleteBtn() {
            const checkedCount = document.querySelectorAll('.perm-checkbox:checked').length;
            countSpan.textContent = checkedCount;
            deleteBtn.style.display = checkedCount > 0 ? 'inline-block' : 'none';
        }

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                permCheckboxes.forEach(cb => {
                    if (cb.closest('tr').style.display !== 'none') {
                        cb.checked = this.checked;
                    }
                });
                updateDeleteBtn();
            });
        }

        permCheckboxes.forEach(cb => cb.addEventListener('change', updateDeleteBtn));

        document.getElementById('searchPermissions').addEventListener('keyup', function() {
            const query = this.value.toLowerCase().trim();
            document.querySelectorAll('.permission-row').forEach(row => {
                const name = row.querySelector('.perm-name').textContent.toLowerCase();
                const roles = row.querySelector('.perm-roles').textContent.toLowerCase();
                row.style.display = (name.includes(query) || roles.includes(query)) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
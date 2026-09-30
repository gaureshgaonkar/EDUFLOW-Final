<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IAQMS - Create Role</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #0f172a; }
        .main-card { background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 32px; box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05); }
        .btn-primary-custom { background-color: #2563eb; color: #ffffff; border: none; border-radius: 10px; padding: 10px 24px; font-weight: 600; }
        .btn-primary-custom:hover { background-color: #1d4ed8; }
        .btn-soft-primary { background-color: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; border-radius: 8px; font-weight: 600; font-size: 0.85rem; padding: 4px 12px; }
        .btn-soft-primary:hover { background-color: #dbeafe; }
        .perm-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 16px; transition: all 0.2s; }
        .perm-box:hover { border-color: #bfdbfe; background-color: #eff6ff; }
    </style>
</head>
<body class="py-5">
    <div class="container" style="max-width: 640px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold m-0">Create New Role</h3>
            <a href="eduflow_role_index.php" class="btn btn-light border rounded-3 fw-semibold">← Back</a>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="main-card">
            <form action="eduflow_role_index.php?action=add_role" method="POST">
                <div class="mb-4">
                    <label class="form-label fw-semibold">Role Name</label>
                    <input type="text" name="role_name" class="form-control" style="border-radius: 10px; padding: 12px 16px;" placeholder="e.g. System Inspector" required>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-semibold m-0">Assign Permissions</label>
                        <button type="button" class="btn btn-soft-primary" data-bs-toggle="modal" data-bs-target="#quickAddPermModal">+ Quick Add Permission</button>
                    </div>

                    <div class="row g-2" id="permissionsContainer">
                        <?php if (!empty($all_permissions)): ?>
                            <?php foreach ($all_permissions as $perm): ?>
                                <div class="col-md-6">
                                    <div class="perm-box d-flex align-items-center gap-2">
                                        <input type="checkbox" name="permissions[]" value="<?php echo $perm['id']; ?>" class="form-check-input me-2" id="perm_<?php echo $perm['id']; ?>">
                                        <label class="form-check-label fw-medium text-dark m-0" for="perm_<?php echo $perm['id']; ?>">
                                            <?php echo htmlspecialchars($perm['permission_name']); ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted small" id="noPermsMsg">No permissions created yet.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="eduflow_role_index.php" class="btn btn-light border rounded-3 px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary-custom">Save Role</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Add Permission Modal -->
    <div class="modal fade" id="quickAddPermModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Quick Add Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" id="closeQuickModalBtn"></button>
                </div>
                <div class="modal-body py-4">
                    <div id="quickPermAlert" class="alert alert-danger d-none py-2 px-3 small"></div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Permission Name</label>
                        <input type="text" id="quick_perm_name" class="form-control" style="border-radius: 10px; padding: 10px 14px;" placeholder="e.g. Export Audit Logs">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="saveQuickPermBtn" class="btn btn-primary-custom">Add & Select</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('saveQuickPermBtn').addEventListener('click', function() {
            const permNameInput = document.getElementById('quick_perm_name');
            const alertBox = document.getElementById('quickPermAlert');
            const permName = permNameInput.value.trim();

            if (!permName) {
                alertBox.textContent = 'Please enter a permission name.';
                alertBox.classList.remove('d-none');
                return;
            }

            // 1. Gather all currently checked permission IDs before updating
            const currentlyCheckedIds = Array.from(
                document.querySelectorAll('#permissionsContainer input[name="permissions[]"]:checked')
            ).map(cb => cb.value);

            const formData = new FormData();
            formData.append('permission_name', permName);

            fetch('eduflow_role_index.php?action=quick_add_permission', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alertBox.classList.add('d-none');
                    permNameInput.value = '';
                    
                    // Hide Modal
                    const modalEl = document.getElementById('quickAddPermModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    modal.hide();

                    // Dynamically refresh permissions checkboxes
                    const container = document.getElementById('permissionsContainer');
                    container.innerHTML = ''; 

                    data.permissions.forEach(perm => {
                        const isNewlyAdded = perm.permission_name.toLowerCase() === permName.toLowerCase();
                        // 2. Keep checked if it was previously checked OR if it's the brand-new permission
                        const shouldBeChecked = isNewlyAdded || currentlyCheckedIds.includes(String(perm.id));
                        
                        const col = document.createElement('div');
                        col.className = 'col-md-6';
                        col.innerHTML = `
                            <div class="perm-box d-flex align-items-center gap-2">
                                <input type="checkbox" name="permissions[]" value="${perm.id}" class="form-check-input me-2" id="perm_${perm.id}" ${shouldBeChecked ? 'checked' : ''}>
                                <label class="form-check-label fw-medium text-dark m-0" for="perm_${perm.id}">
                                    ${perm.permission_name}
                                </label>
                            </div>
                        `;
                        container.appendChild(col);
                    });
                } else {
                    alertBox.textContent = data.message || 'Error saving permission.';
                    alertBox.classList.remove('d-none');
                }
            })
            .catch(err => {
                alertBox.textContent = 'Server connection failed.';
                alertBox.classList.remove('d-none');
            });
        });
    </script>
</body>
</html>+++++++++
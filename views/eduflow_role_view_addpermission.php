<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IAQMS - Create Permission</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #0f172a; }
        .main-card { background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 32px; box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05); }
        .btn-primary-custom { background-color: #2563eb; color: #ffffff; border: none; border-radius: 10px; padding: 10px 24px; font-weight: 600; }
        .btn-primary-custom:hover { background-color: #1d4ed8; }
    </style>
</head>
<body class="py-5">
    <div class="container" style="max-width: 540px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold m-0">Create Permission</h3>
            <a href="eduflow_role_index.php?action=permissions" class="btn btn-light border rounded-3 fw-semibold">← Back</a>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="main-card">
            <form action="eduflow_role_index.php?action=add_permission" method="POST">
                <div class="mb-4">
                    <label class="form-label fw-semibold">Permission Name</label>
                    <input type="text" name="permission_name" class="form-control" style="border-radius: 10px; padding: 12px 16px;" placeholder="e.g. Delete Sensor Logs" required>
                    <div class="form-text text-muted mt-2">Create a clear key name for this permission action.</div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="eduflow_role_index.php?action=permissions" class="btn btn-light border rounded-3 px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary-custom">Save Permission</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
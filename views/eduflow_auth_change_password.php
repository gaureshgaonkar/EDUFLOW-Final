<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduFlow - Change Password</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f4f6f9; margin: 0; }
        .card { background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 350px; }
        .card h3 { margin-top: 0; color: #333; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-size: 14px; color: #555; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { width: 100%; padding: 10px; background: #0056b3; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn:hover { background: #004085; }
        .error { color: #d9534f; font-size: 14px; margin-bottom: 15px; }
        .success { color: #5cb85c; font-size: 14px; margin-bottom: 15px; }
        .back-link { display: block; margin-top: 15px; font-size: 14px; text-align: center; color: #0056b3; text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <h3>Change Password</h3>

        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form action="eduflow_auth_index.php?action=change_password" method="POST">
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>

            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" minlength="8" required>
            </div>

            <button type="submit" class="btn">Update Password</button>
        </form>

        <a href="eduflow_auth_index.php?action=dashboard" class="back-link">&larr; Back to Dashboard</a>
    </div>
</body>
</html>
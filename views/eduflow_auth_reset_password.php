<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IAQMS - Set New Password</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        body { background-color: #ffffff; color: #f0f6fc; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; position: relative; }
        .card { background-color: #0d1117; border: 1px solid #30363d; border-radius: 12px; padding: 36px; width: 100%; max-width: 400px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); }
        .card h1 { font-size: 22px; font-weight: 700; color: #f0f6fc; margin-bottom: 20px; }
        .form-group { margin-bottom: 18px; text-align: left; }
        .form-group label { display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; color: #c9d1d9; }
        .form-group input { width: 100%; padding: 10px 12px; background-color: #161b22; border: 1px solid #30363d; border-radius: 6px; font-size: 14px; color: #f0f6fc; outline: none; }
        .form-group input:focus { border-color: #2ea043; box-shadow: 0 0 0 3px rgba(46, 160, 67, 0.3); }
        .btn-submit { width: 100%; padding: 11px; background-color: #238636; color: #ffffff; border: none; border-radius: 6px; font-size: 15px; font-weight: 600; cursor: pointer; margin-top: 8px; }
        .btn-submit:hover { background-color: #2ea043; }
        .alert-error { background-color: rgba(248, 81, 73, 0.1); border: 1px solid rgba(248, 81, 73, 0.4); color: #f85149; padding: 12px; border-radius: 6px; font-size: 14px; margin-bottom: 18px; }
    </style>
</head>
<body>

    <div class="card">
        <h1>Set New Password</h1>

        <?php if (!empty($error)): ?>
            <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="eduflow_auth_index.php?action=reset_password" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? $_POST['token'] ?? ''); ?>">

            <div class="form-group">
                <label for="password">New Password*</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password*</label>
                <input type="password" id="confirm_password" name="confirm_password" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn-submit">Update Password</button>
        </form>
    </div>

</body>
</html>
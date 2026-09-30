<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IAQMS - Reset Password</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        body { background-color: #ffffff; color: #f0f6fc; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; position: relative; }
        .top-nav { position: absolute; top: 24px; right: 32px; display: flex; align-items: center; gap: 16px; }
        .top-nav a { font-size: 14px; font-weight: 600; text-decoration: none; border-radius: 6px; padding: 8px 16px; }
        .btn-home { color: #24292f; border: 1px solid #d0d7de; }
        .btn-home:hover { background-color: #f6f8fa; }
        .card { background-color: #0d1117; border: 1px solid #30363d; border-radius: 12px; padding: 36px; width: 100%; max-width: 400px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); }
        .card h1 { font-size: 22px; font-weight: 700; color: #f0f6fc; margin-bottom: 12px; }
        .card p { font-size: 14px; color: #8b949e; margin-bottom: 24px; line-height: 1.4; }
        .form-group { margin-bottom: 18px; text-align: left; }
        .form-group label { display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; color: #c9d1d9; }
        .form-group input { width: 100%; padding: 10px 12px; background-color: #161b22; border: 1px solid #30363d; border-radius: 6px; font-size: 14px; color: #f0f6fc; outline: none; }
        .form-group input:focus { border-color: #58a6ff; box-shadow: 0 0 0 3px rgba(56, 139, 253, 0.3); }
        .btn-submit { width: 100%; padding: 11px; background-color: #1f6feb; color: #ffffff; border: none; border-radius: 6px; font-size: 15px; font-weight: 600; cursor: pointer; }
        .btn-submit:hover { background-color: #388bfd; }
        .alert-error { background-color: rgba(248, 81, 73, 0.1); border: 1px solid rgba(248, 81, 73, 0.4); color: #f85149; padding: 12px; border-radius: 6px; font-size: 14px; margin-bottom: 18px; }
        .alert-success { background-color: rgba(46, 160, 67, 0.1); border: 1px solid rgba(46, 160, 67, 0.4); color: #3fb950; padding: 12px; border-radius: 6px; font-size: 14px; margin-bottom: 18px; word-break: break-all; }
        .back-link { display: block; text-align: left; margin-top: 16px; font-size: 14px; color: #58a6ff; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <div class="top-nav">
        <a href="eduflow_auth_index.php?action=home" class="btn-home">&larr; Home</a>
    </div>

    <div class="card">
        <h1>Forgot Password</h1>
        <p>Enter your institutional email address to generate a secure password reset link.</p>

        <?php if (!empty($error)): ?>
            <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="eduflow_auth_index.php?action=request_password_reset" method="POST">
            <div class="form-group">
                <label for="email">Institutional Email</label>
                <input type="email" id="email" name="email" required placeholder="user@institution.edu" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>

            <button type="submit" class="btn-submit">Send Reset Link</button>
        </form>

        <a href="eduflow_auth_index.php?action=login" class="back-link">&larr; Back to Sign in</a>
    </div>

</body>
</html>
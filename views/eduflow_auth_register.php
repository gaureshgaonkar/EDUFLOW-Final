<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IAQMS - Sign Up</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        
        /* White background for outer page */
        body { background-color: #ffffff; color: #f0f6fc; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; position: relative; }

        /* Top Navigation Header */
        .top-nav { position: absolute; top: 24px; right: 32px; display: flex; align-items: center; gap: 16px; }
        .top-nav a { font-size: 14px; font-weight: 600; text-decoration: none; border-radius: 6px; padding: 8px 16px; transition: 0.2s ease; }
        .btn-home { color: #24292f; border: 1px solid #d0d7de; }
        .btn-home:hover { background-color: #f6f8fa; }
        .signin-text { font-size: 14px; color: #57606a; }
        .signin-text a { color: #0969da; text-decoration: none; font-weight: 600; padding: 0; }

        /* Black Inner Container Box */
        .card { background-color: #0d1117; border: 1px solid #30363d; border-radius: 12px; padding: 36px; width: 100%; max-width: 420px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); }
        .card h1 { font-size: 24px; font-weight: 700; color: #f0f6fc; margin-bottom: 24px; text-align: left; }

        /* Form Inputs */
        .form-group { margin-bottom: 18px; text-align: left; }
        .form-group label { display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; color: #c9d1d9; }
        .form-group input { width: 100%; padding: 10px 12px; background-color: #161b22; border: 1px solid #30363d; border-radius: 6px; font-size: 14px; color: #f0f6fc; outline: none; }
        .form-group input:focus { border-color: #2ea043; box-shadow: 0 0 0 3px rgba(46, 160, 67, 0.3); }
        .field-desc { font-size: 12px; color: #8b949e; margin-top: 4px; }

        /* Actions */
        .btn-submit { width: 100%; padding: 11px; background-color: #238636; color: #ffffff; border: none; border-radius: 6px; font-size: 15px; font-weight: 600; cursor: pointer; margin-top: 10px; }
        .btn-submit:hover { background-color: #2ea043; }

        .alert-error { background-color: rgba(248, 81, 73, 0.1); border: 1px solid rgba(248, 81, 73, 0.4); color: #f85149; padding: 12px; border-radius: 6px; font-size: 14px; margin-bottom: 18px; }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <div class="top-nav">
        <a href="eduflow_auth_index.php?action=home" class="btn-home">&larr; Home</a>
        <span class="signin-text">Already have an account? <a href="eduflow_auth_index.php?action=login">Sign in &rarr;</a></span>
    </div>

    <!-- Black Form Card Box -->
    <div class="card">
        <h1>Sign up for IAQMS</h1>

        <?php if (!empty($error)): ?>
            <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="eduflow_auth_index.php?action=register" method="POST">
            <div class="form-group">
                <label for="email">Institutional Email*</label>
                <input type="email" id="email" name="email" required placeholder="user@institution.edu" value="<?php echo htmlspecialchars($_GET['email'] ?? $_POST['email'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="username">Username*</label>
                <input type="text" id="username" name="username" required placeholder="e.g. john_doe" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                <p class="field-desc">Usernames are unique across the system.</p>
            </div>

            <div class="form-group">
                <label for="password">Password*</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
                <p class="field-desc">Password must be at least 8 characters long.</p>
            </div>

            <button type="submit" class="btn-submit">Create Account</button>
        </form>
    </div>

</body>
</html>
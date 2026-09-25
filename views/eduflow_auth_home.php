<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IAQMS - Accreditation Management System</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        body { background-color: #0d1117; color: #c9d1d9; min-height: 100vh; display: flex; flex-direction: column; }
        
        /* Navigation Bar */
        header { display: flex; justify-content: space-between; align-items: center; padding: 20px 40px; background-color: #161b22; border-bottom: 1px solid #30363d; }
        .logo { font-size: 20px; font-weight: 700; color: #58a6ff; text-decoration: none; letter-spacing: 0.5px; }
        .nav-links { display: flex; gap: 12px; }
        .btn-signin { color: #c9d1d9; border: 1px solid #30363d; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 500; }
        .btn-signin:hover { background-color: #21262d; }
        .btn-signup { background-color: #238636; color: #ffffff; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 600; }
        .btn-signup:hover { background-color: #2ea043; }

        /* Hero Container */
        .hero { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 40px 20px; }
        .hero h1 { font-size: 48px; font-weight: 800; color: #f0f6fc; margin-bottom: 20px; max-width: 850px; line-height: 1.25; }
        .hero p { font-size: 18px; color: #8b949e; margin-bottom: 36px; max-width: 650px; line-height: 1.5; }

        /* Get Started Bar */
        .cta-box { display: flex; background-color: #0d1117; border: 1px solid #30363d; border-radius: 8px; padding: 6px; width: 100%; max-width: 480px; }
        .cta-box input { flex: 1; background: transparent; border: none; padding: 10px 14px; color: #f0f6fc; outline: none; font-size: 15px; }
        .cta-box button { background-color: #238636; color: #ffffff; border: none; padding: 10px 20px; border-radius: 6px; font-weight: 600; font-size: 15px; cursor: pointer; }
        .cta-box button:hover { background-color: #2ea043; }
    </style>
</head>
<body>

    <header>
        <a href="eduflow_auth_index.php?action=home" class="logo">IAQMS Enterprise</a>
        <div class="nav-links">
            <a href="eduflow_auth_index.php?action=login" class="btn-signin">Sign in</a>
            <a href="eduflow_auth_index.php?action=register" class="btn-signup">Sign up</a>
        </div>
    </header>

    <section class="hero">
        <h1>Institutional Accreditation & Quality Assurance System</h1>
        <p>Streamline compliance workflows, centralize audit documentation, and simplify institutional quality management on a unified platform.</p>
        
        <form action="eduflow_auth_index.php" method="GET" class="cta-box">
            <input type="hidden" name="action" value="register">
            <input type="email" name="email" placeholder="Enter your institutional email..." required>
            <button type="submit">Sign up for IAQMS</button>
        </form>
    </section>

</body>
</html>
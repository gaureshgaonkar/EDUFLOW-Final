<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EduFlow Enterprise</title>
    <link rel="stylesheet" href="/eduFlow_Sub_User_style.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div style="text-align: center; margin-bottom: 2rem;">
                <h1 style="font-size: 1.5rem; font-weight: 700; color: #0f172a;">EduFlow <span style="color: #2563eb;">EEP</span></h1>
                <p style="font-size: 0.875rem; color: #64748b; margin-top: 0.25rem;">Enterprise Accreditation Management</p>
            </div>

            <form action="/login" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="user@institution.edu" required autofocus>
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Sign In</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Protection: redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

// Current route checking for active menu state
$currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$currentAction = $_GET['action'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - IAQMS</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        body { background-color: #ffffff; color: #c9d1d9; min-height: 100vh; display: flex; flex-direction: column; }

        /* Top Header */
        header { display: flex; align-items: center; justify-content: space-between; padding: 12px 24px; background-color: #010409; border-bottom: 1px solid #21262d; }
        .header-left { display: flex; align-items: center; gap: 16px; }
        .menu-btn { background: transparent; border: 1px solid #30363d; border-radius: 6px; color: #c9d1d9; padding: 6px 10px; cursor: pointer; font-size: 16px; }
        .menu-btn:hover { background-color: #21262d; }
        .brand-title { font-size: 16px; font-weight: 600; color: #f0f6fc; }

        .search-box { background-color: #0d1117; border: 1px solid #30363d; border-radius: 6px; padding: 6px 12px; color: #8b949e; font-size: 14px; width: 240px; }
        .user-nav { display: flex; align-items: center; gap: 12px; font-size: 14px; }
        .btn-logout { color: #f85149; text-decoration: none; border: 1px solid #30363d; padding: 6px 12px; border-radius: 6px; font-size: 13px; }
        .btn-logout:hover { background-color: rgba(248, 81, 73, 0.1); }

        /* Main Container */
        .container { display: flex; flex: 1; position: relative; }

        /* Side Navigation Drawer */
        .sidebar { width: 260px; background-color: #010409; border-right: 1px solid #21262d; padding: 20px 12px; display: flex; flex-direction: column; gap: 4px; }
        .sidebar-item { display: flex; align-items: center; gap: 10px; padding: 8px 12px; color: #c9d1d9; text-decoration: none; font-size: 14px; border-radius: 6px; }
        .sidebar-item:hover, .sidebar-item.active { background-color: #161b22; color: #f0f6fc; }
        .sidebar-section-title { font-size: 12px; font-weight: 600; color: #8b949e; margin: 16px 12px 8px 12px; text-transform: uppercase; }

        /* Main Content Area */
        .main-content { flex: 1; padding: 32px; max-width: 1000px; }
        .content-header { font-size: 24px; font-weight: 600; color: black; margin-bottom: 24px; }

        /* GitHub Copilot Style Prompt Box */
        .prompt-card { background-color: #161b22; border: 1px solid #30363d; border-radius: 8px; padding: 20px; margin-bottom: 24px; }
        .prompt-input { font-size: 15px; color: #ffffff; margin-bottom: 16px; }
        .prompt-actions { display: flex; gap: 8px; }
        .btn-pill { background-color: #21262d; border: 1px solid #30363d; color: #c9d1d9; padding: 6px 12px; border-radius: 20px; font-size: 13px; cursor: pointer; }

        /* Welcome Card */
        .welcome-card { background-color: #161b22; border: 1px solid #30363d; border-radius: 8px; padding: 20px; }
        .badge { background-color: #238636; color: white; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 12px; text-transform: uppercase; }
        .welcome-title { font-size: 18px; color: #f0f6fc; margin: 10px 0 6px 0; }
        .welcome-desc { font-size: 14px; color: #8b949e; }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <div class="header-left">
            <button class="menu-btn" onclick="toggleSidebar()">☰</button>
            <span class="brand-title">IAQMS Dashboard</span>
        </div>
        <div class="search-box">Type / to search</div>
        <div class="user-nav">
            <span>Welcome, <strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? $_SESSION['username'] ?? 'User'); ?></strong></span>
            <a href="/logout" class="btn-logout">Sign Out</a>
        </div>
    </header>

    <!-- Content Layout -->
    <div class="container">
        
        <!-- Collapsible Menu Drawer -->
        <aside class="sidebar" id="sidebarNav">
            <a href="/" class="sidebar-item <?php echo ($currentUri === '/' && $currentAction !== 'dashboard') ? 'active' : ''; ?>">🏠 Home</a>
            <a href="/dashboard" class="sidebar-item <?php echo ($currentUri === '/dashboard' || $currentAction === 'dashboard') ? 'active' : ''; ?>">📊 Dashboard</a>
            
            <div class="sidebar-section-title">Administration</div>
<<<<<<< HEAD
<a href="/users" class="sidebar-item <?php echo ($currentUri === '/users') ? 'active' : ''; ?>">👤 User Role</a>
<a href="/roles" class="sidebar-item <?php echo (strpos(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/roles') === 0) ? 'active' : ''; ?>">⚙️ Role Management</a>
=======
            <a href="/users" class="sidebar-item <?php echo ($currentUri === '/users') ? 'active' : ''; ?>">👤 User Role</a>
            <a href="/users" class="sidebar-item">⚙️ Role Management</a>
>>>>>>> d8e7900ae2754a76fdc493d2c0a3a30ee9591a04
        </aside>

        <!-- Main Content Panel -->
        <main class="main-content">
            <h1 class="content-header">Dashboard</h1>

            <div class="prompt-card">
                <div class="prompt-input">Ask anything or select accreditation context...</div>
                <div class="prompt-actions">
                    <button class="btn-pill">🔍 Audit Status</button>
                    <button class="btn-pill">📄 Compliance Documents</button>
                    <button class="btn-pill">+</button>
                </div>
            </div>

            <div class="welcome-card">
                <span class="badge">IAQMS Active</span>
                <h2 class="welcome-title">Quality Assurance Portal</h2>
                <p class="welcome-desc">System configured and ready for institutional compliance workflow evaluation.</p>
            </div>
        </main>

    </div>

    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById('sidebarNav');
            if (sidebar.style.display === 'none') {
                sidebar.style.display = 'flex';
            } else {
                sidebar.style.display = 'none';
            }
        }
    </script>
</body>
</html>
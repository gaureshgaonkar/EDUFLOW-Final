<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'IAQMS Dashboard' ?></title>
    
    <!-- Dark Mode Structural Styles matching File 2 -->
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        body { background-color: #f6f8fa; color: #24292f; min-height: 100vh; display: flex; flex-direction: column; }

        /* File 2 Top Header Styling */
        header { display: flex; align-items: center; justify-content: space-between; padding: 12px 24px; background-color: #010409; border-bottom: 1px solid #21262d; }
        .header-left { display: flex; align-items: center; gap: 16px; }
        .menu-btn { background: transparent; border: 1px solid #30363d; border-radius: 6px; color: #c9d1d9; padding: 6px 10px; cursor: pointer; font-size: 16px; }
        .menu-btn:hover { background-color: #21262d; }
        .brand-title { font-size: 16px; font-weight: 600; color: #f0f6fc; }

        .search-box { background-color: #0d1117; border: 1px solid #30363d; border-radius: 6px; padding: 6px 12px; color: #8b949e; font-size: 14px; width: 240px; }
        .user-nav { display: flex; align-items: center; gap: 12px; font-size: 14px; color: #c9d1d9; }
        .btn-logout { color: #f85149; text-decoration: none; border: 1px solid #30363d; padding: 6px 12px; border-radius: 6px; font-size: 13px; }
        .btn-logout:hover { background-color: rgba(248, 81, 73, 0.1); }

        /* Layout Container */
        .app-container { display: flex; flex: 1; position: relative; }

        /* File 2 Sidebar Styling */
        .sidebar { width: 260px; background-color: #010409; border-right: 1px solid #21262d; padding: 20px 12px; display: flex; flex-direction: column; gap: 4px; min-height: calc(100vh - 57px); }
        .sidebar-item { display: flex; align-items: center; gap: 10px; padding: 8px 12px; color: #c9d1d9; text-decoration: none; font-size: 14px; border-radius: 6px; }
        .sidebar-item:hover, .sidebar-item.active { background-color: #161b22; color: #f0f6fc; }
        .sidebar-section-title { font-size: 12px; font-weight: 600; color: #8b949e; margin: 16px 12px 8px 12px; text-transform: uppercase; }

        /* File 1 Content Wrapper */
        .main-content { flex: 1; padding: 32px; background-color: #f6f8fa; }
    </style>
    
    <!-- Keep original stylesheet link for File 1 components -->
    <link rel="stylesheet" href="/eduFlow_Sub_User_style.css">
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

    <!-- Main Wrapper -->
    <div class="app-container">
        
        <!-- Side Navigation Drawer -->
<aside class="sidebar" id="sidebarNav">
    <a href="/" class="sidebar-item <?php echo (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === '/') ? 'active' : ''; ?>">🏠 Home</a>
    
    <!-- File 2 Dashboard Link -->
    <a href="/dashboard" class="sidebar-item <?php echo (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === '/dashboard') ? 'active' : ''; ?>">📊 Dashboard</a>
    
    <div class="sidebar-section-title">Administration</div>
    
    <div class="sidebar-section-title">Administration</div>
<a href="/users" class="sidebar-item <?php echo ($currentUri === '/users') ? 'active' : ''; ?>">👤 User Role</a>
<a href="/roles" class="sidebar-item <?php echo (strpos(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/roles') === 0) ? 'active' : ''; ?>">⚙️ Role Management</a>
</aside>

        <!-- Main Workspace -->
        <main class="main-content">
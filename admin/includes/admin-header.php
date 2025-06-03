<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Dori</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <div class="admin-sidebar">
            <div class="sidebar-header">
                <img src="../assets/images/logo.svg" alt="Dori Logo" class="logo">
                <h2>Admin Panel</h2>
            </div>
            
            <nav class="sidebar-nav">
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="pages.php" class="nav-link">
                            <i class="fas fa-file-alt"></i>
                            <span>Pages</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="sections.php" class="nav-link">
                            <i class="fas fa-puzzle-piece"></i>
                            <span>Sections</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="case-studies.php" class="nav-link">
                            <i class="fas fa-briefcase"></i>
                            <span>Case Studies</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="statistics.php" class="nav-link">
                            <i class="fas fa-chart-bar"></i>
                            <span>Statistics</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="uploads.php" class="nav-link">
                            <i class="fas fa-upload"></i>
                            <span>Media</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="users.php" class="nav-link">
                            <i class="fas fa-users"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="settings.php" class="nav-link">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        
        <div class="admin-main">
            <div class="main-header">
                <button class="sidebar-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <div class="user-menu">
                    <span class="username"><?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?></span>
                    <div class="dropdown">
                        <button class="dropdown-toggle">
                            <i class="fas fa-user-circle"></i>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="profile.php" class="dropdown-item">
                                <i class="fas fa-user"></i>
                                <span>Profile</span>
                            </a>
                            <a href="logout.php" class="dropdown-item">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="main-content">
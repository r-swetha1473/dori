<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/admin-functions.php';

// Authentication check
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Get statistics
$totalPages = getTotalCount('pages');
$totalSections = getTotalCount('sections');
$totalImages = getTotalCount('uploads');
$totalUsers = getTotalCount('users');

// Get recent activity
$recentActivity = getRecentActivity(5);

require_once 'includes/admin-header.php';
?>

<div class="admin-dashboard">
    <div class="dashboard-header">
        <h1>Dashboard</h1>
        <p>Welcome back, <?= htmlspecialchars($_SESSION['admin_username']) ?>!</p>
    </div>
    
    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-file"></i>
            </div>
            <div class="stat-content">
                <h3><?= $totalPages ?></h3>
                <p>Pages</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-puzzle-piece"></i>
            </div>
            <div class="stat-content">
                <h3><?= $totalSections ?></h3>
                <p>Sections</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-image"></i>
            </div>
            <div class="stat-content">
                <h3><?= $totalImages ?></h3>
                <p>Images</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3><?= $totalUsers ?></h3>
                <p>Users</p>
            </div>
        </div>
    </div>
    
    <div class="dashboard-content">
        <div class="content-section">
            <div class="section-header">
                <h2>Recent Activity</h2>
                <a href="activity-log.php" class="btn btn-text">View All</a>
            </div>
            
            <div class="activity-list">
                <?php if (empty($recentActivity)): ?>
                <p>No recent activity found.</p>
                <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>User</th>
                            <th>Item</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentActivity as $activity): ?>
                        <tr>
                            <td><?= htmlspecialchars($activity['action']) ?></td>
                            <td><?= htmlspecialchars($activity['username']) ?></td>
                            <td><?= htmlspecialchars($activity['item_type']) ?>: <?= htmlspecialchars($activity['item_name']) ?></td>
                            <td><?= formatDate($activity['created_at']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="content-section">
            <div class="section-header">
                <h2>Quick Actions</h2>
            </div>
            
            <div class="quick-actions">
                <a href="pages.php" class="quick-action">
                    <i class="fas fa-file-alt"></i>
                    <span>Manage Pages</span>
                </a>
                
                <a href="sections.php" class="quick-action">
                    <i class="fas fa-puzzle-piece"></i>
                    <span>Manage Sections</span>
                </a>
                
                <a href="uploads.php" class="quick-action">
                    <i class="fas fa-upload"></i>
                    <span>Upload Media</span>
                </a>
                
                <a href="case-studies.php" class="quick-action">
                    <i class="fas fa-briefcase"></i>
                    <span>Case Studies</span>
                </a>
                
                <a href="statistics.php" class="quick-action">
                    <i class="fas fa-chart-bar"></i>
                    <span>Statistics</span>
                </a>
                
                <a href="users.php" class="quick-action">
                    <i class="fas fa-users-cog"></i>
                    <span>Users</span>
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/admin-footer.php'; ?>
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

// Handle page deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $pageId = (int)$_GET['delete'];
    if (deletePage($pageId)) {
        $_SESSION['success_message'] = "Page deleted successfully";
    } else {
        $_SESSION['error_message'] = "Failed to delete page";
    }
    header('Location: pages.php');
    exit;
}

// Get all pages
$pages = getAllPages();

require_once 'includes/admin-header.php';
?>

<div class="admin-content">
    <div class="content-header">
        <h1>Manage Pages</h1>
        <a href="page-edit.php" class="btn btn-primary">Add New Page</a>
    </div>
    
    <?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success_message'] ?>
        <?php unset($_SESSION['success_message']); ?>
    </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger">
        <?= $_SESSION['error_message'] ?>
        <?php unset($_SESSION['error_message']); ?>
    </div>
    <?php endif; ?>
    
    <div class="content-body">
        <?php if (empty($pages)): ?>
        <p>No pages found. Create your first page to get started.</p>
        <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $page): ?>
                <tr>
                    <td><?= $page['id'] ?></td>
                    <td><?= htmlspecialchars($page['title']) ?></td>
                    <td><?= htmlspecialchars($page['slug']) ?></td>
                    <td><?= formatDate($page['updated_at']) ?></td>
                    <td class="actions">
                        <a href="page-edit.php?id=<?= $page['id'] ?>" class="btn btn-sm btn-edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="sections.php?page_id=<?= $page['id'] ?>" class="btn btn-sm btn-sections" title="Manage Sections">
                            <i class="fas fa-puzzle-piece"></i>
                        </a>
                        <a href="javascript:void(0);" onclick="confirmDelete(<?= $page['id'] ?>)" class="btn btn-sm btn-delete" title="Delete">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this page? This action cannot be undone.')) {
        window.location.href = 'pages.php?delete=' + id;
    }
}
</script>

<?php require_once 'includes/admin-footer.php'; ?>
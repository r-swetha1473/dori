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

// Handle banner deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $bannerId = (int)$_GET['delete'];
    if (deleteBanner($bannerId)) {
        $_SESSION['success_message'] = "Banner deleted successfully";
    } else {
        $_SESSION['error_message'] = "Failed to delete banner";
    }
    header('Location: banners.php');
    exit;
}

// Get all banners
$banners = getAllBanners();

require_once 'includes/admin-header.php';
?>

<div class="admin-content">
    <div class="content-header">
        <h1>Manage Banners</h1>
        <a href="banner-edit.php" class="btn btn-primary">Add New Banner</a>
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
        <?php if (empty($banners)): ?>
        <p>No banners found. Create your first banner to get started.</p>
        <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($banners as $banner): ?>
                <tr>
                    <td><?= $banner['id'] ?></td>
                    <td>
                        <img src="<?= htmlspecialchars($banner['image_path']) ?>" alt="Banner preview" style="max-width: 100px;">
                    </td>
                    <td><?= htmlspecialchars($banner['title']) ?></td>
                    <td>
                        <span class="badge <?= $banner['is_active'] ? 'badge-success' : 'badge-secondary' ?>">
                            <?= $banner['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </td>
                    <td class="actions">
                        <a href="banner-edit.php?id=<?= $banner['id'] ?>" class="btn btn-sm btn-edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="javascript:void(0);" onclick="confirmDelete(<?= $banner['id'] ?>)" class="btn btn-sm btn-delete" title="Delete">
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
    if (confirm('Are you sure you want to delete this banner? This action cannot be undone.')) {
        window.location.href = 'banners.php?delete=' + id;
    }
}
</script>

<?php require_once 'includes/admin-footer.php'; ?>
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

// Get page ID if provided
$pageId = isset($_GET['page_id']) ? (int)$_GET['page_id'] : 0;
$page = null;

if ($pageId > 0) {
    $page = getPageById($pageId);
    if (!$page) {
        $_SESSION['error_message'] = "Page not found";
        header('Location: pages.php');
        exit;
    }
}

// Handle section deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $sectionId = (int)$_GET['delete'];
    if (deleteSection($sectionId)) {
        $_SESSION['success_message'] = "Section deleted successfully";
    } else {
        $_SESSION['error_message'] = "Failed to delete section";
    }
    
    $redirectUrl = $pageId > 0 ? "sections.php?page_id={$pageId}" : "sections.php";
    header("Location: {$redirectUrl}");
    exit;
}

// Get sections based on page ID or all sections
$sections = $pageId > 0 ? getSectionsByPageId($pageId) : getAllSections();

require_once 'includes/admin-header.php';
?>

<div class="admin-content">
    <div class="content-header">
        <h1>
            <?php if ($pageId > 0): ?>
            Manage Sections for "<?= htmlspecialchars($page['title']) ?>"
            <?php else: ?>
            Manage All Sections
            <?php endif; ?>
        </h1>
        <div class="header-actions">
            <?php if ($pageId > 0): ?>
            <a href="section-edit.php?page_id=<?= $pageId ?>" class="btn btn-primary">Add New Section</a>
            <a href="pages.php" class="btn btn-secondary">Back to Pages</a>
            <?php else: ?>
            <a href="section-edit.php" class="btn btn-primary">Add New Section</a>
            <?php endif; ?>
        </div>
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
        <?php if (empty($sections)): ?>
        <p>
            <?php if ($pageId > 0): ?>
            No sections found for this page. Create your first section to get started.
            <?php else: ?>
            No sections found. Create your first section to get started.
            <?php endif; ?>
        </p>
        <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <?php if ($pageId === 0): ?>
                    <th>Page</th>
                    <?php endif; ?>
                    <th>Section Name</th>
                    <th>Title</th>
                    <th>Order</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sections as $section): ?>
                <tr>
                    <td><?= $section['id'] ?></td>
                    <?php if ($pageId === 0): ?>
                    <td><?= htmlspecialchars($section['page_title']) ?></td>
                    <?php endif; ?>
                    <td><?= htmlspecialchars($section['section_name']) ?></td>
                    <td><?= htmlspecialchars($section['title']) ?></td>
                    <td><?= $section['order_num'] ?></td>
                    <td class="actions">
                        <a href="section-edit.php?id=<?= $section['id'] ?>" class="btn btn-sm btn-edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="section-items.php?section_id=<?= $section['id'] ?>" class="btn btn-sm btn-items" title="Manage Items">
                            <i class="fas fa-list"></i>
                        </a>
                        <a href="javascript:void(0);" onclick="confirmDelete(<?= $section['id'] ?>)" class="btn btn-sm btn-delete" title="Delete">
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
    if (confirm('Are you sure you want to delete this section? This action cannot be undone.')) {
        window.location.href = 'sections.php?<?= $pageId > 0 ? "page_id={$pageId}&" : "" ?>delete=' + id;
    }
}
</script>

<?php require_once 'includes/admin-footer.php'; ?>
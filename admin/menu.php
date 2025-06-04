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

// Handle menu item deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $menuId = (int)$_GET['delete'];
    if (deleteMenuItem($menuId)) {
        $_SESSION['success_message'] = "Menu item deleted successfully";
    } else {
        $_SESSION['error_message'] = "Failed to delete menu item";
    }
    header('Location: menu.php');
    exit;
}

// Get all menu items
$menuItems = getAllMenuItems();
$menuHierarchy = buildMenuHierarchy($menuItems);

// Get all pages for dropdown
$pages = getAllPages();

require_once 'includes/admin-header.php';
?>

<div class="admin-content">
    <div class="content-header">
        <h1>Manage Menu</h1>
        <a href="menu-edit.php" class="btn btn-primary">Add Menu Item</a>
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
        <?php if (empty($menuItems)): ?>
        <p>No menu items found. Add your first menu item to get started.</p>
        <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>URL/Page</th>
                    <th>Parent</th>
                    <th>Order</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($menuItems as $item): ?>
                <tr>
                    <td><?= str_repeat('— ', $item['level'] ?? 0) ?><?= htmlspecialchars($item['title']) ?></td>
                    <td>
                        <?php if ($item['page_id']): ?>
                            Page: <?= htmlspecialchars($item['page_title']) ?>
                        <?php else: ?>
                            URL: <?= htmlspecialchars($item['url']) ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php
                        if ($item['parent_id']) {
                            foreach ($menuItems as $parent) {
                                if ($parent['id'] == $item['parent_id']) {
                                    echo htmlspecialchars($parent['title']);
                                    break;
                                }
                            }
                        } else {
                            echo '—';
                        }
                        ?>
                    </td>
                    <td><?= $item['order_num'] ?></td>
                    <td class="actions">
                        <a href="menu-edit.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="javascript:void(0);" onclick="confirmDelete(<?= $item['id'] ?>)" class="btn btn-sm btn-delete" title="Delete">
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
    if (confirm('Are you sure you want to delete this menu item? This will also remove it from any submenus.')) {
        window.location.href = 'menu.php?delete=' + id;
    }
}
</script>

<?php require_once 'includes/admin-footer.php'; ?>
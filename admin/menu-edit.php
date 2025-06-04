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

$menuId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$menuItem = [];
$isNew = true;

if ($menuId > 0) {
    $menuItem = getMenuItemById($menuId);
    if (!$menuItem) {
        $_SESSION['error_message'] = "Menu item not found";
        header('Location: menu.php');
        exit;
    }
    $isNew = false;
}

// Get all menu items for parent selection
$menuItems = getAllMenuItems();
// Remove current item and its children from potential parents
$potentialParents = array_filter($menuItems, function($item) use ($menuId) {
    return $item['id'] != $menuId;
});

// Get all pages for page selection
$pages = getAllPages();

$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $url = trim($_POST['url'] ?? '');
    $pageId = !empty($_POST['page_id']) ? (int)$_POST['page_id'] : null;
    $parentId = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
    $orderNum = (int)($_POST['order_num'] ?? 0);
    
    if (empty($title)) {
        $errors[] = "Title is required";
    }
    
    if (empty($url) && empty($pageId)) {
        $errors[] = "Either URL or Page must be selected";
    }
    
    if (empty($errors)) {
        $menuData = [
            'title' => $title,
            'url' => $url,
            'page_id' => $pageId,
            'parent_id' => $parentId,
            'order_num' => $orderNum
        ];
        
        if ($isNew) {
            $result = addMenuItem($menuData);
            $message = "Menu item created successfully";
        } else {
            $result = updateMenuItem($menuId, $menuData);
            $message = "Menu item updated successfully";
        }
        
        if ($result) {
            $_SESSION['success_message'] = $message;
            header('Location: menu.php');
            exit;
        } else {
            $errors[] = "Failed to save menu item";
        }
    }
}

require_once 'includes/admin-header.php';
?>

<div class="admin-content">
    <div class="content-header">
        <h1><?= $isNew ? 'Add New Menu Item' : 'Edit Menu Item' ?></h1>
        <a href="menu.php" class="btn btn-secondary">Back to Menu</a>
    </div>
    
    <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    
    <div class="content-body">
        <form method="post" action="<?= $isNew ? 'menu-edit.php' : "menu-edit.php?id={$menuId}" ?>">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($menuItem['title'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="page_id">Page</label>
                <select id="page_id" name="page_id">
                    <option value="">Select a page...</option>
                    <?php foreach ($pages as $page): ?>
                    <option value="<?= $page['id'] ?>" <?= ($page['id'] == ($menuItem['page_id'] ?? '')) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($page['title']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <small>Select a page or enter a custom URL below</small>
            </div>
            
            <div class="form-group">
                <label for="url">Custom URL</label>
                <input type="text" id="url" name="url" value="<?= htmlspecialchars($menuItem['url'] ?? '') ?>">
                <small>Leave empty if a page is selected</small>
            </div>
            
            <div class="form-group">
                <label for="parent_id">Parent Menu Item</label>
                <select id="parent_id" name="parent_id">
                    <option value="">None (Top Level)</option>
                    <?php foreach ($potentialParents as $parent): ?>
                    <option value="<?= $parent['id'] ?>" <?= ($parent['id'] == ($menuItem['parent_id'] ?? '')) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($parent['title']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="order_num">Order</label>
                <input type="number" id="order_num" name="order_num" value="<?= (int)($menuItem['order_num'] ?? 0) ?>">
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?= $isNew ? 'Create Menu Item' : 'Update Menu Item' ?></button>
                <a href="menu.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('page_id').addEventListener('change', function() {
    const urlInput = document.getElementById('url');
    if (this.value) {
        urlInput.value = '';
        urlInput.disabled = true;
    } else {
        urlInput.disabled = false;
    }
});

// Initialize on page load
if (document.getElementById('page_id').value) {
    document.getElementById('url').disabled = true;
}
</script>

<?php require_once 'includes/admin-footer.php'; ?>
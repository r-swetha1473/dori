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

$pageId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$page = [];
$isNew = true;

if ($pageId > 0) {
    $page = getPageById($pageId);
    if (!$page) {
        $_SESSION['error_message'] = "Page not found";
        header('Location: pages.php');
        exit;
    }
    $isNew = false;
}

$errors = [];
$success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $metaDescription = trim($_POST['meta_description'] ?? '');
    $content = $_POST['content'] ?? '';
    
    if (empty($title)) {
        $errors[] = "Title is required";
    }
    
    if (empty($slug)) {
        $slug = createSlug($title);
    } else {
        $slug = createSlug($slug);
    }
    
    // Check if slug exists (for new pages or when changing slug)
    if ($isNew || ($page['slug'] !== $slug)) {
        if (slugExists($slug)) {
            $errors[] = "A page with this URL slug already exists";
        }
    }
    
    if (empty($errors)) {
        $pageData = [
            'title' => $title,
            'slug' => $slug,
            'meta_description' => $metaDescription,
            'content' => $content
        ];
        
        if ($isNew) {
            $result = addPage($pageData);
            $message = "Page created successfully";
        } else {
            $result = updatePage($pageId, $pageData);
            $message = "Page updated successfully";
        }
        
        if ($result) {
            $_SESSION['success_message'] = $message;
            header('Location: pages.php');
            exit;
        } else {
            $errors[] = "Failed to save page";
        }
    }
}

require_once 'includes/admin-header.php';
?>

<div class="admin-content">
    <div class="content-header">
        <h1><?= $isNew ? 'Add New Page' : 'Edit Page' ?></h1>
        <a href="pages.php" class="btn btn-secondary">Back to Pages</a>
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
        <form method="post" action="<?= $isNew ? 'page-edit.php' : "page-edit.php?id={$pageId}" ?>">
            <div class="form-group">
                <label for="title">Page Title</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($page['title'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="slug">URL Slug</label>
                <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($page['slug'] ?? '') ?>">
                <small>Leave blank to generate automatically from title. Use only letters, numbers, and hyphens.</small>
            </div>
            
            <div class="form-group">
                <label for="meta_description">Meta Description</label>
                <textarea id="meta_description" name="meta_description" rows="3"><?= htmlspecialchars($page['meta_description'] ?? '') ?></textarea>
                <small>Brief description for search engines. Recommended 150-160 characters.</small>
            </div>
            
            <div class="form-group">
                <label for="content">Page Content</label>
                <textarea id="content" name="content" class="wysiwyg-editor"><?= htmlspecialchars($page['content'] ?? '') ?></textarea>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?= $isNew ? 'Create Page' : 'Update Page' ?></button>
                <a href="pages.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/admin-footer.php'; ?>
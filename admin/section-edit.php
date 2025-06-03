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

$sectionId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$pageId = isset($_GET['page_id']) ? (int)$_GET['page_id'] : 0;
$section = [];
$isNew = true;

if ($sectionId > 0) {
    $section = getSectionById($sectionId);
    if (!$section) {
        $_SESSION['error_message'] = "Section not found";
        header('Location: sections.php');
        exit;
    }
    $isNew = false;
    $pageId = $section['page_id'];
}

// Get all pages for dropdown
$pages = getAllPages();

$errors = [];
$success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    $pageId = (int)$_POST['page_id'];
    $sectionName = trim($_POST['section_name'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $subtitle = trim($_POST['subtitle'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $content = $_POST['content'] ?? '';
    $orderNum = (int)$_POST['order_num'];
    
    // Handle image upload
    $imagePath = $section['image_path'] ?? '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadResult = uploadFile($_FILES['image']);
        if ($uploadResult['success']) {
            $imagePath = $uploadResult['file_path'];
        } else {
            $errors[] = $uploadResult['error'];
        }
    }
    
    if (empty($sectionName)) {
        $errors[] = "Section name is required";
    }
    
    if (empty($errors)) {
        $sectionData = [
            'page_id' => $pageId,
            'section_name' => $sectionName,
            'title' => $title,
            'subtitle' => $subtitle,
            'description' => $description,
            'content' => $content,
            'image_path' => $imagePath,
            'order_num' => $orderNum
        ];
        
        if ($isNew) {
            $result = addSection($sectionData);
            $message = "Section created successfully";
        } else {
            $result = updateSection($sectionId, $sectionData);
            $message = "Section updated successfully";
        }
        
        if ($result) {
            $_SESSION['success_message'] = $message;
            header('Location: sections.php' . ($pageId ? "?page_id={$pageId}" : ''));
            exit;
        } else {
            $errors[] = "Failed to save section";
        }
    }
}

require_once 'includes/admin-header.php';
?>

<div class="admin-content">
    <div class="content-header">
        <h1><?= $isNew ? 'Add New Section' : 'Edit Section' ?></h1>
        <a href="sections.php<?= $pageId ? "?page_id={$pageId}" : '' ?>" class="btn btn-secondary">Back to Sections</a>
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
        <form method="post" action="<?= $isNew ? 'section-edit.php' : "section-edit.php?id={$sectionId}" ?>" enctype="multipart/form-data">
            <div class="form-group">
                <label for="page_id">Page</label>
                <select id="page_id" name="page_id" required>
                    <?php foreach ($pages as $page): ?>
                    <option value="<?= $page['id'] ?>" <?= ($page['id'] == ($section['page_id'] ?? $pageId) ? 'selected' : '') ?>>
                        <?= htmlspecialchars($page['title']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="section_name">Section Name</label>
                <input type="text" id="section_name" name="section_name" value="<?= htmlspecialchars($section['section_name'] ?? '') ?>" required>
                <small>Unique identifier for this section (e.g., hero, features, testimonials)</small>
            </div>
            
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($section['title'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="subtitle">Subtitle</label>
                <input type="text" id="subtitle" name="subtitle" value="<?= htmlspecialchars($section['subtitle'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3"><?= htmlspecialchars($section['description'] ?? '') ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" class="summernote"><?= htmlspecialchars($section['content'] ?? '') ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="image">Section Image</label>
                <input type="file" id="image" name="image" accept="image/*">
                <?php if (!empty($section['image_path'])): ?>
                <div class="current-image">
                    <img src="<?= htmlspecialchars($section['image_path']) ?>" alt="Current section image" style="max-width: 200px;">
                </div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="order_num">Order</label>
                <input type="number" id="order_num" name="order_num" value="<?= (int)($section['order_num'] ?? 0) ?>">
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?= $isNew ? 'Create Section' : 'Update Section' ?></button>
                <a href="sections.php<?= $pageId ? "?page_id={$pageId}" : '' ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<!-- Include Summernote CSS/JS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

<script>
$(document).ready(function() {
    $('.summernote').summernote({
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'codeview']]
        ]
    });
});
</script>

<?php require_once 'includes/admin-footer.php'; ?>
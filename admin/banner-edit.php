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

$bannerId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$banner = [];
$isNew = true;

if ($bannerId > 0) {
    $banner = getBannerById($bannerId);
    if (!$banner) {
        $_SESSION['error_message'] = "Banner not found";
        header('Location: banners.php');
        exit;
    }
    $isNew = false;
}

$errors = [];
$success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $subtitle = trim($_POST['subtitle'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $buttonText = trim($_POST['button_text'] ?? '');
    $buttonLink = trim($_POST['button_link'] ?? '');
    $isActive = isset($_POST['is_active']) ? 1 : 0;
    
    // Handle image upload
    $imagePath = $banner['image_path'] ?? '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadResult = uploadFile($_FILES['image']);
        if ($uploadResult['success']) {
            $imagePath = $uploadResult['file_path'];
        } else {
            $errors[] = $uploadResult['error'];
        }
    }
    
    if (empty($title)) {
        $errors[] = "Title is required";
    }
    
    if (empty($imagePath) && $isNew) {
        $errors[] = "Banner image is required";
    }
    
    if (empty($errors)) {
        $bannerData = [
            'title' => $title,
            'subtitle' => $subtitle,
            'description' => $description,
            'button_text' => $buttonText,
            'button_link' => $buttonLink,
            'image_path' => $imagePath,
            'is_active' => $isActive
        ];
        
        if ($isNew) {
            $result = addBanner($bannerData);
            $message = "Banner created successfully";
        } else {
            $result = updateBanner($bannerId, $bannerData);
            $message = "Banner updated successfully";
        }
        
        if ($result) {
            $_SESSION['success_message'] = $message;
            header('Location: banners.php');
            exit;
        } else {
            $errors[] = "Failed to save banner";
        }
    }
}

require_once 'includes/admin-header.php';
?>

<div class="admin-content">
    <div class="content-header">
        <h1><?= $isNew ? 'Add New Banner' : 'Edit Banner' ?></h1>
        <a href="banners.php" class="btn btn-secondary">Back to Banners</a>
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
        <form method="post" action="<?= $isNew ? 'banner-edit.php' : "banner-edit.php?id={$bannerId}" ?>" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($banner['title'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="subtitle">Subtitle</label>
                <input type="text" id="subtitle" name="subtitle" value="<?= htmlspecialchars($banner['subtitle'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="summernote"><?= htmlspecialchars($banner['description'] ?? '') ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="button_text">Button Text</label>
                <input type="text" id="button_text" name="button_text" value="<?= htmlspecialchars($banner['button_text'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="button_link">Button Link</label>
                <input type="text" id="button_link" name="button_link" value="<?= htmlspecialchars($banner['button_link'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="image">Banner Image</label>
                <input type="file" id="image" name="image" accept="image/*" <?= $isNew ? 'required' : '' ?>>
                <?php if (!empty($banner['image_path'])): ?>
                <div class="current-image">
                    <img src="<?= htmlspecialchars($banner['image_path']) ?>" alt="Current banner image" style="max-width: 200px;">
                </div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" value="1" <?= (!$isNew && $banner['is_active']) ? 'checked' : '' ?>>
                    Active
                </label>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?= $isNew ? 'Create Banner' : 'Update Banner' ?></button>
                <a href="banners.php" class="btn btn-secondary">Cancel</a>
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
        height: 200,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview']]
        ]
    });
});
</script>

<?php require_once 'includes/admin-footer.php'; ?>
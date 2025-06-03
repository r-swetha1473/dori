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

$userId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user = [];
$isNew = true;

if ($userId > 0) {
    $user = getUserById($userId);
    if (!$user) {
        $_SESSION['error_message'] = "User not found";
        header('Location: users.php');
        exit;
    }
    $isNew = false;
}

$errors = [];
$success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');
    
    // Validate inputs
    if (empty($username)) {
        $errors[] = "Username is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if ($isNew || !empty($password)) {
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long";
        }
        if ($password !== $confirmPassword) {
            $errors[] = "Passwords do not match";
        }
    }
    
    // Check if username exists
    if ($isNew || ($user['username'] !== $username)) {
        if (usernameExists($username)) {
            $errors[] = "Username already exists";
        }
    }
    
    if (empty($errors)) {
        $userData = [
            'username' => $username,
            'email' => $email
        ];
        
        if (!empty($password)) {
            $userData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        
        if ($isNew) {
            $result = addUser($userData);
            $message = "User created successfully";
        } else {
            $result = updateUser($userId, $userData);
            $message = "User updated successfully";
        }
        
        if ($result) {
            $_SESSION['success_message'] = $message;
            header('Location: users.php');
            exit;
        } else {
            $errors[] = "Failed to save user";
        }
    }
}

require_once 'includes/admin-header.php';
?>

<div class="admin-content">
    <div class="content-header">
        <h1><?= $isNew ? 'Add New User' : 'Edit User' ?></h1>
        <a href="users.php" class="btn btn-secondary">Back to Users</a>
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
        <form method="post" action="<?= $isNew ? 'user-edit.php' : "user-edit.php?id={$userId}" ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password"><?= $isNew ? 'Password' : 'New Password' ?></label>
                <input type="password" id="password" name="password" <?= $isNew ? 'required' : '' ?>>
                <?php if (!$isNew): ?>
                <small>Leave blank to keep current password</small>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" <?= $isNew ? 'required' : '' ?>>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?= $isNew ? 'Create User' : 'Update User' ?></button>
                <a href="users.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/admin-footer.php'; ?>
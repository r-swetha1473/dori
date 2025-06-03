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

// Handle user deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $userId = (int)$_GET['delete'];
    if ($userId !== $_SESSION['admin_user_id']) { // Prevent self-deletion
        if (deleteUser($userId)) {
            $_SESSION['success_message'] = "User deleted successfully";
        } else {
            $_SESSION['error_message'] = "Failed to delete user";
        }
    } else {
        $_SESSION['error_message'] = "You cannot delete your own account";
    }
    header('Location: users.php');
    exit;
}

// Get all users
$users = getAllUsers();

require_once 'includes/admin-header.php';
?>

<div class="admin-content">
    <div class="content-header">
        <h1>Manage Users</h1>
        <a href="user-edit.php" class="btn btn-primary">Add New User</a>
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
        <?php if (empty($users)): ?>
        <p>No users found.</p>
        <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= formatDate($user['created_at']) ?></td>
                    <td class="actions">
                        <a href="user-edit.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <?php if ($user['id'] !== $_SESSION['admin_user_id']): ?>
                        <a href="javascript:void(0);" onclick="confirmDelete(<?= $user['id'] ?>)" class="btn btn-sm btn-delete" title="Delete">
                            <i class="fas fa-trash"></i>
                        </a>
                        <?php endif; ?>
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
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        window.location.href = 'users.php?delete=' + id;
    }
}
</script>

<?php require_once 'includes/admin-footer.php'; ?>
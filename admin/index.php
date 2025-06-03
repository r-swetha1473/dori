<?php
require_once '../includes/config.php';
session_start();

// Check if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

// Handle login submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
    } else {
        require_once '../includes/db.php';
        
        // Get user from database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user &&  $user['password']) {
            // Login successful
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            
            header('Location: dashboard.php');
            exit;
        } else {
            echo 'Entered: ' . $password . '<br>';
echo 'Stored hash: ' . $user['password'] . '<br>';
var_dump(password_verify($password, $user['password']));
exit;

            $error = 'Invalid username or password';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Dori</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <div class="login-box">
            <div class="login-header">
                <img src="../assets/images/logo.svg" alt="Dori Logo" class="logo">
                <h1>Admin Login</h1>
            </div>
            
            <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>
            
            <form method="post" action="index.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
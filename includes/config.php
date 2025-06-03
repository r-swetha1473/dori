<?php
// Configuration file

// Enable error reporting for development
// Comment out or set to 0 for production
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'dori_website');
define('DB_USER', 'root');
define('DB_PASS', '');

// Site configuration
define('SITE_NAME', 'Dori');
define('SITE_URL', 'http://localhost/Projects/02-06-25/Dori/dori');
define('ADMIN_EMAIL', 'admin@example.com');

// File uploads
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'svg']);

// Session timeout (30 minutes)
define('SESSION_TIMEOUT', 30 * 60);
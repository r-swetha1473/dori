<?php
// Database connection and functions

// Create PDO connection
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    // For production, log the error and show a generic message
    // For development, we can show the actual error
    die('Database connection failed: ' . $e->getMessage());
}

// Initialize database if needed (first run)
function initializeDatabase() {
    global $pdo;
    
    try {
        // Check if tables exist
        $stmt = $pdo->prepare("SHOW TABLES LIKE 'users'");
        $stmt->execute();
        
        if ($stmt->rowCount() === 0) {
            // Create tables
            $queries = [
                // Users table
                "CREATE TABLE users (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    username VARCHAR(50) NOT NULL UNIQUE,
                    password VARCHAR(255) NOT NULL,
                    email VARCHAR(100) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )",
                
                // Pages table
                "CREATE TABLE pages (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    slug VARCHAR(100) NOT NULL UNIQUE,
                    title VARCHAR(255) NOT NULL,
                    meta_description TEXT,
                    content LONGTEXT,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )",
                
                // Sections table
                "CREATE TABLE sections (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    page_id INT NOT NULL,
                    section_name VARCHAR(100) NOT NULL,
                    title VARCHAR(255),
                    subtitle VARCHAR(255),
                    description TEXT,
                    content LONGTEXT,
                    image_path VARCHAR(255),
                    order_num INT DEFAULT 0,
                    FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE
                )",
                
                // Section items table
                "CREATE TABLE section_items (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    section_id INT NOT NULL,
                    title VARCHAR(255),
                    description TEXT,
                    image_path VARCHAR(255),
                    icon VARCHAR(100),
                    link VARCHAR(255),
                    order_num INT DEFAULT 0,
                    FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE CASCADE
                )",
                
                // Statistics table
                "CREATE TABLE statistics (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    page_id INT NOT NULL,
                    stat_value VARCHAR(20) NOT NULL,
                    stat_label VARCHAR(100) NOT NULL,
                    icon VARCHAR(50),
                    order_num INT DEFAULT 0,
                    FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE
                )",
                
                // Case studies table
                "CREATE TABLE case_studies (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    title VARCHAR(255) NOT NULL,
                    company VARCHAR(100),
                    excerpt TEXT,
                    content LONGTEXT,
                    image_path VARCHAR(255),
                    published BOOLEAN DEFAULT TRUE,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )",
                
                // Partners table
                "CREATE TABLE partners (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(100) NOT NULL,
                    logo_path VARCHAR(255) NOT NULL,
                    website VARCHAR(255),
                    order_num INT DEFAULT 0
                )",
                
                // FAQs table
                "CREATE TABLE faqs (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    page_id INT,
                    question VARCHAR(255) NOT NULL,
                    answer TEXT NOT NULL,
                    order_num INT DEFAULT 0,
                    FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE SET NULL
                )",
                
                // Related content table
                "CREATE TABLE related_content (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    page_id INT,
                    title VARCHAR(255) NOT NULL,
                    type VARCHAR(50) NOT NULL,
                    category VARCHAR(100),
                    excerpt TEXT,
                    content LONGTEXT,
                    image_path VARCHAR(255),
                    link VARCHAR(255),
                    read_time INT DEFAULT 5,
                    published BOOLEAN DEFAULT TRUE,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE SET NULL
                )",
                
                // Activity log table
                "CREATE TABLE activity_log (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT NOT NULL,
                    action VARCHAR(50) NOT NULL,
                    item_type VARCHAR(50) NOT NULL,
                    item_id INT NOT NULL,
                    item_name VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                )",
                
                // Uploads table
                "CREATE TABLE uploads (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    file_name VARCHAR(255) NOT NULL,
                    file_path VARCHAR(255) NOT NULL,
                    file_type VARCHAR(100) NOT NULL,
                    file_size INT NOT NULL,
                    uploaded_by INT,
                    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
                )"
            ];
            
            // Execute all queries
            $pdo->beginTransaction();
            
            foreach ($queries as $query) {
                $pdo->exec($query);
            }
            
            // Create default admin user
            $username = 'admin';
            $password = password_hash('admin123', PASSWORD_DEFAULT);
            $email = 'admin@example.com';
            
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
            $stmt->execute([$username, $password, $email]);
            
            $pdo->commit();
            
            return true;
        }
        
        return false;
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        die('Database initialization failed: ' . $e->getMessage());
    }
}

// Call initialization if needed
initializeDatabase();
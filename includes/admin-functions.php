<?php
// Admin-specific functions

/**
 * Get all pages
 */
function getAllPages() {
    global $pdo;
    
    $stmt = $pdo->query("SELECT * FROM pages ORDER BY title ASC");
    
    return $stmt->fetchAll();
}

/**
 * Get page by ID
 */
function getPageById($pageId) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM pages WHERE id = ?");
    $stmt->execute([$pageId]);
    
    return $stmt->fetch();
}

/**
 * Add a new page
 */
function addPage($pageData) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO pages (title, slug, meta_description, content)
            VALUES (?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $pageData['title'],
            $pageData['slug'],
            $pageData['meta_description'],
            $pageData['content']
        ]);
        
        // Log activity
        logActivity('create', 'page', $pdo->lastInsertId(), $pageData['title']);
        
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Update an existing page
 */
function updatePage($pageId, $pageData) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            UPDATE pages
            SET title = ?, slug = ?, meta_description = ?, content = ?
            WHERE id = ?
        ");
        
        $stmt->execute([
            $pageData['title'],
            $pageData['slug'],
            $pageData['meta_description'],
            $pageData['content'],
            $pageId
        ]);
        
        // Log activity
        logActivity('update', 'page', $pageId, $pageData['title']);
        
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Delete a page
 */
function deletePage($pageId) {
    global $pdo;
    
    try {
        // Get page title for activity log
        $stmt = $pdo->prepare("SELECT title FROM pages WHERE id = ?");
        $stmt->execute([$pageId]);
        $page = $stmt->fetch();
        
        // Delete page
        $stmt = $pdo->prepare("DELETE FROM pages WHERE id = ?");
        $stmt->execute([$pageId]);
        
        // Log activity
        logActivity('delete', 'page', $pageId, $page['title']);
        
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Check if a slug already exists
 */
function slugExists($slug) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM pages WHERE slug = ?");
    $stmt->execute([$slug]);
    
    return $stmt->fetchColumn() > 0;
}

/**
 * Get all sections
 */
function getAllSections() {
    global $pdo;
    
    $stmt = $pdo->query("
        SELECT s.*, p.title as page_title
        FROM sections s
        JOIN pages p ON s.page_id = p.id
        ORDER BY p.title ASC, s.order_num ASC
    ");
    
    return $stmt->fetchAll();
}

/**
 * Get sections by page ID
 */
function getSectionsByPageId($pageId) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT s.*, p.title as page_title
        FROM sections s
        JOIN pages p ON s.page_id = p.id
        WHERE s.page_id = ?
        ORDER BY s.order_num ASC
    ");
    $stmt->execute([$pageId]);
    
    return $stmt->fetchAll();
}

/**
 * Get section by ID
 */
function getSectionById($sectionId) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT s.*, p.title as page_title
        FROM sections s
        JOIN pages p ON s.page_id = p.id
        WHERE s.id = ?
    ");
    $stmt->execute([$sectionId]);
    
    return $stmt->fetch();
}

/**
 * Add a new section
 */
function addSection($sectionData) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO sections 
            (page_id, section_name, title, subtitle, description, content, image_path, order_num)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $sectionData['page_id'],
            $sectionData['section_name'],
            $sectionData['title'],
            $sectionData['subtitle'] ?? null,
            $sectionData['description'] ?? null,
            $sectionData['content'] ?? null,
            $sectionData['image_path'] ?? null,
            $sectionData['order_num'] ?? 0
        ]);
        
        // Log activity
        logActivity('create', 'section', $pdo->lastInsertId(), $sectionData['section_name']);
        
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Update an existing section
 */
function updateSection($sectionId, $sectionData) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            UPDATE sections
            SET page_id = ?, section_name = ?, title = ?, subtitle = ?, 
                description = ?, content = ?, image_path = ?, order_num = ?
            WHERE id = ?
        ");
        
        $stmt->execute([
            $sectionData['page_id'],
            $sectionData['section_name'],
            $sectionData['title'],
            $sectionData['subtitle'] ?? null,
            $sectionData['description'] ?? null,
            $sectionData['content'] ?? null,
            $sectionData['image_path'] ?? null,
            $sectionData['order_num'] ?? 0,
            $sectionId
        ]);
        
        // Log activity
        logActivity('update', 'section', $sectionId, $sectionData['section_name']);
        
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Delete a section
 */
function deleteSection($sectionId) {
    global $pdo;
    
    try {
        // Get section name for activity log
        $stmt = $pdo->prepare("SELECT section_name FROM sections WHERE id = ?");
        $stmt->execute([$sectionId]);
        $section = $stmt->fetch();
        
        // Delete section
        $stmt = $pdo->prepare("DELETE FROM sections WHERE id = ?");
        $stmt->execute([$sectionId]);
        
        // Log activity
        logActivity('delete', 'section', $sectionId, $section['section_name']);
        
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Get total count of a table
 */
function getTotalCount($table) {
    global $pdo;
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM {$table}");
    
    return $stmt->fetchColumn();
}

/**
 * Get recent activity
 */
function getRecentActivity($limit = 10) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT al.*, u.username
        FROM activity_log al
        JOIN users u ON al.user_id = u.id
        ORDER BY al.created_at DESC
        LIMIT ?
    ");
    $stmt->execute([$limit]);
    
    return $stmt->fetchAll();
}

/**
 * Log an activity
 */
function logActivity($action, $itemType, $itemId, $itemName) {
    global $pdo;
    
    if (!isset($_SESSION['admin_user_id'])) {
        return false;
    }
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO activity_log (user_id, action, item_type, item_id, item_name)
            VALUES (?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $_SESSION['admin_user_id'],
            $action,
            $itemType,
            $itemId,
            $itemName
        ]);
        
        return true;
    } catch (PDOException $e) {
        return false;
    }
}
/**
 * Format date
 */
function formatDate($date) {
    return date('F j, Y', strtotime($date));
}

/**
 * Handle file upload
 */
function uploadFile($file) {
    // Check for errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return [
            'success' => false,
            'error' => 'Upload failed with error code: ' . $file['error']
        ];
    }
    
    // Check file size
    if ($file['size'] > MAX_FILE_SIZE) {
        return [
            'success' => false,
            'error' => 'File is too large. Maximum size is ' . (MAX_FILE_SIZE / 1024 / 1024) . 'MB'
        ];
    }
    
    // Check file extension
    $fileInfo = pathinfo($file['name']);
    $extension = strtolower($fileInfo['extension']);
    
    if (!in_array($extension, ALLOWED_EXTENSIONS)) {
        return [
            'success' => false,
            'error' => 'Invalid file type. Allowed types: ' . implode(', ', ALLOWED_EXTENSIONS)
        ];
    }
    
    // Create upload directory if it doesn't exist
    if (!file_exists(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }
    
    // Generate unique filename
    $newFilename = uniqid() . '.' . $extension;
    $destination = UPLOAD_DIR . $newFilename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        // Save to database
        global $pdo;
        
        try {
            $stmt = $pdo->prepare("
                INSERT INTO uploads (file_name, file_path, file_type, file_size, uploaded_by)
                VALUES (?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $file['name'],
                'uploads/' . $newFilename,
                $file['type'],
                $file['size'],
                $_SESSION['admin_user_id'] ?? null
            ]);
            
            $uploadId = $pdo->lastInsertId();
            
            // Log activity
            logActivity('upload', 'file', $uploadId, $file['name']);
            
            return [
                'success' => true,
                'file_id' => $uploadId,
                'file_path' => 'uploads/' . $newFilename,
                'file_name' => $file['name']
            ];
        } catch (PDOException $e) {
            // Delete file if database insert fails
            unlink($destination);
            
            return [
                'success' => false,
                'error' => 'Database error: ' . $e->getMessage()
            ];
        }
    } else {
        return [
            'success' => false,
            'error' => 'Failed to move uploaded file'
        ];
    }
}
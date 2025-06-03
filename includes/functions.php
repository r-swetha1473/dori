<?php
// Common functions for the frontend

/**
 * Get page content by slug
 */
function getPageContent($slug) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM pages WHERE slug = ?");
    $stmt->execute([$slug]);
    
    return $stmt->fetch();
}

/**
 * Get section content by page slug and section name
 */
function getSectionContent($pageSlug, $sectionName) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT s.* FROM sections s
        JOIN pages p ON s.page_id = p.id
        WHERE p.slug = ? AND s.section_name = ?
    ");
    $stmt->execute([$pageSlug, $sectionName]);
    
    return $stmt->fetch();
}

/**
 * Get section items by page slug and section name
 */
function getSectionItems($pageSlug, $sectionName) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT si.* FROM section_items si
        JOIN sections s ON si.section_id = s.id
        JOIN pages p ON s.page_id = p.id
        WHERE p.slug = ? AND s.section_name = ?
        ORDER BY si.order_num ASC
    ");
    $stmt->execute([$pageSlug, $sectionName]);
    
    return $stmt->fetchAll();
}

/**
 * Get statistics for a page
 */
function getPageStatistics($pageSlug) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT st.* FROM statistics st
        JOIN pages p ON st.page_id = p.id
        WHERE p.slug = ?
        ORDER BY st.order_num ASC
    ");
    $stmt->execute([$pageSlug]);
    
    return $stmt->fetchAll();
}

/**
 * Get case studies
 */
function getCaseStudies($limit = 3) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT * FROM case_studies
        WHERE published = 1
        ORDER BY created_at DESC
        LIMIT ?
    ");
    $stmt->execute([$limit]);
    
    return $stmt->fetchAll();
}

/**
 * Get partners
 */
function getPartners() {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT * FROM partners
        ORDER BY order_num ASC
    ");
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Get FAQs for a page
 */
function getFAQs($pageSlug) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT f.* FROM faqs f
        JOIN pages p ON f.page_id = p.id
        WHERE p.slug = ?
        ORDER BY f.order_num ASC
    ");
    $stmt->execute([$pageSlug]);
    
    return $stmt->fetchAll();
}

/**
 * Get related content for a page
 */
function getRelatedContent($pageSlug, $limit = 3) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT rc.* FROM related_content rc
        JOIN pages p ON rc.page_id = p.id
        WHERE p.slug = ? AND rc.published = 1
        ORDER BY rc.created_at DESC
        LIMIT ?
    ");
    $stmt->execute([$pageSlug, $limit]);
    
    return $stmt->fetchAll();
}

/**
 * Format date
 */
function formatDate($date) {
    return date('F j, Y', strtotime($date));
}

/**
 * Truncate text to a certain number of words
 */
function truncateText($text, $limit = 20) {
    $words = explode(' ', $text);
    
    if (count($words) > $limit) {
        return implode(' ', array_slice($words, 0, $limit)) . '...';
    }
    
    return $text;
}

/**
 * Create a clean URL slug
 */
function createSlug($string) {
    $string = strtolower(trim($string));
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    $string = trim($string, '-');
    
    return $string;
}
-- Create the database
CREATE DATABASE IF NOT EXISTS dori_website;
USE dori_website;

-- Users table for admin panel
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Pages table for main content
CREATE TABLE pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    meta_description TEXT,
    content LONGTEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Sections table for modular content within pages
CREATE TABLE sections (
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
);

-- Section items table
CREATE TABLE section_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_id INT NOT NULL,
    title VARCHAR(255),
    description TEXT,
    image_path VARCHAR(255),
    icon VARCHAR(100),
    link VARCHAR(255),
    order_num INT DEFAULT 0,
    FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE CASCADE
);

-- Statistics for ROI metrics
CREATE TABLE statistics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page_id INT NOT NULL,
    stat_value VARCHAR(20) NOT NULL,
    stat_label VARCHAR(100) NOT NULL,
    icon VARCHAR(50),
    order_num INT DEFAULT 0,
    FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE
);

-- Case studies/testimonials
CREATE TABLE case_studies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    company VARCHAR(100),
    excerpt TEXT,
    content LONGTEXT,
    image_path VARCHAR(255),
    published BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Partners table
CREATE TABLE partners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    logo_path VARCHAR(255) NOT NULL,
    website VARCHAR(255),
    order_num INT DEFAULT 0
);

-- FAQs table
CREATE TABLE faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page_id INT,
    question VARCHAR(255) NOT NULL,
    answer TEXT NOT NULL,
    order_num INT DEFAULT 0,
    FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE SET NULL
);

-- Related content table
CREATE TABLE related_content (
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
);

-- Activity log table
CREATE TABLE activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    item_type VARCHAR(50) NOT NULL,
    item_id INT NOT NULL,
    item_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Uploads table
CREATE TABLE uploads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(100) NOT NULL,
    file_size INT NOT NULL,
    uploaded_by INT,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert default admin user
INSERT INTO users (username, password, email)
VALUES ('admin', '$2y$10$zKuO/BZ9YByPT5jW.DFofeVZoZCt3H5T3I4S.HGcJTCKBmE.p.p0u', 'admin@example.com');
-- Default password is 'admin123' (bcrypt hashed)

-- Insert default pages
INSERT INTO pages (slug, title, meta_description) VALUES 
('home', 'Dori - Connected Workforce Platform', 'Dori provides AI-powered manufacturing solutions that improve efficiency, quality, and productivity on the factory floor.'),
('solutions', 'Solutions - Dori', 'Quality control and process monitoring solutions for manufacturing using computer vision and AI.'),
('platforms', 'Platform - Dori', 'Doris Visual Intelligence Platform provides real-time monitoring and analytics for manufacturing operations.');

-- Insert sections for home page
INSERT INTO sections (page_id, section_name, title, subtitle, description, image_path, order_num) VALUES
(1, 'hero', 'Every minute matters on the floor', 'THE CONNECTED WORKFORCE PLATFORM', 'Perfect timing wins. Deploy and defense fast. Dont leave your shop floor unprepared or vulnerable to the things you cant predict.', 'assets/images/hero-bg.jpg', 0),
(1, 'solutions', 'Powerful Solutions for Any Operation', NULL, NULL, NULL, 1),
(1, 'stats', 'SHOW ACTION THAT MATTERS', 'See ROI in 2 Weeks', 'Reduced downtime, increased productivity and better quality. Engaged team members are an extra bonus', NULL, 2),
(1, 'ai', 'Dori AI enables <span>Data-Driven AI</span> decisions with an end-to-end platform', NULL, 'Our AI provides a layer of trust and intelligence that enables enterprises with a full-stack solution to address all their AI computer vision needs.', NULL, 3),
(1, 'unique', 'What Makes Dori Unique?', NULL, NULL, NULL, 4),
(1, 'community', 'Dori User Community Stories', NULL, NULL, NULL, 5);

-- Insert sections for solutions page
INSERT INTO sections (page_id, section_name, title, subtitle, description, image_path, order_num) VALUES
(2, 'hero', 'Quality You Can Count On', NULL, 'When equipment starts to safetys at the line, costly downtime begins. Let our computer vision detect potential issues early—before they escalate and act fast.', 'assets/images/solutions-hero.jpg', 0),
(2, 'platform', 'Visual Intelligence Platform', 'for Enterprise', NULL, NULL, 1),
(2, 'quality', 'See quality improve within 2 weeks', NULL, 'Youll see reduced downtime, increased productivity, better quality, and engaged team members.', NULL, 2),
(2, 'use_cases', 'Dori Key Use Cases', NULL, 'Dori AI enables enterprises with computer vision solutions across a range of use cases and industries.', NULL, 3),
(2, 'faq', 'Frequently asked questions', NULL, 'Get answers to your most pressing questions about how L2L can improve quality, reduce defects, eliminate scrap, and ensure compliance across your operations.', NULL, 4);

-- Insert sections for platforms page
INSERT INTO sections (page_id, section_name, title, subtitle, description, image_path, order_num) VALUES
(3, 'hero', 'Seamless Collaboration Zero Disruptions', NULL, 'Keep your factory by letting your engineers at any skill level find, diagnose, and solve AI computer vision problems before they escalate.', 'assets/images/platforms-hero.jpg', 0),
(3, 'tech', 'We have Simplified the Tech', NULL, 'Dori AI provides a suite of templated AI computer vision applications across a broad range of manufacturing needs.', NULL, 1),
(3, 'features', 'Real-Time Features', NULL, NULL, NULL, 2),
(3, 'applications', 'One Platform Many Applications', NULL, 'Dori AI provides a suite of tools and technologies that enable enterprises with a full-stack solution to address all their AI computer vision needs.', 'assets/images/applications-dashboard.jpg', 3),
(3, 'development', 'Data-Driven AI Development', NULL, 'Extract the business value out of your visual data and turn images and videos into real-time operational insights powered by AI and computer vision without the need for a large data science or engineering team.', NULL, 4);

-- Insert statistics for home page
INSERT INTO statistics (page_id, stat_value, stat_label, order_num) VALUES
(1, '-54%', 'Line downtime', 0),
(1, '-50%', 'Labor waiting time', 1),
(1, '+44%', 'Productivity', 2),
(1, '-10%', 'Quality defects', 3);

-- Insert statistics for solutions page
INSERT INTO statistics (page_id, stat_value, stat_label, order_num) VALUES
(2, '-80%', 'time spent on physical inventory count', 0),
(2, '-67%', 'product defects and recalls', 1),
(2, '+20%', 'units of product produced', 2);

-- Insert section items for home page
INSERT INTO section_items (section_id, title, description, icon, order_num) VALUES
(2, 'Connect workers and accelerate production', 'Remove information siloes that cause confusion and delays', 'fas fa-network-wired', 0),
(2, 'Digitize workflows and processes across the facility', 'Eliminate manual, time-consuming activities that slow down production', 'fas fa-clipboard-list', 1),
(2, 'Make problems visible', 'Visualize issues as they emerge and collaborate on solutions', 'fas fa-search', 2),
(2, 'Improve product quality and compliance', 'Catch quality issues at the source with automated inspections', 'fas fa-check-circle', 3);

-- Insert FAQs for solutions page
INSERT INTO faqs (page_id, question, answer, order_num) VALUES
(2, 'How does L2L help reduce defects and improve quality?', 'Our AI-powered visual inspection system automatically detects defects that human inspectors might miss. The system continuously learns and improves over time, providing early warning for potential quality issues before they become widespread problems.', 0),
(2, 'Can L2L support compliance and audit readiness?', 'Yes, our platform automatically documents all inspections and quality checks, creating a comprehensive audit trail. This documentation can be accessed instantly during audits, demonstrating your commitment to quality standards and regulatory requirements.', 1),
(2, 'Which quality metrics can L2L track?', 'Our system tracks a wide range of quality metrics including defect rates, first-pass yield, scrap rates, rework percentages, and compliance to specifications. We can also implement custom metrics specific to your manufacturing processes.', 2);

-- Insert partners
INSERT INTO partners (name, logo_path, website, order_num) VALUES
('Hitachi', 'assets/images/partners/hitachi.png', 'https://www.hitachi.com', 0),
('Autoliv', 'assets/images/partners/autoliv.png', 'https://www.autoliv.com', 1),
('Jeldwen', 'assets/images/partners/jeldwen.png', 'https://www.jeldwen.com', 2),
('Siemens', 'assets/images/partners/siemens.png', 'https://www.siemens.com', 3);

-- Insert case studies
INSERT INTO case_studies (title, company, excerpt, content, image_path, published) VALUES
('Danone Paperless Factory Showcases Manufacturing Sustainability', 'Danone', 'How Danone reduced waste, increased transparency, and improved productivity across their manufacturing lines using Dori AI vision platform.', 'Full case study content goes here...', 'assets/images/case-studies/danone.jpg', 1),
('Automotive Supplier Reduces Defects by 67% with Visual Inspection', 'Autoliv', 'Autoliv implemented Dori visual quality inspection system to detect defects in airbag components, significantly reducing defect rates and recalls.', 'Full case study content goes here...', 'assets/images/case-studies/autoliv.jpg', 1);

-- Insert related content
INSERT INTO related_content (page_id, title, type, category, excerpt, image_path, link, read_time) VALUES
(2, 'Reducing Scrap', 'ebooks', 'MANUFACTURING', 'Learn how AI-powered visual inspection can dramatically reduce scrap rates in manufacturing facilities.', 'assets/images/resources/reducing-scrap.jpg', '#', 15),
(2, 'Standardizing Production Across Plants', 'guides', 'OPERATIONAL EXCELLENCE', 'A digital approach to standardizing production across manufacturing plants.', 'assets/images/resources/standardizing.jpg', '#', 12),
(2, 'Are you measuring the right things?', 'ebooks', 'OPERATIONAL EXCELLENCE', 'How to identify and track the metrics that truly matter for your manufacturing operation.', 'assets/images/resources/measuring.jpg', '#', 8);
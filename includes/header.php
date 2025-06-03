<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Dori - AI-Powered Manufacturing Solutions' ?></title>
    <meta name="description" content="<?= $page['meta_description'] ?? 'Dori provides AI-powered manufacturing solutions that improve efficiency, quality, and productivity on the factory floor.' ?>">
    
    <!-- Favicon -->
    <link rel="icon" href="/assets/images/favicon.ico">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo SITE_URL?>assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="/">
                        <img src="<?php echo SITE_URL?>/assets/images/logo.svg" alt="Dori Logo">
                    </a>
                </div>
                
                <nav class="main-nav">
                    <ul class="nav-list">
                        <li class="nav-item has-dropdown">
                            <a href="<?php echo SITE_URL?>/platforms.php" class="nav-link">Platform</a>
                            <div class="dropdown-menu">
                                <ul>
                                    <li><a href="<?php echo SITE_URL?>/platforms.php#visual-intelligence">Visual Intelligence</a></li>
                                    <li><a href="<?php echo SITE_URL?>/platforms.php#ai-applications">AI Applications</a></li>
                                    <li><a href="<?php echo SITE_URL?>/platforms.php#data-driven">Data-Driven Development</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item has-dropdown">
                            <a href="<?php echo SITE_URL?>/solutions.php" class="nav-link">Solutions</a>
                            <div class="dropdown-menu">
                                <ul>
                                    <li><a href="<?php echo SITE_URL?>/solutions.php#quality-control">Quality Control</a></li>
                                    <li><a href="<?php echo SITE_URL?>/solutions.php#process-monitoring">Process Monitoring</a></li>
                                    <li><a href="<?php echo SITE_URL?>/solutions.php#site-visibility">Site Visibility</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item has-dropdown">
                            <a href="#" class="nav-link">Industries</a>
                            <div class="dropdown-menu">
                                <ul>
                                    <li><a href="#">Manufacturing</a></li>
                                    <li><a href="#">Automotive</a></li>
                                    <li><a href="#">Electronics</a></li>
                                    <li><a href="#">Food & Beverage</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item has-dropdown">
                            <a href="#" class="nav-link">Resources</a>
                            <div class="dropdown-menu">
                                <ul>
                                    <li><a href="#">Case Studies</a></li>
                                    <li><a href="#">Blog</a></li>
                                    <li><a href="#">Documentation</a></li>
                                    <li><a href="#">Support</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </nav>
                
                <div class="header-actions">
                    <a href="#" class="btn btn-primary">BOOK A DEMO</a>
                    <button class="mobile-menu-toggle">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </div>
    </header>
    
    <div class="mobile-menu">
        <div class="mobile-menu-header">
            <div class="logo">
                <a href="<?php echo SITE_URL?>/">
                    <img src="<?php echo SITE_URL?>/assets/images/logo.svg" alt="Dori Logo">
                </a>
            </div>
            <button class="mobile-menu-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <nav class="mobile-nav">
            <ul class="mobile-nav-list">
                <li class="mobile-nav-item">
                    <a href="#" class="mobile-nav-link mobile-dropdown-toggle">Platform</a>
                    <ul class="mobile-dropdown">
                        <li><a href="<?php echo SITE_URL?>/platforms.php#visual-intelligence">Visual Intelligence</a></li>
                        <li><a href="<?php echo SITE_URL?>/platforms.php#ai-applications">AI Applications</a></li>
                        <li><a href="<?php echo SITE_URL?>/platforms.php#data-driven">Data-Driven Development</a></li>
                    </ul>
                </li>
                <li class="mobile-nav-item">
                    <a href="#" class="mobile-nav-link mobile-dropdown-toggle">Solutions</a>
                    <ul class="mobile-dropdown">
                        <li><a href="<?php echo SITE_URL?>/solutions.php#quality-control">Quality Control</a></li>
                        <li><a href="<?php echo SITE_URL?>/solutions.php#process-monitoring">Process Monitoring</a></li>
                        <li><a href="<?php echo SITE_URL?>/solutions.php#site-visibility">Site Visibility</a></li>
                    </ul>
                </li>
                <li class="mobile-nav-item">
                    <a href="#" class="mobile-nav-link mobile-dropdown-toggle">Industries</a>
                    <ul class="mobile-dropdown">
                        <li><a href="#">Manufacturing</a></li>
                        <li><a href="#">Automotive</a></li>
                        <li><a href="#">Electronics</a></li>
                        <li><a href="#">Food & Beverage</a></li>
                    </ul>
                </li>
                <li class="mobile-nav-item">
                    <a href="#" class="mobile-nav-link mobile-dropdown-toggle">Resources</a>
                    <ul class="mobile-dropdown">
                        <li><a href="#">Case Studies</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Documentation</a></li>
                        <li><a href="#">Support</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        
        <div class="mobile-actions">
            <a href="#" class="btn btn-primary btn-block">BOOK A DEMO</a>
        </div>
    </div>
    
    <main>
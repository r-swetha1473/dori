<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Get platforms page content from database
$page = getPageContent('platforms');
$heroSection = getSectionContent('platforms', 'hero');
$techSection = getSectionContent('platforms', 'tech');
$featuresSection = getSectionContent('platforms', 'features');
$applicationsSection = getSectionContent('platforms', 'applications');
$developmentSection = getSectionContent('platforms', 'development');

// Get features
$features = getSectionItems('platforms', 'features_items');
$devFeatures = getSectionItems('platforms', 'development_features');

$pageTitle = $page['title'] ?? 'Platforms - Dori';

require_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero platforms-hero" style="background-image: url('<?= $heroSection['image_path'] ?? 'assets/images/platforms-hero.jpg' ?>');">
    <div class="container">
        <div class="hero-content">
            <h1 class="title"><?= $heroSection['title'] ?? 'Seamless Collaboration Zero Disruptions' ?></h1>
            <p class="description"><?= $heroSection['description'] ?? 'Keep your factory by letting your engineers at any skill level find, diagnose, and solve AI computer vision problems before they escalate.' ?></p>
            <div class="cta-buttons">
                <a href="<?php echo SITE_URL?><?= $heroSection['primary_btn_link'] ?? '#' ?>" class="btn btn-primary"><?= $heroSection['primary_btn_text'] ?? 'BOOK A DEMO' ?></a>
            </div>
        </div>
        <div class="alert-box">
            <div class="alert-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="alert-content">
                <strong>Site Visibility</strong>
            </div>
        </div>
    </div>
    <div class="hero-overlay"></div>
</section>

<!-- Tech Section -->
<section class="tech-section">
    <div class="container">
        <h2 class="section-title">We have <span class="highlight">Simplified</span> the Tech</h2>
        <p class="section-description"><?= $techSection['description'] ?? 'Dori AI provides a suite of templated AI computer vision applications across a broad range of manufacturing needs.' ?></p>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="features-grid">
            <?php 
            foreach($features as $index => $feature): 
                $isAlternate = $index % 2 !== 0;
            ?>
            <div class="feature-item <?= $isAlternate ? 'feature-alternate' : '' ?>">
                <div class="feature-content">
                    <h3><?= $feature['title'] ?></h3>
                    <p><?= $feature['description'] ?></p>
                    <a href="<?php echo SITE_URL?><?= $feature['link'] ?? '#' ?>" class="btn btn-secondary">Learn More</a>
                </div>
                <div class="feature-image">
                    <img src="<?= $feature['image_path'] ?>" alt="<?= $feature['title'] ?>">
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Applications Section -->
<section class="applications-section" style="background-color: #264554;">
    <div class="container">
        <h2 class="section-title text-white">One Platform <span class="highlight">Many Applications</span></h2>
        <p class="section-description text-white"><?= $applicationsSection['description'] ?? 'Dori AI provides a suite of tools and technologies that enable enterprises with a full-stack solution to address all their AI computer vision needs.' ?></p>
        
        <div class="applications-grid">
            <div class="application-img">
                <img src="<?= $applicationsSection['image_path'] ?? 'assets/images/applications-dashboard.jpg' ?>" alt="Applications Dashboard">
            </div>
            
            <div class="benefits">
                <?php 
                $benefits = getSectionItems('platforms', 'application_benefits');
                foreach($benefits as $benefit): 
                ?>
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <img src="<?= $benefit['icon_path'] ?>" alt="<?= $benefit['title'] ?>">
                    </div>
                    <h3><?= $benefit['title'] ?></h3>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Development Section -->
<section class="development-section">
    <div class="container">
        <h2 class="section-title">Data-Driven <span class="highlight">AI Development</span></h2>
        <p class="section-description"><?= $developmentSection['description'] ?? 'Extract the business value out of your visual data and turn images and videos into real-time operational insights powered by AI and computer vision without the need for a large data science or engineering team.' ?></p>
        
        <div class="development-features">
            <?php foreach($devFeatures as $feature): ?>
            <div class="dev-feature">
                <div class="feature-icon">
                    <img src="<?= $feature['icon_path'] ?>" alt="<?= $feature['title'] ?>">
                </div>
                <h3><?= $feature['title'] ?></h3>
                <p><?= $feature['description'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
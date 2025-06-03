<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Get homepage content from database
$page = getPageContent('home');
$heroSection = getSectionContent('home', 'hero');
$solutionsSection = getSectionContent('home', 'solutions');
$statsSection = getSectionContent('home', 'stats');
$aiSection = getSectionContent('home', 'ai');
$uniqueSection = getSectionContent('home', 'unique');
$communitySection = getSectionContent('home', 'community');

// Get statistics
$stats = getPageStatistics('home');

// Get testimonials/case studies
$caseStudies = getCaseStudies(1);

$pageTitle = $page['title'] ?? 'Dori - Connected Workforce Platform';

require_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero" style="background-image: url('<?= $heroSection['image_path'] ?? 'assets/images/hero-bg.jpg' ?>');">
    <div class="container">
        <div class="hero-content">
            <h2 class="subtitle"><?= $heroSection['subtitle'] ?? 'THE CONNECTED WORKFORCE PLATFORM' ?></h2>
            <h1 class="title"><?= $heroSection['title'] ?? 'Every minute matters on the floor' ?></h1>
            <p class="description"><?= $heroSection['description'] ?? 'Perfect timing wins. Deploy and defense fast. Don\'t leave your shop floor unprepared or vulnerable to the things you can\'t predict.' ?></p>
            <div class="cta-buttons">
                <a href="<?php echo SITE_URL?><?= $heroSection['primary_btn_link'] ?? '#' ?>" class="btn btn-primary"><?= $heroSection['primary_btn_text'] ?? 'BOOK A DEMO' ?></a>
            </div>
        </div>
    </div>
    <div class="hero-overlay"></div>
</section>

<!-- Solutions Section -->
<section class="solutions">
    <div class="container">
        <h2 class="section-title text-center"><?= $solutionsSection['title'] ?? 'Powerful Solutions for Any Operation' ?></h2>
        
        <div class="solutions-content">
            <div class="solutions-image">
                <img src="<?= $solutionsSection['image_path'] ?? 'assets/images/factory-floor.jpg' ?>" alt="Factory Floor Layout">
            </div>
            <div class="solutions-text">
                <h3><?= $solutionsSection['subtitle'] ?? 'Remove siloes, streamline workflows, boost insights' ?></h3>
                <ul class="solutions-list">
                    <?php 
                    $solutionItems = getSectionItems('home', 'solutions_items');
                    foreach($solutionItems as $item): 
                    ?>
                    <li>
                        <div class="icon"><i class="<?= $item['icon'] ?? 'fas fa-check-circle' ?>"></i></div>
                        <div class="content">
                            <h4><?= $item['title'] ?></h4>
                            <p><?= $item['description'] ?></p>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Trusted By Section -->
<section class="trusted-by">
    <div class="container">
        <h2 class="section-title text-center">Trusted by global manufacturing leaders</h2>
        <div class="logos-container">
            <?php 
            $partners = getPartners();
            foreach($partners as $partner): 
            ?>
            <div class="logo">
                <img src="<?= $partner['logo_path'] ?>" alt="<?= $partner['name'] ?> logo">
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats" style="background-color: #264554;">
    <div class="container">
        <h2 class="section-title text-center text-white"><?= $statsSection['title'] ?? 'SHOW ACTION THAT MATTERS' ?></h2>
        <h3 class="stats-subtitle text-center text-white"><?= $statsSection['subtitle'] ?? 'See ROI in 2 Weeks' ?></h3>
        <p class="text-center text-white"><?= $statsSection['description'] ?? 'Reduced downtime, increased productivity and better quality. Engaged team members are an extra bonus' ?></p>
        
        <div class="stats-container">
            <?php foreach($stats as $stat): ?>
            <div class="stat-item">
                <h3 class="stat-value <?= strpos($stat['stat_value'], '-') === 0 ? 'negative' : 'positive' ?>"><?= $stat['stat_value'] ?></h3>
                <p class="stat-label"><?= $stat['stat_label'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- AI Section -->
<section class="ai-section">
    <div class="container">
        <div class="ai-header">
            <h2 class="section-title"><?= $aiSection['title'] ?? 'Dori AI enables <span>Data-Driven AI</span> decisions with an end-to-end platform' ?></h2>
            <p><?= $aiSection['description'] ?? 'Our AI provides a layer of trust and intelligence that enables enterprises with a full-stack solution to address all their AI computer vision needs.' ?></p>
        </div>
        
        <div class="ai-features">
            <?php 
            $aiFeatures = getSectionItems('home', 'ai_features');
            foreach($aiFeatures as $feature): 
            ?>
            <div class="ai-feature">
                <div class="feature-image">
                    <img src="<?= $feature['image_path'] ?>" alt="<?= $feature['title'] ?>">
                </div>
                <div class="feature-content">
                    <div class="feature-icon">
                        <i class="<?= $feature['icon'] ?? 'fas fa-chart-line' ?>"></i>
                    </div>
                    <h3><?= $feature['title'] ?></h3>
                    <p><?= $feature['description'] ?></p>
                    <a href="<?php echo SITE_URL?><?= $feature['link'] ?? '#' ?>" class="btn btn-text">Learn More</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- What Makes Unique Section -->
<section class="unique-section" style="background-color: #264554;">
    <div class="container">
        <h2 class="section-title text-center text-white"><?= $uniqueSection['title'] ?? 'What Makes Dori Unique?' ?></h2>
        
        <div class="unique-features">
            <?php 
            $uniqueFeatures = getSectionItems('home', 'unique_features');
            foreach($uniqueFeatures as $feature): 
            ?>
            <div class="unique-feature">
                <h3><?= $feature['title'] ?></h3>
                <p><?= $feature['description'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="demo-cta">
            <h3>Schedule a 30-minute online demo</h3>
            <a href="<?php echo SITE_URL?>" class="btn btn-primary">Book a Demo</a>
        </div>
    </div>
</section>

<!-- Continuous Improvement Section -->
<section class="improvement-section">
    <div class="container">
        <h2 class="section-title text-center">Built for continuous improvement</h2>
        <p class="text-center">Keep your workforce connected, empowered, and high-performing.</p>
    </div>
</section>

<!-- Community Stories Section -->
<section class="community-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?= $communitySection['title'] ?? 'Dori User Community Stories' ?></h2>
            <a href="<?php echo SITE_URL?>" class="view-all">View all stories</a>
        </div>
        
        <div class="case-studies">
            <?php foreach($caseStudies as $case): ?>
            <div class="case-study-card">
                <div class="case-image">
                    <img src="<?= $case['image_path'] ?>" alt="<?= $case['title'] ?>">
                </div>
                <div class="case-content">
                    <h3><?= $case['title'] ?></h3>
                    <p><?= $case['excerpt'] ?></p>
                    <a href="<?php echo SITE_URL?>case-study.php?id=<?= $case['id'] ?>" class="btn btn-primary">Read More</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
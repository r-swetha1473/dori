<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Get solutions page content from database
$page = getPageContent('solutions');
$heroSection = getSectionContent('solutions', 'hero');
$platformSection = getSectionContent('solutions', 'platform');
$qualitySection = getSectionContent('solutions', 'quality');
$useCasesSection = getSectionContent('solutions', 'use_cases');
$faqSection = getSectionContent('solutions', 'faq');

// Get statistics
$stats = getPageStatistics('solutions');

// Get FAQs
$faqs = getFAQs('solutions');

// Get related content
$relatedContent = getRelatedContent('solutions', 3);

$pageTitle = $page['title'] ?? 'Solutions - Dori';

require_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero solutions-hero" style="background-image: url('<?= $heroSection['image_path'] ?? 'assets/images/solutions-hero.jpg' ?>');">
    <div class="container">
        <div class="hero-content">
            <h1 class="title"><?= $heroSection['title'] ?? 'Quality You Can Count On' ?></h1>
            <p class="description"><?= $heroSection['description'] ?? 'When equipment starts to safetys at the line, costly downtime begins. Let our computer vision detect potential issues early—before they escalate and act fast.' ?></p>
            <p class="sub-description"><?= $heroSection['sub_description'] ?? 'Less missing problems. More getting things done safely and on time.' ?></p>
            <div class="cta-buttons">
                <a href="<?php echo SITE_URL?><?= $heroSection['primary_btn_link'] ?? '#' ?>" class="btn btn-primary"><?= $heroSection['primary_btn_text'] ?? 'BOOK A DEMO' ?></a>
            </div>
        </div>
        <div class="alert-box">
            <div class="alert-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="alert-content">
                <strong>Alert: Machine Down</strong>
            </div>
        </div>
    </div>
    <div class="hero-overlay"></div>
</section>

<!-- Platform Section -->
<section class="platform-section">
    <div class="container">
        <h2 class="section-title"><?= $platformSection['title'] ?? 'Visual Intelligence Platform' ?> <span>for Enterprise</span></h2>
        
        <div class="features-grid">
            <?php 
            $platformFeatures = getSectionItems('solutions', 'platform_features');
            foreach($platformFeatures as $index => $feature): 
                $isAlternate = $index % 2 !== 0;
            ?>
            <div class="feature-item <?= $isAlternate ? 'feature-alternate' : '' ?>">
                <div class="feature-content">
                    <h3><?= $feature['title'] ?></h3>
                    <p><?= $feature['description'] ?></p>
                    <a href="<?php echo SITE_URL?><?= $feature['link'] ?? '#' ?>" class="btn btn-secondary">Learn More</a>
                </div>
                <div class="feature-image">
                    <img src="<?php echo SITE_URL?><?= $feature['image_path'] ?>" alt="<?= $feature['title'] ?>">
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Quality Stats Section -->
<section class="quality-stats">
    <div class="container">
        <h2 class="section-title text-center"><?= $qualitySection['title'] ?? 'See quality improve within 2 weeks' ?></h2>
        <p class="text-center"><?= $qualitySection['description'] ?? 'Youll see reduced downtime, increased productivity, better quality, and engaged team members.' ?></p>
        
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

<!-- Use Cases Section -->
<section class="use-cases">
    <div class="container">
        <h2 class="section-title"><?= $useCasesSection['title'] ?? 'Dori Key Use Cases' ?></h2>
        <p><?= $useCasesSection['description'] ?? 'Dori AI enables enterprises with computer vision solutions across a range of use cases and industries.' ?></p>
        
        <div class="use-cases-grid">
            <?php 
            $useCases = getSectionItems('solutions', 'use_cases_items');
            foreach($useCases as $useCase): 
            ?>
            <div class="use-case-card">
                <div class="use-case-image">
                    <img src="<?= $useCase['image_path'] ?>" alt="<?= $useCase['title'] ?>">
                </div>
                <div class="use-case-content">
                    <h3><?= $useCase['title'] ?></h3>
                    <p><?= $useCase['description'] ?></p>
                    <a href="<?php echo SITE_URL?><?= $useCase['link'] ?? '#' ?>" class="link">Learn More</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section">
    <div class="container">
        <h2 class="section-title"><?= $faqSection['title'] ?? 'Frequently asked questions' ?></h2>
        <p><?= $faqSection['description'] ?? 'Get answers to your most pressing questions about how L2L can improve quality, reduce defects, eliminate scrap, and ensure compliance across your operations.' ?></p>
        
        <div class="accordion">
            <?php foreach($faqs as $faq): ?>
            <div class="accordion-item">
                <div class="accordion-header">
                    <h3><?= $faq['question'] ?></h3>
                    <span class="accordion-icon"></span>
                </div>
                <div class="accordion-content">
                    <p><?= $faq['answer'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Related Content Section -->
<section class="related-content">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Related content</h2>
            <div class="content-filter">
                <button class="filter-btn active" data-filter="all">All Resources</button>
                <button class="filter-btn" data-filter="ebooks">E-Books</button>
                <button class="filter-btn" data-filter="guides">Guides</button>
            </div>
        </div>
        
        <div class="content-grid">
            <?php foreach($relatedContent as $content): ?>
            <div class="content-card" data-type="<?= $content['type'] ?>">
                <div class="content-image">
                    <img src="<?= $content['image_path'] ?>" alt="<?= $content['title'] ?>">
                    <div class="content-tag"><?= strtoupper($content['type']) ?></div>
                </div>
                <div class="content-body">
                    <h3><?= $content['title'] ?></h3>
                    <div class="content-meta">
                        <span class="category"><?= $content['category'] ?></span>
                        <span class="separator">•</span>
                        <span class="read-time"><?= $content['read_time'] ?> MIN READ</span>
                    </div>
                    <a href="<?php echo SITE_URL?><?= $content['link'] ?>" class="btn btn-text">LEARN MORE</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
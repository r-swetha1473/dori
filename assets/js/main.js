// Main JavaScript for Dori Website

document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    const mobileMenuClose = document.querySelector('.mobile-menu-close');
    const mobileDropdownToggles = document.querySelectorAll('.mobile-dropdown-toggle');
    
    if (mobileMenuToggle && mobileMenu) {
        mobileMenuToggle.addEventListener('click', function() {
            mobileMenu.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }
    
    if (mobileMenuClose && mobileMenu) {
        mobileMenuClose.addEventListener('click', function() {
            mobileMenu.classList.remove('active');
            document.body.style.overflow = '';
        });
    }
    
    // Mobile dropdown toggles
    mobileDropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const dropdown = this.nextElementSibling;
            
            if (dropdown.classList.contains('active')) {
                dropdown.classList.remove('active');
                this.classList.remove('active');
            } else {
                // Close all other dropdowns
                document.querySelectorAll('.mobile-dropdown.active').forEach(activeDropdown => {
                    if (activeDropdown !== dropdown) {
                        activeDropdown.classList.remove('active');
                        activeDropdown.previousElementSibling.classList.remove('active');
                    }
                });
                
                dropdown.classList.add('active');
                this.classList.add('active');
            }
        });
    });
    
    // Header scroll effect
    const header = document.querySelector('.site-header');
    
    if (header) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
                header.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
            } else {
                header.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
                header.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
            }
        });
    }
    
    // FAQ Accordion
    const accordionItems = document.querySelectorAll('.accordion-item');
    
    accordionItems.forEach(item => {
        const header = item.querySelector('.accordion-header');
        
        if (header) {
            header.addEventListener('click', function() {
                const isActive = item.classList.contains('active');
                
                // Close all accordion items
                accordionItems.forEach(accItem => {
                    accItem.classList.remove('active');
                });
                
                // If the clicked item wasn't active, make it active
                if (!isActive) {
                    item.classList.add('active');
                }
            });
        }
    });
    
    // Content Filter
    const filterButtons = document.querySelectorAll('.filter-btn');
    const contentCards = document.querySelectorAll('.content-card');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Remove active class from all buttons
            filterButtons.forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Filter content cards
            contentCards.forEach(card => {
                if (filter === 'all' || card.getAttribute('data-type') === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
    
    // Statistics counter animation
    const stats = document.querySelectorAll('.stat-value');
    
    function animateStats() {
        stats.forEach(stat => {
            const value = stat.innerText;
            const targetValue = parseFloat(value.replace(/[^\d.-]/g, ''));
            const prefix = value.includes('-') ? '-' : '';
            const suffix = value.replace(/[\d.-]/g, '');
            
            let startValue = 0;
            const duration = 1500;
            const increment = targetValue / (duration / 16);
            
            function updateCount() {
                if (Math.abs(startValue) < Math.abs(targetValue)) {
                    startValue += increment;
                    
                    if (Math.abs(startValue) > Math.abs(targetValue)) {
                        startValue = targetValue;
                    }
                    
                    let displayValue;
                    
                    if (Math.abs(startValue) >= 1) {
                        displayValue = Math.round(startValue);
                    } else {
                        displayValue = startValue.toFixed(1);
                    }
                    
                    stat.innerText = `${prefix}${displayValue}${suffix}`;
                    
                    if (Math.abs(startValue) < Math.abs(targetValue)) {
                        requestAnimationFrame(updateCount);
                    }
                }
            }
            
            updateCount();
        });
    }
    
    // Check if stats are in viewport and animate
    const statsSection = document.querySelector('.stats');
    
    if (statsSection) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateStats();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        observer.observe(statsSection);
    }
    
    // Animate elements on scroll
    const animatedElements = document.querySelectorAll('.animate-fadeIn, .animate-children');
    
    if (animatedElements.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        animatedElements.forEach(el => {
            observer.observe(el);
        });
    }
});
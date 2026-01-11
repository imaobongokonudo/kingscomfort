/**
 * Kings Comfort Luxury - Animations JavaScript
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        KCLAnimations.init();
    });

    const KCLAnimations = {
        init: function() {
            this.initScrollAnimations();
            this.initSectionIndicator();
            this.initCurrentSection();
            this.initParallax();
        },

        // Scroll-triggered Animations
        initScrollAnimations: function() {
            const animatedElements = document.querySelectorAll('.kcl-animate-fade-up, .kcl-animate-fade-in, .kcl-animate-scale, .kcl-animate-slide-left, .kcl-animate-slide-right');

            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('animated');
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                });

                animatedElements.forEach(el => observer.observe(el));
            } else {
                // Fallback for older browsers
                animatedElements.forEach(el => el.classList.add('animated'));
            }
        },

        // Section Indicator Dots
        initSectionIndicator: function() {
            const sections = document.querySelectorAll('.kcl-section[data-section]');
            
            if (sections.length === 0) return;

            // Create indicator container
            const indicatorHtml = '<div class="kcl-section-indicator"></div>';
            $('body').append(indicatorHtml);

            const $indicator = $('.kcl-section-indicator');

            sections.forEach(section => {
                const sectionName = section.dataset.section;
                const capitalizedName = sectionName.charAt(0).toUpperCase() + sectionName.slice(1);
                $indicator.append('<div class="kcl-section-dot" data-section="' + capitalizedName + '" data-target="' + sectionName + '"></div>');
            });

            // Click to scroll
            $('.kcl-section-dot').on('click', function() {
                const target = $(this).data('target');
                const $section = $('[data-section="' + target + '"]');
                if ($section.length) {
                    $('html, body').animate({
                        scrollTop: $section.offset().top - 80
                    }, 600);
                }
            });

            // Update active dot on scroll
            $(window).on('scroll', function() {
                const scrollPos = $(this).scrollTop() + 200;

                sections.forEach(section => {
                    const sectionTop = $(section).offset().top;
                    const sectionBottom = sectionTop + $(section).outerHeight();
                    const sectionName = section.dataset.section;

                    if (scrollPos >= sectionTop && scrollPos < sectionBottom) {
                        $('.kcl-section-dot').removeClass('active');
                        $('.kcl-section-dot[data-target="' + sectionName + '"]').addClass('active');
                    }
                });
            });
        },

        // Current Section Display
        initCurrentSection: function() {
            const sections = document.querySelectorAll('.kcl-section[data-section]');
            
            if (sections.length === 0) return;

            // Create current section display
            $('body').append('<div class="kcl-current-section"></div>');
            const $currentSection = $('.kcl-current-section');

            $(window).on('scroll', function() {
                const scrollPos = $(this).scrollTop();

                if (scrollPos > 300) {
                    sections.forEach(section => {
                        const sectionTop = $(section).offset().top - 150;
                        const sectionBottom = sectionTop + $(section).outerHeight();
                        const sectionName = section.dataset.section;

                        if (scrollPos >= sectionTop && scrollPos < sectionBottom) {
                            const capitalizedName = sectionName.charAt(0).toUpperCase() + sectionName.slice(1);
                            $currentSection.text(capitalizedName).addClass('visible');
                        }
                    });
                } else {
                    $currentSection.removeClass('visible');
                }
            });
        },

        // Parallax Effect
        initParallax: function() {
            const $hero = $('.kcl-hero');

            if ($hero.length === 0) return;

            $(window).on('scroll', function() {
                const scrollPos = $(this).scrollTop();
                const parallaxSpeed = 0.5;

                $hero.css('background-position', 'center ' + (scrollPos * parallaxSpeed) + 'px');
            });
        }
    };

    window.KCLAnimations = KCLAnimations;

})(jQuery);

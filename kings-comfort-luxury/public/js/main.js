/**
 * Kings Comfort Luxury - Main JavaScript
 * Optimized for blazing fast performance
 */

(function($) {
    'use strict';

    // DOM Ready - using native DOMContentLoaded for speed
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            KCL.init();
        });
    } else {
        KCL.init();
    }

    // Main Object
    const KCL = {
        scrollThrottle: null,
        
        init: function() {
            this.initLazyLoading();
            this.initHeader();
            this.initScrollTop();
            this.initMobileNav();
            this.initSmoothScroll();
            this.initFormValidation();
            this.initQuickBookingForm();
            this.initProfileLookup();
            this.initFilters();
            this.preloadCriticalAssets();
        },
        
        // Preload critical assets for faster subsequent navigation
        preloadCriticalAssets: function() {
            // Preload important pages
            const pagesToPreload = ['/apartments/', '/booking/', '/about/'];
            pagesToPreload.forEach(function(page) {
                const link = document.createElement('link');
                link.rel = 'prefetch';
                link.href = kcl_ajax.home_url + page.substring(1);
                document.head.appendChild(link);
            });
        },
        
        // Lazy loading for images
        initLazyLoading: function() {
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            if (img.dataset.src) {
                                img.src = img.dataset.src;
                                img.classList.add('loaded');
                            }
                            observer.unobserve(img);
                        }
                    });
                }, {
                    rootMargin: '100px 0px'
                });
                
                document.querySelectorAll('img[data-src]').forEach(function(img) {
                    imageObserver.observe(img);
                });
            }
        },

        // Sticky Header with throttled scroll
        initHeader: function() {
            const $header = $('#kcl-header');
            let lastScroll = 0;
            let ticking = false;

            $(window).on('scroll', function() {
                const currentScroll = window.scrollY;
                
                if (!ticking) {
                    window.requestAnimationFrame(function() {
                        if (currentScroll > 100) {
                            $header.addClass('scrolled');
                        } else {
                            $header.removeClass('scrolled');
                        }
                        lastScroll = currentScroll;
                        ticking = false;
                    });
                    ticking = true;
                }
            });
        },

        // Scroll to Top with Progress Animation - Optimized
        initScrollTop: function() {
            const $scrollTop = $('#kcl-scroll-top');
            const progressBar = document.querySelector('.kcl-progress-bar');
            const progressFill = document.querySelector('.kcl-progress-fill');
            let ticking = false;

            $(window).on('scroll', function() {
                if (!ticking) {
                    window.requestAnimationFrame(function() {
                        const scrollTop = window.scrollY;
                        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
                        const scrollPercent = Math.min((scrollTop / docHeight) * 100, 100);

                        // Show/hide button
                        if (scrollTop > 300) {
                            $scrollTop.addClass('visible');
                        } else {
                            $scrollTop.removeClass('visible');
                        }

                        // Update progress bar
                        const perimeter = 160;
                        const dashOffset = perimeter - (scrollPercent / 100 * perimeter);
                        if (progressBar) {
                            progressBar.style.strokeDasharray = perimeter;
                            progressBar.style.strokeDashoffset = dashOffset;
                        }
                        
                        // Update fill effect
                        if (progressFill) {
                            progressFill.style.height = scrollPercent + '%';
                        }
                        
                        ticking = false;
                    });
                    ticking = true;
                }
            });

            $scrollTop.on('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        },

        // Mobile Navigation
        initMobileNav: function() {
            const $hamburger = $('#kcl-hamburger');
            const $nav = $('#kcl-nav');

            $hamburger.on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).toggleClass('active');
                $nav.toggleClass('active');
            });

            // Close on link click
            $('.kcl-nav-link').on('click', function() {
                $hamburger.removeClass('active');
                $nav.removeClass('active');
            });

            // Close on outside click
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.kcl-header').length) {
                    $hamburger.removeClass('active');
                    $nav.removeClass('active');
                }
            });
        },

        // Smooth Scroll - using native scroll for better performance
        initSmoothScroll: function() {
            $('a[href^="#"]').on('click', function(e) {
                const targetId = this.getAttribute('href');
                if (targetId !== '#') {
                    const target = document.querySelector(targetId);
                    if (target) {
                        e.preventDefault();
                        const headerOffset = 80;
                        const elementPosition = target.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                        
                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        },

        // Form Validation
        initFormValidation: function() {
            // Phone number formatting
            $('input[type="tel"]').on('input', function() {
                let value = $(this).val().replace(/\D/g, '');
                if (value.length > 0 && !value.startsWith('234')) {
                    if (value.startsWith('0')) {
                        value = '234' + value.substring(1);
                    }
                }
                $(this).val(value);
            });

            // Date validation
            $('#check_out, #quick_check_out').on('change', function() {
                const checkIn = $('#check_in, #quick_check_in').val();
                const checkOut = $(this).val();

                if (checkIn && checkOut && new Date(checkOut) <= new Date(checkIn)) {
                    alert('Check-out date must be after check-in date.');
                    $(this).val('');
                }
            });
        },

        // Quick Booking Form
        initQuickBookingForm: function() {
            $('#kcl-quick-booking-form').on('submit', function(e) {
                e.preventDefault();
                const checkIn = $('#quick_check_in').val();
                const checkOut = $('#quick_check_out').val();
                const guests = $('#quick_guests').val();

                window.location.href = kcl_ajax.booking_url + 
                    '?check_in=' + checkIn + '&check_out=' + checkOut + '&guests=' + guests;
            });
        },

        // Profile Lookup
        initProfileLookup: function() {
            $('#kcl-profile-lookup-form').on('submit', function(e) {
                e.preventDefault();
                const email = $('#lookup_email').val();

                $.ajax({
                    url: kcl_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'kcl_get_profile',
                        nonce: kcl_ajax.nonce,
                        email: email
                    },
                    beforeSend: function() {
                        $('#kcl-profile-lookup-form button').prop('disabled', true).text('Loading...');
                    },
                    success: function(response) {
                        $('#kcl-profile-lookup-form button').prop('disabled', false).text('View Profile');

                        if (response.success) {
                            const profile = response.data;
                            $('#profile-name').text(profile.name);
                            $('#profile-email').text(profile.email);
                            $('#profile-points').text(profile.points.toLocaleString());
                            $('#profile-tier').html('<i class="fas fa-award"></i> ' + profile.tier.charAt(0).toUpperCase() + profile.tier.slice(1) + ' Member');

                            // Build bookings list
                            let bookingsHtml = '';
                            if (profile.bookings.length > 0) {
                                profile.bookings.forEach(function(booking) {
                                    bookingsHtml += '<div class="kcl-booking-item" style="padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px;">';
                                    bookingsHtml += '<div><strong>' + booking.booking_id + '</strong><br><small>' + booking.apartment + '</small></div>';
                                    bookingsHtml += '<div>' + booking.check_in + ' - ' + booking.check_out + '</div>';
                                    bookingsHtml += '<div>₦' + parseInt(booking.total).toLocaleString() + '</div>';
                                    bookingsHtml += '<div><span class="kcl-status kcl-status-' + booking.status + '" style="padding: 4px 12px; border-radius: 20px; font-size: 12px; background: rgba(226,195,105,0.1); color: #E2C369;">' + booking.status.charAt(0).toUpperCase() + booking.status.slice(1) + '</span></div>';
                                    bookingsHtml += '</div>';
                                });
                            } else {
                                bookingsHtml = '<p style="text-align: center; color: rgba(255,255,255,0.5);">No bookings found.</p>';
                            }
                            $('#profile-bookings-list').html(bookingsHtml);
                            $('#kcl-profile-content').slideDown();
                        } else {
                            alert(response.data.message);
                        }
                    },
                    error: function() {
                        $('#kcl-profile-lookup-form button').prop('disabled', false).text('View Profile');
                        alert('An error occurred. Please try again.');
                    }
                });
            });
        },

        // Apartment Filters
        initFilters: function() {
            $('#kcl-apply-filters').on('click', function() {
                const type = $('#kcl-filter-type').val();
                const beds = $('#kcl-filter-beds').val();
                const priceRange = $('#kcl-filter-price').val();

                $.ajax({
                    url: kcl_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'kcl_filter_apartments',
                        nonce: kcl_ajax.nonce,
                        type: type,
                        beds: beds,
                        price_range: priceRange
                    },
                    beforeSend: function() {
                        $('#kcl-apartments-list').html('<div class="kcl-loading" style="margin: 50px auto;"></div>');
                    },
                    success: function(response) {
                        if (response.success && response.data.length > 0) {
                            let html = '';
                            response.data.forEach(function(apt) {
                                html += '<div class="kcl-apartment-card kcl-glass-card">';
                                html += '<div class="kcl-card-image">';
                                if (apt.image) {
                                    html += '<img src="' + apt.image + '" alt="' + apt.title + '" loading="lazy">';
                                } else {
                                    html += '<div class="kcl-image-placeholder"><i class="fas fa-image"></i></div>';
                                }
                                html += '</div>';
                                html += '<div class="kcl-card-content">';
                                html += '<h3>' + apt.title + '</h3>';
                                html += '<div class="kcl-card-meta">';
                                html += '<span><i class="fas fa-bed"></i> ' + (apt.bedrooms || '2') + ' Beds</span>';
                                html += '<span><i class="fas fa-bath"></i> ' + (apt.bathrooms || '1') + ' Baths</span>';
                                html += '<span><i class="fas fa-expand"></i> ' + (apt.size || '75') + ' sqm</span>';
                                html += '</div>';
                                html += '<div class="kcl-card-footer">';
                                html += '<div class="kcl-card-price">₦' + parseInt(apt.price || 150000).toLocaleString() + '<small>/night</small></div>';
                                html += '<a href="' + kcl_ajax.booking_url + '?apartment=' + apt.id + '" class="kcl-btn kcl-btn-primary">Book Now</a>';
                                html += '</div></div></div>';
                            });
                            $('#kcl-apartments-list').html(html);
                        } else {
                            $('#kcl-apartments-list').html('<div class="kcl-no-apartments"><i class="fas fa-building"></i><p>No apartments match your criteria.</p></div>');
                        }
                    }
                });
            });
        }
    };

    // Make KCL available globally
    window.KCL = KCL;

})(jQuery);

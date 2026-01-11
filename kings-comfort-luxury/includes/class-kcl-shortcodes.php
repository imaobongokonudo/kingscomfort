<?php
/**
 * Shortcodes Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class KCL_Shortcodes {
    
    public static function init() {
        add_shortcode('kcl_homepage', array(__CLASS__, 'homepage'));
        add_shortcode('kcl_header', array(__CLASS__, 'header'));
        add_shortcode('kcl_footer', array(__CLASS__, 'footer'));
        add_shortcode('kcl_apartments', array(__CLASS__, 'apartments'));
        add_shortcode('kcl_amenities', array(__CLASS__, 'amenities'));
        add_shortcode('kcl_booking_form', array(__CLASS__, 'booking_form'));
        add_shortcode('kcl_concierge', array(__CLASS__, 'concierge'));
        add_shortcode('kcl_loyalty', array(__CLASS__, 'loyalty'));
        add_shortcode('kcl_guest_profile', array(__CLASS__, 'guest_profile'));
        add_shortcode('kcl_about', array(__CLASS__, 'about'));
    }
    
    public static function header($atts) {
        ob_start();
        ?>
        <header class="kcl-header" id="kcl-header">
            <div class="kcl-container">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="kcl-logo">
                    <img src="<?php echo esc_url(kcl_get_logo_url('png')); ?>" alt="Kings Comfort Luxury" class="kcl-logo-img kcl-sparkle">
                </a>
                <nav class="kcl-nav" id="kcl-nav">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="kcl-nav-link">Home</a>
                    <a href="<?php echo esc_url(home_url('/apartments/')); ?>" class="kcl-nav-link">Apartments</a>
                    <a href="<?php echo esc_url(home_url('/amenities/')); ?>" class="kcl-nav-link">Amenities</a>
                    <a href="<?php echo esc_url(home_url('/concierge/')); ?>" class="kcl-nav-link">Concierge</a>
                    <a href="<?php echo esc_url(home_url('/loyalty/')); ?>" class="kcl-nav-link">Loyalty</a>
                </nav>
                <a href="<?php echo esc_url(home_url('/booking/')); ?>" class="kcl-btn kcl-btn-primary">Book Now</a>
                <button class="kcl-hamburger" id="kcl-hamburger" aria-label="Toggle menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </header>
        <?php
        return ob_get_clean();
    }
    
    public static function footer($atts) {
        ob_start();
        ?>
        <footer class="kcl-footer">
            <div class="kcl-container">
                <div class="kcl-footer-grid">
                    <div class="kcl-footer-col">
                        <img src="<?php echo esc_url(kcl_get_logo_url('png')); ?>" alt="Kings Comfort Luxury" class="kcl-footer-logo">
                        <p>Experience luxury living in the heart of Abuja. Your comfort is our priority.</p>
                    </div>
                    <div class="kcl-footer-col">
                        <h4>Quick Links</h4>
                        <a href="<?php echo esc_url(home_url('/apartments/')); ?>">Apartments</a>
                        <a href="<?php echo esc_url(home_url('/amenities/')); ?>">Amenities</a>
                        <a href="<?php echo esc_url(home_url('/booking/')); ?>">Book Now</a>
                        <a href="<?php echo esc_url(home_url('/concierge/')); ?>">Concierge</a>
                        <a href="<?php echo esc_url(home_url('/loyalty/')); ?>">Loyalty Program</a>
                    </div>
                    <div class="kcl-footer-col">
                        <h4>Contact Info</h4>
                        <p><i class="fas fa-map-marker-alt"></i> Kabusa, Abuja, Nigeria</p>
                        <p><i class="fas fa-phone"></i> +234 803 710 0768</p>
                        <p><i class="fas fa-envelope"></i> info@kingscomfortluxury.com</p>
                    </div>
                    <div class="kcl-footer-col">
                        <h4>Follow Us</h4>
                        <div class="kcl-social-links">
                            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" aria-label="X"><i class="fab fa-x-twitter"></i></a>
                            <a href="#" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                        </div>
                    </div>
                </div>
                <div class="kcl-footer-bottom">
                    <p>&copy; <?php echo esc_html(gmdate('Y')); ?> Kings Comfort Luxury. All rights reserved.</p>
                </div>
            </div>
        </footer>
        <?php
        return ob_get_clean();
    }
    
    public static function homepage($atts) {
        ob_start();
        ?>
        <!-- Hero Section -->
        <section class="kcl-hero kcl-section" data-section="hero">
            <div class="kcl-hero-overlay"></div>
            <div class="kcl-hero-content">
                <h1 class="kcl-animate-fade-up">Experience <span class="kcl-gold">Luxury Living</span></h1>
                <p class="kcl-animate-fade-up">Premium service apartments in the heart of Abuja</p>
                <div class="kcl-hero-btns kcl-animate-fade-up">
                    <a href="<?php echo esc_url(home_url('/apartments/')); ?>" class="kcl-btn kcl-btn-primary">Explore Apartments</a>
                    <a href="<?php echo esc_url(home_url('/booking/')); ?>" class="kcl-btn kcl-btn-outline">Book Now</a>
                </div>
            </div>
            <div class="kcl-hero-image">
                <div class="kcl-image-placeholder"><i class="fas fa-building"></i></div>
            </div>
        </section>

        <!-- Marquee Section -->
        <section class="kcl-marquee">
            <div class="kcl-marquee-track">
                <span>Business Executives</span><span class="kcl-gold">★</span>
                <span>Tourists</span><span class="kcl-gold">★</span>
                <span>Families</span><span class="kcl-gold">★</span>
                <span>Couples</span><span class="kcl-gold">★</span>
                <span>Corporate Retreats</span><span class="kcl-gold">★</span>
                <span>Diplomats</span><span class="kcl-gold">★</span>
                <span>Celebrities</span><span class="kcl-gold">★</span>
                <span>Business Executives</span><span class="kcl-gold">★</span>
                <span>Tourists</span><span class="kcl-gold">★</span>
                <span>Families</span><span class="kcl-gold">★</span>
            </div>
        </section>

        <!-- Luxury Apartments Section -->
        <section class="kcl-section kcl-apartments-section" data-section="apartments">
            <div class="kcl-container">
                <h2 class="kcl-section-title kcl-animate-fade-up"><span class="kcl-gold">Luxury</span> Apartments</h2>
                <p class="kcl-section-subtitle kcl-animate-fade-up">Choose from our exquisite collection</p>
                <div class="kcl-apartments-grid">
                    <?php
                    $apartments = new WP_Query(array(
                        'post_type' => 'kcl_apartment',
                        'posts_per_page' => 6,
                        'meta_query' => array(
                            array(
                                'key' => '_kcl_featured',
                                'value' => '1',
                                'compare' => '='
                            )
                        )
                    ));
                    if ($apartments->have_posts()) :
                        while ($apartments->have_posts()) : $apartments->the_post();
                            $price = get_post_meta(get_the_ID(), '_kcl_price_per_night', true);
                            $bedrooms = get_post_meta(get_the_ID(), '_kcl_bedrooms', true);
                            $bathrooms = get_post_meta(get_the_ID(), '_kcl_bathrooms', true);
                            ?>
                            <div class="kcl-apartment-card kcl-glass-card kcl-animate-fade-up">
                                <div class="kcl-card-image">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('large'); ?>
                                    <?php else : ?>
                                        <div class="kcl-image-placeholder"><i class="fas fa-image"></i></div>
                                    <?php endif; ?>
                                </div>
                                <div class="kcl-card-content">
                                    <h3><?php the_title(); ?></h3>
                                    <div class="kcl-card-meta">
                                        <span><i class="fas fa-bed"></i> <?php echo esc_html($bedrooms ?: '2'); ?> Beds</span>
                                        <span><i class="fas fa-bath"></i> <?php echo esc_html($bathrooms ?: '1'); ?> Baths</span>
                                    </div>
                                    <div class="kcl-card-price"><?php echo esc_html(kcl_format_price($price ?: 100000)); ?><small>/night</small></div>
                                    <a href="<?php the_permalink(); ?>" class="kcl-btn kcl-btn-primary">View Details</a>
                                </div>
                            </div>
                        <?php endwhile; wp_reset_postdata();
                    else : ?>
                        <div class="kcl-apartment-card kcl-glass-card kcl-animate-fade-up">
                            <div class="kcl-card-image"><div class="kcl-image-placeholder"><i class="fas fa-image"></i></div></div>
                            <div class="kcl-card-content">
                                <h3>Premium Suite</h3>
                                <div class="kcl-card-meta"><span><i class="fas fa-bed"></i> 2 Beds</span><span><i class="fas fa-bath"></i> 2 Baths</span></div>
                                <div class="kcl-card-price">₦150,000<small>/night</small></div>
                                <a href="<?php echo esc_url(home_url('/booking/')); ?>" class="kcl-btn kcl-btn-primary">Book Now</a>
                            </div>
                        </div>
                        <div class="kcl-apartment-card kcl-glass-card kcl-animate-fade-up">
                            <div class="kcl-card-image"><div class="kcl-image-placeholder"><i class="fas fa-image"></i></div></div>
                            <div class="kcl-card-content">
                                <h3>Executive Suite</h3>
                                <div class="kcl-card-meta"><span><i class="fas fa-bed"></i> 3 Beds</span><span><i class="fas fa-bath"></i> 2 Baths</span></div>
                                <div class="kcl-card-price">₦250,000<small>/night</small></div>
                                <a href="<?php echo esc_url(home_url('/booking/')); ?>" class="kcl-btn kcl-btn-primary">Book Now</a>
                            </div>
                        </div>
                        <div class="kcl-apartment-card kcl-glass-card kcl-animate-fade-up">
                            <div class="kcl-card-image"><div class="kcl-image-placeholder"><i class="fas fa-image"></i></div></div>
                            <div class="kcl-card-content">
                                <h3>Royal Penthouse</h3>
                                <div class="kcl-card-meta"><span><i class="fas fa-bed"></i> 4 Beds</span><span><i class="fas fa-bath"></i> 3 Baths</span></div>
                                <div class="kcl-card-price">₦450,000<small>/night</small></div>
                                <a href="<?php echo esc_url(home_url('/booking/')); ?>" class="kcl-btn kcl-btn-primary">Book Now</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="kcl-section-cta">
                    <a href="<?php echo esc_url(home_url('/apartments/')); ?>" class="kcl-btn kcl-btn-outline">View All Apartments</a>
                </div>
            </div>
        </section>

        <!-- Amenities Section -->
        <section class="kcl-section kcl-amenities-section" data-section="amenities">
            <div class="kcl-container">
                <h2 class="kcl-section-title kcl-animate-fade-up">World-Class <span class="kcl-gold">Amenities</span></h2>
                <p class="kcl-section-subtitle kcl-animate-fade-up">Everything you need for a luxurious stay</p>
                <div class="kcl-amenities-grid">
                    <div class="kcl-amenity-item kcl-glass-card kcl-animate-fade-up"><i class="fas fa-wifi"></i><span>High-Speed WiFi</span></div>
                    <div class="kcl-amenity-item kcl-glass-card kcl-animate-fade-up"><i class="fas fa-swimming-pool"></i><span>Swimming Pool</span></div>
                    <div class="kcl-amenity-item kcl-glass-card kcl-animate-fade-up"><i class="fas fa-dumbbell"></i><span>Fitness Center</span></div>
                    <div class="kcl-amenity-item kcl-glass-card kcl-animate-fade-up"><i class="fas fa-parking"></i><span>Free Parking</span></div>
                    <div class="kcl-amenity-item kcl-glass-card kcl-animate-fade-up"><i class="fas fa-concierge-bell"></i><span>24/7 Concierge</span></div>
                    <div class="kcl-amenity-item kcl-glass-card kcl-animate-fade-up"><i class="fas fa-utensils"></i><span>Fine Dining</span></div>
                    <div class="kcl-amenity-item kcl-glass-card kcl-animate-fade-up"><i class="fas fa-spa"></i><span>Spa & Wellness</span></div>
                    <div class="kcl-amenity-item kcl-glass-card kcl-animate-fade-up"><i class="fas fa-shield-alt"></i><span>24/7 Security</span></div>
                </div>
            </div>
        </section>

        <!-- Booking Section -->
        <section class="kcl-section kcl-booking-section" data-section="booking">
            <div class="kcl-container">
                <h2 class="kcl-section-title kcl-animate-fade-up">Book Your <span class="kcl-gold">Stay</span></h2>
                <div class="kcl-booking-form-container kcl-glass-card kcl-animate-fade-up">
                    <form id="kcl-quick-booking-form" class="kcl-quick-booking-form">
                        <div class="kcl-form-row">
                            <div class="kcl-form-group">
                                <label for="quick_check_in">Check In</label>
                                <input type="date" id="quick_check_in" name="check_in" required min="<?php echo esc_attr(gmdate('Y-m-d')); ?>">
                            </div>
                            <div class="kcl-form-group">
                                <label for="quick_check_out">Check Out</label>
                                <input type="date" id="quick_check_out" name="check_out" required>
                            </div>
                            <div class="kcl-form-group">
                                <label for="quick_guests">Guests</label>
                                <select id="quick_guests" name="guests">
                                    <option value="1">1 Guest</option>
                                    <option value="2">2 Guests</option>
                                    <option value="3">3 Guests</option>
                                    <option value="4">4+ Guests</option>
                                </select>
                            </div>
                            <button type="submit" class="kcl-btn kcl-btn-primary">Check Availability</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Reviews Section -->
        <section class="kcl-section kcl-reviews-section" data-section="reviews">
            <div class="kcl-container">
                <h2 class="kcl-section-title kcl-animate-fade-up">Guest <span class="kcl-gold">Reviews</span></h2>
                <div class="kcl-reviews-grid">
                    <div class="kcl-review-card kcl-glass-card kcl-animate-fade-up">
                        <div class="kcl-review-stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                        <p>"Absolutely stunning apartments with world-class amenities. The staff was incredibly attentive."</p>
                        <div class="kcl-review-author">- Adaeze O.</div>
                    </div>
                    <div class="kcl-review-card kcl-glass-card kcl-animate-fade-up">
                        <div class="kcl-review-stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                        <p>"Best service apartment in Abuja. Perfect for business travelers looking for comfort."</p>
                        <div class="kcl-review-author">- Chukwuemeka I.</div>
                    </div>
                    <div class="kcl-review-card kcl-glass-card kcl-animate-fade-up">
                        <div class="kcl-review-stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i></div>
                        <p>"The concierge service exceeded our expectations. Will definitely return!"</p>
                        <div class="kcl-review-author">- Ibrahim M.</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Map Section -->
        <section class="kcl-section kcl-map-section" data-section="location">
            <div class="kcl-container">
                <h2 class="kcl-section-title kcl-animate-fade-up">Our <span class="kcl-gold">Location</span></h2>
                <p class="kcl-section-subtitle kcl-animate-fade-up">Kabusa, Abuja, Nigeria</p>
                <div class="kcl-map-container kcl-glass-card kcl-animate-fade-up">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15762.262736!2d7.4051!3d9.0579!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x104e0ba5a0b0b0b5%3A0x0!2sKabusa%2C%20Abuja!5e0!3m2!1sen!2sng!4v1600000000000!5m2!1sen!2sng" width="100%" height="400" style="border:0;border-radius:20px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </section>

        <!-- Concierge Section -->
        <section class="kcl-section kcl-concierge-section" data-section="concierge">
            <div class="kcl-container">
                <h2 class="kcl-section-title kcl-animate-fade-up">Concierge <span class="kcl-gold">Services</span></h2>
                <p class="kcl-section-subtitle kcl-animate-fade-up">High-end services tailored for you</p>
                <div class="kcl-services-grid">
                    <div class="kcl-service-card kcl-glass-card kcl-animate-fade-up">
                        <i class="fas fa-car"></i>
                        <h3>Airport Transfer</h3>
                        <p>Luxury vehicle pickup from Nnamdi Azikiwe International Airport</p>
                    </div>
                    <div class="kcl-service-card kcl-glass-card kcl-animate-fade-up">
                        <i class="fas fa-utensils"></i>
                        <h3>Private Chef</h3>
                        <p>Personal chef service with customized menu options</p>
                    </div>
                    <div class="kcl-service-card kcl-glass-card kcl-animate-fade-up">
                        <i class="fas fa-calendar-alt"></i>
                        <h3>Event Planning</h3>
                        <p>Full event coordination for meetings and celebrations</p>
                    </div>
                    <div class="kcl-service-card kcl-glass-card kcl-animate-fade-up">
                        <i class="fas fa-shopping-bag"></i>
                        <h3>Personal Shopping</h3>
                        <p>Curated shopping experiences with local guides</p>
                    </div>
                </div>
                <div class="kcl-section-cta">
                    <a href="<?php echo esc_url(home_url('/concierge/')); ?>" class="kcl-btn kcl-btn-outline">Explore All Services</a>
                </div>
            </div>
        </section>

        <!-- Loyalty Section -->
        <section class="kcl-section kcl-loyalty-section" data-section="loyalty">
            <div class="kcl-container">
                <h2 class="kcl-section-title kcl-animate-fade-up">Loyalty <span class="kcl-gold">Rewards</span></h2>
                <p class="kcl-section-subtitle kcl-animate-fade-up">Exclusive benefits for our valued guests</p>
                <div class="kcl-loyalty-tiers">
                    <div class="kcl-tier-card kcl-glass-card kcl-animate-fade-up">
                        <div class="kcl-tier-icon bronze"><i class="fas fa-award"></i></div>
                        <h3>Bronze</h3>
                        <ul><li>5% off bookings</li><li>Early check-in</li><li>Welcome drink</li></ul>
                    </div>
                    <div class="kcl-tier-card kcl-glass-card kcl-animate-fade-up">
                        <div class="kcl-tier-icon silver"><i class="fas fa-award"></i></div>
                        <h3>Silver</h3>
                        <ul><li>10% off bookings</li><li>Room upgrade</li><li>Spa credit</li></ul>
                    </div>
                    <div class="kcl-tier-card kcl-glass-card kcl-animate-fade-up">
                        <div class="kcl-tier-icon gold"><i class="fas fa-award"></i></div>
                        <h3>Gold</h3>
                        <ul><li>15% off bookings</li><li>Free breakfast</li><li>Airport transfer</li></ul>
                    </div>
                    <div class="kcl-tier-card kcl-glass-card kcl-animate-fade-up">
                        <div class="kcl-tier-icon platinum"><i class="fas fa-crown"></i></div>
                        <h3>Platinum</h3>
                        <ul><li>20% off bookings</li><li>Personal butler</li><li>VIP experiences</li></ul>
                    </div>
                </div>
                <div class="kcl-section-cta">
                    <a href="<?php echo esc_url(home_url('/loyalty/')); ?>" class="kcl-btn kcl-btn-primary">Join Now</a>
                </div>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }
    
    public static function apartments($atts) {
        ob_start();
        ?>
        <section class="kcl-page-section kcl-apartments-page">
            <div class="kcl-container">
                <h1 class="kcl-page-title"><span class="kcl-gold">Luxury</span> Apartments</h1>
                
                <div class="kcl-filters kcl-glass-card">
                    <select id="kcl-filter-type"><option value="">All Types</option></select>
                    <select id="kcl-filter-beds">
                        <option value="">Bedrooms</option>
                        <option value="1">1 Bedroom</option>
                        <option value="2">2 Bedrooms</option>
                        <option value="3">3+ Bedrooms</option>
                    </select>
                    <select id="kcl-filter-price">
                        <option value="">Price Range</option>
                        <option value="0-100000">Under ₦100,000</option>
                        <option value="100000-200000">₦100,000 - ₦200,000</option>
                        <option value="200000+">₦200,000+</option>
                    </select>
                    <button class="kcl-btn kcl-btn-primary" id="kcl-apply-filters">Apply Filters</button>
                </div>
                
                <div class="kcl-apartments-grid" id="kcl-apartments-list">
                    <?php
                    $apartments = new WP_Query(array('post_type' => 'kcl_apartment', 'posts_per_page' => 12, 'post_status' => 'publish'));
                    if ($apartments->have_posts()) :
                        while ($apartments->have_posts()) : $apartments->the_post();
                            $price = get_post_meta(get_the_ID(), '_kcl_price_per_night', true);
                            $bedrooms = get_post_meta(get_the_ID(), '_kcl_bedrooms', true);
                            $bathrooms = get_post_meta(get_the_ID(), '_kcl_bathrooms', true);
                            $size = get_post_meta(get_the_ID(), '_kcl_size', true);
                            $amenities = get_post_meta(get_the_ID(), '_kcl_apartment_amenities', true);
                            ?>
                            <div class="kcl-apartment-card kcl-glass-card">
                                <div class="kcl-card-image">
                                    <?php if (has_post_thumbnail()) : the_post_thumbnail('large'); else : ?>
                                        <div class="kcl-image-placeholder"><i class="fas fa-image"></i></div>
                                    <?php endif; ?>
                                </div>
                                <div class="kcl-card-content">
                                    <h3><?php the_title(); ?></h3>
                                    <div class="kcl-card-meta">
                                        <span><i class="fas fa-bed"></i> <?php echo esc_html($bedrooms ?: '2'); ?> Beds</span>
                                        <span><i class="fas fa-bath"></i> <?php echo esc_html($bathrooms ?: '1'); ?> Baths</span>
                                        <span><i class="fas fa-expand"></i> <?php echo esc_html($size ?: '75'); ?> sqm</span>
                                    </div>
                                    <?php if ($amenities) : ?>
                                    <div class="kcl-card-amenities">
                                        <?php foreach (array_slice(explode(',', $amenities), 0, 4) as $amenity) : ?>
                                            <span class="kcl-amenity-tag"><?php echo esc_html(trim($amenity)); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php endif; ?>
                                    <div class="kcl-card-footer">
                                        <div class="kcl-card-price"><?php echo esc_html(kcl_format_price($price ?: 150000)); ?><small>/night</small></div>
                                        <a href="<?php echo esc_url(home_url('/booking/?apartment=' . get_the_ID())); ?>" class="kcl-btn kcl-btn-primary">Book Now</a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; wp_reset_postdata();
                    else : ?>
                        <div class="kcl-no-apartments">
                            <i class="fas fa-building"></i>
                            <p>No apartments available yet. Please check back soon!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }
    
    public static function amenities($atts) {
        ob_start();
        ?>
        <section class="kcl-page-section kcl-amenities-page">
            <div class="kcl-container">
                <h1 class="kcl-page-title">Explore Our <span class="kcl-gold">Amenities</span></h1>
                <p class="kcl-page-subtitle">Take a virtual tour of our world-class facilities</p>
                
                <div class="kcl-amenities-showcase">
                    <?php
                    $amenities = new WP_Query(array('post_type' => 'kcl_amenity', 'posts_per_page' => -1, 'post_status' => 'publish'));
                    if ($amenities->have_posts()) :
                        while ($amenities->have_posts()) : $amenities->the_post();
                            $icon = get_post_meta(get_the_ID(), '_kcl_amenity_icon', true);
                            ?>
                            <div class="kcl-amenity-showcase-card kcl-glass-card">
                                <div class="kcl-amenity-image">
                                    <?php if (has_post_thumbnail()) : the_post_thumbnail('large'); else : ?>
                                        <div class="kcl-image-placeholder"><i class="<?php echo esc_attr($icon ?: 'fas fa-star'); ?>"></i></div>
                                    <?php endif; ?>
                                </div>
                                <div class="kcl-amenity-info">
                                    <i class="<?php echo esc_attr($icon ?: 'fas fa-star'); ?>"></i>
                                    <h3><?php the_title(); ?></h3>
                                    <p><?php the_excerpt(); ?></p>
                                </div>
                            </div>
                        <?php endwhile; wp_reset_postdata();
                    else : ?>
                        <div class="kcl-amenities-default-grid">
                            <div class="kcl-amenity-showcase-card kcl-glass-card">
                                <div class="kcl-amenity-image"><div class="kcl-image-placeholder"><i class="fas fa-swimming-pool"></i></div></div>
                                <div class="kcl-amenity-info"><i class="fas fa-swimming-pool"></i><h3>Infinity Pool</h3><p>Relax in our stunning rooftop infinity pool with panoramic views</p></div>
                            </div>
                            <div class="kcl-amenity-showcase-card kcl-glass-card">
                                <div class="kcl-amenity-image"><div class="kcl-image-placeholder"><i class="fas fa-spa"></i></div></div>
                                <div class="kcl-amenity-info"><i class="fas fa-spa"></i><h3>Luxury Spa</h3><p>Rejuvenate with our premium spa treatments and wellness services</p></div>
                            </div>
                            <div class="kcl-amenity-showcase-card kcl-glass-card">
                                <div class="kcl-amenity-image"><div class="kcl-image-placeholder"><i class="fas fa-dumbbell"></i></div></div>
                                <div class="kcl-amenity-info"><i class="fas fa-dumbbell"></i><h3>Fitness Center</h3><p>State-of-the-art equipment and personal training available</p></div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }
    
    public static function booking_form($atts) {
        $selected_apartment = isset($_GET['apartment']) ? intval($_GET['apartment']) : 0;
        ob_start();
        ?>
        <section class="kcl-page-section kcl-booking-page">
            <div class="kcl-container">
                <h1 class="kcl-page-title">Book Your <span class="kcl-gold">Stay</span></h1>
                
                <div class="kcl-booking-wrapper">
                    <form id="kcl-booking-form" class="kcl-booking-form kcl-glass-card">
                        <?php wp_nonce_field('kcl_booking_nonce', 'kcl_nonce'); ?>
                        
                        <div class="kcl-form-section">
                            <h3><i class="fas fa-building"></i> Select Apartment</h3>
                            <select name="apartment_id" id="apartment_id" required>
                                <option value="">Choose an apartment</option>
                                <?php
                                $apartments = new WP_Query(array('post_type' => 'kcl_apartment', 'posts_per_page' => -1, 'post_status' => 'publish'));
                                while ($apartments->have_posts()) : $apartments->the_post();
                                    $price = get_post_meta(get_the_ID(), '_kcl_price_per_night', true);
                                    $selected = ($selected_apartment === get_the_ID()) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo esc_attr(get_the_ID()); ?>" data-price="<?php echo esc_attr($price); ?>" <?php echo esc_attr($selected); ?>>
                                        <?php the_title(); ?> - <?php echo esc_html(kcl_format_price($price ?: 100000)); ?>/night
                                    </option>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </select>
                        </div>
                        
                        <div class="kcl-form-section">
                            <h3><i class="fas fa-calendar-alt"></i> Stay Details</h3>
                            <div class="kcl-form-row">
                                <div class="kcl-form-group">
                                    <label for="check_in">Check In</label>
                                    <input type="date" id="check_in" name="check_in" required min="<?php echo esc_attr(gmdate('Y-m-d')); ?>">
                                </div>
                                <div class="kcl-form-group">
                                    <label for="check_out">Check Out</label>
                                    <input type="date" id="check_out" name="check_out" required>
                                </div>
                            </div>
                            <div class="kcl-form-group">
                                <label for="guests">Number of Guests</label>
                                <select id="guests" name="guests">
                                    <?php for ($i = 1; $i <= 10; $i++) : ?>
                                        <option value="<?php echo esc_attr($i); ?>"><?php echo esc_html($i); ?> Guest<?php echo $i > 1 ? 's' : ''; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="kcl-form-section">
                            <h3><i class="fas fa-user"></i> Guest Information</h3>
                            <div class="kcl-form-row">
                                <div class="kcl-form-group">
                                    <label for="guest_name">Full Name</label>
                                    <input type="text" id="guest_name" name="guest_name" required placeholder="Enter your full name">
                                </div>
                                <div class="kcl-form-group">
                                    <label for="guest_email">Email</label>
                                    <input type="email" id="guest_email" name="guest_email" required placeholder="Enter your email">
                                </div>
                            </div>
                            <div class="kcl-form-group">
                                <label for="guest_phone">Phone Number</label>
                                <input type="tel" id="guest_phone" name="guest_phone" required placeholder="+234...">
                            </div>
                            <div class="kcl-form-group">
                                <label for="special_requests">Special Requests (Optional)</label>
                                <textarea id="special_requests" name="special_requests" rows="3" placeholder="Any special requirements?"></textarea>
                            </div>
                        </div>
                        
                        <div class="kcl-booking-summary kcl-glass-card">
                            <h3><i class="fas fa-receipt"></i> Booking Summary</h3>
                            <div class="kcl-summary-row"><span>Apartment:</span><span id="summary-apartment">-</span></div>
                            <div class="kcl-summary-row"><span>Check In:</span><span id="summary-checkin">-</span></div>
                            <div class="kcl-summary-row"><span>Check Out:</span><span id="summary-checkout">-</span></div>
                            <div class="kcl-summary-row"><span>Nights:</span><span id="summary-nights">-</span></div>
                            <div class="kcl-summary-row kcl-summary-total"><span>Total:</span><span id="summary-total">₦0</span></div>
                        </div>
                        
                        <button type="submit" class="kcl-btn kcl-btn-primary kcl-btn-full" id="kcl-pay-btn">
                            <i class="fas fa-lock"></i> Proceed to Payment
                        </button>
                        <p class="kcl-secure-note"><i class="fas fa-shield-alt"></i> Secured by Paystack</p>
                    </form>
                </div>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }
    
    public static function concierge($atts) {
        ob_start();
        ?>
        <section class="kcl-page-section kcl-concierge-page">
            <div class="kcl-container">
                <h1 class="kcl-page-title">Concierge <span class="kcl-gold">Services</span></h1>
                <p class="kcl-page-subtitle">Elevate your stay with our premium high-end services</p>
                
                <div class="kcl-concierge-grid">
                    <?php
                    $services = new WP_Query(array('post_type' => 'kcl_concierge', 'posts_per_page' => -1, 'post_status' => 'publish'));
                    if ($services->have_posts()) :
                        while ($services->have_posts()) : $services->the_post();
                            $price = get_post_meta(get_the_ID(), '_kcl_service_price', true);
                            $duration = get_post_meta(get_the_ID(), '_kcl_service_duration', true);
                            $icon = get_post_meta(get_the_ID(), '_kcl_service_icon', true);
                            ?>
                            <div class="kcl-concierge-card kcl-glass-card">
                                <div class="kcl-concierge-icon"><i class="<?php echo esc_attr($icon ?: 'fas fa-concierge-bell'); ?>"></i></div>
                                <h3><?php the_title(); ?></h3>
                                <p><?php the_excerpt(); ?></p>
                                <div class="kcl-concierge-meta">
                                    <?php if ($duration) : ?><span><i class="fas fa-clock"></i> <?php echo esc_html($duration); ?></span><?php endif; ?>
                                    <span class="kcl-concierge-price"><?php echo $price ? esc_html(kcl_format_price($price)) : 'Contact for Price'; ?></span>
                                </div>
                                <a href="https://wa.me/<?php echo esc_attr(KCL_WHATSAPP_NUMBER); ?>?text=<?php echo rawurlencode("I'm interested in " . get_the_title()); ?>" target="_blank" class="kcl-btn kcl-btn-outline">Inquire Now</a>
                            </div>
                        <?php endwhile; wp_reset_postdata();
                    else : ?>
                        <div class="kcl-concierge-card kcl-glass-card"><div class="kcl-concierge-icon"><i class="fas fa-car"></i></div><h3>Airport Transfer</h3><p>Luxury vehicle pickup and drop-off at Nnamdi Azikiwe International Airport</p><div class="kcl-concierge-meta"><span class="kcl-concierge-price">₦25,000</span></div><a href="https://wa.me/<?php echo esc_attr(KCL_WHATSAPP_NUMBER); ?>" target="_blank" class="kcl-btn kcl-btn-outline">Inquire Now</a></div>
                        <div class="kcl-concierge-card kcl-glass-card"><div class="kcl-concierge-icon"><i class="fas fa-utensils"></i></div><h3>Private Chef</h3><p>Personal chef service with customized menu for your dietary preferences</p><div class="kcl-concierge-meta"><span class="kcl-concierge-price">₦75,000</span></div><a href="https://wa.me/<?php echo esc_attr(KCL_WHATSAPP_NUMBER); ?>" target="_blank" class="kcl-btn kcl-btn-outline">Inquire Now</a></div>
                        <div class="kcl-concierge-card kcl-glass-card"><div class="kcl-concierge-icon"><i class="fas fa-spa"></i></div><h3>In-Room Spa</h3><p>Premium spa treatments brought directly to your suite</p><div class="kcl-concierge-meta"><span class="kcl-concierge-price">₦50,000</span></div><a href="https://wa.me/<?php echo esc_attr(KCL_WHATSAPP_NUMBER); ?>" target="_blank" class="kcl-btn kcl-btn-outline">Inquire Now</a></div>
                        <div class="kcl-concierge-card kcl-glass-card"><div class="kcl-concierge-icon"><i class="fas fa-calendar-alt"></i></div><h3>Event Planning</h3><p>Full coordination for corporate meetings, celebrations and private events</p><div class="kcl-concierge-meta"><span class="kcl-concierge-price">Contact for Price</span></div><a href="https://wa.me/<?php echo esc_attr(KCL_WHATSAPP_NUMBER); ?>" target="_blank" class="kcl-btn kcl-btn-outline">Inquire Now</a></div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }
    
    public static function loyalty($atts) {
        ob_start();
        ?>
        <section class="kcl-page-section kcl-loyalty-page">
            <div class="kcl-container">
                <h1 class="kcl-page-title">Loyalty <span class="kcl-gold">Program</span></h1>
                <p class="kcl-page-subtitle">Join our exclusive rewards program for personalized attention and exclusive offers</p>
                
                <div class="kcl-loyalty-hero kcl-glass-card">
                    <h2>Become a <span class="kcl-gold">Royal Guest</span></h2>
                    <p>Earn points with every booking and unlock premium benefits</p>
                </div>
                
                <div class="kcl-loyalty-tiers-full">
                    <div class="kcl-tier-full kcl-glass-card">
                        <div class="kcl-tier-header bronze"><i class="fas fa-award"></i><h3>Bronze</h3><span>0 - 4,999 points</span></div>
                        <ul><li><i class="fas fa-check"></i> 5% discount on bookings</li><li><i class="fas fa-check"></i> Early check-in (subject to availability)</li><li><i class="fas fa-check"></i> Welcome drink on arrival</li><li><i class="fas fa-check"></i> Birthday special offer</li></ul>
                    </div>
                    <div class="kcl-tier-full kcl-glass-card">
                        <div class="kcl-tier-header silver"><i class="fas fa-award"></i><h3>Silver</h3><span>5,000 - 19,999 points</span></div>
                        <ul><li><i class="fas fa-check"></i> 10% discount on bookings</li><li><i class="fas fa-check"></i> Guaranteed room upgrade</li><li><i class="fas fa-check"></i> ₦10,000 spa credit</li><li><i class="fas fa-check"></i> Late checkout until 2pm</li><li><i class="fas fa-check"></i> Priority reservations</li></ul>
                    </div>
                    <div class="kcl-tier-full kcl-glass-card featured">
                        <div class="kcl-tier-header gold"><i class="fas fa-award"></i><h3>Gold</h3><span>20,000 - 49,999 points</span></div>
                        <ul><li><i class="fas fa-check"></i> 15% discount on bookings</li><li><i class="fas fa-check"></i> Daily complimentary breakfast</li><li><i class="fas fa-check"></i> Free airport transfer</li><li><i class="fas fa-check"></i> Exclusive lounge access</li><li><i class="fas fa-check"></i> Dedicated concierge</li><li><i class="fas fa-check"></i> Anniversary surprise</li></ul>
                    </div>
                    <div class="kcl-tier-full kcl-glass-card">
                        <div class="kcl-tier-header platinum"><i class="fas fa-crown"></i><h3>Platinum</h3><span>50,000+ points</span></div>
                        <ul><li><i class="fas fa-check"></i> 20% discount on bookings</li><li><i class="fas fa-check"></i> Personal butler service</li><li><i class="fas fa-check"></i> VIP airport meet & greet</li><li><i class="fas fa-check"></i> Complimentary suite upgrades</li><li><i class="fas fa-check"></i> Private dining experiences</li><li><i class="fas fa-check"></i> Exclusive member events</li><li><i class="fas fa-check"></i> Partner luxury brand perks</li></ul>
                    </div>
                </div>
                
                <div class="kcl-loyalty-cta kcl-glass-card">
                    <h3>Ready to Start Earning?</h3>
                    <p>Points are automatically earned with every booking. 1 Naira = 1 Point</p>
                    <a href="<?php echo esc_url(home_url('/booking/')); ?>" class="kcl-btn kcl-btn-primary">Book Now & Earn Points</a>
                </div>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }
    
    public static function guest_profile($atts) {
        ob_start();
        ?>
        <section class="kcl-page-section kcl-profile-page">
            <div class="kcl-container">
                <h1 class="kcl-page-title">Guest <span class="kcl-gold">Profile</span></h1>
                
                <div class="kcl-profile-lookup kcl-glass-card">
                    <h3><i class="fas fa-search"></i> View Your Booking History</h3>
                    <p>Enter the email used during booking to view your profile and booking history</p>
                    <form id="kcl-profile-lookup-form">
                        <?php wp_nonce_field('kcl_profile_nonce', 'kcl_profile_nonce'); ?>
                        <div class="kcl-form-row">
                            <input type="email" id="lookup_email" name="lookup_email" placeholder="Enter your email" required>
                            <button type="submit" class="kcl-btn kcl-btn-primary">View Profile</button>
                        </div>
                    </form>
                </div>
                
                <div id="kcl-profile-content" class="kcl-profile-content" style="display:none;">
                    <div class="kcl-profile-header kcl-glass-card">
                        <div class="kcl-profile-avatar"><i class="fas fa-user-circle"></i></div>
                        <div class="kcl-profile-info">
                            <h2 id="profile-name">Guest</h2>
                            <p id="profile-email">guest@example.com</p>
                            <div class="kcl-profile-tier" id="profile-tier"><i class="fas fa-award"></i> Bronze Member</div>
                        </div>
                        <div class="kcl-profile-points">
                            <span id="profile-points">0</span>
                            <small>Points</small>
                        </div>
                    </div>
                    
                    <div class="kcl-profile-bookings kcl-glass-card">
                        <h3><i class="fas fa-history"></i> Booking History</h3>
                        <div id="profile-bookings-list"></div>
                    </div>
                </div>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }
    
    public static function about($atts) {
        ob_start();
        ?>
        <section class="kcl-page-section kcl-about-page">
            <div class="kcl-container">
                <h1 class="kcl-page-title">About <span class="kcl-gold">Kings Comfort Luxury</span></h1>
                <p class="kcl-page-subtitle">Your premier destination for luxury service apartments in Abuja, Nigeria</p>
                
                <!-- About Hero -->
                <div class="kcl-about-hero kcl-glass-card">
                    <div class="kcl-about-hero-content">
                        <h2>Welcome to <span class="kcl-gold">Unparalleled Luxury</span></h2>
                        <p>Kings Comfort Luxury represents the pinnacle of service apartment living in the heart of Abuja. Founded with a vision to redefine hospitality, we offer discerning travelers and residents an experience that seamlessly blends comfort, elegance, and exceptional service.</p>
                        <p>Located in the prestigious Kabusa area, our apartments provide the perfect sanctuary for business executives, diplomats, tourists, and families seeking a home away from home with all the amenities of a five-star hotel.</p>
                    </div>
                    <div class="kcl-about-hero-image">
                        <div class="kcl-image-placeholder"><i class="fas fa-building"></i></div>
                    </div>
                </div>
                
                <!-- Our Story -->
                <div class="kcl-about-section">
                    <h2 class="kcl-section-title">Our <span class="kcl-gold">Story</span></h2>
                    <div class="kcl-about-story kcl-glass-card">
                        <div class="kcl-about-story-image">
                            <div class="kcl-image-placeholder"><i class="fas fa-crown"></i></div>
                        </div>
                        <div class="kcl-about-story-content">
                            <p>Kings Comfort Luxury was born from a simple yet powerful idea: to create a space where every guest feels like royalty. Our founders, with decades of experience in the hospitality industry, envisioned apartments that would exceed expectations in every way.</p>
                            <p>From the moment you step into any of our properties, you'll experience the warmth of Nigerian hospitality combined with international standards of luxury. Every detail, from the premium furnishings to the personalized services, has been carefully curated to ensure your comfort.</p>
                            <p>Today, we're proud to be recognized as one of Abuja's premier service apartment providers, trusted by discerning guests from around the world.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Mission & Vision -->
                <div class="kcl-about-mission">
                    <div class="kcl-mission-card kcl-glass-card">
                        <div class="kcl-mission-icon"><i class="fas fa-bullseye"></i></div>
                        <h3>Our Mission</h3>
                        <p>To provide exceptional luxury accommodation experiences that make every guest feel at home while enjoying world-class amenities and personalized service that exceeds expectations.</p>
                    </div>
                    <div class="kcl-mission-card kcl-glass-card">
                        <div class="kcl-mission-icon"><i class="fas fa-eye"></i></div>
                        <h3>Our Vision</h3>
                        <p>To be the leading luxury service apartment brand in Nigeria, setting the standard for excellence in hospitality and creating memorable experiences for every guest.</p>
                    </div>
                    <div class="kcl-mission-card kcl-glass-card">
                        <div class="kcl-mission-icon"><i class="fas fa-heart"></i></div>
                        <h3>Our Values</h3>
                        <p>Excellence, Integrity, Hospitality, and Innovation guide everything we do. We believe in treating every guest as family and going above and beyond to create lasting impressions.</p>
                    </div>
                </div>
                
                <!-- Why Choose Us -->
                <div class="kcl-about-section">
                    <h2 class="kcl-section-title">Why Choose <span class="kcl-gold">Us</span></h2>
                    <div class="kcl-why-choose-grid">
                        <div class="kcl-why-card kcl-glass-card">
                            <i class="fas fa-gem"></i>
                            <h4>Premium Quality</h4>
                            <p>Every apartment features high-end furnishings, modern appliances, and premium amenities</p>
                        </div>
                        <div class="kcl-why-card kcl-glass-card">
                            <i class="fas fa-map-marker-alt"></i>
                            <h4>Prime Location</h4>
                            <p>Strategically located in Kabusa, Abuja with easy access to major business and leisure destinations</p>
                        </div>
                        <div class="kcl-why-card kcl-glass-card">
                            <i class="fas fa-concierge-bell"></i>
                            <h4>24/7 Concierge</h4>
                            <p>Round-the-clock dedicated concierge service to attend to your every need</p>
                        </div>
                        <div class="kcl-why-card kcl-glass-card">
                            <i class="fas fa-shield-alt"></i>
                            <h4>Security & Privacy</h4>
                            <p>Advanced security systems and complete privacy for peace of mind during your stay</p>
                        </div>
                        <div class="kcl-why-card kcl-glass-card">
                            <i class="fas fa-wifi"></i>
                            <h4>Modern Connectivity</h4>
                            <p>High-speed internet and smart home features throughout all properties</p>
                        </div>
                        <div class="kcl-why-card kcl-glass-card">
                            <i class="fas fa-award"></i>
                            <h4>Award-Winning Service</h4>
                            <p>Recognized excellence in hospitality with consistently outstanding reviews</p>
                        </div>
                    </div>
                </div>
                
                <!-- Team Section -->
                <div class="kcl-about-section">
                    <h2 class="kcl-section-title">Our <span class="kcl-gold">Team</span></h2>
                    <p class="kcl-section-subtitle">Meet the dedicated professionals behind your exceptional experience</p>
                    <div class="kcl-team-grid">
                        <div class="kcl-team-card kcl-glass-card">
                            <div class="kcl-team-image"><div class="kcl-image-placeholder"><i class="fas fa-user"></i></div></div>
                            <h4>Management Team</h4>
                            <p class="kcl-team-role">Leadership & Strategy</p>
                            <p>Our experienced management team ensures every aspect of your stay meets the highest standards of luxury and comfort.</p>
                        </div>
                        <div class="kcl-team-card kcl-glass-card">
                            <div class="kcl-team-image"><div class="kcl-image-placeholder"><i class="fas fa-user"></i></div></div>
                            <h4>Concierge Team</h4>
                            <p class="kcl-team-role">Guest Services</p>
                            <p>Available 24/7 to assist with reservations, special requests, and local recommendations to enhance your stay.</p>
                        </div>
                        <div class="kcl-team-card kcl-glass-card">
                            <div class="kcl-team-image"><div class="kcl-image-placeholder"><i class="fas fa-user"></i></div></div>
                            <h4>Housekeeping Team</h4>
                            <p class="kcl-team-role">Cleanliness & Comfort</p>
                            <p>Our meticulous housekeeping staff maintains impeccable cleanliness standards throughout all properties.</p>
                        </div>
                    </div>
                </div>
                
                <!-- CTA -->
                <div class="kcl-about-cta kcl-glass-card">
                    <h2>Ready to Experience <span class="kcl-gold">True Luxury</span>?</h2>
                    <p>Book your stay today and discover why Kings Comfort Luxury is Abuja's premier choice for discerning travelers.</p>
                    <div class="kcl-about-cta-btns">
                        <a href="<?php echo esc_url(home_url('/apartments/')); ?>" class="kcl-btn kcl-btn-primary">View Apartments</a>
                        <a href="<?php echo esc_url(home_url('/booking/')); ?>" class="kcl-btn kcl-btn-outline">Book Now</a>
                    </div>
                </div>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }
}

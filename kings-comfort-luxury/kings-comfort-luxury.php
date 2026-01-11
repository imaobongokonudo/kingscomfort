<?php
/**
 * Plugin Name: Kings Comfort Luxury
 * Plugin URI: https://kingscomfortluxury.com
 * Description: A luxury service apartment booking plugin for Kings Comfort in Abuja, Nigeria. Features glass morphism design, Paystack integration, offline support, and mobile-first navigation.
 * Version: 1.0.0
 * Author: Kings Comfort Luxury
 * Author URI: https://kingscomfortluxury.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: kings-comfort-luxury
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Plugin constants
define('KCL_VERSION', '1.0.0');
define('KCL_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('KCL_PLUGIN_URL', plugin_dir_url(__FILE__));
define('KCL_PRIMARY_COLOR', '#E2C369');
define('KCL_BACKGROUND_COLOR', '#0A0A14');
define('KCL_TEXT_COLOR', '#FFFFFF');
define('KCL_WHATSAPP_NUMBER', '2348037100768');

// Include required files
require_once KCL_PLUGIN_DIR . 'includes/class-kcl-activator.php';
require_once KCL_PLUGIN_DIR . 'includes/class-kcl-deactivator.php';
require_once KCL_PLUGIN_DIR . 'includes/class-kcl-post-types.php';
require_once KCL_PLUGIN_DIR . 'includes/class-kcl-shortcodes.php';
require_once KCL_PLUGIN_DIR . 'includes/class-kcl-booking.php';
require_once KCL_PLUGIN_DIR . 'includes/class-kcl-paystack.php';
require_once KCL_PLUGIN_DIR . 'includes/class-kcl-ajax.php';
require_once KCL_PLUGIN_DIR . 'includes/class-kcl-offline.php';
require_once KCL_PLUGIN_DIR . 'admin/class-kcl-admin.php';

/**
 * Main Plugin Class
 */
class Kings_Comfort_Luxury {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init_hooks();
    }
    
    private function init_hooks() {
        // Activation and deactivation hooks
        register_activation_hook(__FILE__, array('KCL_Activator', 'activate'));
        register_deactivation_hook(__FILE__, array('KCL_Deactivator', 'deactivate'));
        
        // Init hooks
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('wp_head', array($this, 'add_favicon'));
        add_action('wp_footer', array($this, 'add_widgets'));
        add_action('wp_footer', array($this, 'add_mobile_navigation'));
        
        // Add header and footer to all frontend pages
        add_action('wp_body_open', array($this, 'add_site_header'));
        add_action('wp_footer', array($this, 'add_site_footer'), 5);
        
        // Initialize components
        KCL_Post_Types::init();
        KCL_Shortcodes::init();
        KCL_Booking::init();
        KCL_Ajax::init();
        KCL_Offline::init();
        
        if (is_admin()) {
            KCL_Admin::init();
        }
    }
    
    public function init() {
        // Load text domain for translations
        load_plugin_textdomain('kings-comfort-luxury', false, dirname(plugin_basename(__FILE__)) . '/languages');
        
        // Register menus
        register_nav_menus(array(
            'kcl-primary-menu' => __('KCL Primary Menu', 'kings-comfort-luxury'),
            'kcl-footer-menu' => __('KCL Footer Menu', 'kings-comfort-luxury'),
        ));
    }
    
    public function enqueue_frontend_assets() {
        // Main styles
        wp_enqueue_style('kcl-main-styles', KCL_PLUGIN_URL . 'public/css/main.css', array(), KCL_VERSION);
        wp_enqueue_style('kcl-animations', KCL_PLUGIN_URL . 'public/css/animations.css', array(), KCL_VERSION);
        wp_enqueue_style('kcl-responsive', KCL_PLUGIN_URL . 'public/css/responsive.css', array('kcl-main-styles'), KCL_VERSION);
        
        // Google Fonts - Elegant luxury fonts
        wp_enqueue_style('kcl-google-fonts', 'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap', array(), null);
        
        // Font Awesome for icons - using multiple CDN sources for reliability
        wp_enqueue_style('font-awesome-cdn', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), '6.5.1');
        
        // Main scripts
        wp_enqueue_script('kcl-main-scripts', KCL_PLUGIN_URL . 'public/js/main.js', array('jquery'), KCL_VERSION, true);
        wp_enqueue_script('kcl-animations-js', KCL_PLUGIN_URL . 'public/js/animations.js', array('jquery'), KCL_VERSION, true);
        wp_enqueue_script('kcl-booking-js', KCL_PLUGIN_URL . 'public/js/booking.js', array('jquery'), KCL_VERSION, true);
        wp_enqueue_script('kcl-offline-js', KCL_PLUGIN_URL . 'public/js/offline.js', array('jquery'), KCL_VERSION, true);
        
        // Paystack script
        wp_enqueue_script('paystack', 'https://js.paystack.co/v1/inline.js', array(), null, true);
        
        // Localize script for AJAX
        wp_localize_script('kcl-main-scripts', 'kcl_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('kcl_nonce'),
            'plugin_url' => KCL_PLUGIN_URL,
            'home_url' => home_url('/'),
            'booking_url' => home_url('/booking/'),
            'whatsapp_number' => KCL_WHATSAPP_NUMBER,
            'primary_color' => KCL_PRIMARY_COLOR,
            'background_color' => KCL_BACKGROUND_COLOR,
        ));
    }
    
    public function enqueue_admin_assets($hook) {
        wp_enqueue_style('kcl-admin-styles', KCL_PLUGIN_URL . 'admin/css/admin.css', array(), KCL_VERSION);
        wp_enqueue_script('kcl-admin-scripts', KCL_PLUGIN_URL . 'admin/js/admin.js', array('jquery'), KCL_VERSION, true);
        
        wp_localize_script('kcl-admin-scripts', 'kcl_admin_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('kcl_admin_nonce'),
        ));
    }
    
    public function add_favicon() {
        $favicon_url = KCL_PLUGIN_URL . 'public/images/logo.png';
        ?>
        <link rel="icon" type="image/png" href="<?php echo esc_url($favicon_url); ?>">
        <link rel="apple-touch-icon" href="<?php echo esc_url($favicon_url); ?>">
        <meta name="theme-color" content="<?php echo esc_attr(KCL_PRIMARY_COLOR); ?>">
        <?php
    }
    
    public function add_widgets() {
        ?>
        <!-- Scroll to Top with Progress -->
        <div id="kcl-scroll-top" class="kcl-scroll-top">
            <svg class="kcl-scroll-progress" viewBox="0 0 48 48">
                <rect class="kcl-progress-bg" x="4" y="4" width="40" height="40" rx="10" ry="10"/>
                <rect class="kcl-progress-bar" x="4" y="4" width="40" height="40" rx="10" ry="10"/>
            </svg>
            <div class="kcl-progress-fill"></div>
            <i class="fas fa-arrow-up"></i>
        </div>
        
        <!-- WhatsApp Widget -->
        <a href="https://wa.me/<?php echo esc_attr(KCL_WHATSAPP_NUMBER); ?>?text=Hello%20Kings%20Comfort%20Luxury" 
           target="_blank" 
           class="kcl-whatsapp-widget" 
           aria-label="<?php esc_attr_e('Contact us on WhatsApp', 'kings-comfort-luxury'); ?>">
            <i class="fab fa-whatsapp"></i>
            <span class="kcl-whatsapp-pulse"></span>
        </a>
        <?php
    }
    
    public function add_mobile_navigation() {
        ?>
        <!-- Mobile Bottom Navigation -->
        <nav class="kcl-mobile-nav" aria-label="<?php esc_attr_e('Mobile Navigation', 'kings-comfort-luxury'); ?>">
            <div class="kcl-mobile-nav-inner">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="kcl-mobile-nav-item <?php echo is_front_page() ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i>
                    <span><?php esc_html_e('Home', 'kings-comfort-luxury'); ?></span>
                </a>
                <a href="<?php echo esc_url(home_url('/apartments/')); ?>" class="kcl-mobile-nav-item <?php echo is_page('apartments') ? 'active' : ''; ?>">
                    <i class="fas fa-building"></i>
                    <span><?php esc_html_e('Apartments', 'kings-comfort-luxury'); ?></span>
                </a>
                <a href="<?php echo esc_url(home_url('/booking/')); ?>" class="kcl-mobile-nav-item kcl-mobile-nav-book <?php echo is_page('booking') ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-check"></i>
                    <span><?php esc_html_e('Book', 'kings-comfort-luxury'); ?></span>
                </a>
                <a href="<?php echo esc_url(home_url('/profile/')); ?>" class="kcl-mobile-nav-item <?php echo is_page('profile') ? 'active' : ''; ?>">
                    <i class="fas fa-user"></i>
                    <span><?php esc_html_e('Profile', 'kings-comfort-luxury'); ?></span>
                </a>
            </div>
        </nav>
        <?php
    }
    
    public function add_site_header() {
        // Only add header on frontend, not admin
        if (is_admin()) {
            return;
        }
        ?>
        <header class="kcl-header" id="kcl-header">
            <div class="kcl-container">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="kcl-logo">
                    <img src="<?php echo esc_url(kcl_get_logo_url('png')); ?>" alt="Kings Comfort Luxury" class="kcl-logo-img kcl-sparkle">
                </a>
                <nav class="kcl-nav" id="kcl-nav">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="kcl-nav-link<?php echo is_front_page() ? ' active' : ''; ?>">Home</a>
                    <a href="<?php echo esc_url(home_url('/apartments/')); ?>" class="kcl-nav-link<?php echo is_page('apartments') ? ' active' : ''; ?>">Apartments</a>
                    <a href="<?php echo esc_url(home_url('/amenities/')); ?>" class="kcl-nav-link<?php echo is_page('amenities') ? ' active' : ''; ?>">Amenities</a>
                    <a href="<?php echo esc_url(home_url('/about/')); ?>" class="kcl-nav-link<?php echo is_page('about') ? ' active' : ''; ?>">About</a>
                    <a href="<?php echo esc_url(home_url('/concierge/')); ?>" class="kcl-nav-link<?php echo is_page('concierge') ? ' active' : ''; ?>">Concierge</a>
                    <a href="<?php echo esc_url(home_url('/loyalty/')); ?>" class="kcl-nav-link<?php echo is_page('loyalty') ? ' active' : ''; ?>">Loyalty</a>
                </nav>
                <a href="<?php echo esc_url(home_url('/booking/')); ?>" class="kcl-btn kcl-btn-primary">Book Now</a>
                <button class="kcl-hamburger" id="kcl-hamburger" aria-label="Toggle menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </header>
        <?php
    }
    
    public function add_site_footer() {
        // Only add footer on frontend, not admin
        if (is_admin()) {
            return;
        }
        ?>
        <footer class="kcl-footer">
            <div class="kcl-container">
                <div class="kcl-footer-grid">
                    <div class="kcl-footer-col">
                        <img src="<?php echo esc_url(kcl_get_logo_url('png')); ?>" alt="Kings Comfort Luxury" class="kcl-footer-logo">
                        <p><?php esc_html_e('Experience luxury living in the heart of Abuja. Your comfort is our priority.', 'kings-comfort-luxury'); ?></p>
                    </div>
                    <div class="kcl-footer-col">
                        <h4><?php esc_html_e('Quick Links', 'kings-comfort-luxury'); ?></h4>
                        <a href="<?php echo esc_url(home_url('/apartments/')); ?>"><?php esc_html_e('Apartments', 'kings-comfort-luxury'); ?></a>
                        <a href="<?php echo esc_url(home_url('/amenities/')); ?>"><?php esc_html_e('Amenities', 'kings-comfort-luxury'); ?></a>
                        <a href="<?php echo esc_url(home_url('/about/')); ?>"><?php esc_html_e('About Us', 'kings-comfort-luxury'); ?></a>
                        <a href="<?php echo esc_url(home_url('/booking/')); ?>"><?php esc_html_e('Book Now', 'kings-comfort-luxury'); ?></a>
                        <a href="<?php echo esc_url(home_url('/concierge/')); ?>"><?php esc_html_e('Concierge', 'kings-comfort-luxury'); ?></a>
                        <a href="<?php echo esc_url(home_url('/loyalty/')); ?>"><?php esc_html_e('Loyalty Program', 'kings-comfort-luxury'); ?></a>
                    </div>
                    <div class="kcl-footer-col">
                        <h4><?php esc_html_e('Contact Info', 'kings-comfort-luxury'); ?></h4>
                        <p><i class="fas fa-map-marker-alt"></i> <?php esc_html_e('Kabusa, Abuja, Nigeria', 'kings-comfort-luxury'); ?></p>
                        <p><i class="fas fa-phone"></i> +234 803 710 0768</p>
                        <p><i class="fas fa-envelope"></i> info@kingscomfortluxury.com</p>
                    </div>
                    <div class="kcl-footer-col">
                        <h4><?php esc_html_e('Follow Us', 'kings-comfort-luxury'); ?></h4>
                        <div class="kcl-social-links">
                            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" aria-label="X"><i class="fab fa-x-twitter"></i></a>
                            <a href="#" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                        </div>
                    </div>
                </div>
                <div class="kcl-footer-bottom">
                    <p>&copy; <?php echo esc_html(gmdate('Y')); ?> Kings Comfort Luxury. <?php esc_html_e('All rights reserved.', 'kings-comfort-luxury'); ?></p>
                </div>
            </div>
        </footer>
        <?php
    }
}

// Initialize plugin
function kcl_init() {
    return Kings_Comfort_Luxury::get_instance();
}

// Start the plugin
add_action('plugins_loaded', 'kcl_init');

// Plugin helper functions
function kcl_get_logo_url($type = 'png') {
    if ($type === 'jpeg' || $type === 'jpg') {
        return KCL_PLUGIN_URL . 'public/images/logo.jpeg';
    }
    return KCL_PLUGIN_URL . 'public/images/logo.png';
}

function kcl_get_apartments($args = array()) {
    $defaults = array(
        'post_type' => 'kcl_apartment',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    );
    $args = wp_parse_args($args, $defaults);
    return new WP_Query($args);
}

function kcl_get_amenities($args = array()) {
    $defaults = array(
        'post_type' => 'kcl_amenity',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    );
    $args = wp_parse_args($args, $defaults);
    return new WP_Query($args);
}

function kcl_format_price($price) {
    return '₦' . number_format($price, 0, '.', ',');
}

<?php
/**
 * Plugin Activator
 */

if (!defined('ABSPATH')) {
    exit;
}

class KCL_Activator {
    
    public static function activate() {
        self::create_tables();
        self::create_pages();
        self::set_default_options();
        flush_rewrite_rules();
    }
    
    private static function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Bookings table
        $bookings_table = $wpdb->prefix . 'kcl_bookings';
        $sql_bookings = "CREATE TABLE IF NOT EXISTS $bookings_table (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            booking_id varchar(50) NOT NULL,
            apartment_id bigint(20) NOT NULL,
            guest_name varchar(255) NOT NULL,
            guest_email varchar(255) NOT NULL,
            guest_phone varchar(50) NOT NULL,
            check_in date NOT NULL,
            check_out date NOT NULL,
            guests int(11) DEFAULT 1,
            total_amount decimal(15,2) NOT NULL,
            payment_status varchar(50) DEFAULT 'pending',
            payment_reference varchar(255) DEFAULT NULL,
            booking_status varchar(50) DEFAULT 'pending',
            special_requests text DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            synced tinyint(1) DEFAULT 1,
            PRIMARY KEY (id),
            UNIQUE KEY booking_id (booking_id),
            KEY apartment_id (apartment_id),
            KEY guest_email (guest_email),
            KEY booking_status (booking_status)
        ) $charset_collate;";
        
        // Offline bookings table (for syncing)
        $offline_table = $wpdb->prefix . 'kcl_offline_bookings';
        $sql_offline = "CREATE TABLE IF NOT EXISTS $offline_table (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            booking_data longtext NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            synced tinyint(1) DEFAULT 0,
            synced_at datetime DEFAULT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        // Loyalty points table
        $loyalty_table = $wpdb->prefix . 'kcl_loyalty';
        $sql_loyalty = "CREATE TABLE IF NOT EXISTS $loyalty_table (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            guest_email varchar(255) NOT NULL,
            guest_name varchar(255) NOT NULL,
            points int(11) DEFAULT 0,
            tier varchar(50) DEFAULT 'bronze',
            total_bookings int(11) DEFAULT 0,
            total_spent decimal(15,2) DEFAULT 0.00,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY guest_email (guest_email)
        ) $charset_collate;";
        
        // Reviews table
        $reviews_table = $wpdb->prefix . 'kcl_reviews';
        $sql_reviews = "CREATE TABLE IF NOT EXISTS $reviews_table (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            apartment_id bigint(20) NOT NULL,
            booking_id varchar(50) DEFAULT NULL,
            guest_name varchar(255) NOT NULL,
            guest_email varchar(255) NOT NULL,
            rating int(11) NOT NULL,
            review text NOT NULL,
            status varchar(50) DEFAULT 'pending',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY apartment_id (apartment_id),
            KEY status (status)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_bookings);
        dbDelta($sql_offline);
        dbDelta($sql_loyalty);
        dbDelta($sql_reviews);
    }
    
    private static function create_pages() {
        $pages = array(
            'apartments' => array(
                'title' => 'Luxury Apartments',
                'content' => '[kcl_apartments]',
            ),
            'amenities' => array(
                'title' => 'Amenities',
                'content' => '[kcl_amenities]',
            ),
            'booking' => array(
                'title' => 'Book Your Stay',
                'content' => '[kcl_booking_form]',
            ),
            'concierge' => array(
                'title' => 'Concierge Services',
                'content' => '[kcl_concierge]',
            ),
            'loyalty' => array(
                'title' => 'Loyalty Program',
                'content' => '[kcl_loyalty]',
            ),
            'profile' => array(
                'title' => 'Guest Profile',
                'content' => '[kcl_guest_profile]',
            ),
            'about' => array(
                'title' => 'About Us',
                'content' => '[kcl_about]',
            ),
        );
        
        foreach ($pages as $slug => $page_data) {
            $existing_page = get_page_by_path($slug);
            if (!$existing_page) {
                wp_insert_post(array(
                    'post_title' => $page_data['title'],
                    'post_content' => $page_data['content'],
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'post_name' => $slug,
                ));
            }
        }
    }
    
    private static function set_default_options() {
        $default_options = array(
            'kcl_paystack_public_key' => '',
            'kcl_paystack_secret_key' => '',
            'kcl_paystack_test_mode' => true,
            'kcl_whatsapp_number' => '2348037100768',
            'kcl_booking_email' => get_option('admin_email'),
            'kcl_currency' => 'NGN',
            'kcl_check_in_time' => '14:00',
            'kcl_check_out_time' => '11:00',
            'kcl_loyalty_points_per_naira' => 1,
            'kcl_loyalty_bronze_threshold' => 0,
            'kcl_loyalty_silver_threshold' => 5000,
            'kcl_loyalty_gold_threshold' => 20000,
            'kcl_loyalty_platinum_threshold' => 50000,
        );
        
        foreach ($default_options as $key => $value) {
            if (get_option($key) === false) {
                add_option($key, $value);
            }
        }
    }
}

<?php
/**
 * AJAX Handler Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class KCL_Ajax {
    
    public static function init() {
        add_action('wp_ajax_kcl_filter_apartments', array(__CLASS__, 'filter_apartments'));
        add_action('wp_ajax_nopriv_kcl_filter_apartments', array(__CLASS__, 'filter_apartments'));
        add_action('wp_ajax_kcl_get_profile', array(__CLASS__, 'get_profile'));
        add_action('wp_ajax_nopriv_kcl_get_profile', array(__CLASS__, 'get_profile'));
        add_action('wp_ajax_kcl_check_availability', array(__CLASS__, 'check_availability'));
        add_action('wp_ajax_nopriv_kcl_check_availability', array(__CLASS__, 'check_availability'));
        add_action('wp_ajax_kcl_sync_offline_bookings', array(__CLASS__, 'sync_offline_bookings'));
        add_action('wp_ajax_nopriv_kcl_sync_offline_bookings', array(__CLASS__, 'sync_offline_bookings'));
    }
    
    public static function filter_apartments() {
        check_ajax_referer('kcl_nonce', 'nonce');
        
        $type = isset($_POST['type']) ? sanitize_text_field(wp_unslash($_POST['type'])) : '';
        $beds = isset($_POST['beds']) ? intval($_POST['beds']) : 0;
        $price_range = isset($_POST['price_range']) ? sanitize_text_field(wp_unslash($_POST['price_range'])) : '';
        
        $args = array(
            'post_type' => 'kcl_apartment',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        );
        
        $meta_query = array();
        
        if ($beds > 0) {
            if ($beds >= 3) {
                $meta_query[] = array(
                    'key' => '_kcl_bedrooms',
                    'value' => 3,
                    'compare' => '>=',
                    'type' => 'NUMERIC',
                );
            } else {
                $meta_query[] = array(
                    'key' => '_kcl_bedrooms',
                    'value' => $beds,
                    'compare' => '=',
                    'type' => 'NUMERIC',
                );
            }
        }
        
        if ($price_range) {
            if (strpos($price_range, '+') !== false) {
                $min_price = intval(str_replace('+', '', $price_range));
                $meta_query[] = array(
                    'key' => '_kcl_price_per_night',
                    'value' => $min_price,
                    'compare' => '>=',
                    'type' => 'NUMERIC',
                );
            } elseif (strpos($price_range, '-') !== false) {
                list($min, $max) = explode('-', $price_range);
                $meta_query[] = array(
                    'key' => '_kcl_price_per_night',
                    'value' => array(intval($min), intval($max)),
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC',
                );
            }
        }
        
        if (!empty($meta_query)) {
            $meta_query['relation'] = 'AND';
            $args['meta_query'] = $meta_query;
        }
        
        if ($type) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'kcl_apartment_type',
                    'field' => 'slug',
                    'terms' => $type,
                ),
            );
        }
        
        $apartments = new WP_Query($args);
        $results = array();
        
        if ($apartments->have_posts()) {
            while ($apartments->have_posts()) {
                $apartments->the_post();
                $price = get_post_meta(get_the_ID(), '_kcl_price_per_night', true);
                $bedrooms = get_post_meta(get_the_ID(), '_kcl_bedrooms', true);
                $bathrooms = get_post_meta(get_the_ID(), '_kcl_bathrooms', true);
                $size = get_post_meta(get_the_ID(), '_kcl_size', true);
                $amenities = get_post_meta(get_the_ID(), '_kcl_apartment_amenities', true);
                
                $results[] = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'permalink' => get_permalink(),
                    'image' => get_the_post_thumbnail_url(get_the_ID(), 'large'),
                    'price' => $price,
                    'bedrooms' => $bedrooms,
                    'bathrooms' => $bathrooms,
                    'size' => $size,
                    'amenities' => $amenities ? explode(',', $amenities) : array(),
                );
            }
            wp_reset_postdata();
        }
        
        wp_send_json_success($results);
    }
    
    public static function get_profile() {
        check_ajax_referer('kcl_nonce', 'nonce');
        
        $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
        
        if (!$email) {
            wp_send_json_error(array('message' => 'Please enter your email.'));
        }
        
        global $wpdb;
        $loyalty_table = $wpdb->prefix . 'kcl_loyalty';
        $bookings_table = $wpdb->prefix . 'kcl_bookings';
        
        $loyalty = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $loyalty_table WHERE guest_email = %s",
            $email
        ));
        
        $bookings = $wpdb->get_results($wpdb->prepare(
            "SELECT b.*, p.post_title as apartment_name 
             FROM $bookings_table b 
             LEFT JOIN {$wpdb->posts} p ON b.apartment_id = p.ID 
             WHERE b.guest_email = %s 
             ORDER BY b.created_at DESC",
            $email
        ));
        
        if (!$loyalty && empty($bookings)) {
            wp_send_json_error(array('message' => 'No bookings found for this email.'));
        }
        
        $profile = array(
            'name' => $loyalty ? $loyalty->guest_name : ($bookings ? $bookings[0]->guest_name : 'Guest'),
            'email' => $email,
            'points' => $loyalty ? $loyalty->points : 0,
            'tier' => $loyalty ? $loyalty->tier : 'bronze',
            'total_bookings' => $loyalty ? $loyalty->total_bookings : count($bookings),
            'total_spent' => $loyalty ? $loyalty->total_spent : 0,
            'bookings' => array(),
        );
        
        foreach ($bookings as $booking) {
            $profile['bookings'][] = array(
                'booking_id' => $booking->booking_id,
                'apartment' => $booking->apartment_name ?: 'Apartment',
                'check_in' => $booking->check_in,
                'check_out' => $booking->check_out,
                'guests' => $booking->guests,
                'total' => $booking->total_amount,
                'status' => $booking->booking_status,
                'payment_status' => $booking->payment_status,
            );
        }
        
        wp_send_json_success($profile);
    }
    
    public static function check_availability() {
        check_ajax_referer('kcl_nonce', 'nonce');
        
        $apartment_id = isset($_POST['apartment_id']) ? intval($_POST['apartment_id']) : 0;
        $check_in = isset($_POST['check_in']) ? sanitize_text_field(wp_unslash($_POST['check_in'])) : '';
        $check_out = isset($_POST['check_out']) ? sanitize_text_field(wp_unslash($_POST['check_out'])) : '';
        
        if (!$apartment_id || !$check_in || !$check_out) {
            wp_send_json_error(array('message' => 'Please provide all required information.'));
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'kcl_bookings';
        
        $conflicting = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name 
             WHERE apartment_id = %d 
             AND booking_status IN ('confirmed', 'pending')
             AND (
                 (check_in <= %s AND check_out > %s) OR
                 (check_in < %s AND check_out >= %s) OR
                 (check_in >= %s AND check_out <= %s)
             )",
            $apartment_id,
            $check_in, $check_in,
            $check_out, $check_out,
            $check_in, $check_out
        ));
        
        if ($conflicting > 0) {
            wp_send_json_error(array('message' => 'This apartment is not available for the selected dates.'));
        }
        
        wp_send_json_success(array('message' => 'Apartment is available!'));
    }
    
    public static function sync_offline_bookings() {
        check_ajax_referer('kcl_nonce', 'nonce');
        
        $bookings_json = isset($_POST['bookings']) ? wp_unslash($_POST['bookings']) : '';
        
        if (empty($bookings_json)) {
            wp_send_json_error(array('message' => 'No bookings to sync.'));
        }
        
        $bookings = json_decode($bookings_json, true);
        
        if (!is_array($bookings)) {
            wp_send_json_error(array('message' => 'Invalid booking data.'));
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'kcl_bookings';
        $synced = 0;
        
        foreach ($bookings as $booking) {
            $existing = $wpdb->get_var($wpdb->prepare(
                "SELECT id FROM $table_name WHERE booking_id = %s",
                $booking['booking_id']
            ));
            
            if (!$existing) {
                $result = $wpdb->insert(
                    $table_name,
                    array(
                        'booking_id' => sanitize_text_field($booking['booking_id']),
                        'apartment_id' => intval($booking['apartment_id']),
                        'guest_name' => sanitize_text_field($booking['guest_name']),
                        'guest_email' => sanitize_email($booking['guest_email']),
                        'guest_phone' => sanitize_text_field($booking['guest_phone']),
                        'check_in' => sanitize_text_field($booking['check_in']),
                        'check_out' => sanitize_text_field($booking['check_out']),
                        'guests' => intval($booking['guests']),
                        'total_amount' => floatval($booking['total_amount']),
                        'special_requests' => sanitize_textarea_field($booking['special_requests'] ?? ''),
                        'payment_status' => 'pending',
                        'booking_status' => 'pending',
                        'synced' => 1,
                    ),
                    array('%s', '%d', '%s', '%s', '%s', '%s', '%s', '%d', '%f', '%s', '%s', '%s', '%d')
                );
                
                if ($result) {
                    $synced++;
                }
            }
        }
        
        wp_send_json_success(array(
            'message' => $synced . ' booking(s) synced successfully.',
            'synced' => $synced,
        ));
    }
}

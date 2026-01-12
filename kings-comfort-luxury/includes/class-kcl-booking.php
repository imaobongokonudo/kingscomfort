<?php
/**
 * Booking Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class KCL_Booking {
    
    public static function init() {
        add_action('wp_ajax_kcl_create_booking', array(__CLASS__, 'create_booking'));
        add_action('wp_ajax_nopriv_kcl_create_booking', array(__CLASS__, 'create_booking'));
        add_action('wp_ajax_kcl_verify_payment', array(__CLASS__, 'verify_payment'));
        add_action('wp_ajax_nopriv_kcl_verify_payment', array(__CLASS__, 'verify_payment'));
    }
    
    public static function create_booking() {
        check_ajax_referer('kcl_nonce', 'nonce');
        
        $apartment_id = isset($_POST['apartment_id']) ? intval($_POST['apartment_id']) : 0;
        $guest_name = isset($_POST['guest_name']) ? sanitize_text_field(wp_unslash($_POST['guest_name'])) : '';
        $guest_email = isset($_POST['guest_email']) ? sanitize_email(wp_unslash($_POST['guest_email'])) : '';
        $guest_phone = isset($_POST['guest_phone']) ? sanitize_text_field(wp_unslash($_POST['guest_phone'])) : '';
        $check_in = isset($_POST['check_in']) ? sanitize_text_field(wp_unslash($_POST['check_in'])) : '';
        $check_out = isset($_POST['check_out']) ? sanitize_text_field(wp_unslash($_POST['check_out'])) : '';
        $guests = isset($_POST['guests']) ? intval($_POST['guests']) : 1;
        $special_requests = isset($_POST['special_requests']) ? sanitize_textarea_field(wp_unslash($_POST['special_requests'])) : '';
        
        if (!$apartment_id || !$guest_name || !$guest_email || !$guest_phone || !$check_in || !$check_out) {
            wp_send_json_error(array('message' => 'Please fill in all required fields.'));
        }
        
        // Handle demo apartments (negative IDs) or real apartments
        if ($apartment_id < 0) {
            // Demo apartment prices
            $demo_prices = array(-1 => 150000, -2 => 250000, -3 => 450000);
            $price_per_night = isset($demo_prices[$apartment_id]) ? $demo_prices[$apartment_id] : 150000;
        } else {
            $price_per_night = get_post_meta($apartment_id, '_kcl_price_per_night', true);
            if (empty($price_per_night)) {
                $price_per_night = 100000; // Default price
            }
        }
        
        $check_in_date = new DateTime($check_in);
        $check_out_date = new DateTime($check_out);
        $nights = $check_in_date->diff($check_out_date)->days;
        
        if ($nights < 1) {
            wp_send_json_error(array('message' => 'Check-out date must be after check-in date.'));
        }
        
        $total_amount = $price_per_night * $nights;
        $booking_id = 'KCL-' . strtoupper(wp_generate_password(8, false));
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'kcl_bookings';
        
        $result = $wpdb->insert(
            $table_name,
            array(
                'booking_id' => $booking_id,
                'apartment_id' => $apartment_id,
                'guest_name' => $guest_name,
                'guest_email' => $guest_email,
                'guest_phone' => $guest_phone,
                'check_in' => $check_in,
                'check_out' => $check_out,
                'guests' => $guests,
                'total_amount' => $total_amount,
                'special_requests' => $special_requests,
                'payment_status' => 'pending',
                'booking_status' => 'pending',
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%f', '%s', '%s', '%s')
        );
        
        if ($result) {
            wp_send_json_success(array(
                'booking_id' => $booking_id,
                'total_amount' => $total_amount,
                'email' => $guest_email,
                'message' => 'Booking created successfully.',
            ));
        } else {
            wp_send_json_error(array('message' => 'Failed to create booking. Please try again.'));
        }
    }
    
    public static function verify_payment() {
        check_ajax_referer('kcl_nonce', 'nonce');
        
        $reference = isset($_POST['reference']) ? sanitize_text_field(wp_unslash($_POST['reference'])) : '';
        $booking_id = isset($_POST['booking_id']) ? sanitize_text_field(wp_unslash($_POST['booking_id'])) : '';
        
        if (!$reference || !$booking_id) {
            wp_send_json_error(array('message' => 'Invalid payment reference.'));
        }
        
        $secret_key = get_option('kcl_paystack_secret_key');
        $test_mode = get_option('kcl_paystack_test_mode');
        
        if ($test_mode && empty($secret_key)) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'kcl_bookings';
            $wpdb->update(
                $table_name,
                array(
                    'payment_status' => 'completed',
                    'payment_reference' => $reference,
                    'booking_status' => 'confirmed',
                ),
                array('booking_id' => $booking_id),
                array('%s', '%s', '%s'),
                array('%s')
            );
            
            self::update_loyalty_points($booking_id);
            self::send_confirmation_email($booking_id);
            
            wp_send_json_success(array('message' => 'Payment verified (test mode).'));
            return;
        }
        
        $response = wp_remote_get(
            'https://api.paystack.co/transaction/verify/' . $reference,
            array(
                'headers' => array(
                    'Authorization' => 'Bearer ' . $secret_key,
                ),
            )
        );
        
        if (is_wp_error($response)) {
            wp_send_json_error(array('message' => 'Payment verification failed.'));
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if ($body['status'] && $body['data']['status'] === 'success') {
            global $wpdb;
            $table_name = $wpdb->prefix . 'kcl_bookings';
            
            $wpdb->update(
                $table_name,
                array(
                    'payment_status' => 'completed',
                    'payment_reference' => $reference,
                    'booking_status' => 'confirmed',
                ),
                array('booking_id' => $booking_id),
                array('%s', '%s', '%s'),
                array('%s')
            );
            
            self::update_loyalty_points($booking_id);
            self::send_confirmation_email($booking_id);
            
            wp_send_json_success(array('message' => 'Payment verified successfully.'));
        } else {
            wp_send_json_error(array('message' => 'Payment verification failed.'));
        }
    }
    
    private static function update_loyalty_points($booking_id) {
        global $wpdb;
        $bookings_table = $wpdb->prefix . 'kcl_bookings';
        $loyalty_table = $wpdb->prefix . 'kcl_loyalty';
        
        $booking = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $bookings_table WHERE booking_id = %s",
            $booking_id
        ));
        
        if (!$booking) return;
        
        $points_per_naira = get_option('kcl_loyalty_points_per_naira', 1);
        // Points earned: 1 point per 100 Naira spent (adjustable via settings)
        $points_earned = floor($booking->total_amount / 100) * $points_per_naira;
        
        $existing = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $loyalty_table WHERE guest_email = %s",
            $booking->guest_email
        ));
        
        if ($existing) {
            $new_points = $existing->points + $points_earned;
            $new_total_bookings = $existing->total_bookings + 1;
            $new_total_spent = $existing->total_spent + $booking->total_amount;
            
            $tier = self::calculate_tier($new_points);
            
            $wpdb->update(
                $loyalty_table,
                array(
                    'points' => $new_points,
                    'tier' => $tier,
                    'total_bookings' => $new_total_bookings,
                    'total_spent' => $new_total_spent,
                ),
                array('guest_email' => $booking->guest_email),
                array('%d', '%s', '%d', '%f'),
                array('%s')
            );
        } else {
            $tier = self::calculate_tier($points_earned);
            
            $wpdb->insert(
                $loyalty_table,
                array(
                    'guest_email' => $booking->guest_email,
                    'guest_name' => $booking->guest_name,
                    'points' => $points_earned,
                    'tier' => $tier,
                    'total_bookings' => 1,
                    'total_spent' => $booking->total_amount,
                ),
                array('%s', '%s', '%d', '%s', '%d', '%f')
            );
        }
    }
    
    private static function calculate_tier($points) {
        $platinum = get_option('kcl_loyalty_platinum_threshold', 50000);
        $gold = get_option('kcl_loyalty_gold_threshold', 20000);
        $silver = get_option('kcl_loyalty_silver_threshold', 5000);
        
        if ($points >= $platinum) return 'platinum';
        if ($points >= $gold) return 'gold';
        if ($points >= $silver) return 'silver';
        return 'bronze';
    }
    
    private static function send_confirmation_email($booking_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'kcl_bookings';
        
        $booking = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE booking_id = %s",
            $booking_id
        ));
        
        if (!$booking) return;
        
        $apartment = get_post($booking->apartment_id);
        $apartment_name = $apartment ? $apartment->post_title : 'Luxury Apartment';
        
        $to = $booking->guest_email;
        $subject = 'Booking Confirmation - ' . $booking_id . ' | Kings Comfort Luxury';
        
        $message = "Dear " . $booking->guest_name . ",\n\n";
        $message .= "Thank you for booking with Kings Comfort Luxury!\n\n";
        $message .= "Booking Details:\n";
        $message .= "Booking ID: " . $booking_id . "\n";
        $message .= "Apartment: " . $apartment_name . "\n";
        $message .= "Check-in: " . $booking->check_in . "\n";
        $message .= "Check-out: " . $booking->check_out . "\n";
        $message .= "Guests: " . $booking->guests . "\n";
        $message .= "Total Amount: ₦" . number_format($booking->total_amount, 0) . "\n\n";
        $message .= "Check-in Time: " . get_option('kcl_check_in_time', '14:00') . "\n";
        $message .= "Check-out Time: " . get_option('kcl_check_out_time', '11:00') . "\n\n";
        $message .= "Location: Kabusa, Abuja, Nigeria\n\n";
        $message .= "For any inquiries, contact us on WhatsApp: +234 803 710 0768\n\n";
        $message .= "We look forward to welcoming you!\n\n";
        $message .= "Best regards,\nKings Comfort Luxury Team";
        
        $headers = array('Content-Type: text/plain; charset=UTF-8');
        
        wp_mail($to, $subject, $message, $headers);
        
        $admin_email = get_option('kcl_booking_email', get_option('admin_email'));
        $admin_subject = 'New Booking: ' . $booking_id;
        wp_mail($admin_email, $admin_subject, $message, $headers);
    }
}

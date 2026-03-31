<?php
/**
 * Paystack Integration Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class KCL_Paystack {
    
    private static $public_key;
    private static $secret_key;
    private static $test_mode;
    
    public static function init() {
        self::$public_key = get_option('kcl_paystack_public_key', '');
        self::$secret_key = get_option('kcl_paystack_secret_key', '');
        self::$test_mode = get_option('kcl_paystack_test_mode', true);
        
        add_action('wp_ajax_kcl_init_payment', array(__CLASS__, 'init_payment'));
        add_action('wp_ajax_nopriv_kcl_init_payment', array(__CLASS__, 'init_payment'));
    }
    
    public static function get_public_key() {
        return self::$public_key;
    }
    
    public static function init_payment() {
        check_ajax_referer('kcl_nonce', 'nonce');
        
        $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
        $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
        $booking_id = isset($_POST['booking_id']) ? sanitize_text_field(wp_unslash($_POST['booking_id'])) : '';
        
        if (!$email || !$amount || !$booking_id) {
            wp_send_json_error(array('message' => 'Invalid payment data.'));
        }
        
        $amount_kobo = $amount * 100;
        
        wp_send_json_success(array(
            'public_key' => self::$public_key,
            'email' => $email,
            'amount' => $amount_kobo,
            'currency' => 'NGN',
            'ref' => 'KCL_' . time() . '_' . wp_rand(1000, 9999),
            'booking_id' => $booking_id,
            'callback_url' => home_url('/booking/?payment=success'),
        ));
    }
    
    public static function verify_transaction($reference) {
        $response = wp_remote_get(
            'https://api.paystack.co/transaction/verify/' . $reference,
            array(
                'headers' => array(
                    'Authorization' => 'Bearer ' . self::$secret_key,
                ),
            )
        );
        
        if (is_wp_error($response)) {
            return false;
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        return $body['status'] && $body['data']['status'] === 'success';
    }
}

<?php
/**
 * Offline Support Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class KCL_Offline {
    
    public static function init() {
        add_action('wp_head', array(__CLASS__, 'register_service_worker'));
        add_action('wp_ajax_kcl_get_offline_data', array(__CLASS__, 'get_offline_data'));
        add_action('wp_ajax_nopriv_kcl_get_offline_data', array(__CLASS__, 'get_offline_data'));
    }
    
    public static function register_service_worker() {
        ?>
        <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('<?php echo esc_url(KCL_PLUGIN_URL); ?>public/js/sw.js')
                    .then(function(registration) {
                        console.log('KCL ServiceWorker registered:', registration.scope);
                    })
                    .catch(function(error) {
                        console.log('KCL ServiceWorker registration failed:', error);
                    });
            });
        }
        </script>
        <?php
    }
    
    public static function get_offline_data() {
        $apartments = array();
        $query = new WP_Query(array(
            'post_type' => 'kcl_apartment',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ));
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $apartments[] = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'price' => get_post_meta(get_the_ID(), '_kcl_price_per_night', true),
                    'bedrooms' => get_post_meta(get_the_ID(), '_kcl_bedrooms', true),
                    'bathrooms' => get_post_meta(get_the_ID(), '_kcl_bathrooms', true),
                );
            }
            wp_reset_postdata();
        }
        
        wp_send_json_success(array(
            'apartments' => $apartments,
            'settings' => array(
                'currency' => get_option('kcl_currency', 'NGN'),
                'check_in_time' => get_option('kcl_check_in_time', '14:00'),
                'check_out_time' => get_option('kcl_check_out_time', '11:00'),
            ),
        ));
    }
}

<?php
/**
 * Custom Post Types
 */

if (!defined('ABSPATH')) {
    exit;
}

class KCL_Post_Types {
    
    public static function init() {
        add_action('init', array(__CLASS__, 'register_post_types'));
        add_action('init', array(__CLASS__, 'register_taxonomies'));
        add_action('add_meta_boxes', array(__CLASS__, 'add_meta_boxes'));
        add_action('save_post', array(__CLASS__, 'save_meta_boxes'));
    }
    
    public static function register_post_types() {
        // Apartments Post Type
        register_post_type('kcl_apartment', array(
            'labels' => array(
                'name' => __('Apartments', 'kings-comfort-luxury'),
                'singular_name' => __('Apartment', 'kings-comfort-luxury'),
                'add_new' => __('Add New Apartment', 'kings-comfort-luxury'),
                'add_new_item' => __('Add New Apartment', 'kings-comfort-luxury'),
                'edit_item' => __('Edit Apartment', 'kings-comfort-luxury'),
                'new_item' => __('New Apartment', 'kings-comfort-luxury'),
                'view_item' => __('View Apartment', 'kings-comfort-luxury'),
                'search_items' => __('Search Apartments', 'kings-comfort-luxury'),
                'not_found' => __('No apartments found', 'kings-comfort-luxury'),
                'not_found_in_trash' => __('No apartments found in trash', 'kings-comfort-luxury'),
                'menu_name' => __('Apartments', 'kings-comfort-luxury'),
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'apartment'),
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
            'menu_icon' => 'dashicons-building',
            'show_in_rest' => true,
        ));
        
        // Amenities Post Type
        register_post_type('kcl_amenity', array(
            'labels' => array(
                'name' => __('Amenities', 'kings-comfort-luxury'),
                'singular_name' => __('Amenity', 'kings-comfort-luxury'),
                'add_new' => __('Add New Amenity', 'kings-comfort-luxury'),
                'add_new_item' => __('Add New Amenity', 'kings-comfort-luxury'),
                'edit_item' => __('Edit Amenity', 'kings-comfort-luxury'),
                'new_item' => __('New Amenity', 'kings-comfort-luxury'),
                'view_item' => __('View Amenity', 'kings-comfort-luxury'),
                'search_items' => __('Search Amenities', 'kings-comfort-luxury'),
                'not_found' => __('No amenities found', 'kings-comfort-luxury'),
                'not_found_in_trash' => __('No amenities found in trash', 'kings-comfort-luxury'),
                'menu_name' => __('Amenities', 'kings-comfort-luxury'),
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'amenity'),
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
            'menu_icon' => 'dashicons-star-filled',
            'show_in_rest' => true,
        ));
        
        // Concierge Services Post Type
        register_post_type('kcl_concierge', array(
            'labels' => array(
                'name' => __('Concierge Services', 'kings-comfort-luxury'),
                'singular_name' => __('Concierge Service', 'kings-comfort-luxury'),
                'add_new' => __('Add New Service', 'kings-comfort-luxury'),
                'add_new_item' => __('Add New Concierge Service', 'kings-comfort-luxury'),
                'edit_item' => __('Edit Concierge Service', 'kings-comfort-luxury'),
                'new_item' => __('New Concierge Service', 'kings-comfort-luxury'),
                'view_item' => __('View Concierge Service', 'kings-comfort-luxury'),
                'search_items' => __('Search Concierge Services', 'kings-comfort-luxury'),
                'not_found' => __('No services found', 'kings-comfort-luxury'),
                'not_found_in_trash' => __('No services found in trash', 'kings-comfort-luxury'),
                'menu_name' => __('Concierge', 'kings-comfort-luxury'),
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'concierge-service'),
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
            'menu_icon' => 'dashicons-businessman',
            'show_in_rest' => true,
        ));
        
        // Loyalty Offers Post Type
        register_post_type('kcl_offer', array(
            'labels' => array(
                'name' => __('Loyalty Offers', 'kings-comfort-luxury'),
                'singular_name' => __('Loyalty Offer', 'kings-comfort-luxury'),
                'add_new' => __('Add New Offer', 'kings-comfort-luxury'),
                'add_new_item' => __('Add New Loyalty Offer', 'kings-comfort-luxury'),
                'edit_item' => __('Edit Loyalty Offer', 'kings-comfort-luxury'),
                'new_item' => __('New Loyalty Offer', 'kings-comfort-luxury'),
                'view_item' => __('View Loyalty Offer', 'kings-comfort-luxury'),
                'search_items' => __('Search Loyalty Offers', 'kings-comfort-luxury'),
                'not_found' => __('No offers found', 'kings-comfort-luxury'),
                'not_found_in_trash' => __('No offers found in trash', 'kings-comfort-luxury'),
                'menu_name' => __('Loyalty Offers', 'kings-comfort-luxury'),
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'loyalty-offer'),
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
            'menu_icon' => 'dashicons-awards',
            'show_in_rest' => true,
        ));
    }
    
    public static function register_taxonomies() {
        // Apartment Type Taxonomy
        register_taxonomy('kcl_apartment_type', 'kcl_apartment', array(
            'labels' => array(
                'name' => __('Apartment Types', 'kings-comfort-luxury'),
                'singular_name' => __('Apartment Type', 'kings-comfort-luxury'),
                'search_items' => __('Search Types', 'kings-comfort-luxury'),
                'all_items' => __('All Types', 'kings-comfort-luxury'),
                'edit_item' => __('Edit Type', 'kings-comfort-luxury'),
                'update_item' => __('Update Type', 'kings-comfort-luxury'),
                'add_new_item' => __('Add New Type', 'kings-comfort-luxury'),
                'new_item_name' => __('New Type Name', 'kings-comfort-luxury'),
                'menu_name' => __('Types', 'kings-comfort-luxury'),
            ),
            'hierarchical' => true,
            'show_admin_column' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'apartment-type'),
        ));
        
        // Amenity Category Taxonomy
        register_taxonomy('kcl_amenity_category', 'kcl_amenity', array(
            'labels' => array(
                'name' => __('Amenity Categories', 'kings-comfort-luxury'),
                'singular_name' => __('Amenity Category', 'kings-comfort-luxury'),
                'search_items' => __('Search Categories', 'kings-comfort-luxury'),
                'all_items' => __('All Categories', 'kings-comfort-luxury'),
                'edit_item' => __('Edit Category', 'kings-comfort-luxury'),
                'update_item' => __('Update Category', 'kings-comfort-luxury'),
                'add_new_item' => __('Add New Category', 'kings-comfort-luxury'),
                'new_item_name' => __('New Category Name', 'kings-comfort-luxury'),
                'menu_name' => __('Categories', 'kings-comfort-luxury'),
            ),
            'hierarchical' => true,
            'show_admin_column' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'amenity-category'),
        ));
        
        // Loyalty Tier Taxonomy
        register_taxonomy('kcl_loyalty_tier', 'kcl_offer', array(
            'labels' => array(
                'name' => __('Loyalty Tiers', 'kings-comfort-luxury'),
                'singular_name' => __('Loyalty Tier', 'kings-comfort-luxury'),
                'search_items' => __('Search Tiers', 'kings-comfort-luxury'),
                'all_items' => __('All Tiers', 'kings-comfort-luxury'),
                'edit_item' => __('Edit Tier', 'kings-comfort-luxury'),
                'update_item' => __('Update Tier', 'kings-comfort-luxury'),
                'add_new_item' => __('Add New Tier', 'kings-comfort-luxury'),
                'new_item_name' => __('New Tier Name', 'kings-comfort-luxury'),
                'menu_name' => __('Tiers', 'kings-comfort-luxury'),
            ),
            'hierarchical' => true,
            'show_admin_column' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'loyalty-tier'),
        ));
    }
    
    public static function add_meta_boxes() {
        // Apartment Meta Box
        add_meta_box(
            'kcl_apartment_details',
            __('Apartment Details', 'kings-comfort-luxury'),
            array(__CLASS__, 'apartment_meta_box_callback'),
            'kcl_apartment',
            'normal',
            'high'
        );
        
        // Amenity Meta Box
        add_meta_box(
            'kcl_amenity_details',
            __('Amenity Details', 'kings-comfort-luxury'),
            array(__CLASS__, 'amenity_meta_box_callback'),
            'kcl_amenity',
            'normal',
            'high'
        );
        
        // Concierge Meta Box
        add_meta_box(
            'kcl_concierge_details',
            __('Service Details', 'kings-comfort-luxury'),
            array(__CLASS__, 'concierge_meta_box_callback'),
            'kcl_concierge',
            'normal',
            'high'
        );
        
        // Offer Meta Box
        add_meta_box(
            'kcl_offer_details',
            __('Offer Details', 'kings-comfort-luxury'),
            array(__CLASS__, 'offer_meta_box_callback'),
            'kcl_offer',
            'normal',
            'high'
        );
    }
    
    public static function apartment_meta_box_callback($post) {
        wp_nonce_field('kcl_apartment_meta_box', 'kcl_apartment_meta_box_nonce');
        
        $price_per_night = get_post_meta($post->ID, '_kcl_price_per_night', true);
        $max_guests = get_post_meta($post->ID, '_kcl_max_guests', true);
        $bedrooms = get_post_meta($post->ID, '_kcl_bedrooms', true);
        $bathrooms = get_post_meta($post->ID, '_kcl_bathrooms', true);
        $size = get_post_meta($post->ID, '_kcl_size', true);
        $amenities = get_post_meta($post->ID, '_kcl_apartment_amenities', true);
        $gallery = get_post_meta($post->ID, '_kcl_gallery', true);
        $featured = get_post_meta($post->ID, '_kcl_featured', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="kcl_price_per_night"><?php esc_html_e('Price Per Night (₦)', 'kings-comfort-luxury'); ?></label></th>
                <td><input type="number" id="kcl_price_per_night" name="kcl_price_per_night" value="<?php echo esc_attr($price_per_night); ?>" class="regular-text" step="100" min="0"></td>
            </tr>
            <tr>
                <th><label for="kcl_max_guests"><?php esc_html_e('Max Guests', 'kings-comfort-luxury'); ?></label></th>
                <td><input type="number" id="kcl_max_guests" name="kcl_max_guests" value="<?php echo esc_attr($max_guests); ?>" class="small-text" min="1" max="20"></td>
            </tr>
            <tr>
                <th><label for="kcl_bedrooms"><?php esc_html_e('Bedrooms', 'kings-comfort-luxury'); ?></label></th>
                <td><input type="number" id="kcl_bedrooms" name="kcl_bedrooms" value="<?php echo esc_attr($bedrooms); ?>" class="small-text" min="0" max="20"></td>
            </tr>
            <tr>
                <th><label for="kcl_bathrooms"><?php esc_html_e('Bathrooms', 'kings-comfort-luxury'); ?></label></th>
                <td><input type="number" id="kcl_bathrooms" name="kcl_bathrooms" value="<?php echo esc_attr($bathrooms); ?>" class="small-text" min="0" max="20"></td>
            </tr>
            <tr>
                <th><label for="kcl_size"><?php esc_html_e('Size (sqm)', 'kings-comfort-luxury'); ?></label></th>
                <td><input type="number" id="kcl_size" name="kcl_size" value="<?php echo esc_attr($size); ?>" class="regular-text" min="0"></td>
            </tr>
            <tr>
                <th><label for="kcl_apartment_amenities"><?php esc_html_e('Apartment Amenities', 'kings-comfort-luxury'); ?></label></th>
                <td>
                    <textarea id="kcl_apartment_amenities" name="kcl_apartment_amenities" rows="5" class="large-text"><?php echo esc_textarea($amenities); ?></textarea>
                    <p class="description"><?php esc_html_e('Enter amenities separated by commas (e.g., WiFi, Air Conditioning, Kitchen)', 'kings-comfort-luxury'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="kcl_featured"><?php esc_html_e('Featured Apartment', 'kings-comfort-luxury'); ?></label></th>
                <td>
                    <label>
                        <input type="checkbox" id="kcl_featured" name="kcl_featured" value="1" <?php checked($featured, '1'); ?>>
                        <?php esc_html_e('Show on homepage', 'kings-comfort-luxury'); ?>
                    </label>
                </td>
            </tr>
        </table>
        <?php
    }
    
    public static function amenity_meta_box_callback($post) {
        wp_nonce_field('kcl_amenity_meta_box', 'kcl_amenity_meta_box_nonce');
        
        $icon = get_post_meta($post->ID, '_kcl_amenity_icon', true);
        $featured = get_post_meta($post->ID, '_kcl_amenity_featured', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="kcl_amenity_icon"><?php esc_html_e('Icon Class (Font Awesome)', 'kings-comfort-luxury'); ?></label></th>
                <td>
                    <input type="text" id="kcl_amenity_icon" name="kcl_amenity_icon" value="<?php echo esc_attr($icon); ?>" class="regular-text">
                    <p class="description"><?php esc_html_e('Enter Font Awesome icon class (e.g., fas fa-wifi, fas fa-swimming-pool)', 'kings-comfort-luxury'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="kcl_amenity_featured"><?php esc_html_e('Featured Amenity', 'kings-comfort-luxury'); ?></label></th>
                <td>
                    <label>
                        <input type="checkbox" id="kcl_amenity_featured" name="kcl_amenity_featured" value="1" <?php checked($featured, '1'); ?>>
                        <?php esc_html_e('Show on homepage', 'kings-comfort-luxury'); ?>
                    </label>
                </td>
            </tr>
        </table>
        <?php
    }
    
    public static function concierge_meta_box_callback($post) {
        wp_nonce_field('kcl_concierge_meta_box', 'kcl_concierge_meta_box_nonce');
        
        $price = get_post_meta($post->ID, '_kcl_service_price', true);
        $duration = get_post_meta($post->ID, '_kcl_service_duration', true);
        $icon = get_post_meta($post->ID, '_kcl_service_icon', true);
        $featured = get_post_meta($post->ID, '_kcl_service_featured', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="kcl_service_price"><?php esc_html_e('Service Price (₦)', 'kings-comfort-luxury'); ?></label></th>
                <td>
                    <input type="number" id="kcl_service_price" name="kcl_service_price" value="<?php echo esc_attr($price); ?>" class="regular-text" step="100" min="0">
                    <p class="description"><?php esc_html_e('Leave empty for "Contact for Price"', 'kings-comfort-luxury'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="kcl_service_duration"><?php esc_html_e('Duration', 'kings-comfort-luxury'); ?></label></th>
                <td>
                    <input type="text" id="kcl_service_duration" name="kcl_service_duration" value="<?php echo esc_attr($duration); ?>" class="regular-text">
                    <p class="description"><?php esc_html_e('e.g., "2 hours", "Full day", "Per trip"', 'kings-comfort-luxury'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="kcl_service_icon"><?php esc_html_e('Icon Class (Font Awesome)', 'kings-comfort-luxury'); ?></label></th>
                <td>
                    <input type="text" id="kcl_service_icon" name="kcl_service_icon" value="<?php echo esc_attr($icon); ?>" class="regular-text">
                    <p class="description"><?php esc_html_e('Enter Font Awesome icon class (e.g., fas fa-car, fas fa-utensils)', 'kings-comfort-luxury'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="kcl_service_featured"><?php esc_html_e('Featured Service', 'kings-comfort-luxury'); ?></label></th>
                <td>
                    <label>
                        <input type="checkbox" id="kcl_service_featured" name="kcl_service_featured" value="1" <?php checked($featured, '1'); ?>>
                        <?php esc_html_e('Show on homepage', 'kings-comfort-luxury'); ?>
                    </label>
                </td>
            </tr>
        </table>
        <?php
    }
    
    public static function offer_meta_box_callback($post) {
        wp_nonce_field('kcl_offer_meta_box', 'kcl_offer_meta_box_nonce');
        
        $discount = get_post_meta($post->ID, '_kcl_offer_discount', true);
        $min_points = get_post_meta($post->ID, '_kcl_offer_min_points', true);
        $expiry = get_post_meta($post->ID, '_kcl_offer_expiry', true);
        $code = get_post_meta($post->ID, '_kcl_offer_code', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="kcl_offer_discount"><?php esc_html_e('Discount (%)', 'kings-comfort-luxury'); ?></label></th>
                <td><input type="number" id="kcl_offer_discount" name="kcl_offer_discount" value="<?php echo esc_attr($discount); ?>" class="small-text" min="0" max="100"></td>
            </tr>
            <tr>
                <th><label for="kcl_offer_min_points"><?php esc_html_e('Minimum Points Required', 'kings-comfort-luxury'); ?></label></th>
                <td><input type="number" id="kcl_offer_min_points" name="kcl_offer_min_points" value="<?php echo esc_attr($min_points); ?>" class="regular-text" min="0"></td>
            </tr>
            <tr>
                <th><label for="kcl_offer_expiry"><?php esc_html_e('Expiry Date', 'kings-comfort-luxury'); ?></label></th>
                <td><input type="date" id="kcl_offer_expiry" name="kcl_offer_expiry" value="<?php echo esc_attr($expiry); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="kcl_offer_code"><?php esc_html_e('Promo Code', 'kings-comfort-luxury'); ?></label></th>
                <td><input type="text" id="kcl_offer_code" name="kcl_offer_code" value="<?php echo esc_attr($code); ?>" class="regular-text"></td>
            </tr>
        </table>
        <?php
    }
    
    public static function save_meta_boxes($post_id) {
        // Apartment meta
        if (isset($_POST['kcl_apartment_meta_box_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['kcl_apartment_meta_box_nonce'])), 'kcl_apartment_meta_box')) {
            if (isset($_POST['kcl_price_per_night'])) {
                update_post_meta($post_id, '_kcl_price_per_night', sanitize_text_field(wp_unslash($_POST['kcl_price_per_night'])));
            }
            if (isset($_POST['kcl_max_guests'])) {
                update_post_meta($post_id, '_kcl_max_guests', sanitize_text_field(wp_unslash($_POST['kcl_max_guests'])));
            }
            if (isset($_POST['kcl_bedrooms'])) {
                update_post_meta($post_id, '_kcl_bedrooms', sanitize_text_field(wp_unslash($_POST['kcl_bedrooms'])));
            }
            if (isset($_POST['kcl_bathrooms'])) {
                update_post_meta($post_id, '_kcl_bathrooms', sanitize_text_field(wp_unslash($_POST['kcl_bathrooms'])));
            }
            if (isset($_POST['kcl_size'])) {
                update_post_meta($post_id, '_kcl_size', sanitize_text_field(wp_unslash($_POST['kcl_size'])));
            }
            if (isset($_POST['kcl_apartment_amenities'])) {
                update_post_meta($post_id, '_kcl_apartment_amenities', sanitize_textarea_field(wp_unslash($_POST['kcl_apartment_amenities'])));
            }
            $featured = isset($_POST['kcl_featured']) ? '1' : '0';
            update_post_meta($post_id, '_kcl_featured', $featured);
        }
        
        // Amenity meta
        if (isset($_POST['kcl_amenity_meta_box_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['kcl_amenity_meta_box_nonce'])), 'kcl_amenity_meta_box')) {
            if (isset($_POST['kcl_amenity_icon'])) {
                update_post_meta($post_id, '_kcl_amenity_icon', sanitize_text_field(wp_unslash($_POST['kcl_amenity_icon'])));
            }
            $featured = isset($_POST['kcl_amenity_featured']) ? '1' : '0';
            update_post_meta($post_id, '_kcl_amenity_featured', $featured);
        }
        
        // Concierge meta
        if (isset($_POST['kcl_concierge_meta_box_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['kcl_concierge_meta_box_nonce'])), 'kcl_concierge_meta_box')) {
            if (isset($_POST['kcl_service_price'])) {
                update_post_meta($post_id, '_kcl_service_price', sanitize_text_field(wp_unslash($_POST['kcl_service_price'])));
            }
            if (isset($_POST['kcl_service_duration'])) {
                update_post_meta($post_id, '_kcl_service_duration', sanitize_text_field(wp_unslash($_POST['kcl_service_duration'])));
            }
            if (isset($_POST['kcl_service_icon'])) {
                update_post_meta($post_id, '_kcl_service_icon', sanitize_text_field(wp_unslash($_POST['kcl_service_icon'])));
            }
            $featured = isset($_POST['kcl_service_featured']) ? '1' : '0';
            update_post_meta($post_id, '_kcl_service_featured', $featured);
        }
        
        // Offer meta
        if (isset($_POST['kcl_offer_meta_box_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['kcl_offer_meta_box_nonce'])), 'kcl_offer_meta_box')) {
            if (isset($_POST['kcl_offer_discount'])) {
                update_post_meta($post_id, '_kcl_offer_discount', sanitize_text_field(wp_unslash($_POST['kcl_offer_discount'])));
            }
            if (isset($_POST['kcl_offer_min_points'])) {
                update_post_meta($post_id, '_kcl_offer_min_points', sanitize_text_field(wp_unslash($_POST['kcl_offer_min_points'])));
            }
            if (isset($_POST['kcl_offer_expiry'])) {
                update_post_meta($post_id, '_kcl_offer_expiry', sanitize_text_field(wp_unslash($_POST['kcl_offer_expiry'])));
            }
            if (isset($_POST['kcl_offer_code'])) {
                update_post_meta($post_id, '_kcl_offer_code', sanitize_text_field(wp_unslash($_POST['kcl_offer_code'])));
            }
        }
    }
}

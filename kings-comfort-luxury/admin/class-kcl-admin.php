<?php
/**
 * Admin Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class KCL_Admin {
    
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_admin_menu'));
        add_action('admin_init', array(__CLASS__, 'register_settings'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_admin_scripts'));
        add_filter('manage_kcl_apartment_posts_columns', array(__CLASS__, 'apartment_columns'));
        add_action('manage_kcl_apartment_posts_custom_column', array(__CLASS__, 'apartment_column_content'), 10, 2);
        add_filter('manage_edit-kcl_apartment_sortable_columns', array(__CLASS__, 'apartment_sortable_columns'));
    }
    
    public static function enqueue_admin_scripts($hook) {
        global $post_type;
        
        if (in_array($post_type, array('kcl_apartment', 'kcl_amenity', 'kcl_concierge', 'kcl_offer')) || 
            strpos($hook, 'kings-comfort') !== false) {
            wp_enqueue_media();
            wp_enqueue_style('kcl-admin-style', KCL_PLUGIN_URL . 'admin/css/admin.css', array(), KCL_VERSION);
            wp_enqueue_script('kcl-admin-script', KCL_PLUGIN_URL . 'admin/js/admin.js', array('jquery', 'wp-color-picker'), KCL_VERSION, true);
            wp_enqueue_style('wp-color-picker');
        }
    }
    
    public static function add_admin_menu() {
        add_menu_page(
            __('Kings Comfort', 'kings-comfort-luxury'),
            __('Kings Comfort', 'kings-comfort-luxury'),
            'manage_options',
            'kings-comfort',
            array(__CLASS__, 'dashboard_page'),
            'dashicons-building',
            30
        );
        
        add_submenu_page(
            'kings-comfort',
            __('Dashboard', 'kings-comfort-luxury'),
            __('Dashboard', 'kings-comfort-luxury'),
            'manage_options',
            'kings-comfort',
            array(__CLASS__, 'dashboard_page')
        );
        
        add_submenu_page(
            'kings-comfort',
            __('Bookings', 'kings-comfort-luxury'),
            __('Bookings', 'kings-comfort-luxury'),
            'manage_options',
            'kcl-bookings',
            array(__CLASS__, 'bookings_page')
        );
        
        add_submenu_page(
            'kings-comfort',
            __('Reviews', 'kings-comfort-luxury'),
            __('Reviews', 'kings-comfort-luxury'),
            'manage_options',
            'kcl-reviews',
            array(__CLASS__, 'reviews_page')
        );
        
        add_submenu_page(
            'kings-comfort',
            __('Loyalty Members', 'kings-comfort-luxury'),
            __('Loyalty Members', 'kings-comfort-luxury'),
            'manage_options',
            'kcl-loyalty',
            array(__CLASS__, 'loyalty_page')
        );
        
        add_submenu_page(
            'kings-comfort',
            __('Settings', 'kings-comfort-luxury'),
            __('Settings', 'kings-comfort-luxury'),
            'manage_options',
            'kcl-settings',
            array(__CLASS__, 'settings_page')
        );
    }
    
    public static function register_settings() {
        register_setting('kcl_settings', 'kcl_paystack_public_key', 'sanitize_text_field');
        register_setting('kcl_settings', 'kcl_paystack_secret_key', 'sanitize_text_field');
        register_setting('kcl_settings', 'kcl_paystack_test_mode', 'sanitize_text_field');
        register_setting('kcl_settings', 'kcl_whatsapp_number', 'sanitize_text_field');
        register_setting('kcl_settings', 'kcl_booking_email', 'sanitize_email');
        register_setting('kcl_settings', 'kcl_check_in_time', 'sanitize_text_field');
        register_setting('kcl_settings', 'kcl_check_out_time', 'sanitize_text_field');
        register_setting('kcl_settings', 'kcl_loyalty_points_per_naira', 'intval');
    }
    
    public static function apartment_columns($columns) {
        $new_columns = array();
        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;
            if ($key === 'title') {
                $new_columns['kcl_price'] = __('Price/Night', 'kings-comfort-luxury');
                $new_columns['kcl_bedrooms'] = __('Bedrooms', 'kings-comfort-luxury');
                $new_columns['kcl_featured'] = __('Featured', 'kings-comfort-luxury');
            }
        }
        return $new_columns;
    }
    
    public static function apartment_column_content($column, $post_id) {
        switch ($column) {
            case 'kcl_price':
                $price = get_post_meta($post_id, '_kcl_price_per_night', true);
                echo esc_html($price ? kcl_format_price($price) : '-');
                break;
            case 'kcl_bedrooms':
                echo esc_html(get_post_meta($post_id, '_kcl_bedrooms', true) ?: '-');
                break;
            case 'kcl_featured':
                $featured = get_post_meta($post_id, '_kcl_featured', true);
                echo $featured ? '<span class="dashicons dashicons-star-filled" style="color:#E2C369;"></span>' : '<span class="dashicons dashicons-star-empty"></span>';
                break;
        }
    }
    
    public static function apartment_sortable_columns($columns) {
        $columns['kcl_price'] = 'kcl_price';
        $columns['kcl_bedrooms'] = 'kcl_bedrooms';
        return $columns;
    }
    
    public static function dashboard_page() {
        global $wpdb;
        
        $bookings_table = $wpdb->prefix . 'kcl_bookings';
        $total_bookings = $wpdb->get_var("SELECT COUNT(*) FROM $bookings_table");
        $pending_bookings = $wpdb->get_var("SELECT COUNT(*) FROM $bookings_table WHERE booking_status = 'pending'");
        $confirmed_bookings = $wpdb->get_var("SELECT COUNT(*) FROM $bookings_table WHERE booking_status = 'confirmed'");
        $total_revenue = $wpdb->get_var("SELECT SUM(total_amount) FROM $bookings_table WHERE payment_status = 'completed'");
        
        $total_apartments = wp_count_posts('kcl_apartment')->publish;
        $total_amenities = wp_count_posts('kcl_amenity')->publish;
        
        $loyalty_table = $wpdb->prefix . 'kcl_loyalty';
        $total_members = $wpdb->get_var("SELECT COUNT(*) FROM $loyalty_table");
        
        $recent_bookings = $wpdb->get_results(
            "SELECT b.*, p.post_title as apartment_name 
             FROM $bookings_table b 
             LEFT JOIN {$wpdb->posts} p ON b.apartment_id = p.ID 
             ORDER BY b.created_at DESC LIMIT 5"
        );
        ?>
        <div class="wrap kcl-admin-wrap">
            <h1><img src="<?php echo esc_url(KCL_PLUGIN_URL); ?>public/images/logo.png" alt="" style="height:40px;vertical-align:middle;margin-right:10px;">Kings Comfort Luxury Dashboard</h1>
            
            <div class="kcl-dashboard-stats">
                <div class="kcl-stat-card">
                    <div class="kcl-stat-icon"><span class="dashicons dashicons-calendar-alt"></span></div>
                    <div class="kcl-stat-content">
                        <h3><?php echo esc_html($total_bookings); ?></h3>
                        <p>Total Bookings</p>
                    </div>
                </div>
                <div class="kcl-stat-card">
                    <div class="kcl-stat-icon pending"><span class="dashicons dashicons-clock"></span></div>
                    <div class="kcl-stat-content">
                        <h3><?php echo esc_html($pending_bookings); ?></h3>
                        <p>Pending Bookings</p>
                    </div>
                </div>
                <div class="kcl-stat-card">
                    <div class="kcl-stat-icon success"><span class="dashicons dashicons-yes-alt"></span></div>
                    <div class="kcl-stat-content">
                        <h3><?php echo esc_html($confirmed_bookings); ?></h3>
                        <p>Confirmed Bookings</p>
                    </div>
                </div>
                <div class="kcl-stat-card">
                    <div class="kcl-stat-icon revenue"><span class="dashicons dashicons-chart-bar"></span></div>
                    <div class="kcl-stat-content">
                        <h3><?php echo esc_html(kcl_format_price($total_revenue ?: 0)); ?></h3>
                        <p>Total Revenue</p>
                    </div>
                </div>
                <div class="kcl-stat-card">
                    <div class="kcl-stat-icon"><span class="dashicons dashicons-building"></span></div>
                    <div class="kcl-stat-content">
                        <h3><?php echo esc_html($total_apartments); ?></h3>
                        <p>Apartments</p>
                    </div>
                </div>
                <div class="kcl-stat-card">
                    <div class="kcl-stat-icon"><span class="dashicons dashicons-groups"></span></div>
                    <div class="kcl-stat-content">
                        <h3><?php echo esc_html($total_members); ?></h3>
                        <p>Loyalty Members</p>
                    </div>
                </div>
            </div>
            
            <div class="kcl-dashboard-grid">
                <div class="kcl-dashboard-card">
                    <h2>Quick Actions</h2>
                    <div class="kcl-quick-actions">
                        <a href="<?php echo esc_url(admin_url('post-new.php?post_type=kcl_apartment')); ?>" class="button button-primary">
                            <span class="dashicons dashicons-plus-alt"></span> Add Apartment
                        </a>
                        <a href="<?php echo esc_url(admin_url('post-new.php?post_type=kcl_amenity')); ?>" class="button">
                            <span class="dashicons dashicons-star-filled"></span> Add Amenity
                        </a>
                        <a href="<?php echo esc_url(admin_url('post-new.php?post_type=kcl_concierge')); ?>" class="button">
                            <span class="dashicons dashicons-businessman"></span> Add Service
                        </a>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=kcl-bookings')); ?>" class="button">
                            <span class="dashicons dashicons-list-view"></span> View Bookings
                        </a>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=kcl-settings')); ?>" class="button">
                            <span class="dashicons dashicons-admin-settings"></span> Settings
                        </a>
                    </div>
                </div>
                
                <div class="kcl-dashboard-card">
                    <h2>Recent Bookings</h2>
                    <?php if ($recent_bookings) : ?>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Guest</th>
                                <th>Apartment</th>
                                <th>Check-in</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_bookings as $booking) : ?>
                            <tr>
                                <td><strong><?php echo esc_html($booking->booking_id); ?></strong></td>
                                <td><?php echo esc_html($booking->guest_name); ?></td>
                                <td><?php echo esc_html($booking->apartment_name ?: 'N/A'); ?></td>
                                <td><?php echo esc_html($booking->check_in); ?></td>
                                <td>
                                    <span class="kcl-status kcl-status-<?php echo esc_attr($booking->booking_status); ?>">
                                        <?php echo esc_html(ucfirst($booking->booking_status)); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else : ?>
                    <p>No bookings yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
    
    public static function bookings_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'kcl_bookings';
        
        $action = isset($_GET['action']) ? sanitize_text_field(wp_unslash($_GET['action'])) : '';
        $booking_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($action === 'confirm' && $booking_id && wp_verify_nonce(isset($_GET['_wpnonce']) ? sanitize_text_field(wp_unslash($_GET['_wpnonce'])) : '', 'kcl_confirm_booking')) {
            $wpdb->update($table_name, array('booking_status' => 'confirmed'), array('id' => $booking_id), array('%s'), array('%d'));
            echo '<div class="notice notice-success"><p>Booking confirmed!</p></div>';
        }
        
        if ($action === 'cancel' && $booking_id && wp_verify_nonce(isset($_GET['_wpnonce']) ? sanitize_text_field(wp_unslash($_GET['_wpnonce'])) : '', 'kcl_cancel_booking')) {
            $wpdb->update($table_name, array('booking_status' => 'cancelled'), array('id' => $booking_id), array('%s'), array('%d'));
            echo '<div class="notice notice-warning"><p>Booking cancelled.</p></div>';
        }
        
        $status_filter = isset($_GET['status']) ? sanitize_text_field(wp_unslash($_GET['status'])) : '';
        $where = $status_filter ? $wpdb->prepare(" WHERE booking_status = %s", $status_filter) : '';
        
        $bookings = $wpdb->get_results(
            "SELECT b.*, p.post_title as apartment_name 
             FROM $table_name b 
             LEFT JOIN {$wpdb->posts} p ON b.apartment_id = p.ID 
             $where
             ORDER BY b.created_at DESC"
        );
        ?>
        <div class="wrap kcl-admin-wrap">
            <h1>Bookings Management</h1>
            
            <div class="kcl-filter-bar">
                <a href="<?php echo esc_url(admin_url('admin.php?page=kcl-bookings')); ?>" class="button <?php echo !$status_filter ? 'button-primary' : ''; ?>">All</a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=kcl-bookings&status=pending')); ?>" class="button <?php echo $status_filter === 'pending' ? 'button-primary' : ''; ?>">Pending</a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=kcl-bookings&status=confirmed')); ?>" class="button <?php echo $status_filter === 'confirmed' ? 'button-primary' : ''; ?>">Confirmed</a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=kcl-bookings&status=cancelled')); ?>" class="button <?php echo $status_filter === 'cancelled' ? 'button-primary' : ''; ?>">Cancelled</a>
            </div>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Guest</th>
                        <th>Contact</th>
                        <th>Apartment</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($bookings) : foreach ($bookings as $booking) : ?>
                    <tr>
                        <td><strong><?php echo esc_html($booking->booking_id); ?></strong></td>
                        <td><?php echo esc_html($booking->guest_name); ?></td>
                        <td>
                            <a href="mailto:<?php echo esc_attr($booking->guest_email); ?>"><?php echo esc_html($booking->guest_email); ?></a><br>
                            <small><?php echo esc_html($booking->guest_phone); ?></small>
                        </td>
                        <td><?php echo esc_html($booking->apartment_name ?: 'N/A'); ?></td>
                        <td><?php echo esc_html($booking->check_in); ?></td>
                        <td><?php echo esc_html($booking->check_out); ?></td>
                        <td><?php echo esc_html(kcl_format_price($booking->total_amount)); ?></td>
                        <td><span class="kcl-status kcl-status-<?php echo esc_attr($booking->payment_status); ?>"><?php echo esc_html(ucfirst($booking->payment_status)); ?></span></td>
                        <td><span class="kcl-status kcl-status-<?php echo esc_attr($booking->booking_status); ?>"><?php echo esc_html(ucfirst($booking->booking_status)); ?></span></td>
                        <td>
                            <?php if ($booking->booking_status === 'pending') : ?>
                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=kcl-bookings&action=confirm&id=' . $booking->id), 'kcl_confirm_booking')); ?>" class="button button-small button-primary">Confirm</a>
                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=kcl-bookings&action=cancel&id=' . $booking->id), 'kcl_cancel_booking')); ?>" class="button button-small">Cancel</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; else : ?>
                    <tr><td colspan="10">No bookings found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
    
    public static function reviews_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'kcl_reviews';
        
        $action = isset($_GET['action']) ? sanitize_text_field(wp_unslash($_GET['action'])) : '';
        $review_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($action === 'approve' && $review_id && wp_verify_nonce(isset($_GET['_wpnonce']) ? sanitize_text_field(wp_unslash($_GET['_wpnonce'])) : '', 'kcl_approve_review')) {
            $wpdb->update($table_name, array('status' => 'approved'), array('id' => $review_id), array('%s'), array('%d'));
        }
        
        if ($action === 'delete' && $review_id && wp_verify_nonce(isset($_GET['_wpnonce']) ? sanitize_text_field(wp_unslash($_GET['_wpnonce'])) : '', 'kcl_delete_review')) {
            $wpdb->delete($table_name, array('id' => $review_id), array('%d'));
        }
        
        $reviews = $wpdb->get_results(
            "SELECT r.*, p.post_title as apartment_name 
             FROM $table_name r 
             LEFT JOIN {$wpdb->posts} p ON r.apartment_id = p.ID 
             ORDER BY r.created_at DESC"
        );
        ?>
        <div class="wrap kcl-admin-wrap">
            <h1>Reviews Management</h1>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Guest</th>
                        <th>Apartment</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($reviews) : foreach ($reviews as $review) : ?>
                    <tr>
                        <td><?php echo esc_html($review->guest_name); ?><br><small><?php echo esc_html($review->guest_email); ?></small></td>
                        <td><?php echo esc_html($review->apartment_name ?: 'N/A'); ?></td>
                        <td><?php echo str_repeat('★', $review->rating) . str_repeat('☆', 5 - $review->rating); ?></td>
                        <td><?php echo esc_html(wp_trim_words($review->review, 20)); ?></td>
                        <td><span class="kcl-status kcl-status-<?php echo esc_attr($review->status); ?>"><?php echo esc_html(ucfirst($review->status)); ?></span></td>
                        <td><?php echo esc_html(gmdate('M d, Y', strtotime($review->created_at))); ?></td>
                        <td>
                            <?php if ($review->status === 'pending') : ?>
                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=kcl-reviews&action=approve&id=' . $review->id), 'kcl_approve_review')); ?>" class="button button-small button-primary">Approve</a>
                            <?php endif; ?>
                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=kcl-reviews&action=delete&id=' . $review->id), 'kcl_delete_review')); ?>" class="button button-small" onclick="return confirm('Delete this review?');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; else : ?>
                    <tr><td colspan="7">No reviews found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
    
    public static function loyalty_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'kcl_loyalty';
        $members = $wpdb->get_results("SELECT * FROM $table_name ORDER BY points DESC");
        ?>
        <div class="wrap kcl-admin-wrap">
            <h1>Loyalty Members</h1>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Points</th>
                        <th>Tier</th>
                        <th>Total Bookings</th>
                        <th>Total Spent</th>
                        <th>Member Since</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($members) : foreach ($members as $member) : ?>
                    <tr>
                        <td><strong><?php echo esc_html($member->guest_name); ?></strong></td>
                        <td><?php echo esc_html($member->guest_email); ?></td>
                        <td><?php echo esc_html(number_format($member->points)); ?></td>
                        <td><span class="kcl-tier kcl-tier-<?php echo esc_attr($member->tier); ?>"><?php echo esc_html(ucfirst($member->tier)); ?></span></td>
                        <td><?php echo esc_html($member->total_bookings); ?></td>
                        <td><?php echo esc_html(kcl_format_price($member->total_spent)); ?></td>
                        <td><?php echo esc_html(gmdate('M d, Y', strtotime($member->created_at))); ?></td>
                    </tr>
                    <?php endforeach; else : ?>
                    <tr><td colspan="7">No loyalty members yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
    
    public static function settings_page() {
        if (isset($_POST['kcl_save_settings']) && wp_verify_nonce(isset($_POST['kcl_settings_nonce']) ? sanitize_text_field(wp_unslash($_POST['kcl_settings_nonce'])) : '', 'kcl_save_settings')) {
            update_option('kcl_paystack_public_key', isset($_POST['kcl_paystack_public_key']) ? sanitize_text_field(wp_unslash($_POST['kcl_paystack_public_key'])) : '');
            update_option('kcl_paystack_secret_key', isset($_POST['kcl_paystack_secret_key']) ? sanitize_text_field(wp_unslash($_POST['kcl_paystack_secret_key'])) : '');
            update_option('kcl_paystack_test_mode', isset($_POST['kcl_paystack_test_mode']) ? 1 : 0);
            update_option('kcl_whatsapp_number', isset($_POST['kcl_whatsapp_number']) ? sanitize_text_field(wp_unslash($_POST['kcl_whatsapp_number'])) : '');
            update_option('kcl_booking_email', isset($_POST['kcl_booking_email']) ? sanitize_email(wp_unslash($_POST['kcl_booking_email'])) : '');
            update_option('kcl_check_in_time', isset($_POST['kcl_check_in_time']) ? sanitize_text_field(wp_unslash($_POST['kcl_check_in_time'])) : '');
            update_option('kcl_check_out_time', isset($_POST['kcl_check_out_time']) ? sanitize_text_field(wp_unslash($_POST['kcl_check_out_time'])) : '');
            echo '<div class="notice notice-success"><p>Settings saved!</p></div>';
        }
        ?>
        <div class="wrap kcl-admin-wrap">
            <h1>Kings Comfort Settings</h1>
            <form method="post" class="kcl-settings-form">
                <?php wp_nonce_field('kcl_save_settings', 'kcl_settings_nonce'); ?>
                
                <div class="kcl-settings-section">
                    <h2>Payment Settings (Paystack)</h2>
                    <table class="form-table">
                        <tr>
                            <th><label for="kcl_paystack_public_key">Public Key</label></th>
                            <td><input type="text" id="kcl_paystack_public_key" name="kcl_paystack_public_key" value="<?php echo esc_attr(get_option('kcl_paystack_public_key')); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th><label for="kcl_paystack_secret_key">Secret Key</label></th>
                            <td><input type="password" id="kcl_paystack_secret_key" name="kcl_paystack_secret_key" value="<?php echo esc_attr(get_option('kcl_paystack_secret_key')); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th><label for="kcl_paystack_test_mode">Test Mode</label></th>
                            <td><label><input type="checkbox" id="kcl_paystack_test_mode" name="kcl_paystack_test_mode" value="1" <?php checked(get_option('kcl_paystack_test_mode'), 1); ?>> Enable test mode</label></td>
                        </tr>
                    </table>
                </div>
                
                <div class="kcl-settings-section">
                    <h2>Contact Settings</h2>
                    <table class="form-table">
                        <tr>
                            <th><label for="kcl_whatsapp_number">WhatsApp Number</label></th>
                            <td><input type="text" id="kcl_whatsapp_number" name="kcl_whatsapp_number" value="<?php echo esc_attr(get_option('kcl_whatsapp_number', '2348037100768')); ?>" class="regular-text" placeholder="2348037100768"></td>
                        </tr>
                        <tr>
                            <th><label for="kcl_booking_email">Booking Notification Email</label></th>
                            <td><input type="email" id="kcl_booking_email" name="kcl_booking_email" value="<?php echo esc_attr(get_option('kcl_booking_email', get_option('admin_email'))); ?>" class="regular-text"></td>
                        </tr>
                    </table>
                </div>
                
                <div class="kcl-settings-section">
                    <h2>Booking Settings</h2>
                    <table class="form-table">
                        <tr>
                            <th><label for="kcl_check_in_time">Check-in Time</label></th>
                            <td><input type="time" id="kcl_check_in_time" name="kcl_check_in_time" value="<?php echo esc_attr(get_option('kcl_check_in_time', '14:00')); ?>"></td>
                        </tr>
                        <tr>
                            <th><label for="kcl_check_out_time">Check-out Time</label></th>
                            <td><input type="time" id="kcl_check_out_time" name="kcl_check_out_time" value="<?php echo esc_attr(get_option('kcl_check_out_time', '11:00')); ?>"></td>
                        </tr>
                    </table>
                </div>
                
                <p class="submit">
                    <input type="submit" name="kcl_save_settings" class="button button-primary" value="Save Settings">
                </p>
            </form>
        </div>
        <?php
    }
}

<?php
/**
 * Plugin Name: Whizz Chat
 * Plugin URI: https://www.whizzchat.com/
 * Description: Whizz Chat is a WordPress plugin based on Ajax and Live Chat system where buyers and seller can communicate with each other.
 * Version: 1.3
 * Text Domain: whizz-chat
 * Author: ScriptsBundle
 * Author URI: https://scriptsbundle.com/
 */
if (!defined('ABSPATH')) {
    die('-1');
}
if (!class_exists('Whizz_Chat')) {

    class Whizz_Chat {

        public $plugin_url;
        public $whizzchat_bot;
        public $whizzchat_box;
        public $plugin_dir;
        public $whizzChat_options;
        private static $instance = null;

        public static function get_instance() {
            if (!self::$instance)
                self::$instance = new self;
            return self::$instance;
        }

        public function __construct() {
            global $whizzChat_options;
            $whizzChat_options = get_option('whizz-chat-options');
            $whizzchat_bot = isset($whizzChat_options['whizzChat-bot']) && $whizzChat_options['whizzChat-bot'] == '1' ? TRUE : FALSE;
            $whizzChat_switch = isset($whizzChat_options['whizzChat-active']) && $whizzChat_options['whizzChat-active'] != '' ? $whizzChat_options['whizzChat-active'] : '0';
            $whizzChat_switch = isset($whizzChat_switch) && $whizzChat_switch != '' ? $whizzChat_switch : 0;
            $this->whizzchat_bot = $whizzchat_bot;
            $this->whizzchat_box = $whizzChat_switch;
            add_action('init', array($this, 'whizz_chat_load_plugin_textdomain'), 0);
            $this->whizz_chat_define_constants();
            $this->whizz_chat_files_inclusion();
            add_action('wp_enqueue_scripts', array($this, 'whizzChat_plugin_scripts'));
            add_action('admin_enqueue_scripts', array($this, 'whizzChat_admin_style_scripts'));
            add_action('wp_print_scripts', array($this, 'whizzChat_enqueued_fancybox'));
            add_action('admin_head', array($this, 'whizzChat_hide_notices'));
            add_filter('upload_mimes', array($this, 'whizzchat_mime_types_webp'));
            add_action('wp_logout', array($this, 'whizzChat_reset_all_cookies_data'));
            add_shortcode('whizchat_shortcode', array($this, 'whizchat_shorcode_fun'));
        }

        function whizzChat_reset_all_cookies_data() {
            $name = isset($_COOKIE['whizzChat_name']) && $_COOKIE['whizzChat_name'] != '' ? $_COOKIE['whizzChat_name'] : '';
            if ($name != '') {
                $cookie_name = 'whizchat-' . str_replace(' ', '-', $name);
                unset($_COOKIE[$cookie_name]);
                unset($_COOKIE['whizzChat_name']);
                unset($_COOKIE['whizzChat_email']);
                unset($_COOKIE['whizzChat-cookies']);
                unset($_COOKIE['whizz_sound_enable']);
                unset($_COOKIE['Whizz_Admin_Chat_id']);
            }
        }

        function whizzchat_mime_types_webp($mimes) {
            $mimes['webp'] = 'image/webp';
            $mimes["ogg|oga"] = 'audio/ogg';
            $mimes["wav"] = 'audio/wav';
            return $mimes;
        }

        function whizzChat_enqueued_fancybox() {
            global $wp_scripts;
            $enqueued_scripts = array();
            $fancy_box_enqueued = False;
            $array_scripts = array(
                'fancybox',
            );

            foreach ($wp_scripts->queue as $handle) {
                if (basename($wp_scripts->registered[$handle]->src) == 'jquery.fancybox.min.js') {
                    $fancy_box_enqueued = TRUE;
                }
            }
            if (!$fancy_box_enqueued) {
                wp_enqueue_script('fancybox');
            }
        }

        public function whizz_chat_load_plugin_textdomain() {

            if (empty($_COOKIE['whizz_sound_enable']) && !headers_sent()) {
                setcookie("whizz_sound_enable", 'on', time() + 31556926, COOKIEPATH, COOKIE_DOMAIN, 0, 0);
                $_COOKIE['whizz_sound_enable'] = 'on';
            }

            $locale = apply_filters('plugin_locale', get_locale(), 'whizz-chat');
            $dir = trailingslashit(WP_LANG_DIR);
            load_textdomain('whizz-chat', plugin_dir_path(__FILE__) . "languages/whizz-chat-" . $locale . '.mo');
            load_plugin_textdomain('whizz-chat', false, plugin_basename(dirname(__FILE__)) . '/languages');
        }

        public function whizz_chat_files_inclusion() {
            global $whizzChat_options;

            $whizzChat_options = get_option('whizz-chat-options');

            require_once 'includes/db-tables.php';

            if ($this->whizzchat_box) {
                require_once 'includes/action-hooks.php';
                require_once 'includes/chatbox-html.php';
            }
            require_once 'includes/api.php';
            require_once 'includes/class-whizz-chat.php';
            require_once 'includes/whizz-chat-functions.php';
           

            require_once 'options-api/api.php';

            if ($this->whizzchat_bot) {
                require_once 'includes/class-whizzchat-bot.php';
            }
            require_once 'includes/dashboard/whizzchat-dashboard.php';
        }

        public function whizz_chat_define_constants() {
            global $whizzChat_options, $wpdb, $whizz_tbl_sessions, $whizz_tblname_chat_message, $whizz_tblname_offline_msgs, $whizz_tbl_user_preferences;

            $whizzChat_options = get_option('whizz-chat-options');
            $whizz_tbl_sessions = $wpdb->prefix . "whizz_chat_sessions";
            $whizz_tblname_chat_message = $wpdb->prefix . "whizz_chat_message";
            $whizz_tbl_user_preferences = $wpdb->prefix . "whizz_user_preferences";
            $whizz_tblname_offline_msgs = $wpdb->prefix . "whizz_offline_messages";
            $this->plugin_url           = plugin_dir_url(__FILE__);
            $this->plugin_dir           = plugin_dir_path(__FILE__);
            $this->whizzChat_options    = $whizzChat_options;
        }
        public function whizz_chat_common_scripts() {
            global $whizzChat_options;

            $whizzChat_options = $this->whizzChat_options;
            $time_check = isset($whizzChat_options['whizzChat-check-time']) ? $whizzChat_options['whizzChat-check-time'] : '15';

            $voice_record = FALSE;
            if ($voice_record) {
                /*
                 * Audio recoder file
                 */
                wp_enqueue_script('RecordRTC', $this->plugin_url . 'assets/scripts/recorder/RecordRTC.js', array(), false, false);
                wp_enqueue_script('gif-recorder', $this->plugin_url . 'assets/scripts/recorder/gif-recorder.js', array(), false, false);
                wp_enqueue_script('getScreenId', $this->plugin_url . 'assets/scripts/recorder/getScreenId.js', array(), false, false);
                wp_enqueue_script('DetectRTC', $this->plugin_url . 'assets/scripts/recorder/DetectRTC.js', array(), false, false);
                wp_enqueue_script('adapter-latest', $this->plugin_url . 'assets/scripts/recorder/adapter-latest.js', array(), false, false);
                wp_enqueue_script('whizzchat-record-functions', $this->plugin_url . 'assets/scripts/recorder/whizzchat-record-functions.js', array('jquery'), false, true);
                /*
                 * Audio recoder file
                 */
            }

            
            $emoji_check = isset($whizzChat_options['whizzChat-allow-emojies']) ? $whizzChat_options['whizzChat-allow-emojies'] : true;
            
            if($emoji_check){
            wp_enqueue_style('emojionearea', $this->plugin_url . 'assets/css/emojionearea.min.css');
            wp_enqueue_script('emojionearea', $this->plugin_url . 'assets/scripts/emojionearea.min.js', array('jquery'), false, false);
            }
            wp_enqueue_style('fancybox', $this->plugin_url . 'assets/css/jquery.fancybox.min.css');
            wp_register_script('fancybox', $this->plugin_url . 'assets/scripts/jquery.fancybox.min.js', array('jquery'), false, false);

            wp_enqueue_style('toast', $this->plugin_url . 'assets/css/jquery.toast.min.css');
            wp_enqueue_script('toast', $this->plugin_url . 'assets/scripts/jquery.toast.min.js', array());

            wp_enqueue_style('leaflet', $this->plugin_url . 'assets/css/leaflet.css');
            wp_enqueue_script('leaflet', $this->plugin_url . 'assets/scripts/leaflet.js');

            wp_enqueue_script('whizz-chat-functions', $this->plugin_url . 'assets/scripts/whizz-chat-functions.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('whizz-chat-icons', 'https://kit.fontawesome.com/15ab445cda.js', array(), '1.0.0', true);

            wp_enqueue_style('counter-analog', $this->plugin_url . 'assets/css/counter/jquery.counter-analog.css');
            wp_enqueue_script('counter-analog', $this->plugin_url . 'assets/css/counter/jquery.counter.js', array(), false, true);


            $whizz_chat_comm_type = isset($whizzChat_options["whizzChat-comm-type"]) && $whizzChat_options["whizzChat-comm-type"] == '1' ? TRUE : FALSE;
            $socket_key = isset($whizzChat_options["whizzChat-agilepusher-key"]) && $whizzChat_options["whizzChat-agilepusher-key"] != '' ? $whizzChat_options["whizzChat-agilepusher-key"] : '';
            $max_box = isset($whizzChat_options["whizzChat-max-chatbox"]) && $whizzChat_options["whizzChat-max-chatbox"] != '' ? $whizzChat_options["whizzChat-max-chatbox"] : '2';
            $whizzChat_chatetype = isset($whizzChat_options['whizzChat-chat-type']) && $whizzChat_options['whizzChat-chat-type'] != '' ? $whizzChat_options['whizzChat-chat-type'] : '1';

            $show_emoji = isset($whizzChat_options["whizzChat-allow-emojies"]) && $whizzChat_options["whizzChat-allow-emojies"] != '' ? $whizzChat_options["whizzChat-allow-emojies"] : true;
            
            $whizz_chat_script_globals = array(
                'whizz_ajax_url' => admin_url('admin-ajax.php'),
                'whizz_server_token' => get_option("whizz_api_secret_token"),
                'whizz_restapi_endpoint' => get_rest_url('', 'whizz-chat-api/v1'),
                'whizz_user_token' => whizzChat::session_id(),
                'root' => esc_url_raw(rest_url()),
                'nonce' => wp_create_nonce('wp_rest'),
                'check_time' => $time_check * 1000,
                'max_chatbox_window' => $max_box,
                'whizz_chat_type' => $whizzChat_chatetype,
                'whizz_image' => whizzChat_upload_info('image'),
                'whizz_file' => whizzChat_upload_info('file'),
                'plugin_url' => $this->plugin_dir = plugin_dir_url(__FILE__),
                'whizzcaht_socket_key' => $socket_key,
                'whizzcaht_socket_url' => 'wss://socket.agilepusher.com',
                'whizzcaht_room' => md5($_SERVER['HTTP_HOST']) . '_' . 'whizchat',
                'enter_valid_email' => esc_html__('Please enter a valid email.', 'whizz-chat'),
                'provide_info' => esc_html__('Please provide your information above.', 'whizz-chat'),
                'browser_not_support' => esc_html__("Your browser don't support to send your location.", 'whizz-chat'),
                'enable_location' => esc_html__("Please enable location from your browser to send your location.", 'whizz-chat'),
                'not_valid_type' => esc_html__("Not A Valid File/Image type uploaded", 'whizz-chat'),
                'warning' => esc_html__("Warning", 'whizz-chat'),
                'type_something' => esc_html__("Type something here.", 'whizz-chat'),
                'select_chat_room' => esc_html__("Please select a chat person from the chat list.", 'whizz-chat'),
                'confirm_remove_db' => esc_html__("Are you sure you want to remove the chat messages and chat users.", 'whizz-chat'),
                'reset_db_success' => esc_html__("Reset database successfully.", 'whizz-chat'),
                'went_wrong' => esc_html__("OOoops !!!  something went wrong.", 'whizz-chat'),
                'plugin_directory' => $this->plugin_dir,
                'nothing_found_icon' => esc_html__('Nothing found icon', 'whizz-chat'),
                'select_chat_person' => esc_html__('Please select a chat person from the chat list.', 'whizz-chat'),
                'add_aphanemeric' => esc_html__('Add alphanemeric only.', 'whizz-chat'),
                'invalid_type_data' => esc_html__('Please input alphanumeric characters only.', 'whizz-chat'),
                'invalid_type_data2' => esc_html__('it seems your message is empty or you are adding invalid type of data..', 'whizz-chat'),
                'add_messages' => esc_html__('Please add messages.', 'whizz-chat'),
                'type_size_not_valid' => esc_html__('uploaded type and size and type is not correct.', 'whizz-chat'),
                'size_not_valid' => esc_html__('uploaded size is not correct.', 'whizz-chat'),
                'type_not_valid' => esc_html__('uploaded type is not correct.', 'whizz-chat'),
                'sm_type_size_not_valid' => esc_html__('Some of these uploaded data type and size and type is not correct.', 'whizz-chat'),
                'sm_size_not_valid' => esc_html__('Some of these uploaded data size is not correct.', 'whizz-chat'),
                'sm_type_not_valid' => esc_html__('Some of these uploaded data type is not correct.', 'whizz-chat'),
                'logo_img' => plugin_dir_url('/') . 'whizz-chat/assets/images/whizzchat-logo-dashboard.svg',
                 'show_emoji' =>  $show_emoji, 
               
            );

            wp_localize_script('whizz-chat-functions', 'whizzChat_ajax_object', $whizz_chat_script_globals);
            wp_localize_script('whizzchat-record-functions', 'whizzChat_media_obj', $whizz_chat_script_globals);

            if ($this->whizzchat_bot) {

                if (!is_admin()) {

                    wp_enqueue_style('whizzChat-botui-min', $this->plugin_url . 'assets/css/botui.min.css');
                    wp_enqueue_style('whizzChat-botui-theme-default', $this->plugin_url . 'assets/css/botui-theme-default.css');
                    wp_enqueue_style('whizzChat-bot-style', $this->plugin_url . 'assets/css/whizzchat-bot-style.css');

                    wp_enqueue_script('whizzChat-botui-min', $this->plugin_url . 'assets/scripts/botui.min.js');
                    wp_enqueue_script('whizzChat-vue', $this->plugin_url . 'assets/scripts/vue.js');
                }
            }

            /*
             * 
             * Socket integration
             * 
             */
            if ($whizz_chat_comm_type) {
                wp_enqueue_script('socket-io-min', $this->plugin_url . '/assets/scripts/socket.io.min.js', array('jquery'), '1.0.0', true);
                wp_enqueue_script('whizz-chat-live-core', $this->plugin_url . '/assets/scripts/whizz-chat-live.js', array('socket-io-min'), '1.0.0', true);
                wp_enqueue_script('whizz-chat-live-function', $this->plugin_url . '/assets/scripts/whizz-chat-live-functions.js', array('socket-io-min'), '1.0.0', true);
                wp_localize_script('whizz-chat-live-core', 'whizzChat_livecore', $whizz_chat_script_globals);
                wp_localize_script('whizz-chat-live-function', 'whizzChat_live_object', $whizz_chat_script_globals);
            }

            /*
             * 
             * End Socket integration
             * 
             */
        }

        public function whizzChat_plugin_scripts() {
            global $whizzChat_options;
            wp_enqueue_style('whizzChat-style', $this->plugin_url . 'assets/css/style.css');

            $this->whizz_chat_common_scripts();
            if (is_rtl()) {
                wp_enqueue_style('whizzChat-style-rtl', $this->plugin_url . 'assets/css/style-rtl.css');
            }
        }

        public function whizzChat_admin_style_scripts() {
            global $whizzChat_options;

            $whizzChat_options = $this->whizzChat_options;
            wp_enqueue_style('whizzChat-admin', $this->plugin_url . 'assets/admin/admin-style.css');
            wp_enqueue_script('whizzChat-admin-script', $this->plugin_url . 'assets/admin/admin-script.js', array(), false, true);
            $this->whizz_chat_common_scripts();
            if (is_rtl()) {
                wp_enqueue_style('whizzChat-admin-rtl', $this->plugin_url . 'assets/admin/admin-style-rtl.css');
            }
        }

        public function whizzChat_hide_notices() {
            $whizzchat_current_screen = get_current_screen();

            if (isset($whizzchat_current_screen->base) && $whizzchat_current_screen->base == 'toplevel_page_whizzChat-menu') {
                remove_all_actions('admin_notices');
            }

            if (isset($whizzchat_current_screen->base) && $whizzchat_current_screen->base == 'whizzchat_page_whizzchat-admin-chat') {
                remove_all_actions('admin_notices');
            }

            if (isset($whizzchat_current_screen->base) && $whizzchat_current_screen->base == 'whizzchat_page_whizzchat-docs') {
                remove_all_actions('admin_notices');
            }

            if (isset($whizzchat_current_screen->base) && $whizzchat_current_screen->base == 'whizzchat_page_whizzchat-bot') {
                remove_all_actions('admin_notices');
            }
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    $('#adminmenuwrap .toplevel_page_whizzChat-menu ul.wp-submenu-wrap li').eq(2).addClass('whizzchat-admin-chat-link');
                    $('.whizzchat-admin-chat-link a').attr('target', '_blank');
                });
            </script>
            <?php

        }

        function whizchat_shorcode_fun($atts) {
            global $wp_query, $whizzChat_options;

            $page_id = isset($wp_query->post->ID )   ?  $wp_query->post->ID   : "" ;
            
            
           $author_id = get_post_field( 'post_author', $page_id );
            
            
            $custom_class = isset($atts['class']) ? $atts['class'] : "";
            $shortcode_html = "";
            $img_path = $this->plugin_url . "assets/images/whizzchat-logo-dashboard3.png";
            $shortcode_html = '<div class="whizchat_widget_shortcode' . $custom_class . '">'
                    . '<a href="javascript:void(0)" data-page_id="' . $page_id . '"  data-user_id =  "'.$author_id.'"  class="chat_toggler"><img src="' . $img_path . '"></img></a></div>';

            return $shortcode_html;
        }

    }

}
Whizz_Chat::get_instance();
register_activation_hook(__FILE__, array('Whizz_chat_db_tables', 'whizz_create_db_tables'));

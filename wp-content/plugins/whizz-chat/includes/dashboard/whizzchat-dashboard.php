<?php
/*
 * whizzchat dashboard init class
 */

if (!class_exists('WhizzChat_Dashboard')) {

    Class WhizzChat_Dashboard {

        public $dashboard_url;
        public $dashboard_dir;

        public function __construct() {
            global $whizzChat_options;
            $whizzChat_options = get_option('whizz-chat-options');
            $this->dashboard_url = plugin_dir_url(__FILE__);
            $this->dashboard_dir = plugin_dir_path(__FILE__);
            add_filter('page_template', array($this, 'whizzchat_dashboard_page_template'));
            add_filter('theme_page_templates', array($this, 'whizzchat_page_template_selection'), 10, 4);
            add_action('wp_enqueue_scripts', array($this, 'whizzChat_dashboard_scripts'), 11);
            add_action('widgets_init', array($this, 'whizzchat_dashboard_siderbar_init'));
            add_action('wp_head', array($this, 'whizzchat_dashboard_custom_color'));

            $this->whizzchat_dashboard_files_inclusion();
            add_filter('show_admin_bar', array($this, 'whizzchat_hide_admin_bar'));
        }

        public function whizzchat_dashboard_custom_color() {
            global $whizzChat_options;

            $dashboard_clr = isset($whizzChat_options['whizzchat-dash-nav-bg']) ? $whizzChat_options['whizzchat-dash-nav-bg'] : '#665dfe';
            ?>
            <style>
                .message.self .message-content {
                    background-color: <?php echo whizzChat_return($dashboard_clr); ?> !important;
                }
                .whizzchat-main-layout .bg-primary, .contacts-list .contacts-item.active .contacts-link{
                    background-color: <?php echo whizzChat_return($dashboard_clr); ?> !important;  
                }
                .whizzchat-main-layout .contacts-list .contacts-item.active .contacts-link{
                    border: 1px solid <?php echo whizzChat_return($dashboard_clr); ?> !important;  
                    background-color: <?php echo whizzChat_return($dashboard_clr); ?> !important;  
                }
                .whizzchat-main-layout .contacts-list .contacts-item.active .contacts-link:hover{
                    border: 1px solid <?php echo whizzChat_return($dashboard_clr); ?> !important; 
                }
                .chat-footer .input-group-prepend .dropdown .dropdown-menu .dropdown-item:hover{
                    color: #fff !important; 
                    background-color: <?php echo whizzChat_return($dashboard_clr); ?> !important; 
                }
                .whizzchat-main-layout .btn-primary{
                    background-color: <?php echo whizzChat_return($dashboard_clr); ?> !important; 
                    border-color: <?php echo whizzChat_return($dashboard_clr); ?> !important; 
                }
                .whizz-dash-chat-body .chat-header .dropdown .dropdown-menu .dropdown-item:hover{
                    background-color: <?php echo whizzChat_return($dashboard_clr); ?> !important; 
                    color: #fff !important; 
                }
                .contacts-list .contacts-item:hover .contacts-link {
                    border-color: <?php echo whizzChat_return($dashboard_clr); ?> !important;
                }
                .whizzchat-sidebar .widget.widget_pages ul li a{
                    border-color: <?php echo whizzChat_return($dashboard_clr); ?> !important;
                }
                .whizzchat-sidebar .widget.widget_pages ul li a:hover{
                    background-color: <?php echo whizzChat_return($dashboard_clr); ?> !important; 
                }
            </style>
            <?php
        }

        public function whizzchat_dashboard_siderbar_init() {
            register_sidebar(array(
                'name' => esc_html__('Whizzchat Dashboard - Sidebar', 'adforest'),
                'id' => 'whizzchat_sidebar',
                'before_widget' => '<div class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="whizzchat-widget-title"><h4>',
                'after_title' => '</h4></div>',
                'class' => 'sidebar whizzchat-sidebar',
            ));
        }

        function whizzchat_dashboard_files_inclusion() {
            require_once 'includes/class-dashboard-html.php';
            require_once 'includes/class-dashboard-api.php';
        }

        function whizzchat_hide_admin_bar() {
            if (is_page_template('template-whizzchat.php')) {   
                return false;
            }
            else{
                return true;
            }
        }

        public function whizzChat_dashboard_scripts() {

            if (is_page_template('template-whizzchat.php')) {
                global $whizzChat_options;

                $time_check = isset($whizzChat_options['whizzChat-check-time']) ? $whizzChat_options['whizzChat-check-time'] : '15';
                $max_box = isset($whizzChat_options["whizzChat-max-chatbox"]) && $whizzChat_options["whizzChat-max-chatbox"] != '' ? $whizzChat_options["whizzChat-max-chatbox"] : '2';
                $whizzChat_chatetype = isset($whizzChat_options['whizzChat-chat-type']) && $whizzChat_options['whizzChat-chat-type'] != '' ? $whizzChat_options['whizzChat-chat-type'] : '1';
                $socket_key = isset($whizzChat_options["whizzChat-agilepusher-key"]) && $whizzChat_options["whizzChat-agilepusher-key"] != '' ? $whizzChat_options["whizzChat-agilepusher-key"] : '';

                
                $show_emoji = isset($whizzChat_options["whizzChat-allow-emojies"]) && $whizzChat_options["whizzChat-allow-emojies"] != '' ? $whizzChat_options["whizzChat-allow-emojies"] : true;
                wp_enqueue_style('whizzchat-inter', $this->dashboard_url . 'assets/webfonts/inter/inter.css', array(), rand(12, 999), 'all');
                wp_enqueue_style('whizzchat-app', $this->dashboard_url . 'assets/css/app.min.css', array(), rand(12, 999), 'all');
                wp_enqueue_style('whizzchat-dashboard-style', $this->dashboard_url . 'assets/css/whizzchat-dashboard-style.css', array(), rand(12, 999), 'all');
                if (is_rtl()) {
                    wp_enqueue_style('whizzchat-dashboard-rtl', $this->dashboard_url . 'assets/css/whizzchat-dashboard-rtl.css', array(), rand(12, 999), 'all');
                }
                wp_enqueue_script('bootstrap-bundle', $this->dashboard_url . 'assets/vendors/bootstrap/bootstrap.bundle.min.js', array('jquery'), false, true);
                wp_enqueue_script('magnific-popup', $this->dashboard_url . 'assets/vendors/magnific-popup/jquery.magnific-popup.min.js', array('jquery'), false, true);
                wp_enqueue_script('svg-inject', $this->dashboard_url . 'assets/vendors/svg-inject/svg-inject.min.js', array('jquery'), false, true);

                wp_enqueue_script('modal-steps', $this->dashboard_url . 'assets/vendors/modal-stepes/modal-steps.min.js', array('jquery'), false, true);
                wp_enqueue_script('whizzchat-app', $this->dashboard_url . 'assets/js/app.js', array('jquery'), false, true);
                wp_enqueue_script('whizzchat-dashboard-functions', $this->dashboard_url . 'assets/js/whizzchat-dashboard-functions.js', array('jquery'), false, true);

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
                    'logo_img'=> plugin_dir_url('/') . 'whizz-chat/assets/images/whizzchat-logo-dashboard.svg',
                    'welcome_msg'=> esc_html__('Welcome to WhizzChat Messenger', 'whizz-chat'),
                    'plz_select'=> esc_html__('Please select a chat to Start messaging.', 'whizz-chat'),
                    'show_emoji' =>  $show_emoji, 
                    
                );
                wp_localize_script('whizzchat-dashboard-functions', 'whizzChat_dashboard_object', $whizz_chat_script_globals);
            }
        }
        public function whizzchat_dashboard_page_template($page_template) {
            if (get_page_template_slug() == 'template-whizzchat.php') {
                $page_template = dirname(__FILE__) . '/page-template/template-whizzchat.php';
            }
            return $page_template;
        }
        public function whizzchat_page_template_selection($post_templates, $wp_theme, $post, $post_type) {
            $post_templates['template-whizzchat.php'] = __('WhizzChat Dashboard');
            return $post_templates;
        }
    }
}
new WhizzChat_Dashboard();
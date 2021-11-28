<?php
if (!defined('ABSPATH'))
    exit;

class WhizzChat_Setting_Page {

    private $dir;
    private $file;
    private $plugin_name;
    private $plugin_slug;
    private $textdomain;
    private $options;
    private $settings;

    public function __construct($plugin_name, $plugin_slug, $file) {
        $this->file = $file;
        $this->plugin_slug = $plugin_slug;
        $this->plugin_name = $plugin_name;
        $this->textdomain = 'whizz-chat';
        add_action('admin_init', array($this, 'init'));
        add_action('admin_menu', array($this, 'add_menu_item'));
        add_filter('plugin_action_links_' . plugin_basename($this->file), array($this, 'add_settings_link'));
    }

    /**
     * Initialise settings
     * @return void
     */
    public function init() {
       $this->settings = $this->settings_fields();
        $this->options = $this->get_options();
        $this->register_settings();
    }

    /**
     * Add settings page to admin menu
     * @return void
     */
    public function add_menu_item() {
        global $whizzChat_options;


        $whizzChat_options = get_option('whizz-chat-options');
        $dashboard_page = isset($whizzChat_options['whizzChat-dashboard-page']) && $whizzChat_options['whizzChat-dashboard-page'] != '' ? $whizzChat_options['whizzChat-dashboard-page'] : 'javascript:void(0)';
        $dashboard_link = 'javascript:void(0)';
        if ($dashboard_page != '') {
            $dashboard_link = get_permalink($dashboard_page);
        }
        add_menu_page('WhizzChat Setting', 'WhizzChat', 'manage_options', 'whizzChat-menu', array($this, 'whizzchat_settings_page'), 'dashicons-testimonial', 31);
        add_submenu_page('whizzChat-menu', __('Admin Chat', 'whizz-chat'), __('Admin Chat', 'whizz-chat'), 'manage_options', $dashboard_link);
    }

    /**
     * Add settings link to plugin list table
     * @param  array $links Existing links
     * @return array 		Modified links
     */
    public function add_settings_link($links) {
        $settings_link = '<a href="options-general.php?page=' . $this->plugin_slug . '">' . __('Settings', $this->textdomain) . '</a>';
        array_push($links, $settings_link);
        return $links;
    }

    /**
     * Build settings fields
     * @return array Fields to be displayed on settings page
     */
    private function settings_fields() {
        global $whizzChat_options;

        $whizzChat_options = get_option('whizz-chat-options');

        $whizzChat_between = isset($whizzChat_options['whizzChat-chat-between']) && $whizzChat_options['whizzChat-chat-between'] != '' ? $whizzChat_options['whizzChat-chat-between'] : '0';


        $whizzChat_pagetype = isset($whizzChat_options['whizzChat-chat-type']) && $whizzChat_options['whizzChat-chat-type'] != '' ? $whizzChat_options['whizzChat-chat-type'] : '1';

        $admin_sec_class = 'whizz-chat-admin-dropdown';
        if ($whizzChat_between == 0) { // for admin only
            $admin_sec_class = 'whizz-chat-admin-dropdown hide';
        }

        $logintype_select = 'whizzchat-login-type hide';
        if (isset($whizzChat_pagetype) && $whizzChat_pagetype == 2) { // for admin only
            $logintype_select = 'whizzchat-login-type hide';
        }

        $logintype_field = 'whizzchat-logintype-field hide';
        if (isset($whizzChat_pagetype) && $whizzChat_pagetype == 2) { // for admin only
            $logintype_field = 'whizzchat-logintype-field';
        }

        $user_args = array(
            'role' => 'administrator',
            'fields' => array('display_name', 'ID', 'user_email')
        );
        $all_admins = get_users($user_args);


        $admins_arr = array();
        if (isset($all_admins) && !empty($all_admins) && is_array($all_admins)) {
            foreach ($all_admins as $each_admin) {
                $admins_arr[$each_admin->ID] = $each_admin->display_name . ' ( ' . $each_admin->user_email . ' )';
            }
        }

         

    

       $defaults = array(
        'numberposts'      => -1,
        'fields'         => 'ids',
        'post_status' => 'publish',
        'post_type'        => 'page',
        
    );

      $pages    =   get_posts($defaults);
      $admin_pages   =  array();
      if(isset($pages)  && !empty($pages)){

        foreach ($pages as $page) {
           if($page != ""){
          $admin_pages[$page] = get_the_title($page);
           }            # code...
        }
      }    
        $settings['basic_settings'] = array(
            'title' => __('Basic Settings', $this->textdomain),
            'description' => __('Here you can customize your chat settings.', $this->textdomain),
            'fields' => array(
                array(
                    'id' => 'whizzChat-active',
                    'label' => __('WhizzChat', $this->textdomain),
                    'description' => __('Use these options to completely stop/start the chat.', $this->textdomain),
                    'type' => 'radio',
                    'options' => array(
                        '0' => __('Disable', $this->textdomain),
                        '1' => __('Enable', $this->textdomain),
                    ),
                    'default' => '0'
                ),
                array(
                    'id' => 'whizzChat-comm-type',
                    'label' => __('Communication Type', $this->textdomain),
                    'description' => __('<span><b style="color:red">Note : </b> For Real Time communication you need to follow the Real Time Communication Tab Settings.</span>', $this->textdomain),
                    'type' => 'radio',
                    'options' => array(
                        '0' => __('Ajax Based', $this->textdomain),
                        '1' => __('Real Time', $this->textdomain),
                    ),
                    'default' => '0',
                ),
                array(
                    'id' => 'whizzChat-chat-between',
                    'label' => __('Chat Between', $this->textdomain),
                    'description' => __('Select option to select a chat between.', $this->textdomain),
                    'type' => 'radio',
                    'options' => array(
                        '0' => __('Authors', $this->textdomain),
                        '1' => __('Admin Only', $this->textdomain),
                        '2' => __('Both', $this->textdomain),
                    ),
                    'default' => '0',
                    'class' => 'whizz-chat-chat-between',
                ),
                array(
                    'id' => 'whizzChat-admin-value',
                    'label' => __('Select Admin', $this->textdomain),
                    'description' => __('Select admin for chat.', $this->textdomain),
                    'type' => 'select',
                    'options' => $admins_arr,
                    'default' => '1',
                    'class' => $admin_sec_class,
                ),
                array(
                    'id' => 'whizzChat-admin-page',
                    'label' => __('General Queries Page', $this->textdomain),
                    'description' => __('Select the admin page for general queries.', $this->textdomain),
                    'type' => 'select',
                    'options' => $admin_pages,
                    'default' => '1',
                    'class' => $admin_sec_class,
                ),
                array(
                    'id' => 'whizzChat-chat-type',
                    'label' => __('Chat User Type', $this->textdomain),
                    'description' => __('Select option to select a chat type.', $this->textdomain),
                    'type' => 'radio',
                    'options' => array(
                        '0' => __('Anyone', $this->textdomain),
                        '1' => __('Require (Name and Email)', $this->textdomain),
                        '2' => __('Only WordPress Login', $this->textdomain),
                    ),
                    'default' => '1',
                    'class' => 'whizzchat-usertype',
                ),
                array(
                    'id' => 'whizzChat-login-page-type',
                    'label' => __('Login Type', $this->textdomain),
                    'description' => __('select the type of login wether you are using page/popup for site user login.', $this->textdomain),
                    'type' => 'select',
                    'options' => array(
                        'url' => __('Page Url', $this->textdomain),
                        'popup' => __('Popup', $this->textdomain),
                    ),
                    'default' => '1',
                    'class' => $logintype_select,
                ),
                array(
                    'id' => 'whizzChat-login-url',
                    'label' => __('Login URL/popup', $this->textdomain),
                    'description' => __('Pleae enter the login url or add the popup window class/id.it redirects the chatbox window to the login page.', $this->textdomain),
                    'type' => 'text',
                    'default' => '#',
                    'placeholder' => __(site_url(), $this->textdomain),
                    'class' => $logintype_field,
                ),
                array(
                    'id' => 'whizzChat-max-chatbox',
                    'label' => __('Max chat box window', $this->textdomain),
                    'description' => __('Pleae enter the number of chat box window open while chatting.', $this->textdomain),
                    'type' => 'number',
                    'default' => '2',
                    'placeholder' => __('2', $this->textdomain)
                ),
                array(
                    'id' => 'whizzChat-check-time',
                    'label' => __('Notification Check Time', $this->textdomain),
                    'description' => __('Please check message notification ajax time in second(s).', $this->textdomain),
                    'type' => 'number',
                    'default' => '15',
                    'placeholder' => __('15', $this->textdomain)
                ),
                array(
                    'id' => 'whizzChat-boxShow-on',
                    'label' => __('Show Chat Box on', $this->textdomain),
                    'description' => __('Select where you want to show the chatbox.', $this->textdomain),
                    'type' => 'checkbox_multi',
                    'options' => whizzChat_showChatBox_on(),
                    'default' => array('post'),
                ),
                array(
                    'id' => 'whizzChat-shortcode-allow',
                    'label' => __('WhizzChat Shortcode', $this->textdomain),
                    'description' => __('Use this option to add a shortcode; this will prevent chats from auto-populating; instead, a button click will open the specific chat. You can create your own customised button by using the  { class "chat toggler" and the data-page id = "relevent post id" }', $this->textdomain),
                    'type' => 'radio',
                    'options' => array(
                        '0' => __('Disable', $this->textdomain),
                        '1' => __('Enable', $this->textdomain),
                    ),
                    'default' => '0'
                ),
                
                array(
                    'id' => 'whizzChat-chatlist',
                    'label' => __('Hide Show chat list', $this->textdomain),
                    'description' => __('Use this option to hide show chat list on a page', $this->textdomain),
                    'type' => 'radio',
                    'options' => array(
                        '0' => __('Disable', $this->textdomain),
                        '1' => __('Enable', $this->textdomain),
                    ),
                    'default' => '1'
                ),
                
            )
        );


        $settings['upload_settings'] = array(
            'title' => __('Media Settings', $this->textdomain),
            'description' => __('Set the Media Settings according to your requirement.', $this->textdomain),
            'fields' => array(
                array(
                    'id' => 'whizzChat-allow-image',
                    'label' => __('Allow Image Upload', $this->textdomain),
                    'description' => __('Enable image upload feature in chat', $this->textdomain),
                    'type' => 'radio',
                    'options' => array(
                        '1' => __('Enabled', $this->textdomain),
                        '0' => __('Disabled', $this->textdomain),
                    ),
                    'default' => '1'
                ),
                array(
                    'id' => 'whizzChat-image-size',
                    'label' => __('Image Size', $this->textdomain),
                    'description' => __('Enter image max upload size in KB', $this->textdomain),
                    'type' => 'text',
                    'default' => '2000',
                    'placeholder' => __('e.g 2 or 0.8 ', $this->textdomain)
                ),
                array(
                    'id' => 'whizzChat-image-format',
                    'label' => __('Image Format', $this->textdomain),
                    'description' => __('Enter upload allowed image format seprate by ,', $this->textdomain),
                    'type' => 'text',
                    'default' => 'png,jpg,jpeg',
                    'placeholder' => 'png,jpg,jpeg'
                ),
                /* File Upload */
                array(
                    'id' => 'whizzChat-allow-file',
                    'label' => __('Allow file Upload', $this->textdomain),
                    'description' => __('Enable file upload feature in chat', $this->textdomain),
                    'type' => 'radio',
                    'options' => array(
                        '1' => __('Enabled', $this->textdomain),
                        '0' => __('Disabled', $this->textdomain),
                    ),
                    'default' => '1'
                ),
                array(
                    'id' => 'whizzChat-file-size',
                    'label' => __('File Size', $this->textdomain),
                    'description' => __('Enter file max upload size in KB', $this->textdomain),
                    'type' => 'text',
                    'default' => '3000',
                    'placeholder' => __('e.g 2 or 0.8 ', $this->textdomain)
                ),
                array(
                    'id' => 'whizzChat-file-format',
                    'label' => __('File Format', $this->textdomain),
                    'description' => __('Enter upload allowed file format seprate by ,', $this->textdomain),
                    'type' => 'text',
                    'default' => 'zip,doc,pdf,txt',
                    'placeholder' => 'zip,doc,pdf'
                ),
                array(
                    'id' => 'whizzChat-allow-location',
                    'label' => __('Allow Location', $this->textdomain),
                    'description' => __('Enable location feature in chat', $this->textdomain),
                    'type' => 'radio',
                    'options' => array(
                        '1' => __('Enabled', $this->textdomain),
                        '0' => __('Disabled', $this->textdomain),
                    ),
                    'default' => '1'
                ),
                array(
                    'id' => 'whizzChat-allow-emojies',
                    'label' => __('Allow Emojies', $this->textdomain),
                    'description' => __('Enable emojies feature in chat', $this->textdomain),
                    'type' => 'radio',
                    'options' => array(
                        '1' => __('Enabled', $this->textdomain),
                        '0' => __('Disabled', $this->textdomain),
                    ),
                    'default' => '1'
                ),
            )
        );

        $settings['design_settings'] = array(
            'title' => __('Design Settings', $this->textdomain),
            'description' => __('Set the Design Settings according to your requirement.', $this->textdomain),
            'fields' => array(
                array(
                    'id' => 'whizzChat-chatlist-head-color',
                    'label' => __('Chat List head Color', $this->textdomain),
                    'description' => __('Select the Chat List Head Color.', $this->textdomain),
                    'type' => 'color',
                    'default' => '#000000',
                    'placeholder' => '',
                ),
                array(
                    'id' => 'chatlist-head-txt-color',
                    'label' => __('Chat List head Text Color', $this->textdomain),
                    'description' => __('Select the Chat List header Text Color.', $this->textdomain),
                    'type' => 'color',
                    'default' => '#FFFFFF',
                    'placeholder' => '',
                ),
                array(
                    'id' => 'whizzChat-chatbox-head-color',
                    'label' => __('Chat Box head Color', $this->textdomain),
                    'description' => __('Select the Chat Box Head Color.', $this->textdomain),
                    'type' => 'color',
                    'default' => '#000000',
                    'placeholder' => '',
                ),
                array(
                    'id' => 'chatbox-primary-color',
                    'label' => __('Chat Box Primary Text Color', $this->textdomain),
                    'description' => __('Select the Chat Box header Text Primary Color.', $this->textdomain),
                    'type' => 'color',
                    'default' => '#FFFFFF',
                    'placeholder' => '',
                ),
                array(
                    'id' => 'chatbox-second-color',
                    'label' => __('Chat Box Secondary Text Color', $this->textdomain),
                    'description' => __('Select the Chat Box header Text Secondary Color.', $this->textdomain),
                    'type' => 'color',
                    'default' => '#FFFFFF',
                    'placeholder' => '',
                ),
                array(
                    'id' => 'whizzchat-btn-bg-color',
                    'label' => __('ChatBox Button Background Color', $this->textdomain),
                    'description' => __('Select the ChatBox button color for start chat form and admin chat form .', $this->textdomain),
                    'type' => 'color',
                    'default' => '#4c67f0',
                    'placeholder' => '',
                ),
                array(
                    'id' => 'whizzchat-btn-txt-color',
                    'label' => __('ChatBox Button Text Color', $this->textdomain),
                    'description' => __('Select the ChatBox button text color for start chat form and admin chat form .', $this->textdomain),
                    'type' => 'color',
                    'default' => '#FFFFFF',
                    'placeholder' => '',
                ),
            )
        );


        $settings['realtime_comm_settings'] = array(
            'title' => __('Real Time Communication', $this->textdomain),
            'description' => __('<b style="color:red">Note : </b>For RealTime Communication You have to create the agile-pusher credentials.<br/>For Creating agile-pusher Credentials please follow this link.<a href="https://agilepusher.com/"> Create Credentials </a>', $this->textdomain),
            'fields' => array(
                array(
                    'id' => 'whizzChat-agilepusher-key',
                    'label' => __('Agile Pusher Key', $this->textdomain),
                    'description' => __('Enter the agile pusher Api key.', $this->textdomain),
                    'type' => 'text',
                    'default' => '',
                    'placeholder' => '4545jksds93439sdksj6'
                ),
            )
        );

        $settings['dashboard_settings'] = array(
            'title' => __('Dashboard Settings', $this->textdomain),
            'description' => esc_html__('Set the dashboard settings according to your requirements.', $this->textdomain),
            'fields' => array(
                array(
                    'id' => 'whizzChat-dashboard-page',
                    'label' => __('Dashboard Page', $this->textdomain),
                    'description' => __('Select the dashboard page for chat messenger.', $this->textdomain),
                    'type' => 'select',
                    'options' => $admin_pages,
                    'default' => '',
                ),
                array(
                    'id' => 'whizzchat-sidebar',
                    'label' => __('Allow dashboard sidebar', $this->textdomain),
                    'description' => __('Use this option to enable/disable whizzchat sidebar in the dashboard area.', $this->textdomain),
                    'type' => 'radio',
                    'options' => array(
                        '1' => __('Enabled', $this->textdomain),
                        '0' => __('Disabled', $this->textdomain),
                    ),
                    'default' => '1'
                ),
                array(
                    'id' => 'whizzChat-sidebar-title',
                    'label' => __('Sidebar title', $this->textdomain),
                    'description' => __('Enter the sidebar section title.', $this->textdomain),
                    'type' => 'text',
                    'default' => __('Whizzchat Sidebar.', $this->textdomain),
                    'placeholder' => __('Whizzchat Sidebar.', $this->textdomain),
                ),
                array(
                    'id' => 'whizzchat-dash-nav-bg',
                    'label' => __('Whizzchat Dashboard Color', $this->textdomain),
                    'description' => __('Select the Whizzchat color for dashboard sections.', $this->textdomain),
                    'type' => 'color',
                    'default' => '#665dfe',
                    'placeholder' => '',
                ),
            )
        );

        $settings['whizzchat_bot'] = array(
            'title' => __('WhizzChat Bot', $this->textdomain),
            'description' => '',
            'fields' => array(
                array(
                    'id' => 'whizzChat-bot',
                    'label' => __('WhizzChat Bot', $this->textdomain),
                    'description' => __('Use these options to Enable/Disable Admin WhizzChat Bot.Make Sure you have select the chat between ( admin|both ) in Basic Settings.', $this->textdomain),
                    'type' => 'radio',
                    'options' => array(
                        '1' => __('Enabled', $this->textdomain),
                        '0' => __('Disabled', $this->textdomain),
                    ),
                    'default' => '0'
                ),
                array(
                    'id' => 'whizzChatbot-head-text',
                    'label' => __('WhizzChat Bot header Text', $this->textdomain),
                    'description' => __('Add the WhizzChat Bot header text,', $this->textdomain),
                    'type' => 'text',
                    'default' => __('WhizzChat Bot', $this->textdomain),
                    'placeholder' => __('Header text', $this->textdomain),
                ),
                array(
                    'id' => 'whizzChatbot-tooltip',
                    'label' => __('WhizzChat Bot tooltip Text', $this->textdomain),
                    'description' => __('Add the WhizzChat Bot tooltip text,', $this->textdomain),
                    'type' => 'text',
                    'default' => __("Got Confused? let's chat with admin.", $this->textdomain),
                    'placeholder' => __('tooltip text', $this->textdomain),
                ),
                array(
                    'id' => 'whizzchatbot-header-color',
                    'label' => __('WhizzChat Bot Header Color', $this->textdomain),
                    'description' => __('Select the ChatBot header color.', $this->textdomain),
                    'type' => 'color',
                    'default' => '#000000',
                    'placeholder' => '',
                ),
                array(
                    'id' => 'whizzchatbot-header-text-color',
                    'label' => __('WhizzChat Bot Header Text Color', $this->textdomain),
                    'description' => __('Select the ChatBot header text color.', $this->textdomain),
                    'type' => 'color',
                    'default' => '#FFFFFF',
                    'placeholder' => '',
                ),
                array(
                    'id' => 'whizzChat-intro-msgs',
                    'label' => __('Introductory Messages', $this->textdomain),
                    'description' => __('Add the introductory Messages.You can add the multiple messages seperated by | .', $this->textdomain),
                    'type' => 'textarea',
                    'default' => '',
                    'placeholder' => ''
                ),
                array(
                    'id' => 'whizzChat-res-msg-limit',
                    'label' => __('Response Message LImit', $this->textdomain),
                    'description' => __('Limit the Response Messages added in the whizz-chat Bot Actions.', $this->textdomain),
                    'type' => 'text',
                    'default' => '10',
                    'placeholder' => __('Add response LImit', $this->textdomain),
                ),
                array(
                    'id' => 'whizzChat-bot-copyright',
                    'label' => __('WhizzChat Bot Copyright', $this->textdomain),
                    'description' => __('Enable this setion to display copyright section at the bottom of the WhizzChat Bot.', $this->textdomain),
                    'type' => 'radio',
                    'options' => array(
                        '1' => __('Enabled', $this->textdomain),
                        '0' => __('Disabled', $this->textdomain),
                    ),
                    'default' => '0'
                ),
                array(
                    'id' => 'whizzchat-bot-copyright-text',
                    'label' => __('WhizzChat Bot Copyright Text', $this->textdomain),
                    'description' => __('Add the copyright text of your choice.', $this->textdomain),
                    'type' => 'textarea',
                    'default' => 'Powered By',
                    'placeholder' => __('Copyright text', $this->textdomain),
                ),
            )
        );

        $settings['reset_chat'] = array(
            'title' => __('Reset Data', $this->textdomain),
            'description' => __('it is used to remove all chat messages and chat users.', $this->textdomain),
            'fields' => array(
                array(
                    'id' => 'whizzChat-reset-chat',
                    'label' => __('whizzchat database', $this->textdomain),
                    'button_label' => __('Reset Database', $this->textdomain),
                    'type' => 'button',
                    'description' => '',
                    'ext_class' => 'button button-primary button-large whizzchat-reset'
                ),
            )
        );
        $settings = apply_filters('plugin_settings_fields', $settings);


        return $settings;
    }

    /**
     * Options getter
     * @return array Options, either saved or default ones.
     */
    public function get_options() {
        $options = get_option($this->plugin_slug);
        if (!$options && is_array($this->settings)) {
            $options = Array();
            foreach ($this->settings as $section => $data) {
                foreach ($data['fields'] as $field) {
                    $options[$field['id']] = isset($field['default']) ? $field['default'] : '';
                }
            }
            add_option($this->plugin_slug, $options);
        }
        return $options;
    }

    /**
     * Register plugin settings
     * @return void
     */
    public function register_settings() {
        if (is_array($this->settings)) {
            register_setting($this->plugin_slug, $this->plugin_slug, array($this, 'validate_fields'));
            foreach ($this->settings as $section => $data) {
                add_settings_section($section, $data['title'], array($this, 'settings_section'), $this->plugin_slug);
                foreach ($data['fields'] as $field) {
                    $fields_args = array('field' => $field);
                    if (isset($field['class']) && $field['class'] != '') {
                        $fields_args = array('field' => $field, 'class' => $field['class']);
                    }
                    add_settings_field($field['id'], $field['label'], array($this, 'display_field'), $this->plugin_slug, $section, $fields_args);
                }
            }
        }
    }

    public function settings_section($section) {
        $html = '<p> ' . $this->settings[$section['id']]['description'] . '</p>' . "\n";
        echo whizzChat_return($html);
    }

    /**
     * Generate HTML for displaying fields
     * @param  array $args Field data
     * @return void
     */
    public function display_field($args) {
        $field = $args['field'];
        $html = '';
        $option_name = $this->plugin_slug . "[" . $field['id'] . "]";
        
        $data = (isset($this->options[$field['id']])) ? $this->options[$field['id']] : $this->options[$field['default']];      
        switch ($field['type']) {
            case 'text':
            case 'password':
            case 'number':
                $html .= '<input id="' . esc_attr($field['id']) . '" type="' . $field['type'] . '" name="' . esc_attr($option_name) . '" placeholder="' . esc_attr($field['placeholder']) . '" value="' . $data . '"/>' . "\n";
                break;
            case 'color':
                $html .= '<input class="whizzchat-color-field" id="' . esc_attr($field['id']) . '" type="' . $field['type'] . '" name="' . esc_attr($option_name) . '" placeholder="' . esc_attr($field['placeholder']) . '" value="' . $data . '"/>' . "\n";
                break;
            case 'button':
                $html .= '<button class="' . esc_attr($field['ext_class']) . '" id="' . esc_attr($field['id']) . '" type="' . $field['type'] . '" name="' . esc_attr($option_name) . '" >' . esc_attr($field['button_label']) . '</button>' . "\n";
                break;
            case 'text_secret':
                $html .= '<input id="' . esc_attr($field['id']) . '" type="text" name="' . esc_attr($option_name) . '" placeholder="' . esc_attr($field['placeholder']) . '" value=""/>' . "\n";
                break;
            case 'textarea':
                $html .= '<textarea id="' . esc_attr($field['id']) . '" rows="7" cols="70" name="' . esc_attr($option_name) . '" placeholder="' . esc_attr($field['placeholder']) . '">' . $data . '</textarea><br/>' . "\n";
                break;
            case 'checkbox':
                $checked = '';
                if ($data && 'on' == $data) {
                    $checked = 'checked="checked"';
                }
                $html .= '<input id="' . esc_attr($field['id']) . '" type="' . $field['type'] . '" name="' . esc_attr($option_name) . '" ' . $checked . '/>' . "\n";
                break;
            case 'checkbox_multi':
                foreach ($field['options'] as $k => $v) {
                    $checked = false;
                    if (is_array($data) && in_array($k, $data)) {
                        $checked = true;
                    }
                    $html .= '<label for="' . esc_attr($field['id'] . '_' . $k) . '"><input type="checkbox" ' . checked($checked, true, false) . ' name="' . esc_attr($option_name) . '[]" value="' . esc_attr($k) . '" id="' . esc_attr($field['id'] . '_' . $k) . '" /> ' . $v . '</label> ';
                }
                break;
            case 'radio':
                foreach ($field['options'] as $k => $v) {
                    $checked = false;
                    if ($k == $data) {
                        $checked = true;
                    }
                    $html .= '<label for="' . esc_attr($field['id'] . '_' . $k) . '"><input type="radio" ' . checked($checked, true, false) . ' name="' . esc_attr($option_name) . '" value="' . esc_attr($k) . '" id="' . esc_attr($field['id'] . '_' . $k) . '" /> ' . $v . '</label> ';
                }
                break;
            case 'select':
                $html .= '<select name="' . esc_attr($option_name) . '" id="' . esc_attr($field['id']) . '">';
                foreach ($field['options'] as $k => $v) {
                    $selected = false;
                    if ($k == $data) {
                        $selected = true;
                    }
                    $html .= '<option ' . selected($selected, true, false) . ' value="' . esc_attr($k) . '">' . $v . '</option>';
                }
                $html .= '</select> ';
                break;
            case 'select_multi':
                $html .= '<select name="' . esc_attr($option_name) . '[]" id="' . esc_attr($field['id']) . '" multiple="multiple">';
                foreach ($field['options'] as $k => $v) {
                    $selected = false;
                    if (in_array($k, $data)) {
                        $selected = true;
                    }
                    $html .= '<option ' . selected($selected, true, false) . ' value="' . esc_attr($k) . '" />' . $v . '</label> ';
                }
                $html .= '</select> ';
                break;
        }
        switch ($field['type']) {
            case 'checkbox_multi':
            case 'radio':
            case 'select_multi':
                $html .= '<br/><span class="description">' . $field['description'] . '</span>';
                break;
            default:
                $html .= '<label for="' . esc_attr($field['id']) . '"><span class="description">' . $field['description'] . '</span></label>' . "\n";
                break;
        }
        echo whizzChat_return($html);
    }

    /**
     * Validate individual settings field
     * @param  array $data Inputted value
     * @return array       Validated value
     */
    public function validate_fields($data) {
        return $data;
    }

    /**
     * Load settings page content
     * @return void
     */
    public function whizzchat_settings_page() {
        ?>
        <div class="wrap whizz-admin-container" id="<?php echo whizzChat_return($this->plugin_slug);?>">
            <h2><?php _e('WhizzChat Settings', $this->textdomain);?></h2>
            <p><?php _e('Whizz Chat is a WordPress plugin based on Ajax and Live Chat system where buyers and seller can communicate with each other.', $this->textdomain);?></p>
            <h2 class="nav-tab-wrapper settings-tabs hide-if-no-js">
                <?php
                foreach ($this->settings as $section => $data) {
                    echo '<a href="#' . $section . '" class="nav-tab">' . $data['title'] . '</a>';
                }
                ?>
            </h2>
            <?php $this->do_script_for_tabbed_nav();?>
            <!-- Tab navigation ends -->
            <form action="options.php" method="POST">
                <?php settings_fields($this->plugin_slug);?>
                <div class="settings-container">
                    <?php do_settings_sections($this->plugin_slug);?>
                </div>
                <?php submit_button();?>
            </form>
        </div>
        <?php
    }

    /**
     * Print jQuery script for tabbed navigation
     * @return void
     */
    private function do_script_for_tabbed_nav() {
        ?>
        <script>
            jQuery(document).ready(function ($) {
                /*
                 * functions to save active tab in cookie
                 */
                function whizzchat_setCookie(key, value, expiry) {
                    var expires = new Date();
                    expires.setTime(expires.getTime() + (expiry * 24 * 60 * 60 * 1000));
                    document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
                }
                function whizzchat_getCookie(key) {
                    var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
                    return keyValue ? keyValue[2] : null;
                }
                function whizzchat_eraseCookie(key) {
                    var keyValue = whizzchat_getCookie(key);
                    whizzchat_setCookie(key, keyValue, '-1');
                }

                /*
                 * functions to save active tab in cookie
                 */

                var headings = jQuery('.settings-container > h2, .settings-container > h3');
                var paragraphs = jQuery('.settings-container > p');
                var tables = jQuery('.settings-container > table');
                var triggers = jQuery('.settings-tabs a');
                triggers.each(function (i) {
                    triggers.eq(i).on('click', function (e) {
                        e.preventDefault();
                        triggers.removeClass('nav-tab-active');
                        headings.hide();
                        paragraphs.hide();
                        tables.hide();
                        whizzchat_eraseCookie('whizz-tab-active');
                        triggers.eq(i).addClass('nav-tab-active');
                        headings.eq(i).show();
                        paragraphs.eq(i).show();
                        tables.eq(i).show();
                        whizzchat_setCookie('whizz-tab-active', i, '1'); //(key,value,expiry in days)

                    });
                });
                var whizz_active = whizzchat_getCookie('whizz-tab-active');
                whizz_active = typeof whizz_active !== 'undefined' && whizz_active != '' ? whizz_active : 0;
                triggers.eq(whizz_active).click();
                jQuery('.whizz-chat-chat-between input[type="radio"]').on('click', function (e) {
                    var chat_between = jQuery(this).val();
                    if (typeof chat_between !== 'undefined' && chat_between != '') {
                        if (chat_between == '1' || chat_between == '2') {
                            jQuery('.whizz-chat-admin-dropdown').show();
                        } else {
                            jQuery('.whizz-chat-admin-dropdown').hide();
                        }
                    }
                });
                jQuery('.whizzchat-usertype input[type="radio"]').on('click', function (e) {
                    var user_type = jQuery(this).val();
                    if (typeof user_type !== 'undefined' && user_type != '') {
                        if (user_type == '2') {
                            jQuery('.whizzchat-login-type').show();
                            jQuery('.whizzchat-logintype-field').show();

                        } else {
                            jQuery('.whizzchat-login-type').hide();
                            jQuery('.whizzchat-logintype-field').hide();
                        }
                    }
                });
            });
        </script>
        <?php
    }

}
$settings = new WhizzChat_Setting_Page("WhizzChat", "whizz-chat-options", __FILE__);
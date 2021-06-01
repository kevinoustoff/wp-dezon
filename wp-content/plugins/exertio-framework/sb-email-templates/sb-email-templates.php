<?php

if (!defined('ABSPATH')) {
    die('-1');
}

if (!class_exists('Sb_Email_Templates')) {

    class Sb_Email_Templates {

        public $plugin_url;
        public $plugin_dir;
        private static $instance = null;

        public static function get_instance() {
            if (!self::$instance)
                self::$instance = new self;
            return self::$instance;
        }

        public function __construct() {

            $this->sb_email_templates_define_constants();
            $this->sb_email_templates_files_inclusion();
            add_action('wp_enqueue_scripts', array($this, 'sb_email_templates_plugin_scripts'));
            add_action('admin_enqueue_scripts', array($this, 'sb_email_templates_admin_style_scripts'));
        }

        public function sb_email_templates_files_inclusion() {

            require_once ( dirname(__FILE__) ) . '/includes/class-post-type-templates.php';

            require_once ( dirname(__FILE__) ) . '/includes/sb-email-template-functions.php';

            /*
             * Adding all email email templates files
             */

            //update_option('sb_email_template_added', array());

            $email_file_paths = plugin_dir_path(__FILE__) . "includes/emails/";
            $template_added = get_option('sb_email_template_added');
            $template_added = isset($template_added) && !empty($template_added) ? $template_added : array();

            $sb_email_templates = array_diff(scandir($email_file_paths), array('.', '..'), $template_added);

            if (SB_EMAIL_TEMPLATE_DEV) {
                $sb_email_templates = array_diff(scandir($email_file_paths), array('.', '..'));
                update_option('sb_email_template_added', array());
            }

            if (isset($sb_email_templates) && !empty($sb_email_templates) && is_array($sb_email_templates)) {
                foreach ($sb_email_templates as $each_file) {
                    require_once $email_file_paths . $each_file;
                    $template_added[] = $each_file;
                }
                update_option('sb_email_template_added', $template_added);
            }
        }

        public function sb_email_templates_define_constants() {

            $this->plugin_url = plugin_dir_url(__FILE__);
            $this->plugin_dir = plugin_dir_path(__FILE__);
            define('SB_EMAIL_TEMPLATE_DEV', true);
        }

        public function sb_email_templates_plugin_scripts() {
            //wp_enqueue_style('sb-email-template-functions', $this->plugin_url . 'assets/css/leaflet.css');
        }

        public function sb_email_templates_admin_style_scripts() {

            wp_enqueue_script('sb-email-template-functions', $this->plugin_url . 'js/sb-email-template-functions.js');
            wp_localize_script('sb-email-template-functions', 'email_js_global', $this->sb_email_template_admin_localize_strings());
        }

        public function sb_email_template_admin_localize_strings() {

            $localize_data = array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'confirm_reset' => __('Are you sure you want to reset the email content to the default template', ''),
            );

            return $localize_data;
        }

    }

}
Sb_Email_Templates::get_instance();

<?php
/*
 * Email Templates Custom Post Type
 */
if (!defined('ABSPATH'))
    exit;

class Sb_Email_Template_Post {

    public $post_type_name;
    public $prefix;

    public function __construct() {
        add_action('init', array($this, 'exertio_register_email_template_post'));
        add_action('add_meta_boxes', array($this, 'sb_email_templates_variables_metabox'));
        if (!SB_EMAIL_TEMPLATE_DEV) {
            add_action('admin_head', array($this, 'sb_email_templates_hide_notices'));
            add_filter('bulk_post_updated_messages', array($this, 'sb_hide_posts_counting_section'), 10, 2);
            add_filter('post_row_actions', array($this, 'sb_email_templates_row_actions'), 10, 2);
            add_filter('bulk_actions-edit-sb-email-template', array($this, 'sb_remove_email_bulk_actions'));
            add_filter('manage_sb-email-template_posts_columns', array($this, 'sb_manage_email_templates_columns'));
            add_filter('wp_trash_post', array($this, 'sb_email_template_restrict_post_deletion'));
            add_filter('manage_edit-sb-email-template_sortable_columns', array($this, 'sb_email_template_remove_title_sort'));
        }
        add_action('wp_ajax_sb_email_template_content_reset', array($this, 'sb_email_template_content_reset'));
    }

    function sb_email_template_remove_title_sort($sort_col) {
        unset($sort_col['title']);
        return $sort_col;
    }

    public function sb_email_template_content_reset() {


        $template_id = isset($_POST['template_id']) && $_POST['template_id'] != '' ? $_POST['template_id'] : '';

        $error_arr = array();

        if ($template_id != '') {

            $default_content = get_post_meta($template_id, 'sb_template_defalut_content', true);

            $update_content_args = array(
                'post_type' => 'sb-email-template',
                'ID' => $template_id,
                'post_content' => $default_content,
            );

            kses_remove_filters();
            $update_tem_id = wp_update_post($update_content_args, true);
            kses_init_filters();

            if (is_wp_error($update_tem_id)) {

                $errors = $update_tem_id->get_error_messages();

                $error_arr = array(
                    'status' => 'error',
                    'message' => $errors,
                );
            } else {

                $error_arr = array(
                    'status' => 'success',
                    'message' => __('Content Changed Successfully.', 'exertio_framework'),
                );
            }
        } else {

            $error_arr = array(
                'status' => 'error',
                'message' => __('Ooops ! something went wrong.', 'exertio_framework'),
            );
        }
        echo json_encode($error_arr);
        wp_die();
    }

    public function sb_email_templates_variables_metabox() {
        add_meta_box('sb-emails-variable-meta', esc_html__('Variables', 'exertio_framework'), array($this, 'exertio_email_variables'), 'sb-email-template', 'side', 'high');
        add_meta_box('sb-emails-mail-settings', esc_html__('Mail Settings', 'exertio_framework'), array($this, 'exertio_email_settings'), 'sb-email-template', 'normal', 'high');
    }

    public function exertio_email_settings($post) {
        global $exertio_meta_fields;
        ?>
        <table class="sb-classified-table">
            <div class="custom-row">
                <div class="col-3"> <?php echo __('Email Subject', 'exertio_framework'); ?> </div>
                <div class="col-3"><input type="text" class="email_subject" name="email_subject" width="100%"></div>
            </div>
            <div class="custom-row">
                <div class="col-3"> <?php echo __('From Name', 'exertio_framework'); ?> </div>
                <div class="col-3"><input type="text" class="from_name" name="from_name" width="100%"> </div>
            </div>
            <div class="custom-row">
                <div class="col-3"> <?php echo __('From Email', 'exertio_framework'); ?> </div>
                <div class="col-3"> <input type="text" class="from_email" name="from_name"> </div>
            </div>

        </table>
        <?php
    }

    public function exertio_email_variables($post) {

        $email_variables = get_post_meta($post->ID, 'sb_email_templates_variables', true);
        $email_variables = isset($email_variables) && !empty($email_variables) ? $email_variables : array();

        $default_variables = array('site-title', 'site-description', 'site-url');

        if (sizeof($email_variables) > 0) {
            $email_variables = array_merge($default_variables, $email_variables);
        } else {
            $email_variables = $default_variables;
        }

        if (isset($email_variables) && sizeof($email_variables) > 0) {
            foreach ($email_variables as $value) {
                echo '%' . $value . '%';
                echo '<br />';
            }
        }
        echo '<hr />';
        echo '<a href="javascript:void(0)" data-template-id="' . $post->ID . '" class="sb-reset-template-content button button-primary button-large">' . __('Reset Template Content', 'exertio_framework') . '</a>';
    }

    public function exertio_register_email_template_post() {

        // Register Custom Post Type Email Template

        $labels = array(
            'name' => _x('Email Templates', 'Post Type General Name', 'exertio_framework'),
            'singular_name' => _x('Email Template', 'Post Type Singular Name', 'exertio_framework'),
            'menu_name' => _x('Email Templates', 'Admin Menu text', 'exertio_framework'),
            'name_admin_bar' => _x('Email Template', 'Add New on Toolbar', 'exertio_framework'),
            'archives' => __('Email Template Archives', 'exertio_framework'),
            'attributes' => __('Email Template Attributes', 'exertio_framework'),
            'parent_item_colon' => __('Parent Email Template:', 'exertio_framework'),
            'all_items' => __('All Email Templates', 'exertio_framework'),
            'add_new_item' => __('Add New Email Template', 'exertio_framework'),
            'add_new' => __('Add New', 'exertio_framework'),
            'new_item' => __('New Email Template', 'exertio_framework'),
            'edit_item' => __('Edit Email Template', 'exertio_framework'),
            'update_item' => __('Update Email Template', 'exertio_framework'),
            'view_item' => __('View Email Template', 'exertio_framework'),
            'view_items' => __('View Email Templates', 'exertio_framework'),
            'search_items' => __('Search Email Template', 'exertio_framework'),
            'not_found' => __('Not found', 'exertio_framework'),
            'not_found_in_trash' => __('Not found in Trash', 'exertio_framework'),
            'featured_image' => __('Featured Image', 'exertio_framework'),
            'set_featured_image' => __('Set featured image', 'exertio_framework'),
            'remove_featured_image' => __('Remove featured image', 'exertio_framework'),
            'use_featured_image' => __('Use as featured image', 'exertio_framework'),
            'insert_into_item' => __('Insert into Email Template', 'exertio_framework'),
            'uploaded_to_this_item' => __('Uploaded to this Email Template', 'exertio_framework'),
            'items_list' => __('Email Templates list', 'exertio_framework'),
            'items_list_navigation' => __('Email Templates list navigation', 'exertio_framework'),
            'filter_items_list' => __('Filter Email Templates list', 'exertio_framework'),
        );
        $args = array(
            'label' => __('Email Template', 'exertio_framework'),
            'description' => __('This post type is responsible for email templates.', 'exertio_framework'),
            'labels' => $labels,
            'menu_icon' => 'dashicons-email-alt',
            'supports' => array('title', 'editor'),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'show_in_admin_bar' => false,
            'show_in_nav_menus' => false,
            'can_export' => false,
            'has_archive' => false,
            'hierarchical' => false,
            'exclude_from_search' => true,
            'show_in_rest' => false,
            'publicly_queryable' => false,
            'capability_type' => 'page',
            'capabilities' => array(
                'create_posts' => false,
            ),
            'map_meta_cap' => true,
        );
        register_post_type('sb-email-template', $args);
    }

    public function sb_email_templates_hide_notices() {
        $get_current_screen_ = get_current_screen();

        if (isset($get_current_screen_->post_type) && $get_current_screen_->post_type == 'sb-email-template') {
            remove_all_actions('admin_notices');
            add_filter('months_dropdown_results', '__return_empty_array');
        }
        if (isset($get_current_screen_->id) && $get_current_screen_->id == 'edit-sb-email-template') {
            remove_all_actions('admin_notices');
        }
    }

    function sb_hide_posts_counting_section($bulk_messages) {
        global $locked_post_status;

        $post_type = (isset($_GET['post_type'])) ? $_GET['post_type'] : '';
        if ($post_type == 'sb-email-template') {
            $exclude_post_types = array('page');
            if (!in_array($post_type, $exclude_post_types)) {
                $locked_post_status = TRUE;
            }
        }

        return $bulk_messages;
    }

    function sb_email_template_restrict_post_deletion($post_id) {
        if (get_post_type($post_id) === 'sb-email-template') {
            wp_die(__('The post you were trying to delete is protected.', 'exertio_framework'));
        }
    }

    function sb_manage_email_templates_columns($columns) {

        unset($columns['cb']);
        unset($columns['date']);
        $columns['title'] = __('Template Names', 'exertio_framework');
        return $columns;
    }

    function sb_remove_email_bulk_actions($actions) {
        foreach ($actions as $key => $value) {
            unset($actions[$key]);
        }
        return $actions;
    }

    public function sb_email_templates_row_actions($actions, $post) {
        if ($post->post_type === 'sb-email-template') {
            foreach ($actions as $key => $value) {
                unset($actions[$key]);
            }
        }
        return $actions;
    }

}

new Sb_Email_Template_Post();

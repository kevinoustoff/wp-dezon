<?php

if (!defined('ABSPATH')) {
    die('-1');
}

//if (!has_action('sb_email_template_call')) {

add_action('sb_email_template_call', 'sb_email_template_call', 10, 2);

function sb_email_template_call($template_key = '', $template_args = array()) {

    if ($template_key == '') {
        return;
    }

    $template_id = sb_get_email_template_id($template_key);
    $template_body = sb_get_email_template_body_by_id($template_id);

    $template_variables = get_post_meta($template_id, 'sb_email_templates_variables', true);
    $template_subject = get_post_meta($template_id, '_exertio_email_subject', true);
    $template_from_name = get_post_meta($template_id, '_exertio_from_name', true);
    $template_from_email = get_post_meta($template_id, '_exertio_from_email', true);

    $from_name = get_bloginfo('name');
    $from_email = get_bloginfo('admin_email');

    if (isset($template_from_name) && $template_from_name != '') {
        $from_name = $template_from_name;
    }

    if (isset($template_from_email) && $template_from_email != '') {
        $from_email = $template_from_email;
    }

    $from = "From: {$from_name} <{$from_email}>";

    $template_headers = array('Content-Type: text/html; charset=' . get_bloginfo('charset') . '', $from);

    function sb_email_variable_creation($variable) {
        return '%' . $variable . '%';
    }

    $merged_array_variables = array_merge(sb_email_template_default_variable_value(), $template_args['variables']);
    $body = str_replace(array_map('sb_email_variable_creation', array_keys($merged_array_variables)), array_values($merged_array_variables), $template_body);
        
    update_option('_check_active_mail',$body);
    
    $main_status = wp_mail($to, $template_subject, $body, $template_headers);
    
    if (isset($template_args['return']) && $template_args['return']) {
        return $main_status;
    }
}

//}


if (!function_exists('sb_email_template_default_variable_value')) {

    function sb_email_template_default_variable_value() {

        $default_variable = array();
        $default_variable['site-title'] = get_bloginfo('name');
        $default_variable['site-description'] = get_bloginfo('description');
        $default_variable['site-url'] = get_bloginfo('url');
        return $default_variable;
    }

}




if (!function_exists('sb_get_email_template_id')) {

    function sb_get_email_template_id($key) {
        global $wpdb;

        if ($key == '') {
            return false;
        }

        $temp_data = $wpdb->get_row("SELECT meta_value FROM " . $wpdb->prefix . "postmeta WHERE meta_key = '" . $key . "'", 'ARRAY_A');
        if (isset($temp_data) && isset($temp_data['meta_value'])) {
            return $temp_data['meta_value'];
        } else {
            return false;
        }
    }

}

if (!function_exists('sb_get_email_template_body_by_id')) {

    function sb_get_email_template_body_by_id($template_id = '') {

        if ($template_id == '') {
            return false;
        }

        $content = get_post_field('post_content', $template_id);
        return $content;
    }

}

if (!function_exists('sb_get_email_template_body_by_key')) {

    function sb_get_email_template_body_by_key($template_key = '') {

        if ($template_key == '') {
            return false;
        }

        $template_id = sb_get_email_template_id($template_key);
        $content = sb_get_email_template_body_by_id($template_id);
        return $content;
    }

}
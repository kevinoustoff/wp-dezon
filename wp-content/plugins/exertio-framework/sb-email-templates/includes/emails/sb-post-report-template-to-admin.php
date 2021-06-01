<?php

if (!defined('ABSPATH'))
    exit;

if (!class_exists('Exertio_Project_Posted')) {

    class Exertio_Project_Posted {

        var $template_key;
        var $template_title;
        var $template_content;

        public function __construct() {
            // Need to change for each template
            $this->template_title = 'Project Posted Email to User'; // title of the email template
            $this->template_key = 'project-posted-template-user';   // template key for getting an email template
            $this->template_subject = 'Project Posted - ' . get_bloginfo('name') . '';   // setting the email subject
            
            // don't change these settings 
            $this->template_content = $this->sb_email_templates_editor_html(); // email template body content editable            
            $this->template_from_name = get_bloginfo('name');   // setting the email subject
            $this->template_from_email = get_bloginfo('admin_email');   // setting the email subject
            add_action('wp', array($this, 'sb_email_templates_insert_post'));
        }

        public function template_variables() {

            $variables = array('project-link', 'project-title');
            return $variables;
        }

        public function sb_email_templates_editor_html() {

            $template_html = '<div class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f6f6f6; width: 100%;" border="0" cellspacing="0" cellpadding="0">
                            <div>
                                <div>
                                    <div style="font-family: sans-serif; font-size: 14px; vertical-align: top;"> </div>
                                    <div class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; max-width: 580px; padding: 10px; width: 580px; margin: 0 auto !important;">
                                        <div class="content" style="box-sizing: border-box; display: block; margin: 0 auto; max-width: 580px; padding: 10px;">
                                            <div class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background: #fff; border-radius: 3px; width: 100%;">
                                                <div>
                                                    <div>
                                                        <div class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">
                                                            <div style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;" border="0" cellspacing="0" cellpadding="0">
                                                                <div>
                                                                    <div style="box-sizing: border-box; font-size: 14px; margin: 0;">
                                                                        <div class="alert" style="box-sizing: border-box; font-size: 16px; vertical-align: top; color: #000; font-weight: 500; text-align: center; border-radius: 3px 3px 0 0; background-color: #fff; margin: 0; padding: 20px;" align="center" valign="top" bgcolor="#fff">A Designing and development company</div>
                                                                    </div>
                                                                    <div>
                                                                        <div style="font-family: sans-serif; font-size: 14px; vertical-align: top;">
                                                                            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;"><span style="font-family: sans-serif; font-weight: normal;">Hello </span><span style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;"><b>Admin,</b></span></p>
                                                                            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Below Post is reported</p>
                                                                            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Title: %post-title%</p>
                                                                            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Link: <a href="%post-link%">%post-title%</a></p>
                                                                            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Poster: %post-owner%</p>
                                                                            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;"><strong>Thanks!</strong></p>
                                                                            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">ScriptsBundle</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="footer" style="clear: both; padding-top: 10px; text-align: center; width: 100%;">
                                                <div style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;" border="0" cellspacing="0" cellpadding="0">
                                                    <div>
                                                        <div>
                                                            <div class="content-block powered-by" style="font-family: sans-serif; font-size: 12px; vertical-align: top; color: #999999; text-align: center;"><a style="color: #999999; text-decoration: underline; font-size: 12px; text-align: center;" href="https://themeforest.net/user/scriptsbundle"> Scripts Bundle</a>.</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div style="font-family: sans-serif; font-size: 14px; vertical-align: top;"> </div>
                                </div>
                            </div>
                        </div>';

            return $template_html;
        }

        public function sb_email_templates_insert_post() {

            $insert_flag = TRUE;
            if (function_exists('sb_get_email_template_id')) {
                $insert_flag = sb_get_email_template_id($this->template_key);
            }


            if (post_type_exists('sb-email-template')) {


                if (!function_exists('kses_remove_filters')) {
                    require_once ABSPATH . WPINC . '/kses.php';
                }

                if (isset($insert_flag) && $insert_flag != '' && $insert_flag != FALSE && SB_EMAIL_TEMPLATE_DEV) {

                    $temp_id = $insert_flag;

                    $update_content_args = array(
                        'post_type' => 'sb-email-template',
                        'ID' => $temp_id,
                        'post_title' => $this->template_title,
                        'post_content' => $this->template_content,
                    );
                    /*
                     * Update default email template
                     */
                    kses_remove_filters();
                    $temp_id = wp_update_post($update_content_args, true);
                    kses_init_filters();

                    if (!is_wp_error($temp_id)) {

                        update_post_meta($temp_id, 'sb_email_templates_variables', $this->template_variables());
                        update_post_meta($temp_id, $this->template_key, $temp_id);
                        update_post_meta($temp_id, 'sb_template_defalut_content', $this->sb_email_templates_editor_html());
                        update_post_meta($temp_id, '_exertio_email_subject', $this->template_subject);                        
                        update_post_meta($temp_id, '_exertio_from_name', $this->template_from_name);
                        update_post_meta($temp_id, '_exertio_from_email', $this->template_from_email);
                        
                    } else {
                        echo $temp_id->get_error_message();
                    }
                }


                if ($insert_flag == '' || $insert_flag == null || $insert_flag == FALSE) {

                    $temp_args = array(
                        'post_type' => 'sb-email-template',
                        'post_title' => $this->template_title,
                        'post_content' => $this->template_content,
                        'post_status' => 'publish',
                        'post_author' => get_current_user_id(),
                    );

                    /*
                     * insert email template
                     */
                    kses_remove_filters();
                    $temp_id = wp_insert_post($temp_args);
                    kses_init_filters();

                    if (!is_wp_error($temp_id)) {
                        
                        update_post_meta($temp_id, 'sb_email_templates_variables', $this->template_variables());
                        update_post_meta($temp_id, $this->template_key, $temp_id);
                        update_post_meta($temp_id, 'sb_template_defalut_content', $this->sb_email_templates_editor_html());
                        update_post_meta($temp_id, '_exertio_email_subject', $this->template_subject);
                        update_post_meta($temp_id, '_exertio_from_name', $this->template_from_name);
                        update_post_meta($temp_id, '_exertio_from_email', $this->template_from_email);
                        
                    } else {
                        echo $temp_id->get_error_message();
                    }
                }
            }
        }

    }

    new Exertio_Project_Posted();
}
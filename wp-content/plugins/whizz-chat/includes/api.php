<?php
add_action('rest_api_init', 'whizz_rest_api_route_init');

function whizz_rest_api_route_init() {

    register_rest_route('whizz-chat-api/v1', '/start-session', array(
        'methods' => 'POST',
        'callback' => 'whizz_api_register_chat_session',
        'permission_callback' => function () {
            return whizzchat_auth_callback();
        },
    ));

    register_rest_route('whizz-chat-api/v1', '/whizz-chat-sound', array(
        'methods' => 'POST',
        'callback' => 'whizzchat_sound_switch',
        'permission_callback' => function () {
            return whizzchat_auth_callback();
        },
    ));

    register_rest_route('whizz-chat-api/v1', '/user-online', array(
        'methods' => 'POST',
        'callback' => 'whizz_api_user_online',
        'permission_callback' => function () {
            return whizzchat_auth_callback();
        },
    ));
    register_rest_route('whizz-chat-api/v1', '/user-offline', array(
        'methods' => 'POST',
        'callback' => 'whizz_api_user_offline',
        'permission_callback' => function () {
            return whizzchat_auth_callback();
        },
    ));
    register_rest_route('whizz-chat-api/v1', '/end-session', array(
        'methods' => 'POST',
        'callback' => 'whizz_api_end_chat_session',
        'permission_callback' => function () {
            return whizzchat_auth_callback();
        },
    ));
    register_rest_route('whizz-chat-api/v1', '/get-chat-list', array(
        'methods' => 'GET, POST',
        'callback' => 'whizzChat_get_chat_list',
        'permission_callback' => function () {
            return whizzchat_auth_callback();
        },
    ));
    register_rest_route('whizz-chat-api/v1', '/get-chat-list-box', array(
        'methods' => 'GET, POST',
        'callback' => 'whizzChat_get_chat_list_box',
        'permission_callback' => function () {
            return whizzchat_auth_callback();
        },
    ));
    register_rest_route('whizz-chat-api/v1', '/get-chat-list-box-admin', array(
        'methods' => 'GET, POST',
        'callback' => 'whizzChat_get_chat_list_box_admin',
        'permission_callback' => function () {
            return whizzchat_auth_callback();
        },
    ));
    register_rest_route('whizz-chat-api/v1', '/get-chat-list-admin', array(
        'methods' => 'GET, POST',
        'callback' => 'whizzChat_get_chat_list_admin',
        'permission_callback' => function () {
            return whizzchat_auth_callback();
        },
    ));
    register_rest_route('whizz-chat-api/v1', '/send-chat-message', array(
        'methods' => 'GET, POST',
        'callback' => 'whizzChat_start_chat',
        'permission_callback' => function () {
            return whizzchat_auth_callback();
        },
    ));
    register_rest_route('whizz-chat-api/v1', '/send-chat-message-admin', array(
        'methods' => 'GET, POST',
        'callback' => 'whizzChat_start_chat_admin',
        'permission_callback' => function () {
            return whizzchat_auth_callback();
        },
    ));
    register_rest_route('whizz-chat-api/v1', '/get-chat-box', array(
        'methods' => 'GET, POST',
        'callback' => 'whizzChat_get_chat_box',
        'permission_callback' => function () {
            return whizzchat_auth_callback();
        },
    ));
    register_rest_route('whizz-chat-api/v1', '/get-chat-box-admin', array(
        'methods' => 'GET, POST',
        'callback' => 'whizzChat_get_chat_box_admin',
        'permission_callback' => function () {
            return whizzchat_auth_callback();
        },
    ));

    register_rest_route('whizz-chat-api/v1', '/block-user', array(
        'methods' => 'GET, POST',
        'callback' => 'whizzChat_block_user',
        'permission_callback' => function () {
            return whizzchat_auth_callback();
        },
    ));

    register_rest_route('whizz-chat-api/v1', '/upload-files', array(
        'methods' => 'GET, POST',
        'callback' => 'whizzChat_upload_files',
        'permission_callback' => function () {
            return whizzchat_auth_callback();
        },
    ));

    register_rest_route('whizz-chat-api/v1', '/load-old-chat', array(
        'methods' => 'GET, POST',
        'callback' => 'whizzChat_load_old_chat',
        'permission_callback' => function () {
            return whizzchat_auth_callback();
        },
    ));

    register_rest_route('whizz-chat-api/v1', '/load-old-chat-admin', array(
        'methods' => 'GET, POST',
        'callback' => 'whizzChat_load_old_chat_admin',
        'permission_callback' => function () {
            return whizzchat_auth_callback();
        },
    ));

    register_rest_route('whizz-chat-api/v1', '/read-chat', array(
        'methods' => 'GET, POST',
        'callback' => 'whizzChat_read_chat',
        'permission_callback' => function () {
            return whizzchat_auth_callback();
        },
    ));

    register_rest_route('whizz-chat-api/v1', '/reset-whizchat-data', array(
        'methods' => 'POST',
        'callback' => 'whizzchat_reset_database',
        'permission_callback' => function () {
            return whizzchat_auth_callback();
        },
    ));

    register_rest_route('whizz-chat-api/v1', '/search-keyword', array(
        'methods' => 'POST',
        'callback' => 'whizzchat_search_keywork',
        'permission_callback' => function () {
            return whizzchat_auth_callback();
        },
    ));
    register_rest_route('whizz-chat-api/v1', '/get-admin-bot', array(
        'methods' => 'POST',
        'callback' => 'whizzchat_get_admin_bot',
        'permission_callback' => function () {
            return whizzchat_auth_callback();
        },
    ));
        
     register_rest_route('whizz-chat-api/v1', '/send-offline-user-email', array(
        'methods' => 'GET, POST',
        'callback' => 'send_offline_user_email_fun',
        'permission_callback' => function () {
            return whizzchat_auth_callback();
        },
    ));   
        
        
}

if (!function_exists('whizzchat_auth_callback')) {

    function whizzchat_auth_callback() {
        return true;
    }

}

if (!function_exists('whizzchat_sound_switch')) {

    function whizzchat_sound_switch(WP_REST_Request $request) {
        $get_parms = $request->get_params();
        $nonce = (isset($get_parms['nonce'])) ? $get_parms['nonce'] : '';
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            $return = array('success' => false, 'data' => '', 'message' => esc_html__('Invalid security token sent....', 'whizz-chat'));
            wp_send_json_error($return);
        }
        $sound_val = (isset($get_parms['sound_val'])) ? $get_parms['sound_val'] : '';
        $text_val = (isset($get_parms['text_val'])) ? $get_parms['text_val'] : '';

        if ($sound_val == 'on') {
            setcookie("whizz_sound_enable", 'off', time() + 31556926, COOKIEPATH, COOKIE_DOMAIN, 0, 0);
            $_COOKIE['whizz_sound_enable'] = 'off';

            $sound_val = 'off';
            $text_val = $whizz_sound_text = esc_html__('Sound off', 'whizz-chat');
        } else {
            setcookie("whizz_sound_enable", 'on', time() + 31556926, COOKIEPATH, COOKIE_DOMAIN, 0, 0);
            $_COOKIE['whizz_sound_enable'] = 'on';
            $sound_val = 'on';
            $text_val = $whizz_sound_text = esc_html__('Sound on', 'whizz-chat');
        }

        $return = array('success' => true, 'sound_val' => $sound_val, 'text_val' => $text_val);
        return $return;
    }

}

if (!function_exists('whizz_api_user_online')) {

    function whizz_api_user_online(WP_REST_Request $request) {

        $get_parms = $request->get_params();
        $nonce = (isset($get_parms['nonce'])) ? $get_parms['nonce'] : '';
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            $return = array('success' => false, 'data' => '', 'message' => esc_html__('Invalid security token sent....', 'whizz-chat'));
            wp_send_json_error($return);
        }
        $user_id = (isset($get_parms['user_id'])) ? $get_parms['user_id'] : '';
        $u_id = $user_id;
        if ($u_id != "") {
            $single_logged_in_users = get_transient("whizzChat_online_status_user_$u_id");
            $single_no_need_to_update = isset($single_logged_in_users) && $single_logged_in_users > (time() - (1 * 60));
            if (!$single_no_need_to_update) {
                set_transient("whizzChat_online_status_user_$u_id", time(), $expire_in = (2 * 60)); // 30 mins
                return '1';
            }
            return '2';
        }
        return '3';
    }

}

if (!function_exists('whizz_api_user_offline')) {

    function whizz_api_user_offline(WP_REST_Request $request) {

        $get_parms = $request->get_params();
        $nonce = (isset($get_parms['nonce'])) ? $get_parms['nonce'] : '';
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            $return = array('success' => false, 'data' => '', 'message' => esc_html__('Invalid security token sent....', 'whizz-chat'));
            wp_send_json_error($return);
        }
        $user_id = (isset($get_parms['user_id'])) ? $get_parms['user_id'] : '';
        $u_id = $user_id;
        if ($u_id != "") {
            delete_transient("whizzChat_online_status_user_$u_id");
            return '1';
        }
        return '2';
    }

}


if (!function_exists('whizz_api_check_online_status')) {

    function whizz_api_check_online_status(WP_REST_Request $request) {

        $get_parms = $request->get_params();
        $nonce = (isset($get_parms['nonce'])) ? $get_parms['nonce'] : '';
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            $return = array('success' => false, 'data' => '', 'message' => esc_html__('Invalid security token sent....', 'whizz-chat'));
            wp_send_json_error($return);
        }
        $user_id = (isset($get_parms['user_id'])) ? $get_parms['user_id'] : '';
        $u_id = $user_id;
        if ($u_id != "") {
            $get_status_val = get_transient("whizzChat_online_status_user_$u_id");

            if (isset($get_status_val) && $get_status_val != '') {
                return '1';
            } else {
                return '0';
            }
        }
        return '0';
    }

}


if (!function_exists('whizzchat_get_admin_bot')) {

    function whizzchat_get_admin_bot(WP_REST_Request $request) {

        $get_parms = $request->get_params();
        $nonce = (isset($get_parms['nonce'])) ? $get_parms['nonce'] : '';
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            $return = array('success' => false, 'data' => '', 'message' => esc_html__('Invalid security token sent....', 'whizz-chat'));
            wp_send_json_error($return);
        }



        $session = (isset($get_parms['session'])) ? $get_parms['session'] : '';

        $bot_params = array(
            'session' => $session,
        );

        $whizzbot_html = '';
        ob_start();
        do_action('whizzchat_bot', $bot_params);
        $whizzbot_html = ob_get_contents();
        ob_end_clean();

        return $whizzbot_html;
    }

}

if (!function_exists('whizzchat_search_keywork')) {

    function whizzchat_search_keywork(WP_REST_Request $request) {

        $get_parms = $request->get_params();
        $nonce = (isset($get_parms['nonce'])) ? $get_parms['nonce'] : '';
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            $return = array('success' => false, 'data' => '', 'message' => esc_html__('Invalid security token sent....', 'whizz-chat'));
            wp_send_json_error($return);
        }

        $searchkeyword = (isset($get_parms['searchkeyword'])) ? $get_parms['searchkeyword'] : '';
        $style = (isset($get_parms['style'])) ? $get_parms['style'] : 'box';

        $chat_lists = whizzChat_chat_list();
        
        $searched_list = array();
        if (isset($chat_lists) && $chat_lists != '' && is_array($chat_lists)) {
            foreach ($chat_lists as $chat_key => $chat_value) {

                if ($style == 'dashboard') {
                    if (preg_match("/$searchkeyword/i", "{$chat_value['name']}")) {
                        $searched_list[] = $chat_lists[$chat_key];
                    }
                } else {
                    if (preg_match("/$searchkeyword/i", "{$chat_value['post_title']}")) {
                        $searched_list[] = $chat_lists[$chat_key];
                    }
                }
            }
        }
        $chat_list_html = '';

        if ($style == 'dashboard') {

            if (isset($searched_list) && sizeof($searched_list) > 0) {
                $chat_list_html = apply_filters('whizzChat_dashboard_load_chatlist', $searched_list);
            } else {
                $chat_list_html = apply_filters('whizzChat_dashboard_load_chatlist', $searched_list);
            }
        } else {
            if (isset($searched_list) && sizeof($searched_list) > 0) {
                $chat_list_html = apply_filters('whizzChat_load_chat_list', $searched_list);
            } else {
                $chat_list_html = apply_filters('whizzChat_load_chat_list', $searched_list);
            }
        }

        return $chat_list_html;
    }

}

if (!function_exists('whizzchat_reset_database')) {

    function whizzchat_reset_database(WP_REST_Request $request) {

        $get_parms = $request->get_params();
        $nonce = (isset($get_parms['nonce'])) ? $get_parms['nonce'] : '';
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            $return = array('success' => false, 'data' => '', 'message' => esc_html__('Invalid security token sent....', 'whizz-chat'));
            wp_send_json_error($return);
        }
        global $wpdb, $whizz_tbl_sessions, $whizz_tblname_chat_message, $whizz_tbl_user_preferences;
        $deleted = $wpdb->query("DELETE FROM $whizz_tbl_sessions");
        $deleted = $wpdb->query("DELETE FROM $whizz_tbl_user_preferences");
        $deleted = $wpdb->query("DELETE FROM $whizz_tblname_chat_message");
        if ($deleted) {
            return 'true';
        } else {
            return 'false';
        }
    }

}


if (!function_exists('whizzChat_read_chat')) {

    function whizzChat_read_chat(WP_REST_Request $request) {
        $json_data = $request->get_json_params();
        $get_parms = $request->get_params();
        $files_parms = $request->get_file_params();
        $session = (isset($get_parms['session'])) ? $get_parms['session'] : '';
        $chat_id = (isset($get_parms['chat_id'])) ? $get_parms['chat_id'] : 0;
        $post_id = (isset($get_parms['post_id'])) ? $get_parms['post_id'] : '';
        $message_id = (isset($get_parms['message_id'])) ? $get_parms['message_id'] : '';
        $nonce = (isset($get_parms['nonce'])) ? $get_parms['nonce'] : '';
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            $return = array('success' => false, 'data' => '', 'message' => esc_html__('Invalid security token sent....', 'whizz-chat'));
            wp_send_json_error($return);
        }
        global $whizz_tbl_sessions;
        global $wpdb;
        $qry = "SELECT id, session, rel, message_for, message_count FROM $whizz_tbl_sessions WHERE (`session` = %s OR `rel` = %s) AND `chat_box_id` = %s AND id = %s ORDER BY ID DESC LIMIT 1";
        $chats = $wpdb->get_results($wpdb->prepare($qry, $session, $session, $post_id, $chat_id));
        $chat_id = (isset($chats[0]->id)) ? $chats[0]->id : '';
        $rel_id = (isset($chats[0]->rel)) ? $chats[0]->rel : '';
        $sessionId = (isset($chats[0]->session)) ? $chats[0]->session : '';
        $sender_id = ($sessionId != $session) ? $sessionId : $rel_id;


        $message_for = (isset($chats[0]->message_for)) ? $chats[0]->message_for : '';
        $message_count = (isset($chats[0]->message_count)) ? $chats[0]->message_count : '';
        $session_id = get_current_user_id();

        $arg1 = array(
            "user_id" => $sender_id,
            "chat_id" => $chat_id,
            "post_id" => $post_id,
            "message_id" => $message_id
        );
        $value = do_action('whizzChat_read_chat', $arg1);

        $type_args = array(
            "chat_id" => $chat_id, /* chat  session id */
            "receiver_id" => ( $session_id != $session ) ? $sessionId : $rel_id, /* other user id who will receive message */
            "chat_box_id" => $post_id, /* post id */
            "message_for" => $message_for, /* old user id in message_for column */
            "message_count" => $message_count /* message count from message_count column */,
            "current_user" => $session_id, /// current user session id /
            "is_update" => false
        );


        $status = ($is_show == 1 ) ? 1 : 0;
        do_action('whizzChat_new_message_and_count', $type_args, $status);

        return 'done';
    }

}

if (!function_exists('whizzChat_load_old_chat')) {

    function whizzChat_load_old_chat(WP_REST_Request $request) {


        $json_data = $request->get_json_params();
        $get_parms = $request->get_params();
        $files_parms = $request->get_file_params();
        $session = (isset($get_parms['session'])) ? $get_parms['session'] : '';
        $chat_id = (isset($get_parms['chat_id'])) ? $get_parms['chat_id'] : 0;
        $post_id = (isset($get_parms['post_id'])) ? $get_parms['post_id'] : '';
        $last_chat_id = (isset($get_parms['last_chat_id'])) ? $get_parms['last_chat_id'] : '';
        $nonce = (isset($get_parms['nonce'])) ? $get_parms['nonce'] : '';
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            $return = array('success' => false, 'data' => '', 'message' => esc_html__('Invalid security token sent....', 'whizz-chat'));
            wp_send_json_error($return);
        }
        $current_user = get_current_user_id();
        $session_id = whizzChat::session_id();
        if ($session_id != $session) {
            return array("success" => false, "data" => "", "message" => esc_html__("Invalid session id", "whizz-chat"));
        }
        $chats_boxes = array();
        $chats_boxes['id'] = $chat_id;
        $chats_boxes['chat_id'] = $chat_id;
        $chats_boxes['post_id'] = $post_id;
        $chats_boxes['last_chat_id'] = $last_chat_id;
        $chat_info = whizzChat_chat_boxes($chats_boxes);
        $text = apply_filters('whizz_filter_chat_box_content', $chat_info[0]);
        return $text;
    }

}

if (!function_exists('whizzChat_load_old_chat_admin')) {

    function whizzChat_load_old_chat_admin(WP_REST_Request $request) {


        $json_data = $request->get_json_params();
        $get_parms = $request->get_params();
        $files_parms = $request->get_file_params();
        $session = (isset($get_parms['session'])) ? $get_parms['session'] : '';
        $chat_id = (isset($get_parms['chat_id'])) ? $get_parms['chat_id'] : 0;
        $post_id = (isset($get_parms['post_id'])) ? $get_parms['post_id'] : '';
        $last_chat_id = (isset($get_parms['last_chat_id'])) ? $get_parms['last_chat_id'] : '';
        $nonce = (isset($get_parms['nonce'])) ? $get_parms['nonce'] : '';
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            $return = array('success' => false, 'data' => '', 'message' => esc_html__('Invalid security token sent....', 'whizz-chat'));
            wp_send_json_error($return);
        }
        $current_user = get_current_user_id();
        $session_id = whizzChat::session_id();
        if ($session_id != $session) {
            return array("success" => false, "data" => "", "message" => esc_html__("Invalid session id", "whizz-chat"));
        }
        $chats_boxes = array();
        $chats_boxes['id'] = $chat_id;
        $chats_boxes['chat_id'] = $chat_id;
        $chats_boxes['post_id'] = $post_id;
        $chats_boxes['last_chat_id'] = $last_chat_id;
        $chat_info = whizzChat_chat_boxes($chats_boxes);
        $text = apply_filters('whizz_filter_chat_box_content_admin', $chat_info[0]);
        return $text;
    }

}

if (!function_exists('whizzChat_upload_files')) {

    function whizzChat_upload_files(WP_REST_Request $request) {
        $json_data = $request->get_json_params();
        $get_parms = $request->get_params();
        $files_parms = $request->get_file_params();
        $session = (isset($get_parms['session'])) ? $get_parms['session'] : '';
        $chat_id = (isset($get_parms['chat_id'])) ? $get_parms['chat_id'] : 0;
        $post_id = (isset($get_parms['post_id'])) ? $get_parms['post_id'] : '';
        $nonce = (isset($get_parms['nonce'])) ? $get_parms['nonce'] : '';
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            $return = array('success' => false, 'data' => '', 'message' => esc_html__('Invalid security token sent....', 'whizz-chat'));
            wp_send_json_error($return);
        }
        return '';
    }

}

if (!function_exists('whizzChat_block_user')) {

    function whizzChat_block_user(WP_REST_Request $request) {
        $json_data = $request->get_json_params();
        $get_parms = $request->get_params();
        $nonce = (isset($get_parms['nonce'])) ? $get_parms['nonce'] : '';
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            $return = array('success' => false, 'data' => '', 'message' => esc_html__('Invalid security token sent....', 'whizz-chat'));
            wp_send_json_error($return);
        }
        $session = (isset($get_parms['session'])) ? $get_parms['session'] : '';
        $chat_id = (isset($get_parms['chat_id'])) ? ($get_parms['chat_id']) : 0;
        $post_id = (isset($get_parms['post_id'])) ? ($get_parms['post_id']) : '';
        $type = (isset($get_parms['type'])) ? ($get_parms['type']) : 'box';


        $blocked_class = 'whizzChat-block-user';
        if (isset($type) && $type == 'dashboard') {
            $blocked_class = 'whizzChat-block-user-dashb';
        }


        global $wpdb;
        global $whizz_tbl_user_preferences;
        $message = '';
        $blocked = whizzChat_is_user_blocked($chat_id);
        $data = array();
        if (isset($blocked)) {
            $delete_id = $blocked['id'];
            $blocked_id = $blocked['blocked_id'];
            $blocker_id = $blocked['blocker_id'];
            $data['unique_id'] = $chat_id;

            $html = '';
            if ($blocked['is_blocked'] == true) {
                $deleted = $wpdb->delete($whizz_tbl_user_preferences, array(
                    "id" => $delete_id,
                    "blocker_id" => $blocker_id)
                );
                if ($deleted) {
                    $message = esc_html__("User Unblocked", "whizz-chat");
                }
            } else {
                $id = $wpdb->insert(
                        $whizz_tbl_user_preferences, array('blocker_id' => $blocker_id, 'blocked_id' => $blocked_id, 'post_id' => $post_id), array('%s', '%s', '%s')
                );
                $message = esc_html__("User Blocked", "whizz-chat");
                $html = '<p class="whizzChat-block-user-p blocked-chat-p">' . esc_html__('You have blocked this user. To send message you need to unblock this user.', 'whizz-chat') . ' <a href="javascript:void(0);" class="' . $blocked_class . ' blocked-chat">' . esc_html__('Unblock', 'whizz-chat') . '</a></p>';
            }
        }
        return array("success" => true, "data" => $data, "message" => $message, "html" => $html);
    }

}

if (!function_exists('whizzChat_get_chat_box_admin')) {

    function whizzChat_get_chat_box_admin(WP_REST_Request $request) {

        $json_data = $request->get_json_params();
        $get_parms = $request->get_params();
        $nonce = (isset($get_parms['nonce'])) ? $get_parms['nonce'] : '';
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            $return = array('success' => false, 'data' => '', 'message' => esc_html__('Invalid security token sent....', 'whizz-chat'));
            wp_send_json_error($return);
        }


        $session = (isset($get_parms['session'])) ? $get_parms['session'] : '';
        $chat_id = (isset($get_parms['chat_id'])) ? ($get_parms['chat_id']) : 0;
        $is_show = (isset($get_parms['is_show'])) ? ($get_parms['is_show']) : 0;
        $last_chat_box = (isset($get_parms['last_chat_box'])) ? ($get_parms['last_chat_box']) : 0;
        $boxes_length = (isset($get_parms['boxes_length'])) ? $get_parms['boxes_length'] : 0;
        global $wpdb;
        global $whizz_tbl_sessions;
        
        $current_session_id = $session_id = whizzChat::session_id();

        $user_id = get_current_user_id();
        if ($user_id) {
            $query = "SELECT * FROM $whizz_tbl_sessions WHERE `id` = %s AND (`rel` = %s) LIMIT 1";
            $chats = $wpdb->get_results($wpdb->prepare($query, $chat_id, $user_id));
        } else {
            $query = "SELECT * FROM $whizz_tbl_sessions WHERE `id` = %s  LIMIT 1";
            $chats = $wpdb->get_results($wpdb->prepare($query, $chat_id));
        }

        if (isset($chats) && count($chats) > 0) {
            foreach ($chats as $chat) {
                if (isset($chat->id)) {
                    $value[] = array(
                        "id" => $chat->id,
                        "name" => $chat->name,
                        "email" => $chat->email,
                        "chat_status" => $chat->status,
                        "post_id" => $chat->chat_box_id,
                        "post_author_id" => $chat->rel,
                        "session_id" => $chat->session,
                        "start_time" => $chat->timestamp,
                        "last_active_time" => $chat->last_active_timestamp,
                        "chat_box_status" => $chat->chat_box_status,
                        "receiver_open" => $chat->chatbox_receiver_open,
                        "sender_open" => $chat->chatbox_sender_open,
                        "message_for" => $chat->message_for,
                        "message_count" => $chat->message_count
                    );

                    $user_type = ($current_session_id == $chat->session) ? 'sender' : 'receiver';

                    $type_args = array(
                        "chat_id" => $chat->id,
                        "user_type" => $user_type,
                        "last_chat_index" => $last_chat_box,
                    );
                    $status = ($is_show == 1 ) ? 1 : 0;
                    do_action('whizzChat_close_chat_box', $type_args, $status);

                    $receiver_id = ( isset($value[0]['session_id']) && $session_id != $value[0]['session_id'] ) ? $value[0]['session_id'] : $value[0]['post_author_id'];
                    $type_args = array(
                        "chat_id" => $value[0]['id'], /* chat  session id */
                        "receiver_id" => $receiver_id, /* other user id who will receive message */
                        "chat_box_id" => $value[0]['post_id'], /* post id */
                        "message_for" => $value[0]['message_for'], /* old user id in message_for column */
                        "message_count" => $value[0]['message_count'], /* message count from message_count column */
                        "current_user" => $session_id, // current user session id /
                        "is_update" => false,
                    );

                    $status = 0;
                    do_action('whizzChat_new_message_and_count', $type_args, $status);
                }
            }
        }
        $box = '';
        $box = apply_filters('whizzChat_load_chat_chatbox_admin', $value, false);
        $header_block_data = apply_filters('whizz_filter_chat_box_header_admin', $chat_id, $chats[0]->chat_box_id);
        $response = array('data' => $box, 'block_data' => $header_block_data);
        return $response;
    }

}

if (!function_exists('whizzChat_get_chat_box')) {

    function whizzChat_get_chat_box(WP_REST_Request $request) {
        $json_data = $request->get_json_params();
        $get_parms = $request->get_params();
        $nonce = (isset($get_parms['nonce'])) ? $get_parms['nonce'] : '';
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            $return = array('success' => false, 'data' => '', 'message' => esc_html__('Invalid security token sent....', 'whizz-chat'));
            wp_send_json_error($return);
        }

        $session = (isset($get_parms['session'])) ? $get_parms['session'] : '';
        $chat_id = (isset($get_parms['chat_id'])) ? ($get_parms['chat_id']) : 0;
        $is_show = (isset($get_parms['is_show'])) ? ($get_parms['is_show']) : 0;
        $always_open = (isset($get_parms['always_open'])) ? ($get_parms['always_open']) : 0;
        $last_chat_box = (isset($get_parms['last_chat_box'])) ? ($get_parms['last_chat_box']) : 0;
        $boxes_length = (isset($get_parms['boxes_length'])) ? $get_parms['boxes_length'] : 0;
        global $wpdb;
        global $whizz_tbl_sessions;
        $session_id = $current_session_id = whizzChat::session_id();
        $user_id = get_current_user_id();
        if ($user_id) {
            $query = "SELECT * FROM $whizz_tbl_sessions WHERE `id` = %s AND (`rel` = %s OR `session` = %s) LIMIT 1";
            $chats = $wpdb->get_results($wpdb->prepare($query, $chat_id, $user_id, $session));
        } else {
            $query = "SELECT * FROM $whizz_tbl_sessions WHERE `id` = %s AND `session` = %s LIMIT 1";
            $chats = $wpdb->get_results($wpdb->prepare($query, $chat_id, $session));
        }

        

        if (isset($chats) && count($chats) > 0) {
            foreach ($chats as $chat) {
                if (isset($chat->id)) {
                    $value[] = array(
                        "id" => $chat->id,
                        "name" => $chat->name,
                        "email" => $chat->email,
                        "chat_status" => $chat->status,
                        "post_id" => $chat->chat_box_id,
                        "post_author_id" => $chat->rel,
                        "session_id" => $chat->session,
                        "start_time" => $chat->timestamp,
                        "last_active_time" => $chat->last_active_timestamp,
                        "chat_box_status" => $chat->chat_box_status,
                        "receiver_open" => $chat->chatbox_receiver_open,
                        "sender_open" => $chat->chatbox_sender_open,
                        "message_for" => $chat->message_for,
                        "message_count" => $chat->message_count
                    );
                    $user_type = ($current_session_id == $chat->session) ? 'sender' : 'receiver';
                    $type_args = array(
                        "chat_id" => $chat->id,
                        "user_type" => $user_type,
                        "last_chat_index" => $last_chat_box,
                    );

                    if ($always_open == 1) {
                        $status = 1;
                    } else {
                        $status = ($is_show == 1 ) ? 1 : 0;
                    }
                    do_action('whizzChat_close_chat_box', $type_args, $status);
                }
            }
        }



        $box = '';
        if ($is_show == 1) {
            $box = apply_filters('whizzChat_load_chat_chatbox', $value, false);
        }

        $receiver_id = ( isset($value[0]['session_id']) && $session_id != $value[0]['session_id'] ) ? $value[0]['session_id'] : $value[0]['post_author_id'];
        $type_args = array(
            "chat_id" => $value[0]['id'], /* chat  session id */
            "receiver_id" => $receiver_id, /* other user id who will receive message */
            "chat_box_id" => $value[0]['post_id'], /* post id */
            "message_for" => $value[0]['message_for'], /* old user id in message_for column */
            "message_count" => $value[0]['message_count'], /* message count from message_count column */
            "current_user" => $session_id, // current user session id /
            "is_update" => false,
        );
        $status = 0;
        

         

        do_action('whizzChat_new_message_and_count', $type_args, $status);

        return $box;
    }

}

//define('ALLOW_UNFILTERED_UPLOADS', true);
add_filter('whizzChat_send_sound_message_filter', 'whizzChat_send_sound_message_filter_func', 10, 3);
if (!function_exists('whizzChat_send_sound_message_filter_func')) {

    function whizzChat_send_sound_message_filter_func($request, $cid) {
        global $wpdb;
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';

        $json_data = $request->get_json_params();
        $get_parms = $request->get_params();
        $files_parms = $request->get_file_params();

        $attachment_ids = array();
        $message = '';
        $success = false;
        $message_type = $get_parms['message_type'];

        $files = $files_parms['whizzchat_attachment'];
        $file = array(
            'name' => 'whizz-voice-' . time() . '.wav',
            'type' => 'audio/wav',
            'tmp_name' => $files['tmp_name'],
            'error' => $files['error'],
            'size' => $files['size']
        );
        $_FILES = array("whizzChat_attachment" => $file);
        foreach ($_FILES as $filee => $array) {
            $attachment_id = media_handle_upload($filee, 0);
            if (is_wp_error($attachment_id)) {
                return $response = rest_ensure_response(array('success' => false, 'data' => '', 'message' => esc_html__("Sorry, this file type is not permitted for security reason. ", 'whizz-chat')));
            } else {
                $attachment_ids[] = $attachment_id;
            }
        }
        $success = true;
        $attachment_ids = json_encode($attachment_ids);
        $message_type = ($message_type != "") ? $message_type : 'text';
        return array("success" => $success, "attachments" => $attachment_ids, "message_type" => $message_type, "message" => $message);
    }

}


add_filter('whizzChat_send_message_filter', 'whizzChat_send_message_filter_func', 10, 3);
if (!function_exists('whizzChat_send_message_filter_func')) {

    function whizzChat_send_message_filter_func($request, $cid) {

        $json_data = $request->get_json_params();
        $get_parms = $request->get_params();
        $files_parms = $request->get_file_params();

        $attachment_ids = array();
        $message = '';
        $success = false;
        $message_type = $get_parms['message_type'];
        if (isset($files_parms['file']) && count($files_parms['file']) > 0) {
            $session = (isset($get_parms['session'])) ? $get_parms['session'] : '';
            $chat_id = (isset($get_parms['chat_id'])) ? $get_parms['chat_id'] : 0;
            $post_id = (isset($get_parms['post_id'])) ? $get_parms['post_id'] : '';
            $upload_type = (isset($get_parms['upload_type'])) ? $get_parms['upload_type'] : '';

            global $wpdb;
            require_once ABSPATH . 'wp-admin/includes/image.php';
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';
            $upload_data = whizzChat_upload_info($upload_type);
            if (!empty($files_parms['file']) && count($files_parms['file']) > 0 && isset($upload_data)) {
                $files = $files_parms['file'];
                $display_size = '100000';
                foreach ($files['name'] as $key => $value) {
                    if ($files['name'][$key]) {
                        $file = array(
                            'name' => $files['name'][$key],
                            'type' => $files['type'][$key],
                            'tmp_name' => $files['tmp_name'][$key],
                            'error' => $files['error'][$key],
                            'size' => $files['size'][$key]
                        );
                        $uploaded_file_type = $files['type'][$key];
                        $uploaded_file_size = $files['size'][$key];
                        $uploaded_file_name = $files['name'][$key];
                        $uploaded_file_size = $uploaded_file_size / 1000; // convert bytes to kilobytes
                        if (strpos($uploaded_file_type, 'image') !== false && $upload_type == 'image') {
                            $message_type = 'image';
                        }
                        if ($upload_type == 'file') {
                            $message_type = 'file';
                        }
                        $fileName = end(explode(".", $uploaded_file_name));
                        if ($uploaded_file_size > $upload_data['size']) {
                            $success = false;
                            $message = esc_html__("Max allowed size is", 'whizz-chat') . " " . $upload_data['size'];
                        } else if (!in_array($fileName, $upload_data['format'])) {
                            $success = false;
                            $message = esc_html__("Invalid format uploaded.", 'whizz-chat');
                        } else {
                            $_FILES = array("whizzChat_attachment" => $file);
                            foreach ($_FILES as $file => $array) {
                                $attachment_id = media_handle_upload('whizzChat_attachment', 0);
                                if (is_wp_error($attachment_id)) {
                                    return $response = rest_ensure_response(array('success' => false, 'data' => '', 'message' => esc_html__("Sorry, this file type is not permitted for security reason. ", 'whizz-chat')));
                                } else {

                                    $attachment_ids[] = $attachment_id;
                                }
                            }
                            $success = true;
                        }
                    }
                }
            }
        }
        $attachment_ids = json_encode($attachment_ids);
        $message_type = ($message_type != "") ? $message_type : 'text';
        $success = ( $message_type == 'text' || $message_type == 'map' ) ? true : $success;
        return array("success" => $success, "attachments" => $attachment_ids, "message_type" => $message_type, "message" => $message);
    }

}

if (!function_exists('whizzChat_start_chat')) {

    function whizzChat_start_chat(WP_REST_Request $request) {




        $json_data = $request->get_json_params();
        $get_parms = $request->get_params();
        $files_parms = $request->get_file_params();

        $nonce = (isset($get_parms['nonce'])) ? $get_parms['nonce'] : '';
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            $return = array('message' => esc_html__('Invalid security token sent....', 'whizz-chat'));
            wp_send_json_error($return);
        }
        if (isset($get_parms['action']) && 'whizzChat_send_chat_message' == $get_parms['action']) {
            global $wpdb;
            global $whizz_tbl_sessions;
            global $whizz_tblname_chat_message;
            global $whizz_tblname_chat_ratings;
            global $whizz_tblname_offline_msgs;
            $msg       = (isset($get_parms['msg'])) ? $get_parms['msg'] : '';
            $session   = (isset($get_parms['session'])) ? $get_parms['session'] : '';
            $post_id   = (isset($get_parms['post_id'])) ? $get_parms['post_id'] : '';
            $author_id = get_post_field('post_author', $post_id);

           $comm_id   = (isset($get_parms['comm_id'])) ? $get_parms['comm_id'] : '';


            $url = '#';
            $chat_id = (isset($get_parms['chat_id'])) ? $get_parms['chat_id'] : '';
            $chat_id = ($chat_id);
            $message_ids = (isset($get_parms['message_ids'])) ? $get_parms['message_ids'] : '';
            $current_user = get_current_user_id();
            $session_id = get_current_user_id();
            if ($session_id != $session) {
                return array("success" => false, "data" => "", "message" => esc_html__("Invalid session id", "whizz-chat"));
            }
            $query = "SELECT * FROM $whizz_tbl_sessions WHERE `id` = %s LIMIT 1";
            $results = $wpdb->get_results($wpdb->prepare($query, $chat_id));
            
            if (isset($results) && count($results) == 0) {
                $server_token = $rest_token = get_option("whizz_api_secret_token");
                $data_array = array();
                $name = $data_array['name'] = whizzChat::user_data('name');
                $email = $data_array['email'] = whizzChat::user_data('email');
                $data_array['url'] = $url;
                $data_array['session'] = $session;
                $data_array['cid'] = "";
                $data_array['server_token'] = $server_token;
                $data_array['chat_box_id'] = $post_id;
                $data_array['sender_id'] = whizzChat::user_data('id');
                $data_array['comm_id']    =  $comm_id ;
               
                $cid = apply_filters('whizzChat_register_user_and_session', $data_array);                               
                $query = "SELECT * FROM $whizz_tbl_sessions WHERE `id` = %s LIMIT 1";
                $results = $wpdb->get_results($wpdb->prepare($query, $cid));
                $cid = $results[0]->id;
                $email = $results[0]->email;
                $rel = $results[0]->rel;
                $session = $results[0]->session;
                $is_rel = ($current_user) ? $current_user : $session_id;
                $message_for = $results[0]->message_for;
                $message_count = $results[0]->message_count;
            } else {
                $cid = $results[0]->id;
                $name = whizzChat::user_data('name');
                $email = $results[0]->email;
                $rel = $results[0]->rel;
                $session = $results[0]->session;
                $is_rel = ($current_user) ? $current_user : $session_id;
                $message_for = $results[0]->message_for;
                $message_count = $results[0]->message_count;
            }


            $msgType = isset($get_parms['message_type']) ? $get_parms['message_type'] : 'text';
            $chat_id = (isset($results[0]->id)) ? $results[0]->id : '';
            $rel_id = (isset($results[0]->rel)) ? $results[0]->rel : '';
            $sessionId = (isset($results[0]->session)) ? $results[0]->session : '';
            $sender_id = ($sessionId != $session_id) ? $sessionId : $rel_id;
           
            $arg1 = array(
                "user_id" => $sender_id,
                "chat_id" => $chat_id,
                "post_id" => $post_id,
                "message_id" => $message_ids
            );
            $value = do_action('whizzChat_read_chat', $arg1);

            $type_args = array(
                "chat_id" => $cid, /* chat  session id */
                "receiver_id" => ( $session_id != $session ) ? $sessionId : $rel_id, /* other user id who will receive message */
                "chat_box_id" => $post_id, /* post id */
                "message_for" => $message_for, /* old user id in message_for column */
                "message_count" => $message_count /* message count from message_count column */,
                "current_user" => $session_id, /// current user session id /
                "is_update" => true,
            );
            $status = ($is_show == 1 ) ? 1 : 0;
            do_action('whizzChat_new_message_and_count', $type_args, $status);

            $message_type = $msgType;
            $attachments = '';
            if ($cid) {
                $extra_data = array();
                $extra_data['success'] = true;
                $extra_data['message_type'] = $msgType;
                $extra_data['attachments'] = '';
                if (isset($files_parms)) {

                    if ($get_parms['message_type'] == 'voice') {
                        $extra_data = apply_filters("whizzChat_send_sound_message_filter", $request, $cid);
                    } else {
                        $extra_data = apply_filters("whizzChat_send_message_filter", $request, $cid);
                    }
                }
                if ($extra_data['success'] == true) {
                    $message_type = isset($extra_data['message_type']) ? $extra_data['message_type'] : $msgType;
                    $attachments = isset($extra_data['attachments']) ? $extra_data['attachments'] : '';
                    $is_go = true;
                    if ($message_type == 'text' && $msg == "") {
                        $is_go = false;
                    } else if ($message_type == 'map') {
                        $attachments = isset($get_parms['map_data']) ? $get_parms['map_data'] : '[]';
                    }



                 
                    $blocked_status = whizzChat_is_user_blocked($cid, true);
                    if ($blocked_status['is_blocked'] != true && $is_go == true) {
                        $author_id = apply_filters('whizz_chat_author_rel_id', $author_id); // in admin only case 
                        $id = $wpdb->insert($whizz_tblname_chat_message, array('session_id' => $cid, 'timestamp' => current_time('mysql'), 'fromname' => $name, 'message' => $msg, 'status' => 0, 'rel' => $is_rel, 'author_id' => $author_id, 'post_id' => $post_id, 'message_type' => $message_type, 'attachments' => $attachments), array('%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s')
                        );
                        $message = esc_html__("Message sent.", "whizz-chat");
                        $success = true;
                    } else {
                        $message = esc_html__("User has blocked you. You can not send the message until user unblock you.", "whizz-chat");
                        $success = false;
                    }
                } else {
                    $message = $extra_data['message'];
                    $success = false;
                }
                $array['id'] = $cid;
                $array['send_message'] = 'text';

                $extraData['send_message'] = 'text';
                $extraData['message_ids'] = array(array("chat_id" => $cid, "get_message_id" => $message_ids));
                $chat_lists = whizzChat_chat_boxes($array, $extraData);
                $chat_boxes_html = array(
                    "post_id" => $post_id,
                    "chat_id" => $cid,
                    "html" => apply_filters('whizz_filter_chat_box_content', $chat_lists[0], $blocked_status)
                );
                $data['chat_boxes'] = json_encode($chat_boxes_html);
                return array("success" => $success, "data" => $data, "message" => $message);
            } else {
                return array("success" => false, "data" => "", "message" => esc_html__("No Chat Found", "whizz-chat"));
            }
        }
        /* End Action Here */
        return array("success" => false, "data" => "", "message" => esc_html__("Action not match", "whizz-chat"));
    }

}

if (!function_exists('whizzChat_start_chat_admin')) {

    function whizzChat_start_chat_admin(WP_REST_Request $request) {
        $json_data = $request->get_json_params();
        $get_parms = $request->get_params();
        $files_parms = $request->get_file_params();







        $nonce = (isset($get_parms['nonce'])) ? $get_parms['nonce'] : '';
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            $return = array('message' => esc_html__('Invalid security token sent....', 'whizz-chat'));
            wp_send_json_error($return);
        }
        if (isset($get_parms['action']) && 'whizzChat_send_chat_message_admin' == $get_parms['action']) {
            global $wpdb;
            global $whizz_tbl_sessions;
            global $whizz_tblname_chat_message;
            global $whizz_tblname_chat_ratings;
            global $whizz_tblname_offline_msgs;
            $msg = (isset($get_parms['msg'])) ? $get_parms['msg'] : '';
            $session = (isset($get_parms['session'])) ? $get_parms['session'] : '';
            $post_id = (isset($get_parms['post_id'])) ? $get_parms['post_id'] : '';
            $author_id = get_post_field('post_author', $post_id);
            $url = '#';
            $chat_id = (isset($get_parms['chat_id'])) ? $get_parms['chat_id'] : '';
            $chat_id = ($chat_id);
            $message_ids = (isset($get_parms['message_ids'])) ? $get_parms['message_ids'] : '';
            $current_user = get_current_user_id();
            $session_id = whizzChat::session_id();
            if ($session_id != $session) {
                return array("success" => false, "data" => "", "message" => esc_html__("Invalid session id", "whizz-chat"));
            }
            $query = "SELECT * FROM $whizz_tbl_sessions WHERE `id` = %s LIMIT 1";
            $results = $wpdb->get_results($wpdb->prepare($query, $chat_id));
            if (isset($results) && count($results) == 0) {
                $server_token = $rest_token = get_option("whizz_api_secret_token");
                $data_array = array();
                $name = $data_array['name'] = whizzChat::user_data('name');
                $email = $data_array['email'] = whizzChat::user_data('email');
                $data_array['url'] = $url;
                $data_array['session'] = $session;
                $data_array['cid'] = "";
                $data_array['server_token'] = $server_token;
                $data_array['chat_box_id'] = $post_id;
                $data_array['sender_id'] = whizzChat::user_data('id');
                $cid = apply_filters('whizzChat_register_user_and_session', $data_array);
                $query = "SELECT * FROM $whizz_tbl_sessions WHERE `id` = %s LIMIT 1";
                $results = $wpdb->get_results($wpdb->prepare($query, $cid));
                $cid = $results[0]->id;
                $email = $results[0]->email;
                $rel = $results[0]->rel;
                $session = $results[0]->session;
                $message_for = $results[0]->message_for;
                $message_count = $results[0]->message_count;
                $is_rel = ($current_user) ? $current_user : $session_id;
            } else {
                $cid = $results[0]->id;
                $name = whizzChat::user_data('name');
                $email = $results[0]->email;
                $rel = $results[0]->rel;
                $session = $results[0]->session;
                $message_for = $results[0]->message_for;
                $message_count = $results[0]->message_count;
                $is_rel = ($current_user) ? $current_user : $session_id;
            }
            $msgType = isset($get_parms['message_type']) ? $get_parms['message_type'] : 'text';
            $chat_id = (isset($results[0]->id)) ? $results[0]->id : '';
            $rel_id = (isset($results[0]->rel)) ? $results[0]->rel : '';
            $sessionId = (isset($results[0]->session)) ? $results[0]->session : '';
            $sender_id = ($sessionId != $session_id) ? $sessionId : $rel_id;
            $arg1 = array(
                "user_id" => $sender_id,
                "chat_id" => $chat_id,
                "post_id" => $post_id,
                "message_id" => $message_ids
            );
            $value = do_action('whizzChat_read_chat', $arg1);

            $type_args = array(
                "chat_id" => $chat_id, /* chat  session id */
                "receiver_id" => ( $session_id != $session ) ? $sessionId : $rel_id, /* other user id who will receive message */
                "chat_box_id" => $post_id, /* post id */
                "message_for" => $message_for, /* old user id in message_for column */
                "message_count" => $message_count /* message count from message_count column */,
                "current_user" => $session_id, /// current user session id /
                "is_update" => true,
            );
            $status = ($is_show == 1 ) ? 1 : 0;
            do_action('whizzChat_new_message_and_count', $type_args, $status);



            $message_type = $msgType;
            $attachments = '';
            if ($cid) {
                $extra_data = array();
                $extra_data['success'] = true;
                $extra_data['message_type'] = $msgType;
                $extra_data['attachments'] = '';
                if (isset($files_parms)) {
                    $extra_data = apply_filters("whizzChat_send_message_filter", $request, $cid);
                }
                if ($extra_data['success'] == true) {
                    $message_type = isset($extra_data['message_type']) ? $extra_data['message_type'] : $msgType;
                    $attachments = isset($extra_data['attachments']) ? $extra_data['attachments'] : '';
                    $is_go = true;
                    if ($message_type == 'text' && $msg == "") {
                        $is_go = false;
                    } else if ($message_type == 'map') {
                        $attachments = isset($get_parms['map_data']) ? $get_parms['map_data'] : '[]';
                    }

                    $blocked_status = whizzChat_is_user_blocked($cid, true);
                    if ($blocked_status['is_blocked'] != true && $is_go == true) {
                        $id = $wpdb->insert($whizz_tblname_chat_message, array('session_id' => $cid,
                            'timestamp' => current_time('mysql'),
                            'fromname' => $name,
                            'message' => $msg,
                            'status' => 0,
                            'rel' => $is_rel,
                            'author_id' => $author_id,
                            'post_id' => $post_id,
                            'message_type' => $message_type,
                            'attachments' => $attachments
                                ), array('%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s')
                        );
                        $message = esc_html__("Message sent.", "whizz-chat");
                    } else {
                        $message = esc_html__("Message not sent.", "whizz-chat");
                    }
                    $success = true;
                } else {
                    $message = $extra_data['message'];
                    $success = false;
                }
                $array['id'] = $cid;
                $array['send_message'] = 'text';

                $extraData['send_message'] = 'text';
                $extraData['message_ids'] = array(array("chat_id" => $cid, "get_message_id" => $message_ids));
                $chat_lists = whizzChat_chat_boxes($array, $extraData);
                $chat_boxes_html = array(
                    "post_id" => $post_id,
                    "chat_id" => $cid,
                    "html" => apply_filters('whizz_filter_chat_box_content_admin', $chat_lists[0], $blocked_status)
                );
                $data['chat_boxes'] = json_encode($chat_boxes_html);
                return array("success" => $success, "data" => $data, "message" => $message);
            } else {
                return array("success" => false, "data" => "", "message" => esc_html__("No Chat Found", "whizz-chat"));
            }
        }
        /* End Action Here */
        return array("success" => false, "data" => "", "message" => esc_html__("Action not match", "whizz-chat"));
    }

}

if (!function_exists('whizzChat_get_chat_list_box')) {

    function whizzChat_get_chat_list_box(WP_REST_Request $request) {
        global $wpdb, $whizz_tbl_sessions, $whizz_tblname_chat_message, $whizzChat_options;
        $json_data = $request->get_json_params();
        $get_parms = $request->get_params();
        $nonce = (isset($get_parms['nonce'])) ? $get_parms['nonce'] : '';
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            return array("success" => false, "data" => "", "message" => esc_html__('Invalid security token sent....', 'whizz-chat'));
        }

        $whizzChat_options = get_option('whizz-chat-options');
        $whizzchat_admin_page = $whizzChat_options["whizzChat-admin-page"];
        $whizzchat_between = $whizzChat_options["whizzChat-chat-between"];

        $admin_chat_flag = FALSE;
        if (isset($whizzchat_between) && $whizzchat_between == '2') {
            $admin_chat_flag = TRUE;
        }

        $return_data = array();

        $ids_dataa = array();
        $list_html = '';
        $list_ids = isset($get_parms['list_ids']) && $get_parms['list_ids'] != '' ? json_decode($get_parms['list_ids']) : '';
        $session = isset($get_parms['session']) && $get_parms['session'] != '' ? ($get_parms['session']) : '';
        $list_data = isset($get_parms['list_data']) && $get_parms['list_data'] != '' ? ($get_parms['list_data']) : 0;

        $diff_ids = array();


        $query   =     "SELECT * FROM {$whizz_tbl_sessions} WHERE `session` = %s OR `rel` = %s ORDER BY last_active_timestamp DESC"  ;
        $prepared_statementR = $wpdb->prepare($query,$session,$session);

        $results_data = $wpdb->get_results($prepared_statementR);
        $results_array = array_merge();
        if (isset($results_data) && count($results_data) > 0) {

            $usr_session_id = whizzChat::session_id();

            foreach ($results_data as $rdata) {
                $get_id = $rdata->id;
                
                $new_qr  =   "SELECT * FROM $whizz_tblname_chat_message WHERE `session_id` = %s";
                
                $new_list = $wpdb->get_row($wpdb->prepare($new_qr,$get_id));
                if (isset($new_list->post_id) && $new_list->post_id != '') {

                    if ($admin_chat_flag && $new_list->post_id == $whizzchat_admin_page) {
                        //continue;
                    }
                    $ids_dataa[] = $get_id;
                    $size = array(150, 150);
                    $url = get_the_post_thumbnail_url($new_list->post_id, $size);

                    $image = '';
                    $image_id = '';

                    if ($url) {
                        $image = '<img src="' . esc_url($url) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '"/>';
                    } else {
                        $url = plugin_dir_url('/') . 'whizz-chat/assets/images/no-list-img.jpg';
                        $image = '<img src="' . esc_url($url) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '"/>';
                    }


                    $chat_title = get_the_title($new_list->post_id);
                    $author_id = get_post_field('post_author', $new_list->post_id);


                    if($author_id ==  ""){

                      $author_id   =  $new_list->post_id;
                    }

                    if ($usr_session_id != $author_id) {
                        $display_name = get_the_author_meta('display_name', $author_id);
                    } else {
                        $display_name = $rdata->name;
                    }

                    if ($usr_session_id == $author_id) {
                            
                         $to_display   =   $author_id ==  $rdata->rel   ?  $rdata->sender_id  : $rdata->rel;
                         $display_name = get_the_author_meta('display_name', $to_display);


                        if(isset($rdata->session) &&  !is_numeric($rdata->session)){
                          $display_name = $rdata->name;
                         }
                        }

                    $last_active_time = whizzChat::whizzchat_time_ago($rdata->last_active_timestamp);
                    $liste = "'list'";
                    $message_count = $rdata->message_count;
                    $message_for = $rdata->message_for;

                    $message_count_html = ($usr_session_id == $message_for) ? "<span id='chat-badge-" . $get_id . "' class='chat-badge-count'>" . $message_count . "</span>" : '';
                    $alert_msg_class = ($usr_session_id == $message_for && $message_count > 0) ? "chatlist-message-alert" : "";

                    $class_check = ( $author_id != $session ) ? ' new-chat-message-sender ' : ' new-chat-message-receiver ';

                    $list_html .= '<li id="' . $get_id . '" class="' . $alert_msg_class . ' ' . $class_check . '">'
                            . '<a class="thumbnail" href="javascript:void(0);" onclick="return open_whizz_chat(' . $get_id . ',' . $liste . ')">' . $image . '</a>
                                 <div class="content">
                                    <h3 class="whizz-chat-text-nowrap">
                                      <a onclick="return open_whizz_chat(' . $get_id . ',' . $liste . ')" href="javascript:void(0);">' . whizzchat_words_count($chat_title, 30) . '</a>
                                    </h3>
                                    <span class="preview">' . $display_name . '</span>
                                    <span class="meta"> ' . $last_active_time . '</span>
                                        ' . $message_count_html . '
                                 </div>
                              </li>';
                }
            }
        }
        $return_data['list_ids'] = json_encode($ids_dataa);
        $return_data['list_html'] = json_encode($list_html);
        return $return_data;
    }

}

if (!function_exists('whizzChat_get_chat_list_box_admin')) {

    function whizzChat_get_chat_list_box_admin(WP_REST_Request $request) {
        global $wpdb, $whizz_tbl_sessions, $whizz_tblname_chat_message, $whizzChat_options;
        $json_data = $request->get_json_params();
        $get_parms = $request->get_params();
        $nonce = (isset($get_parms['nonce'])) ? $get_parms['nonce'] : '';
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            return array("success" => false, "data" => "", "message" => esc_html__('Invalid security token sent....', 'whizz-chat'));
        }
        
        
        
        

        $whizzChat_options = get_option('whizz-chat-options');
        $whizzchat_admin_page = $whizzChat_options["whizzChat-admin-page"];
        $whizzchat_between = $whizzChat_options["whizzChat-chat-between"];

        $admin_chat_flag = FALSE;
        if (isset($whizzchat_between) && $whizzchat_between == '2') {
            $admin_chat_flag = TRUE;
        }

        $return_data = array();

        $ids_dataa = array();
        $list_html = '';
        $list_ids = isset($get_parms['list_ids']) && $get_parms['list_ids'] != '' ? json_decode($get_parms['list_ids']) : '';
        $session = isset($get_parms['session']) && $get_parms['session'] != '' ? ($get_parms['session']) : '';
        $list_data = isset($get_parms['list_data']) && $get_parms['list_data'] != '' ? ($get_parms['list_data']) : 0;

        $diff_ids = array();

       
        $new_qr     =    "SELECT * FROM {$whizz_tbl_sessions} WHERE `session` = %s OR `rel` = %s ORDER BY last_active_timestamp DESC";

        $prepared_statementR = $wpdb->prepare($new_qr, $session, $session);
        $results_data = $wpdb->get_results($prepared_statementR);
        $results_array = array_merge();
        if (isset($results_data) && count($results_data) > 0) {

            $usr_session_id = whizzChat::session_id();

            foreach ($results_data as $rdata) {


                $get_id = $rdata->id;
                $new_query    =    "SELECT * FROM $whizz_tblname_chat_message WHERE `session_id` = %s";
                $new_list = $wpdb->get_row($wpdb->prepare($new_query,$get_id));
                if (isset($new_list->post_id) && $new_list->post_id != '') {

                    if ($new_list->post_id != $whizzchat_admin_page) {
                        continue;
                    }
                    $ids_dataa[] = $get_id;
                    $size = array(150, 150);
                    $url = get_the_post_thumbnail_url($new_list->post_id, $size);

                    $image = '';
                    $image_id = '';
                    if ($url) {
                        $image = '<img src="' . esc_url($url) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" width="50" class="rounded-circle"/>';
                    } else {
                        $url = plugin_dir_url('/') . 'whizz-chat/assets/images/admin_list_user.svg';
                        $image = '<img src="' . esc_url($url) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" width="50" class="rounded-circle">';
                    }


                    $chat_title = get_the_title($new_list->post_id);
                    $author_id = get_post_field('post_author', $new_list->post_id);


                    if ($usr_session_id != $author_id) {
                        $display_name = get_the_author_meta('display_name', $author_id);
                    } else {
                        $display_name = $rdata->name;
                    }
                    $last_active_time = whizzChat::whizzchat_time_ago($rdata->last_active_timestamp);
                    $liste = "'list'";
                    $message_count = $rdata->message_count;
                    $message_for = $rdata->message_for;

                    $message_count_html = ($usr_session_id == $message_for) ? "<span class='badge badge-light'>" . $message_count . "<span>" : '';
                    $alert_msg_class = ($usr_session_id == $message_for && $message_count > 0) ? "chatlist-message-alert" : "";

                    $class_check = ( $author_id != $session ) ? ' new-chat-message-sender ' : ' new-chat-message-receiver ';

                    $clicked = "'" . $get_id . "'";
                    $list_html .= '<a  id="' . $get_id . '" href="javascript:void(0);" data-author-id="' . $author_id . '" onClick="return open_whizz_chat_admin(' . $clicked . ',' . $new_list->post_id . ',this)" class="' . $alert_msg_class . ' ' . $class_check . 'list-group-item list-group-item-action list-group-item-light rounded-0">
                                        <div class="media">
                                          ' . $image . '
                                            <div class="media-body ml-4">
                                                <div class="d-flex align-items-center justify-content-between mb-1">
                                                    <h6 class="mb-0">' . $display_name . '</h6><small class="small">' . $last_active_time . '</small>
                                                </div>
                                                <p class="font-italic text-muted mb-0 text-small" title="' . $chat_title . '">' . whizzchat_words_count($chat_title, 50) . '</p>
					       ' . $message_count_html . '	
                                            </div>
                                        </div>
                                    </a>';
                }
            }
        }
        $return_data['list_ids'] = json_encode($ids_dataa);
        $return_data['list_html'] = json_encode($list_html);
        return $return_data;
    }

}


if (!function_exists('whizzChat_get_chat_list')) {

    function whizzChat_get_chat_list(WP_REST_Request $request) {
        
        
        $json_data = $request->get_json_params();
        $get_parms = $request->get_params();
        
    

        $nonce = (isset($get_parms['nonce'])) ? $get_parms['nonce'] : '';
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            return array("success" => false, "data" => "", "message" => esc_html__('Invalid security token sent....', 'whizz-chat'));
        }
        /* Register user status online */
        $chat_list_html = '';
        /* Load Chat List */
        if (isset($chat_info) && count($chat_info) > 0) {
            $chat_list_html = apply_filters('whizzChat_load_chat_list', $chat_info);
        }
        /* Load Active Chat Boxes */
        $chat_boxes_html = array();

       
        $chat_boxs = (isset($get_parms['boxs'])) ? $get_parms['boxs'] : '';

        $message_ids = (isset($get_parms['message_ids'])) ? $get_parms['message_ids'] : '';

         

        if ($chat_boxs != "") {
            $arr = array();
            $chats_boxes = (json_decode($chat_boxs, true));
            $message_ids = (json_decode($message_ids, true));

            if (isset($chats_boxes) && !empty($chats_boxes)) {
                $extra_data['send_message'] = 'text';
                $extra_data['message_ids'] = $message_ids;
                  

                $chat_info = whizzChat_chat_boxes($chats_boxes, $extra_data);
                

                if (isset($chat_info) && count($chat_info) > 0) {
                    foreach ($chat_info as $chat) {
                        $post_id = ($chat["post_id"]);
                        $chat_id = ($chat["id"]);
                        $session_id = ($chat["session_id"]);
                        $post_author_id = $chat["post_author_id"];
                        $blocked_status = whizzChat_is_user_blocked($chat_id, true);
                        $chat_boxes_html[] = array(
                            "post_id" => $post_id,
                            "chat_id" => $chat_id,
                            "is_online" => whizzChat::user_online_status($session_id, $post_author_id),
                            "html" => apply_filters('whizz_filter_chat_box_content', $chat, $blocked_status),
                            'session_id'=>$session_id,
                            'post_author_id'=>$post_author_id,
                        );
                    }
                }
            }
        }
        $data['chat_list'] = $chat_list_html;
        $data['chat_boxes'] = json_encode($chat_boxes_html);
        return array("success" => true, "data" => $data, "message" => "");
    }

}
if (!function_exists('whizzChat_get_chat_list_admin')) {

    function whizzChat_get_chat_list_admin(WP_REST_Request $request) {
        $json_data = $request->get_json_params();
        $get_parms = $request->get_params();
        $nonce = (isset($get_parms['nonce'])) ? $get_parms['nonce'] : '';
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            return array("success" => false, "data" => "", "message" => esc_html__('Invalid security token sent....', 'whizz-chat'));
        }
        /* Register user status online */
        $chat_list_html = '';
        /* Load Chat List */
        if (isset($chat_info) && count($chat_info) > 0) {
            $chat_list_html = apply_filters('whizzChat_load_chat_list_admin', $chat_info);
        }
        /* Load Active Chat Boxes */
        $chat_boxes_html = array();
        $chat_boxs = (isset($get_parms['boxs'])) ? $get_parms['boxs'] : '';
        $message_ids = (isset($get_parms['message_ids'])) ? $get_parms['message_ids'] : '';
        if ($chat_boxs != "") {
            $arr = array();
            $chats_boxes = (json_decode($chat_boxs, true));
            $message_ids = (json_decode($message_ids, true));

            if (isset($chats_boxes) && !empty($chats_boxes)) {
                $extra_data['send_message'] = 'text';
                $extra_data['message_ids'] = $message_ids;
                $chat_info = whizzChat_chat_boxes($chats_boxes, $extra_data);
                if (isset($chat_info) && count($chat_info) > 0) {
                    foreach ($chat_info as $chat) {
                        $post_id = ($chat["post_id"]);
                        $chat_id = ($chat["id"]);
                        $session_id = ($chat["session_id"]);
                        $post_author_id = $chat["post_author_id"];
                        $blocked_status = whizzChat_is_user_blocked($chat_id, true);
                        $chat_boxes_html[] = array(
                            "post_id" => $post_id,
                            "chat_id" => $chat_id,
                            "is_online" => whizzChat::user_online_status($session_id, $post_author_id),
                            "html" => apply_filters('whizz_filter_chat_box_content_admin', $chat, $blocked_status)
                        );
                    }
                }
            }
        }

        $data['chat_list'] = $chat_list_html;
        $data['chat_boxes'] = json_encode($chat_boxes_html);
        return array("success" => true, "data" => $data, "message" => "");
    }

}


if (!function_exists('whizz_api_register_chat_session')) {

    function whizz_api_register_chat_session(WP_REST_Request $request) {

        global $whizzChat_options;
        $user_type = $whizzChat_options['whizzChat-chat-type'];
        $user_type = isset($user_type) && $user_type != '' ? $user_type : 1;
        $json_data = $request->get_json_params();
        $get_parms = $request->get_params();
        $nonce = (isset($get_parms['nonce'])) ? $get_parms['nonce'] : '';
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            $return = array('message' => esc_html__('Invalid security token sent....', 'real'));
            wp_send_json_error($return);
        }
        $rest_token = get_option("whizz_api_secret_token");
        $name = (isset($get_parms['whizzChat_name'])) ? $get_parms['whizzChat_name'] : '';
        $email = (isset($get_parms['whizzChat_email'])) ? $get_parms['whizzChat_email'] : '';
        $session_type = (isset($get_parms['session_type'])) ? $get_parms['session_type'] : 'chat_box';

        $url = (isset($get_parms['url'])) ? $get_parms['url'] : '';

        $cookie_name = 'whizchat-' . str_replace(' ', '-', $name);

        if ($session_type == 'chat_bot') {  // in chat bot 1 = need to create new session with name || 2 = use already created session
            if (isset($_COOKIE['whizzChat_name']) && $_COOKIE['whizzChat_name'] != '') {
                $name = $_COOKIE['whizzChat_name'];
                $email = $_COOKIE['whizzChat_email'];
                $cookie_name = 'whizchat-' . str_replace(' ', '-', $_COOKIE['whizzChat_name']);
                $session_plus = $_COOKIE[$cookie_name];
            } else {

                $session_plus = md5($name . time());
                setcookie("whizzChat_name", $name, time() + 31556926, COOKIEPATH, COOKIE_DOMAIN, 0, 0);
                setcookie("whizzChat_email", $email, time() + 31556926, COOKIEPATH, COOKIE_DOMAIN, 0, 0);
                setcookie($cookie_name, $session_plus, time() + 31556926, COOKIEPATH, COOKIE_DOMAIN, 0, 0);

                // set cookie data for js cookie storage
                $set_cookie[] = array('key' => 'whizzChat_name', 'value' => $name, 'time' => time() + 31556926);
                $set_cookie[] = array('key' => 'whizzChat_email', 'value' => $email, 'time' => time() + 31556926);
                $set_cookie[] = array('key' => $cookie_name, 'value' => $session_plus, 'time' => time() + 31556926);
            }


              if(get_current_user_id() != 0 && get_current_user_id() != ""){

                $session_plus = whizzChat::cookie_id();
              }

        } else {

            if (isset($_COOKIE['whizzChat_name']) && $_COOKIE['whizzChat_name'] != '') {
                $name = $_COOKIE['whizzChat_name'];
                $email = $_COOKIE['whizzChat_email'];
                $cookie_name = 'whizchat-' . str_replace(' ', '-', $_COOKIE['whizzChat_name']);
            } else {
                $session_plus = md5($name . time());
                setcookie("whizzChat_name", $name, time() + 31556926, COOKIEPATH, COOKIE_DOMAIN, 0, 0);
                setcookie("whizzChat_email", $email, time() + 31556926, COOKIEPATH, COOKIE_DOMAIN, 0, 0);
                setcookie($cookie_name, $session_plus, time() + 31556926, COOKIEPATH, COOKIE_DOMAIN, 0, 0);

                // set cookie data for js cookie storage
                $set_cookie[] = array('key' => 'whizzChat_name', 'value' => $name, 'time' => time() + 31556926);
                $set_cookie[] = array('key' => 'whizzChat_email', 'value' => $email, 'time' => time() + 31556926);
                $set_cookie[] = array('key' => $cookie_name, 'value' => $session_plus, 'time' => time() + 31556926);
            }
        }

        $cid = (isset($get_parms['post_id'])) ? $get_parms['post_id'] : '';
        $requested_cid = $cid;
        $server_token = $rest_token;
        $chat_box_id = (isset($get_parms['post_id'])) ? $get_parms['post_id'] : '';
        $data_array = array();
        $data_array['name'] = $name;
        $data_array['email'] = $email;
        $data_array['url'] = $url;
        $data_array['session'] = $session_plus;
        $data_array['chat_box_id'] = $chat_box_id;



        $filter_data = apply_filters('whizzChat_register_user_and_session', $data_array);


        $array['id'] = $filter_data;
        $chat_list = whizzChat_chat_boxes($array);
        $chat_box_html = apply_filters('whizzChat_load_chat_chatbox', $chat_list);
        if ($session_type == 'chat_bot') {
            setcookie("Whizz_Admin_Chat_id", $filter_data, time() + 31556926, COOKIEPATH, COOKIE_DOMAIN, 0, 0);
        }

        $return_data = array('html_data' => $chat_box_html, 'whizz_cookie_data' => $set_cookie, 'chat_id' => $filter_data);
        return $return_data;
    }

}

add_action("whizzChat_sortsession_action", 'whizzChat_sortsession_data_func');

function whizzChat_sortsession_data() {
    $chats = '';
    if (isset($_SESSION['whizzChat_sessions']) && count($_SESSION['whizzChat_sessions']) > 0) {
        $sess_key = intval($cid);
        $compare_session = whizzChat_session_values($sess_key, $_SESSION['whizzChat_sessions'], true);
        if (isset($compare_session) && count($compare_session) > 0) {
            global $wpdb;
            global $whizz_tbl_sessions;
            foreach ($_SESSION['whizzChat_sessions'] as $key => $val) {
                $whizz_cid = ($val['session_id']);
                $whizz_cid_session = ($val['chat_session_id']);
                $chats = $wpdb->get_results("SELECT * FROM $whizz_tbl_sessions WHERE session = '" . $whizz_cid_session . "' ORDER BY ID DESC LIMIT 1 ");
                if (isset($chats) && count($chats) > 0) {
                    
                }
            }
        }
    } else {
        
    }
}

if (!function_exists('whizz_api_end_chat_session')) {

    function whizz_api_end_chat_session(WP_REST_Request $request) {
        global $whizz_tbl_sessions, $wpdb;
        $json_data = $request->get_json_params();
        $get_parms = $request->get_params();
        $nonce = (isset($get_parms['nonce'])) ? $get_parms['nonce'] : '';
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            $return = array('message' => esc_html__('Invalid security token sent....', 'real'));
            wp_send_json_error($return);
        }
        $leave_chat_id = (isset($get_parms['cid'])) ? $get_parms['cid'] : '';
        if ($wpdb->delete($whizz_tbl_sessions, array('ID' => $leave_chat_id))) {
            $return_data['success'] = true;
        } else {
            $return_data['success'] = 'false';
        }
        return ($return_data);
    }

}



// send email to a user if he is offile 



if (!function_exists('send_offline_user_email_fun')){
    function send_offline_user_email_fun(WP_REST_Request $request)
    {      
        $json_data = $request->get_json_params();
        $get_parms = $request->get_params();   
              
        wp_send_json_success(array('message' => "" ,'user_name' => ""));
        die();
        $nonce = (isset($get_parms['nonce'])) ? $get_parms['nonce'] : '';
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            $return = array('message' => esc_html__('Invalid security token sent....', 'real'));
            wp_send_json_error($return);
        }      
        $receiver_id = (isset($get_parms['receiver_id'])) ? $get_parms['receiver_id'] : '';        
        $msg = (isset($get_parms['message'])) ? $get_parms['message'] : '';       
        $chat_id = (isset($get_parms['chat_id'])) ? $get_parms['chat_id'] : '';        
        $user_data = get_userdata( $receiver_id ); 
        if ( $user_data == false )
        { 
            $return = array('message' => esc_html__('User not exist', 'real'));
            wp_send_json_error($return);            
        } else 
        {                     
          $user_email   =   isset($user_data->user_email)   ?   $user_data->user_email   : "" ;
          $user_name   =  isset($user_data->display_name)   ?   $user_data->display_name   : "" ;
          if($user_email != ""){                    
              global $wpdb  , $whizz_tbl_sessions;
              $prepared_statementR = $wpdb->prepare("SELECT * FROM {$whizz_tbl_sessions} WHERE `id` = '{$chat_id}' AND `rel` = '{$receiver_id}' ");
              $results_data = $wpdb->get_results($prepared_statementR);                           
              if (isset($results_data) && count($results_data) > 0) {           
             $subject    = esc_html__('You have new Message','real');      
             wp_mail($user_email, $subject, $msg);
             $return = array('message' => $msg ,'user_name' => $user_name ,'site_url' => site_url() ,'site_name' =>get_bloginfo('name'));
             wp_send_json_success($return);
                
                  
              }             
          }
        }    
    }     
}
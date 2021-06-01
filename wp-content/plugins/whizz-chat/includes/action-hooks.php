<?php
/* Set different actions to performs */
add_action("whizzChat_end_session_chat_id", 'whizzChat_end_session_func'); // not uin use
add_action("whizzChat_read_chat", 'whizzChat_read_chat_func', 10, 3);
add_action("whizzChat_close_chat_box", 'whizzChat_close_chat_box_func', 10, 3);
add_action("whizzChat_new_message_and_count", 'whizzChat_new_message_and_count_func', 10, 3);

/* Add Filters */

add_filter('whizzChat_register_user_and_session', 'whizzChat_register_user_and_session_func', 10, 1);
add_filter('whizzChat_list_chat_messages', 'whizzChat_list_chat_messages_func', 10, 1);
add_filter('whizzChat_list_chat_messages_dashboard', 'whizzChat_list_chat_messages_dashb_func', 10, 1);


if (!function_exists('whizzChat_new_message_and_count_func')) {

    function whizzChat_new_message_and_count_func($args = array(), $status_arg = '', $arg3 = '') {

        $chat_id = (isset($args['chat_id']) && $args['chat_id'] != '') ? $args['chat_id'] : 0;


        if (isset($args['is_update']) && $args['is_update'] == false) {
            if (isset($args['chat_id']) && $args['chat_id'] != '' && ($args['current_user'] == $args['message_for'])) {
                global $whizz_tbl_sessions;
                global $wpdb;
                $data = array('message_for' => 0, 'message_count' => 0);
                $where = array('id' => $chat_id);
                $wpdb->update($whizz_tbl_sessions, $data, $where);
            }
        } else {

            $blocked_status = whizzChat_is_user_blocked($chat_id, true);
            if (isset($blocked_status['is_blocked']) && $blocked_status['is_blocked'] == true) {
                
            } else {
                if (isset($args['chat_id']) && $args['receiver_id'] != '') {
                    global $whizz_tbl_sessions;
                    global $wpdb;
                    $last_chat_box = isset($args['last_chat_index']) ? $args['last_chat_index'] : '';
                    $receiver_id = $args['receiver_id'];
                    $message_count = $args['message_count'];
                    $current_user = $args['current_user'];
                    $message_for = $args['message_for'];
                    $message_count = ( $current_user == $message_for ) ? 1 : $message_count + 1;
                    $time = date("Y-m-d H:i:s");
                    $data = array('last_active_timestamp' => $time, 'message_for' => $receiver_id, 'message_count' => $message_count);
                    $where = array('id' => $chat_id);
                    $wpdb->update($whizz_tbl_sessions, $data, $where);
                }
            }
        }
    }

}




if (!function_exists('whizzChat_close_chat_box_func')) {

    function whizzChat_close_chat_box_func($args = array(), $status_arg = '', $arg3 = '') {
        /*
          @Array()
          "chat_id"   => 1, int
          "user_type" => "", string = sender or receiver

          $status_arg = status 0 or 1
         */

        $last_chat_box = $args['last_chat_index'];

        global $whizz_tbl_sessions;
        global $wpdb;
        $chat_id = (isset($args['chat_id']) && $args['chat_id'] != '') ? $args['chat_id'] : 0;
        $user_type = '';
        if (isset($args['chat_id']) && $args['user_type'] == 'sender') {

            $data = array(
                'chatbox_sender_open' => $status_arg,
            );
            $where = array(
                'id' => $chat_id,
            );
            $wpdb->update($whizz_tbl_sessions, $data, $where);

            if ($last_chat_box != 0) {
                $data = array(
                    'chatbox_sender_open' => 0,
                );
                $where = array(
                    'id' => $last_chat_box,
                );
                $wpdb->update($whizz_tbl_sessions, $data, $where);
            }
        } else if (isset($args['user_type']) && $args['user_type'] == 'receiver') {

            $data = array(
                'chatbox_receiver_open' => $status_arg,
            );
            $where = array(
                'id' => $chat_id,
            );
            $wpdb->update($whizz_tbl_sessions, $data, $where);
            if ($last_chat_box != 0) {

                $data = array(
                    'chatbox_receiver_open' => 0,
                );
                $where = array(
                    'id' => $last_chat_box,
                );
                $wpdb->update($whizz_tbl_sessions, $data, $where);
            }
        }
    }

}

if (!function_exists('whizzChat_read_chat_func')) {

    function whizzChat_read_chat_func($args = array(), $arg2 = '', $arg3 = '') {
        /*
          @Array()
          "user_id" => "",
          "chat_id" 	 => "",
          "post_id" 	 => "",
          "message_id" => ""	 //Last message id in chatbox.
         */
        $user_id = (isset($args['user_id'])) ? $args['user_id'] : '';
        $chat_id = (isset($args['chat_id'])) ? $args['chat_id'] : '';
        $post_id = (isset($args['post_id'])) ? $args['post_id'] : '';
        $message_id = (isset($args['message_id'])) ? $args['message_id'] : '';
        if ($user_id == "" || $chat_id == "" || $post_id == "" || $message_id == "")
            return '';

        global $wpdb;
        global $whizz_tblname_chat_message;
        $query = "SELECT id FROM $whizz_tblname_chat_message WHERE `session_id` = '" . $chat_id . "' AND `post_id` = '" . $post_id . "' AND `rel` = '" . $user_id . "' AND id <= '" . $message_id . "' AND `seen_at` IS NULL ORDER BY `id` DESC";
        $messages = $wpdb->get_results($query);
        if (isset($messages) && count($messages) > 0) {
            $ids_list = array();
            foreach ($messages as $key => $message) {
                $ids_list[] = ($message->id);
            }
            $ids = "(" . implode(',', array_map('intval', $ids_list)) . ")";
            $time = date("Y-m-d H:i:s");
            $update = $wpdb->query("UPDATE $whizz_tblname_chat_message SET seen_at = '" . $time . "' WHERE `id` IN $ids");
        }
    }

}

if (!function_exists('whizzChat_list_chat_messages_func')) {

    function whizzChat_list_chat_messages_func($data_array = array()) {
        global $wpdb;
        global $whizz_tblname_chat_message;
        $session_id = $data_array['id'];
        $ad_id = $data_array['post_id'];
        $current_user = get_current_user_id();

        $load_messages = '';
        if (isset($data_array['last_chat_id']) && $data_array['last_chat_id'] != "") {
            $last_chat_id = $data_array['last_chat_id'];
            if($last_chat_id != "" || $last_chat_id != "undefined"){
                $load_messages = " AND ID < '$last_chat_id'";
            }
        } else if (isset($data_array['first_message_id']) && $data_array['first_message_id'] != "") {
            $first_message_id = $data_array['first_message_id'];
            if($first_message_id != "" || $last_chat_id != "undefined"){
                $load_messages = " AND ID > '$first_message_id'";
            }
        }

        $query = "SELECT * FROM $whizz_tblname_chat_message WHERE ( session_id = '" . $session_id . "' ) AND post_id = '" . $ad_id . "' " . $load_messages . " ORDER BY ID DESC LIMIT 6";

        $chats = $wpdb->get_results($query);
        $chat_messages = array();
        $chats = array_reverse($chats, true);

        if (isset($chats) && count($chats) > 0) {
            foreach ($chats as $key => $value) {
                $is_reply = '';
                if ($current_user > 0) {
                    if ($value->rel != $current_user) {
                        $is_reply = 'message-partner';
                    }
                } else {
                    if ($value->rel != whizzChat::session_id()) {
                        $is_reply = 'message-partner';
                    }
                }

                if ($is_reply == '') {
                    $is_reply = 'message-sender-box';
                }

                $chat_messages[] = array(
                    "chat_message_id" => $value->id,
                    "chat_sender_id" => $value->session_id,
                    "chat_sender_name" => $value->fromname,
                    "chat_message" => $value->message,
                    "chat_post_id" => $value->post_id,
                    "chat_post_author" => $value->author_id,
                    "chat_time" => $value->timestamp,
                    "is_reply" => $is_reply,
                    "rel" => $value->rel,
                    "message_type" => $value->message_type,
                    "attachments" => $value->attachments,
                    "seen_at" => $value->seen_at
                );
            }
        }
        return $chat_messages;
    }

}

if (!function_exists('whizzChat_list_chat_messages_dashb_func')) {

    function whizzChat_list_chat_messages_dashb_func($data_array = array()) {
        global $wpdb;
        global $whizz_tblname_chat_message;
        $session_id = $data_array['id'];
        $ad_id = $data_array['post_id'];
        $current_user = get_current_user_id();

        $load_messages = '';
        if (isset($data_array['last_chat_id']) && $data_array['last_chat_id'] != "") {
            $last_chat_id = $data_array['last_chat_id'];
            if($last_chat_id != "" || $last_chat_id != "undefined"){
                $load_messages = " AND ID < '$last_chat_id'";
            }
        } else if (isset($data_array['first_message_id']) && $data_array['first_message_id'] != "") {
            $first_message_id = $data_array['first_message_id'];
            if($first_message_id != "" || $last_chat_id != "undefined"){
                $load_messages = " AND ID > '$first_message_id'";
            }
        }

        $query = "SELECT * FROM $whizz_tblname_chat_message WHERE ( session_id = '" . $session_id . "' ) AND post_id = '" . $ad_id . "' " . $load_messages . " ORDER BY ID DESC LIMIT 10";

        $chats = $wpdb->get_results($query);
        $chat_messages = array();
        $chats = array_reverse($chats, true);

        if (isset($chats) && count($chats) > 0) {
            foreach ($chats as $key => $value) {
                $is_reply = '';
                if ($current_user > 0) {
                    if ($value->rel != $current_user) {
                        $is_reply = 'message-partner';
                    }
                } else {
                    if ($value->rel != whizzChat::session_id()) {
                        $is_reply = 'message-partner';
                    }
                }

                if ($is_reply == '') {
                    $is_reply = 'message-sender-box';
                }

                $chat_messages[] = array(
                    "chat_message_id" => $value->id,
                    "chat_sender_id" => $value->session_id,
                    "chat_sender_name" => $value->fromname,
                    "chat_message" => $value->message,
                    "chat_post_id" => $value->post_id,
                    "chat_post_author" => $value->author_id,
                    "chat_time" => $value->timestamp,
                    "is_reply" => $is_reply,
                    "rel" => $value->rel,
                    "message_type" => $value->message_type,
                    "attachments" => $value->attachments,
                    "seen_at" => $value->seen_at
                );
            }
        }
        return $chat_messages;
    }

}

if (!function_exists('whizzChat_register_user_and_session_func')) {

    function whizzChat_register_user_and_session_func($data_array = array()) {

        /*
         * data array consists of
         * 
         * name
         * email
         * url
         * session
         * chat_box_id
         * 
         * cid = ''
         * server_token = ''
         * sender_id = ''
         */

        extract($data_array);

        global $wpdb;
        global $whizz_tbl_sessions;
        $cookie_id = whizzChat::cookie_id();
        $cookie_id = isset($cookie_id) && $cookie_id != '' ? $cookie_id : $session;

        if ($cookie_id != $session) {
            return 'Session and cookies not matched';
        }

        $message_type = ( isset($message_type) && $message_type != "") ? $message_type : 'message';
        $qry = "SELECT id FROM $whizz_tbl_sessions WHERE `session` = %s AND `chat_box_id` = %s ORDER BY ID DESC LIMIT 1";
        $chats = $wpdb->get_results($wpdb->prepare($qry, $session, $chat_box_id));
        $chat_id = (isset($chats[0]->id)) ? $chats[0]->id : 0;
        if (isset($chats) && count($chats) == 0) {
            $name = substr(strip_tags(sanitize_text_field($name)), 0, 40);
            $name = ($name != "" ) ? $name : whizzChat::user_data('name');
            $email = substr(strip_tags(sanitize_text_field($email)), 0, 40);
            $email = ($email != "" ) ? $email : whizzChat::user_data('email');
            $user_data = array('ip' => whizzChat::ip(), 'user_agent' => sanitize_text_field(whizzChat::user_agent()));
            $extra_data = array();
            $extra_data['more_data'] = false;
            $extra_data = apply_filters("whizzChat_start_extra_data", $extra_data);
            /* Insert User Data Against Chat */
            $current_time = current_time('mysql');
            $authr_id = get_post_field('post_author', $chat_box_id);
            $authr_id = apply_filters('whizz_chat_author_rel_id', $authr_id); // in admin only case 
            $wpdb->insert(
                    $whizz_tbl_sessions, array(
                'status' => 1,
                'url' => $url,
                'name' => $name,
                'email' => $email,
                'session' => $session,
                'chat_box_id' => $chat_box_id,
                'last_active_timestamp' => $current_time,
                'timestamp' => $current_time,
                'ip' => maybe_serialize($user_data),
                'other' => maybe_serialize($extra_data),
                'sender_id' => whizzChat::user_data('id'),
                'rel' => $authr_id,
                'message_type' => $message_type
                    ), array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
            );
            $_SESSION['whizz_session_id'] = $cookie_id;
            $chat_id = $wpdb->insert_id;
        }
        return $chat_id;
        $message = esc_html__("Chat Started", "whizz-chat");
        $data['chat_id'] = $chat_id;
        return array("success" => true, "data" => $data, "message" => $message);
    }

}


if (!function_exists('whizzChat_end_session_func')) {

    function whizzChat_end_session_func() {
        $_SESSION = array();
        session_write_close();
        session_start();
        session_regenerate_id();
    }

}


if (!function_exists('whizzChat_set_cookies_func')) {

    function whizzChat_set_cookies_func($chat_data = array()) {
        if (isset($chat_data) && $chat_data['chat_id'] != "") {
            $chat_id = $chat_data['chat_id'];
            $chat_post_id = $chat_data['chat_box_id'];
            $session = isset($chat_data['session']) ? $chat_data['session'] : '';
            $chat_name = isset($chat_data['chat_name']) ? $chat_data['chat_name'] : '';
            $chat_email = isset($chat_data['chat_email']) ? $chat_data['chat_email'] : '';
            $rest_token = get_option("whizz_api_secret_token");
            if (!$rest_token) {
                update_option("whizz_api_secret_token", "1234567890");
            }
            $rest_token = get_option("whizz_api_secret_token");
            $whizz_node_token = '';
            $cookie_values[$user_cookies] = array
                (
                "name" => $chat_name,
                "email" => $chat_email,
                "chat_id" => intval($chat_id),
                "session_id" => $session,
                "post_id" => $chat_post_id,
                "node_token" => $whizz_node_token
            );

            $c_array = whizzChat_cookies_value(intval($chat_id));
            if (count($c_array) > 0) {
                $cookies_array = whizzChat_cookies_value();
                $final_array = $cookies_array + $cookie_values;
                $cookie_values = serialize($final_array);
                $cookie_value = wp_json_encode($cookie_values, JSON_UNESCAPED_UNICODE);
                $is_set = setcookie("whizzChat-cookies", $cookie_value, time() + 31556926, COOKIEPATH, COOKIE_DOMAIN, 0, 0);
            } else {
                $cookie_values = serialize($cookie_values);
                $cookie_value = wp_json_encode($cookie_values, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                $is_set = setcookie("whizzChat-cookies", $cookie_value, time() + 31556926, COOKIEPATH, COOKIE_DOMAIN, 0, 0);
            }
            return $chat_id;
        }
    }

}

if (!function_exists('whizzChat_cookies_value')) {

    function whizzChat_cookies_value($cookie_id = '') {
        $cookies_data = $cookies = $cookies_array = array();
        if (isset($_COOKIE['whizzChat-cookies'])) {
            $cookies = $_COOKIE['whizzChat-cookies'];
            $is_serialized = whizzChat_is_serialized($cookies);
            if ($is_serialized) {
                $cookies_data = unserialize(json_decode(stripslashes($cookies)));
                return (isset($cookies_data[$cookie_id])) ? array() : $cookies_data;
            } else {
                return unserialize(json_decode(stripslashes($cookies), true));
            }
            return $cookies_data;
        }
        return $cookies_data;
    }

}


if (!function_exists('whizzChat_is_serialized')) {

    function whizzChat_is_serialized($data) {

        if (!is_string($data))
            return false;

        $data = trim($data);
        if ('N;' == $data)
            return true;

        if (!preg_match('/^([adObis]):/', $data, $badions))
            return false;

        switch ($badions[1]) {
            case 'a' :
            case 'O' :
            case 's' :
                if (preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data))
                    return true;

                break;
            case 'b' :
            case 'i' :
            case 'd' :
                if (preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data))
                    return true;

                break;
        }
        return false;
    }

}

if (!function_exists('whizzChat_session_values')) {

    function whizzChat_session_values($session_id = '', $session_values, $return_value = false) {
        if ($return_value == true) {
            if (isset($_SESSION['whizzChat_sessions'][$session_id])) {
                return $sesion_data[$session_id] = $_SESSION['whizzChat_sessions'][$session_id];
            }
        } else {
            $session_array = (isset($_SESSION['whizzChat_sessions'])) ? $_SESSION['whizzChat_sessions'] : array();
            if (!array_key_exists($session_id, $session_array)) {
                $_SESSION["whizzChat_sessions"]["$session_id"] = $session_values;
            }
            return $_SESSION["whizzChat_sessions"];
        }
    }

}
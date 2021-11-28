<?php
/*
 * Class for dashboard rest api 
 */
if (!class_exists('WhizzChat_Dashboard_Api')) {

    Class WhizzChat_Dashboard_Api {

        public function __construct() {
            add_action('rest_api_init', array($this, 'whizzchat_dashboard_apis_callback'));
            add_filter('whizzChat_load_chat_chatbox_dashboard', array($this, 'whizzChat_load_chat_chatbox_dashboard_html'), 10, 2);
        }

        public function whizzchat_dashboard_apis_callback() {

            register_rest_route('whizz-chat-api/v1', '/get-chat-box-dashboard', array(
                'methods' => 'GET, POST',
                'callback' => array($this, 'whizzChat_get_chatbox_dashboard'),
                'permission_callback' => function () {
                    return $this->whizzchat_dashboard_auth_callback();
                },
            ));

            register_rest_route('whizz-chat-api/v1', '/send-chat-message-dashboard', array(
                'methods' => 'GET, POST',
                'callback' => array($this, 'whizzChat_start_chat_dashboard'),
                'permission_callback' => function () {
                    return $this->whizzchat_dashboard_auth_callback();
                },
            ));

            register_rest_route('whizz-chat-api/v1', '/read-chat-dashboard', array(
                'methods' => 'GET, POST',
                'callback' => array($this, 'whizzChat_read_chat_dashboard'),
                'permission_callback' => function () {
                    return $this->whizzchat_dashboard_auth_callback();
                },
            ));

            register_rest_route('whizz-chat-api/v1', '/get-chat-list-dashboard', array(
                'methods' => 'GET, POST',
                'callback' => array($this, 'whizzChat_get_chat_list_dashboard'),
                'permission_callback' => function () {
                    return $this->whizzchat_dashboard_auth_callback();
                },
            ));

            register_rest_route('whizz-chat-api/v1', '/get-chat-list-box-dashboard', array(
                'methods' => 'GET, POST',
                'callback' => array($this, 'whizzChat_get_chat_list_box_dashboard'),
                'permission_callback' => function () {
                    return $this->whizzchat_dashboard_auth_callback();
                },
            ));

            register_rest_route('whizz-chat-api/v1', '/load-old-chat-dashboard', array(
                'methods' => 'GET, POST',
                'callback' => array($this, 'whizzChat_load_old_chat_dashboard'),
                'permission_callback' => function () {
                    return $this->whizzchat_dashboard_auth_callback();
                },
            ));
        }

        public function whizzchat_dashboard_auth_callback() {
            return true;
        }
        public function whizzChat_load_old_chat_dashboard(WP_REST_Request $request) {
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
            $text = apply_filters('whizz_filter_chat_box_content_dashboard', $chat_info[0]);
            return $text;
        }

        function whizzChat_get_chat_list_box_dashboard(WP_REST_Request $request) {
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
            $chatbox_comm_type = isset($whizzChat_options['whizzChat-comm-type']) && $whizzChat_options['whizzChat-comm-type'] != '' ? $whizzChat_options['whizzChat-comm-type'] : '0';
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

             $new_qr    =    "SELECT * FROM {$whizz_tbl_sessions} WHERE `session` = %s OR `rel` = %s ORDER BY last_active_timestamp DESC";   

            $prepared_statementR = $wpdb->prepare($new_qr ,$session,$session);





            $results_data = $wpdb->get_results($prepared_statementR);
            $results_array = array_merge();            
            if (isset($results_data) && count($results_data) > 0) {

                $usr_session_id = whizzChat::session_id();
                foreach ($results_data as $rdata) {
                    $get_id = $rdata->id;
                     

                     $query_var   =   "SELECT * FROM $whizz_tblname_chat_message WHERE `session_id` = %s";

                    $new_list = $wpdb->get_row($wpdb->prepare($query_var,$get_id));
                    if (isset($new_list->post_id) && $new_list->post_id != '') {

                        
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

                        $message_count_html = ($usr_session_id == $message_for) ? "<div id='chat-badge-" . $get_id . "' class='badge badge-rounded badge-primary ml-1'>" . $message_count . "</div>" : '';
                        $alert_msg_class = ($usr_session_id == $message_for && $message_count > 0) ? "chatlist-message-alert" : "";

                        $class_check = ( $author_id != $session ) ? ' new-chat-message-sender ' : ' new-chat-message-receiver ';

                        
                        $real_com_id = $usr_session_id; //
                        $real_com_id = "'" . $real_com_id . "'";
                        $admin_room = "'" . md5($_SERVER['HTTP_HOST']) . "_whizchat" . $get_id . "'";
                        $clicked = "'" . $get_id . "'";
                         //avatar-online , avatar-offline, avatar-busy, avatar-away                   
                    $online_status = whizzChat::user_online_status($rdata->session, $author_id);
                    $status_class = '';
                    if ($chatbox_comm_type == '1') {
                        $status_class = ( $online_status != "" ) ? ' avatar-online' : ' avatar-away';
                    }                      
                        
                        $list_html .= '<li id="' . $get_id . '" class="contacts-item friends ' . $alert_msg_class . '">
                                    <a class="contacts-link" href="javascript:void(0)" onClick="return whizzchat_open_dashboard_chat_person(' . $clicked . ',' . $new_list->post_id . ',this,' . $real_com_id . ',' . $admin_room . ')">
                                        <div class="avatar'.$status_class.'">
                                            ' . $image . '
                                        </div>
                                        <div class="contacts-content">
                                            <div class="contacts-info">
                                                <h6 class="chat-name text-truncate">' . $display_name . '</h6>
                                                <div class="chat-time">' . $last_active_time . '</div>
                                            </div>
                                            <div class="contacts-texts">
                                                <p class="text-truncate">' . whizzchat_words_count($chat_title, 30) . '</p>
                                                     ' . $message_count_html . '
                                            </div>
                                            
                                        </div>
                                    </a>
                                </li>';
                    }
                }
            }
            $return_data['list_ids'] = json_encode($ids_dataa);
            $return_data['list_html'] = json_encode($list_html);
            return $return_data;
        }

        function whizzChat_get_chat_list_dashboard(WP_REST_Request $request) {
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
                $chat_list_html = apply_filters('whizzChat_dashboard_load_chatlist', $chat_info);
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
                                "html" => apply_filters('whizz_filter_chat_box_content_dashboard', $chat, $blocked_status)
                            );
                        }
                    }
                }
            }
            $data['chat_list'] = $chat_list_html;
            $data['chat_boxes'] = json_encode($chat_boxes_html);
            return array("success" => true, "data" => $data, "message" => "");
        }

        function whizzChat_read_chat_dashboard(WP_REST_Request $request) {
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
            $status = (isset($is_show) &&  $is_show == 1 ) ? 1 : 0;
            do_action('whizzChat_new_message_and_count', $type_args, $status);
            return 'done';
        }

        function whizzChat_start_chat_dashboard(WP_REST_Request $request) {
            $json_data = $request->get_json_params();
            $get_parms = $request->get_params();
            $files_parms = $request->get_file_params();
            $nonce = (isset($get_parms['nonce'])) ? $get_parms['nonce'] : '';
            if (!wp_verify_nonce($nonce, 'wp_rest')) {
                $return = array('message' => esc_html__('Invalid security token sent....', 'whizz-chat'));
                wp_send_json_error($return);
            }
            if (isset($get_parms['action']) && 'whizzChat_send_chat_message_dashb' == $get_parms['action']) {
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
                $status = 0;
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
                        "html" => apply_filters('whizz_filter_chat_box_content_dashboard', $chat_lists[0], $blocked_status)
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

        public function whizzChat_load_chat_chatbox_dashboard_html($chat_lists = array(), $content_html = false) {
            global $whizzChat_options;
            $whizzChat_options = get_option('whizz-chat-options');

            $filter = $list_html = '';
            $session_id = whizzChat::session_id();
            $session_idrrr = whizzChat::session_id();

            if (count($chat_lists) > 0) {
                foreach ($chat_lists as $chat_list) {
                    $post_id = $chat_list["post_id"];
                    $chat_id = ($chat_list["id"]);
                    $author_id = get_post_field('post_author', $post_id);
                    $author_id = apply_filters('whizz_chat_author_rel_id', $author_id);
                    $filters = apply_filters('whizz_filter_chat_box_header_dashboard', $chat_list);
                    if ($chat_list['session_id'] != "") {
                        $blocked_status = whizzChat_is_user_blocked($chat_id, true);
                        $filters .= apply_filters('whizz_filter_chat_box_content_dashboard', $chat_list, $blocked_status);
                        $filters .= apply_filters('whizz_filter_chat_box_footer_dashboard', $chat_list, $blocked_status); // shataka
                    } else {
                        $filters .= apply_filters('whizz_filter_chat_box_content_offline', $chat_list);
                    }

                    $real_com_id = $chat_list['session_id'];
                    if ($session_id == $author_id) {
                        $real_com_id = $chat_list['session_id'];
                    } else {
                        $real_com_id = $author_id;
                    }


                     if($real_com_id   ==   ""){


                 // in case of user to user chat author id as post id
                 if ($session_id == $post_id) {
                        $real_com_id = $chat_list['session_id'];
                    } else {
                        $real_com_id = $post_id;
                    }
                        


                    }

                    $live_room_data = ' data-room="' . md5($_SERVER['HTTP_HOST']) . '_whizchat' . $chat_id . '" ';

                    $session_id = md5($chat_list['session_id']);
                    $filter = '<main class="whizz-main main-visible">
                                <div class="chats">
                           <div class="whizz-dash-chat-body" id="' . $chat_id . '" data-post-id="' . $post_id . '" ' . $live_room_data . ' data-author-id="' . $author_id . '" data-comm-id="' . $real_com_id . '" data-chat-id="' . $chat_id . '" data-unique-user="' . $session_id . '">' . $filters . ' ';

                    $filter .= '</div></div></main>';

                    return $filter;
//                   
                }
            }
            return $filter;
        }

        public function whizzChat_get_chatbox_dashboard(WP_REST_Request $request) {
            $json_data = $request->get_json_params();
            $get_parms = $request->get_params();
            $nonce = (isset($get_parms['nonce'])) ? $get_parms['nonce'] : '';
            if (!wp_verify_nonce($nonce, 'wp_rest')) {
                $return = array('success' => false, 'data' => '', 'message' => esc_html__('Invalid security token sent....', 'whizz-chat'));
                wp_send_json_error($return);
            }
            $session = (isset($get_parms['session'])) ? $get_parms['session'] : '';
            $chat_id = (isset($get_parms['chat_id'])) ? ($get_parms['chat_id']) : 0;

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
                            "first_message_id" => isset($chat->first_message_id) ? $chat->first_message_id : "",
                            "message_for" => $chat->message_for,
                            "message_count" => $chat->message_count
                        );
                    }
                }
            }
            $box = '';
            $box = apply_filters('whizzChat_load_chat_chatbox_dashboard', $value, false);

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

    new WhizzChat_Dashboard_Api();
}
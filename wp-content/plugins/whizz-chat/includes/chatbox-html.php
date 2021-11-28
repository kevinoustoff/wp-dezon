<?php
/*
 * HTML rendering Class
 * 
 */

class Whizz_Chat_box_Html {

    public function __construct() {

        add_action('wp_footer', array($this, 'whizz_chat_popup_box_html'), 9999);
        add_filter('whizz_filter_chat_box_header', array($this, 'whizzChat_individual_chatbox_header_html'), 10, 3);
        add_filter('whizz_filter_chat_box_header_admin', array($this, 'whizzChat_individual_chatbox_header_html_admin'), 10, 2);
        add_filter('whizz_filter_chat_box_content', array($this, 'whizzChat_individual_chatbox_html'), 10, 2);
        add_filter('whizz_filter_chat_box_content_admin', array($this, 'whizzChat_individual_chatbox_html_admin'), 10, 3);
        add_filter('whizz_filter_chat_box_content_after', array($this, 'whizzChat_individual_chatbox_after_html'), 11, 3);
        add_filter('whizz_filter_chat_box_content_after_admin', array($this, 'whizzChat_individual_chatbox_after_html_admin'), 11, 3);
        add_filter('whizzChat_list_chat_messages_text', array($this, 'whizzChat_list_chat_messages_text_html'), 10, 3);
        add_filter('whizzChat_list_chat_messages_image', array($this, 'whizzChat_list_chat_messages_image_html'), 10, 3);
        add_filter('whizzChat_list_chat_messages_file', array($this, 'whizzChat_list_chat_messages_file_html'), 10, 3);
        add_filter('whizzChat_list_chat_messages_voice', array($this, 'whizzChat_list_chat_messages_voice_html'), 10, 3);
        add_filter('whizzChat_list_chat_messages_list', array($this, 'whizzChat_list_chat_messages_map_html'), 10, 3);
        add_filter('whizzChat_list_chat_messages_seen', array($this, 'whizzChat_list_chat_messages_seen_html'), 10, 3);
        add_filter('whizzChat_chat_messages_div_attributes', array($this, 'whizzChat_chat_messages_div_attributes_html'), 10, 3);
        add_filter('whizz_filter_chat_box_footer', array($this, 'whizzChat_individual_chatbox_footer_html'), 10, 2);
        add_filter('whizz_filter_chat_box_footer_attachment', array($this, 'whizz_filter_chat_box_footer_attachment_html'), 10, 3);
        add_filter('whizz_filter_chat_box_footer_attachment', array($this, 'whizz_filter_chat_box_footer_image_attachment_html'), 13, 3);
        add_filter('whizz_filter_chat_box_content_offline', array($this, 'whizzChat_popup_box_offline_html'), 10, 1);
        add_filter('whizzChat_load_chat_list', array($this, 'whizzChat_load_chat_list_html'), 10, 1);
        add_filter('whizzChat_load_chat_list_admin', array($this, 'whizzChat_load_chat_list_admin_html'), 10, 1);
        add_filter('whizzChat_load_chat_chatbox', array($this, 'whizzChat_load_chat_chatbox_html'), 10, 2);
        add_filter('whizzChat_load_chat_chatbox_admin', array($this, 'whizzChat_load_chat_chatbox_html_admin'), 10, 2);
        add_filter('whizzChat_load_more_chat_list', array($this, 'whizzChat_load_more_chat_list_html'), 10, 3);
    }

    public function whizz_chat_popup_box_html() {

        global $whizzChat_options;

        if (is_page_template('template-whizzchat.php')) {
            return;
        }


        $whizzChat_options = get_option('whizz-chat-options');
        $whizzChat_between = $whizzChat_options['whizzChat-chat-between'];
        $whizzChat_admin_val = $whizzChat_options['whizzChat-admin-value'];
        $current_usr_id = get_current_user_id();
        $chat_model_flag = TRUE;

        if ($whizzChat_between == 1 && $whizzChat_admin_val != '' && $current_usr_id == $whizzChat_admin_val) { // for admin only
            return;
        }


        $whizzchat_between = $whizzChat_options["whizzChat-chat-between"];
        $whizzchat_admin_page = $whizzChat_options["whizzChat-admin-page"];
        
        $args = array();
        $args['direct_load'] = true;
        $args['chat_box_open'] = true;
        $args['chat_box_id'] = get_the_ID();

        $chat_box_html   =  "";
        $is_allowed_shortcode = isset($whizzChat_options['whizzChat-shortcode-allow']) ? $whizzChat_options['whizzChat-shortcode-allow'] : "0";

        $holder_hide_class = "no_chat";
        if ($is_allowed_shortcode != "1") {
            $holder_hide_class = "";
            $chat_boxes = whizzChat_chat_boxes($args);
            $chat_box_html = apply_filters('whizzChat_load_chat_chatbox', $chat_boxes);
        }          
        
          $cahtlist_allow   =   isset($whizzChat_options['whizzChat-chatlist'])   ? $whizzChat_options['whizzChat-chatlist']  :  "1";
        
          $chat_list_html    = "";
          if($cahtlist_allow  ==  "1" && $is_allowed_shortcode != "1"){
               $chat_lists = whizzChat_chat_list();
               $chat_list_html = apply_filters('whizzChat_load_chat_list', $chat_lists);
           }

        $list_render = '<div class="chatbox-inner-list">' . $chat_list_html . '</div>';
        if (isset($whizzchat_between) && $whizzchat_between == '1') { // in case of admin remove popover/chat list
            $list_render = '';
            $popover_html = '';
        }

        $whizzchat_bot_tooltip = isset($whizzChat_options["whizzChatbot-tooltip"]) && $whizzChat_options["whizzChatbot-tooltip"] != '' ? $whizzChat_options["whizzChatbot-tooltip"] : esc_html__("Got Confused? let's chat with admin.", 'whizz-chat');

        $image_id = '';
        if ($whizzChat_between == 1 && $whizzChat_admin_val != '' && $current_usr_id != $whizzChat_admin_val) { // for admin only ooposite
            $html = '<div class="whizzChat_head">
                                  <div class="whizzChat_head-section">    
                                    <span class="whizzChat_head-tooltip">' . $whizzchat_bot_tooltip . '</span>    
                                    <span class="whizzChat_head-img"><a href="javascript:void(0)" class="whizz-admin-bot"><img src="' . plugins_url('whizz-chat') . '/assets/images/bot.svg" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" /></a></span>
                                  </div>
                                    </div>
                                 <div class="chatbox-holder">
                                     <div class="chatbox-inner-holder"></div>
                             </div>';
        } else {
            $html = '<div class="chatbox-holder '. esc_attr($holder_hide_class).'">
                        <div class="chatbox-inner-holder">' . $chat_box_html . '</div>
                        ' . $list_render . '
                </div>';
        }

echo whizzChat_return($html);



    }

    public function whizzChat_list_chat_messages_seen_html($html = '', $user_data = array(), $msg = array()) {

        $first_message_id = '';
        if (isset($user_data['first_message_id']) && $user_data['first_message_id'] != "") {
            global $whizz_tblname_chat_message;
            global $wpdb;
            $first_message_id = $user_data['first_message_id'];
            $current_user = whizzChat::session_id();
            $query = "SELECT seen_at FROM $whizz_tblname_chat_message WHERE ( id = '" . $first_message_id . "' AND rel = '" . $current_user . "') LIMIT 1";
            $chats = $wpdb->get_results($query, true);
            if ($chats) {
                $last_seen = ( isset($chats[0]->seen_at)) ? $chats[0]->seen_at : '';
                if ($last_seen != "") {
                    return $html = whizzChat::whizzchat_time_ago($last_seen);
                }
            }
        }
        $seen_at_time = (isset($msg['seen_at']) && $msg['seen_at'] != "") ? $msg['seen_at'] : '';
        $html = '';
        if ($seen_at_time != "" && $msg['is_reply'] == 'message-sender-box') {
            return $html = whizzChat::whizzchat_time_ago($seen_at_time);
        }
    }

    public function whizzChat_individual_user_blocked_html($html = '', $user_data = array(), $box_content = array()) {
        $html .= "<p class='whizzChat-block-user-p blocked-chat-p'>" . esc_html__('Chat Ended', 'whizz-chat') . "</p>";

        return $html;
    }

    public function whizzChat_individual_chatbox_after_html($html = '', $user_data = array(), $box_content = array()) {


        if (isset($box_content['is_blocked']) && $box_content['is_blocked'] == true && isset($user_data['first_message_id']) && $user_data['first_message_id'] == "") {

            $blocked_class = 'whizzChat-block-user';
            if (is_page_template('template-whizzchat.php')) {
                $blocked_class = 'whizzChat-block-user-dashb';
            }


            $message = '';
            if ($box_content['current_session'] == $box_content['blocked_id']) {
                $message = esc_html__("User has blocked you. You can not send the message until user unblock you.", "whizz-chat");
            }
            if ($box_content['current_session'] == $box_content['blocker_id']) {
                $message = esc_html__("You have blocked this user. To send message you need to unblock this user.", "whizz-chat") . ' ' . '<a href="javascript:void(0);" class="' . $blocked_class . ' blocked-chat">' . esc_html__("Unblock", "whizz-chat") . '</a>';
            }
            $unblock = "";
            $html .= "<p class='whizzChat-block-user-p blocked-chat-p'>" . $message . "</p>";
        } else {
            
        }
        return $html;
    }

    public function whizzChat_individual_chatbox_after_html_admin($html = '', $user_data = array(), $box_content = array()) {


        if (isset($box_content['is_blocked']) && $box_content['is_blocked'] == true) {
            $message = '';
            if ($box_content['current_session'] == $box_content['blocked_id']) {
                $message = esc_html__("User has blocked you. You can not send the message until user unblock you.", "whizz-chat");
            }
            if ($box_content['current_session'] == $box_content['blocker_id']) {
                $message = esc_html__("You have blocked this user. To send message you need to unblock this user.", "whizz-chat") . ' ' . '<a href="javascript:void(0);" class="whizzChat-block-user blocked-chat">' . esc_html__("Unblock", "whizz-chat") . '</a>';
            }
            $unblock = "";
            $html .= "<p class='whizzChat-block-user-p blocked-chat-p'>" . $message . "</p>";
        }
        return $html;
    }

    public function whizzChat_load_more_chat_list_html($html = '', $chat_boxes = array()) {
        $list_html = '';

        if (isset($chat_boxes) && count($chat_boxes) > 0) {
            $exclude_ids = array();
            foreach ($chat_boxes as $key => $value) {
                if ($value['id'] > 0 && $value['chat_status'] == 1) {
                    $exclude_ids[] = ($value['id']);
                }
            }

            $args = array();
            $args['exclude'] = $exclude_ids;
            $chat_lists = whizzChat_chat_list($args);

            foreach ($chat_boxes as $key => $list) {
                $chat_id = $list['id'];
                $chat_title = get_the_title($list['post_id']);
                $author_id = get_post_field('post_author', $list['post_id']);
                $display_name = get_the_author_meta('display_name', $author_id);

                $size = array(150, 150);

                $url = get_the_post_thumbnail_url($list["post_id"], $size);
                $image = '';
                $image_id = '';

                if ($url) {
                    $image = '<img src="' . esc_url($url) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '"/>';
                } else {
                    $url = plugin_dir_url('/') . 'whizz-chat/assets/images/no-list-img.jpg';
                    $image = '<img src="' . esc_url($url) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '"/>';
                }

                $last_active_time = whizzChat::whizzchat_time_ago($list["last_active_time"]);

                $list_html .= '<li data-more-id="' . $chat_id . '">
                                <a onclick="return open_whizz_chat(' . $chat_id . ')" href="javascript:void(0);">' . $image . '</a>
                                <div class="content">
                                <h3 class="whizz-chat-text-nowrap"> 
                                      <a onclick="return open_whizz_chat(' . $chat_id . ')" href="javascript:void(0);">' . whizzchat_words_count($chat_title, 30) . '</a>
                                  </h3>
                                   <span class="preview">' . $display_name . '</span>
                                    <span class="meta"> ' . $last_active_time . '</span>   
                                  </div>
                              </li>';
            }
        }

        $html = '';
        if (isset($list_html) && $list_html != '') {
            $html = '<div class="whizzChat-pophover">
					<div class="popover__wrapper">
						<a class="whizzChat-more-boxes" href="javascript:void(0);"><i class="fa fa-plus"></i></a>
					  <div class="popover__content pophover-min">
						<div class="chat-messages">
							<div class="whizz-chat-list whizzChat-more-chat-list">
								<div class="chat-body">
									<ul>' . $list_html . '</ul>
								</div>
							</div>
						 </div>
					  </div>
				  </div>
				</div>';
        }



        return $html;
    }

    public function whizzChat_load_chat_chatbox_html_admin($chat_lists = array(), $content_html = false) {
        $filter = $list_html = '';
        $session_id = whizzChat::session_id();
        /* Chats Boxes Html */

        if (count($chat_lists) > 0) {
            foreach ($chat_lists as $chat_list) {
                $post_id = $chat_list["post_id"];
                $chat_id = $chat_list["id"];


                if ($chat_list['session_id'] != "") {
                    $blocked_status = whizzChat_is_user_blocked($chat_id, true);
                    $filters .= apply_filters('whizz_filter_chat_box_content_admin', $chat_list, $blocked_status);
                } else {
                    $filters .= apply_filters('whizz_filter_chat_box_content_offline', $chat_list);
                }
                $chat_idd = $chat_id;
                if ($chat_id == 0) {
                    $chat_idd = 'whizz-chat-temp-' . $post_id;
                }

                $chat_switch = array();
                if ($content_html == true) {
                    $filter .= $filters;
                } else {
                    $session_id = md5($chat_list['session_id']);
                    $chat_attr = '';
                    $box_html = '<div class="chatbox group-chat chat-messages" ' . $chat_attr . '>' . $filters . '  </div>';
                    $filter .= '<div id="' . $chat_idd . '" data-post-id="' . $post_id . '" data-chat-id="' . $chat_id . '" class="individual-chat-box" data-unique-user="' . $session_id . '">' . $box_html . '</div>';
                    $filter .= '<input id="get-chat-switch-' . $chat_idd . '" value="on" type="hidden">';
                    $chat_switch[] = $chat_idd;
                }
                update_option('get-chat-switch', $chat_switch);
            }
        }
        return $filter;
    }

    public function whizzChat_load_chat_chatbox_html($chat_lists = array(), $content_html = false) {
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
                
                if( isset($chat_list["author_id"])  && $chat_list["author_id"] != "" ){


                    $author_id    =   $chat_list["author_id"];
                }


                $filters = apply_filters('whizz_filter_chat_box_header', $chat_list);
                if ($chat_list['session_id'] != "") {
                    $blocked_status = whizzChat_is_user_blocked($chat_id, true);
                    $filters .= apply_filters('whizz_filter_chat_box_content', $chat_list, $blocked_status);
                    $filters .= apply_filters('whizz_filter_chat_box_footer', $chat_list, $blocked_status); // shataka
                } else {
                    $filters .= apply_filters('whizz_filter_chat_box_content_offline', $chat_list);
                }

                $chat_idd = $chat_id;
                $temp_class = '';
                if ($chat_id == 0) {
                    $chat_idd = 'whizz-chat-temp-' . $post_id;
                    $temp_class = ' whizzchat-temp-section';
                }
                $chat_switch = array();
                if ($content_html == true) {
                    $filter .= $filters;
                } else {


                    


                    $real_com_id = $chat_list['session_id'];
                    if ($session_id == $author_id) {
                        $real_com_id = $chat_list['session_id'];
                    } else {
                        $real_com_id = $author_id;
                    }
                        
                

                    $live_room_data = ' data-room="' . md5($_SERVER['HTTP_HOST']) . '_whizchat' . $chat_id . '" ';

                    $chat_box_hide = 'whizzChat_hide_my_box_' . $chat_id;
                    $hide_box_class = isset($_COOKIE[$chat_box_hide]) && $_COOKIE[$chat_box_hide] == 'hide' ? ' chatbox-min' : '';
                    $session_id = md5($chat_list['session_id']);
                    $chat_attr = '';
                    $box_html = '<div  class="chatbox group-chat' . $hide_box_class . '" ' . $chat_attr . '>' . $filters . '  </div>';
                    $filter .= '<div id="' . $chat_idd . '" data-post-id="' . $post_id . '" ' . $live_room_data . ' data-author-id="' . $author_id . '" data-comm-id="' . $real_com_id . '" data-chat-id="' . $chat_id . '" class="individual-chat-box' . $temp_class . '" data-unique-user="' . $session_id . '">' . $box_html . '</div>';
                    $filter .= '<input id="get-chat-switch-' . $chat_idd . '" value="on" type="hidden">';
                    $chat_switch[] = $chat_idd;
                }
            }
        }
        return $filter;
    }

    public function whizzchat_time_ago($timestamp) {

        $time_ago = strtotime($timestamp);

        return $timestamp;
        
        $current_time = time();
        $time_difference = $current_time - $time_ago;




        $seconds = $time_difference;
        $minutes = round($seconds / 60); // value 60 is seconds  
        $hours = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec  
        $days = round($seconds / 86400); //86400 = 24 * 60 * 60;  
        $weeks = round($seconds / 604800); // 7*24*60*60;  
        $months = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60  
        $years = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60
        if ($seconds <= 60) {
            return esc_html__("Just Now", "whizz-chat");
        } else if ($minutes <= 60) {
            if ($minutes == 1) {
                return esc_html__("1 minute ago", "whizz-chat");
            } else {
                return "$minutes " . esc_html__("minutes ago", "whizz-chat");
            }
        } else if ($hours <= 24) {
            if ($hours == 1) {
                return esc_html__("an hour ago", "whizz-chat");
            } else {
                return "$hours " . esc_html__("hrs ago", "whizz-chat");
            }
        } else if ($days <= 7) {
            if ($days == 1) {
                return esc_html__("Yesterday", "whizz-chat");
            } else {
                return "$days " . esc_html__("days ago", "whizz-chat");
            }
        } else if ($weeks <= 4.3) {
            if ($weeks == 1) {
                return esc_html__("a week ago", "whizz-chat");
            } else {
                return "$weeks " . esc_html__("weeks ago", "whizz-chat");
            }
        } else if ($months <= 12) {
            if ($months == 1) {
                return esc_html__("a month ago", "whizz-chat");
            } else {
                return "$months " . esc_html__("months ago", "whizz-chat");
            }
        } else {
            if ($years == 1) {
                return esc_html__("one year ago", "whizz-chat");
            } else {
                return "$years " . esc_html__("years ago", "whizz-chat");
                ;
            }
        }
    }

    public function whizzChat_load_chat_list_admin_html($chat_lists = array()) {
        global $whizz_tblname_chat_message, $wpdb;
        /* Chats Lists Html */
        $list_html = '';
        $chat_id = '';
        foreach ($chat_lists as $chat_list) {
            if (isset($chat_list) && $chat_list['id'] > 0) {

                $query = "SELECT id FROM $whizz_tblname_chat_message WHERE `session_id` = '" . $chat_list['id'] . "' AND `post_id` = '" . $chat_list['post_id'] . "' AND `rel` = '" . $chat_list['session_id'] . "' AND `seen_at` IS NULL ORDER BY `id` DESC";
                $messages = $wpdb->get_results($query);
                $chat_id = ($chat_list["id"]);
                $clicked = "'" . $chat_id . "'";
                $url = get_the_post_thumbnail_url($chat_list["post_id"]);
                $image_id = '';
                if ($url) {
                    $image = '<img src="' . esc_url($url) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" width="50" class="rounded-circle">';
                } else {
                    $url = plugin_dir_url('/') . 'whizz-chat/assets/images/admin_list_user.svg';

                    $image = '<img src="' . esc_url($url) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" width="50" class="rounded-circle">';
                }
                $real_com_id = $chat_list['session_id']; //
                $real_com_id = "'" . $real_com_id . "'";
                $active_time = ($chat_list["last_active_time"]);
                $title = get_the_title($chat_list["post_id"]);
                $last_active_time = whizzChat::whizzchat_time_ago($active_time);


                $msg_count_html = isset($chat_list["message_count"]) && $chat_list["message_count"] != 0 ? '<span class="badge badge-light">' . $chat_list["message_count"] . '</span>' : '';

                $admin_room = "'" . md5($_SERVER['HTTP_HOST']) . "_whizchat" . $chat_id . "'";


                $author_id = apply_filters('whizz_chat_author_rel_id', $chat_list["post_author_id"]);
                $list_html .= '<a id="' . $chat_id . '" href="javascript:void(0);" data-author-id="' . $author_id . '" onClick="return open_whizz_chat_admin(' . $clicked . ',' . $chat_list["post_id"] . ',this,' . $real_com_id . ',' . $admin_room . ')" class="list-group-item list-group-item-action list-group-item-light rounded-0">
                                        <div class="media">
                                          ' . $image . '
                                            <div class="media-body ml-4">
                                                <div class="d-flex align-items-center justify-content-between mb-1">
                                                    <h6 class="mb-0">' . $chat_list["name"] . '</h6><small class="small">' . $last_active_time . '</small>
                                                </div>
                                                <p class="font-italic text-muted mb-0 text-small" title="' . $title . '">' . whizzchat_words_count($title, 50) . '</p>
						' . $msg_count_html . '	
                                            </div>
                                        </div>
                                    </a>';
            }
        }
        $list_html_final = '';
        $user_name = whizzChat::user_data('name');

        $list_html_final .= '<div class="whizzchat-sidebar bg-white" data-chat-id="' . $chat_id . '" data-user-name="' . $user_name . '">
                            <div class="whizzchat-sidebar-heading bg-light">
                            
                                <p class="h5 mb-0 py-1">  Chat List </p>
                            </div>
                            <div class="messages-box">
                                <div class="list-group rounded-0">
                                    ' . $list_html . '
                                </div>
                            </div>
                        </div>';
        return $list_html_final;
    }

    public function whizzChat_load_chat_list_html($chat_lists = array()) {
        /* Chats Lists Html */
        global $whizzChat_options;
        $whizzChat_options = get_option('whizz-chat-options');

        $both_chat_enable = isset($whizzChat_options['whizzChat-bot']) && $whizzChat_options['whizzChat-bot'] ? TRUE : False;
        $chat_between = isset($whizzChat_options['whizzChat-chat-between']) && $whizzChat_options['whizzChat-chat-between'] != '' ? $whizzChat_options['whizzChat-chat-between'] : '0';
        $whizzchat_bot_tooltip = isset($whizzChat_options["whizzChatbot-tooltip"]) && $whizzChat_options["whizzChatbot-tooltip"] != '' ? $whizzChat_options["whizzChatbot-tooltip"] : esc_html__("Got Confused? let's chat with admin.", 'whizz-chat');
        $image_id = '';
        $both_chat_symbol = '';
        if ($both_chat_enable && ($chat_between == '1' || $chat_between == '2') && (!in_array('administrator', wp_get_current_user()->roles) )) {

            $both_chat_symbol = '<div class="whizzchatbot-section">    
                                    <span class="whizzchatbot-tooltip">' . $whizzchat_bot_tooltip . '</span>    
                                    <span class="whizzchatlist-bot-img"><a href="javascript:void(0)" class="whizz-admin-bot"><img src="' . plugins_url('whizz-chat') . '/assets/images/bot.svg" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" /></a></span>
                                  </div>';
        }

        $chatlist_head_color = isset($whizzChat_options['whizzChat-chatlist-head-color']) && $whizzChat_options['whizzChat-chatlist-head-color'] != '' ? $whizzChat_options['whizzChat-chatlist-head-color'] : '#FFFFFF';
        $color_style = ' style="background-color:' . $chatlist_head_color . '"';
        $chatlist_head_txtcolor = isset($whizzChat_options['chatlist-head-txt-color']) && $whizzChat_options['chatlist-head-txt-color'] != '' ? $whizzChat_options['chatlist-head-txt-color'] : '#FFFFFF';
        $txtcolor_style = ' style="color:' . $chatlist_head_txtcolor . ' !important"';
        $user_id_ses = whizzChat::get_session_id();
        $list_html = '';
        $session_id = whizzChat::session_id();

        foreach ($chat_lists as $chat_list) {
            if (isset($chat_list) && $chat_list['id'] > 0) {
                $chat_id = ($chat_list["id"]);

                $message_count = $chat_list['message_count'];
                $message_for = $chat_list['message_for'];
                $message_count_html = ($session_id == $message_for) ? "<span id='chat-badge-" . $chat_id . "' class='chat-badge-count'>" . $message_count . "</span>" : '';
                $alert_msg_class = ($session_id == $message_for && $message_count > 0) ? "chatlist-message-alert" : "";

                $clicked = "'" . $chat_id . "'";
                $liste = "'list'";

                $size = array(150, 150);

                $url = get_the_post_thumbnail_url($chat_list["post_id"], $size);
                if ($url) {
                    $image = '<a class="thumbnail" href="javascript:void(0);" onClick="return open_whizz_chat(' . $clicked . ',' . $liste . ')"><img src="' . esc_url($url) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" /></a>';
                } else {
                    $url = plugin_dir_url('/') . 'whizz-chat/assets/images/no-list-img.jpg';
                    $image = '<a class="thumbnail" href="javascript:void(0);" onClick="return open_whizz_chat(' . $clicked . ',' . $liste . ')"><img src="' . esc_url($url) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" /></a>';
                }
                $title = '<h3 class="whizz-chat-text-nowrap">
                            <a onClick="return open_whizz_chat(' . $clicked . ',' . $liste . ')" href="javascript:void(0);">' . whizzchat_words_count(get_the_title($chat_list["post_id"]), 30) . '</a></h3>';
                $author_id = get_post_field('post_author', $chat_list['post_id']);



                if ($user_id_ses == $chat_list['session_id']) {
                    $display_name = get_the_author_meta('display_name', $author_id);
                } else {
                    $display_name = $chat_list['name'];
                }
                 
                if($user_id_ses !=   $chat_list['post_author_id'] ){

                        $display_name = get_the_author_meta('display_name', $chat_list['post_author_id']);
                    }
                

                $last_active_time = whizzChat::whizzchat_time_ago($chat_list["last_active_time"]);
                $list_html .= "<li id='" . $chat_id . "' class='" . $alert_msg_class . "'>" . $image . "
                                 <div class='content'>" . $title . "
                                <span class='preview'>" . $display_name . "</span>
                                <span class='meta'> " . $last_active_time . "</span>
                               $message_count_html
                            </div>
                          </li>";
            }
        }


        $no_chat_message = "<p class='nochat text-center'><img src='" . plugins_url('whizz-chat') . "/assets/images/nochat.png'><span>" . esc_html__('No Chat Available', 'whizz-chat') . "</span></p>";
        $load_more = "<footer><a href='#'>" . esc_html__('Load more messages', 'whizz-chat') . "</a></footer>";
        $load_more = ($list_html != "" ) ? "" : $no_chat_message;
        $html_body = "<div class='whizz-chat-list'>
                          <div class='chat-body'>
                            <div class='whizz-search'> <input placeholder='" . esc_html__('Search chat', 'whizz-chat') . "' type='text' class='form-control chat-search'><span class='whizz-search-loader'></span></div>
                                <ul> " . $list_html . " </ul>
                          </div>
                            " . $load_more . " </div>";

        $user_name = whizzChat::user_data('name');


        if (isset($_COOKIE['whizz_sound_enable']) && $_COOKIE['whizz_sound_enable'] == 'on') {
            $whizz_sound_text = esc_html__('Sound off', 'whizz-chat');
            $whizz_sound_val = 'off';
        } else { // in case of value off
            $whizz_sound_text = esc_html__('Sound on', 'whizz-chat');
            $whizz_sound_val = 'on';
        }
        $hide_list_class = isset($_COOKIE['whizzChat_list_close']) && $_COOKIE['whizzChat_list_close'] == 'hide' ? ' chatlist-min' : '';

        $dashboard_page = isset($whizzChat_options['whizzChat-dashboard-page']) && $whizzChat_options['whizzChat-dashboard-page'] != '' ? $whizzChat_options['whizzChat-dashboard-page'] : 'javascript:void(0)';

        $dashboard_style = 'href="javascript:void(0)" ';
        if ($dashboard_page != '') {
            $dashboard_style = 'href="' . get_permalink($dashboard_page) . '" target="__blank" ';
        }

        $list_html = '<div class="chatbox group-chat chatbox-list' . $hide_list_class . '" id="whizz-list-' . $user_id_ses . '">
            <div class="chatbox-top"' . $color_style . '>
              <div class="chat-group-name"> 
                    <div class="whizzChat-author-meta whizz-chat-text-nowrap">
                        <span class="whizzChat-ad-title"' . $txtcolor_style . '>' . esc_html__("Chat List", "whizz-chat") . '</span>
                         ' . $both_chat_symbol . '
                    </div>
              </div>
                 <div class="chatbox-icons">
                 <label for="chkSettings-list"><i class="fa fa-gear"></i></label>
                      <input type="checkbox" id="chkSettings-list" class="chkSettings" />
                    <div class="settings-popup">
                      <ul>
                        <li><a href="javascript:void(0)" class="whizzChat-sound-switch" data-sound-val="' . $whizz_sound_val . '" data-replace-text="' . $whizz_sound_text . '">' . $whizz_sound_text . '</a></li>
                      </ul>
                    </div>
                    <label for="whizzchat-dashboard-list"><a title="' . esc_html__('whizzchat dashboard page', 'whizz-chat') . '" ' . $dashboard_style . '><i class="fas fa-tachometer-alt"></i></a></label>
                   <a href="javascript:void(0)" class="whizz-chat-list-close"><i class="fa fa-angle-down"></i></a>    
                 </div>
              </div>
            <div class="chat-messages">' . $html_body . '</div>
            </div>';
        return $list_html;
    }

    public function whizzChat_individual_chatbox_header_html($user_data) {


        global $whizzChat_options;
        $whizzChat_options = get_option('whizz-chat-options');
        $chatbox_head_color = isset($whizzChat_options['whizzChat-chatbox-head-color']) && $whizzChat_options['whizzChat-chatbox-head-color'] != '' ? $whizzChat_options['whizzChat-chatbox-head-color'] : '#FFFFFF';
        $chatbox_head_txtcolor = isset($whizzChat_options['chatbox-primary-color']) && $whizzChat_options['chatbox-primary-color'] != '' ? $whizzChat_options['chatbox-primary-color'] : '#FFFFFF';
        $chatbox_head_scd_txtcolor = isset($whizzChat_options['chatbox-second-color']) && $whizzChat_options['chatbox-second-color'] != '' ? $whizzChat_options['chatbox-second-color'] : '#FFFFFF';
        $chatbox_comm_type = isset($whizzChat_options['whizzChat-comm-type']) && $whizzChat_options['whizzChat-comm-type'] != '' ? $whizzChat_options['whizzChat-comm-type'] : '0';
        $whizzchat_between = isset($whizzChat_options['whizzChat-chat-between']) && $whizzChat_options['whizzChat-chat-between'] != '' ? $whizzChat_options['whizzChat-chat-between'] : '0';

        $boxcolor_style = ' style="background-color:' . $chatbox_head_color . '" ';
        $boxtxtcolor_style = ' style="color:' . $chatbox_head_txtcolor . ' !important" ';
        $boxtxt2ndcolor_style = ' style="color:' . $chatbox_head_scd_txtcolor . ' !important" ';


        $random = rand(0, 9999);
        $current_user = whizzChat::session_id();

          $post_author_id    =    $user_data["post_author_id"];


          if(isset($user_data['author_id']) && $user_data['author_id'] != "" ){


            $post_author_id    =    $user_data["author_id"];
          }

        if ($post_author_id != $current_user) {
            $display_name = get_the_author_meta('display_name', $post_author_id);
        } else {
            $display_name = $user_data['name'];
        }
        $is_blocked = esc_html__("Block", "whizz-chat");
        $is_blocked1 = esc_html__("Unblock", "whizz-chat");
        $blocked = whizzChat_is_user_blocked($user_data["id"]);
        if (isset($blocked['is_blocked']) && $blocked['is_blocked'] == true) {
            $is_blocked = esc_html__("Unblock", "whizz-chat");
            $is_blocked1 = esc_html__("Block", "whizz-chat");
        }
        $session_user_id = $user_data["session_id"];
    
        $online_status = whizzChat::user_online_status($session_user_id, $post_author_id);
        //away
        $online_status_attr = ( $online_status != "" ) ? 'online' : 'donot-disturb';
        $status_html = '';
        if ($chatbox_comm_type == '1') {
            $status_html = '<span class="whizzchat-status ' . $online_status_attr . '"></span>';
        }

        $dashboard_page = isset($whizzChat_options['whizzChat-dashboard-page']) && $whizzChat_options['whizzChat-dashboard-page'] != '' ? $whizzChat_options['whizzChat-dashboard-page'] : 'javascript:void(0)';

        $dashboard_style = 'href="javascript:void(0)" ';
        if ($dashboard_page != '') {
            $dashboard_style = 'href="' . get_permalink($dashboard_page) . '" target="__blank" ';
        }

        $chat_dashboard_html = $dashboard_link = '';
        if ($whizzchat_between == '1') {
            $chat_dashboard_html = ' <label for="whizzchat-dashboard-list"><a title="' . esc_html__('whizzchat dashboard page', 'whizz-chat') . '" ' . $dashboard_style . '><i ' . $boxtxt2ndcolor_style . 'class="fas fa-tachometer-alt"></i></a></label>';
        
            
        }

      $dashboard_link = ' <li><a title="' . esc_html__('whizzchat dashboard page', 'whizz-chat') . '" ' . $dashboard_style . '>'. esc_html__('Visit Dashboard').'</a></li>';
        /* chatbox-unread-message */

          $post_title  =    get_the_title($user_data["post_id"]);


             if($user_data["post_id"]   ==  $post_author_id ) {


              $post_title    =   "";
             }


        $html = '
        <div class="chatbox-top"' . $boxcolor_style . '>
            <div class="chat-group-name"> 
                <div class="whizzChat-author-meta whizz-chat-text-nowrap">
                    <span class="whizzChat-ad-title" title="' . $post_title . '"><a ' . $boxtxtcolor_style . ' href="' . get_the_permalink($user_data["post_id"]) . '">' . whizzchat_words_count($post_title , 25) . '</a></span>
                        <span class="whizzChat-author-name" ' . $boxtxt2ndcolor_style . '>' . $status_html . $display_name . '</span>
                </div>
            </div>
          <div class="chatbox-icons">
            ' . $chat_dashboard_html . '
            <label for="chkSettings-' . $random . '"><i ' . $boxtxt2ndcolor_style . 'class="fa fa-gear"></i></label>
            <input type="checkbox" id="chkSettings-' . $random . '" class="chkSettings" />
            <div class="settings-popup">
              <ul>
                <li><a href="javascript:void(0)" class="whizzChat-block-user" data-replace-text="' . $is_blocked1 . '">' . $is_blocked . '</a></li>
                <li><a data-leave-chat-id="' . $user_data["id"] . '" href="javascript:void(0)" class="logout-chat-session">' . esc_html__('Leave Chat', 'whizz-chat') . '</a></li>
                  '.$dashboard_link.'
                
              </ul>
            </div>
            <a href="javascript:void(0);" class="whizzchat-minimize" id="minimize-' . $user_data["id"] . '"><i ' . $boxtxt2ndcolor_style . 'class="fa fa-minus"></i></a> 
               <a onclick="return open_whizz_chat(' . $user_data["id"] . ')" href="javascript:void(0);"><i ' . $boxtxt2ndcolor_style . 'class="fa fa-close"></i></a> 
             </div>
        </div>';
        return $html;
    }

    public function whizzChat_individual_chatbox_header_html_admin($chat_id, $post_id) {
        $random = rand(0, 9999);
        $current_user = whizzChat::session_id();
        $is_blocked = esc_html__("Block", "whizz-chat");
        $is_blocked1 = esc_html__("Unblock", "whizz-chat");
        $blocked = whizzChat_is_user_blocked($chat_id);
        if (isset($blocked['is_blocked']) && $blocked['is_blocked'] == true) {
            $is_blocked = esc_html__("Unblock", "whizz-chat");
            $is_blocked1 = esc_html__("Block", "whizz-chat");
        }
        return '<li><a href="javascript:void(0)" data-chat-id="' . $chat_id . '" data-post-id="' . $post_id . '" class="whizzChat-block-user-admin" data-replace-text="' . $is_blocked1 . '">' . $is_blocked . '</a></li>
         <li><a data-leave-chat-id="' . $chat_id . '" href="javascript:void(0)" class="logout-chat-session-admin">' . esc_html__('Leave Chat', 'whizz-chat') . '</a></li>';
    }

    public function whizzChat_individual_chatbox_html_admin($user_data, $box_content = array()) {

        $chat_html = $seen_at = '';

        $chat_html_load = '<span class="whizzChat-span-loading whizzChat-loading"></span>';
        $chat_html_load = '';
        $image_id = '';
        $last_message = array();
        if ($user_data['id'] > 0) {
            $current_user = get_current_user_id();
            $messages = apply_filters('whizzChat_list_chat_messages', $user_data);

            $last_message = end($messages);
            $date_array = array();
            $seen_flag = TRUE;
            foreach ($messages as $msg) {
                $time_html = '';

                $chat_time = date("Y-m-d", strtotime($msg['chat_time']));
                if (!in_array($chat_time, $date_array)) {
                    $date_array[] = $chat_time;
                    if (isset($user_data['send_message']) && $user_data['send_message'] != "") {
                        
                    } else if (isset($user_data['first_message_id']) && $user_data['first_message_id'] != "") {
                        
                    } else {
                        $time_html = '<p class="small text-muted">' . whizzChat::whizzchat_time_ago($msg['chat_time']) . '</p>';
                    }
                }
                $time_html = '<p class="small text-muted">' . whizzChat::whizzchat_time_ago($msg['chat_time']) . '</p>';
                $url = get_the_post_thumbnail_url($msg["chat_post_id"]);
                $image_post = '';
                if ($url) {
                    $image_post = '<img src="' . esc_url($url) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" width="50" class="rounded-circle">';
                } else {
                    $url = plugin_dir_url('/') . 'whizz-chat/assets/images/admin_list_user.svg';
                    $image_post = '<img src="' . esc_url($url) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" width="50" class="rounded-circle">';
                }
                if ($msg['message_type'] == 'text') {
                    $is_reply = $msg['is_reply'];
                    $chatId = $msg['chat_message_id'];
                    $inner = '';
                    if ($msg['chat_message'] != "") {
                        $chat_message = $msg['chat_message'];
                        $man_div_css = ($is_reply != "" ) ? "bg-light rounded py-2 px-3 mb-2" : 'text-small mb-0 text-white';
                        $div_attributes = apply_filters("whizzChat_chat_messages_div_attributes", '', $user_data, $msg);
                        if ($is_reply == "message-partner") {
                            $chat_html .= '<div  class="message-box-holder media w-50 mb-3" ' . $div_attributes . '>
                                             ' . $image_post . '
                                                <div class="media-body ml-3">
                                                    <div class="bg-light rounded py-2 px-3 mb-2">
                                                        <p class="text-small mb-0 text-muted">' . nl2br($chat_message) . '</p>
                                                    </div>
                                                    ' . $time_html . '
                                                </div>
                                            </div>';
                        } else {
                            $chat_html .= '<div class="message-box-holder media w-50 ml-auto mb-3" ' . $div_attributes . '>
                                            <div class="media-body">
                                                <div class="bg-primary rounded py-2 px-3 mb-2">
                                                    <p class="text-small mb-0">' . nl2br($chat_message) . '</p>
                                                </div>
                                               ' . $time_html . '
                                            </div>
                                        </div>';
                        }
                    }
                } else if ($msg['message_type'] == 'image') {

                    $image_id = '';
                    $is_reply = $msg['is_reply'];
                    $message_id = $msg["chat_message_id"];
                    $attachments = ($msg['attachments']);
                    $images = '';
                    if (isset($attachments)) {
                        $attachments = json_decode($attachments, true);
                        $img_count = 0;
                        if (isset($attachments) && count($attachments) > 0) {
                            foreach ($attachments as $attachment) {
                                $image = whizzChat_get_image_size_links($attachment);
                                $huumb = $image["thumbnail"];
                                $full = $image["full"];
                                $images .= '<a data-fancybox="gallery-' . $message_id . '" href="' . esc_url($full) . '"><img src="' . esc_url($huumb) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" /></a>';
                                $img_count++;
                            }
                        }
                        $more_html = ($img_count >= 4) ? '<div class="centered">' . esc_html__("More", "whizz-chat") . '</div>' : "";
                        $extra_class = ($img_count < 4) ? 'whizz-less-images' : '';
                        $div_attributes = apply_filters("whizzChat_chat_messages_div_attributes", '', $user_data, $msg);
                        $man_div_css = ($is_reply != "" ) ? "main-" . $is_reply : '';
                        if ($is_reply == "message-partner") {
                            $chat_html .= '<div class="message-box-holder media whizzchat-media w-50 mb-3" ' . $div_attributes . '>
                                             ' . $image_post . '
                                                <div class="media-body ml-3">
                                                    <div class="bg-light rounded py-2 px-3 mb-2">
                                                        <p class="text-small mb-0 text-muted"> ' . $images . '</p>
                                                    </div>
                                                    ' . $time_html . '
                                                </div>
                                            </div>';
                        } else {
                            $chat_html .= '<div class="message-box-holder media whizzchat-media w-50 ml-auto mb-3" ' . $div_attributes . '>
                                            <div class="media-body">
                                                <div class="bg-primary rounded py-2 px-3 mb-2">
                                                    <p class="text-small mb-0"> ' . $images . '</p>
                                                </div>
                                               ' . $time_html . '
                                            </div>
                                        </div>';
                        }
                    }
                } else if ($msg['message_type'] == 'file') {
                    $is_reply = $msg['is_reply'];
                    $attachments = ($msg['attachments']);
                    $urls = '';
                    if (isset($attachments)) {
                        $attachments = json_decode($attachments, true);
                        foreach ($attachments as $attachment) {
                            $urls .= "<a href='" . wp_get_attachment_url($attachment) . "' target='_blank'>" . basename(get_attached_file($attachment)) . "</a>";
                        }

                        $div_attributes = apply_filters("whizzChat_chat_messages_div_attributes", '', $user_data, $msg);
                        $man_div_css = ($is_reply != "" ) ? "main-" . $is_reply : '';
                        if ($is_reply == "message-partner") {
                            $chat_html .= '<div class="message-box-holder media w-50 mb-3" ' . $div_attributes . '>
                                             ' . $image_post . '
                                                <div class="media-body ml-3">
                                                    <div class="bg-light rounded py-2 px-3 mb-2">
                                                        <p class="text-small mb-0 text-muted">' . $urls . '</p>
                                                    </div>
                                                    ' . $time_html . '
                                                </div>
                                            </div>';
                        } else {
                            $chat_html .= '<div class="message-box-holder media w-50 ml-auto mb-3" ' . $div_attributes . '>
                                            <div class="media-body">
                                                <div class="bg-primary rounded py-2 px-3 mb-2">
                                                    <p class="text-small mb-0"> ' . $urls . '</p>
                                                </div>
                                               ' . $time_html . '
                                            </div>
                                        </div>';
                        }
                    }
                } else {

                    if ($msg['message_type'] == 'map') {

                        $is_reply = $msg['is_reply'];
                        $latlongs = ( json_decode($msg['attachments'], true) );
                        if (isset($latlongs['latitude']) && isset($latlongs['longitude'])) {
                            $latitude = $latlongs['latitude'];
                            $longitude = $latlongs['longitude'];
                            $message_id = $msg["chat_message_id"];
                            $message_id = rand(123, 999);

                            $img_url = plugins_url('whizz-chat') . "/assets/images/marker-pin.webp";
                            $script = '<script>
                                    var map = L.map("map-' . $message_id . '", {center: [' . $latitude . ', ' . $longitude . '],zoom: 16, scrollWheelZoom : false });
                                    L.tileLayer(\'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png\', {
                                    }).addTo(map);
                                    var myIcon = L.icon({ iconUrl: "' . $img_url . '", iconSize: [36, 36], iconAnchor: [22, 94], popupAnchor: [-3, -76], shadowSize: [68, 95], shadowAnchor: [22, 94] }); L.marker([' . $latitude . ', ' . $longitude . '], {icon: myIcon}).addTo(map);
                                    ;</script>';
                            $div_attributes = apply_filters("whizzChat_chat_messages_div_attributes", '', $user_data, $msg);
                            $man_div_css = ($msg["is_reply"] != "" ) ? "main-" . $msg["is_reply"] : '';

                            if ($is_reply == "message-partner") {
                                $chat_html .= '<div class="message-box-holder media w-50 mb-3" ' . $div_attributes . '>
                                                <div class="media-body ml-3">
                                                    <div class="bg-light rounded py-2 px-3 mb-2">
                                                        <p class="text-small mb-0 text-muted"><div id="map-' . $message_id . '"></div></p>
                                                    </div>
                                                    ' . $time_html . '
                                                </div>
                                                ' . $script . '
                                            </div>';
                            } else {
                                $chat_html .= '<div class="message-box-holder media w-50 ml-auto mb-3" ' . $div_attributes . '>
                                            <div class="media-body">
                                                <div class="bg-primary rounded py-2 px-3 mb-2">
                                                    <p class="text-small mb-0"> <div id="map-' . $message_id . '"></div></p>
                                                </div>
                                               ' . $time_html . '
                                            </div>
                                            ' . $script . '
                                        </div>';
                            }
                        }

                        $div_attributes = apply_filters("whizzChat_chat_messages_div_attributes", '', $user_data, $msg);
                        $man_div_css = ($is_reply != "" ) ? "main-" . $is_reply : '';
                    }
                }
            }
        }

        $seen_at_html = '';

        $seen_at = apply_filters("whizzChat_list_chat_messages_seen", '', $user_data, $last_message);

        $seen_at = '';

        if ($seen_at != "") {
            $seen_text = esc_html__("Seen", "whizz-chat");
            $seen_at_html = '<span class="whizzChat-chat-messages-last">' . $seen_text . ': ' . $seen_at . '</span>';
        }

        if (isset($user_data['last_chat_id']) && $user_data['last_chat_id'] != "") {
            return $chat_html;
        } else if (isset($user_data['send_message']) && $user_data['send_message'] != "") {
            return $chat_html . $seen_at_html;
        } else if (isset($user_data['first_message_id']) && $user_data['first_message_id'] != "") {
            return $chat_html . $seen_at_html;
        } else {
            return $chat_html_load . $chat_html;
        }
    }

    public function whizzChat_individual_chatbox_html($user_data, $box_content = array()) {

        global $whizzChat_options;
        $whizzChat_options = get_option('whizz-chat-options');
        $whizzChat_allow_bot = isset($whizzChat_options['whizzChat-allow-chatbot']) && $whizzChat_options['whizzChat-allow-chatbot'] != '1' ? FALSE : TRUE;
        $chat_html = $seen_at = '';
        $chat_html .= apply_filters("whizz_filter_chat_box_content_before", '', $user_data, $box_content);
        $last_message = array();
        if ($user_data['id'] > 0) {
            $current_user = get_current_user_id();
            $messages = apply_filters('whizzChat_list_chat_messages', $user_data);
            $last_message = end($messages);
            $date_array = array();

            foreach ($messages as $msg) {
                $chat_time = date("Y-m-d", strtotime($msg['chat_time']));
                if (!in_array($chat_time, $date_array)) {
                    $date_array[] = $chat_time;
                }
                if ($msg['message_type'] == 'text') {
                    $chat_html .= apply_filters("whizzChat_list_chat_messages_text", '', $user_data, $msg);
                } else if ($msg['message_type'] == 'image') {
                    $chat_html .= apply_filters("whizzChat_list_chat_messages_image", '', $user_data, $msg);
                } else if ($msg['message_type'] == 'file') {
                    $chat_html .= apply_filters("whizzChat_list_chat_messages_file", '', $user_data, $msg);
                } else if ($msg['message_type'] == 'voice') {
                    $chat_html .= apply_filters("whizzChat_list_chat_messages_voice", '', $user_data, $msg);
                } else {
                    $chat_html .= apply_filters("whizzChat_list_chat_messages_list", '', $user_data, $msg);
                }
            }
        }

        $chat_html .= apply_filters("whizz_filter_chat_box_content_after", '', $user_data, $box_content);
        $seen_at_html = '';
        $seen_at = apply_filters("whizzChat_list_chat_messages_seen", '', $user_data, $last_message);
        $seen_at = "";

        if ($seen_at != "") {
            $seen_text = esc_html__("Seen", "whizz-chat");
            $seen_at_html = '<span class="whizzChat-chat-messages-last">' . $seen_text . ': ' . $seen_at . '</span>';
        }

        if (isset($user_data['last_chat_id']) && $user_data['last_chat_id'] != "") {
            return $chat_html;
        } else if (isset($user_data['send_message']) && $user_data['send_message'] != "") {
            return $chat_html . $seen_at_html;
        } else if (isset($user_data['first_message_id']) && $user_data['first_message_id'] != "") {
            return $chat_html . $seen_at_html;
        } else {
            return '<div class="chat-messages">
                    <span class="whizzChat-span-loading whizzChat-loading"></span>
                    ' . $chat_html . '
                    ' . $seen_at_html . '
                    <input type="hidden" value="active" id="chat-box-' . $user_data['id'] . '" />
                    </div>';
        }
    }

    public function whizzChat_chat_messages_div_attributes_html($chat_html = '', $user_data = "", $msg ="") {
        $attr = '';
        $attr .= " data-chat-unique-id='" . $msg['chat_message_id'] . "'";
        $attr .= " data-chat-last-seen='" . $msg['seen_at'] . "'";
        return $attr;
    }

    public function whizzChat_list_chat_messages_map_html($chat_html, $user_data, $msg) {
        if ($msg['message_type'] == 'map') {
            $chat_user_name = isset($msg['chat_sender_name']) && $msg['chat_sender_name'] != '' ? $msg['chat_sender_name'] : 'Demo';
            $latlongs = ( json_decode($msg['attachments'], true) );
            if (isset($latlongs['latitude']) && isset($latlongs['longitude'])) {
                $latitude = $latlongs['latitude'];
                $longitude = $latlongs['longitude'];
                $message_id = $msg["chat_message_id"];
                $img_url = plugins_url('whizz-chat') . "/assets/images/marker-pin.webp";
                $script = '<script>
                var map = L.map("map-' . $message_id . '", {center: [' . $latitude . ', ' . $longitude . '],zoom: 16, scrollWheelZoom : false });
                L.tileLayer(\'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png\', {
                }).addTo(map);
                var myIcon = L.icon({ iconUrl: "' . $img_url . '", iconSize: [36, 36], iconAnchor: [22, 94], popupAnchor: [-3, -76], shadowSize: [68, 95], shadowAnchor: [22, 94] }); L.marker([' . $latitude . ', ' . $longitude . '], {icon: myIcon}).addTo(map);
                ;</script>';

                $div_attributes = apply_filters("whizzChat_chat_messages_div_attributes", '', $user_data, $msg);
                $man_div_css = ($msg["is_reply"] != "" ) ? "main-" . $msg["is_reply"] : '';
                $chat_html .= '<div class="message-box-holder ' . $man_div_css . '" ' . $div_attributes . '><div class="message-box ' . $msg["is_reply"] . '"> <p><span class="name"> ' . $chat_user_name . ' </span><span class="time"> ' . whizzChat::whizzchat_time_ago($msg['chat_time']) . '</span></p><div id="map-' . $message_id . '"></div>' . $script . '</div></div>';
            }
        }
        return $chat_html;
    }

    public function whizzChat_list_chat_messages_text_html($chat_html, $user_data, $msg) {
        global $whizzChat_options;

        $whizzChat_options = get_option('whizz-chat-options');
        $whizzChat_type = $whizzChat_options['whizzChat-chat-type'];
        $chat_user_name = isset($msg['chat_sender_name']) && $msg['chat_sender_name'] != '' ? $msg['chat_sender_name'] : 'Demo';
        $is_reply = $msg['is_reply'];
        $chatId = $msg['chat_message_id'];
        $inner = '';
        if ($msg['chat_message'] != "") {
            $chat_message = $msg['chat_message'];
            $man_div_css = ($is_reply != "" ) ? "main-" . $is_reply : '';
            $div_attributes = apply_filters("whizzChat_chat_messages_div_attributes", '', $user_data, $msg);
            $chat_html .= '<div class="message-box-holder ' . $man_div_css . '" ' . $div_attributes . '>
                            <div class="message-box ' . $is_reply . '">
				<p><span class="name"> ' . $chat_user_name . ' </span><span class="time"> ' . whizzChat::whizzchat_time_ago($msg['chat_time']) . '</span></p>	
                                <p>' . nl2br($chat_message) . '</p>
                            </div>
                           </div>';
        }

        return $chat_html;
    }

    public function whizzChat_list_chat_messages_image_html($chat_html, $user_data, $msg) {
        $is_reply = $msg['is_reply'];
        $message_id = $msg["chat_message_id"];
        $attachments = ($msg['attachments']);
        $images = '';
        $image_id = '';
        $chat_user_name = isset($msg['chat_sender_name']) && $msg['chat_sender_name'] != '' ? $msg['chat_sender_name'] : 'Demo';
        if (isset($attachments)) {
            $attachments = json_decode($attachments, true);
            $img_count = 0;
            if (isset($attachments) && count($attachments) > 0) {
                foreach ($attachments as $attachment) {
                    $image = whizzChat_get_image_size_links($attachment);
                    $huumb = $image["thumbnail"];
                    $full = $image["full"];
                    $images .= '<a data-fancybox="gallery-' . $message_id . '" href="' . esc_url($full) . '"><img src="' . esc_url($huumb) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" /></a>';

                    $img_count++;
                }
            }
            $more_html = ($img_count >= 4) ? '<div class="centered">' . esc_html__("More", "whizz-chat") . '</div>' : "";
            $extra_class = ($img_count < 4) ? ' whizz-less-images' : '';
            $div_attributes = apply_filters("whizzChat_chat_messages_div_attributes", '', $user_data, $msg);
            $man_div_css = ($is_reply != "" ) ? "main-" . $is_reply : '';
            $chat_html .= '<div class="message-box-holder ' . $man_div_css . '" ' . $div_attributes . '>
                    <div class="message-box ' . $is_reply . '">
                        <p><span class="name"> ' . $chat_user_name . ' </span><span class="time"> ' . whizzChat::whizzchat_time_ago($msg['chat_time']) . '</span></p>
                        <div class="whizzChat-chatbox-images' . $extra_class . '">' . $images . $more_html . '</div>
                    </div>
                   </div>';
        }

        return $chat_html;
    }

    public function whizzChat_list_chat_messages_file_html($chat_html, $user_data, $msg) {
        $is_reply = $msg['is_reply'];
        $attachments = ($msg['attachments']);
        $urls = '';
        if (isset($attachments)) {
            $attachments = json_decode($attachments, true);
            foreach ($attachments as $attachment) {
                $urls .= "<a href='" . wp_get_attachment_url($attachment) . "' target='_blank'>" . basename(get_attached_file($attachment)) . "</a>";
            }
            $chat_user_name = isset($msg['chat_sender_name']) && $msg['chat_sender_name'] != '' ? $msg['chat_sender_name'] : 'Demo';
            $div_attributes = apply_filters("whizzChat_chat_messages_div_attributes", '', $user_data, $msg);
            $man_div_css = ($is_reply != "" ) ? "main-" . $is_reply : '';
            $chat_html .= '<div class="message-box-holder ' . $man_div_css . '"  ' . $div_attributes . '>
                               
                            <div class="message-box ' . $is_reply . '"> <p><span class="name"> ' . $chat_user_name . ' </span><span class="time"> ' . whizzChat::whizzchat_time_ago($msg['chat_time']) . '</span></p>' . $urls . '</div>
                           </div>';
        }
        return $chat_html;
    }

    public function whizzChat_list_chat_messages_voice_html($chat_html, $user_data, $msg) {
        $is_reply = $msg['is_reply'];
        $attachments = ($msg['attachments']);
        $urls = '';
        if (isset($attachments)) {
            $attachments = json_decode($attachments, true);
            foreach ($attachments as $attachment) {
                $urls .= '<audio controls>
                            <source src="' . wp_get_attachment_url($attachment) . '" type="audio/wav">
                          </audio>';
            }
            $chat_user_name = isset($msg['chat_sender_name']) && $msg['chat_sender_name'] != '' ? $msg['chat_sender_name'] : 'Demo';
            $div_attributes = apply_filters("whizzChat_chat_messages_div_attributes", '', $user_data, $msg);
            $man_div_css = ($is_reply != "" ) ? "main-" . $is_reply : '';
            $chat_html .= '<div class="message-box-holder ' . $man_div_css . '"  ' . $div_attributes . '>
                            <div class="message-box ' . $is_reply . '"> <p><span class="name"> ' . $chat_user_name . ' </span><span class="time"> ' . whizzChat::whizzchat_time_ago($msg['chat_time']) . '</span></p>' . $urls . '</div>
                           </div>';
        }
        return $chat_html;
    }

    public function whizzChat_individual_chatbox_footer_html($user_data, $box_content) {

        global $whizzChat_options;
        $whizz_chat_emojies = '1';
        $whizz_chat_emojies = isset($whizz_chat_emojies) && $whizz_chat_emojies == '1' ? TRUE : FALSE;
        $emj_class = '';
        if ($whizz_chat_emojies) {
            $emj_class = ' whizzChat-emoji';
        }

        $loader_html = '<div class="whizzChat-custom"><div class="overlay"></div><div class="spanner"><div class="loader"></div></div></div>';
        $html = '';
        $user_name = whizzChat::user_data('name');
        $live_room_data = ' data-room="' . md5($_SERVER['HTTP_HOST']) . '_whizchat' . $user_data['id'] . '" ';
        $html .= '<div class="whizz-custom-div">
                     ' . $loader_html . '
                     <span class="typing-box-' . $user_data['id'] . ' whizz-typing"></span>    
                    <div class="chat-input-holder" ' . $live_room_data . ' data-chat-id="' . $user_data['id'] . '" data-user-name="' . $user_name . '">
                      <textarea placeholder="Type a message"  class="chat-input initate-chat-input-text' . $emj_class . '"></textarea>
                      <a href="javascript:void(0)" class="message-send whizz-btn-wrap-' . $user_data['id'] . '"><i class="fas fa-chevron-right initate-chat-input-btn"></i></a>
                    </div>';
        $attachment_panel = '';
        $html .= '<div class="attachment-panel">' . apply_filters("whizz_filter_chat_box_footer_attachment", $attachment_panel, $user_data) . '</div></div>';
        return $html;
    }

    public function whizz_filter_chat_box_footer_attachment_html($html, $user_data, $admin = false) {

        global $whizzChat_options;
        $whizz_chat_location = $whizzChat_options['whizzChat-allow-location'];
        $whizz_chat_location = isset($whizz_chat_location) && $whizz_chat_location == '1' ? TRUE : FALSE;
        if ($whizz_chat_location) {
            $html .= '<a href="javascript:void(0)" class="whizz-chat-location fa fa-map-marker"></a>';
        }
        return $html;
    }

    public function whizz_filter_chat_box_footer_image_attachment_html($html, $user_data, $admin = false) {

        global $whizzChat_options;
        $img_formats = $whizzChat_options['whizzChat-image-format'];
        $img_formats = isset($img_formats) && $img_formats != '' ? $img_formats : 'jpeg,jpg,png,gif,bmp';
        $file_formats = $whizzChat_options['whizzChat-file-format'];
        $file_formats = isset($file_formats) && $file_formats != '' ? $file_formats : 'zip,docx';
        $whizzChat_record_limit = isset($whizzChat_options['whizzChat-record-limit']) && $whizzChat_options['whizzChat-record-limit'] != '' ? $whizzChat_options['whizzChat-record-limit'] : 10;
        $attach_img_class = 'whizzChat-image';
        $attach_file_class = 'whizzChat-file';
        if ($admin) {
            $attach_img_class = 'whizzChat-image-admin';
            $attach_file_class = 'whizzChat-file-admin';
        }
        $file = whizzChat_globalVal('whizzChat-allow-file');
        if ($file == 1) {
            $img_format_arr = explode(',', $file_formats);
            $accept_img_format = '';
            $counter = 1;
            foreach ($img_format_arr as $img_key) {
                $accept_img_format .= '.' . $img_key;
                if (count($img_format_arr) > $counter) {
                    $accept_img_format .= ',';
                }
                $counter++;
            }
            $html .= '<form action="" method="POST" class="ibenic_upload_form whizzChat-file" enctype="multipart/form-data">
                        <div class="ibenic_upload_message"></div>
                            <div id="ibenic_file_upload" class="file-upload ibenic_file_upload" style="position: relative;">
                            <input type="file" accept="' . $accept_img_format . '" id="ibenic_file_input-' . rand(1234, 99999) . '" class="ibenic_file_input fa fa-link-o ' . $attach_file_class . '" multiple style="opacity:0;;position: absolute;top: 0;right: 0;width: 25px;height: 25px;cursor: pointer;" />
                            <i class="fa fa-link"></i> 
                      </div>
                    </form>';
        }

        $image = whizzChat_globalVal('whizzChat-allow-image');
        if ($image == 1) {
            $img_format_arr = explode(',', $img_formats);
            $accept_img_format = '';
            $counter = 1;
            foreach ($img_format_arr as $img_key) {
                $accept_img_format .= '.' . $img_key;
                if (count($img_format_arr) > $counter) {
                    $accept_img_format .= ',';
                }
                $counter++;
            }
            $html .= '<form action="" method="POST" class="ibenic_upload_form whizzChat-image" enctype="multipart/form-data">
                        <div class="ibenic_upload_message"></div>
                            <div id="ibenic_file_upload" class="file-upload ibenic_file_upload" style="position: relative;">
                            <input type="file" accept="' . $accept_img_format . '" id="ibenic_file_input-' . rand(1234, 99999) . '" class="ibenic_file_input fa-picture-o ' . $attach_img_class . '" multiple style="opacity:0;;position: absolute;top: 0;right: 0;width: 25px;height: 25px;cursor: pointer;" />
                            <i class="fa fa-picture-o"></i> 
                      </div>
                    </form>';
        }
        $audio_record = false;
        if ($audio_record) {
            ob_start();
            ?>
            <a class="whizzchat-record-sound" href="javascript:void(0)"><i class="fa fa-microphone" aria-hidden="true"></i></a>
            <a class="whizzchat-send-voice" ><i class="fa fa-check-circle" aria-hidden="true"></i></a>
            <a class="whizzchat-remove-voice" ><i class="fa fa-times-circle" aria-hidden="true"></i></a>
            <span class="whizz-chat-countdown"></span>
            <?php
            $record_data = ob_get_contents();
            ob_end_clean();
            $html .= $record_data;
        }
        return $html;
    }

    public function whizzChat_popup_box_offline_html($user_data) {
        global $whizzChat_options;
        $user_type = $whizzChat_options['whizzChat-chat-type'];
        $user_type = isset($user_type) && $user_type != '' ? $user_type : 1;

        $login_type = $whizzChat_options['whizzChat-login-page-type'];
        $login_type = isset($login_type) && $login_type != '' ? $login_type : 'url';

        $login_url = $whizzChat_options['whizzChat-login-url'];
        $login_url = isset($login_url) && $login_url != '' ? $login_url : 'javascript:void(0)';

        $attr_cerat = 'href="' . $login_url . '" ';
        if ($login_type == 'popup') {
            $attr_cerat = 'href="javascript:void(0)" data-toggle="modal" data-target="' . $login_url . '" ';
        }

        $whizzchat_btn_bgclr = isset($whizzChat_options["whizzchat-btn-bg-color"]) && $whizzChat_options["whizzchat-btn-bg-color"] != '' ? $whizzChat_options["whizzchat-btn-bg-color"] : '#4c67f0';


        $whizzchat_btn_txtclr = isset($whizzChat_options["whizzchat-btn-txt-color"]) && $whizzChat_options["whizzchat-btn-txt-color"] != '' ? $whizzChat_options["whizzchat-btn-txt-color"] : '#FFFFFF';
        $btn_style = ' style="background-color:' . $whizzchat_btn_bgclr . ';color:' . $whizzchat_btn_txtclr . '"';

        $form_html = '';
        if ($user_type == 2) {
            $form_html .= '<div class="login-first"><h3>' . esc_html__('Please login first to start chat.', 'whizz-chat') . '</h3>';
            $form_html .= '<a  ' . $attr_cerat . 'target="_blank"> ' . esc_html__('Login Here', 'whizz-chat') . '</a></div>';
        } else {

            $form_html .= '<div class="panel-body">';
            $form_html .= '<form accept-charset="UTF-8" role="form" class="initate-chat">';
            if ($user_type == 1) {
                $form_html .= '<div class="whizz-chat-desc"><p>' . esc_html__("Please Provide name and email of your choice to start chat.", "whizz-chat") . '</p></div>';
            }
            $form_html .= '                       <div class="form-group">
								   <i class="far fa-user"></i>
                                       <input class="form-control new-user-name" placeholder="' . esc_html__("Full Name", "whizz-chat") . '" name="new_user_name" type="text" autocomplete="off">
                                   </div>';
            if ($user_type != 0) {
                $form_html .= '<div class="form-group">
								<i class="far fa-envelope"></i>
                              <input class="form-control new-user-email" placeholder="' . esc_html__("Email Address", "whizz-chat") . '" name="new_user_email" type="text" autocomplete="off">
                           </div><div class="whizz-chat-error"></div>';
            }
            $form_html .= '<button class="btn btn-lg btn-success initate-chat-button"' . $btn_style . '>' . esc_html__("Start Chat", "whizz-chat") . '</button>
                    </form></div>';
        }
        return $form_html;
    }

}
new Whizz_Chat_box_Html();

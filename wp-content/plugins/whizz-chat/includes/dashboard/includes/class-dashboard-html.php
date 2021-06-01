<?php
/*
 * Class to render the dashboard html
 * 
 */
if (!class_exists('WhizzChat_Dashboard_Html')) {

    class WhizzChat_Dashboard_Html {

        public function __construct() {

            add_filter('whizzChat_dashboard_load_chatlist', array($this, 'whizzChat_dashboard_load_chatlist_callback'), 10, 1);
            add_filter('whizz_filter_chat_box_header_dashboard', array($this, 'whizzChat_individual_chatbox_header_html'), 10, 1);
            add_filter('whizzChat_list_chat_messages_text_dashboard', array($this, 'whizzChat_text_message_dashboard_callback'), 10, 3);
            add_filter('whizzChat_list_chat_messages_image_dashboard', array($this, 'whizzChat_list_chat_messages_image_dashboard_html'), 10, 3);
            add_filter('whizzChat_list_chat_messages_file_dashboard', array($this, 'whizzChat_list_chat_messages_file_dashboard_html'), 10, 3);
            add_filter('whizzChat_list_chat_messages_voice_dashboard', array($this, 'whizzChat_list_chat_messages_voice_dashboard_html'), 10, 3);
            add_filter('whizzChat_list_chat_messages_list_dashboard', array($this, 'whizzChat_list_chat_messages_map_dashboard_html'), 10, 3);
            add_filter('whizz_filter_chat_box_footer_dashboard', array($this, 'whizzChat_individual_chatbox_footer_dashboard_html'), 10, 2);
            add_filter('whizz_filter_chat_box_content_dashboard', array($this, 'whizz_filter_chat_box_content_dashboard_html'), 10, 2);
        }

        public function whizz_filter_chat_box_content_dashboard_html($user_data, $box_content = array()) {


            global $whizzChat_options;
            $whizzChat_options = get_option('whizz-chat-options');
            $whizzChat_allow_bot = isset($whizzChat_options['whizzChat-allow-chatbot']) && $whizzChat_options['whizzChat-allow-chatbot'] != '1' ? FALSE : TRUE;
            $chat_html = $seen_at = '';
            $chat_html .= apply_filters("whizz_filter_chat_box_content_before", '', $user_data, $box_content);
            $last_message = array();
            if ($user_data['id'] > 0) {
                $current_user = get_current_user_id();
                $messages = apply_filters('whizzChat_list_chat_messages_dashboard', $user_data);
                $last_message = end($messages);
                $date_array = array();

                foreach ($messages as $msg) {

                    $chat_time = date("Y-m-d", strtotime($msg['chat_time']));
                    if (!in_array($chat_time, $date_array)) {
                        $date_array[] = $chat_time;
                    }
                    if ($msg['message_type'] == 'text') {
                        $chat_html .= apply_filters("whizzChat_list_chat_messages_text_dashboard", '', $user_data, $msg);
                    } else if ($msg['message_type'] == 'image') {
                        $chat_html .= apply_filters("whizzChat_list_chat_messages_image_dashboard", '', $user_data, $msg);
                    } else if ($msg['message_type'] == 'file') {
                        $chat_html .= apply_filters("whizzChat_list_chat_messages_file_dashboard", '', $user_data, $msg);
                    } else if ($msg['message_type'] == 'voice') {
                        $chat_html .= apply_filters("whizzChat_list_chat_messages_voice_dashboard", '', $user_data, $msg);
                    } else {
                        $chat_html .= apply_filters("whizzChat_list_chat_messages_list_dashboard", '', $user_data, $msg);
                    }
                }
            }

            $chat_html .= apply_filters("whizz_filter_chat_box_content_after_admin", '', $user_data, $box_content);
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

                return '
                    <div class="chat-content p-2" id="whizzchat-message-body" data-post-id="' . $user_data['post_id'] . '" data-chat-id="' . $user_data['id'] . '">
                    <div class="container chat-messages-dashb">
                        <span class="whizzChat-span-loading whizzChat-loading"></span>
                        ' . $chat_html . '
                        ' . $seen_at_html . '
                    <input type="hidden" value="active" id="chat-box-' . $user_data['id'] . '" />
                    </div></div>';
            }
        }

        public function whizzChat_list_chat_messages_map_dashboard_html($chat_html, $user_data, $msg) {
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

                    $sender_receiver = isset($msg["is_reply"]) && $msg["is_reply"] == "message-partner" ? ' other' : ' self';
                    
                    $image_id = '';
                    $size = array(150, 150);
                    $url = get_the_post_thumbnail_url($msg['chat_post_id'], $size);
                    if ($url) {
                        $image = '<img src="' . esc_url($url) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" />';
                    } else {
                        $url = plugin_dir_url('/') . 'whizz-chat/assets/images/no-list-img.jpg';
                        $image = '<img src="' . esc_url($url) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" />';
                    }

                    $chat_html .= '<div class="message' . $sender_receiver . ' message-box-holder-dash" ' . $div_attributes . '>
                                    <div class="message-wrapper">
                                        <div class="message-content message-box">
                                            <span><div id="map-' . $message_id . '"></div></span>
                                        </div>
                                    </div>
                                    <div class="message-options">
                                        <div class="avatar avatar-sm">' . $image . '</div>
                                        <span class="message-date">' . whizzChat::whizzchat_time_ago($msg['chat_time']) . '</span>
                                    </div>
                                ' . $script . '</div>';
                }
            }
            return $chat_html;
        }

        public function whizzChat_list_chat_messages_voice_dashboard_html($chat_html, $user_data, $msg) {
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

                $sender_receiver = isset($msg["is_reply"]) && $msg["is_reply"] == "message-partner" ? ' other' : ' self';
                $image_id = '';
                $size = array(150, 150);
                $url = get_the_post_thumbnail_url($msg['chat_post_id'], $size);
                if ($url) {
                    $image = '<img src="' . esc_url($url) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" />';
                } else {
                    $url = plugin_dir_url('/') . 'whizz-chat/assets/images/no-list-img.jpg';
                    $image = '<img src="' . esc_url($url) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" />';
                }
                $chat_html .= '<div class="message' . $sender_receiver . ' message-box-holder-dash" ' . $div_attributes . '>
                                    <div class="message-wrapper">
                                        <div class="message-content">
                                            <span>' . $urls . '</span>
                                        </div>
                                    </div>
                                    <div class="message-options">
                                        <div class="avatar avatar-sm">' . $image . '</div>
                                        <span class="message-date">' . whizzChat::whizzchat_time_ago($msg['chat_time']) . '</span>
                                    </div>
                                ' . $script . '</div>';
            }
            return $chat_html;
        }

        public function whizzChat_list_chat_messages_file_dashboard_html($chat_html, $user_data, $msg) {

            $is_reply = $msg['is_reply'];
            $attachments = ($msg['attachments']);
            $urls = '';
            if (isset($attachments)) {
                $attachments = json_decode($attachments, true);
                foreach ($attachments as $attachment) {
                    $file_type = wp_check_filetype(wp_get_attachment_url($attachment));
                    $urls .= '<h6><a href="' . wp_get_attachment_url($attachment) . '" class="text-reset" title="' . basename(get_attached_file($attachment)) . '">' . basename(get_attached_file($attachment)) . '</a></h6>';
                    $urls .= '<ul class="list-inline small mb-0">
                                <li class="list-inline-item">
                                    <span class="text-muted">' . size_format(filesize(get_attached_file($attachment)), 1) . '</span>
                                </li>
                                <li class="list-inline-item">
                                    <span class="text-muted text-uppercase">' . $file_type['ext'] . '</span>
                                </li>
                            </ul>';
                }
                $chat_user_name = isset($msg['chat_sender_name']) && $msg['chat_sender_name'] != '' ? $msg['chat_sender_name'] : 'Demo';
                $div_attributes = apply_filters("whizzChat_chat_messages_div_attributes", '', $user_data, $msg);
                $man_div_css = ($is_reply != "" ) ? "main-" . $is_reply : '';
                $sender_receiver = isset($msg["is_reply"]) && $msg["is_reply"] == "message-partner" ? ' other' : ' self';
                $image_id = '';
                $size = array(150, 150);
                $url = get_the_post_thumbnail_url($msg['chat_post_id'], $size);
                if ($url) {
                    $image = '<img src="' . esc_url($url) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" />';
                } else {
                    $url = plugin_dir_url('/') . 'whizz-chat/assets/images/no-list-img.jpg';
                    $image = '<img src="' . esc_url($url) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" />';
                }

                $chat_html = '<div class="message' . $sender_receiver . ' message-box-holder-dash" ' . $div_attributes . '>
                                    <div class="message-wrapper">
                                        <div class="message-content">
                                            <div class="document">
                                                <div class="btn btn-primary btn-icon rounded-circle text-light mr-2">
                                                    <!-- Default :: Inline SVG -->
                                                    <svg class="hw-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                                <div class="document-body">
                                                        ' . $urls . '
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="message-options">
                                        <div class="avatar avatar-sm">' . $image . '</div>
                                        <span class="message-date">' . whizzChat::whizzchat_time_ago($msg['chat_time']) . '</span>
                                    </div>
                                </div>';
            }
            return $chat_html;
        }

        public function whizzChat_list_chat_messages_image_dashboard_html($chat_html, $user_data, $msg) {
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
                        $images_old = '<a data-fancybox="gallery-' . $message_id . '" href="' . esc_url($full) . '"><img src="' . esc_url($huumb) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" /></a>';

                        $images .= '<div class="col">
                                        <a class="popup-media" href="' . esc_url($full) . '">
                                            <img class="img-fluid rounded" src="' . esc_url($huumb) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '">
                                        </a>
                                    </div>';


                        $img_count++;
                    }
                }
                $more_html = ($img_count >= 4) ? '<div class="centered">' . esc_html__("More", "whizz-chat") . '</div>' : "";
                $extra_class = ($img_count < 4) ? 'whizz-less-images' : '';
                $div_attributes = apply_filters("whizzChat_chat_messages_div_attributes", '', $user_data, $msg);
                $man_div_css = ($is_reply != "" ) ? "main-" . $is_reply : '';
                $chat_html_old = '<div class="message-box-holder ' . $man_div_css . '" ' . $div_attributes . '>
                    <div class="message-box ' . $is_reply . '">
                        <p><span class="name"> ' . $chat_user_name . ' </span><span class="time"> ' . whizzChat::whizzchat_time_ago($msg['chat_time']) . '</span></p>
                        <div class="whizzChat-chatbox-images ' . $extra_class . '">' . $images . $more_html . '</div>
                    </div>
                   </div>';

                $sender_receiver = isset($msg["is_reply"]) && $msg["is_reply"] == "message-partner" ? ' other' : ' self';
                $image_id = '';
                $size = array(150, 150);
                $url = get_the_post_thumbnail_url($msg['chat_post_id'], $size);
                if ($url) {
                    $image = '<img src="' . esc_url($url) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" />';
                } else {
                    $url = plugin_dir_url('/') . 'whizz-chat/assets/images/no-list-img.jpg';
                    $image = '<img src="' . esc_url($url) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" />';
                }
                
                $chat_html = '<div class="message' . $sender_receiver . ' message-box-holder-dash" ' . $div_attributes . '>
                                    <div class="message-wrapper">
                                        <div class="message-content">
                                            <div class="form-row">
                                                ' . $images . '
                                            </div>
                                        </div>
                                    </div>
                                    <div class="message-options">
                                        <div class="avatar avatar-sm">' . $image . '</div>
                                        <span class="message-date">' . whizzChat::whizzchat_time_ago($msg['chat_time']) . '</span>
                                    </div>
                                </div>';
            }

            return $chat_html;
        }

        public function whizzChat_text_message_dashboard_callback($chat_html, $user_data, $msg) {
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
                
                $image_id = '';
                $size = array(150, 150);
                $url = get_the_post_thumbnail_url($msg['chat_post_id'], $size);
                if ($url) {
                    $image = '<img src="' . esc_url($url) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" />';
                } else {
                    $url = plugin_dir_url('/') . 'whizz-chat/assets/images/no-list-img.jpg';
                    $image = '<img src="' . esc_url($url) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" />';
                }

                $sender_receiver = isset($msg["is_reply"]) && $msg["is_reply"] == "message-partner" ? ' other' : ' self';

                $chat_html .= '<div class="message' . $sender_receiver . ' message-box-holder-dash" ' . $div_attributes . '>
                                    <div class="message-wrapper">
                                        <div class="message-content">
                                            <span>' . nl2br($chat_message) . '</span>
                                        </div>
                                    </div>
                                    <div class="message-options">
                                        <div class="avatar avatar-sm">' . $image . '</div>
                                        <span class="message-date">' . whizzChat::whizzchat_time_ago($msg['chat_time']) . '</span>
                                    </div>
                                </div>';
            }

            return $chat_html;
        }

        public function whizzChat_individual_chatbox_footer_dashboard_html($user_data, $box_content) {

            global $whizzChat_options;
            $whizz_chat_emojies = '1';
            $whizz_chat_emojies = isset($whizz_chat_emojies) && $whizz_chat_emojies == '1' ? TRUE : FALSE;
            $emj_class = '';
            if ($whizz_chat_emojies) {
                $emj_class = ' whizzChat-emoji-dashb';
            }
            $loader_html = '<div class="whizzChat-custom"><div class="overlay"></div><div class="spanner"><div class="loader"></div></div></div>';
            $html = '';
            $user_name = whizzChat::user_data('name');
            $live_room_data = ' data-room="' . md5($_SERVER['HTTP_HOST']) . '_whizchat' . $user_data['id'] . '" ';
          
            global $whizzChat_options;
            $whizz_chat_location = $whizzChat_options['whizzChat-allow-location'];
            $whizz_chat_location = isset($whizz_chat_location) && $whizz_chat_location == '1' ? TRUE : FALSE;
            if ($whizz_chat_location) {
                $html .= '<a href="javascript:void(0)" class="whizz-chat-location fa fa-map-marker"></a>';
                $location_upload = '<a class="dropdown-item whizzchat-dash-location whizz-dashb-marker" href="javascript:void(0)">
                                        <svg class="hw-20 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span>' . esc_html__('Location', 'whizz-chat') . '</span>
                                    </a>';
            }


            global $whizzChat_options;
            $img_formats = $whizzChat_options['whizzChat-image-format'];
            $img_formats = isset($img_formats) && $img_formats != '' ? $img_formats : 'jpeg,jpg,png,gif,bmp';
            $file_formats = $whizzChat_options['whizzChat-file-format'];
            $file_formats = isset($file_formats) && $file_formats != '' ? $file_formats : 'zip,docx';
            $whizzChat_record_limit = isset($whizzChat_options['whizzChat-record-limit']) && $whizzChat_options['whizzChat-record-limit'] != '' ? $whizzChat_options['whizzChat-record-limit'] : 10;
            $attach_img_class = 'whizzChat-image';
            $attach_file_class = 'whizzChat-file';
            
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
                $file_upload = '<a class="dropdown-item whizzchat-dash-pannel" href="javascript:void(0)">
                            <form action="" method="POST" class="ibenic_upload_form whizzChat-file" enctype="multipart/form-data">
                                    <div class="ibenic_upload_message"></div>
                                        <div id="ibenic_file_upload" class="file-upload ibenic_file_upload" style="position: relative;">
                                        <input type="file" accept="' . $accept_img_format . '" id="ibenic_file_input-' . rand(1234, 99999) . '" class="ibenic_file_input fa fa-link-o ' . $attach_file_class . '" multiple style="opacity:0;;position: absolute;top: 0;right: 0;width: 25px;height: 25px;cursor: pointer;" />
                                        <svg class="hw-20 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        <span>' . esc_html__('Document', 'whizz-chat') . '</span>                            
                                     </div>
                                   </form>
                                </a>';
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
                $gall_upload = '<a class="dropdown-item whizzchat-dash-pannel" href="javasceipt:void(0)">
                                    <form action="" method="POST" class="ibenic_upload_form whizzChat-image" enctype="multipart/form-data">
                                        <div class="ibenic_upload_message"></div>
                                            <div id="ibenic_file_upload" class="file-upload ibenic_file_upload" style="position: relative;">
                                            <input type="file" accept="' . $accept_img_format . '" id="ibenic_file_input-' . rand(1234, 99999) . '" class="ibenic_file_input fa-picture-o ' . $attach_img_class . '" multiple style="opacity:0;;position: absolute;top: 0;right: 0;width: 25px;height: 25px;cursor: pointer;" />
                                            <svg class="hw-20 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span>' . esc_html__('Gallery', 'whizz-chat') . '</span>                       
                                        </div>
                                    </form>
                                </a>';
            }
            $audio_record = false;
            $voice_upload = '';
            if ($audio_record) {

                $voice_upload = '<div class="whizzchat-record-wrap"><a class="whizzchat-record-sound" href="javascript:void(0)"><i class="fa fa-microphone" aria-hidden="true"></i></a>
                                    <a class="whizzchat-send-voice" ><i class="fa fa-check-circle" aria-hidden="true"></i></a>
                                    <a class="whizzchat-remove-voice" ><i class="fa fa-times-circle" aria-hidden="true"></i></a>
                                    <span class="whizz-chat-countdown"></span></div>';
            }
            $html;
            $id = $user_data['id'];
            $post_id = $user_data['post_id'];
            $post_author_id = $user_data['post_author_id'];
            $session_id = $user_data['session_id'];
            $author_id = get_post_field('post_author', $post_id);
            $author_id = apply_filters('whizz_chat_author_rel_id', $author_id);
            $real_com_id = $user_data['session_id'];
            if ($session_id == $author_id) {
                $real_com_id = $user_data['session_id'];
            } else {
                $real_com_id = $author_id;
            }
            $live_room_data = ' data-room="' . md5($_SERVER['HTTP_HOST']) . '_whizchat' . $user_data['id'] . '" ';
            $session_id = md5($user_data['session_id']);
            $attr_data = 'id="' . $id . '" data-post-id="' . $post_id . '" ' . $live_room_data . ' data-author-id="' . $author_id . '" data-comm-id="' . $real_com_id . '" data-chat-id="' . $id . '" class="individual-chat-box" data-unique-user="' . $session_id . '"';
            $attr_data = '';
            $html = '';
            ob_start();
            ?>
            <div class="chat-footer" <?php echo whizzChat_return($attr_data);?>>
                <div class="form-row">
                    <div class="col">
                        <div class="input-group">
                            <div class="input-group-prepend mr-sm-2 mr-1">
                                <div class="dropdown">
                                    <button class="btn btn-secondary btn-icon btn-minimal btn-sm text-muted text-muted" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <svg class="hw-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                    <div class="dropdown-menu">
                                        <?php echo whizzChat_return($gall_upload);?>
                                        <?php echo whizzChat_return($file_upload);?>
                                        <?php echo whizzChat_return($location_upload);?>
                                    </div>
                                </div>
                            </div>
                            <span class="typing-box-admin whizztyping-<?php echo whizzChat_return($id);?>"></span>
                            <textarea class="form-control transparent-bg border-0 no-resize chat-input chat-input-holder-dashb initate-chat-input-text<?php echo whizzChat_return($emj_class);?>" placeholder="Write your message..." rows="1"></textarea>
                            <?php echo whizzChat_return($voice_upload);?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="btn btn-primary btn-icon rounded-circle text-light mb-1 initate-dash-btn" role="button">
                            <i class="fas fa-chevron-right" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }

        public function whizzChat_individual_chatbox_header_html($user_data) {
            global $whizzChat_options;

            $whizzChat_options = get_option('whizz-chat-options');
            $chatbox_head_color = isset($whizzChat_options['whizzChat-chatbox-head-color']) && $whizzChat_options['whizzChat-chatbox-head-color'] != '' ? $whizzChat_options['whizzChat-chatbox-head-color'] : '#FFFFFF';
            $chatbox_head_txtcolor = isset($whizzChat_options['chatbox-primary-color']) && $whizzChat_options['chatbox-primary-color'] != '' ? $whizzChat_options['chatbox-primary-color'] : '#FFFFFF';
            $chatbox_head_scd_txtcolor = isset($whizzChat_options['chatbox-second-color']) && $whizzChat_options['chatbox-second-color'] != '' ? $whizzChat_options['chatbox-second-color'] : '#FFFFFF';
            $chatbox_comm_type = isset($whizzChat_options['whizzChat-comm-type']) && $whizzChat_options['whizzChat-comm-type'] != '' ? $whizzChat_options['whizzChat-comm-type'] : '0';
            $boxcolor_style = ' style="background-color:' . $chatbox_head_color . '" ';
            $boxtxtcolor_style = ' style="color:' . $chatbox_head_txtcolor . ' !important" ';
            $boxtxt2ndcolor_style = ' style="color:' . $chatbox_head_scd_txtcolor . ' !important" ';
            $random = rand(0, 9999);
            $current_user = whizzChat::session_id();
            if ($user_data["post_author_id"] != $current_user) {
                $display_name = get_the_author_meta('display_name', $user_data["post_author_id"]);
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
            /* chatbox-unread-message */
          

            $image_id = '';
            $size = array(150, 150);
            $url = get_the_post_thumbnail_url($user_data['post_id'], $size);
            if ($url) {
                $image = '<img src="' . esc_url($url) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" />';
            } else {
                $url = plugin_dir_url('/') . 'whizz-chat/assets/images/no-list-img.jpg';
                $image = '<img src="' . esc_url($url) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" />';
            }
            $session_user_id = $user_data["session_id"];
            $post_author_id = $user_data["post_author_id"];
            $online_status = whizzChat::user_online_status($session_user_id, $post_author_id);
             $status_class = '';
             $status_label = '';
             
            if ($chatbox_comm_type == '1') {
               $status_class = ( $online_status != "" ) ? ' avatar-online' : ' avatar-away';
               $status_label = ( $online_status != "" ) ? '<small class="text-muted">'.__('Online','whizz-chat').'</small>' : '<small class="text-muted">'.__('Offline','whizz-chat').'</small>'; 
            }
            $html = '<div class="chat-header">
                <button class="btn btn-secondary btn-icon btn-minimal btn-sm text-muted d-xl-none" type="button" data-close="">
                    <svg class="hw-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </button>
                <div class="media chat-name align-items-center text-truncate">
                    <div class="avatar'.$status_class.' d-sm-inline-block mr-3 whizzchat-dash-status">
                        ' . $image . '
                    </div>
                    <div class="media-body align-self-center">
                        <h6 class="text-truncate mb-0">' . $display_name . '</h6>
                        '.$status_label.'
                    </div>
                </div>
                <ul class="nav flex-nowrap">
                    <li class="nav-item list-inline-item d-none d-sm-block mr-0">
                        <div class="dropdown">
                            <a class="nav-link text-muted px-1" href="#" role="button" title="Details" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg class="hw-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                </svg>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item align-items-center d-flex" href="javascript:void(0)">
                                    <svg class="hw-20 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    <span data-leave-chat-id="' . $user_data["id"] . '"  class="logout-chat-session-dashb">'.__('Delete','whizz-chat').'</span>
                                </a>
                                <a class="dropdown-item align-items-center d-flex text-danger" href="javascript:void(0)">
                                    <svg class="hw-20 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                    </svg>
                                    <span data-chat-id="' . $user_data["id"] . '" data-post-id="' . $user_data["post_id"] . '" class="whizzChat-block-user-dashb" data-replace-text="' . $is_blocked1 . '">' . $is_blocked . '</span>
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>';
            return $html;
        }

        public function whizzChat_dashboard_load_chatlist_callback($chat_lists = array()) {
            /* Chats Lists Html */
            global $whizzChat_options;
            $whizzChat_options = get_option('whizz-chat-options');

            $both_chat_enable = isset($whizzChat_options['whizzChat-bot']) && $whizzChat_options['whizzChat-bot'] ? TRUE : False;
            $chat_between = isset($whizzChat_options['whizzChat-chat-between']) && $whizzChat_options['whizzChat-chat-between'] != '' ? $whizzChat_options['whizzChat-chat-between'] : '0';
            $chatbox_comm_type = isset($whizzChat_options['whizzChat-comm-type']) && $whizzChat_options['whizzChat-comm-type'] != '' ? $whizzChat_options['whizzChat-comm-type'] : '0';
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
                    $message_count_html = ($session_id == $message_for) ? "<div id='chat-badge-" . $chat_id . "' class='badge badge-rounded badge-primary ml-1'>" . $message_count . "</div>" : '';
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

                    if ($url) {
                        $image = '<img src="' . esc_url($url) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" />';
                    } else {
                        $url = plugin_dir_url('/') . 'whizz-chat/assets/images/no-list-img.jpg';
                        $image = '<img src="' . esc_url($url) . '" alt="' . esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)) . '" />';
                    }
                    $title = '<h3 class="whizz-chat-text-nowrap">
                            <a onClick="return open_whizz_chat(' . $clicked . ',' . $liste . ')" href="javascript:void(0);">' . whizzchat_words_count(get_the_title($chat_list["post_id"]), 30) . '</a></h3>';
                    $author_id = get_post_field('post_author', $chat_list['post_id']);
                    $title = '';
                    if ($user_id_ses == $chat_list['session_id']) {
                        $display_name = get_the_author_meta('display_name', $author_id);
                    } else {
                        $display_name = $chat_list['name'];
                    }
                    $last_active_time = whizzChat::whizzchat_time_ago($chat_list["last_active_time"]);
                    
                    $real_com_id = $chat_list['session_id']; //
                    $real_com_id = "'" . $real_com_id . "'";
                    $admin_room = "'" . md5($_SERVER['HTTP_HOST']) . "_whizchat" . $chat_id . "'";
                    //avatar-online , avatar-offline, avatar-busy, avatar-away
                    $session_user_id = $chat_list["session_id"];
                    $post_author_id = $author_id;
                    $online_status = whizzChat::user_online_status($session_user_id, $post_author_id);
                    $status_class = '';
                    if ($chatbox_comm_type == '1') {
                        $status_class = ( $online_status != "" ) ? ' avatar-online' : ' avatar-away';
                    }
                    $list_html .= '<li id="' . $chat_id . '" class="contacts-item friends ' . $alert_msg_class . '">
                                    <a class="contacts-link" href="javascript:void(0)" onClick="return whizzchat_open_dashboard_chat_person(' . $clicked . ',' . $chat_list["post_id"] . ',this,' . $real_com_id . ',' . $admin_room . ')">
                                        <div class="avatar' . $status_class . '">
                                            ' . $image . '
                                        </div>
                                        <div class="contacts-content">
                                            <div class="contacts-info">
                                                <h6 class="chat-name text-truncate">' . $display_name . '</h6>
                                                <div class="chat-time">' . $last_active_time . '</div>
                                            </div>
                                            <div class="contacts-texts">
                                                <p class="text-truncate">' . whizzchat_words_count(get_the_title($chat_list["post_id"]), 30) . '</p>
                                                     ' . $message_count_html . '
                                            </div>
                                           
                                        </div>
                                    </a>
                                </li>';
                }
            }
            $no_chat_message = "<li>"
                                . "<div class='nochat text-center'>"
                                    . "<div class='whizz-no-img'>"
                                    . "<img src='" . plugins_url('whizz-chat') . "/assets/images/nochat-dash.png'>"
                                    . "</div>"
                                       . "<span>" . esc_html__('No Chat Available', 'whizz-chat') . "</span>"
                                . "</div>"
                                . "</li>";
            
            $load_more = "<footer><a href='#'>" . esc_html__('Load more messages', 'whizz-chat') . "</a></footer>";
            $load_more = ($list_html != "" ) ? "" : $no_chat_message;
            $html_body = "<div class='whizz-chat-list'>
                          <div class='whizz-dash-chat-body'>
                            <div class='search'> <input placeholder='" . esc_html__('Search chat', 'whizz-chat') . "' type='text' class='form-control chat-search'></div>
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
            return $load_more = ($list_html != "" ) ? $list_html : $no_chat_message;
            //return $list_html;
            $hide_list_class = isset($_COOKIE['whizzChat_list_close']) && $_COOKIE['whizzChat_list_close'] == 'hide' ? ' chatlist-min' : '';
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
                   <a href="javascript:void(0)" class="whizz-chat-list-close"><i class="fa fa-angle-down"></i></a>    
                 </div>
              </div>
            <div class="chat-messages">' . $html_body . '</div>
            </div>';
            return $list_html;
        }

    }
    new WhizzChat_Dashboard_Html();
}
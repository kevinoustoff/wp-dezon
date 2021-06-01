<?php
/*
 * Functionality of whizz chat Bot
 */

class whizzChat_Bot {

    private $session;

    public function __construct() {
        add_action('whizzchat_bot', array($this, 'whizzchat_bot_html'), 10, 1);
        add_action('init', array($this, 'whizzchat_chatbox_bot_callback'), 0);
        add_action('admin_menu', array($this, 'whizzchat_external_link'));
        add_action('whizzchat-bot_edit_form_fields', array($this, 'whizzchat_bot_editing_fields_callback'), 999, 2);
        add_action('edit_whizzchat-bot', array($this, 'whizzchat_bot_save_callback'), 999);
    }

    public function whizzchat_external_link() {
        global $submenu;
        $permalink = admin_url('edit-tags.php') . '?taxonomy=whizzchat-bot';
        $submenu['whizzChat-menu'][] = array('WhizzChat Bot', 'manage_options', $permalink);
    }

    public function whizzchat_chatbox_bot_callback() {

        $labels = array(
            'name' => _x('WhizzChat Bot', 'taxonomy general name', 'whizz-chat'),
            'singular_name' => _x('WhizzChat Bot', 'taxonomy singular name', 'whizz-chat'),
            'search_items' => esc_html__('Search WhizzChat Bot', 'whizz-chat'),
            'all_items' => esc_html__('All WhizzChat Bot', 'whizz-chat'),
            'parent_item' => esc_html__('Parent WhizzChat Bot', 'whizz-chat'),
            'parent_item_colon' => esc_html__('Parent WhizzChat Bot:', 'whizz-chat'),
            'edit_item' => esc_html__('Edit WhizzChat Bot', 'whizz-chat'),
            'update_item' => esc_html__('Update WhizzChat Bot', 'whizz-chat'),
            'add_new_item' => esc_html__('Add New WhizzChat Bot', 'whizz-chat'),
            'new_item_name' => esc_html__('New WhizzChat Bot Name', 'whizz-chat'),
            'menu_name' => esc_html__('WhizzChat Bot', 'whizz-chat'),
        );
        $args = array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'show_in_menu' => false,
        );
        register_taxonomy('whizzchat-bot', 'post', $args);
    }

    public function whizzchat_bot_save_callback($term_id) {

        if (isset($_POST) && isset($_POST['action_data'])) {
            update_term_meta($term_id, 'action_data', $_POST['action_data']);
        } else {
            update_term_meta($term_id, 'action_data', "");
        }
        if (isset($_POST) && isset($_POST['action_type'])) {
            update_term_meta($term_id, 'action_type', $_POST['action_type']);
        } else {
            update_term_meta($term_id, 'action_type', "");
        }
    }

    public function whizzchat_bot_editing_fields_callback($taxonomy) {
        global $whizzChat_options;
        $bot_js_data = '';
        $whizzChat_options = get_option('whizz-chat-options');
        $whizzChat_res_limit = isset($whizzChat_options['whizzChat-res-msg-limit']) && $whizzChat_options['whizzChat-res-msg-limit'] != '' ? $whizzChat_options['whizzChat-res-msg-limit'] : '10';

        $action_data = get_term_meta($taxonomy->term_id, 'action_data', true);
        $action_data = isset($action_data) && $action_data != '' ? $action_data : array();

        $action_type = get_term_meta($taxonomy->term_id, 'action_type', true);
        $action_type = isset($action_type) && $action_type != '' ? $action_type : array();

        $fields_html = '';
        if (isset($action_data) && is_array($action_data) && sizeof($action_data) > 0) {
            $counter_fields = 0;
            foreach ($action_data as $each_field) {

                $text_selected = '';
                $url_selected = '';
                $html_selected = '';
                $video_selected = '';
                $img_selected = '';
                if ($action_type[$counter_fields] == 'text') {
                    $text_selected = ' selected ';
                } elseif ($action_type[$counter_fields] == 'url') {
                    $url_selected = ' selected ';
                } elseif ($action_type[$counter_fields] == 'html') {
                    $html_selected = ' selected ';
                } elseif ($action_type[$counter_fields] == 'video') {
                    $video_selected = ' selected ';
                } elseif ($action_type[$counter_fields] == 'image') {
                    $img_selected = ' selected ';
                }
                $fields_html .= '<div class="whizzchat-res-field"><table>'
                        . '<td><input placeholder="' . esc_html__('Response Message', 'whizz-chat') . '" type="text" name="action_data[]" value="' . $each_field . '"></td>'
                        . '<td><select name="action_type[]">'
                        . '<option value="">' . esc_html__('Type of Message', 'whizz-chat') . '</option>'
                        . '<option value="text"' . $text_selected . '>' . esc_html__('text', 'whizz-chat') . '</option>'
                        . '<option value="url"' . $url_selected . '>' . esc_html__('URL', 'whizz-chat') . '</option>'
                        . '<option value="html"' . $html_selected . '>' . esc_html__('HTML', 'whizz-chat') . '</option>'
                        . '<option value="video"' . $video_selected . '>' . esc_html__('Video', 'whizz-chat') . '</option>'
                        . '<option value="image"' . $img_selected . '>' . esc_html__('Image', 'whizz-chat') . '</option>'
                        . '</select></td>';

                if ($counter_fields == 0) {
                    $fields_html .= ' <td><button class="btn btn-sm btn-primary add_more_button">' . esc_html__('Add More Fields', 'whizz-chat') . '</button></td></table></div> ';
                } else {
                    $fields_html .= ' <td><a href="#" class="remove_field" style="margin-left:10px;">' . esc_html__('Remove', 'whizz-chat') . '</a></td></table></div> ';
                }
                $counter_fields++;
            }
        } else {
            $fields_html .= '<div class="whizzchat-res-field"><table><td><input placeholder="' . esc_html__('Response Message', 'whizz-chat') . '" type="text" name="action_data[]"></td><td><select name="action_type[]"><option value="">' . esc_html__('Type of Message', 'whizz-chat') . '</option><option value="text">' . esc_html__('text', 'whizz-chat') . '</option><option value="url">' . esc_html__('URL', 'whizz-chat') . '</option><option value="html">' . esc_html__('HTML', 'whizz-chat') . '</option><option value="video">' . esc_html__('Video', 'whizz-chat') . '</option><option value="image">' . esc_html__('Image', 'whizz-chat') . '</option></select></td><td><button class="btn btn-sm btn-primary add_more_button">' . esc_html__('Add More Fields', 'whizz-chat') . '</button></td></table></div>';
        }


        $response_fields = ' <tr class="form-field whizzchatbot-wrap-admin">
        <th scope="row" valign="top"><label>' . esc_html__('Add Response Messages', 'whizz-chat') . '</label></th>
            <td class="whizzchat-response-container">
                ' . $fields_html . '
            </td>
        </tr> <tr class="form-field whizzchatbot-note"><td></td><td class="form-field" colspan="2">' . esc_html__('Please add the response messages according to the message type and format.<br />', 'whizz-chat') . '</td></tr>';
        echo whizzChat_return($response_fields);
        ?>
        <script>
            jQuery(document).ready(function () {
                var max_fields_limit = '<?php echo whizzChat_return($whizzChat_res_limit);?>';
                var x = 1;
                jQuery('.add_more_button').click(function (e) {
                    e.preventDefault();
                    if (x < max_fields_limit) {
                        x++;
                        jQuery('.whizzchat-response-container').append('<div class="whizzchat-res-field"><table><td><input placeholder="<?php echo esc_html__('Response Message', 'whizz-chat');?>" type="text" name="action_data[]"/></td><td><select name="action_type[]"><option value=""><?php echo esc_html__('Type of Message', 'whizz-chat');?></option><option value="text"><?php echo esc_html__('text', 'whizz-chat');?></option><option value="url"><?php echo esc_html__('URL', 'whizz-chat');?></option><option value="html"><?php echo esc_html__('HTML', 'whizz-chat');?></option><option value="video"><?php echo esc_html__('Video', 'whizz-chat');?></option><option value="image"><?php echo esc_html__('Image', 'whizz-chat');?></option></select></td><td><a href="#" class="remove_field" style="margin-left:10px;"><?php echo esc_html__('Remove', 'whizz-chat');?></a></td></table></div>'); //add input field
                    }
                });
                jQuery('.whizzchat-response-container').on("click", ".remove_field", function (e) {
                    e.preventDefault();
                    jQuery(this).closest('.whizzchat-res-field').remove();
                    jQuery(this).closest('.whizzchat-res-field').hide('slow', function () {
                        jQuery(this).closest('.whizzchat-res-field').remove();
                    });
                    x--;
                })
            });
        </script>
        <?php
    }

    public function whizzchat_bot_html($bot_data = array()) {
        global $whizzChat_options;

        // session
        extract($bot_data);

        $this->session = whizzChat::session_id();

        $whizzChat_options = get_option('whizz-chat-options');
        $whizzChat_copyright = isset($whizzChat_options['whizzChat-bot-copyright']) && $whizzChat_options['whizzChat-bot-copyright'] ? TRUE : FALSE;

        $copyright_html = '';
        if ($whizzChat_copyright) {
            $whizzChat_copyright_text = isset($whizzChat_options['whizzchat-bot-copyright-text']) && $whizzChat_options['whizzchat-bot-copyright-text'] != '' ? $whizzChat_options['whizzchat-bot-copyright-text'] : '';
            if ($whizzChat_copyright_text != '') {
                $copyright_html .= '<div class="whizzchat-bot-footer"><p> ' . $whizzChat_copyright_text . ' </p></div>';
            }
        }

        $whizzchat_admin_page = $whizzChat_options["whizzChat-admin-page"];
        $whizzchat_bot_header_text = $whizzChat_options["whizzChatbot-head-text"];
        $whizzchat_bot_header_text = isset($whizzchat_bot_header_text) && $whizzchat_bot_header_text != '' ? $whizzchat_bot_header_text : esc_html__('WhizzChat Bot', 'whizz-chat');
        
        $whizzchat_bot_header_color = $whizzChat_options["whizzchatbot-header-color"];
        $whizzchat_bot_header_bgcolor = isset($whizzchat_bot_header_color) && $whizzchat_bot_header_color != '' ? ' style="background-color:'.$whizzchat_bot_header_color.'"' : '#000';
        
        $whizzchat_bot_header_txtcolor = $whizzChat_options["whizzchatbot-header-text-color"];
        $whizzchat_bot_header_txtcolor = isset($whizzchat_bot_header_txtcolor) && $whizzchat_bot_header_txtcolor != '' ? ' style="color:'.$whizzchat_bot_header_txtcolor.'"' : '#000';

        $admin_chat_id = isset($_COOKIE['Whizz_Admin_Chat_id']) && $_COOKIE['Whizz_Admin_Chat_id'] != '' ? $_COOKIE['Whizz_Admin_Chat_id'] : 0;
        $admin_chat_box_html = '';
        if ($admin_chat_id != 0 && $session != '') {
            $admin_chat_box_html .= '<div class="whizzchat-short-quick"><a onclick="return open_whizz_chat(' . $admin_chat_id . ')" href="javascript:void(0);"> ' . esc_html__('Admin ChatBox', 'whizz-chat') . ' </a></div>';
        }

        $html = '';
        $image_id = '';
        $html .= '<div class="chatbox-holder-bot" data-session="' . $this->session . '" data-admin-id="' . $admin_chat_id . '" data-page-id="' . $whizzchat_admin_page . '">
                    <div class="chatbox-inner-holder-bot">
                        <div class="individual-chat-box">
                            <div class="chatbox group-chat whizz-chat-bot">
                                <div class="chatbox-top"'.$whizzchat_bot_header_bgcolor.'>
                                    <div class="chat-group-name"> 
                                        <div class="whizzChat-author-meta whizz-chat-text-nowrap">
                                            <span class="whizzchat-bot-img"><img src="' . plugins_url('whizz-chat') . '/assets/images/bot.svg" alt="'.esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', TRUE)).'" /></span>
                                            <span class="whizzChat-ad-title" title="Admin"'.$whizzchat_bot_header_txtcolor.'>' .$whizzchat_bot_header_text. '</span>
                                            <span class="whizzChat-author-name"'.$whizzchat_bot_header_txtcolor.'> <span class="status online"></span>' . esc_html__(' Online', 'whizz-chat') . '</span>
                                        </div>
                                    </div>
                                    <div class="chatbox-icons">
                                        <a href="javascript:void(0)" class="whizzchat-minimize"><i class="fa fa-minus"></i></a>  
                                        <a href="javascript:void(0);" class="whizzchat-bot-close"><i class="fa fa-close"></i></a>
                                    </div>
                                </div>
                                <div class="chat-messages">';

        $html .= '<div class="botui-app-container">
                    <div id="whizzchatbot-ui-box">
                        <whizzchatbot-ui></whizzchatbot-ui>
                    </div>
                </div>';

        $html .= '</div>
            ' . $admin_chat_box_html . '
                    ' . $copyright_html . '
                    </div>
                </div>   
            </div>
        </div>';

        echo ($html . $this->whizzchat_render_bot_chat());
    }

    public function whizzzchat_terms_hierarchically(Array &$cats, Array &$into, $parentId = 0) {
        foreach ($cats as $i => $cat) {
            if ($cat->parent == $parentId) {
                $into[$cat->term_id] = $cat;
                unset($cats[$i]);
            }
        }
        foreach ($into as $topCat) {
            $topCat->children = array();
            $this->whizzzchat_terms_hierarchically($cats, $topCat->children, $topCat->term_id);
        }
    }

    public function whizzchat_get_response_data($term = 0) {

        $term_id = $term->term_id;
        if ($term_id == '' || $term_id == 0)
            return '';

        $action_resp_data = '';
        $term_tesc = strip_tags(term_description($term_id, 'whizzchat-bot'));
        $term_tesc = preg_replace("/\r|\n/", "", $term_tesc);
        $has_desc = FALSE;
        if ($term_tesc != '') {
            $has_desc = TRUE;
            $action_resp_data .= 'return whizzbotui.message.bot({loading: true,delay: 1500,content: " ' . $term_tesc . ' "  })';
        }
        $action_data = get_term_meta($term_id, 'action_data', true);
        $action_type = get_term_meta($term_id, 'action_type', true);
        $action_data = isset($action_data) && $action_data != '' ? $action_data : array();



        if (isset($action_data) && is_array($action_data) && sizeof($action_data) > 0) {
            $counter_fields = 0;
            $action_data = array_filter($action_data); 
            foreach ($action_data as $each_field) {

                if ($has_desc) {
                    $action_resp_data .= '.then(function () { ';
                } else {
                    if ($each_field == '') {
                        continue;
                    }
                    if ($counter_fields == 0) {
                    } else {
                        $action_resp_data .= '.then(function () { ';
                    }
                }
                if ($action_type[$counter_fields] == 'text') {
                    $action_resp_data .= 'return whizzbotui.message.add({loading: true,delay: 1500,content: " ' . $each_field . ' "  })';
                } elseif ($action_type[$counter_fields] == 'url') {
                    $url_embed = '[' . $each_field . '](' . $each_field . ')';
                    $action_resp_data .= 'return whizzbotui.message.add({loading: true,delay: 1500,content: " ' . $url_embed . ' "  })';
                } elseif ($action_type[$counter_fields] == 'html') {
                    $action_resp_data .= 'return whizzbotui.message.add({loading: true,delay: 1500,type: "html",content: " ' . $each_field . ' "  })';
                } elseif ($action_type[$counter_fields] == 'video') {
                    parse_str(parse_url($each_field, PHP_URL_QUERY), $video_slices);
                    $video_embed = 'https://www.youtube.com/embed/' . $video_slices['v'];
                    $action_resp_data .= 'return whizzbotui.message.add({loading: true,delay: 1500, type: "embed",content: " ' . $video_embed . ' "  })';
                } elseif ($action_type[$counter_fields] == 'image') {
                    $img_embed = '![whizzchatbot image](' . $each_field . ')';
                    $action_resp_data .= 'return whizzbotui.message.add({loading: true,delay: 1500,content: " ' . $img_embed . ' "  })';
                } else {
                    $action_resp_data .= 'return whizzbotui.message.add({loading: true,delay: 1500,content: " ' . $each_field . ' "  })';
                }


                if ($has_desc) {
                    $action_resp_data .= '})';
                } else {
                    if ($counter_fields > 0) {
                        $action_resp_data .= '})';
                    }
                }
                $counter_fields++;
            }
        }

        if (isset($term->children) && $term->children != '' && is_array($term->children) && sizeof($term->children) > 0) {
            $action_resp_data .= $this->whizzchat_render_bot_chat_arr('', $term->children);
        }
        return $action_resp_data;
    }

    public function whizzchat_render_bot_chat_arr($bot_js_data = '', $whizzchatbot_data) {
        if (isset($whizzchatbot_data) && $whizzchatbot_data != '' && is_array($whizzchatbot_data)) {
            $action_data = '';
            $action_counter = 0;
            $response_data = '';
            foreach ($whizzchatbot_data as $key => $whizzchatbot) {
                $resp_messages = $this->whizzchat_get_response_data($whizzchatbot);
                $action_data .= '{text: "' . $whizzchatbot->name . '",value: "' . $whizzchatbot->slug . '"}';
                $response_data .= ' if(res.value == "' . $whizzchatbot->slug . '") { ' . $resp_messages . ' }';
                if (count($whizzchatbot_data) > $action_counter) {
                    $action_data .= ', ';
                }
                $action_counter++;
            }
            $bot_js_data .= '.then(function () {
                                            return whizzbotui.action.button({
                                                delay: 1500,
                                                loading: true,
                                                addMessage: true,
                                                action: [' . $action_data . ']
                                            })
                                        }).then(function (res) {
                                           ' . $response_data . '
                                        })';
        }
        return $bot_js_data;
    }

    public function whizzchat_render_bot_chat() {
        global $whizzChat_options, $wp_query;

        $bot_js_data = '';
        $whizzChat_options = get_option('whizz-chat-options');
        $whizzChat_intro_msgs = isset($whizzChat_options['whizzChat-intro-msgs']) && $whizzChat_options['whizzChat-intro-msgs'] != '' ? $whizzChat_options['whizzChat-intro-msgs'] : '';
        $whizzChat_intro_msgs_arr = array();
        if ($whizzChat_intro_msgs != '') {
            $whizzChat_intro_msgs_arr = explode("|", $whizzChat_intro_msgs);
        }
        if (isset($whizzChat_intro_msgs_arr) && !empty($whizzChat_intro_msgs_arr) && is_array($whizzChat_intro_msgs_arr) && sizeof($whizzChat_intro_msgs_arr) > 0) {
            $intro_counter = 0;
            foreach ($whizzChat_intro_msgs_arr as $message) {
                $message = strip_tags($message);
                $message = preg_replace("/\r|\n/", "", $message);
                if ($message != '') {
                    if ($intro_counter == 0) {
                        $bot_js_data .= 'whizzbotui.message.bot({
                                        content: "' . $message . '",
                                        loading: true,
                                        delay: 1500,
                                    })';
                    } else {
                        $bot_js_data .= '.then(function () {
                                if(repeat){
                                    return whizzbotui.message.bot({
                                          loading: true,
                                         delay: 1500,
                                         content: "' . $message . '",
                                      });
                                 }
                                 })';
                    }
                }
                $intro_counter++;
            }
        }
        $categories = get_terms('whizzchat-bot', array('hide_empty' => false));
        $whizzchatbot_data = array();
        $this->whizzzchat_terms_hierarchically($categories, $whizzchatbot_data);
        $bot_js_data = $this->whizzchat_render_bot_chat_arr($bot_js_data, $whizzchatbot_data);

        $whizzchat_admin_page = $whizzChat_options["whizzChat-admin-page"];
        $whizzchat_admin_val = $whizzChat_options["whizzChat-admin-value"];


        $current_session = $this->session;


        $admin_chat_id = isset($_COOKIE['Whizz_Admin_Chat_id']) && $_COOKIE['Whizz_Admin_Chat_id'] != '' ? $_COOKIE['Whizz_Admin_Chat_id'] : 0;
        $whizzChat_name = isset($_COOKIE['whizzChat_name']) && $_COOKIE['whizzChat_name'] != '' ? $_COOKIE['whizzChat_name'] : '';


        $whizz_token = md5($_SERVER['HTTP_HOST']) . '_' . 'whizchat';

        $start_chat_data = '';
        if ($admin_chat_id != 0) {
            $clicked = "'" . $admin_chat_id . "'";
            $start_chat_data = ' open_whizz_chat("' . $admin_chat_id . '");';
        } else {

            if (isset($current_session) && $current_session != '') { // for already logged-in (session-created) user
                $start_chat_data = 'var whizzChat_name = "' . $whizzChat_name . '";
                                var nonce_val = "' . wp_create_nonce('wp_rest') . '";
                                var post_id = "' . $whizzchat_admin_page . '";
                                var chat_id = "";
                                var client_data = {
                                    action: "whizzChat_initiate_chat",
                                    whizzChat_name: whizzChat_name,
                                    whizzChat_email: "",
                                    url: window.location.href,
                                    nonce: nonce_val,
                                    post_id: post_id,
                                    chat_id: chat_id,
                                    session_type:"chat_bot",
                                };
                                
                                var json_end_point = "' . get_rest_url('', 'whizz-chat-api/v1') . '/start-session";
                                $.ajax({
                                    type: "POST",
                                    action: "whizzChat_initiate_chat",
                                    url: json_end_point,
                                    data: client_data,
                                    dataType: "json",
                                    crossDomain: true,
                                    cache: false,
                                    async: true,
                                    beforeSend: function (xhr) {
                                        xhr.setRequestHeader("X-WP-Nonce", nonce_val);
                                    },
                                }).done(function (response) {
                                    if (response.whizz_cookie_data != "") {
                                            $.each(response.whizz_cookie_data, function (index, whizz_cooki) {
                                                whizzchat_setCookie(whizz_cooki.key, whizz_cooki.value, whizz_cooki.time);
                                            });
                                    }
                                    if(response.chat_id !== "undefined" && response.chat_id != ""){
                                        whizzchat_setCookie("Whizz_Admin_Chat_id", response.chat_id, 30); 
                                    }
                                    jQuery("div.chatbox-holder .chatbox-inner-holder").append(response.html_data);
                                    var whizzchat_live_enable = $("#whizz-chat-live").val();
                                    jQuery("[data-chat-id=\'"+response.chat_id+"\'] .whizzChat-emoji").emojioneArea({
                                        pickerPosition: "top",
                                        filtersPosition: "bottom",
                                        tones: false,
                                        spellcheck: true,
                                        autocomplete: false,
                                        hidePickerOnBlur: true,
                                        saveEmojisAs: "unicode",
                                        placeholder: "Type something here",
                                        events: {
                                            focus: function (editor, event) {
                                            var chat_id = editor.parent().parent().attr("data-chat-id");
                                                var user_name = editor.parent().parent().attr("data-user-name");
                                                var msg = "";
                                                var room = editor.parent().parent().attr("data-room");
                                                if (typeof whizzchat_live_enable != "undefined" && whizzchat_live_enable == "1") {
                                                    socket.emit("agTyping", room, msg, chat_id); 
                                                }

                                            },
                                            blur: function (editor, event) {
                                            var chat_id = editor.parent().parent().attr("data-chat-id");
                                                var room = editor.parent().parent().attr("data-room");
                                                if (typeof whizzchat_live_enable != "undefined" && whizzchat_live_enable == "1") {
                                                    socket.emit("agStopTyping", room, chat_id);
                                                }
                                            },
                                        }
                                    });
                                   
                                });';
            } else {

                $start_chat_data = 'return whizzbotui.action.text({
                                        action: {
                                          placeholder: "Enter Your Name",
                                        }
                                      }).then(function (res) {
                                      var nonce_val = "' . wp_create_nonce('wp_rest') . '";
                                      var whizzChat_name = res.value;
                                        var post_id = "' . $whizzchat_admin_page . '";
                                        var chat_id = "' . $admin_chat_id . '";
                                        var client_data = {
                                            action: "whizzChat_initiate_chat",
                                            whizzChat_name: whizzChat_name,
                                            whizzChat_email: "",
                                            url: window.location.href,
                                            nonce: nonce_val,
                                            post_id: post_id,
                                            chat_id: chat_id,
                                            session_type:"chat_bot",
                                        };
                                        var json_end_point = "' . get_rest_url('', 'whizz-chat-api/v1') . '/start-session";
                                        $.ajax({
                                            type: "POST",
                                            action: "whizzChat_initiate_chat",
                                            url: json_end_point,
                                            data: client_data,
                                            dataType: "json",
                                            crossDomain: true,
                                            cache: false,
                                            async: true,
                                            beforeSend: function (xhr) {
                                                xhr.setRequestHeader("X-WP-Nonce", nonce_val);
                                            },
                                        }).done(function (response) {
                                            
                                            if (response.whizz_cookie_data != "") {
                                                    $.each(response.whizz_cookie_data, function (index, whizz_cooki) {
                                                        whizzchat_setCookie(whizz_cooki.key, whizz_cooki.value, whizz_cooki.time);
                                                    });
                                            }
                                            if(response.chat_id !== "undefined" && response.chat_id != ""){
                                                whizzchat_setCookie("Whizz_Admin_Chat_id", response.chat_id, 30); 
                                            }
                                            jQuery("div.chatbox-holder .chatbox-inner-holder").append(response.html_data);
                                            var whizzchat_live_enable = $("#whizz-chat-live").val();
                                            
                                            jQuery("[data-chat-id=\'"+response.chat_id+"\'] .whizzChat-emoji").emojioneArea({
                                                pickerPosition: "top",
                                                filtersPosition: "bottom",
                                                tones: false,
                                                spellcheck: true,
                                                autocomplete: false,
                                                hidePickerOnBlur: true,
                                                saveEmojisAs: "unicode",
                                                placeholder: "Type something here",
                                                events: {
                                                    focus: function (editor, event) {
                                                    var chat_id = editor.parent().parent().attr("data-chat-id");
                                                        var user_name = editor.parent().parent().attr("data-user-name");
                                                        var msg = "";
                                                        var room = editor.parent().parent().attr("data-room");
                                                        if (typeof whizzchat_live_enable != "undefined" && whizzchat_live_enable == "1") {
                                                            socket.emit("agTyping", room, msg, chat_id); 
                                                        }

                                                    },
                                                    blur: function (editor, event) {
                                                    var chat_id = editor.parent().parent().attr("data-chat-id");
                                                       var room = editor.parent().parent().attr("data-room");
                                                        if (typeof whizzchat_live_enable != "undefined" && whizzchat_live_enable == "1") {
                                                            socket.emit("agStopTyping", room, chat_id);
                                                        }
                                                    },
                                                }
                                            });
                                            
                                        });
                                          
                                      });';
            }
        }




        $bot_js_data .= '.then(function () {
                            return whizzbotui.action.button({
                                delay: 1500,
                                loading: true,
                                addMessage: true,
                                action: [{text: "Go to Main Menu",value: "back"},{text: "All Done!",value: "ok"},{text: "Start Chat",value: "chat_start"}]
                            })
                        }).then(function (res) {
                                if (res.value == "back") {
                                   whizzchat_bot_loading(false);
                                 } else if (res.value == "ok") {
                                     return whizzbotui.message.add({
                                         loading: true,
                                         delay: 1500,
                                         content: "!(check) Ok Have A Nace Day",
                                     });
                                 } else if (res.value == "chat_start") {
                                      ' . $start_chat_data . '
                                 }
                        });';

        $render_js_bot = '';
        ob_start();
        ?>
        <script>
            jQuery(function ($) {
                var whizzbotui = new BotUI('whizzchatbot-ui-box');
                function whizzchat_bot_loading(repeat) {
                    repeat = typeof repeat === 'undefined' ? true : false;
        <?php echo ($bot_js_data);?>
                }
                whizzchat_bot_loading();
            });
        </script>
        <?php
        $render_js_bot = ob_get_contents();
        ob_end_clean();
        return $render_js_bot;
    }

}
new whizzChat_Bot();
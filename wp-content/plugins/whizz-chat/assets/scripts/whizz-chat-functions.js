/*
 * 
 * Whizz Chat Functions
 * 
 */
var $ = jQuery;
var whizzchat_live_enable = jQuery("#whizz-chat-live").val();
var whizzchat_dashboard = jQuery("#whizzchat-dashboard").val();

/*
 * functions to save active tab in cookie
 */


function whizzchat_setCookie(key, value, expiry) {
    var expires = new Date();
    var value = whizzchat_strip_html(value);
    var key = whizzchat_strip_html(key);
    expires.setTime(expires.getTime() + (expiry * 24 * 60 * 60 * 1000));
    document.cookie = key + "=" + value + ";" + expires + ";path=/";
}
function whizzchat_getCookie(key) {
    var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
    return keyValue ? keyValue[2] : null;
}
function whizzchat_eraseCookie(key) {
    var keyValue = whizzchat_getCookie(key);
    var keyValue = whizzchat_strip_html(keyValue);
    var key = whizzchat_strip_html(key);
    whizzchat_setCookie(key, keyValue, '-1');
}
function whizz_user_token_js(user_token) {
    if (typeof user_token === 'undefined' || user_token == null || user_token == '') {
        var whizz_cookie_name = whizzchat_getCookie('whizzChat_name');
        if (typeof whizz_cookie_name !== 'undefined' && whizz_cookie_name != null) {
            var cookie_name = 'whizchat-' + whizz_cookie_name.replace(' ', '-', whizz_cookie_name);
            user_token = whizzchat_getCookie(cookie_name);
        }
    }
    return user_token;
}
/*
 * functions to save active tab in cookie
 */


jQuery(function ($) {

    $(document).ready(function () {

        jQuery('.individual-chat-box.whizzchat-temp-section .chatbox.group-chat').addClass('chatbox-min');


        $('body').on('DOMSubtreeModified', '.chatbox-inner-list', function (e) {

            if ($(".chatbox-inner-list li.chatlist-message-alert").length)
            {
                $("div.chatbox-inner-list div.chatbox-list div.chatbox-top").addClass('chatbox-unread-message');
            } else
            {
                $("div.chatbox-inner-list div.chatbox-list div.chatbox-top").removeClass('chatbox-unread-message');
            }
        });


        if ($(".chatbox-inner-list li.chatlist-message-alert").length)
        {

            $(document).on('click', '.chatbox-inner-list li.chatlist-message-alert', function () {
                $(this).find('span.chat-badge-count').remove();
            });
        }

        $(".whizz-admin-bot").on("click", function () {

            if (jQuery('div.chatbox-holder .chatbox-inner-holder').find('.chatbox-holder-bot').length !== 0) {
                return false;
            }
            var session_id = whizz_user_token_js(whizzChat_ajax_object.whizz_user_token);



            var client_data = {
                session: session_id,
                nonce: whizzChat_ajax_object.nonce,
            };


            var json_end_point = whizzChat_ajax_object.whizz_restapi_endpoint + '/get-admin-bot';
            jQuery.ajax({
                type: 'POST',
                action: 'whizzChat_get_Bot',
                url: json_end_point,
                data: client_data,
                dataType: 'json',
                crossDomain: true,
                cache: false,
                async: true,
                xhrFields: {withCredentials: true},
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', whizzChat_ajax_object.nonce);
                },
            }).done(function (data) {

                 
                 console.log(data);

                if (data) {
                    jQuery('div.chatbox-holder .chatbox-inner-holder').append(data);
                }


            });
        });

        $(".whizzChat-more-boxes").on("click", function () {
            $('.popover__wrapper').find('.popover__content').toggleClass('pophover-min');
        });

    });

    $("body").delegate(".chat-input-holder", "click", function () {

        var chat_id = jQuery(this).parents().filter(function () {
            return jQuery(this).data("chat-id");
        }).eq(0).data("chat-id");

        var post_id = jQuery(this).parents().filter(function () {
            return jQuery(this).data("post-id");
        }).eq(0).data("post-id");
        var message_id = jQuery('#' + chat_id + ' div.message-box-holder:last').data('chat-unique-id');
        var is_seen = jQuery('#' + chat_id + ' div.message-box-holder:last').data('chat-last-seen');
        if (is_seen == "")
        {
            jQuery('#' + chat_id + ' div.message-box-holder:last').data('chat-last-seen', '-');
        }

        jQuery('.chatbox-inner-list .whizz-chat-list').find('#chat-badge-' + chat_id + '').remove();
        jQuery('.chatbox-inner-list .whizz-chat-list').find('li#' + chat_id + '').removeClass('chatlist-message-alert');
        if (jQuery(".chatbox-inner-list li.chatlist-message-alert").length)
        {
            jQuery("div.chatbox-inner-list div.chatbox-list div.chatbox-top").addClass('chatbox-unread-message');

        } else
        {
            jQuery("div.chatbox-inner-list div.chatbox-list div.chatbox-top").removeClass('chatbox-unread-message');
        }

        var client_data = {
            session: whizzChat_ajax_object.whizz_user_token,
            nonce: whizzChat_ajax_object.nonce,
            chat_id: chat_id,
            post_id: post_id,
            message_id: message_id
        };
        var json_end_point = whizzChat_ajax_object.whizz_restapi_endpoint + '/read-chat';
        jQuery.ajax({
            type: 'POST',
            action: 'whizzChat_read_chat',
            url: json_end_point,
            data: client_data,
            dataType: 'json',
            crossDomain: true,
            cache: false,
            async: true,
            xhrFields: {withCredentials: true},
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', whizzChat_ajax_object.nonce);
            },
        }).done(function (data) {
        });
    });

    var whizzchat_live_enable = $("#whizz-chat-live").val();
    whizzchat_live_enable = typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable != '' ? whizzchat_live_enable : '';
    var whizzchat_live_enabled = typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1' ? true : false;

    $("body").delegate("a.whizzchat-minimize", "click", function () {
        jQuery(this).closest('.chatbox').toggleClass('chatbox-min');
    });

    $("body").delegate("a.whizzchat-minimize", "click", function () {
        var ttt = $(this).parent().parent().parent().parent().attr('id');
        var whizz_cookie_name = 'whizzChat_hide_my_box_' + ttt;

        if (whizzchat_getCookie(whizz_cookie_name) == 'show') {
            whizzchat_setCookie(whizz_cookie_name, 'hide', 30);
        } else if (whizzchat_getCookie(whizz_cookie_name) == 'hide') {
            whizzchat_setCookie(whizz_cookie_name, 'show', 30);
        } else {
            whizzchat_setCookie(whizz_cookie_name, 'hide', 30);
        }
    });
    $(".chatbox-top a.whizzchat-minimize").each(function () {
        var ttt = $(this).parent().parent().parent().parent().attr('id');
        var whizz_cookie_name = 'whizzChat_hide_my_box_' + ttt;
        if (whizzchat_getCookie(whizz_cookie_name) == 'hide') {
            $(this).parent().parent().parent().addClass('chatbox-min');
        }
    });

    $("body").delegate("a.whizz-chat-list-close", "click", function () {
        var whizz_cookie_name = 'whizzChat_list_close';
        if (whizzchat_getCookie(whizz_cookie_name) == 'show') {
            whizzchat_setCookie(whizz_cookie_name, 'hide', 30);
        } else if (whizzchat_getCookie(whizz_cookie_name) == 'hide') {
            whizzchat_setCookie(whizz_cookie_name, 'show', 30);
        } else {
            whizzchat_setCookie(whizz_cookie_name, 'hide', 30);
        }
    });

    var whizz_list_close = 'whizzChat_list_close';
    if (whizzchat_getCookie(whizz_list_close) == 'hide') {
        $(".chatbox-holder .chatbox-inner-list .chatbox").addClass('chatlist-min');
    }
    //chat-search
    var typingTimer;
    var doneTypingInterval = 1000;
    $(document).on('keyup', '.chat-search', function () {

        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTyping, doneTypingInterval);


    });
    $(document).on('keydown', '.chat-search', function () {
        clearTimeout(typingTimer);
    });

    function doneTyping(chat_obj) {

        var keyword = jQuery('.chat-search').val();
        var style = jQuery('.chat-search').attr('data-style');

        var whizzChat_email = '';
        var client_data = {
            searchkeyword: keyword,
            style: style,
            session: whizzChat_ajax_object.whizz_user_token,
            nonce: whizzChat_ajax_object.nonce
        };

        if (typeof style !== 'undefined' && style == 'dashboard') {
            $('.dashb-search-loader').html('<i class="fas fa-spinner fa-spin"></i>');
        } else {
            $('.whizz-search-loader').html('<i class="fas fa-spinner fa-spin"></i>');
        }

        var json_end_point = whizzChat_ajax_object.whizz_restapi_endpoint + '/search-keyword';
        var _nonce = whizzChat_ajax_object.whizz_site_nonce;
        var _nonce_rest = whizzChat_ajax_object.whizz_restapi_nonce;
        $.ajax({
            type: 'POST',
            action: 'whizzChat_end_chat',
            url: json_end_point,
            data: client_data,
            dataType: 'json',
            async: true,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', whizzChat_ajax_object.nonce);
            },
        }).done(function (data) {

            if (typeof data !== 'undefined' && data != '') {

                if (typeof style !== 'undefined' && style == 'dashboard') {
                    $('.dashb-search-loader').html('');
                    $(".chats-tab-open aside.whizz-sidebar ul.contacts-list").html(data);
                    jQuery('.chat-search').val(keyword);
                } else {
                    jQuery('.chatbox-inner-list').html(data);
                    jQuery('.chat-search').val(keyword);
                }

            }

        });
    }

    $(document).on('click', '.whizz-chat-body .indivisual-chat-area', function () {
        var chat_id = jQuery(this).attr('data-chat-id');
        jQuery(this).find('span.badge-light').remove();
    });



    $(document).on('click', '.logout-chat-session', function () {

        var leave_chat_id = $(this).data('leave-chat-id');
        var whizzChat_name = '';
        var whizzChat_email = '';
        var client_data = {
            action: 'whizzChat_end_chat',
            cid: leave_chat_id,
            whizzChat_name: whizzChat_name,
            whizzChat_email: whizzChat_email,
            url: window.location.href,
            session: whizzChat_ajax_object.whizz_user_token,
            nonce: whizzChat_ajax_object.nonce
        };

        var json_end_point = whizzChat_ajax_object.whizz_restapi_endpoint + '/end-session';
        var _nonce = whizzChat_ajax_object.whizz_site_nonce;
        var _nonce_rest = whizzChat_ajax_object.whizz_restapi_nonce;
        $.ajax({
            type: 'POST',
            action: 'whizzChat_end_chat',
            url: json_end_point,
            data: client_data,
            dataType: 'json',
            async: true,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', whizzChat_ajax_object.nonce);
            },
        }).done(function (data) {
            if (typeof data !== 'undefined') {
                $(".chatbox-holder .chatbox-inner-holder div[data-chat-id=" + leave_chat_id + "]").remove();
                $(".chatbox-holder .chatbox-inner-list .chat-messages .whizz-chat-list li[id=" + leave_chat_id + "]").remove();
                $(".whizz-chat-list.whizzChat-more-chat-list li[data-more-id=" + leave_chat_id + "]").remove();
                var more_val = parseInt($("a.whizzChat-more-boxes span").html());
                if (typeof more_val !== 'undefined' && more_val > 0) {
                    more_val = more_val - 1;
                    $("a.whizzChat-more-boxes span").html(more_val);
                }
            }
        });
    });


    function whizzChat_is_alphanumeric(inputtxt)
    {
        var letters = /^[0-9a-zA-Z ]+$/;
        var return_val = false;
        if (inputtxt.match(letters))
        {
            var return_val = true;
        } else
        {
            alert(whizzChat_ajax_object.add_aphanemeric);
            var return_val = false;
        }
        return return_val;
    }

    $(document).on('click', '.initate-chat-button', function (e) {
        e.preventDefault();
        var whizzChat_name = $(this).closest("form.initate-chat").find("input[name='new_user_name']").val();
        var whizzChat_email = $(this).closest("form.initate-chat").find("input[name='new_user_email']").val();


        var validate_name = whizzchat_strip_html(whizzChat_name);
        if (validate_name == '')
        {

            $(this).closest("form.initate-chat").find("input[name='new_user_name']").val('');
            alert(whizzChat_ajax_object.invalid_type_data);//invalid_type_data
            return '';
        }

        var post_id = $(this).parents().filter(function () {
            return $(this).attr("data-post-id");
        }).eq(0).attr("data-post-id");

        var chat_id = $(this).parents().filter(function () {
            return $(this).attr("data-chat-id");
        }).eq(0).attr("data-chat-id");



        var chat_type_flag = false;
        var validate_email = false;

        var chat_type = whizzChat_ajax_object.whizz_chat_type;
        chat_type = typeof chat_type !== 'undefined' && chat_type != '' ? chat_type : 1;

        if (chat_type == 1 && (typeof whizzChat_name !== 'undefined' && whizzChat_name != '') && (typeof whizzChat_email !== 'undefined' && whizzChat_email != '')) {
            chat_type_flag = true;
        }

        if (chat_type == 0 && (typeof whizzChat_name !== 'undefined' && whizzChat_name != '')) {
            chat_type_flag = true;
        }

        $(this).prop('disabled', true);
        $(this).html('<i class="fa fa-spinner fa-spin"></i>');

        var client_data = {
            action: 'whizzChat_initiate_chat',
            whizzChat_name: whizzChat_name,
            whizzChat_email: whizzChat_email,
            url: window.location.href,
            nonce: whizzChat_ajax_object.nonce,
            post_id: post_id,
            chat_id: chat_id
        };

        var json_end_point = whizzChat_ajax_object.whizz_restapi_endpoint + '/start-session';
        var _nonce = whizzChat_ajax_object.whizz_site_nonce;
        var _nonce_rest = whizzChat_ajax_object.whizz_restapi_nonce;
        if (chat_type_flag) {

            var pattern = '/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i';

            if (chat_type == 1 && typeof whizzChat_email !== 'undefined' && whizzChat_email != '' && !whizz_is_valid_email(whizzChat_email)) {

                $('.whizz-chat-error').html('<p>' + whizzChat_ajax_object.enter_valid_email + '</p>');

            } else {

                $.ajax({
                    type: 'POST',
                    action: 'whizzChat_initiate_chat',
                    url: json_end_point,
                    data: client_data,
                    dataType: 'json',
                    crossDomain: true,
                    cache: false,
                    async: true,
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-WP-Nonce', whizzChat_ajax_object.nonce);
                    },
                }).done(function (response) {

                    if (response.whizz_cookie_data != "") {
                        $.each(response.whizz_cookie_data, function (index, whizz_cooki) {
                            whizzchat_setCookie(whizz_cooki.key, whizz_cooki.value, whizz_cooki.time);
                        });
                    }

                    if (response.html_data != "") {



                        $("div[data-post-id=" + post_id + "]").replaceWith(response.html_data);
                         var show_emoji    =  whizzChat_ajax_object.show_emoji;
             
             if(show_emoji ==  "1"){
                        jQuery("[data-chat-id='" + response.chat_id + "'] .whizzChat-emoji").emojioneArea({
                            pickerPosition: "top",
                            filtersPosition: "bottom",
                            tones: false,
                            spellcheck: true,
                            autocomplete: false,
                            //inline: true,
                            hidePickerOnBlur: true,
                            saveEmojisAs: 'unicode',
                            placeholder: "Type something here",
                            events: {
                                focus: function (editor, event) {
                                    var chat_id = editor.parent().parent().attr('data-chat-id');
                                    var user_name = editor.parent().parent().attr('data-user-name');
                                    var msg = user_name + ' is typing ';
                                    var room = editor.parent().parent().attr("data-room");

                                    if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {
                                        socket.emit('agTyping', room, msg, chat_id);  // 
                                    }
                                },
                                blur: function (editor, event) {
                                    var chat_id = editor.parent().parent().attr('data-chat-id');
                                    var room = editor.parent().parent().attr("data-room");

                                    if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {
                                        socket.emit('agStopTyping', room, chat_id);
                                    }
                                },
                            }
                        });
                    }
                    }
                });
            }

        } else {
            $('.whizz-chat-error').html('<p>' + whizzChat_ajax_object.provide_info + '</p>');
        }
        $(this).prop('disabled', false);
        $(this).html('Start Chat');
    });

    function whizz_is_valid_email(emailAddress) {
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        return pattern.test(emailAddress);
    }

    $(document).on('click', '.whizzChat-block-user-admin', function () {

        var chat_id = $(this).attr("data-chat-id");
        var post_id = $(this).attr("data-post-id");

        var client_data = {
            chat_id: chat_id,
            post_id: post_id,
            session: whizzChat_ajax_object.whizz_user_token,
            nonce: whizzChat_ajax_object.nonce
        };

        var json_end_point = whizzChat_ajax_object.whizz_restapi_endpoint + '/block-user';
        $.ajax({
            type: 'POST',
            action: 'whizzChat_block_user',
            url: json_end_point,
            data: client_data,
            crossDomain: true,
            cache: false,
            async: true,
            xhrFields: {
                withCredentials: true
            },
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', whizzChat_ajax_object.nonce);
            },
        }).done(function (data) {
            if (data['success'] == true && data['data']['unique_id'] != "") {
                var unique_id = data['data']['unique_id'];
                $("div[data-unique-user=" + unique_id + "]").each(function (index) {
                    var html_val = $(this).find('a.whizzChat-block-user-admin:eq(0)').html();
                    var data_val = $(this).find('a.whizzChat-block-user-admin:eq(0)').data('replace-text');
                    $(this).find('a.whizzChat-block-user-admin:eq(0)').html(data_val);
                    $(this).find('a.whizzChat-block-user-admin:eq(0)').data('replace-text', html_val);
                    $(this).find('a.whizzChat-block-user-admin:eq(1)').remove();
                    $(this).find('p.blocked-chat-p').remove();
                    $(this).find('.chat-messages').append(data['html']);
                });
            }
        });
    });

    $("body").delegate(".whizzChat-block-user", "click", function () {
        //$(document).on('click', '.whizzChat-block-user', function () {
        var chat_id = $(this).parents().filter(function () {
            return $(this).data("chat-id");
        }).eq(0).data("chat-id");
        var post_id = $(this).parents().filter(function () {
            return $(this).data("post-id");
        }).eq(0).data("post-id");
        var client_data = {
            chat_id: chat_id,
            post_id: post_id,
            session: whizzChat_ajax_object.whizz_user_token,
            nonce: whizzChat_ajax_object.nonce
        };

        var json_end_point = whizzChat_ajax_object.whizz_restapi_endpoint + '/block-user';
        $.ajax({
            type: 'POST',
            action: 'whizzChat_block_user',
            url: json_end_point,
            data: client_data,
            crossDomain: true,
            cache: false,
            async: true,
            xhrFields: {
                withCredentials: true
            },
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', whizzChat_ajax_object.nonce);
            },
        }).done(function (data) {
            if (data['success'] == true && data['data']['unique_id'] != "") {
                var unique_id = data['data']['unique_id'];
                $("div[data-chat-id=" + unique_id + "]").each(function (index) {

                    var html_val = $(this).find('a.whizzChat-block-user:eq(0)').html();
                    var data_val = $(this).find('a.whizzChat-block-user:eq(0)').data('replace-text');
                    $(this).find('a.whizzChat-block-user:eq(0)').html(data_val);
                    $(this).find('a.whizzChat-block-user:eq(0)').data('replace-text', html_val);
                    $(this).find('a.whizzChat-block-user:eq(1)').remove();
                    $(this).find('p.blocked-chat-p').remove();
                    $(this).find('.chat-messages').append(data['html']);

                });
            }
        });
    });



    $("body").delegate(".initate-chat-input-text", "keydown", function (e) {
         
        if (e.which == 13) {
            e.preventDefault();
            var msg = $(document.activeElement).closest('div.chat-input-holder').find('.initate-chat-input-btn').click();
        }
    });


    /*Initate and start chat*/
    //if (!whizzchat_live_enabled) {
    $(document).on('click', '.initate-chat-input-btn', function () {

        var this_obj = $(this);

        var chat_id = $(this).parents().filter(function () {
            return $(this).attr("data-chat-id");
        }).eq(0).attr("data-chat-id");

        var comm_id = $(this).parents().filter(function () {
            return $(this).attr("data-comm-id");
        }).eq(0).attr("data-comm-id");

        var room = $(this).parents().filter(function () {
            return $(this).attr("data-room");
        }).eq(0).attr("data-room");


        var ad_author_id = $(this).parents().filter(function () {
            return $(this).attr("data-author-id");
        }).eq(0).attr("data-author-id");

        var post_id = $(this).parents().filter(function () {
            return $(this).attr("data-post-id");
        }).eq(0).attr("data-post-id");

        var msg = '';
         var show_emoji    =  whizzChat_ajax_object.show_emoji;
             
        if(show_emoji ==  "1"){
             var msg = $(this).closest('div.chat-input-holder').find('.initate-chat-input-text').data("emojioneArea").getText();
           }
           else{
               
               var msg = $(this).closest('div.chat-input-holder').find('.initate-chat-input-text').val();
           }


        msg = whizzchat_strip_html(msg);
        if (msg == '') {
            alert(whizzChat_ajax_object.invalid_type_data2);
            return;
        }
        $(this).closest('div.chat-input-holder').find('.initate-chat-input-text').val('');
        $(this).closest('div.chat-input-holder').find('div.emojionearea-editor').html('');
        var this_var = $(this);
        var message_ids = $('#' + chat_id + ' div.message-box-holder:last').attr('data-chat-unique-id');
        $('#get-chat-switch-' + chat_id + '').val('on');
        var rmv_div = "div[data-chat-id=" + chat_id + "] .chat-messages .whizzChat-chat-messages-last";
        $(rmv_div).remove();
        
        var dd_bottom = "div[data-chat-id=" + chat_id + "] .chat-messages";


        $(dd_bottom).prop({scrollTop: $(dd_bottom).prop("scrollHeight")});

        var session_id = whizz_user_token_js(whizzChat_ajax_object.whizz_user_token);


     

        var client_data = {
            action: 'whizzChat_send_chat_message',
            url: window.location.href,
            session: session_id,
            nonce: whizzChat_ajax_object.nonce,
            post_id: post_id,
            chat_id: chat_id,
            msg: msg,
            message_ids: message_ids,
            message_type: 'text',
            comm_id   : comm_id
        };


        if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {
            window.sender_id = session_id;
            window.receiver_id = comm_id;
            socket.emit('agRoomJoined', room, session_id, comm_id);
        }
       $('.whizz-btn-wrap-' + chat_id + '').html('<i class="fas fa-spinner fa-spin initate-chat-input-btn"></i>');
        var json_end_point = whizzChat_ajax_object.whizz_restapi_endpoint + '/send-chat-message';
        var _nonce = whizzChat_ajax_object.whizz_site_nonce;
        var _nonce_rest = whizzChat_ajax_object.whizz_restapi_nonce;

        $.ajax({
            type: 'POST',
            action: 'whizzChat_send_chat_message',
            url: json_end_point,
            data: client_data,
            dataType: 'json',
            crossDomain: true,
            cache: false,
            async: true,
            xhrFields: {
                withCredentials: true
            },
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', whizzChat_ajax_object.nonce);
            },
        }).done(function (data) {
            if (data['success'] == true && data['data']['chat_boxes']) {


                var json_data = JSON.parse(data['data']['chat_boxes']);
                var post_id = (json_data['post_id']);
                var chat_id = json_data['chat_id'];
                var html = (json_data['html']);
                var dd = "div[data-chat-id=" + chat_id + "] .chat-messages div.message-box-holder:last";
                var temp_id = 'whizz-chat-temp-' + post_id;
                var temp_msg = "div[id=" + temp_id + "]";
                if ($(temp_msg).length > 0) {
                    $("div[id=" + temp_id + "] .chat-messages").append(html);
                } else if ($(dd).length <= 0) {
                    $("div[data-chat-id=" + chat_id + "] .chat-messages:last").append(html);
                } else {
                    $(dd).after(html);
                }
                var time_text = $(dd).data('chat-last-seen');
                var time_div = "div[data-chat-id=" + chat_id + "] .chat-messages whizzChat-chat-messages-last";
                $(time_div).html(time_text);
                var dd_bottom = "div[data-chat-id=" + chat_id + "] .chat-messages";
                $(dd_bottom).prop({scrollTop: $(dd_bottom).prop("scrollHeight")});
                $('#whizz-chat-temp-' + post_id + '').attr('data-chat-id', chat_id);
                if ($(temp_msg).length > 0) {

                    var room_id = whizzChat_ajax_object.whizzcaht_room + chat_id;
                    $('#whizz-chat-temp-' + post_id + '').attr('data-room', room_id);
                    $('#whizz-chat-temp-' + post_id + '').attr('data-chat-id', chat_id);

                    $('#whizz-chat-temp-' + post_id + ' .chat-input-holder').attr('data-chat-id', chat_id);
                    $('#whizz-chat-temp-' + post_id + ' .chat-input-holder').attr('data-room', room_id);
                    $('#whizz-chat-temp-' + post_id + ' .whizz-custom-div span.whizz-typing').addClass("typing-box-" + chat_id);
                    $('#whizz-chat-temp-' + post_id + ' .whizz-custom-div span.whizz-typing').removeClass("typing-box-0");
                    $('#whizz-chat-temp-' + post_id + ' .settings-popup .logout-chat-session').attr('data-leave-chat-id', chat_id);

                    //$('.chat-input-holder').attr('data-room', room_id);

                    $('#whizz-chat-temp-' + post_id + '').attr('id', chat_id);
                    $('.message-send.whizz-btn-wrap-0').removeClass('whizz-btn-wrap-0').addClass("whizz-btn-wrap-" + chat_id);
                }
                $('.whizz-btn-wrap-' + chat_id + '').html('<i class="fas fa-chevron-right initate-chat-input-btn"></i>');

                if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {

                    socket.emit('agSendMessage', room, msg, comm_id, chat_id);
                }
            } else if (data['success'] == false && data['data']['chat_boxes']) {
                var json_data = JSON.parse(data['data']['chat_boxes']);
                var post_id = (json_data['post_id']);
                var chat_id = json_data['chat_id'];
                $('.whizz-btn-wrap-' + chat_id + '').html('<i class="fas fa-chevron-right initate-chat-input-btn"></i>');
                if (typeof data.message !== 'undefined' && data.message != '') {
                    alert(data.message);
                }
            }
        });
    });



    $(document).on('click', '.initate-chat-admin', function (e) {

        var current_obj = $(this);
        var chat_id = $(this).attr('data-chat-id');
        var post_id = $(this).attr('data-post-id');
        var comm_id = $(this).attr('data-comm-id');
        var room = $(this).attr('data-room');
        var ad_author_id = $(this).attr('data-author-id');
        if (typeof chat_id !== 'undefined' && chat_id != '' && typeof post_id !== 'undefined' && post_id != '') {
            var msg = $('.initate-chat-input-text').val();
            msg = whizzchat_strip_html(msg);
            if (msg == '') {
                alert(whizzChat_ajax_object.invalid_type_data2);
                return;
            }
            if (typeof msg !== 'undefined' && msg != '') {
                var session_id = whizz_user_token_js(whizzChat_ajax_object.whizz_user_token);
                var message_ids = $('div.message-box-holder:last').data('chat-unique-id');
                var client_data = {
                    action: 'whizzChat_send_chat_message_admin',
                    url: window.location.href,
                    session: session_id,
                    nonce: whizzChat_ajax_object.nonce,
                    post_id: post_id,
                    chat_id: chat_id,
                    msg: msg,
                    message_ids: message_ids,
                    message_type: 'text'
                };
                if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {
                    socket.emit('agRoomJoined', room, session_id, comm_id);
                }
                current_obj.html('<i class="fas fa-spinner fa-spin"></i>');
                var json_end_point = whizzChat_ajax_object.whizz_restapi_endpoint + '/send-chat-message-admin';
                var _nonce = whizzChat_ajax_object.whizz_site_nonce;
                var _nonce_rest = whizzChat_ajax_object.whizz_restapi_nonce;
                $.ajax({
                    type: 'POST',
                    action: 'whizzChat_send_chat_message_admin',
                    url: json_end_point,
                    data: client_data,
                    dataType: 'json',
                    crossDomain: true,
                    cache: false,
                    async: true,
                    xhrFields: {
                        withCredentials: true
                    },
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-WP-Nonce', whizzChat_ajax_object.nonce);
                    },
                }).done(function (data) {
                    current_obj.html('Send');
                    if (data['success'] = true && data['data']['chat_boxes']) {
                        var json_data = JSON.parse(data['data']['chat_boxes']);
                        var post_id = (json_data['post_id']);
                        var chat_id = json_data['chat_id'];

                        if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {
                            socket.emit('agSendMessage', room, msg, comm_id, chat_id);
                        }

                        var html = (json_data['html']);
                        var dd = "div.whizz-chat-body div[data-chat-id=" + chat_id + "] .chat-messages div.message-box-holder:last";
                        $(dd).after(html);
                        var time_text = $(dd).data('chat-last-seen');
                        var time_div = "div[data-chat-id=" + chat_id + "] .chat-messages whizzChat-chat-messages-last";
                        $(time_div).html(time_text);
                        var dd_bottom = "div[data-chat-id=" + chat_id + "] .chat-messages";
                        $(dd_bottom).prop({scrollTop: $(dd_bottom).prop("scrollHeight")});
                        $('.whizz-btn-wrap-' + chat_id + '').html('<i class="fas fa-chevron-right message-send initate-chat-input-btn"></i>');
                        jQuery('.whizz-chat-body textarea.whizzChat-emoji').val('');
                        jQuery('.whizz-chat-body .emojionearea-editor').html('');
                    }
                });
            } else {
                alert(whizzChat_ajax_object.add_messages);
            }

        } else {
            alert(whizzChat_ajax_object.select_chat_person);
        }

    });


    $('body').on('click', '.attachment-panel .whizz-chat-location', function () {

        var chat_id = $(this).parents().filter(function () {
            return $(this).attr("data-chat-id");
        }).eq(0).attr("data-chat-id");
        var post_id = $(this).parents().filter(function () {
            return $(this).attr("data-post-id");
        }).eq(0).attr("data-post-id");
        var comm_id = $(this).parents().filter(function () {
            return $(this).attr("data-comm-id");
        }).eq(0).attr("data-comm-id");
        var room = $(this).parents().filter(function () {
            return $(this).attr("data-room");
        }).eq(0).attr("data-room");
        var msg = $(this).closest('div.chat-input-holder').find('.initate-chat-input-text').val();
        var msg = '';
        $(this).closest('div.chat-input-holder').find('.initate-chat-input-text').val('');
        $(this).closest('div.chat-input-holder').find('div.emojionearea-editor').html('');
        var rmv_div = "div[data-chat-id=" + chat_id + "] .chat-messages .whizzChat-chat-messages-last";
        $(rmv_div).remove();
        var message_ids = $('#' + chat_id + ' div.message-box-holder:last').attr('data-chat-unique-id');


        if ($(this).hasClass("fa-map-marker"))
        {
            if (!navigator.geolocation) {
                alert(whizzChat_ajax_object.browser_not_support);
            } else {
                $(this).parents().filter(function ()
                {
                    $('.whizz-btn-wrap-' + chat_id + '').html('<i class="fas fa-spinner fa-spin initate-chat-input-btn"></i>');
                });
                navigator.geolocation.getCurrentPosition(function (position)
                {
                    var session_id = whizz_user_token_js(whizzChat_ajax_object.whizz_user_token);


                    if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {
                        socket.emit('agRoomJoined', room, session_id, comm_id);

                    }

                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;
                    var map_lat_long = [];
                    if (lat != "" && lng != "") {
                        map_lat_long.push({"latitude": lat, "longitude": lng});
                    }
                    
                    
                    
                    var location = {"latitude": lat, "longitude": lng};
                    var mapString = JSON.stringify(location);
                    
                    
                    
                    
                    var session_id = whizz_user_token_js(whizzChat_ajax_object.whizz_user_token);
                    var client_data = {
                        action: 'whizzChat_send_chat_message',
                        url: window.location.href,
                        chat_id: chat_id,
                        post_id: post_id,
                        msg: msg,
                        upload_type: 'map',
                        message_type: 'map',
                        map_data: mapString,
                        session: session_id,
                        nonce: whizzChat_ajax_object.nonce,
                        message_ids: message_ids,
                    };
                    var json_end_point = whizzChat_ajax_object.whizz_restapi_endpoint + '/send-chat-message';
                    $.ajax({
                        type: 'POST',
                        action: 'whizzChat_send_chat_message',
                        url: json_end_point,
                        data: client_data,
                        dataType: 'json',
                        crossDomain: true,
                        cache: false,
                        async: true,
                        xhrFields: {
                            withCredentials: true
                        },
                        beforeSend: function (xhr) {
                            xhr.setRequestHeader('X-WP-Nonce', whizzChat_ajax_object.nonce);
                        },
                    }).done(function (data) {
                        if (data['success'] == true && data['data']['chat_boxes']) {
                            var json_data = JSON.parse(data['data']['chat_boxes']);
                            var post_id = (json_data['post_id']);
                            var chat_id = json_data['chat_id'];
                            var html = (json_data['html']);
                            var dd = "div[data-chat-id=" + chat_id + "] .chat-messages div.message-box-holder:last";
                            var temp_id = 'whizz-chat-temp-' + post_id;
                            var temp_msg = "div[id=" + temp_id + "]";
                            if ($(temp_msg).length > 0) {
                                $("div[id=" + temp_id + "] .chat-messages").append(html);
                            } else if ($(dd).length <= 0) {
                                $("div[data-chat-id=" + chat_id + "] .chat-messages:last").append(html);
                            } else {
                                $(dd).after(html);
                            }
                            var time_text = $(dd).data('chat-last-seen');
                            var time_div = "div[data-chat-id=" + chat_id + "] .chat-messages whizzChat-chat-messages-last";
                            $(time_div).html(time_text);
                            var dd_bottom = "div[data-chat-id=" + chat_id + "] .chat-messages";
                            $(dd_bottom).prop({scrollTop: $(dd_bottom).prop("scrollHeight")});
                            $('#whizz-chat-temp-' + post_id + '').attr('data-chat-id', chat_id);
                            if ($(temp_msg).length > 0) {
                                $('#whizz-chat-temp-' + post_id + '').attr('data-chat-id', chat_id);
                                $('#whizz-chat-temp-' + post_id + '').attr('id', chat_id);
                                var chat_idd = $('.chat-input-holder').attr('data-chat-id');
                                $('.chat-input-holder').attr('data-chat-id', chat_id);
                                $('.message-send.whizz-btn-wrap-0').removeClass('whizz-btn-wrap-0').addClass("whizz-btn-wrap-" + chat_id);
                            }

                            if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {
                                socket.emit('agSendMessage', room, msg, comm_id, chat_id);
                            }
                            $('.whizz-btn-wrap-' + chat_id + '').html('<i class="fas fa-chevron-right initate-chat-input-btn"></i>');
                        } else if (data['success'] == false && data['data']['chat_boxes']) {
                            var json_data = JSON.parse(data['data']['chat_boxes']);
                            var post_id = (json_data['post_id']);
                            var chat_id = json_data['chat_id'];
                            $('.whizz-btn-wrap-' + chat_id + '').html('<i class="fas fa-chevron-right initate-chat-input-btn"></i>');
                            if (typeof data.message !== 'undefined' && data.message != '') {
                                alert(data.message);
                            }
                        }


                    });


                }, function (error) {

                    if (error.code == error.PERMISSION_DENIED)
                    {
                        alert(whizzChat_ajax_object.enable_location);
                    } else {
                        alert(error.message);
                    }

                    $('.whizz-btn-wrap-' + chat_id + '').html('<i class="fas fa-chevron-right initate-chat-input-btn"></i>');
                });
            }

        }

    });


    /*Upload Image/Attachment*/
    $('body').on('change', '.attachment-panel .ibenic_file_input, .attachment-panel a', function () {
        var upload_type = '';

         
        
       

        var chat_id = $(this).parents().filter(function () {
            return $(this).attr("data-chat-id");
        }).eq(0).attr("data-chat-id");
        $('.whizz-btn-wrap-' + chat_id + '').html('<i class="fas fa-spinner fa-spin initate-chat-input-btn"></i>');

        if ($(this).hasClass("whizzChat-file"))
        {
            var upload_type = 'file';
            var file_max_size = whizzChat_ajax_object.whizz_file.size;
            var file_extension = whizzChat_ajax_object.whizz_file.format;
        } else if ($(this).hasClass("whizzChat-image"))
        {
            var upload_type = 'image';
            var file_max_size = whizzChat_ajax_object.whizz_image.size;
            var file_extension = whizzChat_ajax_object.whizz_image.format;
        } else
        {
            $.toast({
                heading: 'Warning',
                text: whizzChat_ajax_object.not_valid_type,
                icon: 'warning',
                position: 'top-right',
                hideAfter: 6000,
                stack: 5,
            });

        }


        /*Validate File Type*/
        var file_obj = $(this).prop('files');
        var form_data = new FormData();
        var file_type_change = false;
        var upload_file_count = file_obj.length;
        var img_size_exceed = false;

        for (var i = 0; i < upload_file_count; i++)
        {
            var file_size = file_obj[i].size;
            var file_name = file_obj[i].name;
            var file_type = file_obj[i].type;
            var is_added = true;
            if (jQuery.inArray(file_name.split('.').pop().toLowerCase(), file_extension) == -1)
            {
                file_type_change = true;
                var is_added = false;

            }

            if (file_size > file_max_size)
            {
                img_size_exceed = true;
                var is_added = false;
            }

            if (is_added == true) {
                form_data.append('file[]', file_obj[i]);
            }
        }



        upload_file_count = typeof upload_file_count !== 'undefined' && upload_file_count != '' ? upload_file_count : 0;
        if (upload_file_count == 1 && img_size_exceed && file_type_change) {
            alert(whizzChat_ajax_object.type_size_not_valid);
        }

        if (upload_file_count == 1 && img_size_exceed) {
            alert(whizzChat_ajax_object.size_not_valid);
        }

        if (upload_file_count == 1 && file_type_change) {
            alert(whizzChat_ajax_object.type_not_valid);
        }

        if (upload_file_count > 1 && img_size_exceed && file_type_change) {
            alert(whizzChat_ajax_object.sm_type_size_not_valid);
        }

        if (upload_file_count > 1 && img_size_exceed) {
            alert(whizzChat_ajax_object.sm_size_not_valid);
        }

        if (upload_file_count > 1 && file_type_change) {
            alert(whizzChat_ajax_object.sm_type_not_valid);
        }

        var comm_id = $(this).parents().filter(function () {
            return $(this).attr("data-comm-id");
        }).eq(0).attr("data-comm-id");

        var room = $(this).parents().filter(function () {
            return $(this).attr("data-room");
        }).eq(0).attr("data-room");

        var post_id = $(this).parents().filter(function () {
            return $(this).attr("data-post-id");
        }).eq(0).attr("data-post-id");

        var ad_author_id = $(this).parents().filter(function () {
            return $(this).data("author-id");
        }).eq(0).data("author-id");

        var rmv_div = "div[data-chat-id=" + chat_id + "] .chat-messages .whizzChat-chat-messages-last";
        $(rmv_div).remove();

        var msg = $(this).closest('div.chat-input-holder').find('.initate-chat-input-text').val();
        var msg = '';
        $(this).closest('div.chat-input-holder').find('.initate-chat-input-text').val('');
        $(this).closest('div.chat-input-holder').find('div.emojionearea-editor').html('');

        var message_ids = $('#' + chat_id + ' div.message-box-holder:last').attr('data-chat-unique-id');

        var session_id = whizz_user_token_js(whizzChat_ajax_object.whizz_user_token);
        form_data.append('chat_id', chat_id);
        form_data.append('post_id', post_id);
        form_data.append('action', 'whizzChat_send_chat_message');
        form_data.append('session', session_id);
        form_data.append('nonce', whizzChat_ajax_object.nonce);
        form_data.append('url', window.location.href);
        form_data.append('msg', msg);
        form_data.append('message_ids', message_ids);
        form_data.append('upload_type', upload_type);
        form_data.append('message_type', 'image/file');
        var json_end_point = whizzChat_ajax_object.whizz_restapi_endpoint + '/send-chat-message';

        if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {
            socket.emit('agRoomJoined', room, session_id, comm_id);
        }

        $.ajax({
            url: json_end_point,
            type: 'POST',
            contentType: false,
            processData: false,
            data: form_data,
            dataType: 'json',
            crossDomain: true,
            cache: false,
            async: true,
            xhrFields: {
                withCredentials: true
            },
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', whizzChat_ajax_object.nonce);

            },
        }).done(function (data) {



            console.log(data);

            if (data['success'] == true && data['data']['chat_boxes']) {

                var json_data = JSON.parse(data['data']['chat_boxes']);
                var post_id = (json_data['post_id']);
                var chat_id = json_data['chat_id'];
                var html = (json_data['html']);
                var dd = "div[data-chat-id=" + chat_id + "] .chat-messages div.message-box-holder:last";
                var temp_id = 'whizz-chat-temp-' + post_id;
                var temp_msg = "div[id=" + temp_id + "]";
                if ($(temp_msg).length > 0) {
                    $("div[id=" + temp_id + "] .chat-messages").append(html);
                } else if ($(dd).length <= 0) {
                    $("div[data-chat-id=" + chat_id + "] .chat-messages:last").append(html);
                } else {
                    $(dd).after(html);
                }
                var time_text = $(dd).data('chat-last-seen');
                var time_div = "div[data-chat-id=" + chat_id + "] .chat-messages whizzChat-chat-messages-last";
                $(time_div).html(time_text);
                var dd_bottom = "div[data-chat-id=" + chat_id + "] .chat-messages";
                $(dd_bottom).prop({scrollTop: $(dd_bottom).prop("scrollHeight")});
                $('#whizz-chat-temp-' + post_id + '').attr('data-chat-id', chat_id);
                if ($(temp_msg).length > 0) {
                    $('#whizz-chat-temp-' + post_id + '').attr('data-chat-id', chat_id);
                    $('#whizz-chat-temp-' + post_id + '').attr('id', chat_id);
                    var chat_idd = $('.chat-input-holder').attr('data-chat-id');
                    $('.chat-input-holder').attr('data-chat-id', chat_id);
                    $('.message-send.whizz-btn-wrap-0').removeClass('whizz-btn-wrap-0').addClass("whizz-btn-wrap-" + chat_id);
                }
                $('.whizz-btn-wrap-' + chat_id + '').html('<i class="fas fa-chevron-right initate-chat-input-btn"></i>');

                //socket work
                if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {
                    socket.emit('agSendMessage', room, msg, comm_id, chat_id);
                }

                $(".attachment-panel .ibenic_file_input, .attachment-panel a").val('');

            } else if (data['success'] == false && data['data']['chat_boxes']) {

                var json_data = JSON.parse(data['data']['chat_boxes']);
                var post_id = (json_data['post_id']);
                var chat_id = json_data['chat_id'];
                $('.whizz-btn-wrap-' + chat_id + '').html('<i class="fas fa-chevron-right initate-chat-input-btn"></i>');
                if (typeof data.message !== 'undefined' && data.message != '') {
                    alert(data.message);
                }
            }

        });
    });

    function whizzChat_custom_overlay_html()
    {
        return '<div class="whizzChat-custom"><div class="overlay"></div><div class="spanner"><div class="loader"></div><p>Uploading...</p></div></div>';
    }

    function whizzChat_getAndSet_chat_data() {

        var client_data = {
            action: 'whizzChat_get_chat',
            url: window.location.href,
            session: whizzChat_ajax_object.whizz_user_token,
            nonce: whizzChat_ajax_object.nonce,
        };
        var json_end_point = whizzChat_ajax_object.whizz_restapi_endpoint + '/chat-messages';
        $.ajax({
            type: 'POST',
            action: 'whizzChat_get_chat',
            url: json_end_point,
            data: client_data,
            dataType: 'json',
            crossDomain: true,
            cache: false,
            async: true,
            xhrFields: {
                withCredentials: true
            },
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', whizzChat_ajax_object.nonce);
            },
        }).done(function (data) {
            if (console && console.log) {
                $("#collapseOne").html(data['data']['html']);
            }
        });
    }


    /*Show Chat Box Chat From Bottom*/
    $(".chatbox-inner-holder .chat-messages").each(function (index) {
        $(this).prop({
            scrollTop: $(this).prop("scrollHeight")
        });
    });
    if ($('li.chatlist-message-alert').length)
    {
        $("li.chatlist-message-alert").on("click", function () {
            $(this).find('.chat-badge-count').remove();
        });
    }
    /*WhizzChat File/Image Uploads*/
    // Just to be sure that the input will be called
    $(".ibenic_file_upload").on("click", function () {
        $('.ibenic_file_input').click(function (event) {
            event.stopPropagation();
        });
    });


    $(document).ready(function () {
        var show_emoji    =  whizzChat_ajax_object.show_emoji;
        if(show_emoji  == "1" && $(".whizzChat-emoji").length > 0 ){
        $(".whizzChat-emoji").emojioneArea({
            pickerPosition: "top",
            filtersPosition: "bottom",
            tones: false,
            spellcheck: true,
            autocomplete: false,
            hidePickerOnBlur: true,
            saveEmojisAs: 'unicode',
            placeholder: "Type something here",
            events: {
                focus: function (editor, event) {
                    var chat_id = editor.parent().parent().attr('data-chat-id');
                    var user_name = editor.parent().parent().attr('data-user-name');
                    var room = editor.parent().parent().attr('data-room');

                    var msg = user_name + ' is typing ';
                    if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {
                        socket.emit('agTyping', room, msg, chat_id);  // 
                    }
                },
                blur: function (editor, event) {
                    var chat_id = editor.parent().parent().attr('data-chat-id');
                    var room = editor.parent().parent().attr('data-room');

                    if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {
                        socket.emit('agStopTyping', room, chat_id);
                    }
                },
            }

        });
    }
    });


    var cookie_id_js = whizzChat_ajax_object.whizz_user_token;
    var whizzchat_comm_type = $("#whizz-chat-live").val();
    var whizzchat_screen = $("#whizzchat-screen").val();
    var whizzchat_between = $("#whizz-chat-between").val();

    if (whizzchat_comm_type == '0' && whizzchat_screen == 'user' && whizzchat_dashboard == 'disable') {
       setInterval(whizzChat_load_chat, whizzChat_ajax_object.check_time);
        setInterval(whizzChat_load_chat_box, whizzChat_ajax_object.check_time);

    }
    if (typeof cookie_id_js !== 'undefined' && cookie_id_js != '' && whizzchat_comm_type == '0' && whizzchat_screen == 'admin' && whizzchat_dashboard == 'disable') {
        
        
        setInterval(whizzChat_load_chat_admin, whizzChat_ajax_object.check_time);
       setInterval(whizzChat_load_chat_box_admin, whizzChat_ajax_object.check_time);
    }


    $.whizzChat_ajax_blocked4 = {loaded: false, timerx: 0}
    function whizzChat_load_chat_box_admin() {
        if (typeof whizzchat_between !== 'undefined' && whizzchat_between == '0') {
            return false;
        }
        var chat_list = [];
        var chat_id;
        var session_id = whizz_user_token_js(whizzChat_ajax_object.whizz_user_token);

        var li_exists = $(".whizz-chat-body .whizzchat-sidebar .messages-box .list-group a").length;
        li_exists = typeof li_exists !== 'undefined' && li_exists > 0 ? li_exists : 0;

        $(".whizz-chat-body .whizzchat-sidebar .messages-box .list-group a").each(function (index) {
            chat_id = $(this).attr('id');
            chat_list.push(chat_id);
        });
        var json_list_ids = JSON.stringify(chat_list);
        var client_data = {
            action: 'whizzChat_get_chat_list_box_admin',
            session: session_id,
            nonce: whizzChat_ajax_object.nonce,
            list_ids: json_list_ids,
            list_data: li_exists,
        };
        var json_end_point = whizzChat_ajax_object.whizz_restapi_endpoint + '/get-chat-list-box-admin';
        $.ajax({
            type: 'POST',
            action: 'whizzChat_get_chat_list_box_admin',
            url: json_end_point,
            data: client_data,
            dataType: 'json',
            crossDomain: true,
            cache: false,
            async: true,
            xhrFields: {
                withCredentials: true
            },
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', whizzChat_ajax_object.nonce);
            },
        }).done(function (response) {
            $.each(JSON.parse(response['list_ids']), function (i, item) {

                if (typeof response['list_html'] !== 'undefined' && response['list_html'] != '') {
                    $(".whizz-chat-body .whizzchat-sidebar .messages-box .list-group a").remove();
                    $(".whizz-chat-body .whizzchat-sidebar .messages-box .list-group").prepend(JSON.parse(response['list_html']));

                }
                return false;

            });
        });
    }

    $.whizzChat_ajax_loaded9 = {loaded: false, timerx: 0}
    function whizzChat_load_chat_admin() {
        
        if ($.whizzChat_ajax_loaded9.timerx != 0) {
            if ($.whizzChat_ajax_loaded9.loaded == false)
                return '';
        }
        
              
        var chat_list = [];
        var message_ids = [];
        $("div.whizz-chat-body .chat-box.bg-white div.individual-chat-box").each(function (index) {

            var post_id = $(this).data('post-id');
            var chat_id = $(this).data('chat-id');
            var this_var = $(this);
            var get_chat_id = $('div.whizz-chat-body #' + chat_id + ' div.message-box-holder:last').data('chat-unique-id');

            var chat_load_switch = $('#get-chat-switch-' + chat_id + '').val();
            if (get_chat_id != "") {
                message_ids.push({"chat_id": chat_id, "get_message_id": get_chat_id});
            }
            if ((post_id != "" && chat_id != "") && (post_id != null && chat_id != null)) {
                chat_list.push({"chat_id": chat_id, "post_id": post_id, });
            }
        });


        var session_id = whizz_user_token_js(whizzChat_ajax_object.whizz_user_token);
        if (message_ids.length !== 0) {
            var message_ids = JSON.stringify(message_ids);
            var jsonString = JSON.stringify(chat_list);
            var client_data = {
                action: 'whizzChat_get_chat_list',
                url: window.location.href,
                session: session_id,
                nonce: whizzChat_ajax_object.nonce,
                chat_boxs: '',
                message_ids: message_ids,
                boxs: jsonString,
            };
            var json_end_point = whizzChat_ajax_object.whizz_restapi_endpoint + '/get-chat-list-admin';
            $.ajax({
                type: 'POST',
                action: 'whizzChat_get_chat_list',
                url: json_end_point,
                data: client_data,
                dataType: 'json',
                crossDomain: true,
                cache: false,
                async: true,
                xhrFields: {
                    withCredentials: true
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', whizzChat_ajax_object.nonce);

                    $.whizzChat_ajax_loaded9.loaded = false;
                },
            }).done(function (data) {

                if (data['success'] == true && data['data']['chat_list'] != "") {
                    $(".chatbox.group-chat.chatbox-list").replaceWith(data['data']['chat_list']);
                }
                if (data['success'] == true && data['data']['chat_boxes']) {

                    if (typeof data['data']['chat_boxes'] != 'undefined' && data['data']['chat_boxes'] !== '') {

                        $.each(JSON.parse(data['data']['chat_boxes']), function (i, item) {
                            if (item) {
                                var post_id = (item['post_id']);
                                var chat_id = (item['chat_id']);
                                var html = (item['html']);

                                var blocked_data = jQuery(".chat-messages").find(".whizzChat-block-user-p");
                                if (blocked_data.length > 0) {
                                    return;
                                }

                                if (html != '') {
                                    var sound_switch = whizzchat_getCookie('whizz_sound_enable');
                                    if (typeof sound_switch !== 'undefined' && sound_switch == 'on') {
                                        jQuery('#whizzchat-notify').trigger("click");
                                    }
                                }
                                var is_online = (item['is_online']);
                                var online = $("div[data-chat-id=" + chat_id + "] .chat-group-name span.status").hasClass('online');
                                var offline = $("div[data-chat-id=" + chat_id + "] .chat-group-name span.status").hasClass('donot-disturb');
                                if (is_online != "")
                                {
                                    $("div[data-chat-id=" + chat_id + "] .chat-group-name span.status").removeClass("donot-disturb");
                                    $("div[data-chat-id=" + chat_id + "] .chat-group-name span.status").removeClass("offline");
                                    $("div[data-chat-id=" + chat_id + "] .chat-group-name span.status").addClass("online");
                                } else
                                {
                                    $("div[data-chat-id=" + chat_id + "] .chat-group-name span.status").removeClass("online");
                                    $("div[data-chat-id=" + chat_id + "] .chat-group-name span.status").addClass("donot-disturb");
                                }
                                var dd = "div.whizz-chat-body div[data-chat-id=" + chat_id + "] .chat-messages div.message-box-holder:last";
                                var rmv_span = "div.whizz-chat-body div[data-chat-id=" + chat_id + "] .chat-messages span.whizzChat-chat-messages-last";
                                $(rmv_span).remove();
                                $(dd).after(html);
                                var last_partner = "div.whizz-chat-body div[data-chat-id=" + chat_id + "] .chat-messages div.main-message-partner:last";
                                var last_chat_id = $(last_partner).data('chat-last-seen');
                                if (undefined === last_chat_id || last_chat_id != "")
                                {
                                    $('div.whizz-chat-body div[data-chat-id=' + chat_id + ']').find('.chatbox-top').removeClass("chatbox-unread-message");
                                } else
                                {
                                    $('div.whizz-chat-body div[data-chat-id=' + chat_id + ']').find('.chatbox-top').addClass("chatbox-unread-message");
                                }

                            } else {
                                $("div.whizz-chat-body div[data-post-id=" + i + "]").removeHTML();
                            }
                            if (html != "") {
                                var dd_bottom = "div.whizz-chat-body div[data-chat-id=" + chat_id + "] .chat-messages";
                                $(dd_bottom).prop({scrollTop: $(dd_bottom).prop("scrollHeight")});
                            }
                        });

                    }

                }


                $.whizzChat_ajax_loaded9.loaded = true;
                $.whizzChat_ajax_loaded9.timerx = 1;
            });
        }

    }

    $.whizzChat_ajax_blocked3 = {loaded: false, timerx: 0}
    function whizzChat_load_chat_box() {

        if ($.whizzChat_ajax_blocked3.timerx != 0) {
            if ($.whizzChat_ajax_blocked3.loaded == false)
                return '';
        }
        if (typeof whizzchat_between !== 'undefined' && whizzchat_between == '1') {
            return false;
        }
        var chat_list = [];
        var chat_id;
        var session_id = whizz_user_token_js(whizzChat_ajax_object.whizz_user_token);
          
        if($('div.chatbox-inner-list .chatbox.group-chat.chatbox-list ul').length  > 0){
           
        var li_exists = $("div.chatbox-inner-list .chatbox.group-chat.chatbox-list ul li").length;
        
        li_exists = typeof li_exists !== 'undefined' && li_exists > 0 ? li_exists : 0;

        $("div.chatbox-inner-list .chatbox.group-chat.chatbox-list ul li").each(function (index) {
            chat_id = $(this).attr('id');
            chat_list.push(chat_id);
        });

        var json_list_ids = JSON.stringify(chat_list);
        var client_data = {
            action: 'whizzChat_get_chat_list_box',
            session: session_id,
            nonce: whizzChat_ajax_object.nonce,
            list_ids: json_list_ids,
            list_data: li_exists,
        };
        var json_end_point = whizzChat_ajax_object.whizz_restapi_endpoint + '/get-chat-list-box';
        $.ajax({
            type: 'POST',
            action: 'whizzChat_get_chat_list',
            url: json_end_point,
            data: client_data,
            dataType: 'json',
            crossDomain: true,
            cache: false,
            async: true,
            xhrFields: {
                withCredentials: true
            },
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', whizzChat_ajax_object.nonce);
                $.whizzChat_ajax_blocked3.loaded = false;
            },
        }).done(function (response) {
            
            $.each(JSON.parse(response['list_ids']), function (i, item) {
                if (typeof response['list_html'] !== 'undefined' && response['list_html'] != '') {
                    $("div.chatbox-inner-list div.whizz-chat-list ul li").remove();
                    $("div.chatbox-inner-list div.whizz-chat-list ul").prepend(JSON.parse(response['list_html']));
                    if ($("div.chatbox-inner-list div.whizz-chat-list p").hasClass('nochat')) {
                        $("div.chatbox-inner-list div.whizz-chat-list p.nochat.text-center").remove();
                    }
                }
                return false;

            });

            $.whizzChat_ajax_blocked3.loaded = true;
            $.whizzChat_ajax_blocked3.timerx = 1;

        });
    }
    }
    $.whizzChat_ajax_block1 = {loaded: false, timerx: 0}
    function whizzChat_load_chat() {
        
       
        
        if ($.whizzChat_ajax_block1.timerx != 0) {
            if ($.whizzChat_ajax_block1.loaded == false)
                return '';
        }
        
        if ($('.chatbox-inner-holder div').length != 0) {
            
        var chat_list = [];
        var message_ids = [];
        $("div.chatbox-holder div.chatbox-inner-holder div.individual-chat-box").each(function (index) {
            var post_id = $(this).attr('data-post-id');
            var chat_id = $(this).attr('data-chat-id');
            var this_var = $(this);
            var get_chat_id = $('#' + chat_id + ' div.message-box-holder:last').data('chat-unique-id');
            
            var chat_load_switch = $('#get-chat-switch-' + chat_id + '').val();
            if (get_chat_id != "") {
                message_ids.push({"chat_id": chat_id, "get_message_id": get_chat_id});
            }
            if ((post_id != "" && chat_id != "") && (post_id != null && chat_id != null)) {
                chat_list.push({"chat_id": chat_id, "post_id": post_id, });
            }
        });
        
        


        var session_id = whizz_user_token_js(whizzChat_ajax_object.whizz_user_token);
        var message_ids = JSON.stringify(message_ids);
        var jsonString = JSON.stringify(chat_list);
        
            
        var client_data = {
            action: 'whizzChat_get_chat_list',
            url: window.location.href,
            session: session_id,
            nonce: whizzChat_ajax_object.nonce,
            chat_boxs: '',
            message_ids: message_ids,
            boxs: jsonString,
        };
        var json_end_point = whizzChat_ajax_object.whizz_restapi_endpoint + '/get-chat-list';

        $.ajax({
            type: 'POST',
            action: 'whizzChat_get_chat_list',
            url: json_end_point,
            data: client_data,
            dataType: 'json',
            crossDomain: true,
            cache: false,
            async: true,
            xhrFields: {
                withCredentials: true
            },
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', whizzChat_ajax_object.nonce);
                $.whizzChat_ajax_block1.loaded = false;
            },
        }).done(function (data) {


            console.log(data);
            
            if (data['success'] == true && data['data']['chat_list'] != "") {
                $(".chatbox.group-chat.chatbox-list").replaceWith(data['data']['chat_list']);
            }

            if (data['success'] == true && data['data']['chat_boxes']) {

                if (typeof data['data']['chat_boxes'] != 'undefined' && data['data']['chat_boxes'] !== '') {

                    $.each(JSON.parse(data['data']['chat_boxes']), function (i, item) {
                        if (item) {
                            var post_id = (item['post_id']);
                            var chat_id = (item['chat_id']);
                            var html = (item['html']);
                            var blocked_data = jQuery(".chat-messages").find(".whizzChat-block-user-p");
                            if (blocked_data.length > 0) {
                                return;
                            }

                            var is_online = (item['is_online']);
                            var online = $("div[data-chat-id=" + chat_id + "] .chat-group-name span.status").hasClass('online');
                            var offline = $("div[data-chat-id=" + chat_id + "] .chat-group-name span.status").hasClass('donot-disturb');
                            if (is_online != "")
                            {
                                $("div[data-chat-id=" + chat_id + "] .chat-group-name span.status").removeClass("donot-disturb");
                                $("div[data-chat-id=" + chat_id + "] .chat-group-name span.status").removeClass("offline");
                                $("div[data-chat-id=" + chat_id + "] .chat-group-name span.status").addClass("online");
                            } else
                            {
                                $("div[data-chat-id=" + chat_id + "] .chat-group-name span.status").removeClass("online");
                                $("div[data-chat-id=" + chat_id + "] .chat-group-name span.status").addClass("donot-disturb");
                            }
                            var dd = "div[data-chat-id=" + chat_id + "] .chat-messages div.message-box-holder:last";
                            var rmv_span = "div[data-chat-id=" + chat_id + "] .chat-messages span.whizzChat-chat-messages-last";
                            $(rmv_span).remove();
                            $(dd).after(html);

                            var wizz_found = {};
                            $('div[data-chat-id=' + chat_id + '] [data-chat-unique-id]').each(function () {
                                var $this = $(this);
                                if (wizz_found[$this.data('chat-unique-id')]) {
                                    $this.remove();
                                } else {
                                    wizz_found[$this.data('chat-unique-id')] = true;
                                }
                            });

                            if ($("div[data-chat-id=" + chat_id + "] .message-box-holder:last").hasClass("main-message-partner") && html != "") {

                                var sound_switch = whizzchat_getCookie('whizz_sound_enable');
                                if (typeof sound_switch !== 'undefined' && sound_switch == 'on') {
                                    jQuery('#whizzchat-notify').trigger("click");
                                }
                            }

                            var last_partner = "div[data-chat-id=" + chat_id + "] .chat-messages div.main-message-partner:last";
                            var last_chat_id = $(last_partner).data('chat-last-seen');
                            if (undefined === last_chat_id || last_chat_id != "")
                            {
                                $('div[data-chat-id=' + chat_id + ']').find('.chatbox-top').removeClass("chatbox-unread-message");
                            } else
                            {

                            }
                        } else {
                            $("div[data-post-id=" + i + "]").removeHTML();
                        }



                        if (html != "") {
                            var dd_bottom = "div[data-chat-id=" + chat_id + "] .chat-messages";
                            $(dd_bottom).prop({scrollTop: $(dd_bottom).prop("scrollHeight")});
                        }
                    });
                }
            }

            $.whizzChat_ajax_block1.loaded = true;
            $.whizzChat_ajax_block1.timerx = 1;
        });
        }

    }

    $(".individual-chat-box .chat-messages").scroll(function () {

        var session_id = whizz_user_token_js(whizzChat_ajax_object.whizz_user_token);

        if ($(this).scrollTop() < 1) {
            // load 10 more old data to div
            var this_var = $(this);
            var last_chat_id = $(this).find("div.message-box-holder").data('chat-unique-id');
            var post_id = $(this).parents().filter(function () {
                return $(this).data("post-id");
            }).eq(0).data("post-id");
            var chat_id = $(this).parents().filter(function () {
                return $(this).data("chat-id");
            }).eq(0).data("chat-id");

            var scroll_switch = $("#chat-box-" + chat_id + "");
            if (typeof scroll_switch !== 'undefined' && scroll_switch == 'stop') {
                return false;
            }

            $(this).find("span.whizzChat-span-loading").show();
            var client_data = {
                action: 'whizzChat_load_old_chat',
                url: window.location.href,
                session: session_id,
                nonce: whizzChat_ajax_object.nonce,
                last_chat_id: last_chat_id,
                post_id: post_id,
                chat_id: chat_id
            };
            var json_end_point = whizzChat_ajax_object.whizz_restapi_endpoint + '/load-old-chat';
            $.ajax({
                type: 'POST',
                action: 'whizzChat_load_old_chat',
                url: json_end_point,
                data: client_data,
                dataType: 'json',
                crossDomain: true,
                cache: false,
                async: true,
                xhrFields: {withCredentials: true},
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', whizzChat_ajax_object.nonce);
                },
            }).done(function (data) {
                if (console && console.log)
                {
                    if (typeof data !== 'undefined' && data != '') {
                        this_var.scrollTop(1);
                        this_var.prepend(data);
                        this_var.find("span.whizzChat-span-loading").hide();
                        var new_date = this_var.find("span.whizzChat-date-sort:first").data('group-message-date');
                        if (this_var.find("span.whizzChat-date-sort[data-group-message-date=" + new_date + "]").length >= 2)
                        {
                            this_var.find("span.whizzChat-date-sort[data-group-message-date=" + new_date + "]:last").remove();
                        }

                    } else {
                        $("#chat-box-" + chat_id + "").val('stop');
                        this_var.find("span.whizzChat-span-loading").hide();
                    }

                } else {

                }
            });
        }
    });

    setInterval(whizzChat_load_chat_message_notify, 8000);
    function whizzChat_load_chat_message_notify() {
        $(".individual-chat-box .chat-messages").each(function (index) {
            var last_chat_id = $(this).find("div.main-message-partner:last").data('chat-last-seen');
            if (undefined === last_chat_id || last_chat_id != "")
            {
                $('div[data-chat-id=' + chat_id + ']').find('.chatbox-top').removeClass("chatbox-unread-message");
            } else
            {
                var post_id = $(this).parents().filter(function () {
                    return $(this).data("post-id");
                }).eq(0).data("post-id");
                var chat_id = $(this).parents().filter(function () {
                    return $(this).data("chat-id");
                }).eq(0).data("chat-id");

            }
        });
    }

    $("body").delegate(".whizz-chat-list-close", "click", function ()
    {

        $('.chatbox-list').toggleClass("chatlist-min");
    });

    $("body").delegate(".whizzchat-bot-close", "click", function ()
    {
        jQuery(jQuery('.chatbox-holder-bot')).hide('slow', function () {
            jQuery(".chatbox-holder-bot").remove();
        });
    });

    /*
     * Admin functions
     */

    $(document).on('click', '.attachment-panel-admin a.fa.fa-map-marker', function () {

        var msg = '';
        var chat_id = $(this).parent().attr('data-chat-id');
        var post_id = $(this).parent().attr('data-post-id');
        var ad_author_id = $(this).parent().attr('data-author-id');
        var comm_id = $(this).parent().attr('data-comm-id');
        var room = $(this).parent().attr('data-room');//


        var message_ids = $('div.message-box-holder:last').data('chat-unique-id');
        var session_id = whizz_user_token_js(whizzChat_ajax_object.whizz_user_token);

        if (typeof chat_id !== 'undefined' && chat_id != '' && typeof post_id !== 'undefined' && post_id != '') {
            if ($(this).hasClass("fa-map-marker"))
            {
                if (!navigator.geolocation) {
                    alert(whizzChat_ajax_object.browser_not_support);
                } else {
                    navigator.geolocation.getCurrentPosition(function (position)
                    {

                        $('a[data-chat-id="' + chat_id + '"]').html('<i class="fas fa-spinner fa-spin"></i>');

                        var lat = position.coords.latitude;
                        var lng = position.coords.longitude;
                        var map_lat_long = [];
                        if (lat != "" && lng != "") {
                            map_lat_long.push({"latitude": lat, "longitude": lng});
                        }
                        var location = {"latitude": lat, "longitude": lng};
                        var mapString = JSON.stringify(location);

                        if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {
                            socket.emit('agRoomJoined', room, session_id, comm_id);
                        }

                        var client_data = {
                            action: 'whizzChat_send_chat_message_admin',
                            url: window.location.href,
                            chat_id: chat_id,
                            post_id: post_id,
                            msg: msg,
                            upload_type: 'map',
                            message_type: 'map',
                            map_data: mapString,
                            message_ids: message_ids,
                            session: session_id,
                            nonce: whizzChat_ajax_object.nonce
                        };
                        var json_end_point = whizzChat_ajax_object.whizz_restapi_endpoint + '/send-chat-message-admin';
                        $.ajax({
                            type: 'POST',
                            action: 'whizzChat_send_chat_message_admin',
                            url: json_end_point,
                            data: client_data,
                            dataType: 'json',
                            crossDomain: true,
                            cache: false,
                            async: true,
                            xhrFields: {
                                withCredentials: true
                            },
                            beforeSend: function (xhr) {
                                xhr.setRequestHeader('X-WP-Nonce', whizzChat_ajax_object.nonce);
                            },
                        }).done(function (data) {

                            if (data['success'] = true && data['data']['chat_boxes']) {
                                var json_data = JSON.parse(data['data']['chat_boxes']);
                                var post_id = (json_data['post_id']);
                                var chat_id = json_data['chat_id'];
                                $('a[data-chat-id="' + chat_id + '"]').html('Send');
                                var room = whizzChat_ajax_object.whizzcaht_room + chat_id + '';
                                if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {
                                    socket.emit('agSendMessage', room, msg, ad_author_id, chat_id);
                                }
                                var html = (json_data['html']);
                                var dd = "div.whizz-chat-body div[data-chat-id=" + chat_id + "] .chat-messages div.message-box-holder:last";
                                $(dd).after(html);

                                var time_text = $(dd).data('chat-last-seen');
                                var time_div = "div[data-chat-id=" + chat_id + "] .chat-messages whizzChat-chat-messages-last";
                                $(time_div).html(time_text);
                                var dd_bottom = "div[data-chat-id=" + chat_id + "] .chat-messages";
                                $(dd_bottom).prop({scrollTop: $(dd_bottom).prop("scrollHeight")});
                                $('.whizz-btn-wrap-' + chat_id + '').html('<i class="fas fa-chevron-right message-send initate-chat-input-btn"></i>');
                                jQuery('.whizz-chat-body textarea.whizzChat-emoji').val('');
                                jQuery('.whizz-chat-body .emojionearea-editor').html('');
                            }
                            $('a[data-chat-id="' + chat_id + '"]').html('Send');
                        });

                    }, function (error) {
                        if (error.code == error.PERMISSION_DENIED)
                        {
                            alert(whizzChat_ajax_object.enable_location);
                        }
                    });
                }

            }
        } else {
            alert(whizzChat_ajax_object.select_chat_room);
        }
    });

    $(document).on('change', '.attachment-panel-admin .whizzChat-file-admin, .attachment-panel-admin .whizzChat-image-admin', function () {
        var upload_type = '';
        var chat_id = $(this).parent().parent().parent().attr('data-chat-id');
        var post_id = $(this).parent().parent().parent().attr('data-post-id');
        var comm_id = $(this).parent().parent().parent().attr('data-comm-id');
        var room = $(this).parent().parent().parent().attr('data-room');

        var ad_author_id = $(this).parent().parent().parent().attr('data-author-id');
        var message_ids = $('div.message-box-holder:last').data('chat-unique-id');

        if (typeof chat_id !== 'undefined' && chat_id != '' && typeof post_id !== 'undefined' && post_id != '') {
            if ($(this).hasClass("whizzChat-file-admin"))
            {
                var upload_type = 'file';
                var file_max_size = whizzChat_ajax_object.whizz_file.size;
                var file_extension = whizzChat_ajax_object.whizz_file.format;
            } else if ($(this).hasClass("whizzChat-image-admin"))
            {
                var upload_type = 'image';
                var file_max_size = whizzChat_ajax_object.whizz_image.size;
                var file_extension = whizzChat_ajax_object.whizz_image.format;
            } else
            {
                alert(whizzChat_ajax_object.not_valid_type);
                return '';
            }

            /*Validate File Type*/
            var file_obj = $(this).prop('files');
            var form_data = new FormData();
            for (var i = 0; i < file_obj.length; i++)
            {
                var file_size = file_obj[i].size;
                var file_name = file_obj[i].name;
                var file_type = file_obj[i].type;
                var is_added = true;
                if (jQuery.inArray(file_name.split('.').pop().toLowerCase(), file_extension) == -1)
                {
                    var is_added = false;
                    alert(whizzChat_ajax_object.not_valid_type);
                    return '';
                }

                if (file_size > file_max_size)
                {
                    var is_added = false;
                    alert(whizzChat_ajax_object.size_not_valid);
                    return '';
                }

                if (is_added == true) {
                    form_data.append('file[]', file_obj[i]);
                }
            }
            $('a[data-chat-id="' + chat_id + '"]').html('<i class="fas fa-spinner fa-spin"></i>');
            var session_id = whizz_user_token_js(whizzChat_ajax_object.whizz_user_token);
            var msg = '';
            form_data.append('chat_id', chat_id);
            form_data.append('post_id', post_id);
            form_data.append('action', 'whizzChat_send_chat_message_admin');
            form_data.append('session', session_id);
            form_data.append('nonce', whizzChat_ajax_object.nonce);
            form_data.append('url', window.location.href);
            form_data.append('msg', msg);
            form_data.append('upload_type', upload_type);
            form_data.append('message_type', 'image/file');
            form_data.append('message_ids', message_ids);
            var json_end_point = whizzChat_ajax_object.whizz_restapi_endpoint + '/send-chat-message-admin';

            if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {
                socket.emit('agRoomJoined', room, session_id, comm_id);
            }
            $.ajax({
                url: json_end_point,
                type: 'POST',
                contentType: false,
                processData: false,
                data: form_data,
                dataType: 'json',
                crossDomain: true,
                cache: false,
                async: true,
                xhrFields: {
                    withCredentials: true
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', whizzChat_ajax_object.nonce);
                },
            }).done(function (data) {

                var json_data = JSON.parse(data['data']['chat_boxes']);
                var post_id = (json_data['post_id']);
                var chat_id = json_data['chat_id'];
                var html = (json_data['html']);
                $('a[data-chat-id="' + chat_id + '"]').html('Send');
                var dd = "div.whizz-chat-body div[data-chat-id=" + chat_id + "] .chat-messages div.message-box-holder:last";
                $(dd).after(html);
                if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {
                    socket.emit('agSendMessage', room, msg, comm_id, chat_id);
                }
            });

        } else {
            alert(whizzChat_ajax_object.select_chat_room);
        }
    });

});

function whizzchat_playSound(filename) {
    var mp3Source = '<source src="' + filename + '.mp3" type="audio/mpeg">';
    var oggSource = '<source src="' + filename + '.ogg" type="audio/ogg">';
    var embedSource = '<embed hidden="true" autostart="true" loop="false" src="' + filename + '.mp3">';
    document.getElementById("sound").innerHTML = '<audio autoplay="autoplay">' + mp3Source + oggSource + embedSource + '</audio>';
}

function open_whizz_chat(id, open_type = '') {

    var max_chatbox_window = whizzChat_ajax_object.max_chatbox_window;
    var max_chat_box = typeof max_chatbox_window !== 'undefined' && max_chatbox_window != '' ? max_chatbox_window : 3;

    var boxes_length = 0;
    if (jQuery("[data-chat-id='" + id + "']").is(':visible'))
    {
        var is_show = 0;
        boxes_length = jQuery(".individual-chat-box .chat-messages").length - 1;
    } else
    {
        var is_show = 1;
        boxes_length = jQuery(".individual-chat-box .chat-messages").length + 1;
    }



    var last_chat_box = 0;
    if (boxes_length > max_chat_box) {
        var last_chat_box_obj = jQuery(".individual-chat-box").first();

        var targt_win = jQuery("[data-chat-id='" + jQuery(last_chat_box_obj).attr('id') + "']");
        jQuery(targt_win).hide('slow', function () {
            jQuery(targt_win).remove();
        });
        last_chat_box = jQuery(last_chat_box_obj).attr('id');
    }

    if (is_show == 1)
    {

        jQuery("div[data-chat-id='" + id + "']").show('slow', function () {
            jQuery("div[data-chat-id='" + id + "']").show();
        });
    } else
    {

        if (open_type != 'list') {
            jQuery("div[data-chat-id='" + id + "']").hide('slow', function () {
                jQuery("div[data-chat-id='" + id + "']").remove();
            });
        }
    }

    var always_open_var = 0;
    if (open_type == 'list') {
        var always_open_var = 1;
    }
    var json_end_point = whizzChat_ajax_object.whizz_restapi_endpoint + '/get-chat-box';

    var session_id = whizz_user_token_js(whizzChat_ajax_object.whizz_user_token);
    var client_data = {
        session: session_id,
        nonce: whizzChat_ajax_object.nonce,
        chat_id: id,
        is_show: is_show,
        boxes_length: boxes_length,
        last_chat_box: last_chat_box,
        always_open: always_open_var,
    };
    jQuery.ajax({
        type: 'POST',
        action: 'whizzChat_get_chat_box',
        url: json_end_point,
        data: client_data,
        dataType: 'json',
        crossDomain: true,
        cache: false,
        xhrFields: {
            withCredentials: true
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', whizzChat_ajax_object.nonce);
        },
    }).done(function (data) {
        if (jQuery("[data-chat-id='" + id + "']").length == 0) {

            
                
            jQuery('div.chatbox-holder .chatbox-inner-holder').append(data);

               var dd_bottom = "div[data-chat-id=" + id + "] .chat-messages";
        $(dd_bottom).prop({scrollTop: $(dd_bottom).prop("scrollHeight")});




             var show_emoji    =  whizzChat_ajax_object.show_emoji;
             
             if(show_emoji ==  "1"){

            jQuery("[data-chat-id='" + id + "'] .whizzChat-emoji").emojioneArea({
                pickerPosition: "top",
                filtersPosition: "bottom",
                tones: false,
                spellcheck: true,
                autocomplete: false,
                hidePickerOnBlur: true,
                saveEmojisAs: 'unicode',
                placeholder: whizzChat_ajax_object.type_something,
                events: {
                    focus: function (editor, event) {
                        var chat_id = editor.parent().parent().attr('data-chat-id');
                        var user_name = editor.parent().parent().attr('data-user-name');
                        var msg = user_name + ' is typing ';
                        var room = editor.parent().parent().attr("data-room");

                        if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {

                            socket.emit('agTyping', room, msg, chat_id);  // 
                        }
                    },
                    blur: function (editor, event) {
                        var chat_id = editor.parent().parent().attr('data-chat-id');
                        var room = editor.parent().parent().attr("data-room");

                        if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {
                            socket.emit('agStopTyping', room, chat_id);
                        }
                    },
                }
            });}
        }

    });

}

function admin_chat_messages_scroll() {
    var as = '.whizz-chat-body .col-7.px-0 .px-4 py-5.chat-box.bg-white .individual-chat-box .chat-messages';
    jQuery(as).scroll(function () {
    });
    jQuery(as).on('scroll', function () {
    });
}

function open_whizz_chat_admin(id, post_id, this_obj, comm_id, room) {

    var boxes_length = 1;

    if (jQuery('.messages-box .list-group a').hasClass('active')) {
        jQuery('.messages-box .list-group a').removeClass('active');
    }
    jQuery(this_obj).addClass('active'); // ad class to the current object

    jQuery(this_obj).find('span.badge-light').remove();
    //badge badge-light
    var is_show = 1;
    var chat_box_ids = [];
    var last_chat_box = id;
    chat_box_ids.push(id);
    var ad_author_id = jQuery(this_obj).attr('data-author-id');
    jQuery('.indivisual-chat-area').show(); // set chat id for message send button.
    jQuery('.initate-chat-admin').attr('data-chat-id', id); // set chat id for message send button.
    jQuery('.initate-chat-admin').attr('data-post-id', post_id); // set chat id for message send button.
    jQuery('.initate-chat-admin').attr('data-author-id', ad_author_id); // set chat id for message send button.
    jQuery('.initate-chat-admin').attr('data-comm-id', comm_id); // set chat id for message send button.
    jQuery('.initate-chat-admin').attr('data-room', room); // set chat id for message send button.

    jQuery('.attachment-panel-admin').attr('data-chat-id', id); // set chat id for message send button.
    jQuery('.attachment-panel-admin').attr('data-post-id', post_id); // set chat id for message send button.
    jQuery('.attachment-panel-admin').attr('data-author-id', ad_author_id); // set chat id for message send button.
    jQuery('.attachment-panel-admin').attr('data-comm-id', comm_id); // set chat id for message send button.
    jQuery('.attachment-panel-admin').attr('data-room', room); // set chat id for message send button.


    jQuery('div.input-group.indivisual-chat-area').attr('data-chat-id', id); // set chat id for message send button.
    jQuery('div.input-group.indivisual-chat-area').attr('data-post-id', post_id); // set chat id for message send button.
    jQuery('div.input-group.indivisual-chat-area').attr('data-room', room); // set chat id for message send button.

    jQuery('div.chat-box.bg-white').addClass('admin-chat-wrapper'); // set chat id for message send button.
    jQuery('div.chat-box.bg-white').attr('data-chat-id', id); // set chat id for message send button.
    jQuery('div.chat-box.bg-white').attr('data-post-id', post_id); // set chat id for message send button.
    var chat_id = id;

    var session_id = whizz_user_token_js(whizzChat_ajax_object.whizz_user_token);

    if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {
        socket.emit('agRoomJoined', room, session_id, comm_id);
    }
    var json_end_point = whizzChat_ajax_object.whizz_restapi_endpoint + '/get-chat-box-admin';
    var client_data = {
        session: whizzChat_ajax_object.whizz_user_token,
        nonce: whizzChat_ajax_object.nonce,
        chat_id: id,
    };

    jQuery.ajax({
        type: 'POST',
        action: 'whizzChat_get_chat_box_admin',
        url: json_end_point,
        data: client_data,
        dataType: 'json',
        crossDomain: true,
        cache: false,
        xhrFields: {
            withCredentials: true
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', whizzChat_ajax_object.nonce);
        },
    }).done(function (response) {
        jQuery('.chat-box.bg-white').html('');
        if (typeof response !== 'undefined' && response != '') {
            jQuery('.chat-box.bg-white').append(response.data);
            jQuery('.admin-chatbox-icons .settings-popup ul').html(response.block_data);
        }
        jQuery(".whizz-chat-body .chat-box").scroll(function (e) {
            whizzchat_load_old_chat_admin(jQuery(this));
        });
    });
}

function whizzchat_load_old_chat_admin(this_obj) {

    var $ = jQuery;
    if (this_obj.scrollTop() < 1) {
        var this_var = this_obj;
        var last_chat_id = this_obj.find("div.message-box-holder").data('chat-unique-id');
        var post_id = this_obj.attr("data-post-id");
        var chat_id = this_obj.attr("data-chat-id");
        var client_data = {
            action: 'whizzChat_load_old_chat',
            url: window.location.href,
            session: whizzChat_ajax_object.whizz_user_token,
            nonce: whizzChat_ajax_object.nonce,
            last_chat_id: last_chat_id,
            post_id: post_id,
            chat_id: chat_id
        };
        var json_end_point = whizzChat_ajax_object.whizz_restapi_endpoint + '/load-old-chat-admin';
        $.ajax({
            type: 'POST',
            action: 'whizzChat_load_old_chat',
            url: json_end_point,
            data: client_data,
            dataType: 'json',
            crossDomain: true,
            cache: false,
            async: true,
            xhrFields: {withCredentials: true},
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', whizzChat_ajax_object.nonce);
            },
        }).done(function (data) {
            if (console && console.log)
            {
                this_var.scrollTop(1);
                this_var.prepend(data);
                this_var.find("span.whizzChat-span-loading").hide();

                var new_date = this_var.find("span.whizzChat-date-sort:first").data('group-message-date');
                if (this_var.find("span.whizzChat-date-sort[data-group-message-date=" + new_date + "]").length >= 2)
                {
                    this_var.find("span.whizzChat-date-sort[data-group-message-date=" + new_date + "]:last").remove();
                }
            }
        });
    }
}

function whizzchat_strip_html(html) {

    if (typeof html !== 'undefined' && html != '') {
        var doc = new DOMParser().parseFromString(html, 'text/html');
        return doc.body.textContent || "";
    } else {
        return '';
    }
}
$("body").delegate(".whizzChat-sound-switch", "click", function () {

    var sound_val = jQuery(this).attr('data-sound-val');
    var text_val = jQuery(this).attr('data-replace-text');
    var obj_this = jQuery(this);

    var client_data = {
        action: 'whizzChat_load_old_chat',
        url: window.location.href,
        nonce: whizzChat_ajax_object.nonce,
        sound_val: sound_val,
        text_val: text_val
    };
    var json_end_point = whizzChat_ajax_object.whizz_restapi_endpoint + '/whizz-chat-sound';
    $.ajax({
        type: 'POST',
        action: 'whizzChat_sound_switch',
        url: json_end_point,
        data: client_data,
        dataType: 'json',
        crossDomain: true,
        cache: false,
        async: true,
        xhrFields: {withCredentials: true},
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', whizzChat_ajax_object.nonce);
        },
    }).done(function (data) {
        obj_this.attr('data');
        obj_this.attr('data-sound-val', data.sound_val);
        obj_this.attr('data-replace-text', data.text_val);
        obj_this.html(data.text_val);
    });
});

jQuery('body').on('click', '.whizzchat-reset', function () {

    var reset_db = confirm(whizzChat_ajax_object.confirm_remove_db);
    if (reset_db == true) {
        var client_data = {
            session: whizzChat_ajax_object.whizz_user_token,
            nonce: whizzChat_ajax_object.nonce,
        };
        var json_end_point = whizzChat_ajax_object.whizz_restapi_endpoint + '/reset-whizchat-data';
        jQuery.ajax({
            type: 'POST',
            url: json_end_point,
            data: client_data,
            dataType: 'json',
            crossDomain: true,
            cache: false,
            async: true,
            xhrFields: {withCredentials: true},
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', whizzChat_ajax_object.nonce);
            },
        }).done(function (data) {
            if (data == 'true') {
                alert(whizzChat_ajax_object.reset_db_success);
                window.location.href;
            } else {
                alert(whizzChat_ajax_object.went_wrong);
            }
        });
    }
});


//on click ajax call to load chats
$(document).ready(function () {

    if ($('.chat_toggler').length > 0) {       
        $('.chat_toggler').on('click', function (e) {           
            
            
            if ($('.chatbox-inner-holder div').children().length ==0) {
                var btn = $(this);
                btn.attr("disabled", true);
                var page_id = btn.data('page_id');
                var user_id = btn.data('user_id');
               
                var show_emoji    =  whizzChat_ajax_object.show_emoji;
                $.post(
                        whizzChat_ajax_object.whizz_ajax_url,
                        {
                            action: 'whizchat_initilze_chat',
                            wc_nonce: whizzChat_ajax_object.nonce,
                            page_id: page_id,
                            user_id : user_id,
                        })
                        .done(function (response) {                      
                            if (response['success'] ==  false) {
                              alert(response['data']['message']);
                            } else {                                                        
                                 var chat_box     =       response['data']['chat_boxes'];
                                 var chat_list    =    response['data']['chat_list'];                             
                                $('.chatbox-inner-list').html(chat_list);
                                $('.chatbox-inner-holder').html(chat_box);

                                btn.attr("disabled", false);
                                $('.chatbox-holder').removeClass("no_chat");
                             if(show_emoji == "1"){
                                jQuery(".whizzChat-emoji").emojioneArea({
                                    pickerPosition: "top",
                                    filtersPosition: "bottom",
                                    tones: false,
                                    spellcheck: true,
                                    autocomplete: false,
                                    hidePickerOnBlur: true,
                                    saveEmojisAs: 'unicode',                                  
                                    events: {
                                        focus: function (editor, event) {
                                            var chat_id = jQuery(".whizz-dash-chat-body").attr('data-chat-id');
                                            var msg = ' is typing ';
                                            var room = jQuery(".whizz-dash-chat-body").attr("data-room");
                                            if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {
                                                socket.emit('agTyping', room, msg, chat_id);  // 
                                            }
                                        },
                                        blur: function (editor, event) {
                                            var chat_id = jQuery(".whizz-dash-chat-body").attr('data-chat-id');
                                            var room = jQuery(".whizz-dash-chat-body").attr("data-room");
                                            if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {
                                                socket.emit('agStopTyping', room, chat_id);
                                            }
                                        },
                                    }
                                }); 
                            }

                            }
                        });
            } else {
                $('.chatbox-holder').toggleClass("no_chat");

            }
        });
    }
});

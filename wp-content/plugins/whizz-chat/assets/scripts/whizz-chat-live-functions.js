/*
 * 
 * Whizz-caht Live communication Functions
 * 
 */
var $ = jQuery;
var whizzchat_live_enable = jQuery("#whizz-chat-live").val();
var whizzchat_between = jQuery("#whizz-chat-between").val();

$.whizzChat_ajax_blocked6={loaded:false, timerx:0 }
function whizzChat_load_chat_box_live() {


    if($.whizzChat_ajax_blocked6.timerx != 0){
        if( $.whizzChat_ajax_blocked6.loaded == false ) return '';
    }

    if (typeof whizzchat_between !== 'undefined' && whizzchat_between == '1') {
        return false;
    }
    var chat_list = [];
    var chat_id;

    var session_id = whizz_user_token_js(whizzChat_live_object.whizz_user_token);

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
        nonce: whizzChat_live_object.nonce,
        list_ids: json_list_ids,
        list_data: li_exists,
    };
    var json_end_point = whizzChat_live_object.whizz_restapi_endpoint + '/get-chat-list-box';
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
            xhr.setRequestHeader('X-WP-Nonce', whizzChat_live_object.nonce);
            $.whizzChat_ajax_blocked6.loaded = false; 
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


            $.whizzChat_ajax_blocked6.loaded = true;
            $.whizzChat_ajax_blocked6.timerx = 1;           
    });
}

$.whizzChat_ajax_blocked5={loaded:false, timerx:0 }

function whizzChat_load_chat_box_admin_live() {

    if($.whizzChat_ajax_blocked5.timerx != 0){
        if( $.whizzChat_ajax_blocked5.loaded == false ) return '';
    }
    var chat_list = [];
    var chat_id;

    var session_id = whizz_user_token_js(whizzChat_live_object.whizz_user_token);

    var li_exists = $(".chats-tab-open aside.whizz-sidebar ul.contacts-list li").length;
    li_exists = typeof li_exists !== 'undefined' && li_exists > 0 ? li_exists : 0;

    $(".chats-tab-open aside.whizz-sidebar ul.contacts-list li").each(function (index) {
        chat_id = $(this).attr('id');
        chat_list.push(chat_id);
    });

    var json_list_ids = JSON.stringify(chat_list);
    var client_data = {
        action: 'whizzChat_get_chat_list_box_dashboard',
        session: session_id,
        nonce: whizzChat_live_object.nonce,
        list_ids: json_list_ids,
        list_data: li_exists,
    };
    var json_end_point = whizzChat_live_object.whizz_restapi_endpoint + '/get-chat-list-box-dashboard';
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
            xhr.setRequestHeader('X-WP-Nonce', whizzChat_live_object.nonce);
            
            $.whizzChat_ajax_blocked5.loaded = false;
        },
    }).done(function (response) {
        $.each(JSON.parse(response['list_ids']), function (i, item) {
            if (typeof response['list_html'] !== 'undefined' && response['list_html'] != '') {
                $(".chats-tab-open aside.whizz-sidebar ul.contacts-list li").remove();
                $(".chats-tab-open aside.whizz-sidebar ul.contacts-list").prepend(JSON.parse(response['list_html']));
            }
            return false;

        });
            $.whizzChat_ajax_blocked5.loaded = true;
            $.whizzChat_ajax_blocked5.timerx = 1;         
    });
}


$.whizzChat_ajax_block2={loaded:false, timerx:0 }
function whizz_chat_live_read_message(pass_chat_id) {

    if($.whizzChat_ajax_block2.timerx != 0){
        if( $.whizzChat_ajax_block2.loaded == false ) return '';
    }    
    var chat_list = [];
    var message_ids = [];
    $("div.chatbox-holder div.chatbox-inner-holder div.individual-chat-box").each(function (index) {
        var post_id = $(this).attr('data-post-id');
        var chat_id = $(this).attr('data-chat-id');

        if (pass_chat_id == chat_id) {
            var this_var = jQuery(this);
            var get_chat_id = jQuery('#' + chat_id + ' div.message-box-holder:last').data('chat-unique-id');
            var chat_load_switch = jQuery('#get-chat-switch-' + chat_id + '').val();
            if (get_chat_id != "") {
                message_ids.push({"chat_id": chat_id, "get_message_id": get_chat_id});
            }
            if ((post_id != "" && chat_id != "") && (post_id != null && chat_id != null)) {
                chat_list.push({"chat_id": chat_id, "post_id": post_id, });
            }
        }

    });

    var session_id = whizz_user_token_js(whizzChat_live_object.whizz_user_token);
    var message_ids = JSON.stringify(message_ids);
    var jsonString = JSON.stringify(chat_list);


    var client_data = {
        action: 'whizzChat_get_chat_list',
        url: window.location.href,
        session: session_id,
        nonce: whizzChat_live_object.nonce,
        chat_boxs: '',
        message_ids: message_ids,
        boxs: jsonString,
    };
    var json_end_point = whizzChat_live_object.whizz_restapi_endpoint + '/get-chat-list';

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
            xhr.setRequestHeader('X-WP-Nonce', whizzChat_live_object.nonce);
            
            $.whizzChat_ajax_block2.loaded = false; 
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

                        var is_online = (item['is_online']);
                        var online = $("div[data-chat-id=" + chat_id + "] .chat-group-name span.whizzchat-status").hasClass('online');
                        var offline = $("div[data-chat-id=" + chat_id + "] .chat-group-name span.whizzchat-status").hasClass('donot-disturb');
                        if (is_online != "")
                        {

                            $("div[data-chat-id=" + chat_id + "] .chat-group-name span.whizzchat-status").removeClass("donot-disturb");
                            $("div[data-chat-id=" + chat_id + "] .chat-group-name span.whizzchat-status").removeClass("offline");
                            $("div[data-chat-id=" + chat_id + "] .chat-group-name span.whizzchat-status").addClass("online");
                        } else
                        {

                            $("div[data-chat-id=" + chat_id + "] .chat-group-name span.whizzchat-status").removeClass("online");
                            $("div[data-chat-id=" + chat_id + "] .chat-group-name span.whizzchat-status").addClass("donot-disturb");
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

            $.whizzChat_ajax_block2.loaded = true;
            $.whizzChat_ajax_block2.timerx = 1;         
    });

}


$.whizzChat_ajax_blocked8={loaded:false, timerx:0 }

function whizz_chat_live_read_message_admin() {
    var chat_list = [];
    var message_ids = [];

    if($.whizzChat_ajax_blocked8.timerx != 0){
        if( $.whizzChat_ajax_blocked8.loaded == false ) return '';
    }



    $(".chats-tab-open .whizz-main.main-visible #whizzchat-message-body").each(function (index) {

        var post_id = $(this).data('post-id');
        var chat_id = $(this).data('chat-id');
        var this_var = $(this);
        var get_chat_id = $('.chat-messages-dashb div.message-box-holder-dash:last').data('chat-unique-id');
        var chat_load_switch = $('#get-chat-switch-' + chat_id + '').val();
        if (get_chat_id != "") {
            message_ids.push({"chat_id": chat_id, "get_message_id": get_chat_id});
        }
        if ((post_id != "" && chat_id != "") && (post_id != null && chat_id != null)) {
            chat_list.push({"chat_id": chat_id, "post_id": post_id, });
        }
    });

    var session_id = whizz_user_token_js(whizzChat_live_object.whizz_user_token);

    var message_ids = JSON.stringify(message_ids);
    var jsonString = JSON.stringify(chat_list);
    var client_data = {
        action: 'whizzChat_get_chat_list_dashb',
        url: window.location.href,
        session: session_id,
        nonce: whizzChat_live_object.nonce,
        chat_boxs: '',
        message_ids: message_ids,
        boxs: jsonString,
    };

    var json_end_point = whizzChat_live_object.whizz_restapi_endpoint + '/get-chat-list-dashboard';

    $.ajax({
        type: 'POST',
        action: 'whizzChat_get_chat_list_dashb',
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
            xhr.setRequestHeader('X-WP-Nonce', whizzChat_live_object.nonce);
            
            $.whizzChat_ajax_blocked8.loaded = false;   
        },
    }).done(function (data) {

        if (data['success'] == true && data['data']['chat_boxes']) {

            if (typeof data['data']['chat_boxes'] != 'undefined' && data['data']['chat_boxes'] !== '') {

                $.each(JSON.parse(data['data']['chat_boxes']), function (i, item) {
                    if (item) {
                        var post_id = (item['post_id']);
                        var chat_id = (item['chat_id']);
                        var html = (item['html']);

                        var blocked_data = jQuery(".chat-messages-dashb").find(".whizzChat-block-user-p");

                        if (blocked_data.length > 0) {
                            return;
                        }

                        var is_online = (item['is_online']);
                        var online = $("div[data-chat-id=" + chat_id + "] .chat-group-name span.status").hasClass('online');
                        var offline = $("div[data-chat-id=" + chat_id + "] .chat-group-name span.status").hasClass('donot-disturb');
                        if (is_online != "")
                        {
                            
                            $(".whizzchat-dash-status").removeClass("avatar-away");
                            $(".whizzchat-dash-status").addClass("avatar-online");
                        } else
                        {
                            
                            $(".whizzchat-dash-status").removeClass("avatar-online");
                            $(".whizzchat-dash-status").addClass("avatar-away");
                        }
                        var dd = ".chat-messages-dashb div.message-box-holder-dash:last";
                        $(dd).after(html);

                        var found = {};
                        $('div[data-chat-id=' + chat_id + '] [data-chat-unique-id]').each(function () {
                            var $this = $(this);
                            if (found[$this.data('chat-unique-id')]) {
                                $this.remove();
                            } else {
                                found[$this.data('chat-unique-id')] = true;
                            }
                        });

                        if ($("div[data-chat-id=" + chat_id + "] .message-box-holder-dash:last").hasClass("other") && html != "") {
                            var sound_switch = whizzchat_getCookie('whizz_sound_enable');
                            if (typeof sound_switch !== 'undefined' && sound_switch == 'on') {
                                jQuery('#whizzchat-notify').trigger("click");
                            }
                        }

                        if (html != "") {
                            var dd_bottom = ".chat-content";
                            $(dd_bottom).prop({scrollTop: $(dd_bottom).prop("scrollHeight")});
                        }
                    } else {
                        $("div[data-post-id=" + i + "]").removeHTML();
                    }

                });
            }
        }

            $.whizzChat_ajax_blocked8.loaded = true;
            $.whizzChat_ajax_blocked8.timerx = 1; 
        
    });

}
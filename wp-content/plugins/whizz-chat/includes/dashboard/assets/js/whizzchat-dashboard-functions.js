
var $ = jQuery;
var whizzchat_live_enable = jQuery("#whizz-chat-live").val();
var whizzchat_comm_type = $("#whizz-chat-live").val();
var cookie_id_js = whizzChat_dashboard_object.whizz_user_token;
var whizzchat_comm_type = $("#whizz-chat-live").val();
var whizzchat_screen = $("#whizzchat-screen").val();
var whizzchat_between = $("#whizz-chat-between").val();
var whizzchat_dashboard = jQuery("#whizzchat-dashboard").val();
if (whizzchat_comm_type == '0' && whizzchat_screen == 'user' && whizzchat_dashboard == 'active') {

    setInterval(whizzChat_load_chat_dashboard, whizzChat_dashboard_object.check_time);
    setInterval(whizzChat_load_chat_box_dashboard, whizzChat_dashboard_object.check_time);

}


$.whizzChat_ajax_get_chat_list_box_dashboard={loaded:false, timerx:0 }
function whizzChat_load_chat_box_dashboard() {

    if($.whizzChat_ajax_get_chat_list_box_dashboard.timerx != 0){
        if( $.whizzChat_ajax_get_chat_list_box_dashboard.loaded == false ) return '';
    }


    var chat_list = [];
    var chat_id;
    var session_id = whizz_user_token_js(whizzChat_dashboard_object.whizz_user_token);
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
        nonce: whizzChat_dashboard_object.nonce,
        list_ids: json_list_ids,
        list_data: li_exists,
    };
    var json_end_point = whizzChat_dashboard_object.whizz_restapi_endpoint + '/get-chat-list-box-dashboard';
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
            xhr.setRequestHeader('X-WP-Nonce', whizzChat_dashboard_object.nonce);
            $.whizzChat_ajax_get_chat_list_box_dashboard.loaded = false;          
        },
    }).done(function (response) {
        $.each(JSON.parse(response['list_ids']), function (i, item) {
            if (typeof response['list_html'] !== 'undefined' && response['list_html'] != '') {

                var active_li = $(".chats-tab-open aside.whizz-sidebar ul.contacts-list li.active").attr('id');

                $(".chats-tab-open aside.whizz-sidebar ul.contacts-list li").remove();
                $(".chats-tab-open aside.whizz-sidebar ul.contacts-list").prepend(JSON.parse(response['list_html']));
                if (active_li) {
                    $(".chats-tab-open aside.whizz-sidebar ul.contacts-list li#" + active_li).addClass('active');
                }
                // alert(active_li);
            }
            return false;

        });


            $.whizzChat_ajax_get_chat_list_box_dashboard.loaded = true;
            $.whizzChat_ajax_get_chat_list_box_dashboard.timerx = 1;  

    });
}


$.whizzChat_ajax_get_chat_list_dashboard={loaded:false, timerx:0}



function whizzChat_load_chat_dashboard() {
    var chat_list = [];
    var message_ids = [];
    if($.whizzChat_ajax_get_chat_list_dashboard.timerx != 0){
        if( $.whizzChat_ajax_get_chat_list_dashboard.loaded == false ) return '';
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

    var session_id = whizz_user_token_js(whizzChat_dashboard_object.whizz_user_token);

    var message_ids = JSON.stringify(message_ids);
    var jsonString = JSON.stringify(chat_list);
    var client_data = {
        action: 'whizzChat_get_chat_list_dashb',
        url: window.location.href,
        session: session_id,
        nonce: whizzChat_dashboard_object.nonce,
        chat_boxs: '',
        message_ids: message_ids,
        boxs: jsonString,
    };

    var json_end_point = whizzChat_dashboard_object.whizz_restapi_endpoint + '/get-chat-list-dashboard';

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
            xhr.setRequestHeader('X-WP-Nonce', whizzChat_dashboard_object.nonce);
            $.whizzChat_ajax_get_chat_list_dashboard.loaded = false;
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
                            var dd_bottom = ".chats-tab-open .whizz-main.main-visible .chat-content";
                            $(dd_bottom).prop({scrollTop: $(dd_bottom).prop("scrollHeight")});


                        }
                    } else {
                        $("div[data-post-id=" + i + "]").removeHTML();
                    }
                });
            }
        }


         $.whizzChat_ajax_get_chat_list_dashboard.loaded = true;
         $.whizzChat_ajax_get_chat_list_dashboard.timerx = 1;
    });


}


function whizzchat_open_dashboard_chat_person(id, post_id, this_obj, comm_id, room) {

    jQuery(this_obj).parent().parent().children('li').removeClass('active');
    jQuery(this_obj).parent().addClass('active');
    var session_id = whizz_user_token_js(whizzChat_dashboard_object.whizz_user_token);

    if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {
        socket.emit('agRoomJoined', room, session_id, comm_id);
    }

    jQuery('.chats-tab-open aside.whizz-sidebar ul.contacts-list').find('#chat-badge-' + id + '').remove();

    var json_end_point = whizzChat_dashboard_object.whizz_restapi_endpoint + '/get-chat-box-dashboard';

    var session_id = whizz_user_token_js(whizzChat_dashboard_object.whizz_user_token);
    var client_data = {
        session: session_id,
        nonce: whizzChat_dashboard_object.nonce,
        chat_id: id,
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
            xhr.setRequestHeader('X-WP-Nonce', whizzChat_dashboard_object.nonce);
        },
    }).done(function (data) {
        jQuery('.chats-tab-open main.whizz-main').replaceWith(data);
        var dd_bottom = ".chat-content";
        jQuery(dd_bottom).prop({scrollTop: jQuery(dd_bottom).prop("scrollHeight")});

        jQuery('.chat-content').magnificPopup({
            delegate: 'a.popup-media',
            type: 'image',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0, 1] // Will preload 0 - before current, and 1 after the current image
            }
        });
        jQuery(".whizz-dash-chat-body .whizzChat-emoji-dashb").emojioneArea({
            pickerPosition: "top",
            filtersPosition: "bottom",
            tones: false,
            spellcheck: true,
            autocomplete: false,
            hidePickerOnBlur: true,
            saveEmojisAs: 'unicode',
            placeholder: whizzChat_dashboard_object.type_something,
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
        
        $('[data-close]').on('click', function (e) {
            e.preventDefault();
            $(".whizz-main").removeClass("main-visible");
        });
        
        $(".chat-content").scroll(function () {

            var session_id = whizz_user_token_js(whizzChat_dashboard_object.whizz_user_token);
            if ($(this).scrollTop() < 1) {
                var this_var = $(this);
                var last_chat_id = $(this).find("div.message-box-holder-dash").data('chat-unique-id');
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
                    action: 'whizzChat_load_old_chat_dashb',
                    url: window.location.href,
                    session: session_id,
                    nonce: whizzChat_dashboard_object.nonce,
                    last_chat_id: last_chat_id,
                    post_id: post_id,
                    chat_id: chat_id
                };
                var json_end_point = whizzChat_dashboard_object.whizz_restapi_endpoint + '/load-old-chat-dashboard';
                $.ajax({
                    type: 'POST',
                    action: 'whizzChat_load_old_chat_dashb',
                    url: json_end_point,
                    data: client_data,
                    dataType: 'json',
                    crossDomain: true,
                    cache: false,
                    async: true,
                    xhrFields: {withCredentials: true},
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-WP-Nonce', whizzChat_dashboard_object.nonce);
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

    });

}


$('body').on('change', '.whizzchat-dash-pannel .ibenic_file_input, .whizzchat-dash-pannel a', function () {
    var upload_type = '';
    var this_obj = $(this);

    var chat_id = $(this).parents().filter(function () {
        return $(this).attr("data-chat-id");
    }).eq(0).attr("data-chat-id");
    $('.initate-dash-btn').html('<i class="fas fa-spinner fa-spin"></i>');

    if ($(this).hasClass("whizzChat-file"))
    {
        var upload_type = 'file';
        var file_max_size = whizzChat_dashboard_object.whizz_file.size;
        var file_extension = whizzChat_dashboard_object.whizz_file.format;
    } else if ($(this).hasClass("whizzChat-image"))
    {
        var upload_type = 'image';
        var file_max_size = whizzChat_dashboard_object.whizz_image.size;
        var file_extension = whizzChat_dashboard_object.whizz_image.format;
    } else
    {
        $.toast({
            heading: 'Warning',
            text: whizzChat_dashboard_object.not_valid_type,
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
        alert(whizzChat_dashboard_object.type_size_not_valid);
    }

    if (upload_file_count == 1 && img_size_exceed) {
        alert(whizzChat_dashboard_object.size_not_valid);
    }

    if (upload_file_count == 1 && file_type_change) {
        alert(whizzChat_dashboard_object.type_not_valid);
    }

    if (upload_file_count > 1 && img_size_exceed && file_type_change) {
        alert(whizzChat_dashboard_object.sm_type_size_not_valid);
    }

    if (upload_file_count > 1 && img_size_exceed) {
        alert(whizzChat_dashboard_object.sm_size_not_valid);
    }

    if (upload_file_count > 1 && file_type_change) {
        alert(whizzChat_dashboard_object.sm_type_not_valid);
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

    var msg = '';
    $(this).closest('div.chat-input-holder').find('.initate-chat-input-text').val('');
    $(this).closest('div.chat-input-holder').find('div.emojionearea-editor').html('');
    var message_ids = $('.whizz-dash-chat-body div.message-box-holder-dash:last').attr('data-chat-unique-id');
    var session_id = whizz_user_token_js(whizzChat_dashboard_object.whizz_user_token);
    form_data.append('chat_id', chat_id);
    form_data.append('post_id', post_id);
    form_data.append('action', 'whizzChat_send_chat_message_dashb');
    form_data.append('session', session_id);
    form_data.append('nonce', whizzChat_dashboard_object.nonce);
    form_data.append('url', window.location.href);
    form_data.append('msg', msg);
    form_data.append('message_ids', message_ids);
    form_data.append('upload_type', upload_type);
    form_data.append('message_type', 'image/file');
    var json_end_point = whizzChat_dashboard_object.whizz_restapi_endpoint + '/send-chat-message-dashboard';

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
            xhr.setRequestHeader('X-WP-Nonce', whizzChat_dashboard_object.nonce);

        },
    }).done(function (data) {

        if (data['success'] == true && data['data']['chat_boxes']) {
            var json_data = JSON.parse(data['data']['chat_boxes']);
            var post_id = (json_data['post_id']);
            var chat_id = json_data['chat_id'];
            if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {
                socket.emit('agSendMessage', room, msg, comm_id, chat_id);
            }

            var html = (json_data['html']);
            var dd = ".chats-tab-open .whizz-main.main-visible .chat-messages-dashb div.message-box-holder-dash:last";
            $(dd).after(html);
            var time_text = $(dd).data('chat-last-seen');
            var dd_bottom = ".chats-tab-open .whizz-main.main-visible .chat-content";
            $(dd_bottom).prop({scrollTop: $(dd_bottom).prop("scrollHeight")});
            $('.initate-dash-btn').html('<i class="fas fa-chevron-right"></i>');
            jQuery('.whizz-dash-chat-body textarea.whizzChat-emoji-dashb').val('');
            jQuery('.whizz-dash-chat-body .emojionearea-editor').html('');


            $(".whizzchat-dash-pannel .ibenic_file_input, .whizzchat-dash-pannel a").val('');

        } else if (data['success'] == false && data['data']['chat_boxes']) {
            var json_data = JSON.parse(data['data']['chat_boxes']);
            var post_id = (json_data['post_id']);
            var chat_id = json_data['chat_id'];
            $('.initate-dash-btn').html('<i class="fas fa-chevron-right"></i>');
            if (typeof data.message !== 'undefined' && data.message != '') {
                alert(data.message);
            }
        }

    });
});

$('body').on('click', '.whizzchat-dash-location', function () {


    var this_obj = $(this);

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
    var message_ids = $('.whizz-dash-chat-body div.message-box-holder-dash:last').attr('data-chat-unique-id');


    if ($(this).hasClass("whizz-dashb-marker"))
    {
        if (!navigator.geolocation) {
            alert(whizzChat_dashboard_object.browser_not_support);
        } else {
            $(this).parents().filter(function ()
            {
                $('.initate-dash-btn').html('<i class="fas fa-spinner fa-spin fa-spin"></i>');
            });
            navigator.geolocation.getCurrentPosition(function (position)
            {
                var session_id = whizz_user_token_js(whizzChat_dashboard_object.whizz_user_token);

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
                var session_id = whizz_user_token_js(whizzChat_dashboard_object.whizz_user_token);
                var client_data = {
                    action: 'whizzChat_send_chat_message_dashb',
                    url: window.location.href,
                    chat_id: chat_id,
                    post_id: post_id,
                    msg: msg,
                    upload_type: 'map',
                    message_type: 'map',
                    map_data: mapString,
                    session: session_id,
                    nonce: whizzChat_dashboard_object.nonce,
                    message_ids: message_ids,
                };
                var json_end_point = whizzChat_dashboard_object.whizz_restapi_endpoint + '/send-chat-message-dashboard';
                $.ajax({
                    type: 'POST',
                    action: 'whizzChat_send_chat_message_dashb',
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
                        xhr.setRequestHeader('X-WP-Nonce', whizzChat_dashboard_object.nonce);
                    },
                }).done(function (data) {

                    if (data['success'] == true && data['data']['chat_boxes']) {
                        var json_data = JSON.parse(data['data']['chat_boxes']);
                        var post_id = (json_data['post_id']);
                        var chat_id = json_data['chat_id'];
                        if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {
                            socket.emit('agSendMessage', room, msg, comm_id, chat_id);
                        }

                        var html = (json_data['html']);
                        var dd = ".chats-tab-open .whizz-main.main-visible .chat-messages-dashb div.message-box-holder-dash:last";
                        $(dd).after(html);
                        var time_text = $(dd).data('chat-last-seen');
                        var dd_bottom = ".chats-tab-open .whizz-main.main-visible .chat-content";
                        $(dd_bottom).prop({scrollTop: $(dd_bottom).prop("scrollHeight")});
                        $('.initate-dash-btn').html('<i class="fas fa-chevron-right"></i>');
                        jQuery('.chats-tab-open textarea.whizzChat-emoji-dashb').val('');
                        jQuery('.chats-tab-open .emojionearea-editor').html('');
                    } else if (data['success'] == false && data['data']['chat_boxes']) {
                        var json_data = JSON.parse(data['data']['chat_boxes']);
                        var post_id = (json_data['post_id']);
                        var chat_id = json_data['chat_id'];
                        $('.initate-dash-btn').html('<i class="fas fa-chevron-right"></i>');
                        if (typeof data.message !== 'undefined' && data.message != '') {
                            alert(data.message);
                        }
                    }
                });


            }, function (error) {
                if (error.code == error.PERMISSION_DENIED)
                {
                    alert(whizzChat_dashboard_object.enable_location);
                } else {
                    alert(error.message);
                }

                $('.whizz-btn-wrap-' + chat_id + '').html('<i class="fas fa-chevron-right initate-chat-input-btn"></i>');
            });
        }

    }

});

$(document).on('click', '.initate-dash-btn', function () {

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
    var msg = $(this).closest('div.chat-footer').find('.initate-chat-input-text').val();
    msg = whizzchat_strip_html(msg);
    if (msg == '') {
        alert(whizzChat_dashboard_object.invalid_type_data2);
        return;
    }


    $(this).closest('div.chat-footer').find('.initate-chat-input-text').val('');
    $(this).closest('div.chat-footer').find('div.emojionearea-editor').html('');
    var this_var = $(this);
    var message_ids = $('.whizz-dash-chat-body div.message-box-holder-dash:last').attr('data-chat-unique-id');
    $('#get-chat-switch-' + chat_id + '').val('on');
    var rmv_div = "div[data-chat-id=" + chat_id + "] .chat-messages .whizzChat-chat-messages-last";
    $(rmv_div).remove();
    var dd_bottom = "div[data-chat-id=" + chat_id + "] .chat-messages";
    $(dd_bottom).prop({scrollTop: $(dd_bottom).prop("scrollHeight")});

    var session_id = whizz_user_token_js(whizzChat_dashboard_object.whizz_user_token);
    var client_data = {
        action: 'whizzChat_send_chat_message_dashb',
        url: window.location.href,
        session: session_id,
        nonce: whizzChat_dashboard_object.nonce,
        post_id: post_id,
        chat_id: chat_id,
        msg: msg,
        message_ids: message_ids,
        message_type: 'text'
    };


    if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {
        socket.emit('agRoomJoined', room, session_id, comm_id);
    }
    $(this_obj).html('<i class="fas fa-spinner fa-spin fa-spin"></i>');

    var json_end_point = whizzChat_dashboard_object.whizz_restapi_endpoint + '/send-chat-message-dashboard';
    var _nonce = whizzChat_dashboard_object.whizz_site_nonce;
    var _nonce_rest = whizzChat_dashboard_object.whizz_restapi_nonce;

    $.ajax({
        type: 'POST',
        action: 'whizzChat_send_chat_message_dashb',
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
            xhr.setRequestHeader('X-WP-Nonce', whizzChat_dashboard_object.nonce);
        },
    }).done(function (data) {

        if (data['success'] == true && data['data']['chat_boxes']) {
            var json_data = JSON.parse(data['data']['chat_boxes']);
            var post_id = (json_data['post_id']);
            var chat_id = json_data['chat_id'];

            if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {
                socket.emit('agSendMessage', room, msg, comm_id, chat_id);
            }

            var html = (json_data['html']);
            var dd = ".chats-tab-open .whizz-main.main-visible .chat-messages-dashb div.message-box-holder-dash:last";
            $(dd).after(html);
            var time_text = $(dd).data('chat-last-seen');
            var dd_bottom = ".chats-tab-open .whizz-main.main-visible .chat-content";
            $(dd_bottom).prop({scrollTop: $(dd_bottom).prop("scrollHeight")});
            $(this_obj).html('<i class="fas fa-chevron-right" aria-hidden="true"></i>');
            jQuery('.whizz-dash-chat-body textarea.whizzChat-emoji-dashb').val('');
            jQuery('.whizz-dash-chat-body .emojionearea-editor').html('');



        } else if (data['success'] == false && data['data']['chat_boxes']) {
            var json_data = JSON.parse(data['data']['chat_boxes']);
            var post_id = (json_data['post_id']);
            var chat_id = json_data['chat_id'];
            $(this_obj).html('<i class="fas fa-chevron-right" aria-hidden="true"></i>');
            if (typeof data.message !== 'undefined' && data.message != '') {
                alert(data.message);
            }
        }

    });
});

$("body").delegate(".whizz-back", "click", function () {
    $(".main").removeClass("main-visible");
});



$("body").delegate(".chat-input-holder-dashb", "click", function () {

    var chat_id = jQuery(this).parents().filter(function () {
        return jQuery(this).data("chat-id");
    }).eq(0).data("chat-id");

    var post_id = jQuery(this).parents().filter(function () { // .chats-tab-open aside.whizz-sidebar ul.contacts-list li
        return jQuery(this).data("post-id");
    }).eq(0).data("post-id");
    var message_id = jQuery('div.message-box-holder-dash:last').data('chat-unique-id');
    var is_seen = jQuery('div.message-box-holder-dash:last').data('chat-last-seen');
    if (is_seen == "")
    {
        jQuery('#' + chat_id + ' div.message-box-holder-dash:last').data('chat-last-seen', '-');
    }

    jQuery('.chats-tab-open aside.whizz-sidebar ul.contacts-list').find('#chat-badge-' + chat_id + '').remove();

    jQuery('.chats-tab-open aside.whizz-sidebar ul.contacts-list').find('li#' + chat_id + '').removeClass('chatlist-message-alert');

    if (jQuery(".chatbox-inner-list li.chatlist-message-alert").length)
    {
        jQuery("div.chatbox-inner-list div.chatbox-list div.chatbox-top").addClass('chatbox-unread-message');

    } else
    {
        jQuery("div.chatbox-inner-list div.chatbox-list div.chatbox-top").removeClass('chatbox-unread-message');
    }

    var client_data = {
        session: whizzChat_dashboard_object.whizz_user_token,
        nonce: whizzChat_dashboard_object.nonce,
        chat_id: chat_id,
        post_id: post_id,
        message_id: message_id
    };
    var json_end_point = whizzChat_dashboard_object.whizz_restapi_endpoint + '/read-chat-dashboard';

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
            xhr.setRequestHeader('X-WP-Nonce', whizzChat_dashboard_object.nonce);
        },
    }).done(function (data) {


    });
});


$(document).on('click', '.logout-chat-session-dashb', function () {

    var del_chat = confirm("Are you Sure you want to remove the chat");

    if (del_chat == true) {
        var leave_chat_id = $(this).data('leave-chat-id');
        var whizzChat_name = '';
        var whizzChat_email = '';
        var client_data = {
            action: 'whizzChat_end_chat',
            cid: leave_chat_id,
            whizzChat_name: whizzChat_name,
            whizzChat_email: whizzChat_email,
            url: window.location.href,
            session: whizzChat_dashboard_object.whizz_user_token,
            nonce: whizzChat_dashboard_object.nonce
        };
        var json_end_point = whizzChat_dashboard_object.whizz_restapi_endpoint + '/end-session';
        var _nonce = whizzChat_dashboard_object.whizz_site_nonce;
        var _nonce_rest = whizzChat_dashboard_object.whizz_restapi_nonce;
        $.ajax({
            type: 'POST',
            action: 'whizzChat_end_chat',
            url: json_end_point,
            data: client_data,
            dataType: 'json',
            async: true,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', whizzChat_dashboard_object.nonce);
            },
        }).done(function (data) {
            if (typeof data.success !== 'undefined' && data.success == true) {
                $(".chats-tab-open aside.whizz-sidebar .contacts-list li[id=" + leave_chat_id + "]").remove();
                $(".chats-tab-open .whizz-main.main-visible .whizz-dash-chat-body").remove();
                var html = '<div class="d-flex flex-column justify-content-center text-center h-100 w-100">\n\
                            <div class="container">\n\
                              <div class="avatar avatar-lg mb-2">\n\
                                 <img class="avatar-img" src="https://asif/whizzchat/wp-content/plugins/whizz-chat/assets/images/whizz-chat-logo.png">\n\
                              </div>\n\
                           <h5>Welcome to WhizzChat Messenger</h5>\n\
                           <p class="text-muted">Please select a chat to Start messaging.</p>\n\
                          </div>\n\
                         </div>';

                $(".chats-tab-open .whizz-main.main-visible").html(html);
            }
        });
    }

});

$(document).on('click', '.whizzChat-block-user-dashb', function () {


    var this_obj = $(this);
    var chat_id = this_obj.attr("data-chat-id");
    var post_id = this_obj.attr("data-post-id");

    var client_data = {
        chat_id: chat_id,
        post_id: post_id,
        type: 'dashboard',
        session: whizzChat_dashboard_object.whizz_user_token,
        nonce: whizzChat_dashboard_object.nonce
    };

    var json_end_point = whizzChat_dashboard_object.whizz_restapi_endpoint + '/block-user';

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
            xhr.setRequestHeader('X-WP-Nonce', whizzChat_dashboard_object.nonce);
        },
    }).done(function (data) {
        if (data['success'] == true && data['data']['unique_id'] != "") {
            var unique_id = data['data']['unique_id'];
            var dd = ".chats-tab-open .whizz-main.main-visible .chat-messages-dashb div.message-box-holder-dash:last";
            $(dd).after();
            var html_val = this_obj.html();
            var data_val = this_obj.data('replace-text');
            
            this_obj.html(data_val);
            this_obj.data('replace-text', html_val);
            jQuery('.chat-messages-dashb').find('p.blocked-chat-p').remove();
            jQuery('.chat-messages-dashb').append(data['html']);

        }
    });
});
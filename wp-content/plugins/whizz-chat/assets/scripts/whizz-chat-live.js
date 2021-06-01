/*
 * 
 * Socket io communication functions
 * 
 */

var current_session_id = whizzChat_livecore.whizz_user_token;
var whizzchat_admin = jQuery("#whizz-chat-between").val();
whizzchat_admin = typeof whizzchat_admin != 'undefined' && whizzchat_admin == '1' ? true : false;
var is_admin = jQuery("#whizz-chat-admin").val();
is_admin = typeof is_admin != 'undefined' && is_admin == '1' ? true : false;
var current_id = jQuery("#whizzchat-current-userid").val();
var whizzchat_dashboard = jQuery("#whizzchat-dashboard").val();

var socket = io(whizzChat_livecore.whizzcaht_socket_url, {
    'reconnection': true,
    'reconnectionDelay': 50000,
    'reconnectionDelayMax': 50000,
    'reconnectionAttempts': 3,
    'query': {apiKey: whizzChat_livecore.whizzcaht_socket_key},
    transports: ['websocket']
});

// On Connection
socket.on('connect', function () {
    console.log('connected to server - client side notification');
    whizz_chat_online_user(current_session_id);
});

// On Disconnection
socket.on('disconnect', function () {
    console.log('disconnected to server - client side notification');
    whizz_chat_offline_user(current_session_id);
});


// reconnect in 
socket.on('reconnect_attempt', () => {
    socket.io.opts.transports = ['polling', 'websocket'];
    socket.io.opts.query = {
        apiKey: whizzChat_livecore.whizzcaht_socket_key
    }
});
socket.on('agTyping', function (msg, chat_id) {
    if (whizzchat_dashboard == 'active') {
        jQuery('.whizztyping-' + chat_id + '').html(' <div class="whizz-chat-wave"><span class="dot"></span><span class="dot"></span><span class="dot"></span></div> ');
    } else {
        jQuery('span.typing-box-' + chat_id + '').html(' <div class="whizz-chat-wave"><span class="dot"></span><span class="dot"></span><span class="dot"></span></div>');
    }

});
socket.on('agStopTyping', function (chat_id) {
    if (whizzchat_dashboard == 'active') {
        jQuery('.whizztyping-' + chat_id + '')
    } else {
        jQuery('span.typing-box-' + chat_id + '').html('');
    }
});

socket.on('agMessageSeen', function (msg, chat_id) {

});


// Got new message	
socket.on('agGotNewMessage', function (msg, user, chat_id) {


    if (whizzchat_dashboard == 'active') {

        whizzChat_load_chat_box_admin_live();
        whizz_chat_live_read_message_admin();

        if (jQuery('.whizz-chat-body .messages-box .list-group a span.badge.badge-light').hasClass('active')) {
            jQuery('.messages-box .list-group a').removeClass('active');
        } else {
            var chat_counter_elem = '.whizz-chat-body .messages-box .list-group a span.chat-counter-' + chat_id + '';
            if (typeof jQuery(chat_counter_elem).html() !== 'undefined' && jQuery(chat_counter_elem).html() !== '') {
                var chat_conter = jQuery(chat_counter_elem).html();
                chat_conter = parseInt(chat_conter) + 1;
                jQuery(chat_counter_elem).html(chat_conter);
            } else {
                jQuery(chat_counter_elem).html(parseInt(1));
            }
        }
    } else {

        whizzChat_load_chat_box_live();
        whizz_chat_live_read_message(chat_id);

    }
});

// listener of Info Messages		
socket.on('agInfoMessage', function (data) {


});

// when user online
socket.on('agUserOnline', function (agUserID) {
    // do what you want to
});

// when user disconnected
socket.on('agUserDisconnected', function (agUserID) {
    // do what you want to
});

// Ask to Join
socket.on('agAskedToJoin', function (room_name, user) {

    socket.emit('agRoomJoined', room_name, user, '');
});
/*
 * Message Seen functionality
 */

function whizz_chat_online_user(user_id) {

    var client_data = {
        nonce: whizzChat_livecore.nonce,
        user_id: user_id,
    };
    var json_end_point = whizzChat_livecore.whizz_restapi_endpoint + '/user-online';
    jQuery.ajax({
        type: 'POST',
        action: 'whizzChat_user_online',
        url: json_end_point,
        data: client_data,
        dataType: 'json',
        crossDomain: true,
        cache: false,
        async: false,
        xhrFields: {withCredentials: true},
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', whizzChat_livecore.nonce);
        },
    }).done(function (data) {

    });

}

function whizz_chat_offline_user(user_id) {
    var client_data = {
        nonce: whizzChat_livecore.nonce,
        user_id: user_id,
    };
    var json_end_point = whizzChat_livecore.whizz_restapi_endpoint + '/user-offline';
    jQuery.ajax({
        type: 'POST',
        action: 'whizzChat_user_offline',
        url: json_end_point,
        data: client_data,
        dataType: 'json',
        crossDomain: true,
        cache: false,
        async: false,
        xhrFields: {withCredentials: true},
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', whizzChat_livecore.nonce);
        },
    }).done(function (data) {

    });
}
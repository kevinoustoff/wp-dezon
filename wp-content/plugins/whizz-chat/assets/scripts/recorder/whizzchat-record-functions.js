(function () {
    var params = {},
            r = /([^&=]+)=?([^&]*)/g;

    function d(s) {
        return decodeURIComponent(s.replace(/\+/g, ' '));
    }

    var match, search = window.location.search;
    while (match = r.exec(search.substring(1))) {
        params[d(match[1])] = d(match[2]);

        if (d(match[2]) === 'true' || d(match[2]) === 'false') {
            params[d(match[1])] = d(match[2]) === 'true' ? true : false;
        }
    }

    window.params = params;
})();
jQuery(document).ready(function () {

    var $ = jQuery;
    var whizzchat_live_enable = jQuery("#whizz-chat-live").val();
    var whizzchat_dashboard = jQuery("#whizzchat-dashboard").val();
    //var wizzinterval = setInterval(whizz_start_interval, 1000);
    // var timer2 = jQuery('#whizz_sound_time').val();


//    function whizz_start_interval() {
//        var timer = timer2;
//        var seconds = parseInt(timer, 10);
//        --seconds;
//        seconds = (seconds < 0 || seconds == 0) ? whizzchat_stop_interval(wizzinterval) : seconds;
//        timer2 = seconds;
//        jQuery('.whizz-chat-countdown').html(seconds);
//    }

    function whizzchat_stop_interval(wizzinterval) {

        // clearInterval(wizzinterval);
        //jQuery(".attachment-panel a.whizzchat-record-sound").trigger("click", true);
        //jQuery('.whizz-chat-countdown').remove();

    }

    jQuery('body').on('click', '.attachment-panel a.whizzchat-record-sound', function () {

    });


    jQuery('body').on('click', 'a.whizzchat-record-sound', function (e, time_click) {


      


        var counter = 5;

        var whizz_time = false;
        if (typeof time_click !== 'undefined' && time_click == true) {   // when timer complete
            whizz_time = true;
        }

        var call_trigger = true;

        if (jQuery(this).html() == '<i class="fa fa-microphone-slash" aria-hidden="true"></i>') { // stop manually when recorder voice
            whizz_time = true;
            clearInterval(counter);
            jQuery('.whizz-chat-countdown').css('display', 'none');
            call_trigger = false;
        }

        if (!whizz_time) {
//            jQuery('.whizz-chat-countdown').css('display', 'inline');
//            setInterval(function () {
//                counter--;
//                if (counter >= 0) {
//                    jQuery('.whizz-chat-countdown').html(counter);
//                }
//                if (counter === 0) {
//                    if(call_trigger){
//                       whizzchat_stop_interval(); 
//                    }
//                    clearInterval(counter);
//                    jQuery('.whizz-chat-countdown').css('display', 'none');
//                }
//            }, 1000);
        }

        if (jQuery(this).hasClass('whizz-stop')) {
            jQuery(this).removeClass('whizz-stop');
        } else {
           // whizz_start_interval();
            jQuery(this).addClass('whizz-stop');
        }

        
        var whizz_time = false;
        if (typeof time_click !== 'undefined' && time_click == true) {
            whizz_time = true;
        }

        if (jQuery(this).html() == '<i class="fa fa-microphone" aria-hidden="true"></i>') { // start
            //whizz_start_interval();
        }




        var whizzchat_mike = this;
        if (whizzchat_mike.innerHTML === '<i class="fa fa-microphone-slash" aria-hidden="true"></i>') { // stop recording
           // whizzchat_mike.disabled = true;
            whizzchat_mike.disableStateWaiting = true;
            setTimeout(function () {
               // whizzchat_mike.disabled = false;
                whizzchat_mike.disableStateWaiting = false;
            }, 2 * 1000);


            //jQuery('.attachment-panel .whizzchat-send-voice').css('display', 'inline');
            //jQuery('.attachment-panel .whizzchat-remove-voice').css('display', 'inline');


            whizzchat_mike.innerHTML = '<i class="fa fa-microphone" aria-hidden="true"></i>';
            function stopStream() {
                if (whizzchat_mike.stream && whizzchat_mike.stream.stop) {
                    whizzchat_mike.stream.stop();
                    whizzchat_mike.stream = null;
                }
            }

            if (whizzchat_mike.recordRTC) {
                if (whizzchat_mike.recordRTC.length) {
                    whizzchat_mike.recordRTC[0].stopRecording(function (url) {
                        if (!whizzchat_mike.recordRTC[1]) {
                            stopStream();
                            saveToDiskOrOpenNewTab(whizzchat_mike.recordRTC[0]);
                            return;
                        }
                        whizzchat_mike.recordRTC[1].stopRecording(function (url) {
                            stopStream();
                        });
                    });
                } else {
                    whizzchat_mike.recordRTC.stopRecording(function (url) {
                        stopStream();
                        saveToDiskOrOpenNewTab(whizzchat_mike.recordRTC);
                    });
                }
            }

            return;
        }

        //whizzchat_mike.disabled = true;
        var commonConfig = {
            onMediaCaptured: function (stream) {
                whizzchat_mike.stream = stream;
                if (whizzchat_mike.mediaCapturedCallback) {
                    whizzchat_mike.mediaCapturedCallback();
                }
                whizzchat_mike.innerHTML = '<i class="fa fa-microphone-slash" aria-hidden="true"></i>';
                //whizzchat_mike.disabled = false;
            },
            onMediaStopped: function () {
                whizzchat_mike.innerHTML = '<i class="fa fa-microphone" aria-hidden="true"></i>';
                if (!whizzchat_mike.disableStateWaiting) {
                    //whizzchat_mike.disabled = false;
                }
            },
            onMediaCapturingFailed: function (error) {
                if (error.name === 'PermissionDeniedError' && !!navigator.mozGetUserMedia) {
                    InstallTrigger.install({
                        'Foo': {
                            // https://addons.mozilla.org/firefox/downloads/latest/655146/addon-655146-latest.xpi?src=dp-btn-primary
                            URL: 'https://addons.mozilla.org/en-US/firefox/addon/enable-screen-capturing/',
                            toString: function () {
                                return this.URL;
                            }
                        }
                    });
                }
                commonConfig.onMediaStopped();
            }
        };
        captureAudio(commonConfig);
        whizzchat_mike.mediaCapturedCallback = function () {
            whizzchat_mike.recordRTC = RecordRTC(whizzchat_mike.stream, {
                type: 'audio',
                bufferSize: typeof params.bufferSize == 'undefined' ? 0 : parseInt(params.bufferSize),
                sampleRate: typeof params.sampleRate == 'undefined' ? 44100 : parseInt(params.sampleRate),
                leftChannel: params.leftChannel || false,
                disableLogs: params.disableLogs || false,
                recorderType: DetectRTC.browser.name === 'Edge' ? StereoAudioRecorder : null
            });
            whizzchat_mike.recordRTC.startRecording();
        };
    });


    jQuery('body').on('click', '.attachment-panel a.whizzchat-remove-voice', function (e) {

       // jQuery('.attachment-panel a.whizzchat-remove-voice').css('display:none');
       // jQuery(this).css('display:none');


    });


    function captureAudio(config) {
        captureUserMedia({audio: true}, function (audioStream) {
            //recordingPlayer.srcObject = audioStream;

            config.onMediaCaptured(audioStream);
            audioStream.onended = function () {
                config.onMediaStopped();
            };
        }, function (error) {
            config.onMediaCapturingFailed(error);
        });
    }

    function captureUserMedia(mediaConstraints, successCallback, errorCallback) {
        navigator.mediaDevices.getUserMedia(mediaConstraints).then(successCallback).catch(errorCallback);
    }


    function setMediaContainerFormat(arrayOfOptionsSupported) {

        var selectedItem = arrayOfOptionsSupported;

    }

    //recordingMedia.onchange = function () {
    //if (this.value === 'record-audio') {
    setMediaContainerFormat(['WAV', 'Ogg']);
    // return;
    //}
    //setMediaContainerFormat(['WebM', /*'Mp4',*/ 'Gif']);
    // };



    function saveToDiskOrOpenNewTab(recordRTC) {

        //jQuery('a.whizzchat-send-voice').disabled = false;
        jQuery('body').on('click', 'a.whizzchat-send-voice', function () {
            
        
            
            var $ = jQuery;
            if (!recordRTC)
                return alert('No recording found.');
            //this.disabled = true;
            var whizzchat_mike = this;

            var chat_id = $(this).parents().filter(function () {
                return $(this).attr("data-chat-id");
            }).eq(0).attr("data-chat-id");

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
            
            if (whizzchat_dashboard == 'active') {
                 var message_ids = $('.chat-body div.message-box-holder-dash:last').attr('data-chat-unique-id');
             }else{
                 var message_ids = $('#' + chat_id + ' div.message-box-holder:last').attr('data-chat-unique-id');
             }
            
            
            
            var session_id = whizz_user_token_js(whizzChat_media_obj.whizz_user_token);

            var formData = new FormData();
            upload_type = 'file';
            formData.append('chat_id', chat_id);
            formData.append('post_id', post_id);
            if (whizzchat_dashboard == 'active') {
                 formData.append('action', 'whizzChat_send_chat_message_dashb');
             }else{
                 formData.append('action', 'whizzChat_send_chat_message');
             }
            
            formData.append('session', session_id);
            formData.append('nonce', whizzChat_media_obj.nonce);
            formData.append('url', window.location.href);
            formData.append('msg', msg);
            formData.append('message_ids', message_ids);
            formData.append('upload_type', upload_type);
            formData.append('message_type', 'voice');
            var blob = recordRTC instanceof Blob ? recordRTC : recordRTC.blob;
            var fileType = blob.type.split('/')[0] || 'audio';
            var fileName = (Math.random() * 1000).toString().replace('.', '');
            if (fileType === 'audio') {
                fileName += '.' + (!!navigator.mozGetUserMedia ? 'ogg' : 'wav');
            } else {
                fileName += '.webm';
            }
            formData.append('sound_name', fileName);
            formData.append('whizzchat_attachment', blob);
            
             if (whizzchat_dashboard == 'active') {
                 var json_end_point = whizzChat_media_obj.whizz_restapi_endpoint + '/send-chat-message-dashboard';
             }else{
                 var json_end_point = whizzChat_media_obj.whizz_restapi_endpoint + '/send-chat-message';
             }
            
            
            
            if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {
                socket.emit('agRoomJoined', room, session_id, comm_id);
            }
            $.ajax({
                url: json_end_point,
                type: 'POST',
                contentType: false,
                processData: false,
                data: formData,
                dataType: 'json',
                crossDomain: true,
                cache: false,
                async: true,
                xhrFields: {
                    withCredentials: true
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', whizzChat_media_obj.nonce);
                },
            }).done(function (data) {

                if (whizzchat_dashboard == 'active') {

                    if (data['success'] == true && data['data']['chat_boxes']) {
                        var json_data = JSON.parse(data['data']['chat_boxes']);
                        var post_id = (json_data['post_id']);
                        var chat_id = json_data['chat_id'];
                        if (typeof whizzchat_live_enable !== 'undefined' && whizzchat_live_enable == '1') {
                            socket.emit('agSendMessage', room, msg, comm_id, chat_id);
                        }

                        var html = (json_data['html']);
                        var dd = ".chats-tab-open .main.main-visible .chat-messages-dashb div.message-box-holder-dash:last";
                        $(dd).after(html);
                        var time_text = $(dd).data('chat-last-seen');
                        //var time_div = "div[data-chat-id=" + chat_id + "] .chat-messages-dashb whizzChat-chat-messages-last";
                        //$(time_div).html(time_text);
                        var dd_bottom = ".chats-tab-open .main.main-visible .chat-messages-dashb";
                        $(dd_bottom).prop({scrollTop: $(dd_bottom).prop("scrollHeight")});
                        $('.initate-dash-btn').html('<i class="fas fa-chevron-right"></i>');
                        jQuery('.whizz-chat-body textarea.whizzChat-emoji').val('');
                        jQuery('.whizz-chat-body .emojionearea-editor').html('');
                    } else if (data['success'] == false && data['data']['chat_boxes']) {
                        var json_data = JSON.parse(data['data']['chat_boxes']);
                        var post_id = (json_data['post_id']);
                        var chat_id = json_data['chat_id'];
                        $('.initate-dash-btn').html('<i class="fas fa-chevron-right"></i>');
                        if (typeof data.message !== 'undefined' && data.message != '') {
                            alert(data.message);
                        }
                    }



                } else {
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
                    } else if (data['success'] == false && data['data']['chat_boxes']) {
                        var json_data = JSON.parse(data['data']['chat_boxes']);
                        var post_id = (json_data['post_id']);
                        var chat_id = json_data['chat_id'];
                        $('.whizz-btn-wrap-' + chat_id + '').html('<i class="fas fa-chevron-right initate-chat-input-btn"></i>');
                        if (typeof data.message !== 'undefined' && data.message != '') {
                            alert(data.message);
                        }
                    }
                }





            });
        });
    }

    var listOfFilesUploaded = [];
    function uploadToServer(recordRTC, callback) {



        //



        //



        var $ = jQuery;

        var chat_id = $(this).parents().filter(function () {
            return $(this).attr("data-chat-id");
        }).eq(0).attr("data-chat-id");


        $('.whizz-btn-wrap-' + chat_id + '').html('<i class="fas fa-spinner fa-spin initate-chat-input-btn"></i>');













    }

    function makeXMLHttpRequest(url, data, callback) {
        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                callback('upload-ended');
            }
        };
        request.upload.onloadstart = function () {
            callback('Upload started...');
        };
        request.upload.onprogress = function (event) {
            callback('Upload Progress ' + Math.round(event.loaded / event.total * 100) + "%");
        };
        request.upload.onload = function () {
            callback('progress-about-to-end');
        };
        request.upload.onload = function () {
            callback('progress-ended');
        };
        request.upload.onerror = function (error) {
            callback('Failed to upload to server');
            console.error('XMLHttpRequest failed', error);
        };
        request.upload.onabort = function (error) {
            callback('Upload aborted.');
            console.error('XMLHttpRequest aborted', error);
        };
        request.open('POST', url);
        request.send(data);
    }

});
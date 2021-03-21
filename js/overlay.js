app.overlay = (function(){
    var root = {};
    var queue = [];

    var _vue = function(){
        app.ui_vue.titulo = new Vue({
            el: '#titulo',
            store: app.store,
            data: {
                titulo: app.lang.welcome_to + app.site
            },
            mounted: function () {
                this.$nextTick(function () {
                    $("#titulo").addClass('magictime').removeClass('hide');
                });
            }
        });

        app.logs("overlay init vue!");
    };

    var _jquery = function(){
        app.overlays = window.overlays;

        _pub_connect();

        var tmp_chat_res = _chat_connect();
        root.open = tmp_chat_res.open;
        root.close = tmp_chat_res.close;
        root.send = tmp_chat_res.send;

        app.logs("overlay init jquery!");
    };

    var _init = function(){
        _vue();
        _jquery();

        if(typeof sessionStorage.tmp_overlay_exec != "undefined"){
            var tmp_overlay_exec = JSON.parse(sessionStorage.tmp_overlay_exec);
            _execOverlay(tmp_overlay_exec);
        }

        if(app.debug) app.alert("overlay load!");
    };

    /*
    * function for executing overlay reward
    */
    var overlay_interval;
    var _execOverlay = function(alert){
        $('.content_img img').prop("src", "");
        $('.content_img .spin, .content_img .static').addClass('hide');
        $('.content_img').removeClass('top').removeClass('center').removeClass('bottom').removeClass('right').removeClass('left');
        $('.content_img img, .content_img .alert_body').removeClass('equis').removeClass('star').removeClass('crazy_star').removeClass('heart').removeClass('diamond').removeClass('trapezoid').removeClass('pentagon').removeClass('rectanglev').removeClass('rectangleh').removeClass('triangle').removeClass('circle_ov').removeClass('round').removeClass('overlap').removeClass('transparent');
        $('.content_img').removeClass('magictime').removeClass('puffIn').removeClass('puffOut').removeClass('vanishIn').removeClass('vanishOut').removeClass('foolishIn').removeClass('holeOut').removeClass('swashIn').removeClass('swashOut').removeClass('swap').removeClass('twisterInDown').removeClass('twisterInUp').removeClass('magic').removeClass('openDownLeft').removeClass('openDownRight').removeClass('openUpLeft').removeClass('openUpRight').removeClass('openDownLeftReturn').removeClass('openDownRightReturn').removeClass('openUpRightReturn').removeClass('openUpRight').removeClass('bombRightOut').removeClass('bombLeftOut').removeClass('tinRightOut').removeClass('tinLeftOut').removeClass('tinUpOut').removeClass('tinDownOut').removeClass('tinRightIn').removeClass('tinLeftIn').removeClass('tinUpIn').removeClass('tinDownIn').removeClass('boingInUp').removeClass('boingOutDown').removeClass('spaceOutUp').removeClass('spaceOutRight').removeClass('spaceOutDown').removeClass('spaceOutLeft').removeClass('spaceInUp').removeClass('spaceInRight').removeClass('spaceInDown').removeClass('spaceInLeft').removeClass('rotateDown').removeClass('rotateUp').removeClass('rotateLeft').removeClass('rotateRight');
        $('.content_img').hide();
        if(typeof overlay_interval != "undefined") clearInterval(overlay_interval);

        queue.splice(0, 1);

        var tmp_conf = typeof alert.config == "string" ? JSON.parse(alert.config) : alert.config;

        var alert_start = function(){
            if(tmp_conf.obs_change == "1"){
                if(app.obs.isConnected){
                    app.obs.setCurrent();
                    app.obs.scene(tmp_conf.obs_scene);
                }
            }

            if (tmp_conf.alert_fadein == "fadein") {
                $('.content_img').fadeIn('slow', alert_end);
            }
            if (tmp_conf.alert_fadein == "slidedown") {
                $('.content_img').slideDown('slow', alert_end);
            }
            if (tmp_conf.alert_fadein == "puffIn") {
                $('.content_img').addClass('magictime puffIn');
                $('.content_img').fadeIn(0, alert_end);
            }
            if (tmp_conf.alert_fadein == "vanishIn") {
                $('.content_img').addClass('magictime vanishIn');
                $('.content_img').fadeIn(0, alert_end);
            }
            if (tmp_conf.alert_fadein == "foolishIn") {
                $('.content_img').addClass('magictime foolishIn');
                $('.content_img').fadeIn(0, alert_end);
            }
            if (tmp_conf.alert_fadein == "swashIn") {
                $('.content_img').addClass('magictime swashIn');
                $('.content_img').fadeIn(0, alert_end);
            }
            if (tmp_conf.alert_fadein == "swap") {
                $('.content_img').addClass('magictime swap');
                $('.content_img').fadeIn(0, alert_end);
            }
            if (tmp_conf.alert_fadein == "twisterInDown") {
                $('.content_img').addClass('magictime twisterInDown');
                $('.content_img').fadeIn(0, alert_end);
            }
            if (tmp_conf.alert_fadein == "twisterInUp") {
                $('.content_img').addClass('magictime twisterInUp');
                $('.content_img').fadeIn(0, alert_end);
            }
            if (tmp_conf.alert_fadein == "openDownLeftReturn") {
                $('.content_img').addClass('magictime openDownLeftReturn');
                $('.content_img').fadeIn(0, alert_end);
            }
            if (tmp_conf.alert_fadein == "openDownRightReturn") {
                $('.content_img').addClass('magictime openDownRightReturn');
                $('.content_img').fadeIn(0, alert_end);
            }
            if (tmp_conf.alert_fadein == "openUpLeftReturn") {
                $('.content_img').addClass('magictime openUpLeftReturn');
                $('.content_img').fadeIn(0, alert_end);
            }
            if (tmp_conf.alert_fadein == "openUpRightReturn") {
                $('.content_img').addClass('magictime openUpRightReturn');
                $('.content_img').fadeIn(0, alert_end);
            }
            if (tmp_conf.alert_fadein == "tinRightIn") {
                $('.content_img').addClass('magictime tinRightIn');
                $('.content_img').fadeIn(0, alert_end);
            }
            if (tmp_conf.alert_fadein == "tinLeftIn") {
                $('.content_img').addClass('magictime tinLeftIn');
                $('.content_img').fadeIn(0, alert_end);
            }
            if (tmp_conf.alert_fadein == "tinUpIn") {
                $('.content_img').addClass('magictime tinUpIn');
                $('.content_img').fadeIn(0, alert_end);
            }
            if (tmp_conf.alert_fadein == "tinDownIn") {
                $('.content_img').addClass('magictime tinDownIn');
                $('.content_img').fadeIn(0, alert_end);
            }
            if (tmp_conf.alert_fadein == "boingInUp") {
                $('.content_img').addClass('magictime boingInUp');
                $('.content_img').fadeIn(0, alert_end);
            }
            if (tmp_conf.alert_fadein == "spaceInUp") {
                $('.content_img').addClass('magictime spaceInUp');
                $('.content_img').fadeIn(0, alert_end);
            }
            if (tmp_conf.alert_fadein == "spaceInRight") {
                $('.content_img').addClass('magictime spaceInRight');
                $('.content_img').fadeIn(0, alert_end);
            }
            if (tmp_conf.alert_fadein == "spaceInDown") {
                $('.content_img').addClass('magictime spaceInDown');
                $('.content_img').fadeIn(0, alert_end);
            }
            if (tmp_conf.alert_fadein == "spaceInLeft") {
                $('.content_img').addClass('magictime spaceInLeft');
                $('.content_img').fadeIn(0, alert_end);
            }

            if (tmp_conf.confetti == "1") confetti.start(tmp_conf.confetti_time, tmp_conf.confetti_min, tmp_conf.confetti_max);

            if(tmp_conf.speech == "1") _readOutLoud(tmp_body, tmp_conf);

            if(tmp_conf.tochat == "1") {
                root.open(function(){
                    root.send(tmp_conf.body);
                    root.close();
                });
            }

            $('.overlay_custom').css('z-index', '10');
        };

        var alert_reset = function(end_conf) {
            if (end_conf.type_img == "static") {
                $('.content_img .static').addClass('hide');
            }
            if (end_conf.type_img == "spin") {
                $('.content_img .spin').addClass('hide');
            }
            if (end_conf.type_img == "spin_wheel") {
                $('.content_img .spin_wheel').addClass('hide');
            }
            if (end_conf.type_img == "magic_type") {
                $('.content_img .magic_type').addClass('hide');
            }
            if ($('#audioAlert').length > 0) {
                $('#audioAlert')[0].pause();
                $('#audioAlert').remove();
            }
            if ($('#videoAlert').length > 0) {
                $('#videoAlert')[0].pause();
                $('#videoAlert').remove();
            }
            $('.overlay_custom').css('z-index', '-1');
            $('.alert_body, .alert_video').html('');
            $('#alert_css').remove();
            if (tmp_conf.confetti == "1") confetti.stop();
            if(queue.length > 0) {
                setTimeout(function(){
                    _execOverlay(queue[0]);
                }, 1000);
            }
        };

        var alert_end = function(){
            var end_conf = tmp_conf;
            overlay_interval = setInterval(function() {
                if (end_conf.alert_fadeout == "fadeout") {
                    $('.content_img').fadeOut('fast', function() {
                        alert_reset(end_conf);
                    });
                }
                if (end_conf.alert_fadeout == "slideup") {
                    $('.content_img').slideUp('fast', function() {
                        alert_reset(end_conf);
                    });
                }
                if (end_conf.alert_fadeout == "puffOut") {
                    $('.content_img').addClass('magictime puffOut');
                    setTimeout(function() {
                        alert_reset(end_conf);
                    }, 1100);
                }
                if (end_conf.alert_fadeout == "vanishOut") {
                    $('.content_img').addClass('magictime vanishOut');
                    setTimeout(function() {
                        alert_reset(end_conf);
                    }, 1100);
                }
                if (end_conf.alert_fadeout == "holeOut") {
                    $('.content_img').addClass('magictime holeOut');
                    setTimeout(function() {
                        alert_reset(end_conf);
                    }, 1100);
                }
                if (end_conf.alert_fadeout == "swashOut") {
                    $('.content_img').addClass('magictime swashOut');
                    setTimeout(function() {
                        alert_reset(end_conf);
                    }, 1100);
                }
                if (end_conf.alert_fadeout == "magic") {
                    $('.content_img').addClass('magictime magic');
                    setTimeout(function() {
                        alert_reset(end_conf);
                    }, 1100);
                }
                if (end_conf.alert_fadeout == "openDownLeft") {
                    $('.content_img').addClass('magictime openDownLeft');
                    setTimeout(function() {
                        alert_reset(end_conf);
                    }, 1100);
                }
                if (end_conf.alert_fadeout == "openDownRight") {
                    $('.content_img').addClass('magictime openDownRight');
                    setTimeout(function() {
                        alert_reset(end_conf);
                    }, 1100);
                }
                if (end_conf.alert_fadeout == "openUpLeft") {
                    $('.content_img').addClass('magictime openUpLeft');
                    setTimeout(function() {
                        alert_reset(end_conf);
                    }, 1100);
                }
                if (end_conf.alert_fadeout == "openUpRight") {
                    $('.content_img').addClass('magictime openUpRight');
                    setTimeout(function() {
                        alert_reset(end_conf);
                    }, 1100);
                }
                if (end_conf.alert_fadeout == "bombRightOut") {
                    $('.content_img').addClass('magictime bombRightOut');
                    setTimeout(function() {
                        alert_reset(end_conf);
                    }, 1100);
                }
                if (end_conf.alert_fadeout == "bombLeftOut") {
                    $('.content_img').addClass('magictime bombLeftOut');
                    setTimeout(function() {
                        alert_reset(end_conf);
                    }, 1100);
                }
                if (end_conf.alert_fadeout == "tinRightOut") {
                    $('.content_img').addClass('magictime tinRightOut');
                    setTimeout(function() {
                        alert_reset(end_conf);
                    }, 1100);
                }
                if (end_conf.alert_fadeout == "tinLeftOut") {
                    $('.content_img').addClass('magictime tinLeftOut');
                    setTimeout(function() {
                        alert_reset(end_conf);
                    }, 1100);
                }
                if (end_conf.alert_fadeout == "tinUpOut") {
                    $('.content_img').addClass('magictime tinUpOut');
                    setTimeout(function() {
                        alert_reset(end_conf);
                    }, 1100);
                }
                if (end_conf.alert_fadeout == "tinDownOut") {
                    $('.content_img').addClass('magictime tinDownOut');
                    setTimeout(function() {
                        alert_reset(end_conf);
                    }, 1100);
                }
                if (end_conf.alert_fadeout == "boingOutDown") {
                    $('.content_img').addClass('magictime boingOutDown');
                    setTimeout(function() {
                        alert_reset(end_conf);
                    }, 1100);
                }
                if (end_conf.alert_fadeout == "spaceOutUp") {
                    $('.content_img').addClass('magictime spaceOutUp');
                    setTimeout(function() {
                        alert_reset(end_conf);
                    }, 1100);
                }
                if (end_conf.alert_fadeout == "spaceOutRight") {
                    $('.content_img').addClass('magictime spaceOutRight');
                    setTimeout(function() {
                        alert_reset(end_conf);
                    }, 1100);
                }
                if (end_conf.alert_fadeout == "spaceOutDown") {
                    $('.content_img').addClass('magictime spaceOutDown');
                    setTimeout(function() {
                        alert_reset(end_conf);
                    }, 1100);
                }
                if (end_conf.alert_fadeout == "spaceOutLeft") {
                    $('.content_img').addClass('magictime spaceOutLeft');
                    setTimeout(function() {
                        alert_reset(end_conf);
                    }, 1100);
                }
                if (end_conf.alert_fadeout == "rotateDown") {
                    $('.content_img').addClass('magictime rotateDown');
                    setTimeout(function() {
                        alert_reset(end_conf);
                    }, 1100);
                }
                if (end_conf.alert_fadeout == "rotateUp") {
                    $('.content_img').addClass('magictime rotateUp');
                    setTimeout(function() {
                        alert_reset(end_conf);
                    }, 1100);
                }
                if (end_conf.alert_fadeout == "rotateLeft") {
                    $('.content_img').addClass('magictime rotateLeft');
                    setTimeout(function() {
                        alert_reset(end_conf);
                    }, 1100);
                }
                if (end_conf.alert_fadeout == "rotateRight") {
                    $('.content_img').addClass('magictime rotateRight');
                    setTimeout(function() {
                        alert_reset(end_conf);
                    }, 1100);
                }
                clearInterval(overlay_interval);

                if(tmp_conf.obs_change == "1"){
                    if(app.obs.isConnected){
                        if(tmp_conf.obs_stay == "0") {
                            app.obs.scene(app.obs.current_scene);
                        }
                    }
                }

                if(typeof sessionStorage.tmp_overlay_exec != "undefined"){
                    sessionStorage.removeItem("tmp_overlay_exec");
                    setTimeout(function(){
                        window.close();
                    }, 1150);
                }
            }, end_conf.timer_time * 1000);
        };

        var tmp_user = typeof alert.reward.user != "undefined" ? alert.reward.user.display_name : "";

        if (tmp_conf.status == "0") {
            alert_reset(tmp_conf);
            return false;
        }

        $('.alert_body').html('');

        $("<style type='text/css' id='alert_css'> @-webkit-keyframes glow {from {text-shadow: 0 0 10px #fff, 0 0 20px #fff, 0 0 30px " + tmp_conf.glow_light + ", 0 0 40px " + tmp_conf.glow_light + ", 0 0 50px " + tmp_conf.glow_light + ", 0 0 60px " + tmp_conf.glow_light + ", 0 0 70px " + tmp_conf.glow_light + ";} to {text-shadow: 0 0 20px #fff, 0 0 30px " + tmp_conf.glow_hard + ", 0 0 40px " + tmp_conf.glow_hard + ", 0 0 50px " + tmp_conf.glow_hard + ", 0 0 60px " + tmp_conf.glow_hard + ", 0 0 70px " + tmp_conf.glow_hard + ", 0 0 80px " + tmp_conf.glow_hard + ";}} @-webkit-keyframes glow_img {from {box-shadow: 0 0 10px #fff, 0 0 20px #fff, 0 0 30px " + tmp_conf.glow_light + ", 0 0 40px " + tmp_conf.glow_light + ", 0 0 50px " + tmp_conf.glow_light + ", 0 0 60px " + tmp_conf.glow_light + ", 0 0 70px " + tmp_conf.glow_light + ";}to {box-shadow: 0 0 20px #fff, 0 0 30px " + tmp_conf.glow_hard + ", 0 0 40px " + tmp_conf.glow_hard + ", 0 0 50px " + tmp_conf.glow_hard + ", 0 0 60px " + tmp_conf.glow_hard + ", 0 0 70px " + tmp_conf.glow_hard + ", 0 0 80px " + tmp_conf.glow_hard + ";}} .glow {text-shadow: 0 0 10px #fff, 0 0 20px #fff, 0 0 30px " + tmp_conf.glow_light + ", 0 0 40px " + tmp_conf.glow_light + ", 0 0 50px " + tmp_conf.glow_light + ", 0 0 60px " + tmp_conf.glow_light + ", 0 0 70px " + tmp_conf.glow_light + ";animation: glow 1s ease-in-out infinite alternate;text-align: center;} .glow:hover {text-shadow: 0 0 20px #fff, 0 0 30px " + tmp_conf.glow_hard + ", 0 0 40px " + tmp_conf.glow_hard + ", 0 0 50px " + tmp_conf.glow_hard + ", 0 0 60px " + tmp_conf.glow_hard + ", 0 0 70px " + tmp_conf.glow_hard + ", 0 0 80px " + tmp_conf.glow_hard + ";cursor:pointer} </style>").appendTo("head");

        if(tmp_conf.glow == "1"){
            $(".alert_body").addClass('glow');
        } else {
            $(".alert_body").removeClass('glow');
        }

        if(tmp_conf.body !== ""){
            tmp_conf.body = tmp_conf.body.replace(/\$user+/g, tmp_user);
            var tmp_body = typeof alert.reward.user_input != "undefined" ? decodeURI(tmp_conf.body) + " <br>" + alert.reward.user_input : decodeURI(tmp_conf.body);
            if (app.debug) app.logs(tmp_conf.font_color);
            $('.alert_body').html(_parseEmotes(tmp_body)).css("font-family", tmp_conf.font).css("color", tmp_conf.font_color);
        }

        // alert position
        if(tmp_conf.alert_position == "center") {
            $('.content_img').addClass('center');
        }
        if(tmp_conf.alert_position == "tleft") {
            $('.content_img').addClass('top').addClass('left');
        }
        if(tmp_conf.alert_position == "tright") {
            $('.content_img').addClass('top').addClass('right');
        }
        if(tmp_conf.alert_position == "bleft") {
            $('.content_img').addClass('bottom').addClass('left');
        }
        if(tmp_conf.alert_position == "bright") {
            $('.content_img').addClass('bottom').addClass('right');
        }

        if (tmp_conf.type_img == "static" && tmp_conf.img_url !== "") {
            $('.content_img .static').removeClass('hide');
        }
        if (tmp_conf.type_img == "spin" && tmp_conf.img_url !== "") {
            $('.content_img .spin').removeClass('hide');
        }
        if (tmp_conf.type_img == "spin_wheel" && tmp_conf.img_url !== "") {
            $('.content_img .spin_wheel').removeClass('hide');
        }
        if (tmp_conf.type_img == "magic_type" && tmp_conf.img_url !== "") {
            $('.content_img .magic_type').removeClass('hide');
        }

        if (tmp_conf.shape_img == "round" && tmp_conf.img_url !== "") {
            $('.content_img img').addClass('round');
        }
        if (tmp_conf.shape_img == "circle" && tmp_conf.img_url !== "") {
            $('.content_img img').addClass('circle_ov');
        }
        if (tmp_conf.shape_img == "triangle" && tmp_conf.img_url !== "") {
            $('.content_img img').addClass('triangle');
        }
        if (tmp_conf.shape_img == "rectangleh" && tmp_conf.img_url !== "") {
            $('.content_img img').addClass('rectangleh');
        }
        if (tmp_conf.shape_img == "rectanglev" && tmp_conf.img_url !== "") {
            $('.content_img img').addClass('rectanglev');
        }
        if (tmp_conf.shape_img == "trapezoid" && tmp_conf.img_url !== "") {
            $('.content_img img').addClass('trapezoid');
        }
        if (tmp_conf.shape_img == "pentagon" && tmp_conf.img_url !== "") {
            $('.content_img img').addClass('pentagon');
        }
        if (tmp_conf.shape_img == "diamond" && tmp_conf.img_url !== "") {
            $('.content_img img').addClass('diamond');
        }
        if (tmp_conf.shape_img == "heart" && tmp_conf.img_url !== "") {
            $('.content_img img').addClass('heart');
        }
        if (tmp_conf.shape_img == "equis" && tmp_conf.img_url !== "") {
            $('.content_img img').addClass('equis');
        }
        if (tmp_conf.shape_img == "star" && tmp_conf.img_url !== "") {
            $('.content_img img').addClass('star');
        }
        if (tmp_conf.shape_img == "crazy_star" && tmp_conf.img_url !== "") {
            $('.content_img img').addClass('crazy_star');
        }
        if (tmp_conf.shape_img == "magic" && tmp_conf.img_url !== "") {
            $('.content_img').addClass('magic_shape');
        }

        if (typeof tmp_conf.video_url != "undefined" && tmp_conf.video_url !== "" && typeof tmp_conf.body != "undefined" && tmp_conf.body !== "") {
            $('.alert_body, .content_img_template, .alert_video').addClass('overlap').addClass('transparent');
            $('.alert_body').addClass('left');
            $('.content_img_template').addClass('right');
        }

        if(tmp_conf.img_url !== ""){
            $('.content_img img:not(.emote)').prop("src", tmp_conf.img_url);
        }
        if(tmp_conf.video_url !== ""){
            $.ajax({
                url: '/api/mediaType',
                data: {
                    "media": tmp_conf.video_url.replace("/media/", "")
                },
                type: 'POST',
                success: function(data){
                    if(data.type == "youtube"){
                        tmp_conf.video_url = data.url;
                    }
                    if($('#videoAlert').length > 0) $('#videoAlert').remove();
                    window.videoElement = document.createElement('video');
                    videoElement.id = "videoAlert";
                    videoElement.setAttribute('src', tmp_conf.video_url);
                    videoElement.setAttribute('autoplay', true);
                    videoElement.setAttribute('muted', true);
                    videoElement.volume = 0;

                    videoElement.addEventListener('canplay', function() {
                        alert_start();
                        this.play();
                    }, false);
                    document.body.appendChild(videoElement);

                    $('.alert_video').append('<br>');
                    $('#videoAlert').appendTo('.alert_video');

                    if(tmp_conf.audio_url !== ""){
                        if($('#audioAlert').length > 0) $('#audioAlert').remove();
                        window.audioElement = document.createElement('audio');
                        audioElement.id = "audioAlert";
                        audioElement.setAttribute('src', tmp_conf.video_url);
                        audioElement.volume = tmp_conf.audio_volumen;

                        audioElement.addEventListener('canplay', function() {
                            this.play();
                        }, false);
                        document.body.appendChild(audioElement);
                    }
                }
            });
        } else {
            if(tmp_conf.audio_url !== ""){
                $.ajax({
                    url: '/api/mediaType',
                    data: {
                        "media": tmp_conf.audio_url.replace("/media/", "")
                    },
                    type: 'POST',
                    success: function(data){
                        if(data.type == "youtube"){
                            tmp_conf.audio_url = data.url;
                        }

                        if($('#audioAlert').length > 0) $('#audioAlert').remove();
                        window.audioElement = document.createElement('audio');
                        audioElement.id = "audioAlert";
                        audioElement.setAttribute('src', tmp_conf.audio_url);
                        audioElement.volume = tmp_conf.audio_volumen;
                        audioElement.onplay = function() {
                            alert_start();
                        };
                        audioElement.addEventListener('canplay', function() {
                            this.play();
                        }, false);
                        document.body.appendChild(audioElement);
                    }
                });
            } else {
                alert_start();
            }
        }
    };

    /*
    * function for text to speech
    */
    var _readOutLoud = function(message, tmp_conf) {
        app.logs(message);
        var speech = new SpeechSynthesisUtterance();

        // Set the text and voice attributes.
        speech.text = message;
        speech.volume = tmp_conf.audio_volumen;
        speech.rate = 1;
        speech.pitch = 1;
        speech.lang = "en-US";

        window.speechSynthesis.speak(speech);
    };

    /*
    * function for listening twitch pup
    */
    var _pub_connect = function() {
        var heartbeatInterval = 1000 * 60; //ms between PING's
        var reconnectInterval = 1000 * 3; //ms to wait before reconnect
        var heartbeatHandle;
        var process_cheer = [];
        var ws = new WebSocket('wss://pubsub-edge.twitch.tv');

        var nonce = function(length) {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            for (var i = 0; i < length; i++) {
                text += possible.charAt(Math.floor(Math.random() * possible.length));
            }
            return text;
        };

        var heartbeat = function() {
            message = {
                type: 'PING'
            };
            if(app.debug) app.logs('SENT: ' + JSON.stringify(message) + '\n');
            ws.send(JSON.stringify(message));
        };

        var listen = function(topic) {
            message = {
                type: 'LISTEN',
                nonce: nonce(15),
                data: {
                    topics: topic.split(","),
                    auth_token: app.user.tkn
                }
            };
            if(app.debug) app.logs('SENT: ' + JSON.stringify(message) + '\n');
            ws.send(JSON.stringify(message));
        };

        ws.onopen = function(event) {
            if(app.debug) app.logs('INFO: Socket Opened\n');
            heartbeat();
            heartbeatHandle = setInterval(heartbeat, heartbeatInterval);
            listen("channel-points-channel-v1." + app.user.id + ",channel-bits-events-v2." + app.user.id);
        };

        ws.onerror = function(error) {
            if(app.debug) app.logs('ERR:  ' + JSON.stringify(error) + '\n');
        };

        ws.onmessage = function(event) {
            message = JSON.parse(event.data);
            if(app.debug) app.logs('RECV: ' + JSON.stringify(message) + '\n');
            if (message.type == 'RECONNECT') {
                if(app.debug) app.logs('INFO: Reconnecting...\n');
                setTimeout(pub_connect, reconnectInterval);
            }

            if(message.type == 'MESSAGE'){
                message = JSON.parse(message.data.message);

                if(message.message_type == 'bits_event'){
                    var tmp_cheer = message.message_id;
                    process_cheer[message.message_id] = message.bits_used;

                    if(app.debug) app.logs(process_cheer);
                }
            }

            if(message.type == 'reward-redeemed'){
                var tmp_reward = message.data.redemption.reward;

                app.getOverlays(function(){
                    $.each(app.overlays, function(index, overlay) {
                        if(tmp_reward.id == overlay.reward && overlay.status == "1"){
                            overlay.reward = message.data.redemption;
                            queue.push(overlay);
                        }
                    });
                    if(queue.length > 0) _execOverlay(queue[0]);
                });
            }
        };

        ws.onclose = function() {
            if(app.debug) app.logs('INFO: Socket Closed\n');
            clearInterval(heartbeatHandle);
            if(app.debug) app.logs('INFO: Reconnecting...\n');
            setTimeout(pub_connect, reconnectInterval);
        };
    };

    /*
    * function for listening twitch chat
    */
    var _chat_connect = function(){
        var who = this;
        var try_reconnect = 10;
        var try_made = 0;
        var reconnect_interval;
        who.username = app.user.name;
        who.password = "oauth:" + app.user.tkn;
        who.channel = "#" + app.user.name;

        who.server = 'irc-ws.chat.twitch.tv';
        who.port = 443;

        who.open = function(cb){
            who.webSocket = new WebSocket('wss://' + who.server + ':' + who.port + '/', 'irc');

            who.webSocket.onmessage = who.onMessage.bind(who);
            who.webSocket.onerror = who.onError.bind(who);
            who.webSocket.onclose = who.onClose.bind(who);
            who.webSocket.onopen = who.onOpen.bind(who);

            $(".msg_txt").html('');
            if(typeof reconnect_interval != "undefined") clearInterval(reconnect_interval);
            if(typeof cb != "undefined") who.cb = cb;
        };

        who.onError = function(message){
            app.logs('<b style="color: red;">Error</b>: ' + message);
        };

        who.onMessage = function(message){
            if(message !== null){
                var parsed = who.parseMessage(message.data);
                if(parsed !== null){
                    var name_res = "";
                    var d = new Date();
                    var hr = (d.getHours() < 10) ? "0"  + d.getHours(): d.getHours();
                    var mi = (d.getMinutes() < 10) ? "0" + d.getMinutes(): d.getMinutes();
                    var se = (d.getSeconds() < 10) ? "0" + d.getSeconds(): d.getSeconds();
                    var tmp_t = hr + ":" + mi + ":" + se;

                    if(parsed.tags !== null && typeof parsed.tags == "object"){
                        name_res = (typeof parsed.tags["display-name"] == "string") ? parsed.tags["display-name"] : name_res;
                    }
                    name_res = (name_res !== "") ? name_res : parsed.username;
                    if(name_res !== null){
                        name_res = name_res.split(" ");
                        name_res = name_res[0];
                    } else {
                        name_res = "tmi.twitch.tv";
                    }
                    if(app.debug) app.logs(who.username, parsed);
                    who.tmp_sender = name_res;

                    if(parsed.command === "PRIVMSG") {
                    } else if(parsed.command === "NOTICE") {
                    } else if(parsed.command === "RECONNECT ") {
                    } else if(parsed.command === "ROOMSTATE ") {
                    } else if(parsed.command === "HOSTTARGET") {
                    } else if(parsed.command === "CLEARMSG"){
                    } else if(parsed.command === "CLEARCHAT"){
                    } else if(parsed.command === "USERNOTICE"){
                    } else if(parsed.command === "WHISPER") {
                    } else if(parsed.command === "PING") {
                        who.webSocket.send("PONG :" + parsed.message);
                    }
                }
                if(message.data.indexOf(':Welcome') >= 0) {
                }
            }
        };

        who.onOpen = function(){
            var socket = who.webSocket;

            if (socket !== null && socket.readyState === 1) {
                app.logs('<p style="text-align:left;"><b style="color:olive">Connecting</b> and authenticating:<br> user <b style="color:blue">' + who.username + '</b><br> on channel <b style="color:orange;">' + who.channel + '</b></p>', 2000);

                socket.send('CAP REQ :twitch.tv/tags');
                socket.send('CAP REQ :twitch.tv/commands');
                socket.send('CAP REQ :twitch.tv/membership');
                socket.send('PASS ' + who.password);
                socket.send('NICK ' + who.username);
                socket.send('USER ' + who.username + ' 8 * :' + who.username);
                socket.send('JOIN ' + who.channel);
                if(typeof who.cb != "undefined") {
                    who.cb();
                    who.cb = undefined;
                }
            }
        };

        who.onClose = function(){
            app.logs('<p style="text-align:left;">' + who.username + ' <b style="color:red">Disconnected</b><br> from chat <b style="color:yellow">#' + who.channel + '</b>.</p>', 2000);

            if(try_made < try_reconnect){
                if(typeof reconnect_interval != "undefined") clearInterval(reconnect_interval);
                reconnect_interval = setInterval(function(){
                    who.open();
                    try_made++;
                }, 5000);
            }
        };

        who.close = function(){
            if(typeof who.webSocket != "undefined" && who.webSocket.readyState == 1){
                who.webSocket.close();
            }
        };

        /* this is an example of an IRC message with tags. I split it across
        multiple lines for readability. The spaces at the beginning of each line are
        intentional to show where each set of information is parsed. */

        //@badges=global_mod/1,turbo/1;color=#0D4200;display-name=TWITCH_UserNaME;emotes=25:0-4,12-16/1902:6-10;mod=0;room-id=1337;subscriber=0;turbo=1;user-id=1337;user-type=global_mod
        // :twitch_username!twitch_username@twitch_username.tmi.twitch.tv
        // PRIVMSG
        // #channel
        // :Kappa Keepo Kappa

        who.parseMessage = function(rawMessage) {
            var parsedMessage = {
                message: null,
                tags: null,
                command: null,
                original: rawMessage,
                channel: null,
                username: null
            };

            if(rawMessage[0] === '@'){
                var tagIndex = rawMessage.indexOf(' '),
                userIndex = rawMessage.indexOf(' ', tagIndex + 1),
                commandIndex = rawMessage.indexOf(' ', userIndex + 1),
                channelIndex = rawMessage.indexOf(' ', commandIndex + 1),
                messageIndex = rawMessage.indexOf(':', channelIndex + 1);

                parsedMessage.tags = rawMessage.slice(0, tagIndex);
                parsedMessage.tags = parsedMessage.tags.replace("@", "");
                parsedMessage.tags = parsedMessage.tags.split(";");
                if(parsedMessage.tags !== null) {
                    var tmp_tag = {};
                    $.each(parsedMessage.tags, function(index, value) {
                        var tmp_values = value.split("=");
                        tmp_tag[tmp_values[0]] = tmp_values[1];
                    });
                    parsedMessage.tags = tmp_tag;
                }
                parsedMessage.username = rawMessage.slice(tagIndex + 2, rawMessage.indexOf('!'));
                parsedMessage.command = rawMessage.slice(userIndex + 1, commandIndex);
                parsedMessage.channel = rawMessage.slice(commandIndex + 1, channelIndex).replace("#", "");
                parsedMessage.message = rawMessage.slice(messageIndex + 1);
            } else if(rawMessage[0] === ':') {
                var tmp_res = rawMessage.split(" ");
                parsedMessage.username = tmp_res[0].replace(":", "");
                parsedMessage.command = tmp_res[1];
                parsedMessage.channel = tmp_res[2].replace("#", "");
                parsedMessage.message = typeof tmp_res[3] == "string" ? tmp_res[3] : '';
                parsedMessage.tags = {};
            } else if(rawMessage.startsWith("PING")) {
                parsedMessage.command = "PING";
                parsedMessage.message = rawMessage.split(":")[1];
            }

            return parsedMessage;
        };

        /*
        * chatclient send msg function
        */
        who.sendmsg = function(msg){
            var tmp_msg = (typeof msg != "undefined") ? msg : $('.msg_txt').val();
            who.webSocket.send("PRIVMSG " + who.channel + " :" + tmp_msg);
        };

        return {
            open: who.open,
            close: who.close,
            send: who.sendmsg
        };
    };

    /*
    * function to parse emotes from string
    */
    var _parseEmotes = function(msg){
        var new_res = "";
        msg = msg.split(" ");
        $.each(msg, function(index, word) {
            if(typeof emotes[word] != "undefined"){
                new_res += "<img class='emote' src='" + emotes[word].url + "' alt='' /> ";
            } else {
                new_res += word + " ";
            }
        });
        return new_res;
    };

    root.init = _init;
    root.exec = _execOverlay;
    root.open = {};
    root.close = {};
    root.send = {};

    return root;
})();

$(document).ready(function(){
    app.overlay.init();
});
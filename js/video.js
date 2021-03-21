app.video = (function(){
	var root = {};

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

		app.logs("init video vue!");
	};

	var _jquery = function(){
		$('footer').addClass('hide');
		$('#playerVideo').appendTo('main');

        $(window).on('load', function(){
            $('#playerVideo')[0].addEventListener('loadedmetadata', function(){
                var who = $(this);
                var tmp_src = who.attr("src");

                if(typeof sessionStorage.tmp_src != "undefined") {
                    if(sessionStorage.tmp_src == tmp_src) who[0].currentTime = sessionStorage.tmp_seek;
                }

                if(typeof sessionStorage.tmp_vol != "undefined") who[0].volume = sessionStorage.tmp_vol;
                if(typeof sessionStorage.tmp_fullscreen != "undefined" && sessionStorage.tmp_fullscreen == "true" && !document.fullscreenElement){
                    if(who[0].requestFullscreen){
                        who[0].requestFullscreen().then(function(){console.log('fullscreen set!');}).catch(function(err){console.log(err);});
                    } else if(who[0].mzRequestFullscreen) {
                        who[0].mzRequestFullscreen();
                    } else if(who.msRequestFullscreen) {
                        who[0].msRequestFullscreen();
                    }
                }

                $('#playerVideo')[0].addEventListener('progress',function(){
                    var who = $(this);
                    var tmp_src = who.attr("src");

                    sessionStorage.tmp_src = tmp_src;
                    sessionStorage.tmp_seek = who[0].currentTime;
                }, true);

                setTimeout(function() {
                    $("body").getNiceScroll().resize();
                }, 400);
            }, true);

            $('#playerVideo')[0].addEventListener('ended',function(){
                var who = $(this);
            }, true);

            $('#playerVideo')[0].addEventListener('error', function(e,ui){
                var msg = '';
                var who = $(this);
                var tmp_src = who.attr("src");

                sessionStorage.tmp_src = tmp_src;
                sessionStorage.tmp_seek = who[0].currentTime;
                if(e.target.error != null){
                    switch (e.target.error.code) {
                        case e.target.error.MEDIA_ERR_ABORTED:
                            msg = app.lang.playerVideo_error1;
                            break;
                        case e.target.error.MEDIA_ERR_NETWORK:
                            msg = app.lang.playerVideo_error2;
                            break;
                        case e.target.error.MEDIA_ERR_DECODE:
                            msg = app.lang.playerVideo_error3;
                            break;
                        case e.target.error.MEDIA_ERR_SRC_NOT_SUPPORTED:
                            if($(this).attr("src") === "") return;
                            msg = app.lang.playerVideo_error4;
                            break;
                        default:
                            msg = app.lang.playerVideo_error5;
                            break;
                    }
                }
                app.alert("<i class='small material-icons red-text'>report_problem</i><br>" + msg + "<br><br>" + JSON.stringify(e) + "<br><br>" + JSON.stringify(ui),"error", true);
            }, true);

            $('#playerVideo')[0].addEventListener('webkitfullscreenchange mozfullscreenchange fullscreenchange MSFullscreenChange', function(){
                if(!document.fullscreenElement) {
                    sessionStorage.tmp_fullscreen = "false";
                } else {
                    sessionStorage.tmp_fullscreen = "true";
                }
            }, true);

            if(typeof sessionStorage.tmp_seek != "undefined"){
                $('#playerVideo')[0].currentTime = parseFloat(sessionStorage.tmp_seek);
                sessionStorage.removeItem("tmp_seek");
            }

            if(typeof sessionStorage.tmp_src != "undefined"){
                $('#playerVideo source').attr('src', sessionStorage.tmp_src);
                sessionStorage.removeItem("tmp_src");
            }
            $('#playerVideo')[0].play();

    		$('.fixed-action-btn, .sidenavbtn').on('mouseover', function(){
    			$(this).stop().animate({
    				'opacity': 1
    			}, 400);
    		}).on('mouseleave', function(){
    			$(this).stop().animate({
    				'opacity': 0.1
    			}, 200);
    		});

    		$('.fixed-action-btn, .sidenavbtn').trigger('mouseleave');
        });

		app.logs("init video jquery!");
	};

	var _init = function(){
		_vue();
		_jquery();

		if(app.debug) app.alert("video load!");
	};

	root.init = _init;

	return root;
})();

$(document).ready(function(){
	app.video.init();
});
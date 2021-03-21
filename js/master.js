var _scrollToTop = function(getUrlAfterHash){
	if($(getUrlAfterHash).length === 0) return false;
	var idPositionHeight = $(getUrlAfterHash).offset();
	$("#body").attr("data-scolling", "on");
	$('html, body').stop().animate({ scrollTop : idPositionHeight.top}, 800, function(){
		$("#body").attr("data-scolling", "");
		$(window).resize();
	});
};

if(window.location.hash){
	var getUrlAfterHash = window.location.hash;

	if(getUrlAfterHash != "#!" && getUrlAfterHash != "#" && getUrlAfterHash.indexOf("access_token") < 0){
		if($(getUrlAfterHash).length > 0){
			$('html, body').animate({ scrollTop : 0 });
			_scrollToTop(getUrlAfterHash);
		}
	}
}

window.app = (function(){
	var root = {};
	var _debug = true;
	var init_title = window.document.title;
	var test_window;

	navigator.sayswho = (function(){
		var ua = navigator.userAgent, tem,
		M = ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
		if(/trident/i.test(M[1])){
			tem=  /\brv[ :]+(\d+)/g.exec(ua) || [];
			return 'IE '+(tem[1] || '');
		}
		if(M[1]=== 'Chrome'){
			tem= ua.match(/\b(OPR|Edge)\/(\d+)/);
			if(tem!== null) return tem.slice(1).join(' ').replace('OPR', 'Opera');
		}
		M = M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
		if((tem= ua.match(/version\/(\d+)/i))!== null) M.splice(1, 1, tem[1]);
		return M.join(' ');
	})();

	var _rndcolors = function(count){
		var tmp_arr = [];
		var letras = '0123456789ABCDEF'.split('');

		for(var i=0;i<count;i++){
			var color = '#';
			for (var j = 0; j < 6; j++ ) {
				color += letras[Math.round(Math.random() * 15)];
			}

			tmp_arr.push(color);
		}

		return tmp_arr;
	};

	var _init = function(){
		if(typeof app.user != "undefined"){
			if(app.user.level >= 50){
				root.get_files = _getFiles;
			}
			if(app.user.level >= 1){
				root.get_images =_getImages;
			}
		}
		_init_jq_defaults(function(){
			_init_listeners(function(){
				_isMobile(function(){
					_init_vue(function(){
					});
				});
			});
		});
	};

	var _init_jq_defaults = function(cb){
		//jquery defaults config
		$.ajaxSetup({
			xhr: function(){
				$(".progress .determinate").css('width', '0%');
				var xhr = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
				xhr.onprogress = function(e) {// For downloads
					if (e.lengthComputable) {
						root.downloading = true;
						window.document.title = init_title + ' ' + Math.floor((e.loaded / e.total) * 100) + '%';
						$(".progress .determinate").css('width', Math.floor((e.loaded / e.total) * 100) + '%');
						if($(".progress").hasClass('hide')) $(".progress").removeClass('hide');
					}
				};
				xhr.onerror = function(e){
					window.document.title = init_title;
					app.alert(e.textContent);
					root.downloading = false;
				};
				xhr.onabort = function(){
					window.document.title = init_title;
					app.alert("Abort!");
					root.downloading = false;
				};
				xhr.onload = function(){
					window.document.title = init_title;
					$(".progress .determinate").addClass('indeterminate').removeClass('determinate');
					root.downloading = true;
				};
				xhr.onloadend = function(){
					window.document.title = init_title;
					$(".progress .determinate").addClass('indeterminate').removeClass('determinate');
					root.downloading = false;
				};
				if(xhr.upload) {
					xhr.upload.onprogress = function(e) {
						if (e.lengthComputable) {
							window.document.title =  init_title + ' ' + Math.floor((e.loaded / e.total) * 100) + '%';
							$(".progress .indeterminate").addClass('determinate').removeClass('indeterminate');
							$(".progress .determinate").css('width', Math.floor((e.loaded / e.total) * 100) + '%');
							if($(".progress").hasClass('hide')) $(".progress").removeClass('hide');
							root.downloading = true;
						}
					};
					xhr.upload.onloadstart = function() {
						window.document.title = init_title + ' ' + '0%';
						$(".progress .indeterminate").addClass('determinate').removeClass('indeterminate');
						$(".progress .determinate").css('width', '0%');
						root.downloading = true;
					};
					xhr.upload.onload = function(){
						window.document.title = init_title;
						root.downloading = true;
						$(".progress .indeterminate").css('width', '0%');
						$(".progress .indeterminate").addClass('determinate').removeClass('indeterminate');
					};
					xhr.upload.onloadend = function(){
						window.document.title = init_title;
						$(".progress .determinate").addClass('indeterminate').removeClass('determinate');
						root.downloading = false;
					};
				}
				return xhr;
			},
			beforeSend: function(xhr){
				var tmp_data_decode = {};
				if(this.url != '/api/updloadImage?type=custom' && this.url != '/api/uploadFiles?type=pdownload' && this.url != '/api/uploadFiles?type=media'){
					if(typeof this.data != "undefined") {
						var tmp_decode = this.data.split("&");
						if(tmp_decode.length > 0) {
							$.each(tmp_decode, function(index, el) {
								el = el.split("=");
								tmp_data_decode[el[0]] = el[1];
							});
						}
					}
					this.data = "jwt=" + signJWT(tmp_data_decode);
				}
				xhr.setRequestHeader("Authorization","Bearer " + app.apikey);
			},
			dataFilter: function(data, type){
				data = typeof data != "object" ? JSON.parse(data) : data;
				if(typeof data.jwt != "undefined"){
					if(verifyJWT(data.jwt)){
						data = decodeJWT(data.jwt);
					} else {
						data = {
							success: false,
							error: "Ilegal jwt"
						};
					}
				}
				if(typeof data.logoutapp != "undefined" && typeof app.user != "undefined"){
					window.location.reload();
					return false;
				}
				if(app.debug) app.logs(data);
				return JSON.stringify(data);
			}
		});

		$(document).ajaxStart(function() {
			$(".progress").removeClass('hide');
		}).ajaxSuccess(function(event, xhr, settings) {
			switch(settings.url){
				case '/':
					$(".progress").addClass('hide');
					break;
				default:
					$(".progress").addClass('hide');
					break;
			}
		}).ajaxError(function(event, xhr, settings, thrownError){
			switch(settings.url){
				case '/':
					$(".progress").addClass('hide');
					break;
				default:
					$(".progress").addClass('hide');
					break;
			}
		    app.logs(xhr);
		    app.alert("<b class='red-text'>ERROR</b>!<br>" + xhr.responseText, true);
		});

		$('a').on('click', function(e){
			if(typeof $(this).attr('href') != "undefined"){
				if($(this).attr('href').indexOf("#") === 0 && $(this).attr('href') != "#!"){
					e.preventDefault();
					var getUrlAfterHash = $(this).attr('href');
					_scrollToTop(getUrlAfterHash);
				} else if($(this).attr('href') == "#!"){
					e.preventDefault();
				}
			}
		});

		app.logs("init main jq defaults!");
		if(typeof cb == "function") cb();
	};

	var _init_listeners = function(cb){
		//window object
		$(window).resize(function() {
			$(".responsive.iframe").each(function() {
				$(this).data('aspectRatio', this.height / this.width).removeAttr('height').removeAttr('width');
				var newWidth = $(this).parent().parent().parent().parent().width();
				var newheight = $(this).parent().parent().parent().parent().height();
				var $el = $(this);
				$el.width(newWidth).height(newheight);
			});
			_isMobile();

			app.logs("resize!");
		});

		$(window).on("beforeunload", function() {
            if(app.downloading) {
            	event.preventDefault();
            	event.returnValue = app.lang.window_beforeunload_active_download;
            	return event.returnValue;
            }
        });

		$(window).on("load", function(){
			$('.modal').modal({
				"dismissible" : false,
				onOpenEnd: function(){
					var who = $(this);

					setTimeout(function(){
						if(!app.ismobile) $("#" + who.attr("id") + " .modal-content").getNiceScroll().resize();
					}, 300);

					if(who.attr("id") == "confirmModal"){
						var tmp_height = $("#confirmModal .titulo").height() + 200;
						tmp_height = tmp_height >= 450 ? '55%' : tmp_height + "px";
						$("#confirmModal").css('height', tmp_height);
					}


					if($(".tab", $("#" + who.attr("id"))).length > 0){
						var tmp_tab = $(".tab a", $("#" + who.attr("id"))).first().attr('href');
						tmp_tab = tmp_tab.replace("#", "");

						$('.tabs', $("#" + who.attr("id"))).tabs('select', tmp_tab);
						if(typeof $("form", $("#" + who.attr("id")))[0] != "undefined"){
							$("form", $("#" + who.attr("id")))[0].reset();
						}
					}

					if(who.attr("id") == "overlaysModal"){
						_getRewards(function(){
							_getOverlays(function(){
								M.updateTextFields();
								$('select', "#overlaysModal").formSelect().trigger("change");
							});
						});
					}

					if(who.attr("id") == "usersModal"){
						_getUsers(function(){
						});
					}

					if(who.attr("id") == "imagesModal"){
						_getImages(function(){
						});
					}

					if(who.attr("id") == "filesModal"){
						_getFiles(function(){
						});
					}

					if(who.attr("id") == "mediaModal"){
						_getMedia(function(){
						});
					}
				},
				onCloseEnd: function(){
					var who = $(this);

					if(who.attr("id") == "confirmModal"){
						who.removeClass('bottom-sheet').removeClass('full_width');
					}
				}
			});

			$('.tabs').tabs();

			$('.tab a').off('click').on('click', function(event) {
				var href = $(this).attr("href");

				setTimeout(function(){
					if(!app.ismobile) $("body, .modal .modal-content").getNiceScroll().resize();
				}, 500);

				var tmp_li = $(this).parent();
				var tmp_ul = tmp_li.parent();
				var tmp_parent = tmp_ul.parent().parent();

				if($(tmp_li, tmp_ul).index() == 0 && tmp_parent.hasClass('modal-content')){
					if(typeof $("form", tmp_parent)[0] != "undefined"){
						$("form", tmp_parent)[0].reset();
						$("i", tmp_ul).eq(1).html(href == "#users" ? "" : "<i class='material-icons'>add</i>");

						M.updateTextFields();
					}
				}

				if(href == "#overlays" || href == "#form_overlays"){
					setTimeout(function(){
						$("#tab_form_overlay").tabs('select', "init_ov");
					}, 100);
				}
			});

			if(!app.ismobile){
				$(".dropdown-content.select-dropdown").addClass('scroller');
				$("body, .modal-content, .scroller, .item-link").niceScroll({
					cursorcolor:app.lfcolor,
					cursorwidth:"16px",
					cursorborderradius:2,
					autohidemode:'scroll'
				});
			}

			app.ctrl_state = false;
            app.shift_state = false;
            app.alt_state = false;
            $('body').on("keydown", function(e) {
                app.ctrl_state = e.ctrlKey;
                app.shift_state = e.shiftKey;
                app.alt_state = e.altKey;
            }).on("keyup", function(e) {
                app.ctrl_state = e.ctrlKey;
                app.shift_state = e.shiftKey;
                app.alt_state = e.altKey;
            });

			setTimeout(function(){
				$(window).resize();

				if(typeof app.user != "undefined"){
					setInterval(function(){
						$.ajax({
							url: '/api/ping',
							type: 'POST',
							success: function(){
							}
						});
					}, 5 * 60 * 1000);
				}
			}, 1200);
		});

		//Listeners
		$(document.body).off("submit", "form").on("submit", "form", function(e) {
			e.preventDefault();
			var who = $(this);
			var send_data = {};
			if(typeof who.attr("data-sntzr") != "undefined"){
				$("input", who).each(function(index, el) {
					el = $(this);
					if(typeof el.attr("data-sntzr") != "undefined"){
						send_data[el.attr("name")] = sntzr(el.val());
					} else {
						if(el.attr("type") == "checkbox" || el.attr("type") == "radio"){
							send_data[el.attr("name")] = el.prop("checked") ? true : '';
						} else {
							send_data[el.attr("name")] = el.val();
						}
					}
				});
				$("select", who).each(function(index, el) {
					el = $(this);
					if(typeof el.attr("data-sntzr") != "undefined"){
						send_data[el.attr("name")] = sntzr(el.val());
					} else {
						send_data[el.attr("name")] = el.val();
					}
				});
				$("textarea", who).each(function(index, el) {
					el = $(this);
					if(typeof el.attr("data-sntzr") != "undefined"){
						send_data[el.attr("name")] = sntzr(el.val());
					} else {
						send_data[el.attr("name")] = el.val();
					}
				});
			} else {
				send_data = who.serialize();
			}
			var _send = function(){
				$.ajax({
					url: who.attr("action"),
					type: who.attr("method"),
					data: send_data,
					success: function (data){
						data = typeof data != "object" ? JSON.parse(data) : data;
						if(data.success){
							if(typeof who.attr("data-alert-msg") != "undefined"){
								app.alert(who.attr("data-alert-msg"), true);
							} else {
								window.location.reload();
							}
						} else {
							app.logs(data);
							app.alert(data.error, true);
						}
					}
				});
			};

			if(typeof who.attr("data-confirm") != "undefined"){
				app.confirm(who.attr("data-confirm"), _send);
			} else {
				_send();
			}
		});

		$("select").on('change', function(){
			$(".dropdown-content.select-dropdown").addClass('scroller');
			if(!app.ismobile){
				$(".scroller").niceScroll({
					cursorcolor:app.lfcolor,
					cursorwidth:"16px",
					cursorborderradius:2,
					autohidemode:'scroll'
				});
				setTimeout(function(){
					$(".scroller").getNiceScroll().resize();
				}, 500);
			}
		});

		$(".btnLogout").off("click").on("click", function(){
			var init_logout = function(){
				$.ajax({
					url: '/api/logout',
					type: 'POST',
					success: function(data){
						sessionStorage.removeItem("app_logs");
						window.location.href = "/";
					}
				});
			};

			app.confirm(app.lang.btnLogout_close_session, init_logout);
		});

		$("#overlayProfile").off("change").on("change", function(){
			var init_ = function(){
				$.ajax({
					url: '/api/updateUser',
					data: {
						id: app.user.id,
						overlay: Number($("#overlayProfile").prop("checked"))
					},
					type: 'POST',
					success: function(data){
					}
				});
			};

			init_();
		});

		$("#obs_host, #obs_password").off("input").on("input", function(){
			var init_ = function(){
				$.ajax({
					url: '/api/updateUser',
					data: {
						id: app.user.id,
						obs_host: $("#obs_host").val(),
						obs_password: $("#obs_password").val()
					},
					type: 'POST',
					success: function(data){
					}
				});
			};

			init_();
		});

		$(document.body).off("click", ".btnEditaruser").on("click", ".btnEditaruser", function(){
			var who = $(this);
			var data = JSON.parse(who.attr("data-user"));
			$('#usersModal .tabs').tabs('select', "form_user");

			$("a[href='#form_user']").html("<i class='material-icons'>mode_edit</i>");

			$("#user_name").val(data.name);
			$("#user_email").val(data.email);
			$("#user_user").val(data.user);
			$("#user_img").val(data.img);
			$("#user_id").val(data.id);
			$("#user_level").val(data.level);
			$("#user_status").prop('checked', Number(data.status));

			M.updateTextFields();
			$('#usersModal select').formSelect().trigger("change");
		});

		$(".btnCrearUser").off("click").on("click", function(){
			if($("#user_id").val() == "-1"){
				app.alert(app.lang.btnCrearUser_select_user);
				return false;
			}

			if($("#user_name").val() === "" || $("#user_user").val() === "" || $("#user_email").val() === ""){
				app.alert(app.lang.empty_field);
				return false;
			}

			$.ajax({
				url: "/",
				type: "POST",
				data: {
					m: "updateUser",
					id: $("#user_id").val(),
					name: sntzr($("#user_name").val()),
					user: sntzr($("#user_user").val()),
					img: $("#user_img").val(),
					email: sntzr($("#user_email").val()),
					level: $("#user_level").val(),
					status: Number($("#user_status")[0].checked),
					admin: '1'
				},
				success: function(data){
					data = typeof data != "object" ? JSON.parse(data) : data;
					if(data.success){
						app.users = data.users;
						app.ui_vue.users.users = app.users;
						app.alert(app.lang.btnCrearUser_user_process);
						$("#tabUsers").tabs('select', "users");
					} else {
						app.logs(data);
						if(typeof data.error != "undefined") app.alert(data.error, true);
					}

					if(!app.ismobile) $("#usersModal .modal-content").getNiceScroll().resize();
				}
			});
		});

		$(document.body).off("click", ".btnBorraruser").on("click", ".btnBorraruser", function(){
			var tmp_id = $(this).attr("data-id");
			var init_borrar = function(){
				$.ajax({
					url: '/',
					type: 'POST',
					data: {
						m: 'deleteUser',
						id: tmp_id
					},
					success: function(data){
						data = typeof data != "object" ? JSON.parse(data) : data;
						if(data.success){
							app.users = data.users;
							app.ui_vue.users.users = app.users;
							app.alert(app.lang.btnBorraruser_delete_user);
						} else {
							app.logs(data);
							app.alert(app.lang.btnBorraruser_cant_delete_user, true);
						}						}
				});
			};

			app.confirm(app.lang.btnBorraruser_question, init_borrar);
		});

		$(document.body).off("click", ".btnUploadYoutube").on("click", ".btnUploadYoutube", function(){
			if($("#youtube_file").val() === ""){
				app.alert(app.lang.empty_field);
				return false;
			}

			var tmp_config = {
				name: $("#youtube_file").val()
			};
			tmp_config = signJWT(tmp_config);

			$(".resText").html("");

			$.ajax({
				url: "/api/uploadYoutube",
				type: "POST",
				data: {
					youtubeFile: $("#youtube_file").val()
				},
				success: function(data){
					data = typeof data != "object" ? JSON.parse(data) : data;
					if(data.success){
						app.media = data.media;
						app.ui_vue.media.media = app.media;
					} else {
						app.logs(data);
						if(typeof data.error != "undefined") app.alert(data.error, true);
					}
				}
			});
		});

		var ov_fontcolor = new CP(document.querySelector('input[id="font_color"]'));
		ov_fontcolor.on("change", function(color) {
			this.target.value = '#' + color;
		});

		var ov_glow_light = new CP(document.querySelector('input[id="glow_light"]'));
		ov_glow_light.on("change", function(color) {
			this.target.value = '#' + color;
		});

		var ov_glow_hard = new CP(document.querySelector('input[id="glow_hard"]'));
		ov_glow_hard.on("change", function(color) {
			this.target.value = '#' + color;
		});

		$(".btnCreateOverlay").off("click").on("click", function(){
			var api_call = '/api/createOverlay';
			var msg = app.lang.btnCreateOverlay_question_create;

			if($("#oid").val() != "-1"){
				api_call = '/api/updateOverlay';
				msg = app.lang.btnCreateOverlay_question_update;
			}

			if($("#event_trigger").val() === ""){
				app.alert(app.lang.empty_field);
				return false;
			}

			if($("#obs_change").prop("checked") && $("#obs_scene").val() === "" && app.obs.isConnected){
				app.alert(app.lang.empty_field);
				return false;
			} else {
				if($("#body").html() === "" && $("#img_url").val() === "" && $("#audio_url").val() === "" && $("#video_url").val() === ""){
					app.alert(app.lang.empty_field);
					return false;
				}
				if($("#timer_time").val() === ""){
					app.alert(app.lang.empty_field);
					return false;
				}
			}
			var tmp_overlay = {};

			tmp_overlay.oid = $("#oid").val();
			tmp_overlay.reward = $("#event_trigger").val();
			tmp_overlay.status = Number($("#alerts_status").prop("checked"));
			tmp_overlay.config = JSON.stringify({
				"obs_change": app.obs.isConnected ? Number($("#obs_change").prop("checked")) : $("#obs_change").attr("data-value"),
				"obs_scene": app.obs.isConnected ? $("#obs_scene").val() : $("#obs_scene").attr("data-value"),
				"obs_stay": app.obs.isConnected ? Number($("#obs_stay").prop("checked")) : $("#obs_stay").attr("data-value"),
				"body": sntzr($("#body_alert").val()),
				"speech": Number($("#speech_alert").prop("checked")),
				"tochat": Number($("#tochat").prop("checked")),
				"img_url": sntzr($("#img_url").val()),
				"type_img": $("#type_img").val(),
				"shape_img": $("#shape_img").val(),
				"audio_url": sntzr($("#audio_url").val()),
				"audio_volumen": $("#audio_volumen").val(),
				"video_url": sntzr($("#video_url").val()),
				"font": $("#font_alert").val(),
				"font_color": sntzr($("#font_color").val()),
				"glow": Number( $("#glow").prop("checked")),
				"glow_light": sntzr($("#glow_light").val()),
				"glow_hard": sntzr($("#glow_hard").val()),
				"confetti": Number($("#confetti").prop("checked")),
				"conffeti_time": $("#conffeti_time").val(),
				"conffeti_min": $("#conffeti_min").val(),
				"conffeti_max": $("#conffeti_max").val(),
				"alert_position": $("#alert_position").val(),
				"alert_fadein": $("#alert_fadein").val(),
				"alert_fadeout": $("#alert_fadeout").val(),
				"timer_time": $("#timer_time").val(),
				"oid": tmp_overlay.oid,
				"reward": tmp_overlay.reward,
				"status": tmp_overlay.status
			});

			var init_create = function(){
				$.ajax({
					url: api_call,
					data: tmp_overlay,
					type: 'POST',
					success: function(data){
						data = typeof data != "object" ? JSON.parse(data) : data;
						if(data.success){
							app.overlays = data.overlays;
							app.ui_vue.overlays.overlays = app.overlays;
							$('#overlaysModal .tabs').tabs('select', "overlays");
						} else {
							app.logs(data);
							if(typeof data.error != "undefined") app.alert(data.error, true);
						}
					}
				});
			};

			app.confirm(msg, init_create);
		});

		$(".btnTestOverlay").off("click").on("click", function(){
			if($("#event_trigger").val() === ""){
				app.alert(app.lang.empty_field);
				return false;
			}

			if($("#obs_change").prop("checked") && $("#obs_scene").val() === "" && app.obs.isConnected){
				app.alert(app.lang.empty_field);
				return false;
			} else {
				if($("#body").html() === "" && $("#img_url").val() === "" && $("#audio_url").val() === "" && $("#video_url").val() === ""){
					app.alert(app.lang.empty_field);
					return false;
				}
				if($("#timer_time").val() === ""){
					app.alert(app.lang.empty_field);
					return false;
				}
			}
			var tmp_overlay = {};

			tmp_overlay.oid = $("#oid").val();
			tmp_overlay.reward = $("#event_trigger").val();
			tmp_overlay.status = 1;
			tmp_overlay.config = JSON.stringify({
				"obs_change": Number($("#obs_change").prop("checked")),
				"obs_scene": $("#obs_scene").val(),
				"obs_stay": Number($("#obs_stay").prop("checked")),
				"body": sntzr($("#body_alert").val()),
				"speech": Number($("#speech_alert").prop("checked")),
				"tochat": Number($("#tochat").prop("checked")),
				"img_url": sntzr($("#img_url").val()),
				"type_img": $("#type_img").val(),
				"shape_img": $("#shape_img").val(),
				"audio_url": sntzr($("#audio_url").val()),
				"audio_volumen": $("#audio_volumen").val(),
				"video_url": sntzr($("#video_url").val()),
				"font": $("#font_alert").val(),
				"font_color": sntzr($("#font_color").val()),
				"glow": Number( $("#glow").prop("checked")),
				"glow_light": sntzr($("#glow_light").val()),
				"glow_hard": sntzr($("#glow_hard").val()),
				"confetti": Number($("#confetti").prop("checked")),
				"conffeti_time": $("#conffeti_time").val(),
				"conffeti_min": $("#conffeti_min").val(),
				"conffeti_max": $("#conffeti_max").val(),
				"alert_position": $("#alert_position").val(),
				"alert_fadein": $("#alert_fadein").val(),
				"alert_fadeout": $("#alert_fadeout").val(),
				"timer_time": $("#timer_time").val(),
				"oid": tmp_overlay.oid,
				"reward": tmp_overlay.reward,
				"status": tmp_overlay.status
			});

			var init_test = function(){
				if(app.request == "overlay"){
					$(".modal").modal("close");
					app.overlay.exec(tmp_overlay);
				} else {
					sessionStorage.tmp_overlay_exec = JSON.stringify(tmp_overlay);

					if(typeof test_window != "undefined") test_window.close();

					test_window = window.open($(".btnOverlay").attr("href"), "OverlayTest");

					sessionStorage.removeItem("tmp_overlay_exec");
				}
			};

			init_test();
		});

		$(document.body).off('click', ".btnEditarOverlay").on('click', ".btnEditarOverlay", function() {
			var who = $(this);
			var tmp_data = JSON.parse(who.attr("data-overlay"));
			tmp_data.config = JSON.parse(tmp_data.config);
			tmp_data.config.oid = tmp_data.oid;
			tmp_data = tmp_data.config;
			$('#overlaysModal .tabs').tabs('select', "form_overlays");
			$("a[href='#form_overlays']").html("<i class='material-icons'>mode_edit</i>");

			$("#oid").val(tmp_data.oid);
			$("#obs_change").prop("checked", tmp_data.obs_change).attr("data-value", tmp_data.obs_change);
			$("#obs_scene").val(tmp_data.obs_scene).attr("data-value", tmp_data.obs_scene);
			$("#obs_stay").prop("checked", tmp_data.obs_stay).attr("data-value", tmp_data.obs_stay);
			$("#body_alert").val(tmp_data.body);
			$("#speech_alert").prop("checked", tmp_data.speech);
			$("#tochat").prop("checked", tmp_data.tochat);
			$("#img_url").val(tmp_data.img_url);
			$("#type_img").val(tmp_data.type_img);
			$("#shape_img").val(tmp_data.shape_img);
			$("#audio_url").val(tmp_data.audio_url);
			$("#audio_volumen").val(tmp_data.audio_volumen);
			$("#video_url").val(tmp_data.video_url);
			$("#font_alert").val(tmp_data.font);
			$("#font_color").val(tmp_data.font_color);
			$("#glow").prop("checked", tmp_data.glow);
			$("#glow_light").val(tmp_data.glow_light);
			$("#glow_hard").val(tmp_data.glow_hard);
			$("#confetti").prop("checked", tmp_data.confetti);
			$("#conffeti_time").val(tmp_data.conffeti_time);
			$("#conffeti_min").val(tmp_data.conffeti_min);
			$("#conffeti_max").val(tmp_data.conffeti_max);
			$("#alert_position").val(tmp_data.alert_position);
			$("#alert_fadein").val(tmp_data.alert_fadein);
			$("#alert_fadeout").val(tmp_data.alert_fadeout);
			$("#timer_time").val(tmp_data.timer_time);
			$("#event_trigger").val(tmp_data.reward);
			$("#alerts_status").prop("checked", tmp_data.status);

			M.updateTextFields();
			$('#overlaysModal select').formSelect().trigger("change");
		});

		$(document.body).off('click', ".btnBorrarOverlay").on('click', ".btnBorrarOverlay", function() {
			var who = $(this);
			var init_del_overlay = function(){
				$.ajax({
					url: '/api/deleteOverlay',
					data: {
						oid: who.attr("data-id")
					},
					type: 'POST',
					success: function(data){
						data = typeof data != "object" ? JSON.parse(data) : data;
						if(data.success){
							app.overlays = data.overlays;
							app.ui_vue.overlays.overlays = app.overlays;
							$('#overlaysModal .tabs').tabs('select', "overlays");
						} else {
							app.logs(data);
							if(typeof data.error != "undefined") app.alert(data.error, true);
						}
					}
				});
			};

			app.confirm(app.lang.btnCreateOverlay_question_delete, init_del_overlay);
		});

		$(document.body).off('change', "#img_logo").on('change', "#img_logo", function() {
			var file = this.files[0];
			var imagefile = file.type;
			var match = ["image/jpeg","image/png","image/jpg","image/gif"];
			if(!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]) || (imagefile == match[3]))){
				app.alert();
				return false;
			} else {
				var init_image_load = function(e){
					if($("#img_logo").prop('files').length > 0){
						$("#img_logo").prop("disabled", true);
						var formdata = new FormData();
						file = $("#img_logo").prop('files')[0];
						formdata.append("file", file);
						$.ajax({
							url: '/api/updloadImage?type=custom',
							type: "POST",
							data: formdata,
							processData: false,
							contentType: false,
							success: function (data){
								data = typeof data != "object" ? JSON.parse(data) : data;
								if(data.success){
									app.images = data.images;
									app.ui_vue.images.images = app.images;
								} else {
									app.logs(data);
									app.alert(app.lang.img_logo_cant_upload, true);
								}
								if(!app.ismobile) setTimeout(function(){$("#imagesModal .modal-content").getNiceScroll().resize();}, 300);
								$("#img_logo").prop("disabled", false);
							},
							error: function(){
								$("#img_logo").prop("disabled", false);
							}
						});
					}
				};
				var reader = new FileReader();
				reader.onload = init_image_load;
				reader.readAsDataURL(file);
			}
		});

		$(document.body).off("click", ".btnBorrarImagen").on("click", ".btnBorrarImagen", function(){
			var tmp_name = $(this).attr("data-name");
			var tmp_type = $(this).attr("data-type");
			var init_borrar = function(){
				$.ajax({
					url: '/',
					type: 'POST',
					data: {
						m: 'delImage',
						name: tmp_name
					},
					success: function(data){
						data = typeof data != "object" ? JSON.parse(data) : data;
						if(data.success){
							app.images = data.images;
							app.ui_vue.images.images = app.images;
						} else {
							app.logs(data);
							app.alert(app.lang.img_logo_cant_delete, true);
						}
						if(!app.ismobile) setTimeout(function(){$("#imagesModal .modal-content").getNiceScroll().resize();}, 300);
					}
				});
			};

			app.confirm(app.lang.img_logo_question_delete + " <br><b>" + tmp_name + "</b>", init_borrar);
		});

		$(document.body).off("dblclick", "#imagesModal img.logos").on("dblclick", "#imagesModal img.logos", function(){
			var tmp_route = $(this).attr("data-route") + $(this).attr("data-name");
			if(typeof $("#imagesModal").attr("data-parent") != "undefined"){
				$("#" + $("#imagesModal").attr("data-parent")).val(tmp_route);

				$("#imagesModal").removeAttr('data-parent');
				$("#imagesModal img.logos").css("cursor", "none");
				$("#imagesModal").modal("close");
			} else {
				var copyText = document.createElement("input");
				copyText.setAttribute('type', 'text');
				copyText.setAttribute('value', tmp_route);
				document.body.appendChild(copyText);
				copyText.select();
				copyText.setSelectionRange(0, 99999);
				document.execCommand("copy");
				app.alert(app.lang.copy_clipboard + copyText.value);

				document.body.removeChild(copyText);
			}
		});

		$(document.body).off("dblclick", ".auto_img_complete").on("dblclick", ".auto_img_complete", function(){
			var tmp_parent = $(this).attr("id");

			$("#imagesModal img.logos").css("cursor", "pointer");
			$("#imagesModal").attr("data-parent", tmp_parent).modal("open");
		});

		$(document.body).off('change', ".file_to_upload").on('change', ".file_to_upload", function() {
			var who = $(this);
			var type = who.attr('data-type');
			var file = who[0].files[0];
			if(typeof file != "undefined"){
				who.prop("disabled", true);
				var ufile_type = file.type;
				var match = ["image/jpeg","image/png","image/jpg","application/pdf","application/x-zip-compressed","audio/mpeg","audio/wav","video/mp4","audio/wav"];
				var goodtogo = false;
				$.each(match, function(index, el) {
					if(ufile_type == el){
						goodtogo = true;
						return false;
					}
				});
				if(!goodtogo){
					app.alert(app.lang.file_logo_legend);
					return false;
				} else {
					var init_file_upload = function(e){
						if(who.prop('files').length > 0){
							var formdata = new FormData();
							file = who.prop('files')[0];
							formdata.append("file", file);
							$.ajax({
								url: '/api/uploadFiles?type=' + type,
								type: "POST",
								data: formdata,
								processData: false,
								contentType: false,
								success: function (data){
									data = typeof data != "object" ? JSON.parse(data) : data;
									if(data.success){
										if(type == "media"){
											app.media = data.media;
											app.ui_vue.media.media = app.media;
										} else {
											app.files = data.files;
											app.ui_vue.files.files = app.files;
										}
									} else {
										app.logs(data);
										app.alert(app.lang.file_logo_cant_upload, true);
									}
									if(!app.ismobile) setTimeout(function(){$(".modal-content", who.parent().parent().parent().parent().parent().parent()).getNiceScroll().resize();}, 300);
									who.prop("disabled", false);
								},
								error: function(){
									who.prop("disabled", false);
								}
							});
						}
					};
					var reader = new FileReader();
					reader.onload = init_file_upload;
					reader.readAsDataURL(file);
				}
			}
		});

		$("label", $(".auto_img_complete").parent()).off("click").on("click", function(){
			var tmp_parent = $(this).attr("id");

			$("#imagesModal img.logos").css("cursor", "pointer");
			$("#imagesModal").attr("data-parent", tmp_parent).modal("open");
		}).addClass('cursor');

		$(document.body).off("click", ".btnBorrarArchivo").on("click", ".btnBorrarArchivo", function(){
			var who = $(this);
			var tmp_name = who.attr("data-name");
			var tmp_type = who.attr("data-ftype");
			var init_borrar = function(){
				$.ajax({
					url: '/api/delFile',
					type: 'POST',
					data: {
						name: tmp_name,
						type: tmp_type
					},
					success: function(data){
						data = typeof data != "object" ? JSON.parse(data) : data;
						if(data.success){
							if(tmp_type == "media"){
								app.media = data.media;
								app.ui_vue.media.media = app.media;
							} else {
								app.files = data.files;
								app.ui_vue.files.files = app.files;
							}
						} else {
							app.logs(data);
							app.alert(app.lang.file_logo_cant_delete, true);
						}
						if(!app.ismobile) setTimeout(function(){$(".modal-content", who.parent().parent().parent().parent()).getNiceScroll().resize();}, 300);						}
				});
			};

			app.confirm(app.lang.file_logo_question_delete + "<br><b>" + tmp_name + "</b>", init_borrar);
		});

		$(document.body).off("dblclick", "img.logos.files").on("dblclick", "img.logos.files", function(){
			var who = $(this);
			var tmp_target = who.attr("data-target");
			var tmp_tkn = who.attr("data-tkn");
			var tmp_route = who.attr("data-name");
			var valueCopy = tmp_target == "mediaModal" ? "/media/" + tmp_tkn : tmp_route;
			if(typeof $("#" + tmp_target).attr("data-parent") != "undefined"){
				$("#" + $("#" + tmp_target).attr("data-parent")).val(valueCopy);

				$("#" + tmp_target).removeAttr('data-parent');
				$("#"  + tmp_target + " img.logos").css("cursor", "none");
				$("#" + tmp_target).modal("close");
			} else {
				var copyText = document.createElement("input");
				copyText.setAttribute('type', 'text');
				copyText.setAttribute('value', valueCopy);
				document.body.appendChild(copyText);
				copyText.select();
				copyText.setSelectionRange(0, 99999);
				document.execCommand("copy");
				app.alert(app.lang.copy_clipboard + valueCopy);

				document.body.removeChild(copyText);
			}
		});

		$(document.body).off("dblclick", ".auto_files_complete").on("dblclick", ".auto_files_complete", function(){
			var tmp_parent = $(this).attr("id");

			$("#filesModal img.logos").css("cursor", "pointer");
			$("#filesModal").attr("data-parent", tmp_parent).modal("open");
		});

		$("label", $(".auto_files_complete").parent()).off("click").on("click", function(){
			var tmp_parent = $(this).attr("id");

			$("#filesModal img.logos").css("cursor", "pointer");
			$("#filesModal").attr("data-parent", tmp_parent).modal("open");
		}).addClass('cursor');

		$(document.body).off("dblclick", ".auto_media_complete").on("dblclick", ".auto_media_complete", function(){
			var tmp_parent = $(this).attr("id");

			$("#mediaModal img.logos").css("cursor", "pointer");
			$("#mediaModal").attr("data-parent", tmp_parent).modal("open");
		});

		$("label", $(".auto_media_complete").parent()).off("click").on("click", function(){
			var tmp_parent = $(this).attr("id");

			$("#mediaModal img.logos").css("cursor", "pointer");
			$("#mediaModal").attr("data-parent", tmp_parent).modal("open");
		}).addClass('cursor');

		$(document.body).off("click", ".refreshFiles").on("click", ".refreshFiles", function(){
			var tmp_type = $("input[type=file]", $(this).parent().parent().parent().parent()).attr("data-type");
			$.ajax({
				url: '/api/refreshFiles',
				type: 'POST',
				data: {
					type: tmp_type
				},
				success: function(data){
					data = typeof data != "object" ? JSON.parse(data) : data;
					if(data.success){
						if(tmp_type == "pdownload"){
							app.files = data.files;
							app.ui_vue.files.files = app.files;
						} else if(tmp_type == "custom"){
							app.images = data.images;
							app.ui_vue.images.images = app.images;
						} else {
							app.media = data.media;
							app.ui_vue.media.media = app.media;
						}
					} else {
						app.logs(data);
						if(typeof data.error != "undefined") app.alert(data.error, true);
					}
				}
			});
		});

		$(".buscar_registros").on('keypress', function(e) {
			var tmp_tag = typeof $(this).attr("data-tag") != "undefined" ? $(this).attr("data-tag") : "tr";
			var tmp_target = $(this).attr("data-target");
			var tmp_query = $(this).val();

			if(tmp_query === ""){
				$(tmp_tag, tmp_target).removeClass('hide');
			}

			if(e.which == 13) {
				if(tmp_query !== ""){
					$(tmp_tag, tmp_target).addClass('hide');

					$(tmp_tag, tmp_target).each(function(index, el) {
						if($(this).attr("data-search").toLowerCase().indexOf(tmp_query.toLowerCase()) > -1){
							$(this).removeClass('hide');
						}
					});
				}
			}
		});

		$('input, textarea').blur(function(event) {
			event.target.checkValidity();
		}).bind('invalid', function(event) {
			setTimeout(function() { $(event.target).focus();}, 50);
		});
		//puglins
		$("nav li a").removeClass("selected");
		$("nav li a[href='/" + app.request + "']").addClass("selected");

		$('.tooltip', $(document.body)).tooltip({
			"html":true
		});

		$("header .dropdown-trigger").dropdown({
			hover:false,
			closeOnClick: true
		});

		$("#dropdown_cats, #dropdown_menu, #dropdown_menu_admin, #dropdown_tienda_admin").appendTo("header");

		$(".dropdown-trigger.admin").dropdown({
			hover:true,
			closeOnClick: true
		});

		$('.collapsible').collapsible({
			onOpenEnd: function(){
				setTimeout(function(){
					if(!app.ismobile) $("body, .modal .modal-content, .scroller").getNiceScroll().resize();
				}, 500);
			}
		});

		$('select').formSelect();

		$('.sidenav').sidenav({
			edge: app.sidenavpos
		});

		$('.materialboxed').materialbox();

		$('.parallax').parallax();

		$('table thead td').addClass('cursor').each(function(){
			var th = $(this),
				thIndex = th.index(),
				inverse = false;

			th.off('click').on('click', function(){
				var tmp_parent = $(this).parent().parent().parent();
				$('tbody', tmp_parent).find('td').filter(function(){
					return $(this).index() === thIndex;
				}).sortElements(function(a, b){
					if($.text([a]) == $.text([b])){
						return 0;
					}
					if(Number($.text([a]))){
						if($.text([a]) - $.text([b]) < 0){
							return inverse ? -1 : 1;
						}
						if($.text([a]) - $.text([b]) > 0){
							return inverse ? 1 : -1;
						}
					} else {
						if($.text([a]).toLowerCase() < $.text([b]).toLowerCase() ){
							return inverse ? -1 : 1;
						}
						if($.text([a]).toLowerCase() > $.text([b]).toLowerCase() ){
							return inverse ? 1 : -1;
						}
					}
				}, function(){
					// parentNode is the element we want to move
					return this.parentNode;
				});
				inverse = !inverse;
			});
		});

		app.logs("init main jq listeners!");
		if(typeof cb == "function") cb();
	};

	var _isMobile = function(cb){
		app.ismobile = (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || (navigator.userAgent).indexOf("Mobile") > -1) ? true : false;

		if(!app.ismobile) {
			$("body").addClass('off_overflow');
			$("body, .modal-content, .scroller").getNiceScroll().resize();
		} else {
			$("body").removeClass('off_overflow');
		}

		app.logs("_isMobile!");
		if(typeof cb == "function") cb();
	};

	var _init_vue = function(cb){
		app.store = new Vuex.Store({
			state: {
				count: 0
			},
			mutations: {
				increment: function(state){
					state.count++;
				}
			}
		});

		app.logs("init vuex store!");

		app.ui_vue.overlays = new Vue({
			el: '#overlaysContent',
			data: {
				overlays: app.overlays
			},
			methods: {
				getVal: function(obj, param){
					var value = JSON.parse(obj);
					return value[param];
				},
				formatDate: function(value){
					return new Date(parseInt(value, 10) * 1000);
				}
			}
		});

		app.ui_vue.rewards = new Vue({
			el: '#rewardsContent',
			data: {
				rewards: app.rewards
			},
			methods: {
				formatDate: function(value){
					return new Date(value);
				}
			}
		});

		app.ui_vue.obs = new Vue({
			el: '#scenesContent',
			data: {
				scenes: app.scenes
			},
			methods: {
				formatDate: function(value){
					return new Date(value);
				}
			}
		});

		app.ui_vue.images = new Vue({
			el: '#imagesContent',
			data: {
				images: app.images
			}
		});

		app.ui_vue.files = new Vue({
			el: '#filesContent',
			data: {
				files: app.files
			}
		});

		app.ui_vue.media = new Vue({
			el: '#mediaContent',
			data: {
				media: app.media
			}
		});

		app.ui_vue.users = new Vue({
			el: '#usersContent',
			data: {
				users: app.users
			},
			methods: {
				formatDate: function(value){
					return new Date(parseInt(value, 10) * 1000);
				}
			}
		});

		app.logs("init main vue!");
		if(typeof cb == "function") cb();
	};

	var _alert = function(msg, error){
		if(typeof error != "undefined"){
			$('#confirmModal').addClass('bottom-sheet').addClass('full_width');
			$("#confirmModal .titulo").html(msg);
			$("#confirmModal .btnAccept").addClass("hide");

			$("#confirmModal .btnCancel").off("click").on("click", function(){
				$('#confirmModal').removeClass('bottom-sheet').removeClass('full_width');
				$('#confirmModal').modal("close");

				return true;
			});

			$('#confirmModal').modal("open");
		} else {
			M.toast({html:msg});
		}
	};

	var _confirm = function(msg, success, error){
		$("#confirmModal .titulo").html(msg);

		if(typeof success == "function") {
			$("#confirmModal .btnAccept").off("click").on("click", function(){
				$('#confirmModal').modal("close");
				success();
			}).removeClass("hide");
		} else {
			$("#confirmModal .btnAccept").addClass("hide");
		}

		$("#confirmModal .btnCancel").off("click").on("click", function(){
			$('#confirmModal').modal("close");
			if(typeof error == "function") error();
		});

		$('#confirmModal').modal("open");

		return false;
	};

	var _log = function(msg){
		if(app.debug) console.log(msg);

		if(typeof sessionStorage.app_logs == "undefined"){
			sessionStorage.app_logs = JSON.stringify([]);
		}

		var tmp_store = JSON.parse(sessionStorage.app_logs);
		tmp_store.push({"log": msg, "date": + new Date(), "page": app.request});
		sessionStorage.app_logs = JSON.stringify(tmp_store);
	};

	var _getImages = function(cb){
		$.ajax({
			url: '/api/getImages',
			type: 'POST',
			success: function(data){
				data = typeof data != "object" ? JSON.parse(data) : data;
				if(data.success){
					app.images = data.images;
					app.ui_vue.images.images = app.images;
					if(typeof cb == "function") cb();
				} else {
					app.logs(data);
					if(typeof data.error != "undefined") app.alert(data.error, true);
				}
			}
		});
	};

	var _getFiles = function(cb){
		$.ajax({
			url: '/api/getFiles',
			type: 'POST',
			success: function(data){
				data = typeof data != "object" ? JSON.parse(data) : data;
				if(data.success){
					app.files = data.files;
					app.ui_vue.files.files = app.files;
					if(typeof cb == "function") cb();
				} else {
					app.logs(data);
					if(typeof data.error != "undefined") app.alert(data.error, true);
				}
			}
		});
	};

	var _getMedia = function(cb){
		$.ajax({
			url: '/api/getMedia',
			type: 'POST',
			success: function(data){
				data = typeof data != "object" ? JSON.parse(data) : data;
				if(data.success){
					app.media = data.media;
					app.ui_vue.media.media = app.media;
					if(typeof cb == "function") cb();
				} else {
					app.logs(data);
					if(typeof data.error != "undefined") app.alert(data.error, true);
				}
			}
		});
	};

	var _getRewards = function(cb){
		$.ajax({
			url: '/api/getRewards',
			type: 'POST',
			success: function(data){
				data = typeof data != "object" ? JSON.parse(data) : data;
				if(data.success){
					app.rewards = data.rewards;
					app.ui_vue.rewards.rewards = app.rewards;
					if(typeof cb == "function") cb();
				} else {
					app.logs(data);
					if(typeof data.error != "undefined") app.alert(data.error, true);
				}
			}
		});
	};

	var _getOverlays = function(cb){
		$.ajax({
			url: '/api/getOverlays',
			traditional: true,
			data: {
				param: JSON.stringify({
					uid: app.user.id
				})
			},
			type: 'POST',
			success: function(data){
				data = typeof data != "object" ? JSON.parse(data) : data;
				if(data.success){
					app.overlays = data.overlays;
					app.ui_vue.overlays.overlays = app.overlays;
					if(typeof cb == "function") cb();
				} else {
					app.logs(data);
					if(typeof data.error != "undefined") app.alert(data.error, true);
				}
			}
		});
	};

	var _getUsers = function(cb){
		$.ajax({
			url: '/api/getUsers',
			traditional: true,
			data:{
				param: JSON.stringify({})
			},
			type: 'POST',
			success: function(data){
				data = typeof data != "object" ? JSON.parse(data) : data;
				if(data.success){
					app.users = data.users;
					app.ui_vue.users.users = app.users;
					if(typeof cb == "function") cb();
				} else {
					app.logs(data);
					if(typeof data.error != "undefined") app.alert(data.error, true);
				}
			}
		});
	};

	var _getFollows = function(cb){
		$.ajax({
			url: '/api/getFollows',
			type: 'POST',
			success: function(data){
				data = typeof data != "object" ? JSON.parse(data) : data;
				if(data.success){
					app.follows = data;
					if(typeof cb == "function") cb();
				} else {
					app.logs(data);
					if(typeof data.error != "undefined") app.alert(data.error, true);
				}
			}
		});
	};

	root.debug = _debug;
	root.downloading = false;
	root.init = _init;
	root.alert = _alert;
	root.confirm = _confirm;
	root.getOverlays = _getOverlays;
	root.getFollows = _getFollows;
	root.logs = _log;
	root.ui_vue = {};
	root.overlays = {};
	root.scenes = {};
	root.follows = {};

	return root;
})();

$(document).ready(function(){
	app.init();
});
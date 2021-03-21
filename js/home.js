app.home = (function(){
	var root = {};
	var channels = [];

	var _vue = function(){
		app.ui_vue.titulo = new Vue({
			el: '#titulo',
			store: app.store,
			data: {
				titulo: app.lang.welcome_to + app.home.welcome
			},
			mounted: function () {
				this.$nextTick(function () {
					$("#titulo").addClass('magictime').removeClass('hide');
				});
			}
		});

		app.logs("home init vue!");
	};

	var _jquery = function(){
		if(app.animeon) $("img.brand-logo").addClass('spin_img');
		$("#titulo p").removeClass('hide');

		$('.btnNewChannel').off('click').on('click', function(){
			var tmp_channel = $("#new_channel").val();

			tmp_channel = tmp_channel === "" ? app.user.user : tmp_channel;

			if(tmp_channel.indexOf(" ") > -1){
				tmp_channel = tmp_channel.split(" ");
				tmp_channel = tmp_channel.filter(Boolean);
			} else {
				tmp_channel = [tmp_channel];
			}

			channels = tmp_channel;

			$(".video iframe:not(.clone)").each(function(index, el) {
				if($.inArray($(this).attr("data-channel"), tmp_channel) < 0){
					$(this)
					.removeClass('tinDownIn').addClass('tinDownOut')
					.attr("src", "about:blank")
					.remove();

					$("iframe[data-channel='" + $(this).attr("data-channel") + "']")
					.attr("src", "about:blank")
					.remove();
				}
			});

			$("iframe.chats:not(.clone)").addClass('hide');

			$.each(tmp_channel, function(index, tmp_channel) {
				if($("#iframeVideo" + index + "").length === 0) {
					if($("iframe[data-channel='" + tmp_channel + "']").length > 0){
						$("iframe[data-channel='" + tmp_channel + "']")
						.attr("src", "about:blank")
						.remove();
					}
					var clone_iframe = $("#iframeVideo").clone();
					// video iframe
					clone_iframe.attr("id", "iframeVideo" + index)
					.attr("src", "https://player.twitch.tv/?channel=" + tmp_channel + "&parent=" + window.location.host)
					.attr("data-channel", tmp_channel)
					.removeClass('hide').removeClass('clone');

					if(index === 0){
						$(".video").prepend(clone_iframe);
					} else {
						$(".video > iframe:not(.clone):nth-child(" + (index) + ")").after(clone_iframe);
					}

					// chat iframe
					clone_iframe = $("#iframeChat").clone();
					clone_iframe.attr("id", "iframeChat" + index)
					.attr("data-channel", tmp_channel)
					.removeClass('clone');

					if($('#chat_switch').prop('checked')){
						clone_iframe.attr("id", "iframeChat" + index)
						.attr("src", "https://www.twitch.tv/embed/" + tmp_channel + "/chat?parent=" + window.location.host);
					}

					$(".chat").append(clone_iframe);
				} else {
					if($("#iframeVideo" + index).attr("data-channel") != tmp_channel){
						$("#iframeVideo" + index)
						.attr("src", "https://player.twitch.tv/?channel=" + tmp_channel + "&parent=" + window.location.host)
						.attr("data-channel", tmp_channel);

						$("#iframeChat" + index)
						.attr("src", "https://www.twitch.tv/embed/" + tmp_channel + "/chat?parent=" + window.location.host)
						.attr("data-channel", tmp_channel);
					}
				}

				if(index === 0) {
					$("#iframeChat" + index).removeClass('hide');
				}
			});

			var width_frames = Math.ceil((100 * (tmp_channel.length / $("#chat_grid_x").val())) / tmp_channel.length) - 1;
			var height_frames = Math.ceil((800 * (tmp_channel.length / $("#chat_grid_y").val())) / tmp_channel.length);
			$(".video iframe:not(.clone)")
			.attr("width", width_frames + "%")
			.attr("height", height_frames + "px");

			$(window).resize();

			_rememberConfig();

			app.alert("<h4>⌛</h4>");
		});

		$(document.body).off('mouseenter', '.video iframe:not(.clone)').on('mouseenter', '.video iframe:not(.clone)', function(){
			var tmp_channel = $(this).attr("data-channel");

			if(app.ctrl_state){
				$("iframe.chats:not(.clone)").addClass('hide');
				$("iframe.chats[data-channel='" + tmp_channel + "']").removeClass('hide');
			}
		});

		$(document.body).on('keypress', "#new_channel", function(e) {
			if(e.which == 13) {
				$('.btnNewChannel').trigger('click');
			}
		});

		$('#chat_remember').off('change').on('change', function(){
			if($(this).prop("checked")){
				_rememberConfig();
			} else{
				localStorage.removeItem("config");
			}
		});

		$("#chat_grid_x, #chat_grid_y").on('change', function(e) {
			$('.btnNewChannel').trigger('click');
		});

		$('#chat_video_switch').off('change').on('change', function(){
			var width_frames = Math.ceil((100 * (channels.length / $("#chat_grid_x").val())) / channels.length) - 1;

			if($(this).prop("checked")){
				$("#iframeChat").parent()
				.addClass('l3 m3');
				$("#iframeVideo").parent()
				.removeClass('hide')
				.addClass('l9 m9');
				$(".video iframe:not(.clone)").each(function(index, el) {
					$(this).attr("src", "https://player.twitch.tv/?channel=" + $(this).attr("data-channel") + "&parent=" + window.location.host);
				});
				$("iframe.chats:not(.clone)")
				.attr("width", "100%")
				.addClass('hide');

				$("iframe.chats[data-channel='" + channels[0] + "']").removeClass('hide');
			} else{
				$("#iframeVideo").parent()
				.removeClass('l9 m9')
				.addClass('hide');
				$("#iframeChat").parent()
				.removeClass('l3 m3');
				$(".video iframe:not(.clone)").each(function(index, el) {
					$(this).attr("src", "about:blank");
				});

				$(".chat iframe:not(.clone)")
				.attr("width", width_frames + "%");

				$("iframe.chats:not(.clone)").removeClass('hide');
			}
			_rememberConfig();
		});

		$('#chat_switch').off('change').on('change', function(){
			var width_frames = Math.ceil((100 * (channels.length / $("#chat_grid_x").val())) / channels.length) - 1;

			if($(this).prop("checked")){
				$(".chat iframe:not(.clone)").each(function(index, el) {
					$(this).attr("src", "https://www.twitch.tv/embed/" + $(this).attr("data-channel") + "/chat?parent=" + window.location.host);
				});

				if($('#chat_video_switch').prop("checked")){
					$(".chat iframe:not(.clone)")
					.attr("width", "100%");

					$("#iframeVideo").parent()
					.addClass('l9 m9');
					$("#iframeChat").parent()
					.removeClass('hide')
					.addClass('l3 m3');
				} else {
					$(".chat iframe:not(.clone)")
					.removeClass('hide')
					.attr("width", width_frames + "%");
					$("#iframeChat").parent()
					.removeClass('hide');
				}
			} else{
				$("#iframeChat").parent()
				.removeClass('l3 m3')
				.addClass('hide');
				$("#iframeVideo").parent()
				.removeClass('l9 m9');
				$(".chat iframe:not(.clone)").each(function(index, el) {
					$(this).attr("src", "about:blank");
				});
			}
			_rememberConfig();
		});

		if(!app.ismobile){
			if($("#landingVideo").length > 0) $("#landingVideo")[0].play();
			if($("#aboutVideo").length > 0) {
				$("#aboutVideo")[0].volume = 0.1;
				$("#aboutVideo")[0].play();
			}
		}

		if(typeof localStorage.config != "undefined"){
			var tmp_config = JSON.parse(localStorage.config);

			$('#new_channel').val(tmp_config.channels);
			$('#chat_grid_x').val(tmp_config.grid_x);
			$('#chat_grid_y').val(tmp_config.grid_y);
			$('#chat_video_switch').prop("checked", tmp_config.video);
			$('#chat_switch').prop("checked", tmp_config.chat);

			M.updateTextFields();
			$("#chat_grid_x, #chat_grid_y").formSelect().trigger("change");
			setTimeout(function(){
				$('#chat_video_switch, #chat_switch').trigger("change");
			}, 300);

			$('#chat_remember').prop("checked", true);
		}

		$('.btnNewChannel').trigger('click');

		if(typeof app.user != "undefined"){
			app.getFollows(function(){
				$('.autoCompleteFollows').autocomplete({data: app.follows.autocomplete});
			});
		}

		app.logs("home init jquery!");
	};

	var _init = function(){
		if(typeof app.user != "undefined"){
			app.home.welcome = app.site + " " + app.user.user;
		} else {
			app.home.welcome = app.site;
		}
		_vue();
		_jquery();

		if(app.debug) app.alert("home load!");
	};

	var _rememberConfig = function(){
		var tmp_config = {
			'channels': sntzr($('#new_channel').val()),
			'grid_x': $('#chat_grid_x').val(),
			'grid_y': $('#chat_grid_y').val(),
			'video': Number($('#chat_video_switch').prop("checked")),
			'chat': Number($('#chat_switch').prop("checked"))
		};

		localStorage.config = JSON.stringify(tmp_config);
	};

	root.init = _init;

	return root;
})();

$(document).ready(function(){
	app.home.init();
});
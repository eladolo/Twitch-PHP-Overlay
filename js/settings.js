app.settings = (function(){
	var root = {};

	var _vue = function(){
		app.ui_vue.titulo = new Vue({
			el: '#titulo',
			store: app.store,
			data: {
				titulo: 'Define config'
			},
			mounted: function () {
				this.$nextTick(function () {
					$("#titulo").addClass('magictime').removeClass('hide');
				});
			}
		});

		app.logs("settings init vue!");
	};

	var _jquery = function(){
		var picker = new CP(document.querySelector('input[id="LFColor"]'));
		picker.on("change", function(color) {
			this.target.value = '#' + color;
		});

		var picker2 = new CP(document.querySelector('input[id="LandingColor"]'));
		picker2.on("change", function(color) {
			this.target.value = '#' + color;
		});

		var picker3 = new CP(document.querySelector('input[id="AboutColor"]'));
		picker3.on("change", function(color) {
			this.target.value = '#' + color;
		});

		var picker5 = new CP(document.querySelector('input[id="LFFontColor"]'));
		picker5.on("change", function(color) {
			this.target.value = '#' + color;
		});

		var picker6 = new CP(document.querySelector('input[id="LFBKColor"]'));
		picker6.on("change", function(color) {
			this.target.value = '#' + color;
		});

		$(".btnResetDb").off('click').on('click', function(){
			var init_gen = function(){
				if($("#DB_DRIVER").val() === "" || $("#DB_host").val() === "" || $("#DB_name").val() === "" || $("#DB_user").val() === "" || $("#DB_password").val() === ""){
					app.alert(app.lang.btnResetDb_empty_fields);
					return false;
				}
				var tmp_data = {
					m: 'genDB',
					driver: $("#DB_DRIVER").val()
				};

				$.ajax({
					url: '/',
					type: 'POST',
					data: tmp_data,
					success: function(data){
						data = typeof data != "object" ? JSON.parse(data) : data;
						if(data.success){
							window.location.href = "/settings";
						} else {
							app.logs(data);
							if(typeof data.query != "undefined") app.alert(data.query, true);
						}
					}
				});
			};

			app.confirm(app.lang.btnResetDb_question, init_gen);
		});

		app.logs("settings init jquery!");
	};

	var _init = function(){
		_vue();
		_jquery();

		if(app.debug) app.alert("settings load!");
	};

	root.init = _init;

	return root;
})();

$(document).ready(function(){
	app.settings.init();
});
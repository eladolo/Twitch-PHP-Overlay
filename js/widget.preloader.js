app.widgetpreloader = (function(){
	var root = {};

	var _vue = function(){
		app.ui_vue.preloader = new Vue({
			el: '.preloader',
			store: app.store,
			data: {
				titulo: app.lang.welcome_to + app.site
			},
			mounted: function () {
				this.$nextTick(function () {
					$(".preloader").addClass('magictime').removeClass('hide');
				});
			}
		});

		app.logs("init widgetpreloader vue!");
	};

	var _jquery = function(){
		app.logs("init widgetpreloader jquery!");
	};

	var _init = function(){
		_vue();
		_jquery();

		if(app.debug) app.alert("widgetpreloader load!");
	};

	root.init = _init;

	return root;
})();

$(document).ready(function(){
	app.widgetpreloader.init();
});
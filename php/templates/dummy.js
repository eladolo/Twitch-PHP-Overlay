app.{{dummy}} = (function(){
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

		app.logs("{{dummy}} init vue!");
	};

	var _jquery = function(){
		app.logs("{{dummy}} init jquery!");
	};

	var _init = function(){
		_vue();
		_jquery();

		if(app.debug) app.alert("{{dummy}} load!");
	};

	root.init = _init;

	return root;
})();

$(document).ready(function(){
	$(window).on("load", function(event){
        app.{{dummy}}.init();
    });
});
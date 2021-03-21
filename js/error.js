app.error = (function(){
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

        app.logs("error init vue!");
    };

    var _jquery = function(){
        app.logs("error init jquery!");
    };

    var _init = function(){
        _vue();
        _jquery();

        $(".btnBack").off("click").on("click", function(){
            window.history.back();
        });

        if(app.debug) app.alert("error load!");
    };

    root.init = _init;

    return root;
})();

$(document).ready(function(){
    app.error.init();
});
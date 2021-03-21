app.login = (function(){
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

        app.logs("login init vue!");
    };

    var _jquery = function(){
        app.logs("login init jquery!");
    };

    var _init = function(){
        _vue();
        _jquery();

        $("#remember_switch").off("change").on("change", function(){
            var tmp_url = $("#btnLoginInit").attr("href");

            if($(this).prop("checked")){
                tmp_url = tmp_url.replace("force_verify=true", "force_verify=false");
                localStorage.rememberme = true;
            } else {
                tmp_url = tmp_url.replace("force_verify=false", "force_verify=true");
                localStorage.removeItem('rememberme');
            }

            $("#btnLoginInit").attr("href", tmp_url);
        });

        if(typeof localStorage.rememberme != "undefined"){
            $("#remember_switch").trigger("click");
        }

        if(app.debug) app.alert("login load!");
    };

    root.init = _init;

    return root;
})();

$(document).ready(function(){
    app.login.init();
});
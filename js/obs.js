app.obs = (function(){
    var root = {};

    var obsinterval;
    var lookupinterval;
    var looking_times = 0;
    var internal_click = false;

    var _getScenes = function(cb){
        socket.getSceneList().then(function(data){
            app.scenes = data.scenes;
            app.ui_vue.obs.scenes = app.scenes;

            if(typeof cb == "function") cb();
        });
    };

    var _setScene = function(scene, cb){
        socket.setCurrentScene({
            'scene-name': scene
        });

        if(typeof cb == "function") cb();
    };

    var _setCurrent = function(){
        socket.GetCurrentScene().then(function(data){
            root.current_scene = data.name;
        });
    };

    var _lookup = function(){
        if(app.obs_host === "") return;
        if(app.obs_host.indexOf("wss://") == -1) return;

        try{
            socket = new OBSWebSocket();
            socket.connect({
                address: app.user.obs_host,
                password: app.user.obs_password
            // Promise convention dictates you have a catch on every chain.
            }).catch(function(err){
                if(typeof lookupinterval == "undefined"){
                    lookupinterval = setInterval(function(){
                        looking_times++;
                        if(app.debug) app.logs("looking obs! " + looking_times);
                        if(looking_times >= 60){
                            clearInterval(lookupinterval);
                            lookupinterval = undefined;
                            return;
                        }
                        _lookup();
                    }, 1 * 10 * 1000);
                }
            });

            socket.onConnectionOpened(function(){
                _init();
            });

            socket.onAuthenticationSuccess(function(){
                app.obs.getScenes(function(){
                    _getScenes(function(){
                        M.updateTextFields();
                        $('select', "#overlaysModal").formSelect().trigger("change");
                    });
                    $(".obs_settings").removeClass('hide');
                    app.obs.setCurrent();
                });
            });

            socket.onConnectionClosed(function(){
                root.isConnected = false;
                $(".obs_settings").addClass('hide');
                if(typeof lookupinterval == "undefined"){
                    lookupinterval = setInterval(function(){
                        looking_times++;
                        if(app.debug) app.logs("looking obs! " + looking_times);
                        if(looking_times >= 60){
                            clearInterval(lookupinterval);
                            lookupinterval = undefined;
                            return;
                        }
                        _lookup();
                    }, 1 * 10 * 1000);
                }
            });

            socket.onSwitchScenes(function(){
                _getScenes();
            });

            socket.onStreamStarted(function(){
                socket.getStreamingStatus().then(function(data){
                    if(data.streaming){
                        root.streaming = true;
                    } else {
                        root.streaming = false;
                    }
                });
            });

            socket.onStreamStopped(function(){
                socket.getStreamingStatus().then(function(data){
                    if(data.streaming){
                        root.streaming = true;
                    } else {
                        root.streaming = false;
                    }
                });
            });

            obsinterval = setInterval(function(){
                clearInterval(obsinterval);
                obsinterval = undefined;
                if(root.isConnected) return;
                if(socket.readyState === 1) socket.disconnect();
            }, 5000);
        } catch (ex) {
           app.alert('OBS error: ' + ex);
        }
    };

    var _init = function(){
        root.isConnected = true;
        clearInterval(obsinterval);
        obsinterval = undefined;
        clearInterval(lookupinterval);
        lookupinterval = undefined;
        root.socket = socket;
    };

    root.socket = '';
    root.current_scene = '';
    root.streaming = false;
    root.isConnected = false;
    root.getScenes = _getScenes;
    root.setCurrent = _setCurrent;
    root.scene = _setScene;
    root.lookup = _lookup;

    return root;
})();

$(document).ready(function($) {
    app.obs.lookup();
});
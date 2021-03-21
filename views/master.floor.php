<?php
	$files = scandir('views');
	foreach($files as $file) {
		if(stripos($file, "modal.") === false) continue;
		include_once("views/" . $file);
	}
	$jss = array(
		"js/libs/modernizr-custom.js",
		"js/libs/jquery-3.4.1.min.js",
		"js/libs/jquery-ui.min.js",
		"js/libs/materialize.js",
		"js/libs/vue-2.6.11.js",
		"js/libs/vuex.js",
		"js/libs/he.js",
		"js/libs/hmac-sha256.js",
		"js/libs/enc-base64.js",
		"js/libs/jwt.js",
		"js/libs/sanitize.js",
		"js/libs/jquery.nicescroll.js",
		"js/libs/jquery.sortElements.js",
		"js/libs/fontawesome.5.0.8.min.js",
		"js/libs/lazysizes.min.js",
		"js/libs/highlight.min.js",
		"js/libs/color-picker.js",
		"js/libs/obs-websocket.min.js",
		"js/libs/confetti.js"
	);
	echo "<script type='text/javascript' id='libs_js'>";
	foreach($jss as $js) {
		echo stripos(".min.", $js) !== false ? $this->private["minify_js"](file_get_contents($js)) : file_get_contents($js);
	}
	echo "</script>";
	echo "<script type='text/javascript' id='master_js'>" . $this->private["minify_js"](file_get_contents("js/master.js")) . "</script>";
	echo "<script type='text/javascript' id='obs_js'>" . $this->private["minify_js"](file_get_contents("js/obs.js")) . "</script>";
	$init_vars = '<script type="text/javascript">';
	if(!empty($redirect_string)){
		$init_vars .= $redirect_string;
	}
	if(isset($_SESSION["login"])){
		$init_vars .= 'window.app.user = ' . json_encode($_SESSION["login"]) . ';';
	}
	$init_vars .= 'window.app.lang = ' . json_encode($lang["views"]). ';window.app.animeon = ' . (ANIME_ON != "" ? "true" : "false") . ';window.app.lflogo = "' . LFLogo . '";window.app.lffont = "' . LFFont . '";window.app.lfcolor = "' . LFColor . '";window.app.sidenavpos = "' . SIDENAVPOS . '";window.app.debug = "' . DEBUG . '";window.app.lffontcolor = "' . LFFontColor . '";window.app.apikey = "' . APIKEY . '";window.app.public_secret = "' . SECRET_PUBLIC . '";window.app.site = "' . SITE . '";window.app.desc = "' . preg_replace( "/\r|\n/", "<br>", DESC) . '";window.app.request = "' . $request . '";window.app.state = ' . json_encode($app_state) . ';window.app.site_id = ' . SITE_ID . ';window.app.site_req = "' . site_req . '";window.app.obs_host = "' . $obs_host . '";window.app.ismobile = ' . (isMobile === "" ? false : true) . ';';
	$init_vars .= '</script>';
	echo $init_vars;
	$files = scandir('js');
	foreach($files as $file) {
		if(stripos($file, "widget.") === false) continue;
		echo "<script type='text/javascript' id='widget_js'>" . $this->private["minify_js"](file_get_contents("js/" . $file )) . "</script>";
	}
	if(file_exists("js/" . $request . ".js")){
		echo "<script type='text/javascript' id='request_js'>" . $this->private["minify_js"](file_get_contents("js/" . $request . ".js")) . "</script>";
	}
	if(file_exists('js/' . $request . '.custom.' . site_req .'.js')){
		echo "<script type='text/javascript' id='custom_js'>" . $this->private["minify_js"](file_get_contents("js/" . $request . '.custom.' . site_req .'.js')) . "</script>";
	}

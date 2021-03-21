<title><?php echo $title; ?></title>
<?php if(isset($_REQUEST["coff"])){ ?>
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Expires" content="-1">
	<meta http-equiv="CACHE-CONTROL" content="NO-CACHE">
<?php } ?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta name="format-detection" content="telephone=no" />
<meta name="msapplication-tap-highlight" content="no" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="<?php echo $description;?>" />
<meta name="viewport" content="user-scalable=yes, initial-scale=1, maximum-scale=5, minimum-scale=1, width=device-width" />
<meta name="keywords" content="<?php echo $keywords; ?>">
<meta name="Author" content="<?php echo $autor; ?>">

<meta name="twitter:card" content="<?php echo $description; ?>" />
<meta name="twitter:site" content="@<?php echo SITE; ?>" />
<meta name="twitter:creator" content="@<?php echo $autor; ?>" />

<meta property="og:url" content="<?php echo "https://" . SITE . "/"; ?>"/>
<meta property="og:type" content="website"/>
<meta property="og:title" content="<?php echo $title; ?>"/>
<meta property="og:description" content="<?php echo $description; ?>"/>
<meta property="og:image" content="<?php echo $imagen; ?>"/>
<link rel="apple-touch-icon" href="<?php echo LFfavicon; ?>">
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo LFfavicon; ?>">
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo LFfavicon; ?>">
<link rel="shortcut icon" type="image/x-icon" href="<?php echo LFfavicon; ?>" />
<link href="//fonts.googleapis.com/css?display=swap&family=Material+Icons|<?php echo $google_fonts; ?>" rel="stylesheet">
<?php
	$css_libs = array(
		"css/font-awesome.min.css",
		"css/jquery-ui.min.css",
		"css/materialize.min.css",
		"css/magic.min.css",
		"css/highlight.min.css",
		"css/monokai-sublime.css",
		"css/color-picker.css"
	);

	$css_buffer = "<style id='libs_css'>";
	$to_min_css = '';
	foreach($css_libs as $css) {
		if(stripos(".min.", $css) !== false){
			$to_min_css .= file_get_contents($css);
		} else {
			$css_buffer .= file_get_contents($css);
		}
	}
	$css_buffer .= $this->private["minify_css"]($to_min_css);
	$css_buffer .= "</style>";

	echo $css_buffer;

	$tmp_master_css = file_get_contents("css/master.css");
	$tmp_master_css = str_replace("{{LFColor}}", LFColor, $tmp_master_css);
	$tmp_master_css = str_replace("{{LFFont}}", LFFont, $tmp_master_css);
	$tmp_master_css = str_replace("{{LFBKColor}}", LFBKColor, $tmp_master_css);
	$tmp_master_css = str_replace("{{LFFontColor}}", LFFontColor, $tmp_master_css);
	$tmp_master_css = str_replace("{{SCROLLER}}", (isMobile ? "auto" : "hidden"), $tmp_master_css);
	echo "<style id='main_css'>" . $this->private["minify_css"]($tmp_master_css). "</style>";

	$files = scandir('css');
	foreach($files as $file) {
		if(stripos($file, "widget.") === false) continue;
		echo "<style id='widget_css'>" . $this->private["minify_css"](file_get_contents("css/" . $file )) . "</style>";
	}

	if(file_exists("css/" . $request . ".css")){
		echo "<style id='request_css'>" . $this->private["minify_css"](file_get_contents("css/" . $request . ".css")) . "</style>";
	}

	if(file_exists('css/' . $request . '.custom.' . site_req .'.css')){
		echo "<style id='custom_css'>" . $this->private["minify_css"](file_get_contents("css/" . $request . '.custom.' . site_req .'.css')) . "</style>";
	}
?>

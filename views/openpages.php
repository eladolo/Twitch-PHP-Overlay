<?php
	$mandatory = array();
	$files = scandir('php/whitelist');
	foreach($files as $file) {
		if(stripos($file, "view.") === false) continue;
		$file = str_replace("view.", "", $file);
	  	array_push($mandatory, $file);
	}
	$open_pages = array();

	foreach ($mandatory as $val) {
		array_push($open_pages, $val);
	}

	if(file_exists("php/install")){
		array_push($open_pages, "settings");
	}

	$files = scandir('views');
	foreach($files as $file) {
		if(stripos($file, "navbar.") === false) continue;
		if($file == "navbar.php") continue;

		$tmp_nm = str_replace("navbar.", "", $file);
		$tmp_nm = str_replace(".not", "", $tmp_nm);
		$tmp_nm = str_replace(".custom", "", $tmp_nm);
		$tmp_nm = str_replace("." . site_req, "", $tmp_nm);

		$tmp_nm = str_replace(".php", "", $tmp_nm);
	  	array_push($open_pages, $tmp_nm);
	}

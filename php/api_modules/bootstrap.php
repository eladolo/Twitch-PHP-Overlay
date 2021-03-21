<?php
	/*
    * bootstrap function
    */
	$bootstrap =  function() use($DB, $Crypto, $lang){
        $app_state = $this->checkAPPState();

		$request = isset($_REQUEST["r"]) && !empty($_REQUEST["r"]) ? $_REQUEST["r"] : "home";

		$file = file_exists("views/view." . $request . ".php") ? "view." . $request . ".php" : '';
		$file = file_exists("views/view." . $request . ".not.php") ? "view." . $request . ".not.php" : $file;
		$file = file_exists("views/view." . $request . ".custom." . site_req . ".php") ? "view." . $request . ".custom." . site_req . ".php" : $file;
		$file = file_exists("views/view." . $request . ".not.custom." . site_req . ".php") ? "view." . $request . ".not.custom." . site_req . ".php" : $file;
		$file = file_exists("views/navbar." . $request . ".php") ? "navbar." . $request . ".php" : $file;
		$file = file_exists("views/navbar." . $request . ".not.php") ? "navbar." . $request . ".not.php" : $file;
		$file = file_exists("views/navbar." . $request . ".custom." . site_req . ".php") ? "navbar." . $request . ".custom." . site_req . ".php" : $file;
		$file_name = file_exists("views/navbar." . $request . ".not.custom." . site_req . ".php") ? "navbar." . $request . ".not.custom." . site_req . ".php" : $file;

 		$blacklist = array();
		$blfiles = scandir('php/blacklist');

		foreach($blfiles as $blfile) {
			if(stripos($blfile, "folder.") === false && stripos($blfile, "view.") === false ) continue;
			$blfile = str_replace("folder.", "", $blfile);
			$blfile = str_replace("view.", "", $blfile);
		  	array_push($blacklist, $blfile);

			if(stripos($request, $blfile) !== false){
				throw new Exception($lang["api"]["bootstrap_access_denied"] . " " . $request, 1);
			}
		}

		if($file_name !== ''){
			include_once("views/master.php");
		} else {
			throw new Exception($lang["api"]["bootstrap_not_a_view"] . " " . $request, 1);
		}
	};
	$API->addMethod("bootstrap", $bootstrap);

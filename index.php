<?php
	$site_req = str_ireplace(".", "", $_SERVER['HTTP_HOST']);

	if(file_exists("php/configs/" . $site_req  . "/config.php")){
		include_once("php/configs/" . $site_req  . "/config.php");
	} else {
		include_once("php/config.php");
	}

	define('site_req', $site_req);

	$isMobile = false;
	$useragent = $_SERVER['HTTP_USER_AGENT'];
	if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) $isMobile = true;
	define('isMobile', $isMobile);

	$tmp_config = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/php/templates/htaccess.dummy');
    $tmp_config = APIKEY !== '' ? str_ireplace("{{APIKEY}}", APIKEY, $tmp_config) : str_ireplace("{{APIKEY}}", "", $tmp_config);
    $tmp_config = str_ireplace("{{php_errors}}", $_SERVER["DOCUMENT_ROOT"] . "/php_errors", $tmp_config);
    file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/.htaccess', $tmp_config);

	if(DEBUG){
		ini_set('display_errors', 1);
		ini_set('ignore_repeated_errors', 1);
		ini_set('display_startup_errors', 1);
		ini_set('error_append_string', 'Error->');
		ini_set('error_prepend_string', '<-' . SITE);
		error_reporting(E_ALL);
	} else {
		error_reporting(0);
	}
	ini_set('memory_limit', '-1');
	ini_set('upload_max_size' , '1G');
    ini_set('post_max_size', '1G');
    ini_set('session.gc_maxlifetime', '2880');
    ini_set('max_input_vars', 3000);

	ob_start ("ob_gzhandler");
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

	if(FORCE_SSL !== ""){
		if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on"){
		    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
		    exit;
		}
	}

	$origins = array();

	$site = SITE !== "" ? SITE : "*";
	array_push($origins, $site);

	if(ALLOW_ORIGINS !== ""){
		$extra_origins = explode(",", ALLOW_ORIGINS);

		foreach ($extra_origins as $externo) {
			array_push($origins, $externo);
		}
	}

	session_start([
	    'read_and_close' => false,
	]);

	if(!isset($_SESSION["token"])){
		$_SESSION["token"] = time();
	} elseif(time() - $_SESSION["token"] > 86400){
		header("Location: /api/logout");
		exit();
	} elseif(time() - $_SESSION["token"] > 14400){
		session_regenerate_id(true);
		$_SESSION["token"] = time();
	}

	include_once("php/lang/". LANG . "/". LANG . ".php");
	include_once("php/api.php");

	if(isset($_REQUEST["jwt"])){
		try{
			if($verifyJWT($_REQUEST["jwt"])){
				$tmp_jwt_data = json_decode($decodeJWT($_REQUEST["jwt"]), true);
				foreach ($tmp_jwt_data as $key => $val) {
					$_REQUEST[$key] = urldecode($val);
					$_POST[$key] = urldecode($val);
				}
			} else {
				throw new Exception($lang["api"]["invalid_jwt"], 1);
			}
		} catch(Exception $e){
			header('Content-type:application/json;charset=utf-8');
			print json_encode(array("success" => false, "error" => $e->getMessage()));
			exit();
		}
	}
	$method = isset($_REQUEST["m"]) ? $_REQUEST["m"] : "views";

	try{
		if($site == "*" || in_array($_SERVER["HTTP_HOST"], $origins)){
			header('Access-Control-Allow-Origin: ' . $_SERVER["HTTP_HOST"]);
			header('Vary: Origin');
		} else {
			throw new Exception($lang["api"]["host_not_allowed"] . $_SERVER["HTTP_HOST"], 1);
		}

		set_time_limit(0);

		if($method == "views"){
			$API->bootstrap();
		} else {
			$method_wl = array("dispatchFile", "genLanguage");
			//auth check apikey
			if(APIKEY !== '' && !in_array($method, $method_wl)){
		        $not_authorized = true;
		        foreach (getallheaders() as $header => $val) {
		        	if($header == "Authorization") {
		        		$val = str_replace(",", "", $val);
		        		$val = explode(" ", $val);
		        		if(strripos($val[0], "Bearer") === false) throw new Exception($lang["api"]["invalid_header"]);

		        		$tmp_check = $API->checkAPIKey($val[1]);

			            if(!$tmp_check["success"]){
			            	throw new Exception($tmp_check["error"]);
			            } else {
			        		$not_authorized = false;
		        			break;
			        	}
		        	}
		        }
		        if($not_authorized) throw new Exception($lang["api"]["empty_header"]);
		    }
			$API->checkAPPState();
			if(is_callable(array($API, $method))){
				$response = call_user_func(array($API, $method));

				if(!$response){
					throw new Exception($lang["api"]["api_unknown_method"] . $method, 1);
				}

				if(isset($_SESSION["redirect"])){
					echo "<meta http-equiv=\"refresh\" content=\"" . ($_SESSION["redirect_timeout"] / 1000) . "; url=" . $_SESSION["redirect"] . "\">";
					echo $_SESSION["redirect_msg"];
					unset($_SESSION["redirect"]);
					unset($_SESSION["redirect_timeout"]);
					unset($_SESSION["redirect_msg"]);
				} else {
					if(is_string($response)){
						header('Content-type:application/html;charset=utf-8');
						print $response;
					} else {
						header('Content-type:application/json;charset=utf-8');
						print json_encode(array("jwt" => $signJWT($response)));
					}
				}
			}
		}
	} catch(Exception $e){
		if($method == "views"){
			$_REQUEST["error"] = $e->getMessage();
			$_REQUEST["r"] = "error";
			$API->bootstrap();
		} else {
			header('Content-type:application/json;charset=utf-8');
			$response = array("success" => false, "error" => $e->getMessage());
			if($response["error"] == $lang["api"]["checkParameters_action_not_allowed"]) {
				if(!isset($_SESSION["login"])) $response["logoutapp"] = true;
			}
			print json_encode($response);
		}
	}

	session_write_close();
	ob_flush();

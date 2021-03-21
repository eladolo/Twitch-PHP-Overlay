<?php
	include_once("views/openpages.php");

	$not_cache = array("login", "home", "overlay");
	if(!in_array($request, $not_cache, true)){
		ob_start();
		include("views/" . $file_name);
		ob_end_clean();
	}

	if(!in_array($request, $open_pages) && !isset($_SESSION["login"])){
		$_SESSION["redirect_comeback"] = $request;
		echo "<script>window.location.href='/login';</script>";
		exit;
	}
	if(isset($_REQUEST["redirect_comeback"]) && !isset($_SESSION["login"])){
		$_SESSION["redirect_comeback"] = $_REQUEST["redirect_comeback"];
		echo "<script>window.location.href='/login';</script>";
		exit;
	}
	$obs_host = '';
	$overlay_url = '';
	if(isset($_SESSION["login"])){
		if(isset($_SESSION["redirect_comeback"])){
			$tmp_comeback = $_SESSION["redirect_comeback"];
			unset($_SESSION["redirect_comeback"]);
			echo "<script>window.location.href='/" . $tmp_comeback . "';</script>";
			exit;
		}

		if(isset($accessLevel) && $_SESSION["login"]["level"] < $accessLevel){
			echo "<script>window.location.href='/error?error=" . $lang["bootstrap_access_denied"] . "';</script>";
			exit;
		}

		$overlay_url = array(
            "id" => $_SESSION["login"]["id"],
            "user" => $_SESSION["login"]["user"],
            "email" => $_SESSION["login"]["email"],
            "time" => time() + (60 * 60 * 24 * 4)
        );

        $overlay_url = json_encode($overlay_url);
        $overlay_url = $this->private["encryptIt"]($overlay_url);
        $overlay_url = 'overlay/' . $overlay_url . '?coff=1';

        $obs_host = $_SESSION["login"]["obs_host"];
	}

	if($request == "overlay"){
		if(!isset($_REQUEST["tkn"])){
			echo "<script> window.location.href = '/error?error='" . $lang["file_handlers_empty_token"] . ";</script>";
		}

		try{
			$token = $this->private["decryptIt"]($_REQUEST["tkn"]);
			$token = json_decode($token);
		} catch(Exception $ex) {
			echo "<script> window.location.href = '/error?error=" . $lang["file_handlers_invalid_token"] . "';</script>";
			exit();
		}

		if(time() - $token->time >= (60 * 60 * 24 * 180)){
			echo "<script> window.location.href = '/error?error=" . $lang["file_handlers_expired_token"] . "';</script>";
			exit();
		}

		$_REQUEST["user"] = $token->user;
		if(!$this->login()["success"]){
			echo "<script> window.location.href = '/error?error=" . $lang["file_handlers_invalid_user"] . "';</script>";
			exit();
		}

		$this->refreshToken();

		if(isset($_REQUEST["logo"])){
			echo '<img src="' . $_SESSION["login"]["img"] . '" alt="' . $_SESSION["login"]["user"] . '" />';
			exit();
		}
	}

	$redirect_string = "";
	if(isset($_SESSION["redirect"])){
		$redirect_string = "setTimeout(function(){window.location='" . $_SESSION["redirect"] . "';}, " . $_SESSION["redirect_timeout"] . ");";
		unset($_SESSION["redirect"]);
		unset($_SESSION["redirect_timeout"]);
	}
	$title = isset($tmp_title) ? $tmp_title : strtoupper(preg_replace("([A-Z])", " $0", $request));
	$description = isset($tmp_description) ? strip_tags($tmp_description) : strip_tags(DESC);
	$keywords = isset($tmp_keywords) ? strip_tags($tmp_keywords) : strip_tags(DESC);
	$autor = isset($tmp_autor) ? $tmp_autor : SITE;
	$imagen = isset($tmp_img) ? "https://" . SITE . $tmp_img : "https://" . SITE . "/" . LFfavicon;

	$google_fonts = "Open Sans|Abril Fatface|Josefin Slab|Sedgwick Ave|Acme|Anton|Arvo|Dancing Script|Lato|Lora|Merriweather|Pacifico|Questrial|Saira Extra Condensed|Sedgwick Ave|Sedgwick Ave Display|Shadows Into Light|Quicksand|Skranji|Mansalva|Turret Road|Amatic SC|Caveat|Luckiest Guy|Bangers|Oi|Akaya Kanadaka|Roboto|Ballet|Dancing Script|Lobster|Indie Flower|Benne|Architects Daughter|Rajdhani|Permanent Marker|Butcherman|Zhi Mang Xing|Fascinate|Vibes|Black And White Picture|Londrina Sketch|Fruktur|Sevillana|Kenia|Warnes|Single Day|Smokum|Stalinist One|Erica One|Calligraffitti|Staatliches|Reggae One";
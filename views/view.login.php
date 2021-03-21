<?php
	if(isset($_SESSION["login"])){
		echo "<script>window.location.href='/';</script>";
		exit;
	}

    if (!isset($_GET['code'])) {
        // Create GET url
        $scopes = urlencode("user_read user:read:email user_subscriptions bits:read chat:read chat:edit channel:read:redemptions");
        $authorizationUrl = "https://id.twitch.tv/oauth2/authorize?scope=" . $scopes . "&response_type=code&redirect_uri=https://" . $_SERVER["HTTP_HOST"] . "/login&client_id=" . TWITCH_CLIENT . "&force_verify=true";
        $_SESSION['twitch_state'] = md5($authorizationUrl . time());
        $authorizationUrl .= "&state=" . $_SESSION['twitch_state'];

        // Display link to start auth flow
        echo "
        <div style='text-align:center;'>
        	<br><br>
            <a id='btnLoginInit' href='$authorizationUrl' class='btn-large LFColor white-text'>" . $lang["views"]["navbar_login"] . "</a>
            <br><br>
        </div>";
        echo '
        <div class="switch" style="text-align:center;">
            <label>
                <input type="checkbox" name="remember_switch" id="remember_switch" />
                <span class="lever"></span>
                ' . $lang["views"]["login_remember_me"] . '
            </label>
            <br>
        </div>';
    // Check given state against previously stored one to mitigate CSRF attack
    } elseif (empty($_GET['state']) || (isset($_SESSION['twitch_state']) && $_GET['state'] !== $_SESSION['twitch_state'])) {

        if (isset($_SESSION['twitch_state'])) {
            unset($_SESSION['twitch_state']);
        }

        if(isset($_SESSION["login_service"])) unset($_SESSION["login_service"]);

        exit('Invalid state');
    } else {
        try {
            // Get an access token using authorization code grant.
            $response = $this->requrl(array(
            	"url" => "https://id.twitch.tv/oauth2/token?client_id=" . TWITCH_CLIENT . "&client_secret=" . TWITCH_SECRET . "&code=" . $_GET["code"] . "&grant_type=authorization_code&redirect_uri=https://" . $_SERVER["HTTP_HOST"] . "/login",
            	"type" => "POST"
            ));
            $response = $response["response"];

            /*
            * User info
            */
            $user_res = $this->requrl(array(
                "url" => "https://api.twitch.tv/helix/users",
                "header" => array(
                    'Authorization: Bearer ' . $response["access_token"],
                    'Client-Id: ' . TWITCH_CLIENT
                )
            ));

            $user_res = $user_res["response"]["data"][0];

            $_SESSION["login"] = array(
                "id" => $user_res["id"],
                "user" => $user_res["login"],
                "name" => $user_res["display_name"],
                "email" => $user_res["email"],
                "img" => $user_res["profile_image_url"],
                "tkn" => $response["access_token"],
                "refresh_tkn" => $response["refresh_token"],
                "level" => 50
            );

            if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/php/install")){
                $this->genDB();

                $_REQUEST["id"] = $user_res["id"];
                $_REQUEST["user"] = $user_res["login"];
                $_REQUEST["name"] = $user_res["display_name"];
                $_REQUEST["email"] = $user_res["email"];
                $_REQUEST["img"] = $user_res["profile_image_url"];
                $_REQUEST["tkn"] = $response["access_token"];
                $_REQUEST["refresh_tkn"] = $response["refresh_token"];
                $_REQUEST["level"] = 60;
                $this->createUser();

                $_REQUEST["APIKEY"] = $this->setAPIkey()["apikey"];
                $this->updateConfig();
            } else {
                if(!$this->userExists($user_res["id"])["success"]){
                    // New user
                    $_REQUEST["id"] = $user_res["id"];
                    $_REQUEST["user"] = $user_res["login"];
                    $_REQUEST["name"] = $user_res["display_name"];
                    $_REQUEST["email"] = $user_res["email"];
                    $_REQUEST["img"] = $user_res["profile_image_url"];
                    $_REQUEST["tkn"] = $response["access_token"];
                    $_REQUEST["refresh_tkn"] = $response["refresh_token"];
                    $_REQUEST["level"] = 50;
                    $this->createUser();
                } else {
                    // Update existing user
                    $_REQUEST["id"] = $user_res["id"];
                    $_REQUEST["user"] = $user_res["login"];
                    $_REQUEST["name"] = $user_res["display_name"];
                    $_REQUEST["email"] = $user_res["email"];
                    $_REQUEST["img"] = $user_res["profile_image_url"];
                    $_REQUEST["tkn"] = $response["access_token"];
                    $_REQUEST["refresh_tkn"] = $response["refresh_token"];
                    $this->updateUser();
                }
            }

            echo "<script>window.location.href='/';</script>";
        } catch (Exception $e) {
            exit('Caught exception: '. $e->getMessage());
        }
    }

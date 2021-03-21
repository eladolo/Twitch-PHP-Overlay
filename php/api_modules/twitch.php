<?php
    /*
    * Refresh token
    */
    $refreshToken = function(){
        $this->private["checkParameters"](array(), false, 50);
        $response = array("success" => false);

        // Get an access token using authorization code grant.
        $response = $this->requrl(array(
            "url" => "https://id.twitch.tv/oauth2/token?client_id=" . TWITCH_CLIENT . "&client_secret=" . TWITCH_SECRET . "&grant_type=refresh_token&refresh_token=" . $_SESSION["login"]["refresh_tkn"] . "",
            "type" => "POST"
        ));

        $_REQUEST["id"] = $_SESSION["login"]["id"];
        $_REQUEST["tkn"] = $response["response"]["access_token"];
        $_REQUEST["refresh_tkn"] = $response["response"]["refresh_token"];
        $this->updateUser();

        $response["success"] = true;

        return $response;
    };
    $API->addMethod("refreshToken", $refreshToken);

    /*
    * Get rewards
    */
    $getRewards = function(){
        $this->private["checkParameters"](array(), false, 50);
        $response = array("success" => false, "rewards" => array());

        $twitch_res = $this->requrl(array(
            "url" => "https://api.twitch.tv/helix/channel_points/custom_rewards?broadcaster_id=" . $_SESSION["login"]["id"],
            "header" => array(
                'Accept: application/vnd.twitchtv.v5+json',
                'Client-ID: ' . TWITCH_CLIENT,
                'Authorization: Bearer ' . $_SESSION["login"]["tkn"]
            ),
            "type" => "GET"
        ));

        if(isset($twitch_res["response"]["error"]) && !isset($_SESSION["refresh_try"])){
            $_SESSION["refresh_try"] = true;
            $this->refreshToken();
            return $this->getRewards;
        } else {
            if(isset($_SESSION["refresh_try"])) unset($_SESSION["refresh_try"]);

            if(isset($twitch_res["response"]["data"])){
                $response["success"] = true;
                $response["rewards"] = $twitch_res["response"]["data"];
            }
        }

        return $response;
    };
    $API->addMethod("getRewards", $getRewards);

    /*
    * Get emotes
    */
    $getEmotes = function(){
        $this->checkParameters(array(), false, 50);
        $response = array();
        $response["emotes"] = array();

        //twitch
        $twitch = $this->requrl(array(
            "url" => "https://api.twitch.tv/v5/users/" . $_SESSION["login"]["id"] . "/emotes",
            "header" => array(
                'Accept: application/vnd.twitchtv.v5+json',
                'Client-ID: ' . TWITCH_CLIENT,
                'Authorization: OAuth ' . $_SESSION["login"]["tkn"]
            )
        ));
        $twitch = $twitch["response"];
        $cat_arr = array();
        foreach($twitch["emoticon_sets"] as $emote_set => $set_value){
            foreach($set_value as $emote){
                $tmp_emote = new stdClass();
                if(!isset($emote["code"])) continue;
                $tmp_emote->type = "Twitch";
                $tmp_emote->name = $emote["code"];
                $tmp_emote->url = "https://static-cdn.jtvnw.net/emoticons/v1/" . $emote["id"] . "/1.0";
                if(!in_array($tmp_emote, $response["emotes"])) $response["emotes"][$tmp_emote->name] = $tmp_emote;
            }
        }
        //betterttv
        $betterttv_channel = $this->requrl(array(
            "url" => "https://api.betterttv.net/3/cached/users/twitch/" . $_SESSION["login"]["id"]
        ));
        $betterttv_channel = $betterttv_channel["response"];
        foreach($betterttv_channel["channelEmotes"] as $emote){
            $tmp_emote = new stdClass();
            if(!isset($emote["code"])) continue;
            $tmp_emote->type = "Betterttv " . $_SESSION["login"]["user"];
            $tmp_emote->name = $emote["code"];
            $tmp_emote->url = "//cdn.betterttv.net/emote/" . $emote["id"] . "/1x";
            if(!in_array($tmp_emote, $response["emotes"])) $response["emotes"][$tmp_emote->name] = $tmp_emote;
        }
        foreach($betterttv_channel["sharedEmotes"] as $emote){
            $tmp_emote = new stdClass();
            if(!isset($emote["code"])) continue;
            $tmp_emote->type = "Betterttv " . $_SESSION["login"]["user"];
            $tmp_emote->name = $emote["code"];
            $tmp_emote->url = "//cdn.betterttv.net/emote/" . $emote["id"] . "/1x";
            if(!in_array($tmp_emote, $response["emotes"])) $response["emotes"][$tmp_emote->name] = $tmp_emote;
        }
        //frankerfacez
        $frankerfacez_channel = $this->requrl(array(
            "url" => "https://api.frankerfacez.com/v1/room/" . $_SESSION["login"]["user"]
        ));
        $frankerfacez_channel = $frankerfacez_channel["response"];
        if(isset($frankerfacez_channel["room"])){
            foreach($frankerfacez_channel["sets"][$frankerfacez_channel["room"]["set"]]["emoticons"] as $emote){
                $tmp_emote = new stdClass();
                if(!isset($emote["name"])) continue;
                $tmp_emote->type = "Frankerfacez " . $_SESSION["login"]["user"];
                $tmp_emote->name = $emote["name"];
                $tmp_emote->url = $emote["urls"]["1"];
                if(!in_array($tmp_emote, $response["emotes"])) $response["emotes"][$tmp_emote->name] = $tmp_emote;
            }
        }

        return $response;
    };
    $API->addMethod("getEmotes", $getEmotes);

    /*
    * Get user follows
    */
    $getFollows = function(){
        $this->private["checkParameters"](array(), false, 50);
        $response = array("success" => true, "follows" => array(), "autocomplete" => array(), "total" => 0);

        $tmp_channels = array();
        $tmp_channels["follows"] = array();
        $tmp_channels["autocomplete"] = array();

        $tmp_res = $this->requrl(array(
            "url" => "https://api.twitch.tv/helix/users/follows?from_id=" . $_SESSION["login"]["id"] . "&first=100",
            "header" => array(
                'Authorization: Bearer ' . $_SESSION["login"]["tkn"]
                ,'Client-ID: ' . TWITCH_CLIENT
            )
        ));

        $tmp_res = $tmp_res["response"];

        if($tmp_res["total"] > 100) {
            $total_pages = ceil($tmp_res["total"] / 100);
            $p = 1;
            while($p <= $total_pages){
                $tmp_ids = '';
                foreach ($tmp_res["data"] as $follow_ids) {
                    $tmp_ids .= 'id=' . $follow_ids["to_id"] . '&';
                }

                $channel_info = $this->requrl(array(
                    "url" =>  'https://api.twitch.tv/helix/users?' . $tmp_ids,
                    "header" => array(
                        'Authorization: Bearer ' . $_SESSION["login"]["tkn"]
                        ,'Client-ID: ' . TWITCH_CLIENT
                    )
                ));

                $tmp_channel_info = $channel_info["response"];

                foreach ($tmp_channel_info["data"] as $follow) {
                    if(!in_array($follow, $tmp_channels["follows"])) array_push($tmp_channels["follows"], $follow);
                    $tmp_channels["autocomplete"][$follow["login"]] = $follow["profile_image_url"];
                }

                $qstring = "https://api.twitch.tv/helix/users/follows?from_id=" . $_SESSION["login"]["id"] . "&first=100&after=" . $tmp_res["pagination"]["cursor"];

                $new_res = $this->requrl(array(
                    "url" => $qstring,
                    "header" => array(
                        'Authorization: Bearer ' . $_SESSION["login"]["tkn"]
                        ,'Client-ID: ' . TWITCH_CLIENT
                    )
                ));

                $tmp_res = $new_res["response"];

                $p++;
            }
        } else {
            $tmp_ids = '';
            foreach ($tmp_res["data"] as $follow_ids) {
                $tmp_ids .= 'id=' . $follow_ids["to_id"] . '&';
            }

            $channel_info = $this->requrl(array(
                "url" =>  'https://api.twitch.tv/helix/users?' . $tmp_ids,
                "header" => array(
                    'Authorization: Bearer ' . $_SESSION["login"]["twitch_token"]
                    ,'Client-ID: ' . TWITCH_CLIENT
                )
            ));

            $tmp_channel_info = $channel_info["response"];

            foreach ($tmp_channel_info["data"] as $follow) {
                if(!in_array($follow, $tmp_channels["follows"])) {
                    array_push($tmp_channels["follows"], $follow);
                    $tmp_channels["autocomplete"][$follow["login"]] = $follow["profile_image_url"];
                }
            }
        }

        $response["total"] = $tmp_res["total"];
        $response["follows"] = $tmp_channels["follows"];
        $response["autocomplete"] = $tmp_channels["autocomplete"];

        return $response;
    };
    $API->addMethod("getFollows", $getFollows);

<?php
    /*
    * function to update config define file
    */
    $updateConfig = function(){
        $level = file_exists($_SERVER["DOCUMENT_ROOT"] . "php/install") ? null : 60;
        $response = array("success" => false);

        try{
            //lang file updated
            $target_lang = $_SERVER["DOCUMENT_ROOT"] . "/php/lang/" . LANG . "/" . LANG . ".php";

            $temp_lang = array(
                "api" => array(),
                "views" => array()
            );

            foreach ($_REQUEST as $key => $value) {
                if(stripos($key, "langvar_") !== false){
                    $key = str_ireplace("langvar_", "", $key);
                    $key = explode("_", $key, 2);

                    $temp_lang[$key[0]][$key[1]] = $value;
                }
            }

            file_put_contents($target_lang, '<?php $lang = ' . var_export($temp_lang, true) . ';');
            //lang file update end

            if($_REQUEST["SIDENAV"] === ""){
                $_REQUEST["NAVBAR"] = true;
            } else {
                $_REQUEST["NAVBAR"] = "";
            }
            if(isset($_REQUEST["ABOUT"])) $_REQUEST["ABOUT"] = str_replace("'", "", $_REQUEST["ABOUT"]);
            $tmp_config = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/php/templates/config.dummy.php');

            ///init site configurations
            $tmp_config = (isset($_REQUEST["VERSION"])) ? str_ireplace("{{VERSION}}", $_REQUEST["VERSION"], $tmp_config) : str_ireplace("{{VERSION}}", VERSION, $tmp_config);
            $tmp_config = (isset($_REQUEST["DEBUG"])) ? str_ireplace("{{DEBUG}}", $_REQUEST["DEBUG"], $tmp_config) : str_ireplace("{{DEBUG}}", (isset($_REQUEST["isform"]) ? '' : DEBUG), $tmp_config);
            $tmp_config = (isset($_REQUEST["INSTALL"])) ? str_ireplace("{{INSTALL}}", $_REQUEST["INSTALL"], $tmp_config) : str_ireplace("{{INSTALL}}", (isset($_REQUEST["isform"]) ? '' : INSTALL), $tmp_config);
            $tmp_config = (isset($_REQUEST["FORCE_SSL"])) ? str_ireplace("{{FORCE_SSL}}", $_REQUEST["FORCE_SSL"], $tmp_config) : str_ireplace("{{FORCE_SSL}}", (isset($_REQUEST["isform"]) ? '' : FORCE_SSL), $tmp_config);

            //site configurations
            $tmp_config = (isset($_REQUEST["ALLOW_ORIGINS"])) ? str_ireplace("{{ALLOW_ORIGINS}}", $_REQUEST["ALLOW_ORIGINS"], $tmp_config) : str_ireplace("{{ALLOW_ORIGINS}}", ALLOW_ORIGINS, $tmp_config);
            $tmp_config = (isset($_REQUEST["SITE"])) ? str_ireplace("{{SITE}}", $_REQUEST["SITE"], $tmp_config) : str_ireplace("{{SITE}}", SITE, $tmp_config);
            $tmp_config = (isset($_REQUEST["SITE_ID"])) ? str_ireplace("{{SITE_ID}}", $_REQUEST["SITE_ID"], $tmp_config) : str_ireplace("{{SITE_ID}}", SITE_ID, $tmp_config);
            $tmp_config = (isset($_REQUEST["SITE_MODE"])) ? str_ireplace("{{SITE_MODE}}", $_REQUEST["SITE_MODE"], $tmp_config) : str_ireplace("{{SITE_MODE}}", SITE_MODE, $tmp_config);
            $tmp_config = (isset($_REQUEST["SITE_LAYOUT"])) ? str_ireplace("{{SITE_LAYOUT}}", $_REQUEST["SITE_LAYOUT"], $tmp_config) : str_ireplace("{{SITE_LAYOUT}}", SITE_LAYOUT, $tmp_config);
            $tmp_config = (isset($_REQUEST["SITE_ROOT"])) ? str_ireplace("{{SITE_ROOT}}", $_REQUEST["SITE_ROOT"], $tmp_config) : str_ireplace("{{SITE_ROOT}}", SITE_ROOT, $tmp_config);
            
            //L&F label and content
            $tmp_config = (isset($_REQUEST["HOMELABEL"])) ? str_ireplace("{{HOMELABEL}}", $_REQUEST["HOMELABEL"], $tmp_config) : str_ireplace("{{HOMELABEL}}", HOMELABEL, $tmp_config);
            $tmp_config = (isset($_REQUEST["USER_ADMIN"])) ? str_ireplace("{{USER_ADMIN}}", $_REQUEST["USER_ADMIN"], $tmp_config) : str_ireplace("{{USER_ADMIN}}", USER_ADMIN, $tmp_config);
            $tmp_config = (isset($_REQUEST["DESC"])) ? str_ireplace("{{DESC}}", $_REQUEST["DESC"], $tmp_config) : str_ireplace("{{DESC}}", DESC, $tmp_config);
            $tmp_config = (isset($_REQUEST["ABOUT"])) ? str_ireplace("{{ABOUT}}", $_REQUEST["ABOUT"], $tmp_config) : str_ireplace("{{ABOUT}}", ABOUT, $tmp_config);
            $tmp_config = (isset($_REQUEST["MINIDESC"])) ? str_ireplace("{{MINIDESC}}", $_REQUEST["MINIDESC"], $tmp_config) : str_ireplace("{{MINIDESC}}", MINIDESC, $tmp_config);
            $tmp_config = (isset($_REQUEST["ABOUTLABEL"])) ? str_ireplace("{{ABOUTLABEL}}", $_REQUEST["ABOUTLABEL"], $tmp_config) : str_ireplace("{{ABOUTLABEL}}", ABOUTLABEL, $tmp_config);
            $tmp_config = (isset($_REQUEST["CONTAINER_LAYOUT"])) ? str_ireplace("{{CONTAINER_LAYOUT}}", $_REQUEST["CONTAINER_LAYOUT"], $tmp_config) : str_ireplace("{{CONTAINER_LAYOUT}}", (isset($_REQUEST["isform"]) ? '' : CONTAINER_LAYOUT), $tmp_config);
            $tmp_config = (isset($_REQUEST["LANG"])) ? str_ireplace("{{LANG}}", $_REQUEST["LANG"], $tmp_config) : str_ireplace("{{LANG}}", LANG, $tmp_config);
            
            //L&F color
            $tmp_config = (isset($_REQUEST["LFColor"])) ? str_ireplace("{{LFColor}}", $_REQUEST["LFColor"], $tmp_config) : str_ireplace("{{LFColor}}", LFColor, $tmp_config);
            $tmp_config = (isset($_REQUEST["LFBKColor"])) ? str_ireplace("{{LFBKColor}}", $_REQUEST["LFBKColor"], $tmp_config) : str_ireplace("{{LFBKColor}}", LFBKColor, $tmp_config);
            $tmp_config = (isset($_REQUEST["LFFont"])) ? str_ireplace("{{LFFont}}", $_REQUEST["LFFont"], $tmp_config) : str_ireplace("{{LFFont}}", LFFont, $tmp_config);
            $tmp_config = (isset($_REQUEST["LFFontColor"])) ? str_ireplace("{{LFFontColor}}", $_REQUEST["LFFontColor"], $tmp_config) : str_ireplace("{{LFFontColor}}", LFFontColor, $tmp_config);
            $tmp_config = (isset($_REQUEST["LandingColor"])) ? str_ireplace("{{LandingColor}}", $_REQUEST["LandingColor"], $tmp_config) : str_ireplace("{{LandingColor}}", LandingColor, $tmp_config);
            $tmp_config = (isset($_REQUEST["AboutColor"])) ? str_ireplace("{{AboutColor}}", $_REQUEST["AboutColor"], $tmp_config) : str_ireplace("{{AboutColor}}", AboutColor, $tmp_config);
            
            //L&F images & video
            $tmp_config = (isset($_REQUEST["LFfavicon"])) ? str_ireplace("{{LFfavicon}}", $_REQUEST["LFfavicon"], $tmp_config) : str_ireplace("{{LFfavicon}}", LFfavicon, $tmp_config);
            $tmp_config = (isset($_REQUEST["LFLogo"])) ? str_ireplace("{{LFLogo}}", $_REQUEST["LFLogo"], $tmp_config) : str_ireplace("{{LFLogo}}", LFLogo, $tmp_config);
            $tmp_config = (isset($_REQUEST["LFParallax"])) ? str_ireplace("{{LFParallax}}", $_REQUEST["LFParallax"], $tmp_config) : str_ireplace("{{LFParallax}}", LFParallax, $tmp_config);
            $tmp_config = (isset($_REQUEST["LandingVideo"])) ? str_ireplace("{{LandingVideo}}", $_REQUEST["LandingVideo"], $tmp_config) : str_ireplace("{{LandingVideo}}", LandingVideo, $tmp_config);
            $tmp_config = (isset($_REQUEST["ABOUTIMG"])) ? str_ireplace("{{ABOUTIMG}}", $_REQUEST["ABOUTIMG"], $tmp_config) : str_ireplace("{{ABOUTIMG}}", ABOUTIMG, $tmp_config);
            $tmp_config = (isset($_REQUEST["ABOUTVIDEO"])) ? str_ireplace("{{ABOUTVIDEO}}", $_REQUEST["ABOUTVIDEO"], $tmp_config) : str_ireplace("{{ABOUTVIDEO}}", ABOUTVIDEO, $tmp_config);
            
            //Animations
            $tmp_config = (isset($_REQUEST["ANIME_ON"])) ? str_ireplace("{{ANIME_ON}}", $_REQUEST["ANIME_ON"], $tmp_config) : str_ireplace("{{ANIME_ON}}", (isset($_REQUEST["isform"]) ? '' : ANIME_ON), $tmp_config);
            
            //navbar & sidenav
            $tmp_config = (isset($_REQUEST["SIDENAV"])) ? str_ireplace("{{SIDENAV}}", $_REQUEST["SIDENAV"], $tmp_config) : str_ireplace("{{SIDENAV}}", (isset($_REQUEST["isform"]) ? '' : SIDENAV), $tmp_config);
            $tmp_config = (isset($_REQUEST["NAVBAR"])) ? str_ireplace("{{NAVBAR}}", $_REQUEST["NAVBAR"], $tmp_config) : str_ireplace("{{NAVBAR}}", (isset($_REQUEST["isform"]) ? '' : NAVBAR), $tmp_config);
            $tmp_config = (isset($_REQUEST["SIDENAVPOS"])) ? str_ireplace("{{SIDENAVPOS}}", $_REQUEST["SIDENAVPOS"], $tmp_config) : str_ireplace("{{SIDENAVPOS}}", SIDENAVPOS, $tmp_config);
            $tmp_config = (isset($_REQUEST["SIDENAVPOS_Y"])) ? str_ireplace("{{SIDENAVPOS_Y}}", $_REQUEST["SIDENAVPOS_Y"], $tmp_config) : str_ireplace("{{SIDENAVPOS_Y}}", SIDENAVPOS_Y, $tmp_config);
            
            //db
            $tmp_config = (isset($_REQUEST["DB_DRIVER"])) ? str_ireplace("{{DB_DRIVER}}", $_REQUEST["DB_DRIVER"], $tmp_config) : str_ireplace("{{DB_DRIVER}}", DB_DRIVER, $tmp_config);
            $tmp_config = (isset($_REQUEST["DB_host"])) ? str_ireplace("{{DB_host}}", $_REQUEST["DB_host"], $tmp_config) : str_ireplace("{{DB_host}}", DB_host, $tmp_config);
            $tmp_config = (isset($_REQUEST["DB_name"])) ? str_ireplace("{{DB_name}}", $_REQUEST["DB_name"], $tmp_config) : str_ireplace("{{DB_name}}", DB_name, $tmp_config);
            $tmp_config = (isset($_REQUEST["DB_user"])) ? str_ireplace("{{DB_user}}", $_REQUEST["DB_user"], $tmp_config) : str_ireplace("{{DB_user}}", DB_user, $tmp_config);
            $tmp_config = (isset($_REQUEST["DB_password"])) ? str_ireplace("{{DB_password}}", $_REQUEST["DB_password"], $tmp_config) : str_ireplace("{{DB_password}}", DB_password, $tmp_config);
            
            //apikey
            $tmp_config = (isset($_REQUEST["APIKEY"])) ? str_ireplace("{{APIKEY}}", $_REQUEST["APIKEY"], $tmp_config) : str_ireplace("{{APIKEY}}", APIKEY, $tmp_config);
            
            //cryptokeys
            $tmp_config = (isset($_REQUEST["SECRET_SHARE"])) ? str_ireplace("{{SECRET_SHARE}}", $_REQUEST["SECRET_SHARE"], $tmp_config) : str_ireplace("{{SECRET_SHARE}}", SECRET_SHARE, $tmp_config);
            $tmp_config = (isset($_REQUEST["SECRET_PUBLIC"])) ? str_ireplace("{{SECRET_PUBLIC}}", $_REQUEST["SECRET_PUBLIC"], $tmp_config) : str_ireplace("{{SECRET_PUBLIC}}", SECRET_PUBLIC, $tmp_config);

            //twitch
            $tmp_config = (isset($_REQUEST["TWITCH_CLIENT"])) ? str_ireplace("{{TWITCH_CLIENT}}", $_REQUEST["TWITCH_CLIENT"], $tmp_config) : str_ireplace("{{TWITCH_CLIENT}}", TWITCH_CLIENT, $tmp_config);
            $tmp_config = (isset($_REQUEST["TWITCH_SECRET"])) ? str_ireplace("{{TWITCH_SECRET}}", $_REQUEST["TWITCH_SECRET"], $tmp_config) : str_ireplace("{{TWITCH_SECRET}}", TWITCH_SECRET, $tmp_config);

            if(!file_exists($_SERVER["DOCUMENT_ROOT"] . '/php/configs/' . site_req . '/')) {
                $new_id = scandir($_SERVER["DOCUMENT_ROOT"] . '/php/configs/');
                $new_id = count($new_id) - 2 + 2;
                $tmp_config = (str_ireplace("{{SITE_ID}}", $new_id, $tmp_config));
                mkdir($_SERVER["DOCUMENT_ROOT"] . '/php/configs/' . site_req . '/');
                mkdir($_SERVER["DOCUMENT_ROOT"] . '/php/files/uploads/' . site_req . '/');
                mkdir($_SERVER["DOCUMENT_ROOT"] . '/php/files/uploads/' . site_req . '/image/');
                mkdir($_SERVER["DOCUMENT_ROOT"] . '/php/files/uploads/' . site_req . '/private/');
                mkdir($_SERVER["DOCUMENT_ROOT"] . '/php/files/uploads/' . site_req . '/public/');
            } else {
                $tmp_config = (isset($_REQUEST["SITE_ID"])) ? str_ireplace("{{SITE_ID}}", $_REQUEST["SITE_ID"], $tmp_config) : str_ireplace("{{SITE_ID}}", SITE_ID, $tmp_config);
            }

            file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/php/configs/' . site_req . '/config.php', $tmp_config);

            if(isset($_REQUEST["target"]) && !empty($_REQUEST["target"])){
                $this->genLanguage();
            }

            $response = array("success" => true);
        } catch(Exception $e) {
            $response["error"] = $e->getMessage();
        }

        return $response;
    };
    $API->addMethod("updateConfig", $updateConfig);

    /*
    * function to gen/regen app database
    */
    $genDB = function() use($lang){
        $level = file_exists($_SERVER["DOCUMENT_ROOT"] . "/php/install") ? null : 60;
        $this->private["checkParameters"](array(), false, $level);
        $db = $this->db;
        $response = array("success" => false);

        try{
            if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/php/install")){
                $query = "CREATE TABLE IF NOT EXISTS `users` (
                    `id` INT PRIMARY KEY,
                    `user` TEXT NOT NULL,
                    `name` TEXT NOT NULL,
                    `email` TEXT NOT NULL,
                    `img` TEXT NOT NULL,
                    `tkn` TEXT NOT NULL,
                    `refresh_tkn` TEXT NOT NULL,
                    `level` INT NOT NULL,
                    `obs_host` TEXT,
                    `obs_password` TEXT,
                    `overlay` INT,
                    `updated` DATETIME,
                    `created` DATETIME,
                    `status` BOOLEAN
                );";
                $res = $db->runQuery($query, array(), $lang["api"]["settings_cant_create_db_user"]);

                if($res == $lang["api"]["settings_cant_create_db_user"]) {
                    throw new Exception($lang["api"]["settings_cant_create_db_user"], 1);
                }

                $query = "CREATE TABLE IF NOT EXISTS `apikeys` (
                    `aid` INT PRIMARY KEY,
                    `uid` INT NOT NULL,
                    `apikey` TEXT NOT NULL,
                    `updated` DATETIME,
                    `created` DATETIME,
                    `status` BOOLEAN,
                    FOREIGN KEY (uid)
                        REFERENCES users(id)
                            ON UPDATE NO ACTION
                            ON DELETE CASCADE
                );";
                $res = $db->runQuery($query, array(), $lang["api"]["settings_cant_create_db_apikeys"]);

                if($res == $lang["api"]["settings_cant_create_db_apikeys"]) {
                    throw new Exception($lang["api"]["settings_cant_create_db_apikeys"], 1);
                }

                $query = "CREATE TABLE IF NOT EXISTS `overlays` (
                    `oid` INT PRIMARY KEY,
                    `uid` INT NOT NULL,
                    `reward` TEXT NOT NULL,
                    `config` TEXT NOT NULL,
                    `updated` DATETIME,
                    `created` DATETIME,
                    `status` BOOLEAN,
                    FOREIGN KEY (uid)
                        REFERENCES users(id)
                            ON UPDATE NO ACTION
                            ON DELETE CASCADE
                );";
                $res = $db->runQuery($query, array(), $lang["api"]["settings_cant_create_db_overlays"]);

                if($res == $lang["api"]["settings_cant_create_db_overlays"]) {
                    throw new Exception($lang["api"]["settings_cant_create_db_overlays"], 1);
                }

                unlink($_SERVER["DOCUMENT_ROOT"] . "/php/install");
                $response["success"] = true;
            }
        } catch(Exception $e) {
            $_SESSION["error"] = array("msg" => $e->getMessage(), "redirect" => "/login");
        }

        return $response;
    };
    $API->addMethod("genDB", $genDB);

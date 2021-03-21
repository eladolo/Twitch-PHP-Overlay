<?php
	/*
    * check APP state
    */
    $checkAPPState = function(){
        global $redis;
        if((!file_exists($_SERVER["DOCUMENT_ROOT"] . '/php/states/appstate_' . session_id() . '.json')) || isset($_REQUEST["init"]) || !isset($_SESSION["app_history"])) {
            unset($_SESSION["app_history"]);
            file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/php/states/appstate_' . session_id() . '.json', json_encode(array()));
        }

        $new_res = !isset($_SESSION["app_history"]) ? array() : "file";
        if($new_res === "file"){
            $new_res = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/php/states/appstate_' . session_id() . '.json');
            $new_res = json_decode($new_res);
        }

        $new_res = is_array($new_res) ? $new_res : array();

        $req = (isset($_REQUEST["r"])) ? array("request" => $_REQUEST["r"], "type" => "url", "session" => $_SESSION) : array("request" => $_REQUEST, "type" => "API", "session" => $_SESSION);
        array_push($new_res, $req);

        file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/php/states/appstate_' . session_id() . '.json', json_encode($new_res));
        $_SESSION["app_history"] = true;

        return $new_res;
    };
    $API->addMethod("checkAPPState", $checkAPPState);

    /*
    * check functions execution parmeters
    */
    $checkParameters = function($parameters, $values_empty = false, $level_permision = null) use($lang){
        if($level_permision && !isset($_SESSION["login"])){
            throw new Exception($lang["api"]["checkParameters_action_not_allowed"]);
        }
        if(isset($level_permision) && isset($_SESSION["login"])){
            $tmp_level = $level_permision;
            if((int)$_SESSION["login"]["level"] < (int)$tmp_level) {
                throw new Exception($lang["api"]["checkParameters_action_not_allowed"]);
            }
        }

        if($values_empty){
            foreach($parameters as $name => $value) {
                if(empty($value)) {
                    throw new Exception($lang["api"]["checkParameters_empty_parameter"] . $name);
                }
            }
        } else {
            if(count($parameters) > 0){
                foreach($parameters as $value) {
                    if(!isset($_REQUEST[$value])) {
                        throw new Exception($lang["api"]["checkParameters_missing_parameter"] . $value);
                    }
                    if(empty($_REQUEST[$value])) {
                        throw new Exception($lang["api"]["checkParameters_empty_value"] . $value);
                    }
                }
            }
        }

        return true;
    };
    $API->addMethod("checkParameters", $checkParameters, "private");


    /*
    * Function for keeping session alive while login into the system
    */
    $ping = function(){
        $this->checkParameters(array(), false, 1);
        $response = array("success" => true);

        return $response;
    };
    $API->addMethod("ping", $ping);

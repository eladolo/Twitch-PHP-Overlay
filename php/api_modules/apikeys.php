<?php
	/*
    * Gen new API Key
    */
    $genAPIKey = function() use($Crypto){
        $tmp_id = isset($_SESSION["login"]) ? $_SESSION["login"]["id"] : "-1";
        $tmp_token = $Crypto::encryptWithPassword("id=" . $tmp_id . "&site=" . SITE . "&time=" . time(), SECRET_SHARE);

        return array("success" => true, "apikey" => $tmp_token);
    };
    $API->addMethod("genAPIKey", $genAPIKey);

    /*
    * decode API Key
    */
    $decodeAPIKey = function($token) use($Crypto){
        $this->private["checkParameters"](array($token), true, null);
        $tmp_token = $Crypto::decryptWithPassword($token, SECRET_SHARE);

        return array("success" => true, "data" => $tmp_token);
    };
    $API->addMethod("decodeAPIKey", $decodeAPIKey, "private");

    /*
    * Set API Key
    */
    $setAPIkey = function($tmpkey = null) use($lang){
        $this->private["checkParameters"](array(), false, 50);
        $response = array("success" => false);
        $db = $this->db;

        $query = "UPDATE apikeys ";
        $query .= "SET status = :status ";
        $query .= ",updated = :updated ";
        $query .= "WHERE uid = :uid ";

        $param = array();
        $param[":uid"] = $_SESSION["login"]["id"];
        $param[":status"] = 0;
        $param[":updated"] = time();

        $res = $db->runQuery($query, $param, "");

        $new_apikey = isset($tmpkey) ? $tmpkey : $this->genAPIKey()["apikey"];

        $query = "INSERT INTO apikeys ";
        $query .= "(uid, apikey, status, created, updated) ";
        $query .= "VALUES(:uid, :apikey, :status, :created, :updated)";
        $nid = "-1";

        $param = array();
        $param[":uid"] = $_SESSION["login"]["id"];
        $param[":apikey"] = $new_apikey;
        $param[":status"] = 1;
        $param[":created"] = time();
        $param[":updated"] = time();

        $res = $db->runQuery($query, $param, $lang["api"]["setAPIkey_can_created_registry"], $nid);

        if($res == $lang["api"]["setAPIkey_can_created_registry"]) {
            throw new Exception($lang["api"]["setAPIkey_can_created_registry"], 1);
        } else {
            $response = array("success" => true, "apikey" => $new_apikey);
        }
        return $response;
    };
    $API->addMethod("setAPIkey", $setAPIkey);

    /*
    * Check API Key
    */
    $checkAPIKey = function($apikey = null) use($lang){
        $apikey = isset($apikey) ? $apikey : $_REQUEST["apikey"];
        $this->private["checkParameters"](array($apikey), true, null);
        $response = array("success" => false);

        $db = $this->db;
        $query = "SELECT apikey FROM apikeys WHERE apikey = :apikey AND status = '1' ";

        $res = $db->runQuery($query, array(":apikey"=>$apikey), $lang["api"]["checkAPIKey_cant_find_apikey"]);
        $res = $res->fetchAll(PDO::FETCH_ASSOC);

        if(isset($res[0]["apikey"])){
            $response = array("success" => true, "apikey" => $res[0]["apikey"]);
        } else {
            $response["error"] = $lang["api"]["checkAPIKey_cant_find_apikey"];
        }

        return $response;
    };
    $API->addMethod("checkAPIKey", $checkAPIKey);

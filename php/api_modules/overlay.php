<?php
    /*
    * Get overlays
    */
    $getOverlays = function() use($lang){
        $this->private["checkParameters"](array("param"), false, 50);
        $response = array("success" => false);
        $db = $this->db;

        if(gettype($_REQUEST["param"]) == "string"){
            $_REQUEST["param"] = json_decode($_REQUEST["param"], true);
        }

        if(isset($_REQUEST["param"]["reward"])){
            $query = "SELECT * FROM overlays WHERE reward = :reward";
            $res = $db->runQuery($query, array(":reward" => $_REQUEST["param"]["reward"]), $lang["api"]["overlay_cant_find_reg"]);
        } else if(isset($_REQUEST["param"]["uid"])){
            $query = "SELECT * FROM overlays WHERE uid = :uid";
            $res = $db->runQuery($query, array(":uid" => $_REQUEST["param"]["uid"]), $lang["api"]["overlay_cant_find_reg"]);
        } else {
            $query = "SELECT * FROM overlays";
            $res = $db->runQuery($query, array(), $lang["api"]["overlay_cant_find_reg"]);
        }

        if(is_array($res) && isset($res["error"])) {
            $response["error"] = $res["error"];
            return $response;
        }

        $response["overlays"] = $res->fetchAll(PDO::FETCH_ASSOC);
        $response["success"] = true;

        return $response;
    };
    $API->addMethod("getOverlays", $getOverlays);

    /*
    * Create overlay
    */
    $createOverlay = function() use($lang){
        $this->private["checkParameters"](array("reward", "config", "status"), false, 50);

        $response = array("success" => false);

        $db = $this->db;
        $query = "INSERT INTO overlays ";
        $query .= "(oid, uid, reward, config, status, created, updated) ";
        $query .= "VALUES(:oid, :uid, :reward, :config, :status, :created, :updated) ";
        $nid = "-1";

        $param = array();
        $param[":oid"] = time();
        $param[":uid"] = $_SESSION["login"]["id"];
        $param[":reward"] = $_REQUEST["reward"];
        $param[":config"] = $_REQUEST["config"];
        $param[":status"] = $_REQUEST["status"];
        $param[":created"] = time() + 2;
        $param[":updated"] = time() + 2;

        $res = $db->runQuery($query, $param, $lang["api"]["overlay_cant_create_reg"], $nid);
        if(is_array($res) && isset($res["error"])) {
            $response["error"] = $res["error"];
            return $response;
        }

        $_REQUEST["param"] = array("uid" => $_SESSION["login"]["id"]);
        $response = $this->getOverlays();

        return $response;
    };
    $API->addMethod("createOverlay", $createOverlay);

    /*
    * Update overlay
    */
    $updateOverlay = function() use($lang){
        $this->private["checkParameters"](array("oid"), false, 50);

        $response = array("success" => false);

        $db = $this->db;
        $query = "UPDATE overlays ";
        $query .= "SET updated = :updated ";
        if(isset($_REQUEST["reward"])) $query .= ",reward = :reward ";
        if(isset($_REQUEST["config"])) $query .= ",config = :config ";
        if(isset($_REQUEST["status"])) $query .= ",status = :status ";
        $query .= "WHERE oid = :oid ";

        $param = array();
        $param[":updated"] = time();
        $param[":oid"] = $_REQUEST["oid"];
        if(isset($_REQUEST["reward"])) $param[":reward"] = $_REQUEST["reward"];
        if(isset($_REQUEST["config"])) $param[":config"] = $_REQUEST["config"];
        if(isset($_REQUEST["status"])) $param[":status"] = $_REQUEST["status"];

        $res = $db->runQuery($query, $param, $lang["api"]["overlay_cant_update_reg"]);
        if(is_array($res) && isset($res["error"])) {
            $response["error"] = $res["error"];
            return $response;
        }

        $_REQUEST["param"] = array("uid" => $_SESSION["login"]["id"]);
        $response = $this->getOverlays();

        return $response;
    };
    $API->addMethod("updateOverlay", $updateOverlay);

    /*
    * Delete overlay
    */
    $deleteOverlay = function() use($lang){
        $this->private["checkParameters"](array("oid"), false, 50);

        $response = array("success" => false);

        $db = $this->db;
        $query = "DELETE FROM overlays ";
        $query .= "WHERE oid = :oid ";

        $param = array();
        $param[":oid"] = $_REQUEST["oid"];

        $res = $db->runQuery($query, $param, $lang["api"]["overlay_cant_delete_reg"]);
        if(is_array($res) && isset($res["error"])) {
            $response["error"] = $res["error"];
            return $response;
        }

        $_REQUEST["param"] = array("uid" => $_SESSION["login"]["id"]);
        $response = $this->getOverlays();

        return $response;
    };
    $API->addMethod("deleteOverlay", $deleteOverlay);

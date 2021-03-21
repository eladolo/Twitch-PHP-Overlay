<?php
    /*
    * Get users
    */
    $getUsers = function() use($lang){
        $this->private["checkParameters"](array("param"), false, 60);
        $response = array("success" => false);
        $db = $this->db;

        if(gettype($_REQUEST["param"]) == "string"){
            $_REQUEST["param"] = json_decode($_REQUEST["param"], true);
        }

        if(isset($_REQUEST["param"]["user"])){
            $query = "SELECT * FROM users WHERE user = :user";
            $res = $db->runQuery($query, array(":user" => $_REQUEST["param"]["user"]), $lang["api"]["user_cant_find_reg"]);
        } else if(isset($_REQUEST["param"]["uid"])){
            $query = "SELECT * FROM users WHERE id = :uid";
            $res = $db->runQuery($query, array(":uid" => $_REQUEST["param"]["uid"]), $lang["api"]["user_cant_find_reg"]);
        } else {
            $query = "SELECT * FROM users ORDER BY level DESC";
            $res = $db->runQuery($query, array(), $lang["api"]["user_cant_find_reg"]);
        }

        if(is_array($res) && isset($res["error"])) {
            $response["error"] = $res["error"];
            return $response;
        }

        $response["users"] = $res->fetchAll(PDO::FETCH_ASSOC);
        $response["success"] = true;

        return $response;
    };
    $API->addMethod("getUsers", $getUsers);

    /*
    * Check if user exists
    */
    $userExists = function($uid) use($lang){
        $this->private["checkParameters"](array($uid), true, null);

        $response = array("success" => false);

        $db = $this->db;
        $query = "SELECT * FROM users ";
        $query .= "WHERE id = :uid ";

        $res = $db->runQuery($query, array(":uid"=>$uid), $lang["api"]["user_cant_find_reg"]);
        if(is_array($res) && isset($res["error"])) {
            $response["error"] = $res["error"];
            return $response;
        }

        $res = $res->fetchAll(PDO::FETCH_ASSOC);
        if(isset($res[0]["id"])){
            $response["success"] = true;
        }

        return $response;
    };
    $API->addMethod("userExists", $userExists);

    /*
    * create user
    */
    $createUser = function() use($lang){
        $this->private["checkParameters"](array("id", "user", "name", "email", "img", "tkn", "refresh_tkn"), false, null);

        $response = array("success" => false);

        $db = $this->db;
        $query = "INSERT INTO users ";
        $query .= "(id, user, name, email, img, tkn, refresh_tkn, status, overlay, obs_host, obs_password, created, updated";
        if(isset($_REQUEST["level"])) $query .= ", level";
        $query .= ") ";
        $query .= "VALUES(:uid, :user, :name, :email, :img, :tkn, :refresh_tkn, :status, :overlay, :obs_host, :obs_password, :created, :updated";
        if(isset($_REQUEST["level"])) $query .= ", :level";
        $query .= ") ";
        $nid = "-1";

        $param = array();
        $param[":uid"] = $_REQUEST["id"];
        $param[":user"] = $_REQUEST["user"];
        $param[":name"] = $_REQUEST["name"];
        $param[":email"] = $_REQUEST["email"];
        $param[":img"] = $_REQUEST["img"];
        $param[":tkn"] = $_REQUEST["tkn"];
        $param[":refresh_tkn"] = $_REQUEST["refresh_tkn"];
        $param[":overlay"] = 1;
        $param[":status"] = 1;
        $param[":obs_host"] = "";
        $param[":obs_password"] = "";
        $param[":created"] = time();
        $param[":updated"] = time();
        if(isset($_REQUEST["level"])) $param[":level"] = $_REQUEST["level"];

        $res = $db->runQuery($query, $param, $lang["api"]["user_cant_create_reg"], $nid);
        if(is_array($res) && isset($res["error"])) {
            $response["error"] = $res["error"];
            return $response;
        }

        $response = $this->login();

        return $response;
    };
    $API->addMethod("createUser", $createUser);

    /*
    * update user
    */
    $updateUser = function() use($lang){
        $this->private["checkParameters"](array("id"), false, null);
        $response = array("success" => false);

        $db = $this->db;

        $query = "UPDATE users ";
        $query .= "SET updated = :updated ";
        if(isset($_REQUEST["user"])) $query .= ",user = :user ";
        if(isset($_REQUEST["name"])) $query .= ",name = :name ";
        if(isset($_REQUEST["email"])) $query .= ",email = :email ";
        if(isset($_REQUEST["img"])) $query .= ",img = :img ";
        if(isset($_REQUEST["tkn"])) $query .= ",tkn = :tkn ";
        if(isset($_REQUEST["refresh_tkn"])) $query .= ",refresh_tkn = :refresh_tkn ";
        if(isset($_REQUEST["level"])) $query .= ",level = :level ";
        if(isset($_REQUEST["obs_host"])) $query .= ",obs_host = :obs_host ";
        if(isset($_REQUEST["obs_password"])) $query .= ",obs_password = :obs_password ";
        if(isset($_REQUEST["overlay"])) $query .= ",overlay = :overlay ";
        if(isset($_REQUEST["status"])) $query .= ",status = :status ";
        $query .= "WHERE id = :uid ";

        $param = array();
        $param[":uid"] = $_REQUEST["id"];
        $param[":updated"] = time();
        if(isset($_REQUEST["user"])) $param[":user"] = $_REQUEST["user"];
        if(isset($_REQUEST["name"])) $param[":name"] = $_REQUEST["name"];
        if(isset($_REQUEST["email"])) $param[":email"] = $_REQUEST["email"];
        if(isset($_REQUEST["img"])) $param[":img"] = $_REQUEST["img"];
        if(isset($_REQUEST["tkn"])) $param[":tkn"] = $_REQUEST["tkn"];
        if(isset($_REQUEST["refresh_tkn"])) $param[":refresh_tkn"] = $_REQUEST["refresh_tkn"];
        if(isset($_REQUEST["level"])) $param[":level"] = $_REQUEST["level"];
        if(isset($_REQUEST["obs_host"])) $param[":obs_host"] = $_REQUEST["obs_host"];
        if(isset($_REQUEST["obs_password"])) $param[":obs_password"] = $_REQUEST["obs_password"];
        if(isset($_REQUEST["overlay"])) $param[":overlay"] = $_REQUEST["overlay"];
        if(isset($_REQUEST["status"])) $param[":status"] = $_REQUEST["status"];

        $res = $db->runQuery($query, $param, $lang["api"]["user_cant_update_reg"], $nid);
        if(is_array($res) && isset($res["error"])) {
            throw new Exception($res["error"], 1);
        }

        if(isset($_REQUEST["admin"])){
            $_REQUEST["param"] = array("");
            $response = $this->getUsers();
        } else {
            $_REQUEST["user"] = $_SESSION["login"]["user"];
            $response = $this->login();
        }

        return $response;
    };
    $API->addMethod("updateUser", $updateUser);

    /*
    * Delete user
    */
    $deleteUser = function() use($lang){
        $this->private["checkParameters"](array("id"), false, 60);

        $response = array("success" => false);

        $db = $this->db;
        $query = "DELETE FROM users ";
        $query .= "WHERE id = :uid ";

        $param = array("");
        $param[":uid"] = $_REQUEST["id"];

        $res = $db->runQuery($query, $param, $lang["api"]["user_cant_delete_reg"]);
        if(is_array($res) && isset($res["error"])) {
            $response["error"] = $res["error"];
            return $response;
        }

        $_REQUEST["param"] = array();
        $response = $this->getUsers();

        return $response;
    };
    $API->addMethod("deleteUser", $deleteUser);

	/*
    * login user
    */
    $login = function() use($lang){
        $this->private["checkParameters"](array("user"), false, null);

        $response = array("success" => false);

        $db = $this->db;
        $query = "SELECT * FROM users ";
        $query .= "WHERE user = :user ";
        $query .= "AND status = '1' ";

        $res = $db->runQuery($query, array(":user"=>$_REQUEST["user"]), $lang["api"]["user_cant_find_reg"]);
        if(is_array($res) && isset($res["error"])) {
            $response["error"] = $res["error"];
            return $response;
        }

        $res = $res->fetchAll(PDO::FETCH_ASSOC);
        if(isset($res[0]["id"])){
            $_SESSION["login"] = $res[0];
            $response["success"] = true;
        } else {
            $response["error"] = $lang["api"]["user_cant_find_reg"];
        }

        return $response;
    };
    $API->addMethod("login", $login);

    /*
    * logout user
    */
    $logout = function() use($lang){
        $response = array("success" => true, "logoutapp" => true);
        unlink($_SERVER["DOCUMENT_ROOT"] . '/php/states/appstate_' . session_id() . '.json');
        session_unset();     // unset $_SESSION variable for the run-time
        session_destroy();   // destroy session data in storage
        return $response;
    };
    $API->addMethod("logout", $logout);

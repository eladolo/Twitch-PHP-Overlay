<?php
	/*
    * CURL function
    */
    $requrl = function($param){
        if(!is_array($param)) return;
        $url = "";
        $header = array();
        $type = "GET";
        $fields = "";
        $user = "";

        ini_set('memory_limit', '-1');

        foreach ($param as $key => $value) {
            if($key == "url") $url = $value;
            if($key == "header") $header = $value;
            if($key == "type") $type = $value;
            if($key == "fields") $fields = $value;
            if($key == "user") $user = $value;
        }

        $ch = curl_init();

        if($type == "GET" && $fields !== ""){
            $url = $url . "?" . $fields;
        }

        if($user !== ""){
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $user);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 400); //timeout in seconds

        if(count($header) > 0){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        if($type == "POST"){
            curl_setopt($ch, CURLOPT_POST,true);
            if($fields !== "") curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        }

        if($type == "PUT" || $type == "DELETE"){
        	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        }

        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);

        if(curl_error($ch)){
        	$server_output = array(
        		"success" => false,
        		"response" => curl_error($ch)
        	);
        } else {
            $server_output = array(
            	"success" => true,
        		"response" => json_decode($server_output, true)
            );
        }

        curl_close($ch);

        return $server_output;
    };
    $API->addMethod("requrl", $requrl);
    $API->addMethod("requrl", $requrl, "private");

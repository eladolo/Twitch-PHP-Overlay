<?php
    /*
    * Function to list images
    */
    $getImages = function() use($imgCache) {
        $this->private["checkParameters"](array(), false, 1);
        $response = array("success" => false);
        $files_adds = array();

        try{
            $files = scandir($_SERVER["DOCUMENT_ROOT"] . '/img/custom');
            foreach($files as $file) {
                if($_SESSION["login"]["level"] < 80 && stripos($file, "_" . SITE_ID . "_.") === false) continue;
                if($_SESSION["login"]["level"] < 50 && stripos($file, "_" . $_SESSION["login"]["id"] . "_") === false) continue;
                if(stripos($file, ".gif") === false && stripos($file, ".jpg") === false && stripos($file, ".jpeg") === false && stripos($file, ".png") === false) continue;
                $tmp_ext = explode(".", $file);
                $tmp_ext = $tmp_ext[count($tmp_ext) - 1];

                if(!file_exists($_SERVER["DOCUMENT_ROOT"] . "/img/cached/" . $file)){
                    $imgCache->cache($_SERVER["DOCUMENT_ROOT"] . "/img/custom/" . $file);
                }
                array_push($files_adds, array("name" => $file, "type" => $tmp_ext, "route" => "/img/cached/"));
            }

            $response = array("success" => true, "images" => $files_adds);
        } catch(Exception $e) {
            $response["error"] = $e->getMessage();
        }

        return $response;
    };

    $API->addMethod("getImages", $getImages);

    /*
    * Function to upload images
    */
	$updloadImage = function(){
		$this->private["checkParameters"](array("type"), false, 1);
        //archivo temporal
        $tmp_path = $_FILES['file']['tmp_name'];
        //nombre temporal
        $day = time();
        $temporary = explode(".", $_FILES['file']['name']);
        $file_extension = end($temporary);

        $tmp_img = "img/custom/" . $_REQUEST["type"] . "_" . $day . "_" .  $_SESSION["login"]["id"] . "_" .  SITE_ID . "_." . $file_extension;
        //si existe la borro
        if(file_exists($tmp_img)){
            unlink($tmp_img);
        }
        //si hubo algun error rompo el flujo y mando error
        if(!move_uploaded_file($tmp_path, $tmp_img)){
            return array("error" => 99, "data" => array($tmp_img, $tmp_path));
        }

        $files_adds = $this->getImages()["images"];

        $response = array("success" => true, "images" => $files_adds);

        return $response;
	};
	$API->addMethod("updloadImage", $updloadImage);

    /*
    * Function to delete images
    */
    $delImage = function(){
        $this->private["checkParameters"](array("name"), false, 1);
        $response = array("success" => false);
        $tmp_nm = $_REQUEST["name"];

        try{
            $files_adds = array();
            unlink($_SERVER["DOCUMENT_ROOT"] . '/img/custom/' . $tmp_nm);
            unlink($_SERVER["DOCUMENT_ROOT"] . '/img/cached/' . $tmp_nm);

            $files = scandir($_SERVER["DOCUMENT_ROOT"] . '/img/custom');
            foreach($files as $file) {
                if(stripos($file, ".gif") === false && stripos($file, ".jpg") === false && stripos($file, ".png") === false) continue;
                $tmp_ext = explode(".", $file);
                $tmp_ext = $tmp_ext[count($tmp_ext) - 1];
                array_push($files_adds, array("name" => $file, "type" => $tmp_ext, "route" => "/img/custom/"));
            }

            $response = array("success" => true, "images" => $files_adds);
        } catch(Exception $e) {
            $response["error"] = $e->getMessage();
        }

        return $response;
    };
    $API->addMethod("delImage", $delImage);

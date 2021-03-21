<?php
	/*
    * Function to list private files
    */
    $getFiles = function(){
        $this->private["checkParameters"](array(), false, 15);
        $response = array("success" => false);
        $files_adds = array();

        try{
            $files = scandir($_SERVER["DOCUMENT_ROOT"] . '/php/files/dispatch');
            foreach($files as $file) {
                if($_SESSION["login"]["level"] < 80 && stripos($file, "_" . SITE_ID . "_.") === false) continue;
                if($_SESSION["login"]["level"] < 50 && stripos($file, "_" . $_SESSION["login"]["id"] . "_") === false) continue;
                if(stripos($file, ".zip") === false && stripos($file, ".jpg") === false && stripos($file, ".png") === false && stripos($file, ".mp3") === false && stripos($file, ".mp4") === false && stripos($file, ".wav") === false && stripos($file, ".pdf") === false) continue;
                if(stripos($file, "_pdownload_") === false) continue;
                $tmp_ext = explode(".", $file);
                $tmp_ext = $tmp_ext[count($tmp_ext) - 1];
                $tmp_config = array(
                    "name" => $file,
                    "type" => $tmp_ext,
                    "route" => "php/files/dispatch/",
                    "uid" => $_SESSION["login"]["id"],
                    "timestamp" => time()
                );
                $tmp_config["tkn"] = $this->private["encryptIt"](json_encode($tmp_config));
                array_push($files_adds, $tmp_config);
            }

            $response = array("success" => true, "files" => $files_adds);
        } catch(Exception $e) {
            $response["error"] = $e->getMessage();
        }

        return $response;
    };
    $API->addMethod("getFiles", $getFiles);

    /*
    * Function to list public files
    */
    $getMedia = function(){
        $this->private["checkParameters"](array(), false);
        $response = array("success" => false);
        $files_adds = array();

        try{
            $files = scandir($_SERVER["DOCUMENT_ROOT"] . '/php/files/dispatch');
            foreach($files as $file) {
                if(!isset($_REQUEST["galery"])){
                    if($_SESSION["login"]["level"] < 80 && stripos($file, "_" . SITE_ID . "_.") === false) continue;
                    if($_SESSION["login"]["level"] < 50 && stripos($file, "_" . $_SESSION["login"]["id"] . "_") === false) continue;
                } else {
                    if(stripos($file, "_" . SITE_ID . "_.") === false) continue;
                }
                if(stripos($file, ".zip") === false && stripos($file, ".jpg") === false && stripos($file, ".png") === false && stripos($file, ".mp3") === false && stripos($file, ".mp4") === false && stripos($file, ".wav") === false && stripos($file, ".youtube") === false && stripos($file, ".pdf") === false) continue;
                if(stripos($file, "_media_") === false) continue;
                $tmp_ext = explode(".", $file);
                $tmp_ext = $tmp_ext[count($tmp_ext) - 1];
                $tmp_config = array(
                    "name" => $file,
                    "type" => $tmp_ext,
                    "route" => "php/files/dispatch/",
                    "media" => true,
                    "uid" => $_SESSION["login"]["id"],
                    "timestamp" => time()
                );
                $tmp_config["tkn"] = $this->private["encryptIt"](json_encode($tmp_config));
                array_push($files_adds, $tmp_config);
            }

            $response = array("success" => true, "media" => $files_adds);
        } catch(Exception $e) {
            $response["error"] = $e->getMessage();
        }

        return $response;
    };
    $API->addMethod("getMedia", $getMedia);

    /*
    * Function to stream local private files
    */
    $dispatchFile = function() use($lang){
        $this->private["checkParameters"](array("tkn"), false);

        try{
            $file_in = $this->private["decryptIt"]($_REQUEST["tkn"]);
            $file_in = json_decode($file_in);
        } catch(Exception $ex) {
            throw new Exception($lang["api"]["file_handlers_invalid_token"], 1);
        }

        if(!isset($file_in->media)){
            $this->private["checkParameters"](array("tkn"), false, 1);
            if(time() - $file_in->timestamp >= (60 * 120)){
                throw new Exception($lang["api"]["file_handlers_expired_token"], 1);
            }

            if($file_in->uid != $_SESSION["login"]["id"]){
                throw new Exception($lang["api"]["file_handlers_invalid_user"], 1);
            }
        }

        if(stripos($file_in->name, ".youtube") !== false){
            $tmp_youtube = json_decode(file_get_contents($file_in->route . $file_in->name), true);
            $video_url = $this->getYoutubeURL($tmp_youtube["id"])["url"];
            return array("youtube" => true, "url" => $video_url);
        } else {
            $file_in = $file_in->route . $file_in->name;
        }

        $filesize64 = function($file){
            static $iswin;
            if (!isset($iswin)) {
                $iswin = (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN');
            }

            static $exec_works;
            if (!isset($exec_works)) {
                $exec_works = (function_exists('exec') && !ini_get('safe_mode') && @exec('echo EXEC') == 'EXEC');
            }

            // try a shell command
            if ($exec_works) {
                $cmd = ($iswin) ? "for %F in (\"$file\") do @echo %~zF" : "stat -c%s \"$file\"";
                @exec($cmd, $output);
                if (is_array($output) && ctype_digit($size = trim(implode("\n", $output)))) {
                    return $size;
                }
            }

            // try the Windows COM interface
            if ($iswin && class_exists("COM")) {
                try {
                    $fsobj = new COM('Scripting.FileSystemObject');
                    $f = $fsobj->GetFile( realpath($file) );
                    $size = $f->Size;
                } catch (Exception $e) {
                    $size = null;
                }
                if (ctype_digit($size)) {
                    return $size;
                }
            }

            // if all else fails
            return filesize($file);
        };

        $rangeDownload = function($file,$size){
            $fp = @fopen($file, 'rb');
            $length = $size;           // Content length
            $start  = 0;               // Start byte
            $end    = $size - 1;       // End byte
            // Now that we've gotten so far without errors we send the accept range header
            /* At the moment we only support single ranges.
            * Multiple ranges requires some more work to ensure it works correctly
            * and comply with the spesifications: http://www.w3.org/Protocols/rfc2616/rfc2616-sec19.html#sec19.2
            *
            * Multirange support annouces itself with:
            * header('Accept-Ranges: bytes');
            *
            * Multirange content must be sent with multipart/byteranges mediatype,
            * (mediatype = mimetype)
            * as well as a boundry header to indicate the various chunks of data.
            */
            header('Accept-Ranges: bytes');
            header("Accept-Ranges: 0-$length");
            // multipart/byteranges
            // http://www.w3.org/Protocols/rfc2616/rfc2616-sec19.html#sec19.2
            if (isset($_SERVER['HTTP_RANGE'])) {
                $c_start = $start;
                $c_end   = $end;
                // Extract the range string
                list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
                // Make sure the client hasn't sent us a multibyte range
                if (strpos($range, ',') !== false) {
                    // (?) Shoud this be issued here, or should the first
                    // range be used? Or should the header be ignored and
                    // we output the whole content?
                    header('HTTP/1.1 416 Requested Range Not Satisfiable');
                    header("Content-Range: bytes $start-$end/$size");
                    // (?) Echo some info to the client?
                    exit;
                }
                // If the range starts with an '-' we start from the beginning
                // If not, we forward the file pointer
                // And make sure to get the end byte if spesified
                if ($range0 == '-') {
                    // The n-number of the last bytes is requested
                    $c_start = $size - substr($range, 1);
                }else {
                    $range  = explode('-', $range);
                    $c_start = $range[0];
                    $c_end   = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
                }
                /* Check the range and make sure it's treated according to the specs.
                 * http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html
                 */
                // End bytes can not be larger than $end.
                $c_end = ($c_end > $end) ? $end : $c_end;
                // Validate the requested range and return an error if it's not correct.
                if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
                    header('HTTP/1.1 416 Requested Range Not Satisfiable');
                    header("Content-Range: bytes $start-$end/$size");
                    // (?) Echo some info to the client?
                    exit;
                }
                $start  = $c_start;
                $end    = $c_end;
                $length = $end - $start + 1; // Calculate new content length
                fseek($fp, $start);
                header('HTTP/1.1 206 Partial Content');
            }
            // Notify the client the byte range we'll be outputting
            header("Content-Range: bytes $start-$end/$size");
            header("Content-Length: $length");
            // Start buffered download
            $buffer = 1024 * 8;
            while(!feof($fp) && ($p = ftell($fp)) <= $end) {
                if ($p + $buffer > $end) {
                    // In case we're only outputtin a chunk, make sure we don't
                    // read past the length
                    $buffer = $end - $p + 1;
                }
                set_time_limit(0); // Reset time limit for big files
                echo fread($fp, $buffer);
                @ob_flush();
                flush(); // Free up memory. Otherwise large files will trigger PHP's memory limit.
            }
            fclose($fp);
        };

        $size = $filesize64($file_in);

        list($name, $type) = explode("[.]", str_replace("../","",$file_in), 2);

        switch($type) {
            case "zip":
                $content = "application/zip";
                break;
            case "m4v":
                $content = "video/x-m4v";
                break;
            case "3gp":
            case "3gpp":
                $content = "video/3gpp";
                break;
            case "3g2":
            case "3gp2":
                $content = "video/3gpp2";
                break;
            case "sdv":
                $content = "video/sd-video";
                break;
            case "mp4":
                $content = "video/mp4";
                break;
            case "mpg":
            case "mpeg":
            case "m1s":
            case "m1v":
            case "m1a":
            case "m75":
            case "m15":
            case "mp2":
            case "mpm":
            case "mpv":
            case "mpa":
                $content = "video/x-mpeg";
                break;
            case "mov":
            case "qt":
            case "mqv":
                $content = "video/quicktime";
                break;
            case "flc":
            case "fli":
            case "cel":
                $content = "video/flc";
                break;
            case "asf":
            case "asx":
                $content = "video/x-ms-asf";
                break;
            case "wm":
                $content = "video/x-ms-wm";
                break;
            case "wax":
                $content = "video/x-ms-wax";
                break;
            case "wmv":
                $content = "video/x-ms-wmv";
                break;
            case "wvx":
                $content = "video/x-ms-wvx";
                break;
            case "divx":
            case "div":
                $content = "video/divx";
                break;
            case "jpg":
            case "jpeg":
            case "gif":
            case "png":
                $content = "image/jpeg";
                break;
            case "wav":
            case "mp3":
            case "flac":
            case "ogg":
            case "aiff":
                $content = "audio/mpeg";
                break;
        }

        $fm = @fopen($file_in,'rb');

        if (!$fm) {
            // You can also redirect here
            //header ("HTTP/1.0 404 Not Found");
            header("Location: /$file_in ");
        } else {
            // Check if it's a HTTP range request
            if (isset($_SERVER['HTTP_RANGE'])) {
                fclose($fm);
                header('Content-type: ' . $content);
                $rangeDownload($file_in,$size);
            } else { // It's not a range request, output the file anyway*/
                $nfile = explode("/",$file_in);
                header('Content-type: ' . $content);
                header('Content-Length: '. $size);
                if(!isset($_REQUEST["embedded"]))header('Content-Disposition: attachment; filename="'. $nfile[(count($nfile)-1)] .'"');
                fseek($fm, 0);
                $end = $size;
                $buffer = 1024 * 128;
                while(!feof($fm) && ($p = ftell($fm)) <= $end) {
                    if ($p + $buffer > $end) {
                        // In case we're only outputtin a chunk, make sure we don't
                        // read past the length
                        $buffer = $end - $p + 1;
                    }

                    set_time_limit(0); // Reset time limit for big files
                    echo fread($fm, $buffer);
                    @ob_flush();
                    flush(); // Free up memory. Otherwise large files will trigger PHP's memory limit.
                }
                fclose($fm);
            }
        }
    };
    $API->addMethod("dispatchFile", $dispatchFile);

    /*
    * Function to check media type
    */
    $mediaType = function() use($lang){
        $this->private["checkParameters"](array("media"), false);

        try{
            $file_in = $this->private["decryptIt"]($_REQUEST["media"]);
            $file_in = json_decode($file_in);
        } catch(Exception $ex) {
            throw new Exception($lang["api"]["file_handlers_invalid_token"], 1);
        }

        if(stripos($file_in->name, ".youtube") !== false){
            $tmp_youtube = json_decode(file_get_contents($file_in->route . $file_in->name), true);
            $video_url = $this->getYoutubeURL($tmp_youtube["id"])["url"];
            return array("type" => "youtube", "url" => $video_url);
        } else {
            return array("type" => $file_in->type);
        }
    };
    $API->addMethod("mediaType", $mediaType);

    /*
    * Function to upload files
    */
    $uploadFiles = function(){
        $this->private["checkParameters"](array("type"), false, 15);
        $response = array("success" => false);
        try{
            //archivo temporal
            $tmp_path = $_FILES['file']['tmp_name'];
            //nombre temporal
            $day = time();
            $tmp_name = explode(".", $_FILES['file']['name']);
            $file_extension = end($tmp_name);

            $tmp_file = $_SERVER["DOCUMENT_ROOT"] . "/php/files/dispatch/" . $tmp_name[0] . "_" . $_REQUEST["type"] . "_" . $day . "_" .  $_SESSION["login"]["id"] . "_" .  SITE_ID . "_." . $file_extension;
            //si existe lo borro
            if(file_exists($tmp_file)){
                unlink($tmp_file);
            }
            //si hubo algun error rompo el flujo y mando error
            if(move_uploaded_file($tmp_path, $tmp_file)){
                if($_REQUEST["type"] == "media"){
                    $response = $this->getMedia();
                } else {
                    $response = $this->getFiles();
                }
            } else {
                $response["error"] = 99;
                $response["data"] = array($tmp_file, $tmp_path);
            }
        } catch (Exception $ex) {
            $response["error"] = $ex->getMessage();
        }

        return $response;
    };
    $API->addMethod("uploadFiles", $uploadFiles);

    /*
    * Function to create youtube files
    */
    $uploadYoutube = function() use($lang){
        $this->private["checkParameters"](array("youtubeFile"), false, 15);
        $response = array("success" => false);
        try{
            $url = '';
            $title = '';
            $author = '';
            $description = '';
            $image = '';
            //extraigo ID de youtube
            $yt_id = explode("?", $_REQUEST["youtubeFile"]);
            parse_str($yt_id[1], $url_gets);
            $yt_id = $url_gets["v"];
            //obtengo informacion del video
            $api_url = 'https://www.youtube.com/get_video_info?video_id=' . $yt_id;
            parse_str(file_get_contents($api_url), $youtubeData);
            $data = json_decode($youtubeData["player_response"], true);
            if(!isset($data['streamingData'])){
                throw new Exception($lang["api"]["file_handlers_no_stream_data"], 1);
            }
            $title = $data['videoDetails']['title'];
            $author = $data['videoDetails']['author'];
            $description = $data['videoDetails']['shortDescription'];
            $image = $data['videoDetails']['thumbnail']['thumbnails'][count($data['videoDetails']['thumbnail']['thumbnails']) - 1];
            $tmp_data = array(
                "original" => $_REQUEST["youtubeFile"],
                "title" => $title,
                "author" => $author,
                "description" => $description,
                "image" => $image,
                "id" => $yt_id
            );
            //nombre de archivo
            $day = time();
            $tmp_file = $_SERVER["DOCUMENT_ROOT"] . "/php/files/dispatch/" . $title . "_media_" . $day . "_" .  $_SESSION["login"]["id"] . "_" .  SITE_ID . "_.youtube";
            //si existe lo borro
            if(file_exists($tmp_file)){
                unlink($tmp_file);
            }
            //si hubo algun error rompo el flujo y mando error
            if(file_put_contents($tmp_file, json_encode($tmp_data))){
                $response = $this->getMedia();
            } else {
               throw new Exception($lang["api"]["uploadYoutube_cant_write_metadata"], 1);
            }
        } catch (Exception $ex) {
            $response["error"] = $ex->getMessage();
        }

        return $response;
    };
    $API->addMethod("uploadYoutube", $uploadYoutube);

    /*
    * Function to get youtube url file
    */
    $getYoutubeURL = function($yt_id) use($lang){
        $this->private["checkParameters"](array($yt_id), true);
        $response = array("success" => false);

        try{
            $api_url = 'https://www.youtube.com/get_video_info?video_id=' . $yt_id;
            parse_str(file_get_contents($api_url), $youtubeData);
            $data = json_decode($youtubeData["player_response"], true);
            if(!isset($data['streamingData'])){
                throw new Exception($lang["api"]["file_handlers_no_stream_data"], 1);
            }
            $url = $data['streamingData']['formats'][count($data['streamingData']['formats']) - 1]['url'];

            $response["success"] = true;
            $response["url"] = $url;
        } catch(Exception $e) {
            $response["error"] = $e->getMessage();
        }

        return $response;
    };
    $API->addMethod("getYoutubeURL", $getYoutubeURL);

    /*
    * Function to upload private files
    */
    $refreshFiles = function(){
        $this->private["checkParameters"](array("type"), false, 15);
        $response = array("success" => false);
        try{
            $folder = $_REQUEST["type"] == "custom" ? 'image' : "private";
            $folder = $folder == "custom" ? 'media' : $folder;
            $files = scandir($_SERVER["DOCUMENT_ROOT"] . "/php/files/uploads/" . site_req . "/" . $folder . "/");
            foreach($files as $file) {
                if(stripos($file, ".zip") === false && stripos($file, ".gif") === false && stripos($file, ".jpg") === false && stripos($file, ".png") === false && stripos($file, ".mp3") === false && stripos($file, ".mp4") === false && stripos($file, ".wav") === false) continue;
                //nombre temporal
                $day = time();
                $tmp_name = explode(".", $file);
                $file_extension = end($tmp_name);

                $tmp_file = "/php/files/dispatch/" . $tmp_name[0] . "_" . $_REQUEST["type"] . "_" . $day . "_" .  $_SESSION["login"]["id"] . "_" .  SITE_ID . "_." . $file_extension;
                $tmp_file = $_REQUEST["type"] == "custom" ? "/img/custom/" . $_REQUEST["type"] . "_" . $day . "_" .  $_SESSION["login"]["id"] . "_" .  SITE_ID . "_." . $file_extension : $tmp_file;
                //si existe lo borro
                if(file_exists($tmp_file)){
                    unlink($tmp_file);
                }
                @rename($_SERVER["DOCUMENT_ROOT"] . '/php/files/uploads/' . $folder . "/" . $file, $_SERVER["DOCUMENT_ROOT"] . $tmp_file);
            }

            if($_REQUEST["type"] == "media"){
                $response = $this->getMedia();
            } elseif($_REQUEST["type"] == "custom"){
                $response = $this->getImages();
            } else {
                $response = $this->getFiles();
            }
        } catch (Exception $ex) {
            $response["error"] = $ex->getMessage();
        }

        return $response;
    };
    $API->addMethod("refreshFiles", $refreshFiles);

    /*
    * Function to delete private files
    */
    $delFile = function(){
        $this->private["checkParameters"](array("name"), false, 50);
        $response = array("success" => false);
        $tmp_nm = $_REQUEST["name"];

        try{
            unlink($_SERVER["DOCUMENT_ROOT"] . '/php/files/dispatch/' . $tmp_nm);

            if($_REQUEST["type"] == "media"){
                $response = $this->getMedia();
            } else {
                $response = $this->getFiles();
            }
        } catch(Exception $e) {
            $response["error"] = $e->getMessage();
        }

        return $response;
    };
    $API->addMethod("delFile", $delFile);

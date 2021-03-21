<?php
	$genLanguage = function() use($lang){
		$this->private["checkParameters"](array("target"), false, 60);
        $response = array("success" => false);

        $target_lang = $_SERVER["DOCUMENT_ROOT"] . "/php/lang/" . $_REQUEST["target"] . "/" . $_REQUEST["target"] . ".php";

        if(!is_dir($_SERVER["DOCUMENT_ROOT"] . "/php/lang/" . $_REQUEST["target"] . '/')){
            mkdir($_SERVER["DOCUMENT_ROOT"] . "/php/lang/" . $_REQUEST["target"] . '/');
        }

        $temp_lang = array(
            "api" => array(),
            "views" => array()
        );
        foreach ($lang as $category => $cat_content) {
            foreach ($cat_content as $varname => $value) {
                $temp_translation = $this->translateWord($value, $_REQUEST["target"]);
                if(!$temp_translation["success"]){
                    $temp_translation = $value;
                } else {
                    $temp_translation = $temp_translation["translation"];
                }
                $temp_lang[$category][$varname] = $temp_translation;
            }
        }

        file_put_contents($target_lang, '<?php $lang = ' . var_export($temp_lang, true) . ';');
        $response["success"] = true;

        return $response;
	};
	$API->addMethod("genLanguage", $genLanguage);

    $translateWord = function($text, $target){
        $this->checkParameters(array($text, $target), true, 60);
        $response = array("success" => false);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://google-translate1.p.rapidapi.com/language/translate/v2",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "q=" . $text . "&source=" . LANG . "&target=" . $target,
            CURLOPT_HTTPHEADER => [
                "accept-encoding: application/gzip",
                "content-type: application/x-www-form-urlencoded",
                "x-rapidapi-host: google-translate1.p.rapidapi.com",
                "x-rapidapi-key: 93d1cb3828mshb37d7257a678010p17acb1jsn7473ba231d0a"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if($err){
            $response = array("success" => false, "error" => $err);
        }

        $response = json_decode($response, true);
        if(isset($response["data"])){
            $response = array("success" => true, "translation" => $response["data"]["translations"][0]["translatedText"]);
        } else {
            $response = array("success" => false, "error" => $response["message"]);
        }
        return $response;
    };
    $API->addMethod("translateWord", $translateWord);

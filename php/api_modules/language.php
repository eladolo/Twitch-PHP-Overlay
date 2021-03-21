<?php
	$genLanguage = function() use($lang){
		$this->private["checkParameters"](array("target"), false, 60);
        $response = array("success" => false);

        $target_lang = $_SERVER["DOCUMENT_ROOT"] . "/php/lang/" . $_REQUEST["target"] . "/" . $_REQUEST["target"] . ".php";

        if(!is_dir($_SERVER["DOCUMENT_ROOT"] . "/php/lang/" . $_REQUEST["target"] . '/')){
            mkdir($_SERVER["DOCUMENT_ROOT"] . "/php/lang/" . $_REQUEST["target"] . '/');
        }

        file_put_contents($target_lang, '<?php $lang = ' . var_export($lang, true) . ';');
        $response["success"] = true;

        return $response;
	};
	$API->addMethod("genLanguage", $genLanguage);

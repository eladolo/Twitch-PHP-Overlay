<?php
	${{dummy}} = function(){
		$this->checkParameters(array("{{dummy}}"), false);
        $response = array("success" => false);

        if($_REQUEST["{{dummy}}"] == "foo"){
            $response = array("success" => true, "data" => "Esta es una respuesta dummy.", "param" => $_REQUEST["{{dummy}}"]);
        } else {
            $response["error"] = "Respuesta incorrecta. Empieza con f y termina o y tiene 3 letras en total";
        }

        return $response;
	};
	$API->addMethod("{{dummy}}", ${{dummy}});
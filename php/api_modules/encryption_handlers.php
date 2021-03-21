<?php
	/*
    * 2 ways encryptions functions
    */
    $encryptIt = function($q) use ($checkParameters, $Crypto){
        $checkParameters(array($q), true, 0);

        $cryptKey  = SECRET_SHARE;
        $qEncoded  = base64_encode( $Crypto::encryptWithPassword($q, md5( md5( $cryptKey ) )) );
        return($qEncoded);
    };
    $API->addMethod("encryptIt", $encryptIt, "private");

    $decryptIt = function($q) use ($checkParameters, $Crypto){
        $checkParameters(array($q), true, 0);
        $cryptKey  = SECRET_SHARE;
        $qDecoded  = rtrim( $Crypto::decryptWithPassword(base64_decode( $q ), md5( md5( $cryptKey ) )), "\0");
        return($qDecoded);
    };
    $API->addMethod("decryptIt", $decryptIt, "private");

    /*
    * JWT functions
    */
    $base64UrlEncode = function($data) use ($checkParameters){
        $checkParameters(array($data), true, 0);
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    };
    $API->addMethod("base64UrlEncode", $base64UrlEncode, "private");

    $base64UrlDecode = function($data) use ($checkParameters){
        $checkParameters(array($data), true, 0);
        $urlUnsafeData = str_replace(['-', '_'], ['+', '/'], $data);
        $paddedData = str_pad($urlUnsafeData, strlen($data) % 4, '=', STR_PAD_RIGHT);

        return base64_decode($paddedData);
    };
    $API->addMethod("base64UrlDecode", $base64UrlDecode, "private");

    $signJWT = function($array) use ($checkParameters, $base64UrlEncode){
        $checkParameters(array($array), true, 0);
        // Create token header as a JSON string
        $header = json_encode(array('alg' => 'HS256', 'typ' => 'JWT'));
        // Create token payload as a JSON string
        $payload = json_encode($array);
        // Encode Header to Base64Url String
        $base64UrlHeader = $base64UrlEncode($header);
        // Encode Payload to Base64Url String
        $base64UrlPayload = $base64UrlEncode($payload);
        // Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, SECRET_PUBLIC, true);
        // Encode Signature to Base64Url String
        $base64UrlSignature = $base64UrlEncode($signature);
        // Create JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        return $jwt;
    };
    $API->addMethod("signJWT", $signJWT, "private");

    $decodeJWT = function($jwt) use ($checkParameters, $base64UrlDecode){
        $checkParameters(array($jwt), true, 0);
        list($headerEncoded, $payloadEncoded, $signatureEncoded) = explode('.', $jwt);
        $jwt = $base64UrlDecode($payloadEncoded);

        return $jwt;
    };
    $API->addMethod("decodeJWT", $decodeJWT, "private");

    $verifyJWT = function($jwt) use ($checkParameters, $base64UrlEncode){
        $checkParameters(array($jwt), true, 0);
        list($headerEncoded, $payloadEncoded, $signatureEncoded) = explode('.', $jwt);
        $dataEncoded = $headerEncoded . "." . $payloadEncoded;
        $rawSignature = hash_hmac('sha256', $dataEncoded, SECRET_PUBLIC, true);
        $testSignature = $base64UrlEncode($rawSignature);

        return hash_equals($signatureEncoded, $testSignature);
    };
    $API->addMethod("verifyJWT", $verifyJWT, "private");

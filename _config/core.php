<?php

// Application Params
error_reporting(E_ALL);
//error_reporting(0); <-- to deactivate
date_default_timezone_set('Europe/Zurich');

$token_conf = array(
    "secret" => 'lkiuerf@oja78781[ojaklj]JHjksa122:891',
    "algorithm" => array('HS256'),
    "issuer" => 'Official Minska API',
    "issuedAt" => time(),
    "notBefore" => time(),
    "expireAt" => time() + (604800),
);

function authenticate(){

    if (isset(getallheaders()['authorization'])) {
        list($type, $data) = explode(" ", getallheaders()['authorization'], 2);
    } else if(isset($_SERVER['authorization'])) {
        list($type, $data) = explode(" ", $_SERVER['authorization'], 2);
    } else if(isset($_SERVER['PHP_AUTH_DIGEST'])) {
        list($type, $data) = explode(" ", $_SERVER['PHP_AUTH_DIGEST'], 2);
    } else {
        returnForbidden("No token");
    }

    if (strcasecmp($type, "Bearer") == 0) {
        return $data;
    } else {
        returnForbidden("Token incorrectly formed");
    }

}

function returnSuccess($data = false){
    http_response_code(200);
    if($data){
        echo json_encode(array(
            "status" => "success",
            "message" => "Request successfully handled",
            "content" => $data
        ));
    } else {
        echo json_encode(array(
            "status" => "success",
            "message" => "Request successfully handled (Returning no content)"
        ));
    }
    die();
}

function returnNoData(){
    http_response_code(204);
    echo json_encode(array(
    "status" => "success",
    "message" => "Request successfully handled but no data found"
    ));
    die();
}

function returnForbidden($reason = false){
    http_response_code(403);
    if($reason){
        echo json_encode(array(
        "status" => "unauthorized",
        "message" => "User is not authorized to perform this action",
        "reason" => $reason
        ));
    } else {
        echo json_encode(array(
        "status" => "unauthorized",
        "message" => "User is not authorized to perform this action"
        ));
    }
    die();
}

function returnBadRequest($reason = false){
    http_response_code(400);
    if($reason){
        echo json_encode(array(
        "status" => "failed",
        "message" => "Bad Request: Values are wrong or missing.",
        "reason" => $reason
        ));
    } else {
        echo json_encode(array(
        "status" => "failed",
        "message" => "Bad Request: Values are wrong or missing."
        ));
    }
    die();
}

function returnError($reason = false){
    http_response_code(500);
    if($reason){
        echo json_encode(array(
        "status" => "error",
        "message" => "An internal error occured",
        "reason" => $reason
        ));
    } else {
        echo json_encode(array(
        "status" => "error",
        "message" => "An internal error occured",
        ));
    }
    die();
}

?>

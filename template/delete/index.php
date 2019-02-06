<?php

// ---- Initialize Default
include_once '../../_config/headers.php';
include_once '../../_config/core.php';
include_once '../../_config/database.php';
include_once '../../_config/libs/php-jwt-master/src/BeforeValidException.php';
include_once '../../_config/libs/php-jwt-master/src/ExpiredException.php';
include_once '../../_config/libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../_config/libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
$database = new Database();
$db = $database->connect();
$data = file_get_contents("php://input");
// ---- End of Initialize Default

// ---- Authenticate Request
$token = authenticate();
// ---- End of Authenticate Request

// ---- Get needed Objects
include_once '../../_config/objects/template.php';
$template = new Template($db);
// ---- End of Get needed Objects

try {

    $decoded = JWT::decode($token, $token_conf['secret'], $token_conf['algorithm']);
    $template->userid = $decoded->data->id;
    $template->id = $data;

    try {
        $template->delete();
        returnSuccess();
    } catch (Exception $e) {
        returnError($e);
    }

} catch(Exception $e) {
    returnForbidden($e);
}



<?php

// ---- Include Defaults
include_once '../../_config/headers.php';
include_once '../../_config/database.php';
include_once '../../_config/core.php';
include_once '../../_config/libs/php-jwt-master/src/BeforeValidException.php';
include_once '../../_config/libs/php-jwt-master/src/ExpiredException.php';
include_once '../../_config/libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../_config/libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

// ---- Initialize Default
$database = new Database();
$db = $database->connect();
$data = json_decode(file_get_contents("php://input"));

// ---- Include Object
include_once '../../_config/objects/weight.php';
$weight = new Weight($db);
// ---- End of default Configuration

$token = isset($data->token) ? $data->token : "";

if($token){

    try {

        $decoded = JWT::decode($token, $token_conf['secret'], $token_conf['algorithm']);
        $weight->userid = $decoded->data->id;
        $weight->id = $data->id;

        try {
            $weight->delete();
            returnSuccess();
        } catch (Exception $e) {
            returnError($e);
        }

    } catch(Exception $e) {
        returnForbidden($e);
    }

} else {
    returnBadRequest();
}

?>
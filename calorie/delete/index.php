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
include_once '../../_config/objects/calorie.php';
$calorie = new Calorie($db);
// ---- End of default Configuration

$jwt=isset($data->jwt) ? $data->jwt : "";

if($jwt){

    try {

        $decoded = JWT::decode($jwt, $key, array('HS256'));
        $calorie->userid = $decoded->data->id;
        $calorie->id = $data->id;

        try {
            $calorie->delete();
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
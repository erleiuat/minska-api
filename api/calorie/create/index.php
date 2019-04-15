<?php

// ---- Initialize Default
include_once '../../_config/headers.php';
include_once '../../_config/core.php';
include_once '../../_config/database.php';
include_once '../../_config/libraries/php-jwt-master/src/BeforeValidException.php';
include_once '../../_config/libraries/php-jwt-master/src/ExpiredException.php';
include_once '../../_config/libraries/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../_config/libraries/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
$database = new Database();
$db = $database->connect();
$data = json_decode(file_get_contents("php://input"));
// ---- End of Initialize Default

// ---- Authenticate Request
$token = authenticate();
// ---- End of Authenticate Request

// ---- Include Object
include_once '../../_config/objects/calorie.php';
$calorie = new Calorie($db);
// ---- End of default Configuration

try {

    $decoded = JWT::decode($token, $token_conf['secret'], $token_conf['algorithm']);
    $calorie->userid = $decoded->data->id;
    $calorie->title = $data->title;
    $calorie->calories = $data->calories;
    $calorie->amount = $data->amount;
    $calorie->date = $data->date;

    try {
        $calorie->create();
        returnSuccess($calorie->id);
    } catch (Exception $e) {
        returnError($e);
    }

} catch (Exception $e) {
    returnForbidden($e);
}



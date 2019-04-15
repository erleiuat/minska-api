<?php

// ---- Initialize Default
include_once '../../_config/settings.php';
include_once '../../_config/core.php';
include_once '../../_config/headers.php';
include_once '../../_config/database.php';
include_once '../../_config/validate.php';
include_once '../../_config/libraries/php-jwt-master/src/BeforeValidException.php';
include_once '../../_config/libraries/php-jwt-master/src/ExpiredException.php';
include_once '../../_config/libraries/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../_config/libraries/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
$database = new Database();
$db = $database->connect($db_conf);
$data = json_decode(file_get_contents("php://input"));
// ---- End of Initialize Default

// ---- Authenticate Request
try {
    $token = authenticate();
    $decoded = JWT::decode($token, $token_conf['secret'], $token_conf['algorithm']);
} catch (Exception $e) {
    returnForbidden();
}
// ---- End of Authenticate Request

// ---- Get needed Objects
include_once '../../_config/objects/calorie.php';
$calorie = new Calorie($db);
// ---- End of Get needed Objects


try {

    $calorie->userid = $decoded->data->id;
    $calorie->title = val_string($data->title, 0, 45);
    $calorie->calories = val_number($data->calories, 1, 9999);
    $calorie->amount = val_number($data->amount, 1, 9999);
    $calorie->date = val_string($data->date, 1, 20);

    try {
        $calorie->create();
        returnSuccess($calorie->id);
    } catch (Exception $e) {
        returnError($e);
    }

} catch (Exception $e) {
    returnForbidden($e);
}

<?php

// ---- Initialize Default
include_once '../../../_config/settings.php';
include_once '../../../_config/core.php';
include_once '../../../_config/headers.php';
include_once '../../../_config/database.php';
include_once '../../../_config/validate.php';
include_once '../../../_config/libraries/php-jwt-master/src/BeforeValidException.php';
include_once '../../../_config/libraries/php-jwt-master/src/ExpiredException.php';
include_once '../../../_config/libraries/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../../_config/libraries/php-jwt-master/src/JWT.php';
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
include_once '../../../_config/objects/calorie.php';
$calorie = new Calorie($db);
// ---- End of Get needed Objects


try {

    $calorie->userid = $decoded->data->id;
    $calorie->date = val_string($data->date,1,20);
    $stmt = $calorie->readByDay();
    $num = $stmt->rowCount();

    if ($num>0) {

        $calories_arr = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            extract($row);
            $calorie_item = array(
                "id" => $id,
                "title" => $title,
                "calories" => $calories,
                "amount" => $amount
            );
            array_push($calories_arr, $calorie_item);

        }
        returnSuccess($calories_arr);

    } else {
        returnNoData();
    }

} catch (Exception $e) {
    returnForbidden($e);
}

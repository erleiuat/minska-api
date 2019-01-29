<?php

// ---- Initialize Default
include_once '../../../_config/headers.php';
include_once '../../../_config/core.php';
include_once '../../../_config/database.php';
include_once '../../../_config/libs/php-jwt-master/src/BeforeValidException.php';
include_once '../../../_config/libs/php-jwt-master/src/ExpiredException.php';
include_once '../../../_config/libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../../_config/libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
$database = new Database();
$db = $database->connect();
$data = file_get_contents("php://input");
// ---- End of Initialize Default

// ---- Authenticate Request
$token = authenticate();
// ---- End of Authenticate Request

// ---- Get needed Objects
include_once '../../../_config/objects/calorie.php';
$calorie = new Calorie($db);
// ---- End of Get needed Objects

try {

    $decoded = JWT::decode($token, $token_conf['secret'], $token_conf['algorithm']);
    $calorie->userid = $decoded->data->id;
    $calorie->date = $data;

    $stmt = $calorie->readByDay();
    $num = $stmt->rowCount();

    if($num>0){

        $calories_arr=array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

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

} catch(Exception $e) {
    returnForbidden($e);
}

?>

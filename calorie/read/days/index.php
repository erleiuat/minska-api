<?php

// ---- Include Defaults
include_once '../../../_config/headers.php';
include_once '../../../_config/database.php';
include_once '../../../_config/core.php';
include_once '../../../_config/libs/php-jwt-master/src/BeforeValidException.php';
include_once '../../../_config/libs/php-jwt-master/src/ExpiredException.php';
include_once '../../../_config/libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../../_config/libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

// ---- Initialize Default
$database = new Database();
$db = $database->connect();
$data = json_decode(file_get_contents("php://input"));

// ---- Include Object
include_once '../../../_config/objects/calorie.php';
$calorie = new Calorie($db);
// ---- End of default Configuration

$token = isset($data->token) ? $data->token : "";

if($token){

    try {

        $decoded = JWT::decode($token, $token_conf['secret'], $token_conf['algorithm']);
        $calorie->userid = $decoded->data->id;

        $stmt = $calorie->readDays();
        $num = $stmt->rowCount();

        if($num>0){

            $i = 1;
            $val_arr=array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $val_item = array(
                    "date" => $date,
                );
                array_push($val_arr, $val_item);
                $i++;
            }

            returnSuccess($val_arr);

        } else {
            returnNoData();
        }

    } catch(Exception $e) {
        returnForbidden($e);
    }

} else {
    returnBadRequest();
}

?>

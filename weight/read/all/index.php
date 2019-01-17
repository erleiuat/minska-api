<?php

// ---- Include Defaults
include_once '../../../_config/headers.php';
include_once '../../../_config/core.php';
include_once '../../../_config/database.php';
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
include_once '../../../_config/objects/weight.php';
$weight = new Weight($db);
// ---- End of default Configuration

$jwt=isset($data->jwt) ? $data->jwt : "";

if($jwt){

    try {

        $decoded = JWT::decode($jwt, $key, array('HS256'));
        $weight->userid = $decoded->data->id;

        $stmt = $weight->read();
        $num = $stmt->rowCount();

        if($num>0){

            $weights_arr=array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $weight_item = array(
                    "id" => $id,
                    "weight" => $weight,
                    "measuredate" => $measuredate,
                );
                array_push($weights_arr, $weight_item);
            }

            returnSuccess($weights_arr);

        } else {
            returnNoData();
        }

    } catch(Exception $e){
        returnForbidden($e);
    }

} else {
    returnBadRequest();
}

?>

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
include_once '../../../_config/objects/weight.php';
$weight = new Weight($db);
// ---- End of default Configuration

$token = isset($data->token) ? $data->token : "";

if($token){

    try {

        $decoded = JWT::decode($token, $token_conf['secret'], $token_conf['algorithm']);
        $weight->userid = $decoded->data->id;

        if(isset($data->order) && $data->order !== 'DESC'){
            $stmt = $weight->read($data->amount, $data->order);
        } else {
            $stmt = $weight->read($data->amount);
        }

        $num = $stmt->rowCount();

        if($num>0){

            $weights_arr=array();
            $i = 1;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

                extract($row);
                $weight_item = array(
                    "id" => $id,
                    "number" => $i,
                    "weight" => $weight,
                    "measuredate" => $measuredate,
                    "creationdate" => $creationdate
                );

                array_push($weights_arr, $weight_item);
                $i++;

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

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
$data = json_decode(file_get_contents("php://input"));
// ---- End of Initialize Default

// ---- Authenticate Request
$token = authenticate();
// ---- End of Authenticate Request

// ---- Include Object
include_once '../../_config/objects/weight.php';
$weight = new Weight($db);
// ---- End of default Configuration

try {

    $decoded = JWT::decode($token, $token_conf['secret'], $token_conf['algorithm']);
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

?>

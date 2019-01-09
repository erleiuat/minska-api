<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] == "OPTIONS"){
    return;
}

include_once '../../../_config/database.php';
include_once '../../../_config/objects/weight.php';

$database = new Database();
$db = $database->connect();
$weight = new Weight($db);
$data = json_decode(file_get_contents("php://input"));

include_once '../../../_config/core.php';
include_once '../../../_config/libs/php-jwt-master/src/BeforeValidException.php';
include_once '../../../_config/libs/php-jwt-master/src/ExpiredException.php';
include_once '../../../_config/libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../../_config/libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

//----- End of default Configuration
$jwt=isset($data->jwt) ? $data->jwt : "";

if($jwt){

    try {

        $decoded = JWT::decode($jwt, $key, array('HS256'));
        $weight->userid = $decoded->data->id;

        if(isset($data->order) && $data->order !== 'DESC'){
            $stmt = $weight->read($data->amount, $data->order);
        } else {
            $stmt = $weight->read($data->amount);
        }

        $num = $stmt->rowCount();

        if($num>0){

            $weights_arr=array();
            $weights_arr["records"]=array();
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

                array_push($weights_arr["records"], $weight_item);

                $i++;

            }

            http_response_code(200);
            echo json_encode($weights_arr);

        } else {

            http_response_code(404);
            echo json_encode(array(
                "message" => "No Weights found."
            ));

        }

    } catch(Exception $e) {

        http_response_code(401);

        echo json_encode(array(
        "message" => "Access denied.",
        "error" => $e->getMessage()
        ));

    }

} else {

    http_response_code(401);
    echo json_encode(array("message" => "Access denied."));

}

?>

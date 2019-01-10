<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] == "OPTIONS"){
    return;
}

include_once '../../_config/database.php';
include_once '../../_config/objects/calorie.php';

$database = new Database();
$db = $database->connect();
$calorie = new Calorie($db);
$data = json_decode(file_get_contents("php://input"));

//----- End of default Configuration

include_once '../../_config/core.php';
include_once '../../_config/libs/php-jwt-master/src/BeforeValidException.php';
include_once '../../_config/libs/php-jwt-master/src/ExpiredException.php';
include_once '../../_config/libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../_config/libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

$jwt=isset($data->jwt) ? $data->jwt : "";

if($jwt){

    try {

        $decoded = JWT::decode($jwt, $key, array('HS256'));

        $calorie->userid = $decoded->data->id;
        $calorie->title = $data->title;
        $calorie->calories = $data->calories;
        $calorie->amount = $data->amount;
        $calorie->date = $data->date;

        try {

            $calorie->create();
            http_response_code(200);
            echo json_encode(array("message" => "Calorie created"));

        } catch (Exception $e) {

            http_response_code(400);
            echo json_encode(array(
                "message" => "Calorie not created",
                "error" => $e->getMessage()
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

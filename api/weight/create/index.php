<?php

// ---- Include Defaults
include_once '../../_config/headers.php';
include_once '../../_config/database.php';
include_once '../../_config/core.php';
include_once '../../_config/libs/php-jwt-master/src/BeforeValidException.php';
include_once '../../_config/libs/php-jwt-master/src/ExpiredException.php';
include_once '../../_config/libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../_config/libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

// ---- Initialize Default
$database = new Database();
$db = $database->connect();
$data = json_decode(file_get_contents("php://input"));

// ---- Include Object
include_once '../../_config/objects/weight.php';
$weight = new Weight($db);
// ---- End of default Configuration

$jwt=isset($data->jwt) ? $data->jwt : "";

if($jwt){

    try {

        $decoded = JWT::decode($jwt, $key, array('HS256'));
        $weight->userid = $decoded->data->id;
        $weight->weight = $data->weight;
        $weight->measuredate = $data->date;

        try {

            $weight->create();
            http_response_code(200);
            echo json_encode(array("message" => "Weight created"));

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(array("message" => "Error"));
        }

    } catch(Exception $e) {
        http_response_code(401);
        echo json_encode(array("message" => "Access denied"));
    }

} else {
    http_response_code(401);
    echo json_encode(array("message" => "Access denied"));
}

?>

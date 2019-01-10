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

$jwt=isset($data->jwt) ? $data->jwt : "";

if($jwt){

    try {

        $decoded = JWT::decode($jwt, $key, array('HS256'));
        $calorie->userid = $decoded->data->id;
        $calorie->date = $data->date;

        $stmt = $calorie->readByDay();
        $num = $stmt->rowCount();

        if($num>0){

            $calories_arr=array();
            $calories_arr["records"]=array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

                extract($row);

                $calorie_item = array(
                    "id" => $id,
                    "title" => $title,
                    "calories" => $calories,
                    "amount" => $amount
                );

                array_push($calories_arr["records"], $calorie_item);

            }

            http_response_code(200);
            echo json_encode($calories_arr);

        } else {
            http_response_code(204);
            echo json_encode(array("message" => "No Data"));
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

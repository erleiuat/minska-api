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
include_once '../../_config/objects/user.php';

$database = new Database();
$db = $database->connect();
$user = new User($db);
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

        $user->id = $decoded->data->id;
        $user->firstname = $data->firstname;
        $user->lastname = $data->lastname;
        $user->height = $data->height;
        $user->language = $data->language;
        $user->isFemale = $data->isFemale;
        $user->aims = $data->aims;

            if($user->update()){

            $token = array(
                "iss" => $iss,
                "iat" => $iat,
                "nbf" => $nbf,
                "exp" => $exp,
                "data" => array(
                    "id" => $user->id,
                    "firstname" => $user->firstname,
                    "lastname" => $user->lastname,
                    "language" => $user->language,
                    "height" => $user->height,
                    "isFemale" => $user->isFemale,
                    "aims" => $user->aims
                )
            );

            $jwt = JWT::encode($token, $key);

            http_response_code(200);
            echo json_encode(array(
                "message" => "User was updated.",
                "jwt" => $jwt
            ));

        } else {

            http_response_code(401);
            echo json_encode(array(
                "message" => "Unable to update user.",
            ));

        }

    } catch (Exception $e){

        http_response_code(401);

        echo json_encode(array(
        "message" => "Access denied.",
        "error" => $e->getMessage()
        ));

    }

}else{
    // set response code
    http_response_code(401);
    // tell the user access denied
    echo json_encode(array("message" => "Access denied."));
}

?>

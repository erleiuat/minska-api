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
include_once '../../_config/objects/user.php';
$user = new User($db);
// ---- End of default Configuration

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
                "message" => "User was updated",
                "jwt" => $jwt
            ));

        } else {
            http_response_code(401);
            echo json_encode(array("message" => "Error"));
        }

    } catch (Exception $e){
        http_response_code(401);
        echo json_encode(array("message" => "Access denied."));
    }

}else{
    http_response_code(401);
    echo json_encode(array("message" => "Access denied"));
}

?>

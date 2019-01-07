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

include_once '../../_config/core.php';
include_once '../../_config/libs/php-jwt-master/src/BeforeValidException.php';
include_once '../../_config/libs/php-jwt-master/src/ExpiredException.php';
include_once '../../_config/libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../_config/libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

$database = new Database();
$db = $database->connect();

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));
$jwt=isset($data->jwt) ? $data->jwt : "";

if($jwt){

    try {

        $decoded = JWT::decode($jwt, $key, array('HS256'));

        $user->firstname = $data->firstname;
        $user->lastname = $data->lastname;
        $user->password = $data->password;
        $user->id = $decoded->data->id;

        if($user->update()) {

            $token = array(
                "iss" => $iss,
                "iat" => $iat,
                "nbf" => $nbf,
                "exp" => $exp,
                "data" => array(
                    "id" => $user->id,
                    "firstname" => $user->firstname,
                    "lastname" => $user->lastname,
                    "email" => $user->email,
                    "language" => $user->language,
                    "expires" => $exp
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
                echo json_encode(array("message" => "Unable to update user."));
            }

    }catch (Exception $e){

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

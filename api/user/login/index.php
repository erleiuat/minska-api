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

$user->email = $data->email;
$email_exists = $user->emailExists();

include_once '../../_config/core.php';
include_once '../../_config/libs/php-jwt-master/src/BeforeValidException.php';
include_once '../../_config/libs/php-jwt-master/src/ExpiredException.php';
include_once '../../_config/libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../_config/libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

if($email_exists && password_verify($data->password, $user->password)){

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
            "expires" => $exp
        )
    );

    http_response_code(200);

    $jwt = JWT::encode($token, $key);
    echo json_encode(array(
        "message" => "Successful login.",
        "jwt" => $jwt
    ));

} else {

    http_response_code(401);
    echo json_encode(array("message" => "Login failed."));

}
?>

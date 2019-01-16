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

$user->email = $data->email;
$email_exists = $user->emailExists();

if($email_exists && password_verify($data->password, $user->password)){

    $token = array(
        "iss" => $token_conf['issuer'],
        "iat" => $token_conf['issuedAt'],
        "exp" => $token_conf['expireAt'],
        "nbf" => $token_conf['notBefore'],
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

    $jwt = JWT::encode($token, $token_conf['secret']);
    returnSuccess($jwt);

} else {
    returnBadRequest();
}
?>

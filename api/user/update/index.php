<?php

// ---- Initialize Default
include_once '../../_config/settings.php';
include_once '../../_config/core.php';
include_once '../../_config/headers.php';
include_once '../../_config/database.php';
include_once '../../_config/validate.php';
include_once '../../_config/libraries/php-jwt-master/src/BeforeValidException.php';
include_once '../../_config/libraries/php-jwt-master/src/ExpiredException.php';
include_once '../../_config/libraries/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../_config/libraries/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
$database = new Database();
$db = $database->connect($db_conf);
$data = json_decode(file_get_contents("php://input"));
// ---- End of Initialize Default

// ---- Authenticate Request
try {
    $token = authenticate();
    $decoded = JWT::decode($token, $token_conf['secret'], $token_conf['algorithm']);
} catch (Exception $e) {
    returnForbidden();
}
// ---- End of Authenticate Request

// ---- Get needed Objects
include_once '../../_config/objects/user.php';
$user = new User($db);
// ---- End of Get needed Objects


try {

    $user->id = $decoded->data->id;
    $user->firstname = val_string($data->firstname, 1, 255);
    $user->lastname = val_string($data->lastname, 1, 255);
    $user->height = $data->height;
    $user->language = val_string($data->language, 1, 3);
    $user->gender = val_string($data->gender, 1, 10);
    $user->birthdate = val_string($data->birthdate, 1, 20);
    $user->aims = $data->aims;

    if ($user->update()) {

        $user->userToken();
        $token = array(
            "iss" => $token_conf['issuer'],
            "iat" => $token_conf['issuedAt'],
            "exp" => $token_conf['expireAt'],
            "nbf" => $token_conf['notBefore'],
            "data" => array(
                "id" => $user->id,
                "firstname" => $user->firstname,
                "lastname" => $user->lastname,
                "email" => $user->email,
                "language" => $user->language,
                "height" => $user->height,
                "birthdate" => $user->birthdate,
                "gender" => $user->gender,
                "aims" => $user->aims
            )
        );

        $jwt = JWT::encode($token, $token_conf['secret']);
        if(setAuth($jwt, $token_conf['expireAt'], $api_conf['cookie'])){
            returnSuccess();
        }

    }

} catch (Exception $e) {
    returnForbidden($e);
}

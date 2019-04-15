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

// ---- Get needed Objects
include_once '../../_config/objects/user.php';
$user = new User($db);
// ---- End of Get needed Objects


try {

    $user->email = val_email($data->email, 1, 89);
    $user->password = val_string($data->password, 1, 255);

    if(password_verify($user->password, $user->getPassword())){

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
        if(setAuth($jwt, $token_conf['expireAt'], $api_conf)){
            returnSuccess();
        }

    } else {
        throw new Exception('password_wrong');
    }

} catch (Exception $e) {
    returnBadRequest($e->getMessage());
}

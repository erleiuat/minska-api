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
    $user->email_key = val_string($data->code, 20, 20);

    if(password_verify($user->email_key, $user->getConfirmCode() )){
        $user->confirmAccount();
        returnSuccess();
    } else {
        throw new Exception('code_invalid');
    }

} catch (Exception $e) {
    returnBadRequest($e->getMessage());
}

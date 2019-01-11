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

$user->firstname = $data->firstname;
$user->lastname = $data->lastname;
$user->email = $data->email;
$user->password = $data->password;

try {
    $user->create();
    http_response_code(200);
    echo json_encode(array("message" => "User created"));
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(array("message" => "User not created"));
}

?>

<?php

// ---- Initialize Default
include_once '../../_config/headers.php';
include_once '../../_config/core.php';
include_once '../../_config/database.php';
include_once '../../_config/libs/php-jwt-master/src/BeforeValidException.php';
include_once '../../_config/libs/php-jwt-master/src/ExpiredException.php';
include_once '../../_config/libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../_config/libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
$database = new Database();
$db = $database->connect();
$data = json_decode(file_get_contents("php://input"));
// ---- End of Initialize Default

// ---- Authenticate Request
$token = authenticate();
// ---- End of Authenticate Request

// ---- Include Object
include_once '../../_config/objects/user.php';
$user = new User($db);
// ---- End of default Configuration

try {

    $decoded = JWT::decode($token, $token_conf['secret'], $token_conf['algorithm']);

    $user->id = $decoded->data->id;
    $user->firstname = $data->firstname;
    $user->lastname = $data->lastname;
    $user->height = $data->height;
    $user->language = $data->language;
    $user->isFemale = $data->isFemale;
    $user->birthdate = $data->birthdate;
    $user->aims = $data->aims;

    if($user->update()){

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
                "isFemale" => $user->isFemale,
                "birthdate" => $user->birthdate,
                "aims" => $user->aims
                )
                );

                $jwt = JWT::encode($token, $token_conf['secret']);
                returnSuccess($jwt);

            } else {
                returnError();
            }

        } catch (Exception $e){
            returnForbidden($e);
        }

        

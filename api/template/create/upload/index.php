<?php

// ---- Initialize Default
include_once '../../../_config/settings.php';
include_once '../../../_config/core.php';
include_once '../../../_config/headers.php';
include_once '../../../_config/database.php';
include_once '../../../_config/validate.php';
include_once '../../../_config/libraries/php-jwt-master/src/BeforeValidException.php';
include_once '../../../_config/libraries/php-jwt-master/src/ExpiredException.php';
include_once '../../../_config/libraries/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../../_config/libraries/php-jwt-master/src/JWT.php';
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

try {

    if(!$_FILES){
        returnBadRequest('image_invalid');
    }
    
    if ($_FILES['img']['type'] == 'image/png') {
        $source = imagecreatefrompng($_FILES['img']['tmp_name']);
    } else if ($_FILES['img']['type'] == 'image/jpeg') {
        $source = imagecreatefromjpeg($_FILES['img']['tmp_name']);
    } else if ($_FILES['img']['type'] == 'image/gif') {
        $source = imagecreatefromgif($_FILES['img']['tmp_name']);
    } else {
        returnBadRequest('image_invalid');
    }

    list($width, $height) = getimagesize($_FILES['img']['tmp_name']);
    $imageName = "minska-U" . $decoded->data->id . "U-T" . time() . "T-R" . rand(100, 999) . "R.jpg";
    $imageWidth = 300;
    $imageHeight = $height*($imageWidth/$width);
    $imageQuality = 80;
    $uploaddir = '../../read/thumbnails';

    $rendered = imagecreatetruecolor($imageWidth, $imageHeight);
    imagecopyresampled($rendered, $source, 0, 0, 0, 0, $imageWidth, $imageHeight, $width, $height);
    imagejpeg($rendered, $uploaddir . "/" . $imageName, $imageQuality);
    returnSuccess($imageName);

} catch (Exception $e) {
    returnBadRequest($e);
}

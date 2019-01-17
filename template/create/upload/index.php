<?php

include_once '../../../_config/headers.php';
include_once '../../../_config/core.php';
include_once '../../../_config/libs/php-jwt-master/src/BeforeValidException.php';
include_once '../../../_config/libs/php-jwt-master/src/ExpiredException.php';
include_once '../../../_config/libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../../_config/libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

$token = $_POST['token'];
$token = isset($_POST['token']) ? $token : "";

function correctImageOrientation($filename) {

}

if($token){

    try {

        $decoded = JWT::decode($token, $token_conf['secret'], $token_conf['algorithm']);

        print_r($_FILES);

        if($_FILES['img']['type'] == 'image/png'){
            $source = imagecreatefrompng($_FILES['img']['tmp_name']);
        } else if($_FILES['img']['type'] == 'image/jpeg') {
            $source = imagecreatefromjpeg($_FILES['img']['tmp_name']);
        } else {
            returnBadRequest();
            die();
        }

        if (function_exists('exif_read_data')) {

            $exif = exif_read_data($_FILES['img']['tmp_name']);

            if($exif && isset($exif['Orientation'])) {
                $orientation = $exif['Orientation'];
                if($orientation != 1){
                    $deg = 0;
                    switch ($orientation) {
                        case 3:
                        $deg = 180;
                        break;
                        case 6:
                        $deg = 270;
                        break;
                        case 8:
                        $deg = 90;
                        break;
                    }

                    if ($deg) {
                        $source = imagerotate($source, $deg, 0);
                    }


                }
            }
        }

        list($width, $height) = getimagesize($_FILES['img']['tmp_name']);
        $imageName = "minska-U".$decoded->data->id."U-T".time()."T-R".rand(100,999)."R.jpg";
        $imageWidth = 300;
        $imageHeight = $height*($imageWidth/$width);
        $imageQuality = 80;
        $uploaddir = '../../read/thumbnails';

        $rendered = imagecreatetruecolor($imageWidth, $imageHeight);
        imagecopyresampled($rendered, $source, 0, 0, 0, 0, $imageWidth, $imageHeight, $width, $height);
        imagejpeg($rendered, $uploaddir ."/".$imageName, $imageQuality);

        returnSuccess($imageName);

    } catch (Exception $e) {
        returnForbidden($e);
    }

} else {
    returnBadRequest();
}

?>

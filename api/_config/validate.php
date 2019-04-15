<?php

function val_string ($value, $min=false, $max=true) {

    $value = trim($value);
    if(strlen($value) === 0 && !$min){
        return $value;
    } else {
        $value = htmlspecialchars($value);
        $value = filter_var($value, FILTER_SANITIZE_STRING);
        if($min <= strlen($value) && $max >= strlen($value)){
            return $value;
        }
    }

    returnBadRequest("Value-Check (String) failed");

}

function val_number ($value, $min=false, $max=true) {

    if($value == 0 && !$min){
        return $value;
    } else {
        $value = trim($value);
        $value = htmlspecialchars($value);
        $value = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT);
        $state = filter_var($value, FILTER_VALIDATE_FLOAT);
        if($state && $min <= $value && $max >= $value){
            return $value;
        }
    }

    returnBadRequest("Value-Check (Number) failed");

}

function val_email ($value, $min=false, $max=true) {

    $value = trim($value);
    if(strlen($value) === 0 && !$min){
        return $value;
    } else {
        $value = htmlspecialchars($value);
        $value = filter_var($value, FILTER_SANITIZE_EMAIL);
        $state = filter_var($value, FILTER_VALIDATE_EMAIL);
        if($state && $min <= strlen($value) && $max >= strlen($value)){
            return $value;
        }
    }

    returnBadRequest("Value-Check (E-Mail) failed");

}

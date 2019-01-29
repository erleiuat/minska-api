<?php

    echo '<br /><br /><br />';
    echo '<h1>apache_request_headers() Dump</h1><br />';
    foreach (apache_request_headers() as $name => $value) {
        print_r($name.' = '.$value.'<br />');
    }

?>

<?php

function sendMail($mail, $code, $url, $lang){

    $subject_en = "Activate your new Account on Minska!";
    $message_en = '
    <html>
        <head>
            <title>Welcome to Minska!</title>
        </head>

        <body style="font-family: Arial, Helvetica, sans-serif; text-align: center;">

            <h1 style="color:#381d56;">Welcome to Minska!</h1>
            <p style="font-size: 16px;">
                We just received a registration with your E-Mail Address.
                If this was you, please follow to instructions below to finalize your registration.
                If not, you can just ignore this Mail.
            </p>
            <h4>
                Please click the link below to activate your Account.
            </h4>

            <div style="
            background-color: #381d56;
            color: white;
            width: 200px;
            padding-top: 8px;
            padding-bottom: 5px;
            padding-left: 10px;
            padding-right: 10px;
            border: solid 1px #381d56;
            border-radius: 10px;
            cursor: pointer;
            margin-left: auto;
            margin-right:auto;">
                <a style="font-size: 15px;color: white; text-decoration: none;" href="https://'.$url.'?mail='.$mail.'&code='.$code.'">
                    <b>ACTIVATE MY ACCOUNT</b>
                </a>
            </div>
            <br /><br />

            <p>Or use the following code on <a href="https://'.$url.'">'.$url.'</a></p>

            <h3><i>'.$code.'</i></h3>

            <p style="text-align: left;">
                <br /><br />
                Best Regards, <br />
                Your Minska Dev-Team :)
            </p>

        </body>
    </html>
    ';

    $subject_de = "Aktiviere deinen neuen Account auf Minska!";
    $message_de = '
    <html>
        <head>
            <title>Willkommen auf Minska!</title>
        </head>

        <body style="font-family: Arial, Helvetica, sans-serif; text-align: center;">

            <h1 style="color:#381d56;">Willkommen auf Minska!</h1>
            <p style="font-size: 16px;">
                Wir haben gerade eine Registrierung über deine E-Mail Adresse erhalten.
                Falls du das warst, folge bitte den Anweisungen weiter unten, um deinen Account zu aktivieren.
                Andernfalls kannst du dieses E-Mail einfach ignorieren.
            </p>
            <h4>
                Drücke folgenden Link um deinen Account zu aktivieren:
            </h4>

            <div style="
            background-color: #381d56;
            color: white;
            width: 200px;
            padding-top: 8px;
            padding-bottom: 5px;
            padding-left: 10px;
            padding-right: 10px;
            border: solid 1px #381d56;
            border-radius: 10px;
            cursor: pointer;
            margin-left: auto;
            margin-right:auto;">
                <a style="font-size: 15px;color: white; text-decoration: none;" href="https://'.$url.'?mail='.$mail.'&code='.$code.'">
                    <b>ACCOUNT AKTIVIEREN</b>
                </a>
            </div>
            <br /><br />

            <p>Oder nutze diesen Code auf <a href="https://'.$url.'">'.$url.'</a></p>

            <h3><i>'.$code.'</i></h3>

            <p style="text-align: left;">
                <br /><br />
                Beste Grüsse, <br />
                Dein Minska Dev-Team :)
            </p>

        </body>
    </html>
    ';

    $header  = "MIME-Version: 1.0\r\n";
    $header .= "Content-type: text/html; charset=utf-8\r\n";
    $header .= "From: $sender\r\n";
    $header .= "Reply-To: $sender\r\n";
    $header .= "X-Mailer: PHP ". phpversion();

    $sender = "minska@osis.io";

    if($lang === 'de'){
        mail($mail, $subject_de, $message_de, $header);
    } else {
        mail($mail, $subject_en, $message_en, $header);
    }

}

<?php
function sendEmail($to, $subject, $title, $message, $buttonLink, $buttonName) {
    $message = "<head> <meta charset=\"UTF-8\"> <link href=\"https://fonts.googleapis.com/css?family=Roboto:300,900\" rel=\"stylesheet\"> </head> <body style=\"background-color: #EFEFEF; padding: 15px;\"> <div style=\"max-width: 650px; background-color: white; margin: 0 auto; padding: 15px; box-shadow: 0 1px 2px 0 rgba(34,36,38,.15); -webkit-box-shadow: 0 1px 2px 0 rgba(34,36,38,.15); -moz-box-shadow: 0 1px 2px 0 rgba(34,36,38,.15);\"> <h1 style='font-family: \"Roboto\", Roboto, serif; color: #3F5765; text-align: center; margin-bottom: 10px;'>" . $title . "</h1> <hr style=\"color: #3F5765; background-color: #3F5765; border: none; height: 3px; width: 50px;\"> <p style='text-align: center;font-family: \"Roboto\", Roboto, serif; padding: auto 25px;'>" . $message . ".</p> <a href=\"http://" . $buttonLink. "\" style=\"text-decoration: none;\"><button style=\"border: 2px solid #3F5765; color: #3F5765; padding: 10px; width: 200px; font-size: 14px; margin: auto; display: block; cursor: pointer; position: relative; background: rgba(0,0,0,0); background: -webkit-linear-gradient(right, rgba(255,255,255,0) 50%, #3F5765 50%); background: -o-linear-gradient(to left, rgba(255,0,0,0) 50%, #3F5765 50%); background: -moz-linear-gradient(to left, rgba(255,0,0,0) 50%, #3F5765 50%); background: linear-gradient(to left, rgba(255,0,0,0) 50%, #3F5765 50%); background-size: 200% 100%; background-position: right bottom; -webkit-transition: background-position 0.3s, color 0.3s; transition: background-position 0.3s, color 0.3s; margin-bottom: 20px;\">" . $buttonName . "</button></a> </div> <p style='text-align: center;font-family: \"Roboto\", Roboto, serif;'>Wreckless Reykjavík</p> </body>";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: Wreckless Reykjavík <noreply@' . $_SERVER['SERVER_NAME'] . '>' . "\r\n";
    $headers .= 'Reply-To: Wreckless Reykjavík <support@' . $_SERVER['SERVER_NAME'] . '>' . "\r\n";
    $message = preg_replace('/(?<!\r)\n/', "\r\n", $message);
    $headers = preg_replace('/(?<!\r)\n/', "\r\n", $headers);
    mail($to, $subject, $message, $headers);
}
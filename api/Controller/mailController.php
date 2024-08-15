<?php

include_once './PHPMailer/Mailer.php';

function mailController($uri, $apiKey)
{

    switch ($_SERVER['REQUEST_METHOD']) {

        case 'GET':
            exit_with_message("You can't do GET request for mail");
            break;

        case 'POST':

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);

            if(!isset($json["subject"]) || !isset($json["htmlString"]) || empty($json["subject"]) || empty($json["htmlString"]) || !isset($json["emailToSend"]) || empty($json["emailToSend"])){
                exit_with_message("Need the subject the htmlString, and the emailToSend parameter");
            }

            $newsletterSender = new NewsletterSender("NO MORE WASTE");
            $mailFailed = $newsletterSender->sendNewsletter($json["subject"], $json["htmlString"], $json["emailToSend"]);
            exit_with_content($mailFailed);

        case 'PUT':
            exit_with_message("You can't do PUT request for mail");
            break;

        case 'DELETE':
            exit_with_message("You can't do DELETE request for mail");
            break;

        default:
            exit_with_message("Not Found", 404);
            exit();
    }
}

?>
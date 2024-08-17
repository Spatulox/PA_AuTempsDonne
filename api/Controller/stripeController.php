<?php
include_once "./Service/stripeService.php";

function stripeController($uri, $apiKey)
{

    switch ($_SERVER['REQUEST_METHOD']) {

        case 'GET':
            exit_with_message("You can't do GET request for stripe");
            break;


        case 'POST':

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);

            if(!isset($json['amount']) || empty($json['amount']) || !isset($json['name']) || empty($json['name']) || !isset($json['mail']) || empty($json['mail'])) {
                exit_with_message("Error, amount, name and mail metadata are mandatory, to pay");
            }
            $tmp_array = ["payment", "subscription"];


            if(in_array($uri[3], $tmp_array)) {

                if($uri[3] == "payment"){
                    $stripeService = new StripeService();
                    $stripeService->startPayment($json['amount'], $json['name'], $json['returnPath'], $json["mail"], getIdUserFromApiKey($apiKey));
                    break;
                } else if($uri[3] == "subscription"){
                    $stripeService = new StripeService();
                    $stripeService->startSubscription($json['amount'], $json['name'], $json['returnPath'], $json["mail"], getIdUserFromApiKey($apiKey));
                    break;
                }
            }

            if ($uri[3] == "updatesubscribe") {
                $payload = @file_get_contents('php://input');
                $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

                $stripeService = new StripeService();
                $stripeService->addSubscriptionToBdd($payload, $sig_header);
            }

            if($uri[3] == "unsuscribe"){
                $stripeService = new StripeService();
                $stripeService->stopSubscription(getIdUserFromApiKey($apiKey));
            }

            exit_with_content(["message" => "Wrong type" ,"correct_values" => $tmp_array]);

        case 'PUT':
            exit_with_message("You can't do PUT request for stripe");
            break;


        case 'DELETE':
            exit_with_message("You can't do DELETE request for stripe");
            break;


        default:
            exit_with_message("Not Found", 404);
            exit();
    }
}

?>
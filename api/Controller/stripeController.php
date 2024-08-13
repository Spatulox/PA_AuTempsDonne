<?php
include_once "./Service/stripeService.php";

function stripeController($uri, $apiKey)
{

    switch ($_SERVER['REQUEST_METHOD']) {

        case 'GET':
            exit_with_message("You can't do GET request for stripe");
            break;


        case 'POST':
            //if(!isset($_POST['amount']) || empty($_POST['amount']) || !isset($_POST['name']) || empty($_POST['name'])) {
            //    exit_with_message("Error, amount and name are mandatory to pay");
            //}
            $tmp_array = ["payment", "subscription"];

            if(!in_array($uri[3], $tmp_array)) {
                exit_with_content(["message" => "Wrong type" ,"correct_values" => $tmp_array]);
            }

            $stripeService = new StripeService();
            //$stripeService->startPayment($_POST['amount'], $_POST['name'], $_POST['type']);
            $stripeService->startPayment(12, "test", "payment");
            break;

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
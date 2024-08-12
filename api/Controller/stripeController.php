<?php


function stripeController($uri, $apiKey)
{

    if(!isset($_POST['amount']) || empty($_POST['amount']) || !isset($_POST['name']) || empty($_POST['name'])) {
        exit_with_message("Error, le montant et le nom sont obligatoire");
        exit();
    }

    switch ($_SERVER['REQUEST_METHOD']) {

        case 'GET':
            exit_with_message("You can't do GET request for stripe");
            break;


        case 'POST':
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
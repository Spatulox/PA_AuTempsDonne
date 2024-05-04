<?php
include_once './Service/dontService.php';
include_once './Models/donModel.php';
include_once './exceptions.php';

function donController($uri, $apiKey)
{
    switch ($_SERVER['REQUEST_METHOD']) {

        case 'GET':

            if ($uri[3]) {
                $donService = new DonService();
                $donService->getDonById($uri[3],$apiKey);
            } else {
                $donService = new DonService();
                $donService->getDonEntrepot($apiKey);
            }

            break;


        case 'POST':
            $body = file_get_contents("php://input");
            $json = json_decode($body, true);

            if ($uri[3] && $uri[3]=== 'annuel'){
                if (!isset($json['date'])) {
                    exit_with_message("Please provide all required fields to create a new planning", 400);
                }

                $donService = new DonService();
                $donService->getDonAnnuel($json['date'], $apiKey);

            }
//---------------------------------------------------------------------------------------------------

            if ($uri[3] && $uri[3]=== 'mensuel'){
                if (!isset($json['date'])) {
                    exit_with_message("Please provide all required fields to create a new planning", 400);
                }

                $donService = new DonService();
                $donService->getDonMensuel($json['date'], $apiKey);

            }

            break;


        case 'PUT':


            break;


        case 'DELETE':


        default:
            exit_with_message("Not Found", 404);
            exit();
    }
}

?>
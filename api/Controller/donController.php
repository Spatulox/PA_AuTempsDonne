<?php
include_once './Service/donService.php';
include_once './Models/donModels.php';
include_once './exceptions.php';

function donController($uri, $apiKey)
{
    switch ($_SERVER['REQUEST_METHOD']) {

        case 'GET':

            if ($uri[3]) {
                $donService = new DonService();
                exit_with_content( $donService->getDonById($uri[3],$apiKey));
            } else {
                $donService = new DonService();
                exit_with_content($donService->getDonEntrepot($apiKey));
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
                exit_with_content($donService->getDonAnnuel($json['date'], $apiKey));

            }
//---------------------------------------------------------------------------------------------------

            if ($uri[3] && $uri[3]=== 'mensuel'){
                if (!isset($json['date'])) {
                    exit_with_message("Please provide all required fields to create a new planning", 400);
                }

                $donService = new DonService();
                exit_with_content($donService->getDonMensuel($json['date'], $apiKey));

            }

            if ($uri[3] && $uri[3]=== 'create'){
                if (!isset($json['prix']) || !isset($json['date'])) {
                    exit_with_message("Please provide all required fields to create a new don", 400);
                }

                $donService = new DonService();
                $donService->CreateDonMensuel($json['prix'],$json['date'], $apiKey);

            }

            if (!$uri[3]){
                    exit_with_message("Wrong redirection", 400);
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
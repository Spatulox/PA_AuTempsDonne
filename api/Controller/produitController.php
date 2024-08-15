<?php

include_once('./Service/produitService.php');
include_once('./Models/produitModel.php');
function collectController($uri, $apikey) {

    $produitService = new ProduitService();

    switch ($_SERVER['REQUEST_METHOD']) {

        case 'GET':

            $role = getRoleFromApiKey($apikey);
            if($role > 3 && $role != 5) {
                exit_with_message("You can't do that", 403);
            }

            if($uri[3] && filter_var($uri[3], FILTER_VALIDATE_INT)) {
                $produitService->getProduitByID($uri[3]);
            }elseif ($uri[3]=="type"){
                $produitService->getType();
            }
            else{
                $produitService->getProduitAll($apikey);
            }

            exit_with_message("Wtf, why are you here bro", 500);

            break;
        case 'POST':

            $role = getRoleFromApiKey($apikey);
            if($role == 3 || $role == 4) {
                exit_with_message("You can't do that", 403);
            }

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);

            if(!isset($json["nom_produit"]) || !isset($json["type"])){
                exit_with_message("You need to give nom_produit and type (int) to create a product", 403);
            }

            $produitService = new produitService();

            $produitService->createProduit($json["nom_produit"], $json["type"]);


            break;
        case 'PUT':
            exit_with_message("Impossible to do this request", 403);
            break;

        case 'DELETE':

            if(!$uri[3]){
                exit_with_message("Need to specifie the id of the collect to delete", 403);
            }

            $role = getRoleFromApiKey($apikey);
            if($role == 3 || $role == 4) {
                exit_with_message("You can't do that", 403);
            }


            $produitService = new produitService();

            $produitService->deleteProduit($uri[3]);

            break;

        default:
            header("HTTP/1.1 404 Not Found");
            echo "{\"message\": \"Not Found\"}";
            break;
    }
}

?>
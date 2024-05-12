<?php
include_once './Service/stockService.php';
include_once './Models/stockModel.php';
include_once './exceptions.php';

function StockController($uri, $apiKey)
{
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if ($apiKey == null) {
                exit_with_message("Unauthorized, need the apikey", 403);
            }
            $stockService = new StockService();

            if(!$uri[3]){
                exit_with_content($stockService->getAllStock($apiKey));
            }

            elseif($uri[3] && filter_var($uri[3], FILTER_VALIDATE_INT)){
                exit_with_content($stockService->getAllStockInEntrepots($uri[3],$apiKey));
            }
            elseif($uri[3]=="sortie" && filter_var($uri[4], FILTER_VALIDATE_INT)){
                exit_with_content($stockService->getAllStockSortie($uri[4],$apiKey));
            }


            break;


        case 'POST':
            if ($apiKey == null) {
                exit_with_message("Unauthorized, need the apikey", 403);
            }
            $stockService = new StockService();

            $body = file_get_contents("php://input");
            $json = json_decode($body, true);
            if (!$uri[3]) {
                if (!isset($json['quantite_produit']) || !isset($json['desc_produit']) || !isset($json['id_produit']) || !isset($json['id_entrepot'])) {
                    exit_with_message("Erreur : Les informations du produit sont incomplètes. Veuillez vérifier que tous les champs sont remplis.", 403);
                }


                $stock = new StockModel(1, $json['quantite_produit'], $json['date_entree'], ($json['date_sortie']), ($json['date_peremption']), ($json['desc_produit']), ($json['id_produit']), (null));

                exit_with_content($stockService->createStock($stock, $json['id_entrepot'], $apiKey));
            }
        elseif($uri[3]=="date"){
            if (!isset($json['date_sortie'])) {
                exit_with_message("Erreur : Les informations de la date sont incomplètes. Veuillez vérifier que tous les champs sont remplis.", 403);
            }
        exit_with_content($stockService->getAllStockdateSortie($json['date_sortie'], $apiKey));
    }

            break;

        case 'DELETE':
            if(!$uri[3]){
                exit_with_message("il manque qu'elle stock vous voulez supprimer", 403);
            }

            $stockService = new StockService();

            $stockService->deleteStock($uri[3],$apiKey);

            break;


        default:
            exit_with_message("Not Found", 404);
            exit();
    }
}
?>
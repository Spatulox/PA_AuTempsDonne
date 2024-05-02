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

                break;

        default:
            exit_with_message("Not Found", 404);
            exit();
    }
}
?>
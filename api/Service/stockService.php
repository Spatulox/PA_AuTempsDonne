<?php

include_once './Repository/stockRepository.php';
include_once './index.php';

class StockService {

    public function getAllStock($apiKey)
    {
        $userRole = getRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2 || $userRole[0]==3) {
            $stockRepository = new StockRepository();
            return $stockRepository->getAllStock();
        }else{
            exit_with_message("Vous n'avais pas accès a cette commande");
        }

    }

    public function getAllStockInEntrepots($id, $apiKey)
    {
        $userRole = getRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2 || $userRole[0]==3) {
        $stockRepository = new StockRepository();
        return $stockRepository->getAllStockInEntrepots($id);
        }else{
            exit_with_message("Vous n'avais pas accès a cette commande");
        }
    }
}
?>
<?php

include_once './Repository/stockRepository.php';
include_once './index.php';

class StockService {

    function isValidDate($date) {
        $pattern = '/^(\d{4})-(\d{2})-(\d{2})$/';
        if (preg_match($pattern, $date, $matches)) {
            $year = $matches[1];
            $month = $matches[2];
            $day = $matches[3];
            if (checkdate($month, $day, $year)) {
                return true;
            }
        }
        return false;
    }

//----------------------------------------------------------------------------------

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

//----------------------------------------------------------------------------------

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

//--------------------------------------------------------------------------------------------

    public function getAllProduitsInEntrepots($id,$produit, $apiKey)
    {
        $userRole = getRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2 || $userRole[0]==3) {
            $stockRepository = new StockRepository();
            return $stockRepository->getAllProduitsInEntrepots($id,$produit);
        }else{
            exit_with_message("Vous n'avais pas accès a cette commande");
        }
    }

//----------------------------------------------------------------------------------

    public function createStock(StockModel $stock, $apiKey)
    {
        $stockRepository = new StockRepository();

        $userRole = getRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2 || $userRole[0]==3) {

            $string = "id_entrepot=" .$stock->id_entrepot;
            $entrepots= selectDB("ENTREPOTS", "id_entrepot",$string,"bool");

            if(!$entrepots){
                exit_with_message("cette entrepot n'existe pas ", 500);
            }

            $string = "id_produit=" .$stock->id_produit ;
            $produit= selectDB("PRODUIT", "id_produit",$string,"bool");

            if(!$produit){
                exit_with_message("ce produit n'existe pas ", 500);
            }

            if (($stock->date_sortie == "NULL" && $stock->date_entree =="NULL")) {
                exit_with_message("Erreur : il faut rentre une date", 400);
            }

            if (($stock->date_sortie != "NULL" && $stock->date_entree !="NULL")) {
                exit_with_message("Erreur : il ne faut pas deux date", 400);
            }


            if (($stock->date_sortie == "NULL" && $stock->date_entree !="NULL")) {
                if (!$this->isValidDate($stock->date_entree)) {
                exit_with_message("Erreur : La date d'entrée est invalide. Le format attendu est AAAA-MM-JJ.", 403);
                }
            }

            if (($stock->date_sortie != "NULL" && $stock->date_entree =="NULL")) {
                $sum = 0;
                if (!$this->isValidDate($stock->date_sortie)) {
                exit_with_message("Erreur : La date de sortie est invalide. Le format attendu est AAAA-MM-JJ.", 403);
                }

                $test = $this->getAllProduitsInEntrepots($stock->id_entrepot,$stock->id_produit,$apiKey);

                $id_produit_stock = array();
                foreach ($test as $item) {
                    $id_produit_stock[] = array(
                        'id_stock' => $item->id_stock,
                        'quantite_produit' => $item->quantite_produit,
                        'date_entree' => $item->date_entree,
                        'date_sortie' => $item->date_sortie

                    );
                    if ($item->date_entree != null){
                        $sum= $sum +$item->quantite_produit;
                    }else{
                        $sum=$sum-$item->quantite_produit;
                    }
                }

               if ($sum-$stock->quantite_produit <=0){
                   exit_with_message(" il n'y a pas acces de stock de ce produit dans cette entrepots");
               }

            }

            if ($stock->date_peremption == "NUlL"){
                return $stockRepository->createStock($stock);
             } else if (!$this->isValidDate($stock->date_peremption)) {
                exit_with_message("Erreur : La date de péremption est invalide. Le format attendu est AAAA-MM-JJ.", 403);
            }



            return $stockRepository->createStock($stock);
        }else{
            exit_with_message("Vous n'avais pas accès a cette commande");
        }
    }
}
?>
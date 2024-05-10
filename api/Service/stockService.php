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
            exit_with_message("You didn't have access to this command");
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
            exit_with_message("You didn't have access to this command");
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
            exit_with_message("You didn't have access to this command");
        }
    }

//----------------------------------------------------------------------------------

    public function createStock(StockModel $stock,$id_entrepots, $apiKey)
    {
        $stockRepository = new StockRepository();

        $userRole = getRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2 || $userRole[0]==3) {



            // Vérifications

            if ($stock->date_peremption != "NUlL"){
                if (!$this->isValidDate($stock->date_peremption)) {
                    exit_with_message("Erreur : La date de péremption est invalide. Le format attendu est AAAA-MM-JJ.", 403);
                }
            }

            $string_prod = "id_produit=" .$stock->id_produit ;
            $produit = selectDB("PRODUIT", "id_produit",$string_prod,"bool");

            if(!$produit){
                exit_with_message("ce produit n'existe pas ", 500);
            }

            if (($stock->date_sortie == "NULL" && $stock->date_entree =="NULL")) {
                exit_with_message("Erreur : il faut rentre une date", 400);
            }

            if (($stock->date_sortie != "NULL" && $stock->date_entree !="NULL")) {
                exit_with_message("Erreur : il ne faut pas deux date", 400);
            }



            //
            // Produit à entrer dans la BDD
            //

            if (($stock->date_sortie == "NULL" && $stock->date_entree !="NULL")) {

                if (!$this->isValidDate($stock->date_entree)) {
                    exit_with_message("Erreur : La date d'entrée est invalide. Le format attendu est AAAA-MM-JJ.", 403);
                }

                $string = "id_entrepot=" .$id_entrepots;
                $etagere= selectDB("ETAGERES", "id_etagere,nombre_de_place",$string);


                if(!$etagere){
                    exit_with_message("cette etagere n'existe pas ", 500);
                }

                //
                // ------------  Quel produit et où ca va être rangé
                //

                $toutranger = 0;
                $bkpProduit = $stock->quantite_produit;

                $tabb = [];
                $tabRenvoyer = [];
                $compteur=0;

                for ($j = 0; $j < count($etagere); $j++) {

                    $compteur++;
                    // Regrde le nombre de place disponible dns l'étagère
                    $nbProduitsEtagere = $this->getnbplace($etagere[$j]['id_etagere']);

                    if ($nbProduitsEtagere < $etagere[$j]['nombre_de_place']) {

                        //la place dans l'etagere
                        $place = $etagere[$j]['nombre_de_place'] - $nbProduitsEtagere;


                        $qte=$stock->quantite_produit;

                        $stock->quantite_produit=$stock->quantite_produit-$place;
                        if ($stock->quantite_produit<=0){
                            $arentre=$qte;
                        }else{
                            $arentre=$qte-$stock->quantite_produit;
                        }

                        $tabb[$etagere[$j]['id_etagere']] = $arentre;

                        $tabRenvoyer[] = $compteur;

                        $toutranger += $arentre;

                        if($stock->quantite_produit <= 0){
                            break;
                        }
                    }

                }



                $msg = "";

                if($toutranger != $bkpProduit){
                    $msg = "Il reste ". ($bkpProduit - $toutranger) . " produit(s) à ranger car plus de place dans cet entrepot";
                    return $stockRepository->addStock($tabb, $stock, $msg, $tabRenvoyer);
                }

                //
                // ------------  FIN
                //
                // Creer où ranger les produits
                if ($tabRenvoyer==null){
                    echo "1";
                }

                return $stockRepository->addStock($tabb ,$stock,$msg,$tabRenvoyer);

                //exit_with_content();

            }


            if (($stock->date_sortie != "NULL" && $stock->date_entree =="NULL")) {
                $sum = 0;
                if (!$this->isValidDate($stock->date_sortie)) {
                exit_with_message("Erreur : La date de sortie est invalide. Le format attendu est AAAA-MM-JJ.", 403);
                }

                $test = $this->getAllProduitsInEntrepots($id_entrepots,$stock->id_produit,$apiKey);



                $id_produit_stock = array();
                foreach ($test as $item) {
                    $id_produit_stock[] = array(
                        'id_stock' => $item->id_stock,
                        'quantite_produit' => $item->quantite_produit,
                        'date_entree' => $item->date_entree,
                        'date_sortie' => $item->date_sortie,
                        'id_etagere' => $item->id_etagere,

                    );

                    if ($item->date_entree != null){
                        $sum= $sum +$item->quantite_produit;
                    }

                }


                if ($sum-$stock->quantite_produit <0){
                   exit_with_message("Il y a ". $sum ."l de ce produit dans cet entrepot, vous ne pouvez pas retirer ". $stock->quantite_produit ."l de ce produit");
               }

                $tabRenvoyer = [];
                $compteur=0;
                $tabb = [];
                for ($j = 0; $j < count($id_produit_stock); $j++) {
                    $compteur++;
                    $qteEtagere = $id_produit_stock[$j]['quantite_produit'];
                    $tabRenvoyer[] = $compteur;
                    if ($qteEtagere < $stock->quantite_produit){
                        $tabb[$id_produit_stock[$j]['id_etagere']] = $qteEtagere;
                        $stock->quantite_produit = $stock->quantite_produit - $qteEtagere;

                        $this->updateStock($id_produit_stock[$j]["id_stock"], $qteEtagere);

                    } else {
                        $tabb[$id_produit_stock[$j]['id_etagere']] = $stock->quantite_produit;
                        $qteEtagere=$stock->quantite_produit ;

                        $this->updateStock($id_produit_stock[$j]["id_stock"],$qteEtagere);
                        $stock->quantite_produit = 0;
                        break;
                    }



                }

                $msg="";
                return $stockRepository->addStock($tabb ,$stock,$msg,$tabRenvoyer);


            }

        }else{
            exit_with_message("You didn't have access to this command");
        }
    }

    //--------------------------------------------------------------------------------------

    public function deleteStock($id,$apiKey)
    {
        $userRole = getRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2 || $userRole[0]==3) {
            $stockRepository = new StockRepository();
            return $stockRepository->deleteStock($id);
        }else{
            exit_with_message("You didn't have access to this command");
        }

    }

    //--------------------------------------------------------------------------------------

    private function getnbplace($id)
    {
        $string = "id_etagere=".$id . " AND ". "date_sortie IS NULL";
        $number= selectDB("STOCKS","quantite_produit",$string,"bool");
        for ($i = 0; $i < count($number); $i++) {
            $sum=$sum+$number[$i]["quantite_produit"];
        }

        return $sum;
    }

    //--------------------------------------------------------------------------------------

    public function updateStock($id_stock, $qteEtagere)

    {

        $act=selectDB("STOCKS","quantite_produit"  ,"id_stock=".$id_stock);
        $act= (int) $act[0]["quantite_produit"]- (int)$qteEtagere;
        updateDB("STOCKS",["quantite_produit"] , [$act] ,"id_stock=".$id_stock);
    }

    public function getAllStockdateSortie($date_sortie, $apiKey)
    {
        $userRole = getRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2 || $userRole[0]==3) {
            $stockRepository = new StockRepository();
            return $stockRepository->getAllStockdateSortie($date_sortie);
        }else{
            exit_with_message("You didn't have access to this command");
        }
    }

    //--------------------------------------------------------------------------------------

    public function getAllStockSortie($int, $apiKey)
    {
        $userRole = getRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2 || $userRole[0]==3) {
            $stockRepository = new StockRepository();
            return $stockRepository->getAllStockSortie();
        }else{
            exit_with_message("You didn't have access to this command");
        }
    }
}
?>
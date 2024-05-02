<?php
include_once './Repository/BDD.php';
include_once './exceptions.php';
include_once './Models/stockModel.php';


class StockRepository {
    function __construct() {

    }


    public function getAllStock()
    {


        $stockArray = selectDB("STOCKS", "*",-1);

        $stock = [];

        for ($i = 0; $i < count($stockArray); $i++) {
            $dateEntre = $stockArray[$i]['date_entree'] ? date('Y-m-d', strtotime($stockArray[$i]['date_entre'])) : 'Non définie';
            $dateSortie = $stockArray[$i]['date_sortie'] ? date('Y-m-d', strtotime($stockArray[$i]['date_sortie'])) : 'Non définie';
            $datePeremption = $stockArray[$i]['date_peremption'] ? date('Y-m-d', strtotime($stockArray[$i]['date_peremption'])) : 'Non définie';
        }

        for ($i = 0; $i < count($stockArray); $i++) {

            $stock[$i] = new StockModel(
                $stockArray[$i] ['id_stock'],
                $stockArray[$i] ['quantite_produit'],
                $dateEntre,
                $dateSortie,
                $datePeremption,
                $stockArray[$i] ['desc_produit'],
                $stockArray[$i] ['id_produit'],
                $stockArray[$i] ['id_entrepot']
            );
            $stock[$i]->setIndexProduit(selectDB("PRODUIT", "nom_produit", "id_produit=".$stockArray[$i]['id_produit'])[0]);
            $stock[$i]->setentrepot(selectDB("ENTREPOTS", "nom_entrepot", "id_entrepot=".$stockArray[$i]['id_entrepot'])[0]);

            //    var_dump($stockArray[4]);
        }
        return $stock;
    }

//-----------------------------------------------------------------------------------------------------------------------------------------------

    public function getAllStockInEntrepots($id)
    {
        $string = "id_entrepot=" .$id ;
       $entrepots= selectDB("ENTREPOTS", "id_entrepot",$string,"bool");

        if(!$entrepots){
            exit_with_message("cette entrepot n'existe pas ", 200);
        }

        $stockArray = selectDB("STOCKS", "*",$string);

        $stock = [];

        for ($i = 0; $i < count($stockArray); $i++) {
            $dateEntre = $stockArray[$i]['date_entree'] ? date('Y-m-d', strtotime($stockArray[$i]['date_entre'])) : 'Non définie';
            $dateSortie = $stockArray[$i]['date_sortie'] ? date('Y-m-d', strtotime($stockArray[$i]['date_sortie'])) : 'Non définie';
            $datePeremption = $stockArray[$i]['date_peremption'] ? date('Y-m-d', strtotime($stockArray[$i]['date_peremption'])) : 'Non définie';
        }

        for ($i = 0; $i < count($stockArray); $i++) {

            $stock[$i] = new StockModel(
                $stockArray[$i] ['id_stock'],
                $stockArray[$i] ['quantite_produit'],
                $dateEntre,
                $dateSortie,
                $datePeremption,
                $stockArray[$i] ['desc_produit'],
                $stockArray[$i] ['id_produit'],
                $stockArray[$i] ['id_entrepot']
            );
            $stock[$i]->setIndexProduit(selectDB("PRODUIT", "nom_produit", "id_produit=".$stockArray[$i]['id_produit'])[0]);
            $stock[$i]->setentrepot(selectDB("ENTREPOTS", "nom_entrepot", "id_entrepot=".$stockArray[$i]['id_entrepot'])[0]);

        }
        return $stock;
    }

    //-----------------------------------------------------------------------------------------------------------------------------------------------

    public function createStock(StockModel $stock)

    {
        $create = insertDB("STOCKS", [ "quantite_produit", "date_entree", "date_sortie", "date_peremption", "desc_produit", "id_produit", "id_entrepot" ], [
            $stock->quantite_produit,
            $stock->date_entree,
            $stock->date_sortie,
            $stock->date_peremption,
            $stock->desc_produit,
            $stock->id_produit,
            $stock->id_entrepot
        ],"-@");

        if(!$create){
            exit_with_message("Error, the Stock can't be created, plz try again", 500);
        }

        return $create;
    }

//------------------------------------------------------------------------------------------------------

    public function getAllProduitsInEntrepots($id, $produit)
    {
        $string_entrepots = "id_entrepot=" .$id ;
        $entrepots= selectDB("ENTREPOTS", "id_entrepot",$string_entrepots,"bool");

        if(!$entrepots){
            exit_with_message("cette entrepot n'existe pas ", 200);
        }

        $string_produits = "id_produit=" .$produit ;
        $produits= selectDB("PRODUIT", "id_produit",$string_produits,"bool");

        if(!$produits){
            exit_with_message("ce produit n'existe pas  ", 200);
        }

        $string = "id_entrepot=" .$id . " and " . "id_produit=". $produit;
        $stockArray = selectDB("STOCKS", "*",$string,);

        $stock = [];



        for ($i = 0; $i < count($stockArray); $i++) {
            $dateEntre = $stockArray[$i]['date_entree'] ? date('Y-m-d', strtotime($stockArray[$i]['date_entre'])) : 'Non définie';
            $dateSortie = $stockArray[$i]['date_sortie'] ? date('Y-m-d', strtotime($stockArray[$i]['date_sortie'])) : 'Non définie';
            $datePeremption = $stockArray[$i]['date_peremption'] ? date('Y-m-d', strtotime($stockArray[$i]['date_peremption'])) : 'Non définie';
        }

        for ($i = 0; $i < count($stockArray); $i++) {

            $stock[$i] = new StockModel(
                $stockArray[$i] ['id_stock'],
                $stockArray[$i] ['quantite_produit'],
                $dateEntre,
                $dateSortie,
                $datePeremption,
                $stockArray[$i] ['desc_produit'],
                $stockArray[$i] ['id_produit'],
                $stockArray[$i] ['id_entrepot']
            );
            $stock[$i]->setIndexProduit(selectDB("PRODUIT", "nom_produit", "id_produit=".$stockArray[$i]['id_produit'])[0]);
            $stock[$i]->setentrepot(selectDB("ENTREPOTS", "nom_entrepot", "id_entrepot=".$stockArray[$i]['id_entrepot'])[0]);

        }
        return $stock;
    }
}
?>
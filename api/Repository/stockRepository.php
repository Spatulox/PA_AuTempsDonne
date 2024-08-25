<?php
include_once './Repository/BDD.php';
include_once './exceptions.php';
include_once './Models/stockModel.php';


class StockRepository
{
    function __construct()
    {

    }


    public function getAllStock()
    {

        $stockArray = selectDB("STOCKS", "*", " quantite_produit >0 AND date_sortie IS NULL");

        $stock = [];

        for ($i = 0; $i < count($stockArray); $i++) {

            $dateEntre = $stockArray[$i]['date_entree'] ? date('Y-m-d', strtotime($stockArray[$i]['date_entree'])) : 'Non définie';
            $dateSortie = $stockArray[$i]['date_sortie'] ? date('Y-m-d', strtotime($stockArray[$i]['date_sortie'])) : 'Non définie';
            $datePeremption = $stockArray[$i]['date_peremption'] ? date('Y-m-d', strtotime($stockArray[$i]['date_peremption'])) : 'Non définie';

            $stock[$i] = new StockModel(
                $stockArray[$i] ['id_stock'],
                $stockArray[$i] ['quantite_produit'],
                $dateEntre,
                $dateSortie,
                $datePeremption,
                $stockArray[$i] ['desc_produit'],
                $stockArray[$i] ['id_produit'],
                $stockArray[$i] ['id_etagere']
            );
            $stock[$i]->setIndexProduit(selectDB("PRODUIT", "nom_produit", "id_produit=" . $stockArray[$i]['id_produit'])[0]);
            $string = "INNER JOIN ENTREPOTS ON ENTREPOTS.id_entrepot = ETAGERES.id_entrepot";
            $stock[$i]->setentrepot(selectJoinDB("ETAGERES", "ETAGERES.id_entrepot ,ENTREPOTS.nom_entrepot", $string, "id_etagere=" . $stockArray[$i]['id_etagere'])[0]);
        }
        return $stock;
    }

//-----------------------------------------------------------------------------------------------------------------------------------------------

    public function getAllStockInEntrepots($id)
    {
        $string = "id_entrepot=" . $id;
        $entrepots = selectDB("ENTREPOTS", "id_entrepot", $string,"bool");

        if (!$entrepots) {
            exit_with_message("cette entrepot n'existe pas ", 200);
        }
        $string_join = "INNER JOIN ETAGERES ON STOCKS.id_etagere = ETAGERES.id_etagere INNER JOIN ENTREPOTS ON ETAGERES.id_entrepot = ENTREPOTS.id_entrepot ";;
        $stockArray = selectJoinDB("STOCKS", "*", $string_join, "ENTREPOTS.$string"." AND quantite_produit >0 AND date_sortie IS NULL");


        $stock = [];

        for ($i = 0; $i < count($stockArray); $i++) {

            $dateEntre = $stockArray[$i]['date_entree'] ? date('Y-m-d', strtotime($stockArray[$i]['date_entree'])) : 'Non définie';
            $dateSortie = $stockArray[$i]['date_sortie'] ? date('Y-m-d', strtotime($stockArray[$i]['date_sortie'])) : 'Non définie';
            $datePeremption = $stockArray[$i]['date_peremption'] ? date('Y-m-d', strtotime($stockArray[$i]['date_peremption'])) : 'Non définie';

            $stock[$i] = new StockModel(
                $stockArray[$i]['id_stock'],
                $stockArray[$i]['quantite_produit'],
                $dateEntre,
                $dateSortie,
                $datePeremption,
                $stockArray[$i]['desc_produit'],
                $stockArray[$i]['id_produit'],
                $stockArray[$i]['id_etagere']
            );
            $stock[$i]->setIndexProduit(selectDB("PRODUIT", "nom_produit", "id_produit=" . $stockArray[$i]['id_produit'])[0]);
            $string = "INNER JOIN ENTREPOTS ON ENTREPOTS.id_entrepot = ETAGERES.id_entrepot";
            $stock[$i]->setentrepot(selectJoinDB("ETAGERES", "ETAGERES.id_entrepot ,ENTREPOTS.nom_entrepot", $string, "id_etagere=" . $stockArray[$i]['id_etagere'])[0]);

        }
        return $stock;
    }

    //-----------------------------------------------------------------------------------------------------------------------------------------------

    public function createStock(StockModel $stock)

    {

        $create = insertDB("STOCKS", ["quantite_produit", "date_entree", "date_sortie", "date_peremption", "desc_produit", "id_produit", "id_etagere"], [
            $stock->quantite_produit,
            $stock->date_entree,
            $stock->date_sortie,
            $stock->date_peremption,
            $stock->desc_produit,
            $stock->id_produit,
            $stock->id_etagere
        ]);

        if (!$create) {
            exit_with_message("Error, the Stock can't be created, plz try again", 500);
        }

        return $create;
    }

//------------------------------------------------------------------------------------------------------

    public function getAllProduitsInEntrepots($id, $produit)
    {
        $string_entrepots = "id_entrepot=" . $id;
        $entrepots = selectDB("ENTREPOTS", "*", $string_entrepots, "bool");

        if (!$entrepots) {
            exit_with_message("Cet entrepot n'existe pas ", 200);
        }

        $string_produits = "id_produit=" . $produit;
        $produits = selectDB("PRODUIT", "id_produit", $string_produits, "bool");


        if (!$produits) {
            exit_with_message("Ce produit n'existe pas  ", 200);
        }


        $string = "ENTREPOTS.id_entrepot=" . $id . " and " . "STOCKS.id_produit=" . $produit;
        $join= "INNER JOIN ETAGERES ON ETAGERES.id_etagere = STOCKS.id_etagere INNER JOIN ENTREPOTS ON ENTREPOTS.id_entrepot = ETAGERES.id_entrepot";
        $colums="STOCKS.id_stock, STOCKS.quantite_produit, STOCKS.date_entree, STOCKS.date_sortie, STOCKS.date_peremption, STOCKS.desc_produit, STOCKS.id_produit,STOCKS.id_etagere ";
        $stockArray = selectJoinDB("STOCKS", $colums ,$join , $string,"bool");

        $stock = [];


        for ($i = 0; $i < count($stockArray); $i++) {

            $dateEntre = $stockArray[$i]['date_entree'];
            $dateSortie = $stockArray[$i]['date_sortie'];
            $datePeremption = $stockArray[$i]['date_peremption'];

            $stock[$i] = new StockModel(
                $stockArray[$i] ['id_stock'],
                $stockArray[$i] ['quantite_produit'],
                $dateEntre,
                $dateSortie,
                $datePeremption,
                $stockArray[$i] ['desc_produit'],
                $stockArray[$i] ['id_produit'],
                $stockArray[$i] ['id_etagere']
            );
            $stock[$i]->setIndexProduit(selectDB("PRODUIT", "nom_produit", "id_produit=" . $stockArray[$i]['id_produit'])[0]);
            $string = "INNER JOIN ENTREPOTS ON ENTREPOTS.id_entrepot = ETAGERES.id_entrepot";
            $stock[$i]->setentrepot(selectJoinDB("ETAGERES", "ETAGERES.id_entrepot ,ENTREPOTS.nom_entrepot", $string, "id_etagere=" . $stockArray[$i]['id_etagere'])[0]);

        }

        return $stock;
    }

    //-------------------------------------------------------------------------------------------------------------------------------------------


    public function deleteStock($id)
    {
        $request = deleteDB("STOCKS", "id_stock=" . $id, "bool");

        if ($request) {
            exit_with_message("Stock has been deleted", 200);
        }

        exit_with_message("Stock not deleted", 200);

    }

//-------------------------------------------------------------------------------------------------------------------------------------------

    public function addStock(array $tabb, StockModel $stock, $msg, $tabRenvoyer)
    {
        for ($i = 0; $i < count($tabb); $i++) {
            $id_etagere = array_keys($tabb)[$i];
            $quantite = $tabb[$id_etagere];

            $create = insertDB("STOCKS", ["quantite_produit", "date_entree", "date_sortie", "date_peremption", "desc_produit", "id_produit", "id_etagere"], [
                $quantite,
                $stock->date_entree,
                $stock->date_sortie,
                $stock->date_peremption,
                $stock->desc_produit,
                $stock->id_produit,
                $id_etagere
            ],);

            if (!$create) {
                exit_with_message("Error, the Stock can't be created, plz try again", 500);
            }
           $msg_tab[] = 'il faut ranger ' . $quantite . "l dans la " . $id_etagere. " étagère";
        }
        return array('create' => $create, 'msg' => $msg, 'msg_tab' => $msg_tab);
    }

    //----------------------------------------------------------------------------------------------------------

    public function getAllStockdateSortie($date)
    {
        $string = "date_sortie='" . $date."'";

        $string_join = "INNER JOIN ETAGERES ON STOCKS.id_etagere = ETAGERES.id_etagere INNER JOIN ENTREPOTS ON ETAGERES.id_entrepot = ENTREPOTS.id_entrepot ";;
        $stockArray = selectJoinDB("STOCKS", "*", $string_join, "STOCKS.$string"." AND quantite_produit >0 AND date_entree IS NULL","-@");


        $stock = [];

        for ($i = 0; $i < count($stockArray); $i++) {

            $dateEntre = $stockArray[$i]['date_entree'] ? date('Y-m-d', strtotime($stockArray[$i]['date_entree'])) : 'Non définie';
            $dateSortie = $stockArray[$i]['date_sortie'] ? date('Y-m-d', strtotime($stockArray[$i]['date_sortie'])) : 'Non définie';
            $datePeremption = $stockArray[$i]['date_peremption'] ? date('Y-m-d', strtotime($stockArray[$i]['date_peremption'])) : 'Non définie';

            $stock[$i] = new StockModel(
                $stockArray[$i]['id_stock'],
                $stockArray[$i]['quantite_produit'],
                $dateEntre,
                $dateSortie,
                $datePeremption,
                $stockArray[$i]['desc_produit'],
                $stockArray[$i]['id_produit'],
                $stockArray[$i]['id_etagere']
            );
            $stock[$i]->setIndexProduit(selectDB("PRODUIT", "nom_produit", "id_produit=" . $stockArray[$i]['id_produit'])[0]);
            $string = "INNER JOIN ENTREPOTS ON ENTREPOTS.id_entrepot = ETAGERES.id_entrepot";
            $stock[$i]->setentrepot(selectJoinDB("ETAGERES", "ETAGERES.id_entrepot ,ENTREPOTS.nom_entrepot", $string, "id_etagere=" . $stockArray[$i]['id_etagere'])[0]);

        }
        return $stock;
    }

    //----------------------------------------------------------------------------------------------------------------------------

    public function getAllStockSortie($id)
    {
        $join="INNER JOIN ETAGERES et on et.id_etagere = s.id_etagere INNER JOIN ENTREPOTS en on en.id_entrepot = et.id_entrepot";
        $stockArray = selectjoinDB("STOCKS s", "*",$join, "et.id_entrepot=".$id." AND quantite_produit >0 AND date_entree IS NULL","bool");

        if (!$stockArray){
            exit_with_message("Pas de sorti de Stock dans cet entrepot", 500);
        }

        $stock = [];

        for ($i = 0; $i < count($stockArray); $i++) {

            $dateEntre = $stockArray[$i]['date_entree'] ? date('Y-m-d', strtotime($stockArray[$i]['date_entree'])) : 'Non définie';
            $dateSortie = $stockArray[$i]['date_sortie'] ? date('Y-m-d', strtotime($stockArray[$i]['date_sortie'])) : 'Non définie';
            $datePeremption = $stockArray[$i]['date_peremption'] ? date('Y-m-d', strtotime($stockArray[$i]['date_peremption'])) : 'Non définie';

            $stock[$i] = new StockModel(
                $stockArray[$i] ['id_stock'],
                $stockArray[$i] ['quantite_produit'],
                $dateEntre,
                $dateSortie,
                $datePeremption,
                $stockArray[$i] ['desc_produit'],
                $stockArray[$i] ['id_produit'],
                $stockArray[$i] ['id_etagere']
            );
            $stock[$i]->setIndexProduit(selectDB("PRODUIT", "nom_produit", "id_produit=" . $stockArray[$i]['id_produit'])[0]);
            $string = "INNER JOIN ENTREPOTS ON ENTREPOTS.id_entrepot = ETAGERES.id_entrepot";
            $stock[$i]->setentrepot(selectJoinDB("ETAGERES", "ETAGERES.id_entrepot ,ENTREPOTS.nom_entrepot", $string, "id_etagere=" . $stockArray[$i]['id_etagere'])[0]);


        }
        return $stock;

    }

}
?>
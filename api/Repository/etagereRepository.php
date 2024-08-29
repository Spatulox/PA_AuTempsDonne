<?php
include_once './Repository/BDD.php';
include_once './exceptions.php';
include_once './Models/stockModel.php';


class EtagereRepository
{

    public function getEtagereStockInEntrepot($Key)
    {
        $res=selectDB("ETAGERES","*","code='".$Key."'","bool");
       if(!$res){
           exit_with_message("Cette etagere n'existe pas",500);
       }

        $string_join = "INNER JOIN ETAGERES ON STOCKS.id_etagere = ETAGERES.id_etagere";
        $stockArray = selectJoinDB("STOCKS", "*", $string_join, "ETAGERES.id_etagere=".$res[0]["id_etagere"] ." AND quantite_produit >0 AND date_sortie IS NULL","bool");

        if (!$stockArray){
            exit_with_message("Cette etagere n'a rien de stocké",500);
        }

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
        exit_with_content($stock,200);
    }
}
?>
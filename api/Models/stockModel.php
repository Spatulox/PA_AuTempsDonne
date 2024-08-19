<?php

include_once './Repository/BDD.php';

class StockModel
{
    public $id_stock;
    public $quantite_produit;
    public $m3;
    public $date_entree;
    public $date_sortie;
    public $date_peremption;
    public $desc_produit;
    public $id_produit;
    public $produit_desc;
    public $id_etagere;
    public $id_entrepot;
    public $entrepot_desc;


    public function __construct($id_stock, $quantite_produit,$m3, $date_entree, $date_sortie, $date_peremption,$desc_produit,$id_produit,$id_etagere) {
        $this->id_stock = $id_stock;
        $this->quantite_produit = $quantite_produit;
        $this->m3 = $m3;
        $this->date_entree = $date_entree;
        $this->date_sortie = $date_sortie;
        $this->date_peremption = $date_peremption;
        $this->desc_produit = $desc_produit;
        $this->id_produit = $id_produit;
        $this->id_etagere = $id_etagere;
    }

    public function setentrepot($ENTREPOTS){
        $this->id_entrepot = $ENTREPOTS[0];
        $this->entrepot_desc = $ENTREPOTS[1];

    }

    public function setIndexProduit($produit){
        $this->produit_desc = $produit[0];
    }

}
?>
<?php

include_once './Repository/BDD.php';

class StockModel
{
    public $id_stock;
    public $quantite_produit;
    public $date_entre;
    public $date_sortie;
    public $date_peremption;
    public $desc_produit;
    public $id_produit;
    public $id_entrepots;
    public $entrepot_desc;
    public $produit_desc;

    public function __construct($id_stock, $quantite_produit, $date_entre, $date_sortie, $date_peremption,$desc_produit,$id_produit,$id_entrepots) {
        $this->id_stock = $id_stock;
        $this->quantite_produit = $quantite_produit;
        $this->date_entre = $date_entre;
        $this->date_sortie = $date_sortie;
        $this->date_peremption = $date_peremption;
        $this->desc_produit = $desc_produit;
        $this->id_produit = $id_produit;
        $this->id_entrepots = $id_entrepots;
    }

    public function setentrepot(){
        $this->entrepot_desc = [0];
    }

    public function setIndexProduit(){
        $this->produit_desc = [0];
    }

}
?>
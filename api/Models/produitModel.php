<?php

class ProduitModel
{
    public $id_produit;
    public $nom_produit;
    public $id_type;
    public $type_string;

    public function __construct($id_produit, $nom_produit, $id_type, $type_string) {
        $this->id_produit = $id_produit;
        $this->nom_produit = $nom_produit;
        $this->id_type = $id_type;
        $this->type_string = $type_string;
    }
}
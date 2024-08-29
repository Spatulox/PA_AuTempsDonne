<?php

include_once './Repository/BDD.php';

class recetteModel {
    
    public $id_recette;
    public $nom_recette;
    public $description_recette;
    public $quantite_recette;
    public $unit_mesure_ingredient;


    public function __construct($id_recette, $nom_recette,$description_recette,$quantite_recette,$unit_mesure_ingredient) {
        $this->id_recette = $id_recette;
        $this->nom_recette = $nom_recette;
        $this->description_recette = $description_recette;
        $this->quantite_recette = $quantite_recette;
        $this->unit_mesure_ingredient = $unit_mesure_ingredient;
    }

}

?>
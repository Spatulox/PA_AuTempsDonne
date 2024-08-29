<?php

include_once './Repository/BDD.php';

class IngredientModels{
    public $id_ingredient;
    public $nom_ingredient;
    public $unit_mesure;

    public function __construct($id_ingredient, $nom_ingredient,$unit_mesure) {
        $this->id_ingredient = $id_ingredient;
        $this->nom_ingredient = $nom_ingredient;
        $this->unit_mesure = $unit_mesure;
    }

}


?>
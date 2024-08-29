<?php

include_once './Repository/BDD.php';

class recetteModel {

    public $id_recette;
    public $nom_recette;
    public $description_recette;
    public $liste;


    public function __construct($id_recette, $nom_recette,$description_recette,$liste) {
        $this->id_recette = $id_recette;
        $this->nom_recette = $nom_recette;
        $this->description_recette = $description_recette;
        $this->liste = $liste;
    }

}

?>
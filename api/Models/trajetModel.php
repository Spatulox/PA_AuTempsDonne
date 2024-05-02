<?php

include_once './Repository/BDD.php';

class TrajetModel {

    public $id_trajet;
    public $id_adresse;

    public function __construct($id_trajet, $id_adresse) {
        $this->id_trajet = $id_trajet;
        $this->id_adresse = $id_adresse;
    }
}

?>
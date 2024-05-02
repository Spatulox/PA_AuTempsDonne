<?php

include_once './Repository/BDD.php';

class adresseModel {

    public $id_adresse;
    public $adresse;

    public function __construct($id_adresse, $adresse) {
        $this->id_adresse = $id_adresse;
        $this->adresse = $adresse;
    }
}

?>
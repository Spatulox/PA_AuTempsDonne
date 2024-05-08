<?php

include_once './Repository/BDD.php';

class TrajetModel {

    public $id_trajet;
    public $id_adresses;


    public function __construct($id_trajet, $id_adresses) {
        $this->id_trajet = $id_trajet;
        $this->id_adresses = $id_adresses;
    }



}

?>
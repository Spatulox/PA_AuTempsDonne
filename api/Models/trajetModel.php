<?php

include_once './Repository/BDD.php';

class TrajetModel {

    public $id_trajet;
    public $id_adresses;
    public ?VehiculeModel $vehicule;


    public function __construct($id_trajet, $id_adresses, VehiculeModel $vehicule = null) {
        $this->id_trajet = $id_trajet;
        $this->id_adresses = $id_adresses;
        $this->vehicule = $vehicule;
    }



}

?>
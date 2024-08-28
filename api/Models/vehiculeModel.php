<?php

include_once "serviceModel.php";

class VehiculeModel
{
    public $id_vehicule;
    public $nom_du_vehicules;
    public $id_owner;
    public $capacite;
    public $nombre_de_place;
    public $id_entrepot;
    public $appartenance;
    public $immatriculation;
    public $contact = null;
    public $services = [];

    public function __construct($id_vehicule, $capacite, $nom_du_vehicules, $nombre_de_place, $id_entrepot, $appartenance, $immatriculation, $id_owner)
    {
        $this->id_vehicule = $id_vehicule;
        $this->capacite = $capacite;
        $this->nom_du_vehicules = $nom_du_vehicules;
        $this->nombre_de_place = $nombre_de_place;
        $this->id_entrepot = $id_entrepot;
        $this->appartenance = $appartenance;
        $this->immatriculation = $immatriculation;
        $this->id_owner = $id_owner;
    }

    public function addService(ServiceModel $service){
        $this->services[] = $service;
    }

    public function addContact(MiniUserModel $user){
        $this->contact[] = $user;
    }


}

?>
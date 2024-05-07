<?php

class VehiculeModel
{
    public $id_vehicule;
    public $capacite;
    public $nom_du_vehicules;
    public $nombre_de_place;
    public $id_entrepot;

    public function __construct($id_vehicule, $capacite, $nom_du_vehicules, $nombre_de_place, $id_entrepot)
    {
        $this->id_vehicule = $id_vehicule;
        $this->capacite = $capacite;
        $this->nom_du_vehicules = $nom_du_vehicules;
        $this->nombre_de_place = $nombre_de_place;
        $this->id_entrepot = $id_entrepot;
    }
}

?>
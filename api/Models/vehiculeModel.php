<?php

class VehiculeModel
{
    public $id_vehicule;
    public $capacite;
    public $nom_du_vehicules;
    public $nombre_de_place;
    public $id_entrepot;

    public function __construct($id_vehicule, $capacite, $nombre_de_place, $nom_du_vehicule,$id_entrepot)
    {
        $this->id_vehicule = $id_vehicule;
        $this->capacite = $capacite;
        $this->nom_du_vehicules = $nom_du_vehicule;
        $this->nombre_de_place = $nombre_de_place ;
        $this->id_entrepot = $id_entrepot;
    }
}
?>
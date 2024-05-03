<?php

class VehiculeModel
{
    public $id_vehicule;
    public $capacite;
    public $id_nom_du_vehicule;
    public $nombre_de_place;
    public $id_entrepot;
    
    public function __construct($id_vehicule, $capacite, $nombre_de_place, $id_nom_du_vehicule)
    {
        $this->id_vehicule = $id_vehicule;
        $this->capacite = $capacite;
        $this->nombre_de_place = $nombre_de_place;
        $this->id_nom_du_vehicule = $id_nom_du_vehicule;
    }
}
?>
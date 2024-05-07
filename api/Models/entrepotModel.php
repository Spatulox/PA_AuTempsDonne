<?php

class EntrepotModel {
    public $id_entrepot;
    public $nom;
    public $parking;
    public $id_addresse;
    public $addresse_desc;
    public $rangement;


    public function __construct($id_entrepot, $nom, $parking,$id_addresse,$rangement) {
        $this->id_entrepot = $id_entrepot;
        $this->nom = $nom;
        $this->parking = $parking;
        $this->id_addresse =$id_addresse;
        $this->rangement = $rangement;
    }

    public function setAddresse($addresse){
        $this->addresse_desc = $addresse[0];
    }
}

?>

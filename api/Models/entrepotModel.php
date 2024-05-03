<?php

class EntrepotModel {
    public $id_entrepot;
    public $nom;
    public $localisation;

    public function __construct($id_entrepot, $nom, $localisation) {
        $this->id_entrepot = $id_entrepot;
        $this->nom = $nom;
        $this->localisation = $localisation;
    }
}

?>

<?php

include_once './Repository/BDD.php';

class ActiviteModels{
    public $id_activite;
    public $nom_activite;

    public function __construct($id_activite, $nom_activite) {
        $this->id_activite = $id_activite;
        $this->nom_activite = $nom_activite;
    }

}


?>
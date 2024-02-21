<?php
include_once './Repository/BDD.php';

class ActiviteModel {
    public $id_activite;
    public $nom;
    public $description;
    public $date_activite;
    public $index_activite; 

    public function __construct($id_activite, $nom, $description, $date_activite, $index_activite) {
        $this->id_activite = $id_activite;
        $this->nom = $nom;
        $this->description = $description;
        $this->date_activite = $date_activite;
        $this->index_activite = $index_activite;
    }
}
?>
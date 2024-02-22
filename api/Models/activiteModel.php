<?php
include_once './Repository/BDD.php';

class ActiviteModel {
    public $id_activite;
    public $nom_activite;
    public $type_activite;
    public $date_activite;
    public $index_activite; 

    public function __construct($id_activite, $nom_activite, $date_activite, $index_activite) {
        $this->id_activite = $id_activite;
        $this->nom_activite = $nom_activite;
        $this->type_activite = $type_activite;
        $this->date_activite = $date_activite;
        $this->index_activite = $index_activite;
    }
}
?>
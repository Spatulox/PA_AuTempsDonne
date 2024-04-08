<?php

include_once './Repository/BDD.php';

class PlanningModel {
    public $id_planning;
    public $description;
    public $lieux;
    public $date_activite;
    public $id_index;
    public $id_activite;
    public $activity_desc;

    public function __construct($id_user, $description, $lieux, $date_activite, $id_index, $id_activite) {
        $this->id_planning = $id_planning;
        $this->description = $description;
        $this->lieux = $lieux;
        $this->date_activite = $date_activite;
        $this->id_index = $id_index;
        $this->id_activite = $id_activite;
    }

    public function setId($id){
        $this->id_planning = $id;
    }

    public function setActivity($activity){
        $this->activity_desc = $activity[0];
    }
}


class ActiviteModel {
    public $id_activite;
    public $nom_activite;

    public function __construct($id_activite, $nom_activite) {
        $this->id_activite = $id_activite;
        $this->nom_activite = $nom_activite;
    }

}


?>

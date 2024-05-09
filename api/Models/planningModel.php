<?php

include_once './Repository/BDD.php';

class PlanningModel {
    public $id_planning;
    public $description;
    public $date_activite;
    public $id_index_planning;
    public $nom_index_planning;
    public $id_activite;
    public $activity_desc;
    public $user;

    public function __construct($id_planning, $description, $date_activite, $id_index_planning, $id_activite) {
        $this->id_planning = $id_planning;
        $this->description = $description;

        $this->date_activite = $date_activite;

        $this->id_index_planning = $id_index_planning;
        $this->id_activite = $id_activite;
    }

    public function setId($id){
        $this->id_planning = $id;
    }

    public function setActivity($activity){
        $this->activity_desc = $activity[0];
    }

    public function setIndexPlanning($id_index_planning){
        $this->nom_index_planning = $id_index_planning[0];
    }
    public function setemailuser($user){
        $this->user = $user;
    }

}?>
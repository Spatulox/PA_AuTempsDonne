<?php
include_once './Repository/BDD.php';
include_once './Models/organisationModel.php';
include_once './exceptions.php';

class PlanningRepository {
    private $connection = null;

   
    function __construct() {
       
    }
    
    //-------------------------------------

    public function getAllPlanning($apiKey){
        $planningArray = selectDB("PLANNING", "*", "id_user=(SELECT id_user FROM UTILISATEUR WHERE apikey='".$apiKey."')");

        $planning = [];

        for ($i=0; $i < count($planningArray); $i++) {
            $planning[$i] = new PlanningModel(
                $planningArray[$i]['id_planning'],
                $planningArray[$i]['description'],
                $planningArray[$i]['lieux'],
                $planningArray[$i]['date_activite'],
                $planningArray[$i]['id_index'],
                $planningArray[$i]['id_activite']
            );
            $planning[$i]->setId($planningArray[$i]['id_planning']);
        }
        return $planning;
    }

    //-------------------------------------

    public function getPlanningById($id){
        $planning = selectDB("PLANNING", "*", "id_planning='".$id."'");
        return new PlanningModel(
            $planningArray[$i]['id_planning'],
            $planningArray[$i]['description'],
            $planningArray[$i]['lieux'],
            $planningArray[$i]['date_activite'],
            $planningArray[$i]['id_index'],
            $planningArray[$i]['id_activite']
        );
    }



}
?>

<?php
include_once './Repository/BDD.php';
include_once './Models/organisationModel.php';
include_once './exceptions.php';

class PlanningRepository {
    private $connection = null;

   
    function __construct() {
       
    }
    
    //-------------------------------------

    public function getAllPlanning(){
        $planningArray = selectDB("PLANNINGS", "*");

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

            $planning[$i]->setActivity(selectDB("ACTIVITES", "nom_activite", "id_activite=".$planningArray[$i]['id_activite'])[0]);
        }
        return $planning;
    }

    //-------------------------------------

    public function getPlanningById($id){
        $planning = selectDB("PLANNINGS", "*", "id_planning='".$id."'")[0];
        
        $planningModel = new PlanningModel(
            $planning['id_planning'],
            $planning['description'],
            $planning['lieux'],
            $planning['date_activite'],
            $planning['id_index'],
            $planning['id_activite']
            
        );

        $planningModel->setActivity(selectDB("ACTIVITES", "nom_activite", "id_activite=".$planning['id_activite'])[0]);
        
        return $planningModel;
    }

    //-------------------------------------
    
    public function createPlanning(PlanningModel $planning){
        $id_planning = insertDB("PLANNINGS", ["id_planning", "description", "lieux", "date_activite", "id_index", "id_activite"], [
            $planning->id_planning,
            $planning->description,
            $planning->lieux,
            $planning->date_activite,
            $planning->id_index,
            $planning->id_activite
        ]);

        if(!$id_planning){
            exit_with_message("Error, the planning can't be created, plz try again", 500);
        }

        $planning->setId($id_planning);
        return $planning;
    }

    //-------------------------------------
    
    public function updatePlanning(PlanningModel $planning){
        $id_planning = updateDB("PLANNINGS", ["id_planning", "description", "lieux", "date_activite", "id_index", "id_activite"], [
            $planning->id_planning,
            $planning->description,
            $planning->lieux,
            $planning->date_activite,
            $planning->id_index,
            $planning->id_activite
        ], "id_planning=".$planning->getId());

        if(!$id_planning){
            exit_with_message("Error, the planning can't be updated, plz try again", 500);
        }

        return $planning;
    }

    //-------------------------------------

    public function deletePlanning($id){
        $deleted = deleteDB("PLANNINGS", "id_planning=".$id);

        if(!$deleted){
            exit_with_message("Error, the planning can't be deleted, plz try again", 500);
        }

        return true;
    }

     //-------------------------------------


    public function joinActivity($userId, $planningId) {
    // a faire verifie que l'utilisateur et l'activité existent avant d'effectuer l'opération.

    $query = "INSERT INTO PARTICIPE (id_user, id_planning) VALUES (?, ?)";
    $statement = $this->connection->prepare($query);
    $success = $statement->execute([$userId, $planningId]);

    //a faire verifier si l'opération d'insertion a réussi
    if ($success) {
        return true;
    } else {
        return false;
    }
}

}
?>

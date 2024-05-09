<?php
include_once './Repository/BDD.php';
include_once './Models/planningModel.php';
include_once './exceptions.php';
include_once './index.php';

class PlanningRepository {

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
                $planningArray[$i]['date_activite'],
                $planningArray[$i]['id_index_planning'],
                $planningArray[$i]['id_activite']
            );
            $planning[$i]->setId($planningArray[$i]['id_planning']);

            $planning[$i]->setIndexPlanning(selectDB("INDEXPLANNING", "index_nom_planning", "id_index_planning=".$planningArray[$i]['id_index_planning'])[0]);
            $planning[$i]->setActivity(selectDB("ACTIVITES", "nom_activite", "id_activite=".$planningArray[$i]['id_activite'])[0]);
        }
        return $planning;
    }

    //--------------------------------------------------------------------------

    public function getPlanningByid($id){
        $planningArray = selectDB("PLANNINGS", "*", "id_planning=".$id);


        $planning = [];

        for ($i=0; $i < count($planningArray); $i++) {
            $planning[$i] = new PlanningModel(
                $planningArray[$i]['id_planning'],
                $planningArray[$i]['description'],
                $planningArray[$i]['date_activite'],
                $planningArray[$i]['id_index_planning'],
                $planningArray[$i]['id_activite']
            );
            $planning[$i]->setId($planningArray[$i]['id_planning']);

            $planning[$i]->setIndexPlanning(selectDB("INDEXPLANNING", "index_nom_planning", "id_index_planning=".$planningArray[$i]['id_index_planning'])[0]);
            $planning[$i]->setActivity(selectDB("ACTIVITES", "nom_activite", "id_activite=".$planningArray[$i]['id_activite'])[0]);
        }
        return $planning[0];
    }

    //-----------------------------------------------------------------------------------------------------------------------------

        public function getPlanningByUser($apiKey)
        {
        $id = getIdUserFromApiKey($apiKey);
        $id_planning = selectDB("PARTICIPE", "id_planning", "id_user='" . $id . "'", "bool");

        if($id_planning == false){
            exit_with_message("No Planning found for you");
        }

        $allPlanning = [];

        foreach ($id_planning as $planning_id) {
            $planningArray = selectDB("PLANNINGS", "*", "id_planning='" . $planning_id[0] . "'");

            foreach ($planningArray as $planningData) {
                $planning = new PlanningModel(
                    $planningData['id_planning'],
                    $planningData['description'],
                    $planningData['date_activite'],
                    $planningData['id_index_planning'],
                    $planningData['id_activite']
                );
                $planning->setId($planningData['id_planning']);
                $planning->setIndexPlanning(selectDB("INDEXPLANNING", "index_nom_planning", "id_index_planning=" . $planningData['id_index_planning'])[0]);
                $planning->setActivity(selectDB("ACTIVITES", "nom_activite", "id_activite=" . $planningData['id_activite'])[0]);

                $allPlanning[] = $planning;
            }
        }

        return $allPlanning;
    }

    //-----------------------------------------------------------------------------------------

    public function getPlanningByIdUser($id){
        $id_planning = selectDB("PARTICIPE", "id_planning", "id_user='" . $id . "'", "bool");

        if($id_planning == false){
            exit_with_message("No Planning found for this user");
        }

        $allPlanning = [];

        foreach ($id_planning as $planning_id) {
            $planningArray = selectDB("PLANNINGS", "*", "id_planning='" . $planning_id[0] . "'");

            foreach ($planningArray as $planningData) {
                $planning = new PlanningModel(
                    $planningData['id_planning'],
                    $planningData['description'],
                    $planningData['date_activite'],
                    $planningData['id_index_planning'],
                    $planningData['id_activite']
                );
                $planning->setId($planningData['id_planning']);
                $planning->setIndexPlanning(selectDB("INDEXPLANNING", "index_nom_planning", "id_index_planning=" . $planningData['id_index_planning'])[0]);
                $planning->setActivity(selectDB("ACTIVITES", "nom_activite", "id_activite=" . $planningData['id_activite'])[0]);

                $allPlanning[] = $planning;
            }
        }

        exit_with_content($allPlanning);
    }


    //-------------------------------------
    
    public function createPlanning(PlanningModel $planning){


        $create = insertDB("PLANNINGS", [ "description", "date_activite", "id_index_planning", "id_activite"], [
            $planning->description,
            $planning->date_activite,
            $planning->id_index_planning,
            $planning->id_activite
        ]);


        if(!$create){
            exit_with_message("Error, the planning can't be created, plz try again", 500);
        }

        $lastId = selectJoinDB("PLANNINGS", "id_planning", "ORDER BY id_planning DESC LIMIT 1");

        exit_with_content($this->getPlanningByid($lastId[0]["id_planning"]),200);
    }

    //--------------------------------------------------------------------------------------------------------------------------
    
    public function updatePlanning(PlanningModel $planning) {
    $updated = updateDB(
        "PLANNINGS",
        [ "description", "date_activite", "id_index_planning", "id_activite"],
        [
            $planning->description,
            $planning->date_activite,
            $planning->id_index_planning,
            $planning->id_activite
        ],
        "id_planning=" . $planning->id_planning
    );

    if (!$updated) {
        exit_with_message("Erreur, le planning n'a pas pu être mis à jour. Veuillez réessayer.", 500);
    }

    return $planning;
}


    //------------------------------------------------------------------------------------------------------------------------------

    public function deletePlanning($id){
        $deleted = deleteDB("PLANNINGS", "id_planning=".$id);

        if(!$deleted){
            exit_with_message("Error, the planning can't be deleted, plz try again", 500);
        }else{
            exit_with_message("Planning deleted",200);
        }

    }

     //------------------------------------------------------------------------------------------------------------------------------


    public function joinActivity($userId, $planningId, $confirme) {

        $user = selectDB("UTILISATEUR", "*", "id_user=".$userId, "bool");

        $planning = selectDB("PLANNINGS", "*", "id_planning=".$planningId, "bool");

        if(!$user){
            exit_with_message("Cet utilisateur n'existe pas");
        }

        if(!$planning){
            exit_with_message("Ce planning n'existe pas");
        }

        $check=selectDB("PARTICIPE", "*", "id_planning=".$planningId." AND id_user=".$userId,"bool");
        if ($check){
            exit_with_message("cette utilisateur est deja inscript",500);
        }

        $create = insertDB("PARTICIPE", [ "id_user", "id_planning","confirme"], [$userId, $planningId ,$confirme]);


        if ($create) {
            exit_with_message("le benevole a bien etait attribuer aux plannings",200);
        } else {
            exit_with_message("le benevole n'a pas peux etre attribuer aux plannings",500);
        }

    }

    //-----------------------------------------------------------------------------------------

    public function linkPlanning(array $planning)
    {
        $check =selectDB("PLANNINGS" , "id_planning" ,"id_planning=" .$planning[1],'bool');
        if (!$check){
            exit_with_message("Erreur: planning n'existe pas ");
        }

        $check =selectDB("TRAJETS" , "id_trajets" ,"id_trajets=" .$planning[0],'bool');

        if (!$check){
            exit_with_message("Erreur: planning n'existe pas ");
        }

        $create = updateDB("PLANNINGS", [ "id_trajets"], [$planning[0]], "id_planning=".$planning[1]);
        if ($create) {
            exit_with_message("le trajet a bien etait attribuer aux plannings",200);
        } else {
            exit_with_message("le trajet n'a pas peux etre attribuer aux plannings",500);
        }
    }

    //-----------------------------------------------------------------------------------------

    public function getAllPlanningeindex(int $index)
    {
        $planningArray = selectDB("PLANNINGS", "*", "id_index_planning=".$index);

        $planning = [];

        for ($i=0; $i < count($planningArray); $i++) {
            $planning[$i] = new PlanningModel(
                $planningArray[$i]['id_planning'],
                $planningArray[$i]['description'],
                $planningArray[$i]['date_activite'],
                $planningArray[$i]['id_index_planning'],
                $planningArray[$i]['id_activite']
            );
            $planning[$i]->setId($planningArray[$i]['id_planning']);

            $planning[$i]->setIndexPlanning(selectDB("INDEXPLANNING", "index_nom_planning", "id_index_planning=".$planningArray[$i]['id_index_planning'])[0]);
            $planning[$i]->setActivity(selectDB("ACTIVITES", "nom_activite", "id_activite=".$planningArray[$i]['id_activite'])[0]);
        }
        return $planning;
    }

    //-----------------------------------------------------------------------------------------

    public function getAllPlanningeDate($date)
    {
        $condition =" id_index_planning=2 AND date_activite BETWEEN '".$date." 00:00:00' AND '".$date." 23:59:59' ORDER BY date_activite ASC";
        $planningArray = selectDB("PLANNINGS", "*",$condition ,"bool");
        if (!$planningArray){
            exit_with_message("Erreur, le planning n'existe pas");
        }

        $planning = [];

        for ($i=0; $i < count($planningArray); $i++) {
            $planning[$i] = new PlanningModel(
                $planningArray[$i]['id_planning'],
                $planningArray[$i]['description'],
                $planningArray[$i]['date_activite'],
                $planningArray[$i]['id_index_planning'],
                $planningArray[$i]['id_activite']
            );
            $planning[$i]->setId($planningArray[$i]['id_planning']);

            $planning[$i]->setIndexPlanning(selectDB("INDEXPLANNING", "index_nom_planning", "id_index_planning=".$planningArray[$i]['id_index_planning'])[0]);
            $planning[$i]->setActivity(selectDB("ACTIVITES", "nom_activite", "id_activite=".$planningArray[$i]['id_activite'])[0]);
        }

        return $planning;
    }

    //-----------------------------------------------------------------------------------------

    public function getPlanningNoAffecte()
    {
        $condition=" PLANNINGS.id_index_planning= 2 AND NOT EXISTS (SELECT 1 FROM PARTICIPE pa WHERE pa.id_planning = PLANNINGS.id_planning)";
        $planningArray = selectDB("PLANNINGS", "*",$condition);

        $planning = [];

        for ($i=0; $i < count($planningArray); $i++) {
            $planning[$i] = new PlanningModel(
                $planningArray[$i]['id_planning'],
                $planningArray[$i]['description'],
                $planningArray[$i]['date_activite'],
                $planningArray[$i]['id_index_planning'],
                $planningArray[$i]['id_activite']
            );
            $planning[$i]->setId($planningArray[$i]['id_planning']);

            $planning[$i]->setIndexPlanning(selectDB("INDEXPLANNING", "index_nom_planning", "id_index_planning=".$planningArray[$i]['id_index_planning'])[0]);
            $planning[$i]->setActivity(selectDB("ACTIVITES", "nom_activite", "id_activite=".$planningArray[$i]['id_activite'])[0]);
        }
        return $planning;
    }

    //---------------------------------------------------------------------------------------------------------------------------------

    public function getPlanningAffecteDate($date)
    {
        $condition = " PLANNINGS.id_index_planning = 2 AND EXISTS (SELECT 1 FROM PARTICIPE pa WHERE pa.id_planning = PLANNINGS.id_planning) AND date_activite BETWEEN '".$date." 00:00:00' AND '".$date." 23:59:59' ORDER BY date_activite ASC";
        $planningArray = selectDB("PLANNINGS", "*", $condition,"bool");

        if (!$planningArray)
        {
            exit_with_message("Erreur, le planning n'existe pas",500);
        }

        $planning = [];

        for ($i = 0; $i < count($planningArray); $i++) {
            $planning[$i] = new PlanningModel(
                $planningArray[$i]['id_planning'],
                $planningArray[$i]['description'],
                $planningArray[$i]['date_activite'],
                $planningArray[$i]['id_index_planning'],
                $planningArray[$i]['id_activite']
            );
            $planning[$i]->setId($planningArray[$i]['id_planning']);

            $planning[$i]->setIndexPlanning(selectDB("INDEXPLANNING", "index_nom_planning", "id_index_planning=" . $planningArray[$i]['id_index_planning'])[0]);
            $planning[$i]->setActivity(selectDB("ACTIVITES", "nom_activite", "id_activite=" . $planningArray[$i]['id_activite'])[0]);

            $res= selectDB("PARTICIPE","*","id_planning=".$planningArray[0]['id_planning']);
            $tab=[];
            for ($j=0; $j < count($res); $j++) {
               array_push($tab, selectDB("UTILISATEUR", "email", "id_user=".$res[$j]['id_user'])[0]["email"]);
            }
            $planning[$i]->setemailuser($tab);
        }

        return $planning;
    }

    //----------------------------------------------------------------------------------------------

    public function updateValidatePlanning($id_index_planning, $id)
    {
        $check = updateDB("PLANNINGS", ["id_index_planning"], [$id_index_planning] ,"id_planning=".$id);

        if(!$check){
            exit_with_message("erreur update planning",500);
        }

        exit_with_content($this->getPlanningByid($id),200);

    }


}
?>
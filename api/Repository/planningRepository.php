<?php
include_once './Repository/BDD.php';
include_once './Models/planningModel.php';
include_once './exceptions.php';
include_once './index.php';
include_once './returnFunctions.php';

class PlanningRepository {

    function __construct() {
       
    }

    public function getTrajetFromId($id,$apiKey) {

        $tabAddresse = [];

        if($id != ""){
            $condition = "t.id_trajets =".$id;
            $join = "JOIN UTILISER u ON t.id_trajets = u.id_trajets JOIN ADRESSE a ON u.id_adresse = a.id_adresse";
            $addresse = selectjoinDB("TRAJETS t", "a.ADRESSE", $join, $condition, "bool");

            if($addresse === false){
                return $tabAddresse;
            }

            for ($i = 0; $i < count($addresse); $i++) {
                $tabAddresse[] = $addresse[$i]["ADRESSE"];
            }
        }
        if ($apiKey != null){
            $historiqueRepo = new HistoriqueRepository();
            $description_hist = " recuperation de trajet.";
            $id_secteur = 4;
            $id_user =getIdUserFromApiKey($apiKey);

            $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        }


        return $tabAddresse;

    }
    
    //-------------------------------------

    public function getAllPlanning($apiKey){
        $planningArray = selectDB("PLANNINGS", "*");

        $planning = [];

        for ($i=0; $i < count($planningArray); $i++) {

            $planning[$i] = returnPlanning($planningArray, $i);
        }

        if ($apiKey != null){
            $historiqueRepo = new HistoriqueRepository();
            $description_hist = " recuperation de planning  .";
            $id_secteur = 4;
            $id_user =getIdUserFromApiKey($apiKey);

            $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        }

        return $planning;
    }

    //--------------------------------------------------------------------------

    public function getPlanningByid($id,$apiKey=null){
        $planningArray = selectDB("PLANNINGS", "*", "id_planning=".$id);


        $planning = [];

        for ($i=0; $i < count($planningArray); $i++) {
            $planning[$i] = returnPlanning($planningArray, $i);
        }
        if ($apiKey != null){
            $historiqueRepo = new HistoriqueRepository();
            $description_hist = " recuperation de planning  .";
            $id_secteur = 4;
            $id_user =getIdUserFromApiKey($apiKey);

            $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        }

        return $planning[0];
    }

    //-----------------------------------------------------------------------------------------------------------------------------

        public function getPlanningByUser($apiKey)
        {

                $id = getIdUserFromApiKey($apiKey);
                $id_planning = selectDB("PARTICIPE", "id_planning", "id_user='" . $id . "'", "bool");

                if ($id_planning == false) {
                    exit_with_message("No Planning found for you", 200);

            }

        $allPlanning = [];

        foreach ($id_planning as $planning_id) {
            $planningArray = selectDB("PLANNINGS", "*", "id_planning='" . $planning_id[0] . "'");

            for ($i=0; $i < count($planningArray); $i++) {
                $planning[$i] = returnPlanning($planningArray, $i);
                $allPlanning[] = $planning[$i];
            }
        }

            if ($apiKey != null){
                $historiqueRepo = new HistoriqueRepository();
                $description_hist = " recuperation de planning  .";
                $id_secteur = 4;
                $id_user =getIdUserFromApiKey($apiKey);

                $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

            }

        return $allPlanning;
    }

    //-----------------------------------------------------------------------------------------

    public function getPlanningByIdUser($id,$apiKey){
        $id_planning = selectDB("PARTICIPE", "id_planning", "id_user='" . $id . "'", "bool");

        if($id_planning == false){
            exit_with_message("No Planning found for this user");
        }

        $allPlanning = [];

        foreach ($id_planning as $planning_id) {
            $planningArray = selectDB("PLANNINGS", "*", "id_planning='" . $planning_id[0] . "'");

            for ($i=0; $i < count($planningArray); $i++) {
                $planning[$i] = returnPlanning($planningArray, $i);
                $allPlanning[] = $planning[$i];
            }
        }

        if ($apiKey != null){
            $historiqueRepo = new HistoriqueRepository();
            $description_hist = " recuperation de planning  .";
            $id_secteur = 4;
            $id_user =getIdUserFromApiKey($apiKey);

            $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        }

        exit_with_content($allPlanning);
    }


    //-------------------------------------
    
    public function createPlanning(PlanningModel $planning,$apiKey){


        $create = insertDB("PLANNINGS", [ "description", "date_activite", "id_index_planning", "id_activite"], [
            $planning->description,
            $planning->date_activite,
            $planning->id_index_planning,
            $planning->id_activite
        ]);


        if(!$create){
            exit_with_message("Error, the planning can't be created, plz try again", 500);
        }
        $userRole=getRoleFromApiKey($apiKey);
        $id_user=getIdUserFromApiKey($apiKey);
        $lastId = selectJoinDB("PLANNINGS", "id_planning", "ORDER BY id_planning DESC LIMIT 1");

        if ($userRole==4){
            insertDB("PARTICIPE",["id_user","id_planning"],[$id_user,$lastId[0]["id_planning"]]);
        }

        if ($apiKey != null){
            $historiqueRepo = new HistoriqueRepository();
            $description_hist = " creation de planning  .";
            $id_secteur = 4;
            $id_user =getIdUserFromApiKey($apiKey);

            $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        }

        exit_with_content($this->getPlanningByid($lastId[0]["id_planning"]),200);
    }

    //--------------------------------------------------------------------------------------------------------------------------
    
    public function updatePlanning(PlanningModel $planning,$apiKey) {
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
        if ($apiKey != null){
            $historiqueRepo = new HistoriqueRepository();
            $description_hist = " mise a jour de planning  .";
            $id_secteur = 4;
            $id_user =getIdUserFromApiKey($apiKey);

            $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        }


    exit_with_content($this->getPlanningByid($planning->id_planning,$apiKey));
}


    //------------------------------------------------------------------------------------------------------------------------------

    public function deletePlanning($id,$apiKey){
        $deleted = deleteDB("PLANNINGS", "id_planning=".$id,"bool");
        if(!$deleted){

            if ($apiKey != null){
                $historiqueRepo = new HistoriqueRepository();
                $description_hist = " echec suppression de planning  .";
                $id_secteur = 4;
                $id_user =getIdUserFromApiKey($apiKey);

                $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

            }

            exit_with_message("Error, the planning can't be deleted, plz try again", 500);
        }else{

            if ($apiKey != null){
                $historiqueRepo = new HistoriqueRepository();
                $description_hist = " suppression de planning  .";
                $id_secteur = 4;
                $id_user =getIdUserFromApiKey($apiKey);

                $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

            }

            exit_with_message("Planning deleted",200);
        }

    }

     //------------------------------------------------------------------------------------------------------------------------------


    public function joinActivity($userId, $planningId, $confirme,$apiKey) {

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


            exit_with_message("Cet utilisateur est deja inscrit",500);
        }

        $create = insertDB("PARTICIPE", [ "id_user", "id_planning","confirme"], [$userId, $planningId ,$confirme]);


        if ($create) {

            if ($apiKey != null){
                $historiqueRepo = new HistoriqueRepository();
                $description_hist = " attribution de personnel aux planning  .";
                $id_secteur = 4;
                $id_user =getIdUserFromApiKey($apiKey);

                $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

            }

            exit_with_message("le bénévole à bien été attribué au planning",200);
        } else {

            if ($apiKey != null){
                $historiqueRepo = new HistoriqueRepository();
                $description_hist = "echec attribution de personnel aux planning  .";
                $id_secteur = 4;
                $id_user =getIdUserFromApiKey($apiKey);

                $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

            }

            exit_with_message("le bénévole n'a pas pu être attribué au planning");
        }

    }

    //-----------------------------------------------------------------------------------------

    public function linkPlanning(array $planning,$apiKey)
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

            if ($apiKey != null){
                $historiqueRepo = new HistoriqueRepository();
                $description_hist = " attribution de trajet aux planning  .";
                $id_secteur = 4;
                $id_user =getIdUserFromApiKey($apiKey);

                $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

            }

            exit_with_message("le trajet a bien etait attribuer aux plannings",200);
        } else {

            if ($apiKey != null){
                $historiqueRepo = new HistoriqueRepository();
                $description_hist = "echec attribution de trajet aux planning  .";
                $id_secteur = 4;
                $id_user =getIdUserFromApiKey($apiKey);

                $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

            }

            exit_with_message("le trajet n'a pas peux etre attribuer aux plannings",500);
        }
    }

    //-----------------------------------------------------------------------------------------

    public function getAllPlanningeindex(int $index,$apiKey)
    {
        $planningArray = selectDB("PLANNINGS", "*", "id_index_planning=".$index);

        $planning = [];

        for ($i=0; $i < count($planningArray); $i++) {
            $planning[$i] = returnPlanning($planningArray, $i);
        }

        if ($apiKey != null){
            $historiqueRepo = new HistoriqueRepository();
            $description_hist = "recuperation de planning  .";
            $id_secteur = 4;
            $id_user =getIdUserFromApiKey($apiKey);

            $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        }

        return $planning;
    }

    //-----------------------------------------------------------------------------------------

    public function getAllPlanningeDate($date,$apiKey)
    {
        $condition =" id_index_planning=2 AND date_activite BETWEEN '".$date." 00:00:00' AND '".$date." 23:59:59' ORDER BY date_activite ASC";
        $planningArray = selectDB("PLANNINGS", "*",$condition ,"bool");
        if (!$planningArray){

            $historiqueRepo = new HistoriqueRepository();
            $description_hist = "Produit not deleted .";
            $id_secteur = 4;
            $id_user =getIdUserFromApiKey($apiKey);

            exit_with_message("Erreur, le planning n'existe pas");
        }

        $planning = [];

        for ($i=0; $i < count($planningArray); $i++) {
            $planning[$i] = returnPlanning($planningArray, $i);
        }

        if ($apiKey != null){
            $historiqueRepo = new HistoriqueRepository();
            $description_hist = "recuperation de planning  .";
            $id_secteur = 4;
            $id_user =getIdUserFromApiKey($apiKey);

            $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        }

        return $planning;
    }

    //-----------------------------------------------------------------------------------------

    public function getPlanningNoAffecte($apiKey)
    {
        $condition = " PLANNINGS.id_index_planning = 2 
                   AND NOT EXISTS (
                       SELECT 1 
                       FROM PARTICIPE pa 
                       INNER JOIN UTILISATEUR u ON pa.id_user = u.id_user
                       WHERE pa.id_planning = PLANNINGS.id_planning AND u.id_role = 3)";
        $planningArray = selectDB("PLANNINGS", "*",$condition);

        $planning = [];

        for ($i=0; $i < count($planningArray); $i++) {
            $planning[$i] = returnPlanning($planningArray, $i);
        }

        if ($apiKey != null){
            $historiqueRepo = new HistoriqueRepository();
            $description_hist = "recuperation de planning non affecté .";
            $id_secteur = 4;
            $id_user =getIdUserFromApiKey($apiKey);

            $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        }

        return $planning;
    }

    //---------------------------------------------------------------------------------------------------------------------------------

    public function getPlanningAffecteDate($date,$apiKey)
    {
        $condition = " PLANNINGS.id_index_planning = 2 AND EXISTS (SELECT 1 FROM PARTICIPE pa WHERE pa.id_planning = PLANNINGS.id_planning) AND date_activite BETWEEN '".$date." 00:00:00' AND '".$date." 23:59:59' ORDER BY date_activite ASC";
        $planningArray = selectDB("PLANNINGS", "*", $condition);

        if (!$planningArray)
        {

            if ($apiKey != null){
                $historiqueRepo = new HistoriqueRepository();
                $description_hist = "essay de recuperation de planning affecter .";
                $id_secteur = 4;
                $id_user =getIdUserFromApiKey($apiKey);

                $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

            }

            exit_with_message("Erreur, le planning n'existe pas",500);
        }

        $planning = [];

        for ($i = 0; $i < count($planningArray); $i++) {
            $planning[$i] = returnPlanning($planningArray, $i);
        }

        if ($apiKey != null){
            $historiqueRepo = new HistoriqueRepository();
            $description_hist = "recuperation de planning affecter .";
            $id_secteur = 4;
            $id_user =getIdUserFromApiKey($apiKey);

            $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        }

        return $planning;
    }

    //----------------------------------------------------------------------------------------------

    public function updateValidatePlanning($id_index_planning, $id,$apiKey)
    {
        $check = updateDB("PLANNINGS", ["id_index_planning"], [$id_index_planning] ,"id_planning=".$id);

        if(!$check){
            exit_with_message("Error when updating the planning",500);
        }

        if ($apiKey != null){
            $historiqueRepo = new HistoriqueRepository();
            $description_hist = "Mise a jour planning valider .";
            $id_secteur = 4;
            $id_user =getIdUserFromApiKey($apiKey);

            $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        }

        exit_with_content($this->getPlanningByid($id),200);

    }

    //----------------------------------------------------------------------------------------------------------------------------

    public function updatejoinPlanning($id_planning, $confirme, $id,$apiKey)
    {
        updateDB("PARTICIPE" ,["confirme"], [$confirme] ,"id_planning=".$id_planning." AND id_user=".$id);

        if ($apiKey != null){
            $historiqueRepo = new HistoriqueRepository();
            $description_hist = " mise a jour commentaire de la participation .";
            $id_secteur = 4;
            $id_user =getIdUserFromApiKey($apiKey);

            $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        }

        return $this->getPlanningByid($id_planning);
    }

    //--------------------------------------------------------------------------------------------------------

    public function deletejoin($user_id, $id_planning,$apiKey)
    {
        $del=deleteDB("PARTICIPE", "id_planning= ". $id_planning ." AND id_user=".$user_id,"bool");

        if ($del !== false)
        {
            if ($apiKey != null){
                $historiqueRepo = new HistoriqueRepository();
                $description_hist = " suppression utilisateur de planning  .";
                $id_secteur = 4;
                $id_user =getIdUserFromApiKey($apiKey);

                $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

            }

            exit_with_message("l'utilisateur a ete supprimer de l'activiter ",200);
        }else{

            if ($apiKey != null){
                $historiqueRepo = new HistoriqueRepository();
                $description_hist = " echec suppression utilisateur  de planning  .";
                $id_secteur = 4;
                $id_user =getIdUserFromApiKey($apiKey);

                $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

            }

        exit_with_message("erreur lors  de la suppression de utilisateur",500);
        }
    }


}
?>
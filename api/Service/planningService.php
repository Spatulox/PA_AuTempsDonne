<?php
include_once './Repository/planningRepository.php'; 
include_once './Models/planningModel.php';

class   PlanningService {
    function getUserRoleFromApiKey($apiKey)
    {
        $sql =selectDB("UTILISATEUR", "id_role", "apikey='".$apiKey."'");
        return $sql[0];
    }


    /*
     *  Récupère tous les planning
    */
    public function getAllPlanning($apiKey) {
        $userRole = $this->getUserRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2) {
            $planningRepository = new PlanningRepository();
            return $planningRepository->getAllPlanning();
        }else{
            exit_with_message("You didn't have access to this command");
        }

    }

    public function getPlanningByIdUser($id, $apiKey) {

        $role = getRoleFromApiKey($apiKey);
        $idUser = getIdUserFromApiKey($apiKey);

        if($role > 2 && $idUser != $id) {
            exit_with_message("You don't have access to this command");
        }

        $planningRepository = new PlanningRepository();
        $planningRepository->getPlanningByIdUser($id);
    }

    /*
     *  Récupère un planning par son id
    */
    public function getPlanningByUser($apiKey) {


        $planningRepository = new PlanningRepository();
        return $planningRepository->getPlanningByUser($apiKey);
    }

    /*
     *  Créer un planning
    */
    public function createPlanning(PlanningModel $planning,$apiKey) {

        $activite = selectDB("ACTIVITES", "id_activite", "id_activite=" . $planning->id_activite,"bool");

        if(!$activite){
            exit_with_message("Please de rentre une activite qui existe ou en cree une", 400);
        }

        $userRole = $this->getUserRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2) {
            $planning->id_index_planning = 2;
            $planningRepository = new PlanningRepository();
            return $planningRepository->createPlanning($planning,$apiKey);

        }elseif($userRole[0]==4){
            $planning->id_index_planning = 3;
            $planningRepository = new PlanningRepository();
            return $planningRepository->createPlanning($planning,$apiKey);
        }else{
            exit_with_message("You didn't have access to this command");
        }
    }

    /*
     *  Met à jour un planning
    */
    public function updatePlanning( $id_planning, $description, $date_activite, $id_index_planning, $id_activite) {

        $activite = selectDB("ACTIVITES", "id_activite", "id_activite=" . $id_activite,"bool");

        if(!$activite){
            exit_with_message("Please de rentre une activite qui existe ou en cree une", 400);
        }
        $planning=selectDB("PLANNINGS","*","id_planning=".$id_planning,"bool");
        if (!$planning){
            exit_with_message("Ce planning n'existe pas",400);
        }

        $planningRepository = new PlanningRepository();
        $updatedPlanning = new PlanningModel(
            $id_planning,
            $description,
            $date_activite,
            $id_index_planning,
            $id_activite
        );
        $updatedPlanning->setId($id_planning);
        return $planningRepository->updatePlanning($updatedPlanning);
    }

    /*
     *  Supprimer un planning
    */
    public function deletePlanning($id,$apiKey) {

        $userRole = $this->getUserRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2 || $userRole[0]==3) {
             $planningRepository = new PlanningRepository();
            return $planningRepository->deletePlanning($id);

        }
        exit_with_message("You don't have access to this command", 403);
    }


    /*
     *  Rejoindre une activité
    */
    public function joinActivity($userId, $planningId, $confirme, $apiKey) {

        $user=selectDB("UTILISATEUR", "*", "id_user=" . $userId,"bool");

        if (!$user[0]["id_user"] ) {
            exit_with_message("Cette utilisateur n'existe pas", 400);
        }
        elseif ($user[0]["id_role"] != 3) {
            exit_with_message("Cette utilisateur n'est pas un benevole",400);
        }
        $planning=selectDB("PLANNINGS","*","id_planning=".$planningId,"bool");
        if (!$planning){
            exit_with_message("Ce planning n'existe pas",400);
        }

        $date =selectDB("PLANNINGS", "DATE(date_activite)" ,"id_planning=".$planningId);
        $join="INNER JOIN PARTICIPE pa on pa.id_planning= pl.id_planning";
        $check=selectJoinDB("PLANNINGS pl" ,"*",$join,"id_user=".$userId. " AND DATE(pl.date_activite) ='".$date[0]["DATE(date_activite)"]."'","bool");

        if(count($check)>=1 && $check !== false) {
            exit_with_message("un utilisateur ne peut pas en faire trop d'activiter en 1 journee",400);
        }




        $userRole = $this->getUserRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2 ) {
            $confirme="non";
            $planningRepository = new PlanningRepository();
            return $planningRepository->joinActivity($userId, $planningId,$confirme,$apiKey);
        }elseif ($userRole[0]==3) {
            $planningRepository = new PlanningRepository();
            return $planningRepository->joinActivity($userId, $planningId,$confirme,$apiKey);
        }else{
            exit_with_message("vous n'avez pas les droits",500);
        }
    }

    //----------------------------------------------------------------------------------------

    public function linkPlanning(array $planning, $apiKey)
    {

        $plannings=selectDB("PLANNINGS","*","id_planning=".$planning[1],"bool");
        if (!$plannings){
            exit_with_message("Ce planning n'existe pas",400);
        }
        $trajet=selectDB("TRAJETS","*","id_trajets=".$planning[0],"bool");
        if (!$trajet){
            exit_with_message("Ce trajet n'existe pas",400);
        }

        $userRole = $this->getUserRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2 || $userRole[0]==3) {

            $planningRepository = new PlanningRepository();
            return $planningRepository->linkPlanning($planning,$apiKey);

        }
        exit_with_message("You don't have access to this command", 403);
    }

    //----------------------------------------------------------------------------------------

    public function getAllPlanningenattente($apiKey) {
        $userRole = $this->getUserRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2) {
            $planningRepository = new PlanningRepository();
            $index =3;
            return $planningRepository->getAllPlanningeindex($index,$apiKey);
        }else{
            exit_with_message("You didn't have access to this command");
        }

    }

    //----------------------------------------------------------------------------------------

    public function getAllPlanningvalidate($apiKey) {
        $userRole = $this->getUserRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2) {
            $planningRepository = new PlanningRepository();
            $index =2;
            return $planningRepository->getAllPlanningeindex($index,$apiKey);
        }else{
            exit_with_message("You didn't have access to this command");
        }

    }

    //----------------------------------------------------------------------------------------

    public function getPlanningBydate($apiKey, $date)
    {
        $userRole = $this->getUserRoleFromApiKey($apiKey);

        if ($userRole[0]>2) {
            exit_with_message("You don't have access to this command");
        }
        $planningRepository = new PlanningRepository();
        return $planningRepository->getPlanningAffecteDate($date,$apiKey);
    }

    //----------------------------------------------------------------------------------------

    public function getPlanningNoAffecte($apiKey)
    {
        $userRole = $this->getUserRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2) {
            $planningRepository = new PlanningRepository();
            return $planningRepository->getPlanningNoAffecte($apiKey);
        }else{
            exit_with_message("You didn't have access to this command");
        }

    }

    //--------------------------------------------------------------------------------------

    public function updateValidatePlanning($id_index_planning, $id, $apiKey)
    {
        $plannings=selectDB("PLANNINGS","*","id_planning=".$id,"bool");
        if (!$plannings){
            exit_with_message("Ce planning n'existe pas",400);
        }

        if($id_index_planning < 1 || $id_index_planning > 3) {
            exit_with_message("Mauvais index, merci de rentrer une valeur entre 1 et 3");
        }

        $userRole = $this->getUserRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2) {
            $planningRepository = new PlanningRepository();
            return $planningRepository->updateValidatePlanning($id_index_planning,$id,$apiKey);
        }else{
            exit_with_message("You didn't have access to this command");
        }
    }

    //--------------------------------------------------------------------------------------

    public function updatejoinPlanning($id_planning,$confirme, $apiKey)
    {
        $plannings=selectDB("PLANNINGS","*","id_planning=".$id_planning,"bool");
        if (!$plannings){
            exit_with_message("Ce planning n'existe pas",400);
        }

        $userRole = $this->getUserRoleFromApiKey($apiKey);
        if ($userRole[0]==3) {
            $id=getIdUserFromApiKey($apiKey);
            $planningRepository = new PlanningRepository();
            return $planningRepository->updatejoinPlanning($id_planning,$confirme,$id,$apiKey);
        }else{
            exit_with_message("You didn't have access to this command");
        }
    }

    //--------------------------------------------------------------------------------------

    public function deletejoin($user_id, $id_planning, $apiKey)
    {
        $user=selectDB("UTILISATEUR","*","id_user=".$user_id,"bool");
        if (!$user){
            exit_with_message("Cette utilisateur n'existe pas",400);
        }elseif ($user[0]["id_index"]==1 || $user[0]["id_index"]==3) {
            exit_with_message("Cette utilisateur est desactiver",400);
        }

        $planning=selectDB("PLANNINGS","*","id_planning=".$id_planning,"bool");
        if (!$planning){
            exit_with_message("Ce planning n'existe pas",400);
        }

        $userRole = $this->getUserRoleFromApiKey($apiKey);
        if ($userRole[0]==1 || $userRole[0]==2 ) {
            $planningRepository = new PlanningRepository();
            return $planningRepository->deletejoin($user_id, $id_planning,$apiKey);
        }else{
            exit_with_message("vous n'avez pas les droits",500);
        }
    }


}
?>
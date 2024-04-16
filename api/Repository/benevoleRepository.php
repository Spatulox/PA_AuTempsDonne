<?php
include_once './Repository/BDD.php';
include_once './Models/userModel.php';
include_once './exceptions.php';

class BenevoleRepository {
    

    public function getPlanning($apikey){
        $id = getIdUserFromApiKey($apikey);

        $activityArray = selectJoinDB("UTILISATEUR u", "a.*", "JOIN PLANNINGS p ON u.id_user = p.id_user
        JOIN ACTIVITES a ON p.id_activite = a.id_activite", "u.id_user='".$id."'", "bool");

        //$formationArray = selectJoinDB("UTILISATEUR u", "f.*", "JOIN SUIVI s ON u.id_user = s.id_user
        //JOIN FORMATIONS f ON s.id_formation = f.id_formation", "s.id_user='".$id."'", "bool");

        if(!$activityArray){
            exit_with_message("No data for planning", 200);
        }

        return $activityArray;
    }

    //-------------------------------------

    public function getFormation($apikey){
        $id = getIdUserFromApiKey($apikey);

        $formationArray = selectJoinDB("UTILISATEUR u", "s.id_user, f.*", "JOIN SUIVI s ON u.id_user = s.id_user
        JOIN FORMATIONS f ON s.id_formation = f.id_formation", "s.id_user='".$id."'", "bool");

        if(!$formationArray){
            exit_with_message("No formations", 200);
        }

        return $formationArray;
    }

    //-------------------------------------

    public function getActivities(){

        $formationArray = selectJoinDB("UTILISATEUR u", "s.id_user, f.*", "JOIN SUIVI s ON u.id_user = s.id_user
        JOIN FORMATIONS f ON s.id_formation = f.id_formation", -1, "bool");
        $formationArray = selectDB("FORMATIONS", "*", -1, "bool");

        //$formationArray = selectJoinDB("UTILISATEUR u", "s.id_user, f.*", "JOIN SUIVI s ON u.id_user = s.id_user
        //JOIN FORMATIONS f ON s.id_formation = f.id_formation", -1, "bool");

        $activityArray = selectDB("ACTIVITES", "*", -1, "bool");

        if(!$formationArray && !$activityArray){
            exit_with_message("No activities or formations", 200);
        }

        return [$formationArray, $activityArray];
    }

}


?>
<?php

class DemandeRepository
{

    private function hjlbf($request){

        $array = [];

        for ($i=0; $i < count($request); $i++) {
            $array[$i] = new DemandeModel($request[$i]["id_user"], $request[$i]["desc_demande"], $request[$i]["id_planning"], $request[$i]["id_user"],);
        }

        return $array;
    }

    function get($apikey = null){

        $request = 0;
        if($apikey == null){
            $request = selectDB("DEMANDE", "*");

            if(!$request){
                exit_with_message("No demande saved", 200);
            }

            exit_with_content($this->hjlbf($request));
        }
        else{
            $idUser = getIdUserFromApiKey($apikey);
            $request = selectDB("DEMANDE", "*", "id_user=".$idUser, "bool");

            if(!$request){
                exit_with_message("No demande saved", 200);
            }

            exit_with_content($this->hjlbf($request));

        }

        exit_with_message("Why are you here ?", 500);
    }

    function getByUser($id_user){
        $request = selectDB("DEMANDE", "*", "id_user=".$id_user);

        exit_with_content($this->hjlbf($request));
    }

    function createDemande($data, $idUser){

       $request = insertDB("DEMANDE", ["desc_demande", "id_user"], [$data["desc_demande"], $idUser]);

        if($request){
            exit_with_message("Sucessfully created demande", 200);
        }

        exit_with_message("Error creating demande", 400);
    }

    function updateDemande($id_demande, $id_planning){
        $request = updateDB("DEMANDE", ["id_planning"], [$id_planning], "id_demande=".$id_demande, bool);

        if($request){
            exit_with_message("Sucessfully updated demande", 200);
        }
        exit_with_message("Error updating demande", 400);
    }
    function deleteDemande($id){
        $resquest = selectDB("DEMANDE", "*", "id_demande=".$id, "bool");

        if($resquest){
            $tmp = deleteDB("DEMANDE", "id_demande=".$id, "bool");
            if($tmp){
                exit_with_message("Sucessfully deleted demande", 200);
            }
        }
        else{
            exit_with_message("The demande doesn't exist", 400);
        }
    }
}
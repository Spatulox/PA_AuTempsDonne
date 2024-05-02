<?php

include_once('./Repository/demandeRepository.php');
class DemandeService
{
    public function __construct()
    {
    }

    function getAll($apikey){

        $role = getRoleFromApiKey($apikey);
        if($role > 2){
            exit_with_message("You can't see all the demande, unless you're an admin");
        }

        $repo = new DemandeRepository();
        $repo->get();
    }

    function getViaApikey($apikey){
        $repo = new DemandeRepository();
        $repo->get($apikey);
    }

    function getViaUser($id, $apikey){
        $role = getRoleFromApiKey($apikey);
        $idUser = getIdUserFromApiKey($apikey);


        if($idUser != $id && $role > 2){
            exit_with_message("You don't have permission to do this");
        }

        $repo = new DemandeRepository();
        $repo->getByUser($id);
    }

    function createDemande($apikey, $data){
        $role = getRoleFromApiKey($apikey);


        if($role != 5){
            exit_with_message("You can't create a demande, unless you're a Partenaire");
        }

        $idUser = getIdUserFromApiKey($apikey);

        $service = new DemandeRepository();

        $service->createDemande($data, $idUser);

    }

    function updateDemande($id_demande, $id_planning, $apikey){
        $role = getRoleFromApiKey($apikey);
        if($role > 2 && $role != 5){
            exit_with_message("You don't have permission to do this");
        }

        if($role == 5){
            $idUser = getIdUserFromApiKey($apikey);
            $request = selectDB("DEMANDE", "id_user", "id_demande=".$id_demande);
            if($request[0]['id_user'] != $idUser){
                exit_with_message("You can't update a demande wich not belong to you");
            }
        }

        $repo = new DemandeRepository();
        $repo->updateDemande($id_demande, $id_planning);
    }

    function deleteDemande($id, $apikey){

        $role = getRoleFromApiKey($apikey);
        if($role != 5 && $role > 2){
            exit_with_message("You don't have permission to delete a demande demande");
        }

        if($role == 5){
            $demande = selectDB("DEMANDE", "id_user", "id_demande=".$id);

            if($demande[0]["id_user"] != getIdUserFromApiKey($apikey)){
                exit_with_message("You cann't delete a demande which not belong to you");
            }
        }

        $service = new DemandeRepository();
        $service->deleteDemande($id);
    }
}
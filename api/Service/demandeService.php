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
        $repo->get($apikey);
    }

    function getViaApikey($apikey){
        $repo = new DemandeRepository();
        $id=getIdUserFromApiKey($apikey);
        $repo->getByUser($id,$apikey);
    }

    function getViaUser($id, $apikey){
        $role = getRoleFromApiKey($apikey);
        $idUser = getIdUserFromApiKey($apikey);


        if($idUser != $id && $role > 2){
            exit_with_message("You don't have permission to do this");
        }

        $repo = new DemandeRepository();
        $repo->getByUser($id,$apikey);
    }

    function createDemande($apikey, $data,$produits){

        $role = getRoleFromApiKey($apikey);

        if($role <3){
            exit_with_message("You can't create a demande, unless you're a Partenaire");
        }

        $idUser = getIdUserFromApiKey($apikey);

        $service = new DemandeRepository();

        $service->createDemande($data, $idUser ,$produits,$apikey);

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
                exit_with_message("You can't update a demande which not belong to you");
            }
        }

        $repo = new DemandeRepository();
        $repo->updateDemande($id_demande, $id_planning,$apikey);
    }

    function deleteDemande($id, $apikey){

        $role = getRoleFromApiKey($apikey);
        if($role != 5 && $role > 2){
            exit_with_message("You don't have permission to delete a demande", 403);
        }

        if($role == 5){
            $demande = selectDB("DEMANDE", "id_user", "id_demande=".$id);

            if($demande[0]["id_user"] != getIdUserFromApiKey($apikey)){
                exit_with_message("You can't delete a demande which not belong to you", 403);
            }
        }

        $service = new DemandeRepository();
        $service->deleteDemande($id,$apikey);
    }

    public function createValidationDemande($apikey, $id)
    {
        $service = new DemandeRepository();
        $service->createValidationDemande($id,$apikey);
    }

    public function createValidationDemandeGroupe($apikey, $id, $id_depart, $id_arriver,$date)
    {
        $service = new DemandeRepository();
        $service->createValidationDemandeGroupe($id,$id_depart,$id_arriver,$date,$apikey);
    }

    public function getAttente( $apikey)
    {
        $repo = new DemandeRepository();
        $repo->getAttente($apikey);
    }
}
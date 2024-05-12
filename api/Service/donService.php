<?php

include_once './Repository/donRepository.php';
include_once './index.php';

class DonService
{

    public function getDonById($id, $apiKey)
    {
        $userRole = getRoleFromApiKey($apiKey);
        if ($userRole==1 || $userRole==2 ) {
            $donRepository = new DonRepository();
            return $donRepository->getDonById($id);
        }else{
            exit_with_message("You didn't have access to this command");
        }
    }
    //-------------------------------------------------------------------------------------------
    public function getDonEntrepot($apiKey)
    {
        $userRole = getRoleFromApiKey($apiKey);
        if ($userRole==1 || $userRole==2 ) {
            $donRepository = new DonRepository();
            return $donRepository->getDonEntrepot();
        }else{
            exit_with_message("You didn't have access to this command");
        }
    }
    //-------------------------------------------------------------------------------------------
    public function getDonAnnuel($date, $apiKey)
    {
        $userRole = getRoleFromApiKey($apiKey);
        if ($userRole==1 || $userRole==2 ) {
            $donRepository = new DonRepository();
            return $donRepository->getDonAnnuel($date);
        }else{
            exit_with_message("You didn't have access to this command");
        }
    }
    //-------------------------------------------------------------------------------------------
    public function getDonMensuel($date, $apiKey)
    {
        $userRole = getRoleFromApiKey($apiKey);
        if ($userRole==1 || $userRole==2 ) {
            $donRepository = new DonRepository();
            return $donRepository->getDonMensuel($date);
        }else{
            exit_with_message("You didn't have access to this command");
        }
    }

//-----------------------------------------------------------------------------------
    public function CreateDonMensuel($prix, $date_don, $apiKey)
    {
        $id = "NULL";
        if($apiKey !== null){
            $id = getIdUserFromApiKey($apiKey);
        }


        $donRepository = new DonRepository();
        $donRepository->CreateDonMensuel($prix, $date_don, $id);

    }
}
?>
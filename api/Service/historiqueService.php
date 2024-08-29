<?php

include_once("./Repository/HistoriqueRepository.php");

class historiqueService
{
    public function __construct()
    {
    }

    function getAllHistory(){

        $HistoriqueRepository = new HistoriqueRepository();
        $HistoriqueRepository->getAllHistorique();
    }

    function CreateHistorique($description_hist ,$id_secteur ,$id_user){

        $HistoriqueRepository = new HistoriqueRepository();
        $HistoriqueRepository->createHistorique($description_hist ,$id_secteur ,$id_user);
    }

}
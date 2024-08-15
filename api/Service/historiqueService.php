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

}
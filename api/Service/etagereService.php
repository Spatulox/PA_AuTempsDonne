<?php

include_once './Repository/etagereRepository.php';
include_once './Models/etagereModel.php';

class EtagereService
{
    function getEtagereStockInEntrepot($Key)
    {
        $etagereRepository = new EtagereRepository();
        $etagereRepository->getEtagereStockInEntrepot($Key);

    }
}
?>
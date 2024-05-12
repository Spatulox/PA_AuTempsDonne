<?php

include_once './Service/etagereService.php';
include_once './exceptions.php';


function etagereController($uri) {

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':

            $etagereService = new EtagereService();
            if ($uri[3]) {

                $etagereService->getEtagereStockInEntrepot($uri[3]);
            }

            break;

        case 'POST':

            break;


        case 'PUT':

            break;

        case 'DELETE':

            break;
    }
}



?>
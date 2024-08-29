<?php

include_once './Repository/recetteRepository.php';
include_once './index.php';

class recetteService {


    public function getAllRecette($apikey)
    {
        $role = getRoleFromApiKey($apikey);


        $repo = new recetteRepository();
        $repo->getAllRecette();
    }

    public function createRecette(array $recette, array $ingredients)
    {
        $repo = new recetteRepository();
        $repo->createRecette($recette, $ingredients);
    }


}
?>
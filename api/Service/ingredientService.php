<?php

include_once './Repository/recetteRepository.php';
include_once './index.php';

class ingredientService {


    public function getAllingredients($apikey)
    {
        $role = getRoleFromApiKey($apikey);


        $repo = new ingredientRepository();
        $repo->getAllingredients();
    }

    public function createingredient($nom_ingredient)
    {
        $repo = new ingredientRepository();
        $repo->createingredient($nom_ingredient);
    }


}
?>
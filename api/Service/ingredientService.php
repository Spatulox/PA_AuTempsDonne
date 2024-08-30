<?php

include_once './Repository/ingredientRepository.php';
include_once './index.php';

class ingredientService {


    public function getAllingredients($apikey)
    {
        $role = getRoleFromApiKey($apikey);


        $repo = new ingredientRepository();
        $repo->getAllingredients();
    }

    public function getIngredientByName($apikey, $name){
        $repo = new ingredientRepository();
        $repo->getIngredientsByname($apikey, $name);
    }

    public function createingredient($nom_ingredient,$unit_mesure)
    {
        $repo = new ingredientRepository();
        $repo->createingredient($nom_ingredient,$unit_mesure);
    }


}
?>
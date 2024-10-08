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

    public function SearchRecette(array $ingredients)
    {

        $repo = new recetteRepository();
        $repo->SearchRecette($ingredients);
    }

    public function getRecetteByid($apikey,  $id)
    {

        $repo = new recetteRepository();
        $repo->getRecetteByid($apikey,$id);

    }


}
?>
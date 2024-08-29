<?php

include_once './Repository/BDD.php';
include_once './exceptions.php';

class ingredientRepository
{
    function __construct()
    {

    }

    //----------------------------------------------------------------------------------------------------------------------------------

    public function getAllingredients()
    {

        $liste = selectDB("INGREDIENT", "*");

        if (!$liste) {
            exit_with_message("Impossible to select data for recette in the DB");
        }

        exit_with_content($liste);

    }

   //-------------------------------------------------------------------------------------
    public function createingredient($nom_ingredient)
    {
        $check=selectDB("INGREDIENT",["nom_ingredient"],"nom_ingredient='".$nom_ingredient."'","bool");

        if ($check) {
            exit_with_message("Ingredient already exists",500);
        }

        insertDB("INGREDIENT", ["nom_ingredient"],  [$nom_ingredient]);

        exit_with_message("Insertion successful");
    }


}
?>
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

        $liste = selectDB("INGREDIENT", "*",-1);

        $listeArray =[];

        if (!$liste) {
            exit_with_message("Impossible to select data for recette in the DB");
        }

        
        for ($i=0; $i < count($liste); $i++) {
            $ingredientModel = new IngredientModels(
                $liste[$i]['id_ingredient'],
                $liste[$i]['nom_ingredient'],
                $liste[$i]['unit_mesure']
            );

            $listeArray[$i]=$ingredientModel;
        }

        exit_with_content($listeArray);

    }

   //-------------------------------------------------------------------------------------
    public function createingredient($nom_ingredient,$unit_mesure)
    {
        $check=selectDB("INGREDIENT","*","nom_ingredient='".$nom_ingredient."'","bool");

        if ($check) {
            exit_with_message("Ingredient already exists",500);
        }

        insertDB("INGREDIENT", ["nom_ingredient","unit_mesure"],  [$nom_ingredient,$unit_mesure]);

        exit_with_message("Insertion successful");
    }


}
?>
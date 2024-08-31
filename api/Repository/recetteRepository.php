<?php

include_once './Repository/BDD.php';
include_once './exceptions.php';

class recetteRepository
{
    function __construct()
    {

    }

    //----------------------------------------------------------------------------------------------------------------------------------

    private function stock($request)
    {

        $array = [];

        $recettes = [];

        foreach ($request as $item) {
            $id_recette = $item["id_recette"];

            if (!isset($recettes[$id_recette])) {
                $recettes[$id_recette] = [
                    "id_recette" => $id_recette,
                    "nom_recette" => $item["nom_recette"],
                    "description_recette" => $item["description_recette"],
                    "liste" => []
                ];
            }

            $nom_ingre = selectDB("INGREDIENT", "nom_ingredient", "id_ingredient='".$item["id_ingredient"]."'")[0]["nom_ingredient"];

            $liste = [
                "id_ingredient" => $item["id_ingredient"],
                "nom_ingredient" => $nom_ingre,
                "quantite_recette" => $item["quantite_recette"],
                "unit_mesure_ingredient" => $item["unit_mesure_ingredient"]
            ];

            $recettes[$id_recette]["liste"][] = $liste;
        }

        foreach ($recettes as $recette) {
            $recetteModel = new recetteModel(
                $recette["id_recette"],
                $recette["nom_recette"],
                $recette["description_recette"],
                $recette["liste"]
            );
            $array[] = $recetteModel;
        }
        return $array;
    }

    //----------------------------------------------------------------------------------------------------------------------

    public function getAllRecette()
    {

        $recette = selectDB("RECETTE", "*");
        $string = "INNER JOIN DANS DA ON DA.id_recette = RECETTE.id_recette;";
        $request = selectJoinDB("RECETTE", "*", $string,);

        if (!$recette) {
            exit_with_message("Impossible to select data for recette in the DB");
        }

        exit_with_content($this->stock($request));

    }

    //---------------------------------------------------------------------------------------------------------------------

    public function createRecette(array $recette, array $ingredients)
    {
        {
            $check=selectDB("RECETTE", "nom_recette" ,"nom_recette='".$recette["nom_recette"]."'","bool");

            if ($check) {
                exit_with_message("Recette already exists",500);
            }

            $request = insertDB("RECETTE", ["nom_recette", "description_recette"], [$recette["nom_recette"], $recette["description_recette"]]);

            if (!$request) {
                exit_with_message("Error creating recette", 500);
            }
            $id_recette = $this->getLastInsertId("RECETTE", "id_recette");

            foreach ($ingredients as $ingredient) {

                $request_recette = insertDB(
                    "DANS",
                    ["id_ingredient", "id_recette", "quantite_recette", "unit_mesure_ingredient"],
                    [
                        $ingredient["id_ingredient"],
                        $id_recette[0]["id_recette"],
                        $ingredient["quantite_ingredient"],
                        $ingredient["unit_mesure_ingredient"]
                    ]
                );
                if (!$request_recette) {
                    exit_with_message("Error creating liste", 500);
                }
            }

            exit_with_message("Sucessfully created recette", 200);
        }
    }
    //--------------------------------------------------------------------------------------------------------------------------

    private function getLastInsertId($table, $id)
    {
        $string = "ORDER BY " . $id . " DESC LIMIT 1";
        $envoie = selectDB($table, $id, -1, $string);
        return $envoie;
    }

    //----------------------------------------------------------------------------------------------------------------------------------

    public function SearchRecette(array $ingredients)
    {

        $string = " (id_ingredient= " . $ingredients[0]["id_ingredient"] . " and quantite_recette<= " . $ingredients[0]["quantite_ingredient"].") ";
        for ($i = 1; $i <count($ingredients) ; $i++) {
            $string = $string." or  (id_ingredient= " . $ingredients[$i]["id_ingredient"] . " and quantite_recette <= " . $ingredients[$i]["quantite_ingredient"].") ";
        }

        $sql= selectJoinDB(" RECETTE r", "DISTINCT *", 'JOIN DANS d ON r.id_recette = d.id_recette' ,$string);

        $recipeCounts = [];
        $recipeNames = [];


        foreach ($sql as $item) {
            $recipeId = $item['id_recette'];
            $recipeCounts[$recipeId] = ($recipeCounts[$recipeId] ?? 0) + 1;
            $recipeNames[$recipeId] = $item['nom_recette'];
        }


        $recipeArray = [];
        foreach ($recipeCounts as $recipeId => $count) {
            $recipeArray[] = [
                "id_recette" => $recipeId,
                "nom_recette" => $recipeNames[$recipeId],
                "nb_ingredients_matches" => $count
            ];
        }

        $string="r.id_recette IN (";

        for ($j = 0; $j < count($recipeArray)-1; $j++) {
            $string= $string . $recipeArray[$j][ "id_recette"]. ", " ;
        }
        ;
        $string= $string . $recipeArray[$j][ "id_recette"]. ")" ;

        $sql= selectJoinDB(" RECETTE r", "DISTINCT *", 'JOIN DANS d ON r.id_recette = d.id_recette' ,$string);

        $recipeCounts = [];
        $recipeNames = [];


        foreach ($sql as $item) {
            $recipeId = $item['id_recette'];
            $recipeCounts[$recipeId] = ($recipeCounts[$recipeId] ?? 0) + 1;
            $recipeNames[$recipeId] = $item['nom_recette'];
        }


        $recipeArray2 = [];
        foreach ($recipeCounts as $recipeId => $count) {
            $recipeArray2[] = [
                "id_recette" => $recipeId,
                "nom_recette" => $recipeNames[$recipeId],
                "nb_ingredients_matches" => $count
            ];
        }


        $recipeArray2Assoc = array_column($recipeArray2, null, 'id_recette');


        $filteredRecipes = array_filter($recipeArray, function($recipe) use ($recipeArray2Assoc) {
            $totalIngredients = $recipeArray2Assoc[$recipe['id_recette']]['nb_ingredients_matches'];
            $matchedIngredients = $recipe['nb_ingredients_matches'];

            $thingToReturn = ($matchedIngredients / $totalIngredients) >= 0.5; // 50% ou plus
            if(!$thingToReturn) {
                $thingToReturn = ($matchedIngredients / $totalIngredients) >= 0.2; // 20% ou plus
            }
            return $thingToReturn;
        });


        usort($filteredRecipes, function($a, $b) use ($recipeArray2Assoc) {
            $percentA = $a['nb_ingredients_matches'] / $recipeArray2Assoc[$a['id_recette']]['nb_ingredients_matches'];
            $percentB = $b['nb_ingredients_matches'] / $recipeArray2Assoc[$b['id_recette']]['nb_ingredients_matches'];
            return $percentB <=> $percentA;
        });

        $finalRecipes = array_map(function($recipe) use ($recipeArray2Assoc) {
            $totalIngredients = $recipeArray2Assoc[$recipe['id_recette']]['nb_ingredients_matches'];
            $matchedIngredients = $recipe['nb_ingredients_matches'];
            $percentage = ($matchedIngredients / $totalIngredients) * 100;
            $recipe['pourcentage_ingredients'] = round($percentage, 2);
            return $recipe;
        }, $filteredRecipes);

        exit_with_content($finalRecipes);
    }
//-------------------------------------------------------------------------------------------------------------------
    public function getRecetteByid($apikey, $id)
    {

        $sql= selectJoinDB(" RECETTE r", "*", 'JOIN DANS d ON r.id_recette = d.id_recette' ,"r.id_recette =". $id);

        if (!$sql) {
            exit_with_message("Impossible to select data for recette in the DB");
        }

        exit_with_content($this->stock($sql));
    }

}
?>
<?php
function fillRecipeDb($uri){
    // Endpoint to list meals with the first letter
    $api_url_meal = 'https://www.themealdb.com/api/json/v1/1/search.php';
    $api_url_ingredient = "https://www.themealdb.com/api/json/v1/1/list.php?i=list";
    $api_url_categorie = "https://www.themealdb.com/api/json/v1/1/list.php?c=list";
    $api_url_country = "https://www.themealdb.com/api/json/v1/1/list.php?a=list";

    $letters = [
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m",
        "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z"
    ];

    if($uri[3] == "ingredient"){
        $response = file_get_contents($api_url_ingredient);
        if ($response === false) {
            die('Erreur lors de la requête API');
        }
        $data = json_decode($response, true);

        $recette = $data['meals'];
        foreach ($recette as $meal) {
            insertDB("INGREDIENT", ["nom_ingredient"], [$meal["strIngredient"]]);
        }
        exit_with_message("Ingredient filled", 200);
    }


    if($uri[3] == "recette") {

        // Retrieve all
        // Boucle on the letters tab
        for ($i = 0; $i < 26; $i++) {
            $api_url_meal2 = $api_url_meal . "?f=".$letters[$i];

            $response = file_get_contents($api_url_meal2);
            if ($response === false) {
                die('Erreur lors de la requête API');
            }

            $data = json_decode($response, true);
            if (isset($data['meals'])) {
                $recette = $data['meals'];
                //deleteDB("DANS", "id_recette > 0");
                //deleteDB("RECETTE", "id_recette > 0");
                $keys = array_keys($recette);
                //for ($i = 0; $i < count($recette); $i++) {
                for ($i = 0; $i < 20; $i++) {
                    $meal = $recette[$keys[$i]];
                    for ($i = 1; $i <= 20; $i++) {

                        $ingredient = $meal["strIngredient$i"];
                        $measure = $meal["strMeasure$i"];

                        $meal["strInstructions"] = str_replace("'", "\"", $meal["strInstructions"]);

                        $id_recette = insertDB("RECETTE", ["nom_recette", "description_recette"], [$meal["strMeal"], $meal["strInstructions"]], 'MAX(id_recette)');//
                        if(!$id_recette){
                            exit_with_content("Récupération failed for id_recette");
                        }

                        $id_recette = $id_recette[0][0];
                        $ingredient = explode(", ", $ingredient);

                        if(count($ingredient) > 1){
                            $ingredient = implode("' OR nom_ingredient LIKE '", $ingredient);
                        } else {
                            $ingredient = $ingredient[0];
                        }

                        $id_ingredient = selectDB("INGREDIENT", "id_ingredient", "nom_ingredient LIKE '".$ingredient."'", 'bool');
                        // If don't find any in the db
                        if(!$id_ingredient){

                            $ingredient = $meal["strIngredient$i"];
                            if (!empty($ingredient) && $ingredient !== null && !empty($measure) && $measure !== null) {

                                $ingredient = str_replace(",", " ", $ingredient);
                                $ingredient = str_replace("-", " ", $ingredient);
                                $ingredient = explode(" ", $ingredient);

                                if(count($ingredient) > 1){
                                    $ingredient = implode("') OR SOUNDEX(nom_ingredient) LIKE SOUNDEX('", $ingredient);
                                } else {
                                    $ingredient = $ingredient[0];
                                }
                                $id_ingredient = selectDB("INGREDIENT", "id_ingredient", "SOUNDEX(nom_ingredient) LIKE SOUNDEX('".$ingredient."%')", 'bool');
                                if($id_ingredient){
                                    $id_ingredient = $id_ingredient[0]["id_ingredient"];
                                } else {
                                    selectDB("INGREDIENT", "id_ingredient", "SOUNDEX(nom_ingredient) LIKE SOUNDEX('".$ingredient."%')", '-@');
                                    exit_with_message("Error when getting ingredient for the recette : nom_ingredient LIKE '".$ingredient."'", 200);
                                }
                            }
                        }
                        // Vérifiez si l'ingrédient et la mesure ne sont pas vides ou null
                        if (!empty($ingredient) && $ingredient !== null && !empty($measure) && $measure !== null) {
                            $id_ingredient = $id_ingredient[0]["id_ingredient"];

                            var_dump($measure);
                            $result = parseQuantity($measure);

                            var_dump($result["quantity"]);
                            var_dump($result["unit"]);

                            $res = insertDB("DANS", ["id_ingredient", "id_recette", "quantite_recette", "unit_mesure_ingredient"], [$id_ingredient, $id_recette, $result["quantity"], $result["unit"]]);
                            if(!$res){
                                var_dump("Impossible d'enregistrer ".$id_recette." dans la BDD");
                            }
                        }

                    }
                }
            }
        }
        exit_with_message("Recette filled", 200);
    }

    if(!$uri[3]) {
        for ($i = 0; $i < 26; $i++) {
            $api_url_meal2 = $api_url_meal . "?f=".$letters[$i];

            $response = file_get_contents($api_url_meal2);
            if ($response === false) {
                die('Erreur lors de la requête API');
            }

            $data = json_decode($response, true);
            if (isset($data['meals'])) {
                exit_with_content($data["meals"]);
            } else {
                echo "Aucun résultat trouvé for ".$letters[$i];
            }
        }
    }

}



function parseQuantity($string) {
    // Supprimer tout ce qui vient après '/' (y compris '/')
    $string = preg_replace('/\/.*/', '', $string);

    // Tableau de conversion des fractions courantes
    $fractions = [
        '¼' => 0.25,
        '½' => 0.5,
        '¾' => 0.75,
        '⅓' => 0.333,
        '⅔' => 0.667,
        '⅛' => 0.125,
        '⅜' => 0.375,
        '⅝' => 0.625,
        '⅞' => 0.875
    ];

    $appelations = [
        "teaspoon" => "Cuilliere à café",
        "tblsp" => "Cuilliere à soupe",
        "sliced" => "tranché",
        "slice" => "tranche(s)"
    ];

    // Remplacer les fractions par leur équivalent décimal
    foreach ($fractions as $fraction => $decimal) {
        $string = str_replace($fraction, $decimal, $string);
    }

    foreach ($appelations as $appelation => $little) {
        $string = str_replace($appelation, $little, $string);
    }

    // Séparer le nombre de l'unité
    if (preg_match('/^(\d*\.?\d+)\s*(.*)$/', $string, $matches)) {
        $quantity = $matches[1];
        $unit = trim($matches[2]);
        if (!$unit) {
            $unit = "unit";
        }

        return [
            'quantity' => $quantity,
            'unit' => $unit
        ];
    }

    // Si aucun nombre n'est trouvé, considérer toute la chaîne comme unité
    return [
        'quantity' => 1,
        'unit' => trim($string)
    ];
}


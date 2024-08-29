<?php
function fillRecipeDb(){
    // Endpoint to list meals with the first letter
    $api_url_meal = 'https://www.themealdb.com/api/json/v1/1/search.php';
    $api_url_ingredient = "https://www.themealdb.com/api/json/v1/1/list.php?i=list";
    $api_url_categorie = "https://www.themealdb.com/api/json/v1/1/list.php?c=list";
    $api_url_country = "https://www.themealdb.com/api/json/v1/1/list.php?a=list";

    $letters = [
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m",
        "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z"
    ];

    // Retrieve all
    for ($i = 0; $i < 26; $i++) {
        $api_url_meal .= "?f=".$letters[$i];
        
        $response = file_get_contents($api_url_meal);
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

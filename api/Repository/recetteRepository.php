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


            $liste = [
                "id_ingredient" => $item["id_ingredient"],
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

        $entrepot = selectDB("RECETTE", "*");
        $string = "INNER JOIN DANS DA ON DA.id_recette = RECETTE.id_recette;";
        $request = selectJoinDB("RECETTE", "*", $string,);

        if (!$entrepot) {
            exit_with_message("Impossible to select data for recette in the DB");
        }

        exit_with_content($this->stock($request));

    }

    //---------------------------------------------------------------------------------------------------------------------

    public function createRecette(array $recette, mixed $ingredients)
    {
        {
            $check=selectDB("RECETTE", "nom_recette" ,"nom_recette='".$recette["nom_recette"]."'","bool");

            if ($check) {
                exit_with_message("Recette already exists",500);
            }

            $request = insertDB("RECETTE", ["nom_recette", "description_recette"], [$recette["nom_entrepot"], $recette["description_recette"]]);

            if (!$request) {
                exit_with_message("Error creating recette", 500);
            }
            $id_recette = $this->getLastInsertId("RECETTE", "id_recette");

            foreach ($ingredients as $ingredient) {

                $request_recette = insertDB("DANS", ["id_ingredient","id_recette", "quantite_recette", "unit_mesure_ingredient"], [$ingredient["id_ingredient"],$id_recette,$ingredient["quantite_recette"], $id_recette["unit_mesure_ingredient"]]);

                if (!$request_recette) {
                    exit_with_message("Error creating liste", 500);
                }

                exit_with_message("Sucessfully created recette", 200);
            }
        }
    }
    //---------------------------------------------------------------

    private function getLastInsertId($table, $id)
    {
        $string = "ORDER BY " . $id . " DESC LIMIT 1";
        $envoie = selectDB($table, $id, -1, $string);
        return $envoie;
    }

}
?>
<?php

include_once './Repository/BDD.php';
include_once './exceptions.php';

class recetteRepository
{
    function __construct()
    {

    }

    public function getAllRecette()
    {



    }

    public function createRecette(array $recette, mixed $ingredients)
    {
        {
            $check=selectDB("RECETTE", "nom_recette" ,"nom_recette='".$recette["nom_recette"]."'","bool");

            if ($check) {
                exit_with_message("Recette already exists",500);
            }

            $request = insertDB("RECETTE", ["nom_recette", "parking"], [$recette["nom_entrepot"], $recette["parking"]]);

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
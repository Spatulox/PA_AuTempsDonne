<?php

class ProduitRepository
{

    public function __construct()
    {
    }

    function getAllProduit()
    {
        $request = selectDB("PRODUIT", "*", "-1", "bool");
        if(!$request){
            exit_with_message("Nothing to show", 200);
        }

        $array = [];

        for ($i = 0; $i<count($request);$i++){

            $type = selectDB("TYPE", "*", "id_type=".$request[$i]["id_type"])[0]["type"];
            $model = new ProduitModel($request[$i]["id_produit"], $request[$i]["nom_produit"], $request[$i]["id_type"], $type);
            $array[$i] = $model;
        }

        exit_with_content($array);
    }

    function getProduitId($id)
    {
        $request = selectDB("PRODUIT", "*", "id_produit=".$id, "bool")[0];

        if(!$request){
            exit_with_message("Nothing the show", 200);
        }

        $type = selectDB("TYPE", "*", "id_type=".$request["id_type"])[0]["type"];


        $model = new ProduitModel($request["id_produit"], $request["nom_produit"], $request["id_type"], $type);

        exit_with_content($model);
    }

    function createProduit($nom_produit, $id_type){
        $request = selectDB("PRODUIT", "*", "nom_produit='".$nom_produit."'", "bool");

        if($request){
            exit_with_message("Produit already exists", 200);
        }

        if(insertDB("PRODUIT", ["nom_produit", "id_type"], [$nom_produit, $id_type], "bool")){
            exit_with_message("Produit has been created", 200);
        }

        exit_with_message("Produit not created", 200);

    }

    function deleteProduitId($id){
        $request = deleteDB("PRODUIT", "id_produit=".$id, "bool");

        if($request){
            exit_with_message("Produit has been deleted", 200);
        }

        exit_with_message("Produit not deleted", 200);

    }

    public function getType()
    {
        $res = selectDB("TYPE", "*");
        $tab = [];
        foreach ($res as $row) {
            $tab[] = [
                "id_type" => $row["id_type"],
                "type" => $row["type"],
                "unit_mesure" => $row["unit_mesure"]
            ];
        }
        exit_with_content($tab);
    }
}
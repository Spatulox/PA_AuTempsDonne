<?php
include_once('./Repository/HistoriqueRepository.php');

class ProduitRepository
{

    public function __construct()
    {
    }

    function getAllProduit($apikey)
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

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "List Produit";
        $id_secteur = 2;
        $id_user =getIdUserFromApiKey($apikey);


        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        exit_with_content($array);
    }

    function getProduitId($id,$apikey)
    {
        $request = selectDB("PRODUIT", "*", "id_produit=".$id, "bool")[0];

        if(!$request){
            exit_with_message("Nothing to show", 200);
        }

        $type = selectDB("TYPE", "*", "id_type=".$request["id_type"])[0]["type"];


        $model = new ProduitModel($request["id_produit"], $request["nom_produit"], $request["id_type"], $type);


        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "detaille produit " .$request["id_produit"] ." .";
        $id_secteur = 2;
        $id_user =getIdUserFromApiKey($apikey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        exit_with_content($model);
    }

    function createProduit($nom_produit, $id_type,$apikey){
        $request = selectDB("PRODUIT", "*", "nom_produit='".$nom_produit."'", "bool");

        if($request){
            exit_with_message("Product already exists", 500);
        }

         $res= insertDB("PRODUIT", ["nom_produit", "id_type"], [$nom_produit, $id_type]);



        if($res==true) {

            $historiqueRepo = new HistoriqueRepository();
            $description_hist = "Produit has been created " .$request["id_produit"] ." .";
            $id_secteur = 2;
            $id_user =getIdUserFromApiKey($apikey);

            $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

            exit_with_message("Produit has been created", 200);
        }else{

            $historiqueRepo = new HistoriqueRepository();
            $description_hist = "Produit not created .";
            $id_secteur = 2;
            $id_user =getIdUserFromApiKey($apikey);

            $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

            exit_with_message("Produit not created", 500);

        }


    }

    function deleteProduitId($id,$apikey){
        $request = deleteDB("PRODUIT", "id_produit=".$id, "bool");

        if($request){

            $historiqueRepo = new HistoriqueRepository();
            $description_hist = "Produit has been deleted.";
            $id_secteur = 2;
            $id_user =getIdUserFromApiKey($apikey);

            $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

            exit_with_message("Produit has been deleted", 200);
        }

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 2;
        $id_user =getIdUserFromApiKey($apikey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

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
<?php
include_once './Repository/BDD.php';
include_once './exceptions.php';
include_once './Models/entrepotModel.php';

class EntrepotRepository
{
    private $connection = null;

    //--------------------------------------------------------------------------------------
    private function stock($request)
    {

        $array = [];

        $entrepots = [];

        foreach ($request as $item) {
            $id_entrepot = $item["id_entrepot"];

            if (!isset($entrepots[$id_entrepot])) {
                $entrepots[$id_entrepot] = [
                    "id_entrepot" => $id_entrepot,
                    "nom_entrepot" => $item["nom_entrepot"],
                    "parking" => $item["parking"],
                    "id_adresse" => $item["id_adresse"],
                    "etagere" => []
                ];
            }


            $etagere = [
                "id_etagere" => $item["id_etagere"],
                "nombre_de_place" => $item["nombre_de_place"],
            ];

            $entrepots[$id_entrepot]["etagere"][] = $etagere;
        }

        foreach ($entrepots as $entrepot) {
            $entrepotModel = new EntrepotModel(
                $entrepot["id_entrepot"],
                $entrepot["nom_entrepot"],
                $entrepot["parking"],
                $entrepot["id_adresse"],
                $entrepot["etagere"]
            );
            $entrepotModel->setAddresse(selectDB("ADRESSE", "adresse", "id_adresse=" . $entrepot['id_adresse'])[0]);
            $array[] = $entrepotModel;
        }
        return $array;
    }

    //------------------------------------------------------------------------------------------------------------------------------------------

    // I'm not sure about this function lol (unuse)
    function __construct()
    {

    }

    //------------------------------------------------------------------------------------------------------------------------------------------

    public function getEntrepots()
    {
        $entrepot = selectDB("ENTREPOTS", "*");
        $string = "INNER JOIN ETAGERES E ON E.id_entrepot = ENTREPOTS.id_entrepot;";
        $request = selectJoinDB("ENTREPOTS", "*", $string,);

        if (!$entrepot) {
            exit_with_message("Impossible to select data for entrepot in the DB");
        }

        exit_with_content($this->stock($request));
    }

    //------------------------------------------------------------------------------------------------------------------------------------------

    public function getEntrepot($id = null)
    {

        if ($id == null) {
            exit_with_content("Plz specifie a id for the entepot");
        }

        $entrepot = selectDB("ENTREPOTS", "*", "id_entrepot = " . $id);
        $string = "INNER JOIN ETAGERES E ON E.id_entrepot = ENTREPOTS.id_entrepot";
        $request = selectJoinDB("ENTREPOTS", "*", $string, "ENTREPOTS.id_entrepot = " . $id);

        if (!$entrepot) {
            exit_with_message("Impossible to select data for entrepot in the DB with the id : " . $id);
        }

        exit_with_content($this->stock($request));
    }


    //------------------------------------------------------------------------------------------------------------------------------------------

    public function createEntrepot($entrepot, $etageres)
    {
        $check=selectDB("ENTREPOTS", "nom_entrepot" ,"nom_entrepot='".$entrepot["nom_entrepot"]."'","bool");
        if ($check) {
            exit_with_message("Entrepot already exists",500);
        }

        $request = insertDB("ENTREPOTS", ["nom_entrepot", "parking", "id_adresse"], [$entrepot["nom_entrepot"], $entrepot["parking"], $entrepot["id_adresse"]]);

        if (!$request) {
            exit_with_message("Error creating entrepot", 500);
        }
        $id_entrepot = $this->getLastInsertId("ENTREPOTS", "id_entrepot");

        foreach ($etageres as $etagere) {

            $last_id=$this->getLastInsertId("ETAGERES","id_etagere");

            $code = hash('sha256', $last_id[0]['id_etagere'] ."_". $etagere['nombre_de_place'] ."_". $id_entrepot[0]['id_entrepot']);

            $request_collecte = insertDB("ETAGERES", ["nombre_de_place","code", "id_entrepot"], [$etagere["nombre_de_place"],$code, $id_entrepot[0]["id_entrepot"]]);

            if (!$request_collecte) {
                exit_with_message("Error creating etagere", 500);
            }

            exit_with_message("Successfully created entrepot", 200);
        }
    }

    //--------------------------------------------------------------------------------------------------------------------------

    public function updateEntrepot(EntrepotModel $entr)
    {

        if ($entr->id_entrepot == null) {
            exit_with_message("Impossible to update entrepot in the DB, need to specify the entrepot you want to update");
        }

        $columnArray = [];
        $valuesArray = [];


        if ($entr->nom != null) {
            array_push($columnArray, "nom_entrepot");
            array_push($valuesArray, $entr->nom);
        }

        if ($entr->localisation != null) {
            array_push($columnArray, "localisation");
            array_push($valuesArray, $entr->localisation);
        }


        if (updateDB("ENTREPOTS", $columnArray, $valuesArray, "id_entrepot=" . $entr->id_entrepot, "bool")) {
            exit_with_message("Entrepot updated with success", 200);
        } else {
            exit_with_message("An error occurred while updating the entrepot " . $entr->nom . " (" . $entr->id_entrepot . ") in the DB");
        }

    }


    //-------------------------------------------------------------------------------------------------------------------------

    public function unreferenceEntrepotById($id)
    {


        $resquest = selectDB("ETAGERES", "*", "id_entrepot=" . $id, "bool");

        if ($resquest) {
            for ($i = 0; $i < count($resquest); $i++) {

                $check=selectDB("STOCKS", "*", "id_etagere=" . $resquest[$i]["id_etagere"]. " AND quantite_produit >0 AND date_sortie IS NULL","bool");
                if ($check){

                    exit_with_message("Etagere is already in use",500);

                }
            }

            $tmp = deleteDB("ETAGERES", "id_entrepot=" . $id);
            if (!$tmp) {
                exit_with_message("The demande doesn't exist", 500);
            }
        }
        $tmp = deleteDB("ENTREPOTS", "id_entrepot=" . $id);
        if (!$tmp) {
            exit_with_message("Entrepot delete no successful", 500);
        }
        exit_with_message("Entrepot deleted successfully", 200);

    }

    //-------------------------------------------------------------------------------------

    public function createEtageres($entrepot, $etageres_place)
    {
        for ($i = 0; $i < count($etageres_place); $i++) {
            $last_id=$this->getLastInsertId("ETAGERES","id_etagere");

            $code = hash('sha256', $last_id[0]['id_etagere'] ."_". $etageres_place[$i] ."_". $entrepot);


            $request_collecte = insertDB("ETAGERES", ["nombre_de_place","code", "id_entrepot"], [$etageres_place[$i],$code, $entrepot]);
            if ($request_collecte==false) {
                exit_with_message("Error creating Etagere", 500);
            }
        }
        exit_with_message("Etagere added with success", 200);
    }

    //-------------------------------------------------------------------------------------

    private function getLastInsertId($table, $id)
    {
        $string = "ORDER BY " . $id . " DESC LIMIT 1";
        $envoie = selectDB($table, $id, -1, $string);
        return $envoie;
    }

    //-------------------------------------------------------------------------------------

    public function DeleteEtagere($id)
    {
        $resquest = selectDB("ETAGERES", "*", "id_etagere=" . $id, "bool");

        if ($resquest) {
            $check=selectDB("STOCKS", "*", "id_etagere=" . $id. " AND quantite_produit >0 AND date_sortie IS NULL","bool");
            if ($check){

                exit_with_message("etagere is already in use",500);

            }
            $nb=selectDB("ETAGERES", "*", "id_entrepot=" . $resquest[0]["id_entrepot"], "bool");

            if (count($nb) ==1){
                exit_with_message("Impossible de supprimer la dernière etagere",500);
            }

            deleteDB("ETAGERES", "id_etagere=" . $id);
            exit_with_message("Etagere deleted with success ", 200);
        }else{
            exit_with_message("This Etagere doesn't exist", 500);
        }

    }

    //-----------------------------------------------------------------------------------------------

    public function getEntrepotPlaceById($id)
    {
        $check=selectDB("ENTREPOTS", "*", "id_entrepot=" . $id, "bool");
        if (!$check){
            exit_with_message("Entrepot not found", 500);
        }
        $string = "id_entrepot=" .$id;
        $etagere= selectDB("ETAGERES", "id_etagere,nombre_de_place",$string);
        if(!$etagere){
            exit_with_message("Cette etagere n'existe pas ", 500);
        }
        $sum=0;
        $place=0;
        for ($i = 0; $i < count($etagere); $i++) {
            $nbProduitsEtagere = $this->getnbplace($etagere[$i]['id_etagere']);
            $sum=$sum+$nbProduitsEtagere;
            $place=$place+$etagere[$i]['nombre_de_place'];
        }
        $sum=$place-$sum;
        exit_with_message("il reste ".$sum. " place dans ".$check[0]["nom_entrepot"], 200);

    }

    private function getnbplace($id)
    {
        $string = "id_etagere=".$id . " AND ". "date_sortie IS NULL";
        $number= selectDB("STOCKS","quantite_produit",$string,"bool");
        for ($i = 0; $i < count($number); $i++) {
            $sum=$sum+$number[$i]["quantite_produit"];
        }

        return $sum;
    }

    public function getEtagereQR($id)
    {
        $code=selectDB("ETAGERES", "*", "id_etagere=" . $id, "bool");
        $tmpModel = ["key" => $code[0]["code"]];
        exit_with_content($tmpModel, 200);
    }


}

?>
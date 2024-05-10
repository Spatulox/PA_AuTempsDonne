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



        $request = insertDB("ENTREPOTS", ["nom_entrepot", "parking", "id_adresse"], [$entrepot["nom_entrepot"], $entrepot["parking"], $entrepot["id_adresse"]]);

        if (!$request) {
            exit_with_message("Error creating entrepot", 500);
        }
        $id_entrepot = $this->getLastInsertId("ENTREPOTS", "id_entrepot");

        foreach ($etageres as $etagere) {
            $request_collecte = insertDB("ETAGERES", ["nombre_de_place", "id_entrepot"], [$etagere["nombre_de_place"], $id_entrepot[0]["id_entrepot"]]);

            if (!$request_collecte) {
                exit_with_message("Error creating etagere", 500);
            }

            exit_with_message("Sucessfully created entrepot", 200);
        }
    }

    //--------------------------------------------------------------------------------------------------------------------------

    public function updateEntrepot(EntrepotModel $entr)
    {

        if ($entr->id_entrepot == null) {
            exit_with_message("Impossible to update entrepot in the DB, need to specifie the entrepot you want to update");
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

                $check=selectDB("STOCKS", "*", "id_etagere=" . $id. " AND quantite_produit >0 AND date_sortie IS NULL","bool");
                if ($check){

                    exit_with_message("etagere is already in use",500);

                }
            }

            $tmp = deleteDB("ETAGERES", "id_entrepot=" . $id);
            if (!$tmp) {
                exit_with_message("The demande doesn't exist", 500);
            }
        }
        $tmp = deleteDB("ENTREPOTS", "id_entrepot=" . $id);
        if (!$tmp) {
            exit_with_message("Entrepot unreference no successful", 500);
        }
        exit_with_message("Entrepot unreference successful", 200);

    }

    //-------------------------------------------------------------------------------------

    public function createEtageres($entrepot, $etageres_place)
    {
        for ($i = 0; $i < count($etageres_place); $i++) {
            $request_collecte = insertDB("ETAGERES", ["nombre_de_place", "id_entrepot"], [$etageres_place[$i], $entrepot] );

            if (!$request_collecte) {
                exit_with_message("Error creating collecte", 500);
            }
        }
        exit_with_message("Etagere add with success", 200);
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
            deleteDB("ETAGERES", "id_etagere=" . $id);
            exit_with_message("Etagere deleted with success ", 200);
        }else{
            exit_with_message("The Etagere doesn't exist", 500);
        }

    }
}

?>
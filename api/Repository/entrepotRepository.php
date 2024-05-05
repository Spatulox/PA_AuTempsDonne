<?php
include_once './Repository/BDD.php';
include_once './exceptions.php';
include_once './Models/entrepotModel.php';

class EntrepotRepository {
    private $connection = null;

    //--------------------------------------------------------------------------------------
    private function stock($request){

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

    //-----------------------------------------------------------------------------------

    // I'm not sure about this function lol (unuse)
    function __construct() {
       
    }
    
    //-------------------------------------

    public function getEntrepots(){
        $entrepot = selectDB("ENTREPOTS", "*");
        $string = "INNER JOIN ETAGERES E ON E.id_entrepot = ENTREPOTS.id_entrepot;";
        $request = selectJoinDB("ENTREPOTS", "*",$string,);

        if(!$entrepot){
            exit_with_message("Impossible to select data for entrepot in the DB");
        }

        exit_with_content($this->stock($request));
    }

    //-------------------------------------

    public function getEntrepot($id = null){

        if($id == null){
            exit_with_content("Plz specifie a id for the entepot");
        }

        $entrepot = selectDB("ENTREPOTS", "*", "id_entrepot = ".$id);
        $string = "INNER JOIN ETAGERES E ON E.id_entrepot = ENTREPOTS.id_entrepot";
        $request = selectJoinDB("ENTREPOTS", "*",$string,"ENTREPOTS.id_entrepot = ".$id);

        if(!$entrepot){
            exit_with_message("Impossible to select data for entrepot in the DB with the id : ".$id);
        }

        exit_with_content($this->stock($request));
    }


    //-------------------------------------
    
    public function createEntrepot(EntrepotModel $entr){

        //var_dump($entr);
        if(selectDB("ENTREPOTS", "*", "nom_entrepot='".$entr->nom."' AND localisation='".$entr->localisation."'", "bool")){
            exit_with_message("Entrepot already exists", 403);
        }
        if(insertDB("ENTREPOTS", ["nom_entrepot", "localisation"], [$entr->nom, $entr->localisation])){
            exit_with_message("Entrepot added successfully", 200);
        }
    }

    //-------------------------------------
    
    public function updateEntrepot(EntrepotModel $entr){

        if($entr->id_entrepot == null){
            exit_with_message("Impossible to update entrepot in the DB, need to specifie the entrepot you want to update");
        }

        $columnArray = [];
        $valuesArray = [];


        if($entr->nom != null){
            array_push($columnArray, "nom_entrepot");
            array_push($valuesArray, $entr->nom);
        }

        if($entr->localisation != null){
            array_push($columnArray, "localisation");
            array_push($valuesArray, $entr->localisation);
        }


        if(updateDB("ENTREPOTS", $columnArray, $valuesArray, "id_entrepot=".$entr->id_entrepot, "bool")){
            exit_with_message("Entrepot updated with success", 200);
        }
        else{
            exit_with_message("An error occurred while updating the entrepot ".$entr->nom." (".$entr->id_entrepot.") in the DB");
        }

    }

    

    //-------------------------------------

    public function unreferenceEntrepotById($id){

        /*if($id == 1){
            exit_with_message("Impossible to delete the entrepot with id 1", 403);
        }*/

        $tmp = selectDB("ENTREPOTS", "*", "id_entrepot=".$id, "bool");
        if(!$tmp){
            exit_with_message("Impossible to select data for entrepot ".$id." in the DB, it may doesn't exist :/", 200);
        }

        // Try to delete it
        if(deleteDB("ENTREPOTS", "id_entrepot=".$id)){
            exit_with_message("Deleting successful", 200);
        }

        // If there is a constrainst violation
        if(!updateDB("UTILISATEUR", ["id_entrepot"], ["1"], "id_entrepot=".$id, "bool")){
            exit_with_message("Deleting error", 200);
        }
        if(deleteDB("ENTREPOTS", "id_entrepot=".$id, "bool")){
            exit_with_message("Deleting successful entrepot with id".$id, 200);
        }

        exit_with_message("Something went wrong when deleting entrepot with id".$id, 500);

    }
    
}


?>
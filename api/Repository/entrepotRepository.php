<?php
include_once './Repository/BDD.php';
include_once './exceptions.php';

class EntrepotRepository {
    private $connection = null;

    // I'm not sure about this function lol (unuse)
    function __construct() {
       
    }
    
    //-------------------------------------

    public function getEntrepots(){
        $entrepot = selectDB("ENTREPOTS", "*");
        //$entrepot = selectDB("ENTREPOTS", "*", "entrepot_index=1");

        if(!$entrepot){
            exit_with_message("Impossible to select data for entrepot in the DB");
        }

        $entrepotArray = [];

        for ($i=0; $i < count($entrepot) ; $i++) { 
            $entrepotArray[$i] = new EntrepotModel($entrepot[$i]["id_entrepot"], $entrepot[$i]["nom_entrepot"], $entrepot[$i]["localisation"]);
        }

        exit_with_content($entrepotArray);
    }

    //-------------------------------------

    public function getEntrepot($id = null){

        if($id == null){
            exit_with_content("Plz specifie a id for the entepot");
        }

         $entrepot = selectDB("ENTREPOTS", "*", "id_entrepot=".$id);
        //$entrepot = selectDB("ENTREPOTS", "*", "id_entrepot=".$id." AND entrepot_index=1");

        if(!$entrepot){
            exit_with_message("Impossible to select data for entrepot in the DB with the id : ".$id);
        }

        exit_with_content(new EntrepotModel($entrepot[0]["id_entrepot"], $entrepot[0]["nom_entrepot"], $entrepot[0]["localisation"]));
    }


    //-------------------------------------
    
    public function createEntrepot(EntrepotModel $entr){

        if(selectDB("ENTREPOTS", "*", "nom_entrepot=".$entr->nom." AND localisation=".$entr->localisation, "bool")){
            exit_with_message("Entrepot already exists");
        }
        if(insertDB("ENTREPOTS", ["nom_entrepot", "localisation"], [$entr->nom, $entr->localisation]) == true){
            exit_with_message("Entrepot added successfully");
        };
    }

    //-------------------------------------
    
    public function updateEntrepot(EntrepotModel $entr){

        if($entr->id_entrepot == null){
            exit_with_message("Impossible to update entrepot in the DB, need to specifie the entrepot you want to update");
        }

        $columnArray = [];
        $valuesArray = [];

        if($entr->nom != null){
            array_push($columnArray, "nom");
            array_push($valuesArray, $entr->nom);
        }

        if($entr->localisation != null){
            array_push($columnArray, "localisation");
            array_push($valuesArray, $entr->localisation);
        }

        if(updateDB("ENTREPOTS", $columnArray, $valuesArray, "id_entrepot=".$entr->id_entrepot)){
            exit_with_message("Entrepot updated with success", 200);
        }
        else{
            exit_with_message("An error occurred while updating the entrepot ".$entr->nom." (".$entr->id_entrepot.") in the DB");
        }

    }

    

    //-------------------------------------

    public function unreferenceEntrepotById($id){

        $tmp = selectDB("ENTREPOTS", "*", "id_entrepot=".$id, "bool");
        if(!$tmp){
            exit_with_message("Impossible to select data for entrepot ".$id." in the DB, it may doesn't exist :/");
        }

        if(updateDB("ENTREPOTS", "index_entrepot=".$id, "-1")){
            exit_with_message("Deleting successful", 200);
        }
        else{
            exit_with_message("An error occurred while deleting the entrepot : ".$id);
        }
    }
    
}


?>
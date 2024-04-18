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
        //$entrepot = selectDB("ENTREPOTS", "*", "entrepot_index=1");

        if(!$entrepot){
            exit_with_message("Impossible to select data for entrepot in the DB with the id : ".$id);
        }

        exit_with_content(new EntrepotModel($entrepot[0]["id_entrepot"], $entrepot[0]["nom_entrepot"], $entrepot[0]["localisation"]));
    }


    //-------------------------------------
    
    public function createEntrepot(EntrepotModel $entr, $password){
        
    }

    //-------------------------------------
    
    public function updateEntrepot(EntrepotModel $entr, $apikey){

    }

    

    //-------------------------------------

    public function unreferenceEntrepotById($id, $apiKey){

    }
    
}


?>
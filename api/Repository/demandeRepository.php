<?php

class DemandeRepository
{

    private function affiche($request) {
        $array = [];
        $demandes = [];

        for ($i = 0; $i < count($request); $i++) {
            $item = $request[$i];
            $id_demande = $item["id_demande"];

            if (!isset($demandes[$id_demande])) {
                $demandes[$id_demande] = [
                    "id_demande" => $id_demande,
                    "desc_demande" => $item["desc_demande"],
                    "id_planning" => $item["id_planning"],
                    "id_user" => $item["id_user"],
                    "collecte" => []
                ];
            }

            $collecte = [
                "id_collecte" => $item["id_collecte"],
                "quantite" => $item["quantite"],
                "id_produit" => $item["id_produit"]
            ];

            $demandes[$id_demande]["collecte"][] = $collecte;

        }


        for ($j = 1; $j < count($demandes)+1; $j++) {
            $demande = $demandes[$j];

            if (!$demandes[$j]["collecte"][0]["id_collecte"]) {
                $array[] = new DemandeModel(
                    $demande["id_demande"],
                    $demande["desc_demande"],
                    $demande["id_planning"],
                    $demande["id_user"],
                    NULL);
            }else{
                $array[] = new DemandeModel(
                    $demande["id_demande"],
                    $demande["desc_demande"],
                    $demande["id_planning"],
                    $demande["id_user"],
                    $demande["collecte"]
                );
            }
        }


        return $array;
    }


//------------------------------------------------------------------------------------------------------------

    function get($apikey = null){

        //Select DEMANDE.id_demande, DEMANDE.desc_demande, DEMANDE.id_user, DEMANDE.id_planning, R.id_collecte, C.quantite, C.id_produit from DEMANDE INNER JOINRECU R ON R.id_demande = DEMANDE.id_demande INNER JOIN COLLECTE C ON C.id_collecte = R.id_collecte;

        $request = 0;
        if($apikey == null){
            $colums="DEMANDE.id_demande, DEMANDE.desc_demande, DEMANDE.id_user, DEMANDE.id_planning, R.id_collecte, C.quantite, C.id_produit";
            $string="LEFT JOIN RECU R ON R.id_demande = DEMANDE.id_demande LEFT JOIN COLLECTE C ON C.id_collecte = R.id_collecte;";
            $request = selectJoinDB("DEMANDE", $colums ,$string);

            if(!$request){
                exit_with_message("No demande saved", 200);
            }

            exit_with_content($this->affiche($request));
        }
        else{
            $idUser = getIdUserFromApiKey($apikey);
            $colums="DEMANDE.id_demande, DEMANDE.desc_demande, DEMANDE.id_user, DEMANDE.id_planning, R.id_collecte, C.quantite, C.id_produit";
            $string="INNER JOIN RECU R ON R.id_demande = DEMANDE.id_demande INNER JOIN COLLECTE C ON C.id_collecte = R.id_collecte;";
            $request = selectJoinDB("DEMANDE", $colums ,$string ,"id_user=".$idUser, "bool");


            if(!$request){
                exit_with_message("No demande saved", 200);
            }

            exit_with_content($this->affiche($request));

        }

        exit_with_message("Why are you here ?", 500);
    }

    //------------------------------------------------------------------------------------------------------------

    function getByUser($id_user){
        $colums="DEMANDE.id_demande, DEMANDE.desc_demande, DEMANDE.id_user, DEMANDE.id_planning, R.id_collecte, C.quantite, C.id_produit";
        $string="LEFT JOIN RECU R ON R.id_demande = DEMANDE.id_demande LEFT JOIN COLLECTE C ON C.id_collecte = R.id_collecte;";
        $request = selectJoinDB("DEMANDE", $colums ,$string ,"id_user=".$id_user, "bool");


        exit_with_content($this->affiche($request));
    }

    //------------------------------------------------------------------------------------------------------------

    function createDemande($data, $idUser, $produits){

        $request = insertDB("DEMANDE", ["desc_demande", "id_user"], [$data["desc_demande"], $idUser]);

        if (!$request) {
            exit_with_message("Error creating demande", 400);
        }
        $id_demande = $this->getLastInsertId("DEMANDE","id_demande");// Récupère l'ID de la dernière demande créée
        //souvenir
        //echo $id_demande[ 0]["id_demande"];
        if ($produits == null) {
            exit_with_message("Sucessfully created demande", 200);
        }


        foreach ($produits as $produit) {
            $request_collecte = insertDB("COLLECTE", ["quantite", "id_produit"], [$produit["quantite"], $produit["id_produit"]]);

            if (!$request_collecte) {
                exit_with_message("Error creating collecte", 400);
            }

            $id_collecte = $this->getLastInsertId("COLLECTE","id_collecte"); // Récupère l'ID de la dernière collecte créée


            // Insère les ID de demande et de collecte dans la table RECU
            $request_recu = insertDB("RECU", ["id_demande", "id_collecte", "recu"], [$id_demande[0]["id_demande"], $id_collecte[0]["id_collecte"], 1]);

            if (!$request_recu) {
                exit_with_message("Error creating recu", 400);
            }
        }

            exit_with_message("Sucessfully created demande", 200);
    }

//------------------------------------------------------------------------------------------------------------

    function updateDemande($id_demande, $id_planning){
        $request = updateDB("DEMANDE", ["id_planning"], [$id_planning], "id_demande=".$id_demande, bool);

        if($request){
            exit_with_message("Sucessfully updated demande", 200);
        }
        exit_with_message("Error updating demande", 400);
    }

    //------------------------------------------------------------------------------------------------------------

    function deleteDemande($id){
        $colums="DEMANDE.id_demande, DEMANDE.desc_demande, DEMANDE.id_user, DEMANDE.id_planning, R.id_collecte, C.quantite, C.id_produit";
        $string="LEFT JOIN RECU R ON R.id_demande = DEMANDE.id_demande LEFT JOIN COLLECTE C ON C.id_collecte = R.id_collecte";
        $resquest = selectJoinDB("DEMANDE", $colums ,$string,"DEMANDE.id_demande=".$id,"bool");

        for ($i=0; $i < count($resquest); $i++) {

            if ($resquest) {

                $tmp = deleteDB("RECU", "id_collecte=" . $resquest[$i]['id_collecte'], "bool");
                if (!$tmp) {
                    exit_with_message("The demande doesn't exist", 200);
                }
                $tmp = deleteDB("COLLECTE", "id_collecte=" . $resquest[$i]['id_collecte'], "bool");
                if (!$tmp) {
                    exit_with_message("The demande doesn't exist", 200);
                }
            }
        }

        if($resquest)

            $tmp = deleteDB("DEMANDE", "id_demande=".$id, "bool");
            if(!$tmp){
                exit_with_message("The demande doesn't exist", 200);
            }
        exit_with_message("Sucessfully deleted demande", 200);
    }

    //----------------------------------------------------------------------------------------------------

    private function getLastInsertId($table,$id)
    {
        $string = "ORDER BY ".$id." DESC LIMIT 1";
       $envoie= selectDB($table,$id,-1, $string);
        return $envoie;
    }
}
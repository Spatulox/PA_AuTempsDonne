<?php

class DemandeRepository
{

    private function affiche($request)
    {
        $array = [];
        $demandes = [];

        foreach ($request as $item) {
            $id_demande = $item["id_demande"];

            if (!isset($demandes[$id_demande])) {
                $demandes[$id_demande] = [
                    "id_demande" => $id_demande,
                    "desc_demande" => $item["desc_demande"],
                    "activite" => $item["activite"],
                    "etat" => $item["etat"],
                    "date_act" => $item["date_act"],
                    "id_activite" => $item["id_activite"],
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

            if (!empty($collecte["id_collecte"])) {
                $demandes[$id_demande]["collecte"][] = $collecte;
            }
        }

        foreach ($demandes as $demande) {
            if (empty($demande["collecte"])) {
                $array[] = new DemandeModel(
                    $demande["id_demande"],
                    $demande["desc_demande"],
                    $demande["activite"],
                    $demande["etat"],
                    $demande["date_act"],
                    $demande["id_activite"],
                    $demande["id_planning"],
                    $demande["id_user"],
                    NULL
                );
            } else {
                $array[] = new DemandeModel(
                    $demande["id_demande"],
                    $demande["desc_demande"],
                    $demande["activite"],
                    $demande["etat"],
                    $demande["date_act"],
                    $demande["id_activite"],
                    $demande["id_planning"],
                    $demande["id_user"],
                    $demande["collecte"]
                );
            }
        }

        return $array;
    }



//------------------------------------------------------------------------------------------------------------

    function get($apikey = null)
    {

        //Select DEMANDE.id_demande, DEMANDE.desc_demande, DEMANDE.id_user, DEMANDE.id_planning, R.id_collecte, C.quantite, C.id_produit from DEMANDE INNER JOINRECU R ON R.id_demande = DEMANDE.id_demande INNER JOIN COLLECTE C ON C.id_collecte = R.id_collecte;

        $request = 0;
        if ($apikey == null) {
            $colums = "DEMANDE.id_demande, DEMANDE.desc_demande,DEMANDE.activite,DEMANDE.etat, DEMANDE.date_act, DEMANDE.id_activite, DEMANDE.id_user, DEMANDE.id_planning, R.id_collecte, C.quantite, C.id_produit";
            $string = "LEFT JOIN RECU R ON R.id_demande = DEMANDE.id_demande LEFT JOIN COLLECTE C ON C.id_collecte = R.id_collecte;";
            $request = selectJoinDB("DEMANDE", $colums, $string, -1, "bool");

            if (!$request) {
                exit_with_message("No demande saved", 200);
            }

            exit_with_content($this->affiche($request));
        } else {
            $idUser = getIdUserFromApiKey($apikey);
            $colums = "DEMANDE.id_demande, DEMANDE.desc_demande,DEMANDE.activite,DEMANDE.etat, DEMANDE.date_act,DEMANDE.id_activite, DEMANDE.id_user, DEMANDE.id_planning, R.id_collecte, C.quantite, C.id_produit";
            $string = "INNER JOIN RECU R ON R.id_demande = DEMANDE.id_demande INNER JOIN COLLECTE C ON C.id_collecte = R.id_collecte;";
            $request = selectJoinDB("DEMANDE", $colums, $string, "id_user=" . $idUser, "bool");


            if (!$request) {
                exit_with_message("No demande saved", 200);
            }

            exit_with_content($this->affiche($request));

        }


        exit_with_message("Why are you here ?", 500);
    }

    //------------------------------------------------------------------------------------------------------------

    function getByUser($id_user,$apiKey)
    {
        $colums = "DEMANDE.id_demande, DEMANDE.desc_demande,DEMANDE.activite,DEMANDE.etat, DEMANDE.date_act,DEMANDE.id_activite, DEMANDE.id_user, DEMANDE.id_planning, R.id_collecte, C.quantite, C.id_produit";
        $string = "LEFT JOIN RECU R ON R.id_demande = DEMANDE.id_demande LEFT JOIN COLLECTE C ON C.id_collecte = R.id_collecte";
        $request = selectJoinDB("DEMANDE", $colums, $string, "id_user=" . $id_user,"bool");
        if(!$request){
         exit_with_message("pas de Demande",500);
        }

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 1;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        exit_with_content($this->affiche($request));
    }

    //------------------------------------------------------------------------------------------------------------

    function createDemande($data, $idUser, $produits,$apiKey)
    {

        if ($data["date_act"]) {
            $request = insertDB("DEMANDE", ["desc_demande", "activite", "etat", "date_act", "id_activite", "id_user"], [$data["desc_demande"], $data["activite"], $data["etat"], $data["date_act"], $data["id_activite"], $idUser]);
        } else {
            $request = insertDB("DEMANDE", ["desc_demande", "activite", "etat", "id_activite", "id_user"], [$data["desc_demande"], $data["activite"], $data["etat"], $data["id_activite"], $idUser]);
        }


        if (!$request) {
            exit_with_message("Error creating demande", 400);
        }
        $id_demande = $this->getLastInsertId("DEMANDE", "id_demande");
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

            $id_collecte = $this->getLastInsertId("COLLECTE", "id_collecte"); // Récupère l'ID de la dernière collecte créée


            // Insère les ID de demande et de collecte dans la table RECU
            $request_recu = insertDB("RECU", ["id_demande", "id_collecte", "recu"], [$id_demande[0]["id_demande"], $id_collecte[0]["id_collecte"], 1]);

            if (!$request_recu) {
                exit_with_message("Error creating recu", 400);
            }
        }

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 1;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        exit_with_message("Sucessfully created demande", 200);
    }

//------------------------------------------------------------------------------------------------------------

    function updateDemande($id_demande, $id_planning,$apiKey)
    {
        $request = updateDB("DEMANDE", ["id_planning"], [$id_planning], "id_demande=" . $id_demande, "bool");

        if ($request) {

            $historiqueRepo = new HistoriqueRepository();
            $description_hist = "Produit not deleted .";
            $id_secteur = 1;
            $id_user =getIdUserFromApiKey($apiKey);

            $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

            exit_with_message("Sucessfully updated demande", 200);
        }

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 1;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        exit_with_message("Error updating demande", 400);
    }

    //------------------------------------------------------------------------------------------------------------

    function deleteDemande($id,$apiKey)
    {
        $colums = "DEMANDE.id_demande, DEMANDE.desc_demande,DEMANDE.activite,DEMANDE.etat, DEMANDE.date_act,DEMANDE.id_activite, DEMANDE.id_user, DEMANDE.id_planning, R.id_collecte, C.quantite, C.id_produit";
        $string = "LEFT JOIN RECU R ON R.id_demande = DEMANDE.id_demande LEFT JOIN COLLECTE C ON C.id_collecte = R.id_collecte";
        $resquest = selectJoinDB("DEMANDE", $colums, $string, "DEMANDE.id_demande=" . $id, "bool");

        for ($i = 0; $i < count($resquest); $i++) {

            if ($resquest){
                 if ($resquest[0]["id_collecte"]) {

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
        }

        if ($resquest)

            $tmp = deleteDB("DEMANDE", "id_demande=" . $id, "bool");
        if (!$tmp) {
            exit_with_message("The demande doesn't exist", 200);
        }

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 1;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        exit_with_message("Sucessfully deleted demande", 200);
    }

    //----------------------------------------------------------------------------------------------------

    private function getLastInsertId($table, $id)
    {
        $string = "ORDER BY " . $id . " DESC LIMIT 1";
        $envoie = selectDB($table, $id, -1, $string);
        return $envoie;
    }

    //------------------------------------------------------------------------------------------------------

    public function createValidationDemande($id,$apiKey)
    {
        $res = selectDB("DEMANDE", "*", "id_demande=" . $id);
        if (!$res) {
            exit_with_message("existe pas demande", 500);
        }

        $addresse = selectDB("UTILISATEUR", "id_adresse", "id_user=" . $res[0]["id_user"]);

        $id_trajets = $this->getLastInsertId("TRAJETS", "id_trajets");
        $trajets = (int)$id_trajets[0]["id_trajets"] + 1;
        $test = insertDB("TRAJETS", ["id_trajets"], [$trajets]);
        $id_trajets = $this->getLastInsertId("TRAJETS", "id_trajets");
        insertDB("UTILISER", ["id_trajets", "id_adresse"], [$id_trajets[0]["id_trajets"], $addresse[0]["id_adresse"]]);


        $id_index_planning = 3;
        $trajet = 0;
        $test = insertDB("PLANNINGS", ["description", "date_activite", "id_index_planning", "id_trajets", "id_activite"], [$res[0]["desc_demande"], $res[0]["date_act"], $id_index_planning, $id_trajets[0]["id_trajets"], $res[0]["id_activite"]]);
        $etat = 0;
        $last_planning = $this->getLastInsertId("PLANNINGS", "id_planning");
        updateDB("DEMANDE", ["etat", "id_planning"], [$etat, $last_planning[0]["id_planning"]], "id_demande=" . $id);

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 1;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        exit_with_message("Validate success", 200);
    }

    public function createValidationDemandeGroupe($id, $id_depart, $id_arriver, $date,$apiKey)
    {
        $array[0] = $id_depart;
        for ($i = 0; $i < count($id); $i++) {

            $res[] = selectDB("DEMANDE", "*", "id_demande=" . $id[$i]);
            if (!$res) {
                exit_with_message("existe pas demande", 500);
            }
            $addresse[$i] = selectDB("UTILISATEUR", "id_adresse", "id_user=" . $res[$i][0]["id_user"]);

            $array[$i + 1] = $addresse[$i][0]["id_adresse"];
        }
        
        $array[$i + 1] = $id_arriver;


        $id_index_planning = 3;

        insertDB("PLANNINGS", ["description", "date_activite", "id_index_planning", "id_activite"], [$res[0][0]["desc_demande"], $date, $id_index_planning, $res[0][0]["id_activite"]]);
        $etat = 0;
        $last_planning = $this->getLastInsertId("PLANNINGS", "id_planning");
        for ($j = 0; $j < count($id); $j++) {

            updateDB("DEMANDE", ["etat", "id_planning"], [$etat, $last_planning[0]["id_planning"]], "id_demande=" . $id[$j],);
        }

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 1;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

        exit_with_content($array);

    }

    public function getAttente($apiKey)
    {

            $colums = "DEMANDE.id_demande, DEMANDE.desc_demande,DEMANDE.activite,DEMANDE.etat, DEMANDE.date_act, DEMANDE.id_activite, DEMANDE.id_user, DEMANDE.id_planning, R.id_collecte, C.quantite, C.id_produit";
            $string = "LEFT JOIN RECU R ON R.id_demande = DEMANDE.id_demande LEFT JOIN COLLECTE C ON C.id_collecte = R.id_collecte";
            $request = selectJoinDB("DEMANDE", $colums, $string, "DEMANDE.etat=0");

            if (!$request) {
                exit_with_message("No demande saved", 200);
            }

        $historiqueRepo = new HistoriqueRepository();
        $description_hist = "Produit not deleted .";
        $id_secteur = 1;
        $id_user =getIdUserFromApiKey($apiKey);

        $historiqueRepo->Createhistorique($description_hist, $id_secteur, $id_user);

            exit_with_content($this->affiche($request));
    }
}
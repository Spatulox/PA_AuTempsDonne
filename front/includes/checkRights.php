<?php

$apikey = $_COOKIE["apikey"];

$returnCode = true;

if($apikey !== ""){

    $currPage = basename($_SERVER["PHP_SELF"]);


// Prepare address for others page
    $file = file_get_contents("../includes/address.json");
    $address = json_decode($file, true);


// Connexion à la base de données
// Lire le contenu du fichier JSON
    $json_file = file_get_contents('/var/www/html/env.json');

// Décoder le contenu JSON en un tableau PHP
    $dataBDD = json_decode($json_file, true);

    $dbHost = $dataBDD['DB_HOST'];
    $dbPort = $dataBDD['DB_PORT'];
    $dbName = $dataBDD['DB_NAME'];
    $dbUser = $dataBDD['DB_USER'];
    $dbPassword = $dataBDD['DB_PASSWORD'];

    try {
        $db = new PDO(
            'mysql:host=' . $dbHost . ';
	        port=' . $dbPort . ';
	        dbname=' . $dbName . ';
	        user=' . $dbUser . ';
	        password=' . $dbPassword . '',
            null,
            null,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );

        // Requête SQL
        $sql = "SELECT id_role FROM UTILISATEUR WHERE apikey='" . $apikey . "'";
        $stmt = $db->prepare($sql);
        $stmt->execute();


        // Traiter les résultats
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
        if (count($results) == 0) {
            $returnCode = false;
        }

    } catch (Exception $e) {
        $returnCode = false;
    }


// Check if the user can be here

    $rightfile = file_get_contents("../includes/rights.json");
    $right = json_decode($rightfile, true);

    if ($returnCode == true) {

        if (in_array($currPage, $right[$results["id_role"]]) || in_array($currPage, $right["all"]) ) {
            $returnCode = true;
        } else {
            $returnCode = false;
        }

    } else {

    }
} else{
    $returnCode = true;
}



?>

<?php

include_once './Repository/BDD.php';

class TrajetModel {

    private $bdd;

    public function __construct() {
        $this->bdd = getDatabaseConnection(); // Ensure you have a function to get a database connection
    }

    public function createTripEntry() {
        $stmt = $this->bdd->prepare("INSERT INTO TRAJETS DEFAULT VALUES");
        $stmt->execute();
        return $this->bdd->lastInsertId(); // Returns the ID of the inserted trip
    }

    public function associateAddressWithTrip($trajetId, $addressId) {
        $this->insertDB("UTILISER", ["id_trajets", "id_adresse"], [$trajetId, $addressId]);
    }

    private function insertDB($tableName, $columns, $values) {
        $columnsString = implode(", ", $columns);
        $placeholders = implode(", ", array_fill(0, count($values), "?"));
        $sql = "INSERT INTO $tableName ($columnsString) VALUES ($placeholders)";
        $stmt = $this->bdd->prepare($sql);
        $stmt->execute($values);
    }
}

?>
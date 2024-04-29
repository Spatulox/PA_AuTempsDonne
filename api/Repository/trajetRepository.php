<?php

class TrajetRepository {
    private $model;

    public function __construct() {
        $this->model = new TrajetModel();
    }

    function createTrajet($addressIds) {

        if (empty($addressIds) || !is_array($addressIds)) {
            throw new Exception("Entrée des id_adresse invalide.");
        }

        $allCreates = [];

        foreach ($addressIds as $addressId) {
            $create = insertDB("UTILISER", ["id_trajets", "id_adresse"], [$trajetIds, $addressId]);

            if ($create) {
                $allCreates = $create;
            }

        }

        return $allCreates;
    }

}

?>
<?php



class TrajetService {

    private $trajetModel;

    public function __construct() {
        $this->trajetModel = new TrajetModel();
    }

    public function createTrajet($addressIds) {
        if (empty($addressIds) || !is_array($addressIds)) {
            throw new Exception("Entrée des id_adresse invalide.");
        }

        // Create a new trip ID
        $trajetId = $this->trajetModel->createTripEntry();

        // Associate each address with the trip
        foreach ($addressIds as $addressId) {
            $this->trajetModel->associateAddressWithTrip($trajetId, $addressId);
        }

        return $trajetId;
    }
}

?>
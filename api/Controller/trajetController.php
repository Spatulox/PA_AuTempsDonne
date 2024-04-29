<?php

include_once './Service/trajetService.php';
include_once './Models/trajetModel.php';

class TrajetController {

    private $trajetService;

    public function __construct() {
        $this->trajetService = new TrajetService();
    }

    public function createTrajet($addressIds) {
        try {
            $tripId = $this->trajetService->createTrajet($addressIds);
            return json_encode(['status' => 'success', 'tripId' => $tripId]);
        } catch (Exception $e) {
            return json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}

?>
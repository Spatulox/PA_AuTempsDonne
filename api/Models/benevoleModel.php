<?php

include_once './Models/userModel.php';

class BenevoleModel {
    public UserModel $userModel;
    public $activity;

    public function __construct(UserModel $userModel, $activity) {
        $this->userModel = $userModel;
        $this->activity = $activity;
    }
}

?>
<?php
    include_once './Service/planningService.php';
    include_once './Models/planningModel.php';
    include_once './exceptions.php';

    function planningController($uri, $apiKey) {

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':

                if($apiKey == null){
                    exit_with_message("Unauthorized, need the apikey", 403);
                }

                $planningService = new PlanningService();

               
                if(!$uri[3]){
                    exit_with_content($planningService->getAllPlanning($apiKey));
                }
                elseif($uri[3] && $uri[3]==="validate"){

                    exit_with_content($planningService->getAllPlanningvalidate($apiKey));
                }
                elseif($uri[3] && $uri[3]==="wait"){

                    exit_with_content($planningService->getAllPlanningenattente($apiKey));
                }


                elseif($uri[3] && $uri[3]==="me"){

                    exit_with_content($planningService->getPlanningByUser($apiKey));
                }
                elseif($uri[3] && $uri[3]==="affecte"){

                    exit_with_content($planningService->getPlanningNoAffecte($apiKey));
                }


                elseif($uri[3] && filter_var($uri[3], FILTER_VALIDATE_INT)){
                    $planningService->getPlanningByIdUser($uri[3], $apiKey);
                }


                else{
                    exit_with_message("You need to be admin to see all the planning", 403);
                }
                
                break;

            case 'POST':

                $planningService = new PlanningService();

                $body = file_get_contents("php://input");
                $json = json_decode($body, true);


                    if ($uri[3] && $uri[3]=== 'create_planning'){
                        if (!isset($json['description']) || !isset($json['date_activite']) || !isset($json['id_activite'])) {
                            exit_with_message("Please provide all required fields to create a new planning", 400);
                        }



                        $planning = new PlanningModel(
                            1,
                            $json['description'],
                            $json['date_activite'],
                            3,
                            $json['id_activite']
                        );

                        $dateString = $json['date_activite'];

                        try {
                            $dateTime = new DateTime($dateString);
                            $dateTimeString = $dateTime->format("Y-m-d H:i:s");
                            $hour = $dateTime->format('H');
                            $min = $dateTime->format('i');
                            $sec = $dateTime->format('s');

                            if ($hour === '00' && $min === '00' && $sec === '00') {
                                exit_with_message("Vous devez spécifier le bon format de l'heure ou l'heure ne peut pas être minuit.");
                            }
                        } catch (Exception $e) {
                            exit_with_message("Erreur Controller : " . $e->getMessage());
                        }

                        $createdPlanning = $planningService->createPlanning($planning, $apiKey);

                        if ($createdPlanning) {
                            exit_with_message("Succes", 200);
                        } else {
                            exit_with_message("Error while creating the planning.", 500);
                        }
                    }

                    elseif ($uri[3] && $uri[3]=== 'join_activity') {
                        if (!isset($json['user_id']) || !isset($json['id_planning'])) {
                            exit_with_message("Please provide both user_id and id_planning to join an activity", 400);
                        }

                        $planningService->joinActivity($json['user_id'], $json['id_planning'], $json['confirme'], $apiKey);

                    }

                    elseif ($uri[3] && $uri[3]=== 'link') {
                        if (!isset($json['id_trajet']) || !isset($json['id_planning'])) {
                            exit_with_message("Please provide both user_id and id_planning to join an activity", 400);
                        }
                        $planning = array(
                            $json['id_trajet'],
                            $json['id_planning']
                        );

                        $linkPlanning = $planningService->linkPlanning($planning, $apiKey);
                        if ($linkPlanning) {
                            exit_with_content($linkPlanning);
                        } else {
                            exit_with_message("Error while creating the planning.", 500);
                        }
                    }
                    elseif($uri[3] && $uri[3]==="date"){

                        if (!isset($json['date_activite'])) {
                            exit_with_message("Please provide date ", 400);
                        }
                        exit_with_content($planningService->getPlanningBydate($apiKey,($json['date_activite'])));
                    }elseif ($uri[3]=="deletejoin") {

                        if (!isset($json['user_id']) || !isset($json['id_planning'])) {
                            exit_with_message("Please provide both user_id and id_planning to join an activity", 400);
                        }
                        $planningService->deletejoin($json['user_id'], $json['id_planning'], $apiKey);
                    }

                    else {
                        if (!isset($json['user_id']) || !isset($json['id_planning'])) {
                            exit_with_message("Please provide both user_id and id_planning to join an activity", 400);
                        }
                        exit_with_message("Invalid action specified", 400);
                    }

                break;




            case 'PUT':
                    $planningService = new PlanningService();

                    $body = file_get_contents("php://input");
                    $json = json_decode($body, true);
                    if (!$uri[3]) {
                        if (!isset($json["id_planning"]) || !isset($json["description"]) || !isset($json["date_activite"])  || !isset($json["id_activite"])) {
                            exit_with_message("Plz give, at least, the id_planning, id_activite, description,  date_activite and id_index_planning");
                        }
                        $role=getRoleFromApiKey($apiKey);
                        if ($role ==2 || $role==1) {
                        $id_index_planning=2;
                        }elseif ($role==4) {
                            $id_index_planning=3;
                        }

                        $dateString = $json['date_activite'];

                        try {
                            $dateTime = new DateTime($dateString);
                            $dateTimeString = $dateTime->format("Y-m-d H:i:s");
                            $hour = $dateTime->format('H');
                            $min = $dateTime->format('i');
                            $sec = $dateTime->format('s');

                            if ($hour === '00' && $min === '00' && $sec === '00') {
                                exit_with_message("Vous devez spécifier le bon format de l'heure, l'heure ne peut pas être minuit.");
                            }
                        } catch (Exception $e) {
                            exit_with_message("Erreur Controller : " . $e->getMessage());
                        }


                        $planningService->updatePlanning($json["id_planning"], $json["description"], $json["date_activite"],$id_index_planning, $json["id_activite"]);
                    }
                    elseif ($uri[3] && $uri[3]=== 'validate' && filter_var($uri[4], FILTER_VALIDATE_INT)) {
                        if (!isset($json["id_index_planning"])) {
                            exit_with_message("Plz give, id_index_planning");
                        }
                        exit_with_content($planningService->updateValidatePlanning($json["id_index_planning"],$uri[4],$apiKey));
                    } elseif ($uri[3] && $uri[3]=== 'join' ) {

                        if (!isset($json["id_planning"])) {
                            exit_with_message("Plz give, id_planning");
                        }
                        exit_with_content($planningService->updatejoinPlanning($json["id_planning"],$json['confirme'],$apiKey));
                    }
                    break;

            case 'DELETE':
                    $planningService = new PlanningService();

                    if(!$uri[3]){
                        exit_with_message("No planning specified", 400);
                    } else {

                    $planningService->deletePlanning($uri[3],$apiKey);
                    }

                    break;

            
            default:
                exit_with_message("Not Found", 404); 
                exit();
        }
    }
    ?>
<?php
include_once '../includes/BDD.php';





function exit_with_message($message = "Internal Server Error", $code = 500) {
    http_response_code($code);
    echo '{"message": "' . $message . '"}';
    exit();
}

function exit_with_content($content = null, $code = 200) {
    http_response_code($code);
    echo json_encode($content);
    exit();
}

$apikey = $_COOKIE["apikey"];

// Determine the id_payment
$id_payment = selectDB("PAYMENTHISTORY", "MAX(id_index)")[0][0];

if($id_payment == NULL){
    $id_payment = 1;
} else {
    $id_payment = (int)$id_payment + 1;
}

$id_user = 1;
if($apikey != NULL){
    $id_user = selectDB("UTILISATEUR", "id_role","apikey='".$apikey."'")[0][0];
}


// Retrieve values
$values = explode(",", explode("?name=", $_GET["amount"])[0]);
$object = explode(",", explode("?return_path=", explode("?name=", $_GET["amount"])[1])[0]);
$returnPath = explode(",", explode("?return_path=", $_GET["amount"])[1]);

array_pop($values);
array_pop($object);

for ($i = 0; $i < count($values); $i++) {
    insertDB("PAYMENTHISTORY", ["id_index", "index_user", "amount", "id_product", "success"], [$id_payment, $id_user, $values[$i], $object[$i], "1"]);
}

if($returnPath == null) {
    $returnPath = "moncompte.php";
}

header("Location: ". $returnPath[0]."?message=Votre payement a ete realise avec succes");
exit();


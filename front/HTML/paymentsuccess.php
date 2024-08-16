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

$subject = explode(",", explode("?subject=", explode("?htmlString", $_GET["amount"])[0])[1]);
$htmlString = explode(",", explode("?htmlString=", $_GET["amount"])[1]);

array_pop($values);
array_pop($object);

for ($i = 0; $i < count($values); $i++) {
    insertDB("PAYMENTHISTORY", ["id_index", "index_user", "amount", "id_product", "success"], [$id_payment, $id_user, $values[$i], $object[$i], "1"]);
}

if($returnPath == null) {
    $returnPath = "moncompte.php";
} else {
    explode("?", $returnPath[0])[0];
}
echo'<html>';
echo '<script type="text/javascript" src="../JS/ledicodesmotsenjs.js"></script>';
echo '<script type="text/javascript" src="../JS/utils.js"></script>';

echo '<script type="text/javascript" src="../JSManager/General.js"></script>';
echo '<script type="text/javascript" src="../JSManager/User.js"></script>';
echo '<script type="text/javascript" src="../JSManager/GestionMail.js"></script>';

echo 'Plz wait, processing...';
//echo(explode("?subscription=", $htmlString[0])[0]);

$subscription = explode("?subscription=", $htmlString[0])[1];

if($subscription){
    echo("Votre abonnement dura " . $subscription . " mois, à parti d'aujourd'hui");
    $date = date("Y-m-d");
    updateDB("UTILISATEUR", ["premium", "date_premium", "month_premium"], ["1", $date, $subscription], "apikey='".$apikey."'");
}

echo "<script type='text/javascript' defer>
        let amount = " .  array_sum($values) . "
    
        async function send(){
            const gestionMail = new GestionMail()
            await gestionMail.connect();
            console.log(['M78stormtrooper@laposte.net', gestionMail.email]);
            await gestionMail.sendMail('$subject[0]', '$htmlString[0]', [gestionMail.email]);
            //alert('Send finish')
            redirect('$returnPath[0]?message=Votre payment a ete realise avec succes !')   
        }
        
        send()
        
</script>";
?>
<style>
    html {
        height: 100vh;
        width: 100vw;
        cursor: wait;
    }
</style>
</html>


<?php exit(); ?>


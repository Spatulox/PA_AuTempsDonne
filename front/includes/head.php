
<?php
include("../includes/checkRights.php");
include("../includes/functions.php");
?>


<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="">

<link rel="stylesheet" type="text/css" href="../CSS/Balise.css">
<link rel="stylesheet" type="text/css" href="../CSS/Class.css">
<link rel="stylesheet" type="text/css" href="../CSS/Id.css">


<!--<script type="text/javascript" src="../JSManager/user_old.js"></script>-->
<script type="text/javascript" src="../JSManager/General.js"></script>
<script type="text/javascript" src="../JSManager/User.js"></script>
<script type="text/javascript" src="../JSManager/Planning.js"></script>
<script type="text/javascript" src="../JSManager/GestionStripe.js"></script>
<script type="text/javascript" src="../JSManager/GestionMail.js"></script>


<?php

if (basename($_SERVER['REQUEST_URI']) == "etagere.php"){

    echo '<script type="text/javascript" src="../JSManager/Admin.js"></script>';
    echo '<script type="text/javascript" src="../JSManager/GestionEntrepot.js"></script>';

}

if($role == 1 || $role == 2){

    echo '<script type="text/javascript" src="../JSManager/Admin.js"></script>';
    echo '<script type="text/javascript" src="../JSManager/GestionEntrepot.js"></script>';
    echo '<script type="text/javascript" src="../JSManager/GestionUserAdmin.js"></script>';
    echo '<script type="text/javascript" src="../JSManager/GestionPlanning.js"></script>';
    echo '<script type="text/javascript" src="../JSManager/GestionStock.js"></script>';
    echo '<script type="text/javascript" src="../JSManager/GestionVehicle.js"></script>';
    echo '<script type="text/javascript" src="../JSManager/GestionProduct.js"></script>';
    echo '<script type="text/javascript" src="../JSManager/GestionDon.js"></script>';
    echo '<script type="text/javascript" src="../JSManager/GestionTrajet.js"></script>';
    echo '<script type="text/javascript" src="../JSManager/GestionDemandes.js"></script>';
    echo '<script type="text/javascript" src="../JSManager/GestionActivite.js"></script>';
    echo '<script type="text/javascript" src="../JSManager/GestionAddress.js"></script>';

    echo '<script type="text/javascript" src="../JS/qrcode.js"></script>';
    echo '<script type="text/javascript" src="../JS/pdfmake.js"></script>';
    echo '<script type="text/javascript" src="../JS/fonts.js"></script>';

}


if($role == 3){

    echo '<script type="text/javascript" src="../JSManager/Admin.js"></script>';
    echo '<script type="text/javascript" src="../JSManager/GestionStock.js"></script>';
    echo '<script type="text/javascript" src="../JSManager/GestionProduct.js"></script>';

}

if($role == 4){

    echo '<script type="text/javascript" src="../JSManager/Admin.js"></script>';
    echo '<script type="text/javascript" src="../JSManager/BeneficiaireRequest.js"></script>';
    echo '<script type="text/javascript" src="../JSManager/GestionActivite.js"></script>';
    echo '<script type="text/javascript" src="../JSManager/GestionProduct.js"></script>';
}

if($role == 5){

    echo '<script type="text/javascript" src="../JSManager/Admin.js"></script>';
    echo '<script type="text/javascript" src="../JSManager/PrestataireRequest.js"></script>';
    echo '<script type="text/javascript" src="../JSManager/GestionActivite.js"></script>';
    echo '<script type="text/javascript" src="../JSManager/GestionProduct.js"></script>';
}


if (basename($_SERVER['REQUEST_URI']) == "vehicle.php" && $role == 4){

    echo '<script type="text/javascript" src="../JSManager/GestionVehicle.js"></script>';
    echo '<script type="text/javascript" src="../JSManager/GestionEntrepot.js"></script>';

}

?>

<script type="text/javascript" src="../JS/popup.js"></script>
<script type="text/javascript" src="../JS/tabs.js"></script>
<script type="text/javascript" src="../JS/ledicodesmotsenjs.js"></script>
<script type="text/javascript" src="../JS/replaceCharacters.js"></script>
<script type="text/javascript" src="../JS/utils.js"></script>
<script type="text/javascript" src="../JS/functions.js"></script>
<script type="text/javascript" src="../JS/loader.js"></script>
<script type="text/javascript" src="../JS/createHtmlElement.js"></script>


<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBRgUYpS2R7KL3eZeSGVQYj8Gs9_lVy0x0&libraries=places"></script>
<script type="text/javascript" src="../JS/itineraire.js"></script>
<script type="text/javascript" src="../JS/getDistance.js"></script>

<?php
if($returnCode === false){
echo '<script type="text/javascript" defer>redirect("./index.php?message=Vous n\'avez pas accès à cette page")</script>';
}?>



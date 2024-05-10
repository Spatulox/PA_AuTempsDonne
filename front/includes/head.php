
<?php include("../includes/checkRights.php");?>


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


<?php

if($role == 1 || $role == 2){

    echo '<script type="text/javascript" src="../JSManager/Admin.js"></script>';
    echo '<script type="text/javascript" src="../JSManager/GestionEntrepot.js"></script>';
    echo '<script type="text/javascript" src="../JSManager/GestionUserAdmin.js"></script>';
    echo '<script type="text/javascript" src="../JSManager/GestionPlanning.js"></script>';
    echo '<script type="text/javascript" src="../JSManager/GestionStock.js"></script>';

}

?>

<script type="text/javascript" src="../JS/popup.js"></script>
<script type="text/javascript" src="../JS/tabs.js"></script>
<script type="text/javascript" src="../JS/ledicodesmotsenjs.js"></script>
<script type="text/javascript" src="../JS/replaceCharacters.js"></script>
<script type="text/javascript" src="../JS/functions.js"></script>
<script type="text/javascript" src="../JS/utils.js"></script>
<script type="text/javascript" src="../JS/loader.js"></script>
<script type="text/javascript" src="../JS/createHtmlElement.js"></script>

<?php
if($returnCode === false){
echo '<script type="text/javascript" defer>redirect("./index.php?message=Vous n\'avez pas accès à cette page")</script>';
}?>



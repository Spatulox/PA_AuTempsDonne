<?php include("../includes/loadLang.php");?>

<!DOCTYPE html>
<html>
<head>

    <?php include("../includes/head.php"); ?>

    <title><?php echo($data["storehouse"]["title"]) ?></title>
</head>
<body>

<?php include("../includes/header.php");?>

<main>

    <div class="width80 marginAuto">
        <h1 class="textCenter"><?php echo($data["storehouse"]["title"]) ?></h1>

        <div class="tab flex flexAround nowrap">
            <button class="tablinks width100" onclick="openTab('tab1')">Onglet 1</button>
            <button class="tablinks width100" onclick="openTab('tab2')">Onglet 2</button>
            <button class="tablinks width100" onclick="openTab('tab3')">Onglet 3</button>
        </div>

        <div id="tab1" class="tabcontent">
            <h3>Contenu de l'onglet 1</h3>
            <p>Voici le contenu de l'onglet 1.</p>
        </div>

        <div id="tab2" class="tabcontent">
            <h3>Contenu de l'onglet 2</h3>
            <p>Voici le contenu de l'onglet 2.</p>
        </div>

        <div id="tab3" class="tabcontent">
            <h3>Contenu de l'onglet 3</h3>
            <p>Voici le contenu de l'onglet 3.</p>
        </div>
    </div>

    <?php

    ?>

</main>

<?php include("../includes/footer.php");?>

</body>
</html>

<script type="text/javascript" defer>
    const tab = getParamFromUrl("tab")

    if(tab && idExistInPage("tab"+tab)){
        openTab('tab'+tab)
    } else {
        openTab('tab1')
    }
</script>
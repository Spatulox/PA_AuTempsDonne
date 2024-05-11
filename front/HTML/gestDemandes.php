<?php include("../includes/loadLang.php");?>

<!DOCTYPE html>
<html>
<head>

    <?php include("../includes/head.php"); ?>

    <title><?php echo($data["don"]["title"]) ?></title>
</head>
<body>

<?php include("../includes/header.php");?>

<main>

    <div class="width80 marginAuto marginBottom30">
        <h1 class="textCenter"><?php echo($data["don"]["title"]) ?></h1>

        <div class="tab flex flexAround nowrap">
            <button class="tablinks width100" onclick="openTab('tab1')"><?php echo($data["gestDemande"]["title"]) ?></button>
            <button class="tablinks width100" onclick="openTab('tab2')"><?php echo($data["gestDemande"]["title"]) ?></button>
            <button class="tablinks width100" onclick="openTab('tab3')"><?php echo($data["gestDemande"]["title"]) ?></button>
        </div>

        <div id="tab1" class="tabcontent">
            <h2><?php echo($data["gestDemande"]["title"]) ?></h2>
            <table>
                <thead>
                <tr>
                    <td><?php echo $data["gestDemande"]["title"] ?></td>
                </tr>
                </thead>
                <tbody id="bodyList">
                <!-- Les lignes de données seront insérées ici par JavaScript -->
                </tbody>
            </table>
        </div>

        <div id="tab2" class="tabcontent">
            <h2><?php echo($data["gestDemande"]["title"]) ?></h2>
            <table>
                <thead>
                <tr>
                    <td><?php echo $data["gestDemande"]["title"] ?></td>
                </tr>
                </thead>
                <tbody id="bodyMeh">
                <!-- Les lignes de données seront insérées ici par JavaScript -->
                </tbody>
            </table>
        </div>

        <div id="tab3" class="tabcontent">
            <h2><?php echo($data["gestDemande"]["title"]) ?></h2>
            <table>
                <thead>
                <tr>
                    <td><?php echo $data["gestDemande"]["title"] ?></td>
                </tr>
                </thead>
                <tbody id="bodyBruh">
                <!-- Les lignes de données seront insérées ici par JavaScript -->
                </tbody>
            </table>
        </div>

    </div>

</main>

<?php include("../includes/footer.php");?>

</body>
</html>

<script type="text/javascript" defer>

    const request = new DemandeAdmin()
    let demande = []

    async function fillListDemande(){
        const bodyList = document.getElementById("bodyList")
        bodyList.innerHTML = ""

        demande = await request.getAllDemande()

        console.log(demande)
        return

        createBodyTableau(bodyList, demande)
    }

    async function onload(){
        startLoading()
        openTab('tab1')
        await request.connect()

        await fillListDemande()
        stopLoading()
    }

    onload()

</script>
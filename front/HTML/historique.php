<?php include("../includes/loadLang.php");?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <?php include("../includes/head.php"); ?>
    <title><?php echo($data["historique"]["title"]) ?></title>
</head>
<body>
    <?php include("../includes/header.php");?>

    <main id="backgroundFixed">
        <div class="flex flexCenter wrap">
            <h1 class="width100 textCenter noMarginBottom"><?php echo($data["historique"]["title"]) ?></h1>
        </div>

        <div class="tabcontent">
            <table id="historiqueTable" >
                <thead>
                    <tr>
                        <td>ID</td>
                        <td><?php echo($data["historique"]["description"]) ?></td>
                        <td><?php echo($data["historique"]["date"]) ?></td>
                        <td><?php echo($data["historique"]["secteur"]) ?></td>
                        <td><?php echo($data["historique"]["email"]) ?></td>
                    </tr>
                </thead>
                <tbody id="historiqueBody">
                  
                </tbody>
            </table>  
        </div>
    </main>

    <?php include("../includes/footer.php");?>

    <script type="text/javascript">
        const historique =new HistoriqueAdmin()

        async function onload(){
            startLoading()
            await historique.connect()
            await fillArray()
            stopLoading()
        }
        onload()

        async function fillArray() {
            const lesData = await historique.getAllHistorique()
            const historiqueBody = document.getElementById("historiqueBody")

            createBodyTableau(historiqueBody, lesData, ["id_secteur"])
            replaceCharacters()
        }

    </script>
</body>
</html>
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
            <button class="tablinks width100" onclick="openTab('tab1')"><?php echo($data["don"]["title"]) ?></button>
        </div>

        <div id="tab1" class="tabcontent">
            <h2><?php echo($data["don"]["title"]) ?></h2>
            <table>
                <thead>
                <tr>
                    <td><?php echo $data["don"]["id"] ?></td>
                    <td><?php echo $data["don"]["prix"] ?></td>
                    <td><?php echo $data["don"]["date"] ?></td>
                    <td><?php echo $data["don"]["idUser"] ?></td>
                </tr>
                </thead>
                <tbody id="bodyList">
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
    const don = new DonAdmin()

    async function onload(){
        await don.connect()
        const data = await don.getAllDon()

        const body = document.getElementById("bodyList")

        createBodyTableau(body, data)
    }

    onload()

</script>
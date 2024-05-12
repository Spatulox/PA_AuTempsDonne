<?php include("../includes/loadLang.php"); ?>

<!DOCTYPE html>
<html>
<head>

    <?php include("../includes/head.php"); ?>

    <title><?php echo($data["etagere"]["title"]) ?></title>
</head>
<body>

<?php include("../includes/header.php"); ?>

<main>

    <div class="width80 marginAuto marginBottom30">
        <h1 class="textCenter"><?php echo($data["etagere"]["title"]) ?></h1>

        <div class="tab flex flexAround nowrap">
            <button class="tablinks width100"
                    onclick="openTab('tab1')"><?php echo($data["etagere"]["tab1"]["title"]) ?></button>
        </div>

        <div id="tab1" class="tabcontent">
            <h2 class="textCenter"><?php echo($data["etagere"]["tab1"]["title"]) ?></h2>
            <table>
                <thead>
                <tr>
                    <td><?php echo $data["etagere"]["tab1"]["id"] ?></td>
                    <td><?php echo $data["etagere"]["tab1"]["qte"] ?> (L)</td>
                    <td><?php echo $data["etagere"]["tab1"]["desc"] ?></td>
                </tr>
                </thead>
                <tbody id="bodyList">
                <!-- Les lignes de données seront insérées ici par JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

</main>
<?php include("../includes/footer.php"); ?>

</body>
</html>

<script type="text/javascript" defer>

    const etagere = new EntrepotAdmin()
    let stock = []

    async function fillEtagere(){
        const key = getParamFromUrl("key")

        if(key === false){
            popup("Erreur lors de la récupération, need the key of the etagere")
            return
        }

        stock = await etagere.getStockInShelfWithKey(key)

        if(stock === false){
            stopLoading()
            return
        }

        const body = document.getElementById("bodyList")
        createBodyTableau(body, stock, ["id_entrepot", "id_produit", "desc_produit", "date_sortie", "date_entree", "date_peremption", "id_etagere", "entrepot_desc"])
        replaceCharacters()

    }

    async function onload(){
        startLoading()
        await fillEtagere()
        openTab("tab1")
        stopLoading()
    }

    onload()
</script>
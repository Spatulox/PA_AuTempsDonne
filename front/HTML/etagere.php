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
                    onclick="openTab('tab1')"><?php echo($data["product"]["tab1"]["title"]) ?></button>
            <button class="tablinks width100"
                    onclick="openTab('tab2')"><?php echo($data["product"]["tab2"]["title"]) ?></button>
            <button class="tablinks width100"
                    onclick="openTab('tab3')"><?php echo($data["product"]["tab3"]["title"]) ?></button>
        </div>

        <div id="tab1" class="tabcontent">
            <h2 class="textCenter"><?php echo($data["product"]["tab1"]["title"]) ?></h2>
            <table>
                <thead>
                <tr>
                    <td><?php echo $data["product"]["tab1"]["id"] ?></td>
                    <td><?php echo $data["product"]["tab1"]["name"] ?></td>
                    <td><?php echo $data["product"]["tab1"]["type"] ?></td>
                    <td><?php echo $data["product"]["tab1"]["button"] ?></td>
                </tr>
                </thead>
                <tbody id="bodyList">
                <!-- Les lignes de données seront insérées ici par JavaScript -->
                </tbody>
            </table>
        </div>

        <div id="tab2" class="tabcontent">
            <h2 class="textCenter"><?php echo($data["product"]["tab2"]["title"]) ?></h2>
            <!--<input type="number" class="search-box marginBottom10" placeholder="Search by id">-->
            <p id="product-container"
               class="border width60 textCenter"><?php echo($data["product"]["tab2"]["errorMsg"]) ?></p>
        </div>

        <div id="tab3" class="tabcontent">
            <h2 class="textCenter"><?php echo($data["product"]["tab3"]["title"]) ?></h2>
            <hr>
            <div class="textCenter">
                <input id="nameProd" class="marginTop10 search-box" type="text"
                       placeholder="<?php echo($data["product"]["tab1"]["name"]) ?>"><br>
                <div id="leSelectAremplir"
                     class="marginTop10 marginAuto search-box"><?php echo($data["vehicle"]["tab2"]["errorMsg"]) ?>,
                    Impossible to select product type
                </div>
                <br>
                <input class="marginTop30" type="button" onclick="addProduct()"
                       value="<?php echo($data["product"]["tab3"]["title"]) ?>">
            </div>
        </div>
    </div>

</main>
<?php include("../includes/footer.php"); ?>

</body>
</html>

<script type="text/javascript" defer>

    async function onload(){
        startLoading()
        openTab("tab1")
        stopLoading()
    }

    onload()
</script>
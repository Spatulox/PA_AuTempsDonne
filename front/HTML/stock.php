<?php include("../includes/loadLang.php");?>

<!DOCTYPE html>
<html>
	<head>

		<?php include("../includes/head.php"); ?>

		<title><?php echo($data["stock"]["title"]) ?></title>
	</head>
	<body>

		<?php include("../includes/header.php");?>

        <main>

            <div class="width80 marginAuto">
                <h1 class="textCenter"><?php echo($data["stock"]["title"]) ?></h1>

                <div class="tab flex flexAround nowrap">
                    <button class="tablinks width100" onclick="openTab('tab1')"><?php echo($data["stock"]["tab1"]["title"]) ?></button>
                    <button class="tablinks width100" onclick="openTab('tab2')"><?php echo($data["stock"]["tab2"]["title"]) ?></button>
                    <button class="tablinks width100" onclick="openTab('tab3')"><?php echo($data["stock"]["tab3"]["title"]) ?></button>
                </div>

                <div id="tab1" class="tabcontent marginBottom20">
                    <h2 class="flex flexCenter"><?php echo($data["stock"]["tab1"]["title"]) ?></h2>
                    <table class="">
                        <thead>
                        <tr>
                            <td><?php echo($data["storehouse"]["tab1"]["IDStorehouse"]) ?></td>
                            <td><?php echo($data["storehouse"]["tab1"]["name"]) ?></td>
                            <td><?php echo($data["storehouse"]["tab1"]["ParkingPlace"]) ?></td>
                            <td><?php echo($data["storehouse"]["tab1"]["Addresses"]) ?></td>
                            <td><?php echo($data["storehouse"]["tab1"]["Rangement"]) ?></td>
                            <td><?php echo($data["storehouse"]["tab1"]["See"]) ?></td>
                        </tr>
                        </thead>
                        <tbody id="bodyList"></tbody>
                    </table>
                </div>

                <div id="tab1" class="tabcontent marginBottom20">
                    <h2 class="flex flexCenter"><?php echo($data["stock"]["tab1"]["title"]) ?></h2>
                    <table class="">
                        <thead>
                        <tr>
                            <td><?php echo($data["stock"]["tab1"]["ID_Stock"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["QTE"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["Date_Entry"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["Date_Exit"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["Date_perem"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["desc"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["IdEtagere"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["Storehouse_id"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["Storehouse_desc"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["btn_see"]) ?></td>
                        </tr>
                        </thead>
                        <tbody id="bodyList"></tbody>
                    </table>
                </div>

                <div id="tab2" class="tabcontent marginBottom20">
                    <h2 class="flex flexCenter"><?php echo($data["stock"]["tab2"]["title"]) ?></h2>
                    <table class="">
                        <thead>
                        <tr>
                            <td><?php echo($data["stock"]["tab1"]["ID_Stock"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["QTE"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["Date_Entry"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["Date_Exit"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["Date_perem"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["desc"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["IdEtagere"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["Storehouse_desc"]) ?></td>
                        </tr>
                        </thead>
                        <tbody id="bodyDetail"><tr><td colspan="8">Nothing</td></tr></tbody>
                    </table>
                </div>


                <div id="tab3" class="tabcontent marginBottom20">
                    <h2 class="textCenter"><?php echo($data["stock"]["tab3"]["title"]) ?></h2>
                    <p></p>
                </div>
            </div>

        </main>

		<?php include("../includes/footer.php");?>

	</body>
</html>


<script type="text/javascript" defer>
    const stock = new StockAdmin()
    const entrepot = new Entrepot()
    let entre = []
    let lesStocks = []

    async function fillList(){

        const bodyList = document.getElementById("bodyList")
        bodyList.innerHTML =""
        entre = await entrepot.getEntrepot()
        createBodyTableau(bodyList, entre,["id_addresse"], [entrepot.msg["See"]], ["seeStock"], "id_entrepot")
        replaceCharacters()
    }

    async function seeStock(id){
        startLoading()
        const bodyDetail = document.getElementById("bodyDetail")

        const lesData = await stock.getStockById(id)

        console.log(lesData)

        bodyDetail.innerHTML = ""

        createBodyTableau(bodyDetail, lesData, ["id_entrepot", 'id_produit', "produit_desc"])

        replaceCharacters()
        openTab('tab2')
        stopLoading()
    }

    function onload(){
        startLoading()

        openTab("tab1")
        fillList()

        stopLoading()
    }
    onload()

</script>

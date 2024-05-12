<?php include("../includes/loadLang.php"); ?>

<!DOCTYPE html>
<html>
<head>

    <?php include("../includes/head.php"); ?>

    <title><?php echo($data["gestAddress"]["title"]) ?></title>
</head>
<body>

<?php include("../includes/header.php"); ?>

<main>

    <div class="width80 marginAuto marginBottom30">
        <h1 class="textCenter"><?php echo($data["gestAddress"]["title"]) ?></h1>

        <div class="tab flex flexAround nowrap">
            <button class="tablinks width100"
                    onclick="openTab('tab1')"><?php echo($data["gestAddress"]["tab1"]["title"]) ?></button>
            <button class="tablinks width100"
                    onclick="openTab('tab2')"><?php echo($data["gestAddress"]["tab2"]["title"]) ?></button>
        </div>

        <div id="tab1" class="tabcontent">
            <h2><?php echo($data["gestAddress"]["tab1"]["title"]) ?></h2>
            <table>
                <thead>
                <tr>
                    <td>ID_Address</td>
                    <td>Address</td>
                </tr>
                </thead>
                <tbody id="bodyAssign">
                <!-- Les lignes de données seront insérées ici par JavaScript -->
                </tbody>
            </table>
        </div>

        <div id="tab2" class="tabcontent">
            <h2><?php echo($data["gestAddress"]["tab2"]["title"]) ?></h2>

            <div class="block marginAuto width30 border">
                <input class="marginTop20" id="number" type="number" placeholder="Street Number"><br>
                <input class="marginTop20" id="stree" type="text" placeholder="Street"><br>
                <input class="marginTop20" id="postal" type="number" placeholder="Postal Code"><br>
                <input class="marginTop20" id="city" type="text" placeholder="City"><br>
                <input class="marginTop20" type="button" value="Add" onclick="createAddress()">
            </div>
        </div>

    </div>

</main>

<?php include("../includes/footer.php"); ?>

</body>
</html>

<script type="text/javascript" defer>

    const address = new AddressAdmin()

    async function fillAddress() {
        const bodyAssign = document.getElementById("bodyAssign")
        bodyAssign.innerHTML = ""
        let addressData = await address.getAllAddress()
        addressData.shift()
        console.log(addressData)
        createBodyTableau(bodyAssign, addressData)
        replaceCharacters()
    }

    async function createAddress() {
        startLoading()
        const number = document.getElementById("number").value
        const street = document.getElementById("stree").value
        const postal = document.getElementById("postal").value
        const city = document.getElementById("city").value

        let string = number + " " + street + ", " + postal + " " + city

        const resp = await address.createAddress(string)

        if( resp !== false){
            popup("Address crée avec succès")
        }
        stopLoading()
    }

    async function onload() {
        startLoading()
        openTab('tab1')
        await fillAddress()
        stopLoading()
    }

    onload()

</script>
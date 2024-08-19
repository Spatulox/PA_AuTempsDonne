<?php include("../includes/loadLang.php"); ?>

<!DOCTYPE html>
<html>
<head>

    <?php include("../includes/head.php"); ?>

    <title><?php echo($data["vehicle"]["title"]) ?></title>
</head>
<body>

<?php include("../includes/header.php"); ?>

<main>

    <div class="width80 marginAuto marginBottom30">
        <h1 class="textCenter"><?php echo($data["vehicle"]["title"]) ?></h1>

        <?php createDateField(); ?>

        <div class="tab flex flexAround nowrap">
            <button class="tablinks width100"
                    onclick="openTab('tab1')"><?php echo htmlspecialchars($data["vehicle"]["tab1"]["title"]) ?></button>
            <?php if($role <= 3): ?>
            <button class="tablinks width100"
                    onclick="openTab('tab2')"><?php echo htmlspecialchars($data["vehicle"]["tab2"]["title"]) ?></button>
            <button class="tablinks width100"
                    onclick="openTab('tab3')"><?php echo htmlspecialchars($data["vehicle"]["tab3"]["title"]) ?></button>
            <?php endif; ?>
            <?php if($role == 4): ?>
            <button class="tablinks width100"
                    onclick="openTab('tab4')"><?php echo htmlspecialchars($data["vehicle"]["tab4"]["title"]) ?></button>
            <?php endif; ?>
        </div>

        <!--Beneficiaire ne voient que ça pour réserver un véhicule-->
        <div id="tab1" class="tabcontent">
            <h2 class="textCenter"><?php echo($data["vehicle"]["tab1"]["title"]) ?></h2>
            <table>
                <thead>
                <tr>
                    <td><?php echo $data["vehicle"]["tab1"]["id"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["capacity"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["name"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["place"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["entrepot"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["button"] ?></td>
                </tr>
                </thead>
                <tbody id="bodyList">
                <!-- Les lignes de données seront insérées ici par JavaScript -->
                </tbody>
            </table>
        </div>

        <?php if($role <= 3): ?>
        <div id="tab2" class="tabcontent">
            <h2 class="textCenter"><?php echo($data["vehicle"]["tab2"]["title"]) ?></h2>
            <!--<input type="number" class="search-box marginBottom10" placeholder="Search by id">-->
            <p id="bodyDetail"><?php echo($data["vehicle"]["tab2"]["errorMsg"]) ?></p>
        </div>
        <?php endif; ?>

        <?php if($role <= 3): ?>
        <div id="tab3" class="tabcontent">
            <h2 class="textCenter"><?php echo($data["vehicle"]["tab3"]["title"]) ?></h2>
            <hr>
            <div class="textCenter">
                <input id="nameV" class="marginTop10 search-box" type="text"
                       placeholder="<?php echo($data["vehicle"]["tab1"]["name"]) ?>"><br>
                <input id="capacityV" class="marginTop10 search-box" type="number"
                       placeholder="<?php echo($data["vehicle"]["tab1"]["capacity"]) ?>"><br>
                <input id="placeV" class="marginTop10 search-box" type="number"
                       placeholder="<?php echo($data["vehicle"]["tab1"]["place"]) ?>"><br>
                <div id="leSelectAremplir" class="marginTop10 marginAuto search-box"><?php echo($data["vehicle"]["tab2"]["errorMsg"]) ?>, Impossible to select storehouse</div><br>
                <input class="marginTop30" type="button" onclick="addVehicle()"
                       value="<?php echo($data["vehicle"]["tab3"]["title"]) ?>">
            </div>
        </div>
        <?php endif; ?>

        <?php if($role == 4): ?>
            <div id="tab4" class="tabcontent">
                <h2 class="textCenter"><?php echo($data["vehicle"]["tab4"]["title"]) ?></h2>
                <!--<input type="number" class="search-box marginBottom10" placeholder="Search by id">-->
                <p id="bodyDetail"><?php echo($data["vehicle"]["tab4"]["errorMsg"]) ?></p>
            </div>
        <?php endif; ?>
    </div>

</main>
<?php include("../includes/footer.php"); ?>

</body>
</html>


<script type="text/javascript">

    async function fillEnterpotList() {
        const entrepot = new EntrepotAdmin()
        await entrepot.connect()
        let dataEtr = await entrepot.getEntrepot()

        let select = document.getElementById("leSelectAremplir")
        select.innerHTML = ""

        let option = []
        const debut = "Choose"

        for (const key in dataEtr) {

            option[key] = {
                "value": dataEtr[key].id_entrepot,
                "text": dataEtr[key].nom
            }

        }
        const newSelect = createSelect(option, debut)
        newSelect.classList.add("marginTop10")
        newSelect.classList.add("search-box")
        newSelect.id ="entrepotV"


        select.parentNode.replaceChild(newSelect, select)
    }

    async function seeDetail(id_vehicle = null) {

        if (id_vehicle == null) {
            const bodyDetail = document.getElementById("bodyDetail")
            bodyDetail.innerHTML = ""
            return
        }

        startLoading()
        let data = await vehicle.getVehicleById(id_vehicle)
        if (data.length === 0) {
            popup("This vehicle don't exist ??")
            startLoading()
            fillList()
            openTab('tab1')
            stopLoading()
            return
        }

        const html = formatVehicleToHTML(data[0])

        const bodyDetail = document.getElementById("bodyDetail")
        bodyDetail.innerHTML = ""
        bodyDetail.appendChild(html)
        openTab('tab2')
        stopLoading()
    }

    function formatVehicleToHTML(vehicleObject) {

        const vehicleCard = document.createElement('div');
        vehicleCard.classList.add("marginAuto")
        vehicleCard.classList.add("width50")
        vehicleCard.classList.add("border")

        const vehicleInfo = document.createElement('div');
        vehicleInfo.classList.add('vehicle-info');

        const idVehicle = document.createElement('p');
        idVehicle.innerHTML = '<strong>ID Véhicule:</strong> <span class="id-vehicule"></span>';
        idVehicle.querySelector('.id-vehicule').textContent = vehicleObject.id_vehicule;

        const nameVehicle = document.createElement('p');
        nameVehicle.innerHTML = '<strong>Nom du véhicule:</strong> <span class="nom-vehicule"></span>';
        nameVehicle.querySelector('.nom-vehicule').textContent = vehicleObject.nom_du_vehicules;

        const capacity = document.createElement('p');
        capacity.innerHTML = '<strong>Capacité:</strong> <span class="capacite"></span>';
        capacity.querySelector('.capacite').textContent = vehicleObject.capacite;

        const idWarehouse = document.createElement('p');
        idWarehouse.innerHTML = '<strong>ID Entrepôt:</strong> <span class="id-entrepot"></span>';
        idWarehouse.querySelector('.id-entrepot').textContent = vehicleObject.id_entrepot;

        const seats = document.createElement('p');
        seats.innerHTML = '<strong>Nombre de places:</strong> <span class="nombre-places"></span>';
        seats.querySelector('.nombre-places').textContent = vehicleObject.nombre_de_place;

        const button = createButton(vehicle.msg["Delete"])
        button.setAttribute("onclick", "deleteVehicle(" + vehicleObject.id_vehicule + ")")

        vehicleInfo.append(idVehicle, nameVehicle, capacity, idWarehouse, seats, button);
        vehicleCard.append(vehicleInfo);

        return vehicleCard;
    }

</script>
<?php if($role <= 3): ?>
<script type="text/javascript" defer>

    const vehicle = new VehicleAdmin()
    let dataVehicle = []

    async function fillList() {
        const bodyList = document.getElementById('bodyList')
        bodyList.innerHTML = ""
        dataVehicle = await vehicle.getAllVehicle()
        createBodyTableau(bodyList, dataVehicle, [], [vehicle.msg["See"]], ["seeDetail"], "id_vehicule")

    }

    /*async function fillEnterpotList() {
        const entrepot = new EntrepotAdmin()
        await entrepot.connect()
        let dataEtr = await entrepot.getEntrepot()

        let select = document.getElementById("leSelectAremplir")
        select.innerHTML = ""

        let option = []
        const debut = "Choose"

        for (const key in dataEtr) {

            option[key] = {
                "value": dataEtr[key].id_entrepot,
                "text": dataEtr[key].nom
            }

        }
        const newSelect = createSelect(option, debut)
        newSelect.classList.add("marginTop10")
        newSelect.classList.add("search-box")
        newSelect.id ="entrepotV"


        select.parentNode.replaceChild(newSelect, select)
    }*/

    /*async function seeDetail(id_vehicle = null) {

        if (id_vehicle == null) {
            const bodyDetail = document.getElementById("bodyDetail")
            bodyDetail.innerHTML = ""
            return
        }

        startLoading()
        let data = await vehicle.getVehicleById(id_vehicle)
        if (data.length === 0) {
            popup("This vehicle don't exist ??")
            startLoading()
            fillList()
            openTab('tab1')
            stopLoading()
            return
        }

        const html = formatVehicleToHTML(data[0])

        const bodyDetail = document.getElementById("bodyDetail")
        bodyDetail.innerHTML = ""
        bodyDetail.appendChild(html)
        openTab('tab2')
        stopLoading()
    }*/

    async function deleteVehicle(id_vehicle) {
        startLoading()
        await vehicle.deleteVehicle(id_vehicle)
        fillList()
        seeDetail()
        openTab("tab1")
        stopLoading()
    }

    async function addVehicle() {
        startLoading()
        let namev = document.getElementById("nameV")
        let placev = document.getElementById("placeV")
        let capacityv = document.getElementById("capacityV")
        let entrepotv = document.getElementById("entrepotV")

        if (namev == null || placev == null || capacityv == null) {
            stopLoading()
            popup("Error")
            return
        }

        namev = namev.value
        placev = placev.value
        capacityv = capacityv.value
        entrepotv = entrepotv.value

        if(entrepotv === "Choose"){
            popup("Vous devez choisir un entrepot")
            stopLoading()
            return
        }

        const data = {
            "capacite": capacityv,
            "nom_du_vehicules": namev,
            "nombre_de_place": placev,
            "id_entrepot": entrepotv
        }

        await vehicle.createVehicle(data)
        await fillList()
        stopLoading()

    }

    /*function formatVehicleToHTML(vehicleObject) {

        const vehicleCard = document.createElement('div');
        vehicleCard.classList.add("marginAuto")
        vehicleCard.classList.add("width50")
        vehicleCard.classList.add("border")

        const vehicleInfo = document.createElement('div');
        vehicleInfo.classList.add('vehicle-info');

        const idVehicle = document.createElement('p');
        idVehicle.innerHTML = '<strong>ID Véhicule:</strong> <span class="id-vehicule"></span>';
        idVehicle.querySelector('.id-vehicule').textContent = vehicleObject.id_vehicule;

        const nameVehicle = document.createElement('p');
        nameVehicle.innerHTML = '<strong>Nom du véhicule:</strong> <span class="nom-vehicule"></span>';
        nameVehicle.querySelector('.nom-vehicule').textContent = vehicleObject.nom_du_vehicules;

        const capacity = document.createElement('p');
        capacity.innerHTML = '<strong>Capacité:</strong> <span class="capacite"></span>';
        capacity.querySelector('.capacite').textContent = vehicleObject.capacite;

        const idWarehouse = document.createElement('p');
        idWarehouse.innerHTML = '<strong>ID Entrepôt:</strong> <span class="id-entrepot"></span>';
        idWarehouse.querySelector('.id-entrepot').textContent = vehicleObject.id_entrepot;

        const seats = document.createElement('p');
        seats.innerHTML = '<strong>Nombre de places:</strong> <span class="nombre-places"></span>';
        seats.querySelector('.nombre-places').textContent = vehicleObject.nombre_de_place;

        const button = createButton(vehicle.msg["Delete"])
        button.setAttribute("onclick", "deleteVehicle(" + vehicleObject.id_vehicule + ")")

        vehicleInfo.append(idVehicle, nameVehicle, capacity, idWarehouse, seats, button);
        vehicleCard.append(vehicleInfo);

        return vehicleCard;
    }*/


    async function onload() {
        startLoading()
        openTab('tab1')

        await fillEnterpotList()
        await vehicle.connect()
        fillList()

        stopLoading()
    }

    onload()

</script>
<?php endif; ?>

<?php if($role == 4): ?>
    <script type="text/javascript" defer>

        const vehicle = new VehicleAdmin()
        let dataVehicle = []

        async function fillListAvailable() {
            const bodyList = document.getElementById('bodyList')
            bodyList.innerHTML = ""

            const today = new Date()
            const demain = new Date
            demain.setDate(today.getDate() + 1)

            dataVehicle = await vehicle.getAvailableVehicle(today.toISOString().split('T')[0], demain.toISOString().split('T')[0])
            console.log(dataVehicle)
            createBodyTableau(bodyList, dataVehicle, [], [vehicle.msg["See"]], ["seeDetail"], "id_vehicule")

        }

        async function onload() {
            startLoading()
            openTab('tab1')

            //await fillEnterpotList()
            await vehicle.connect()
            fillListAvailable()

            stopLoading()
        }

        onload()
    </script>
<?php endif; ?>

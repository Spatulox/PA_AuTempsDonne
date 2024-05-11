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

        <div class="tab flex flexAround nowrap">
            <button class="tablinks width100"
                    onclick="openTab('tab1')"><?php echo($data["vehicle"]["tab1"]["title"]) ?></button>
            <button class="tablinks width100"
                    onclick="openTab('tab2')"><?php echo($data["vehicle"]["tab2"]["title"]) ?></button>
            <button class="tablinks width100"
                    onclick="openTab('tab3')"><?php echo($data["vehicle"]["tab3"]["title"]) ?></button>
        </div>

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

        <div id="tab2" class="tabcontent">
            <h2 class="textCenter"><?php echo($data["vehicle"]["tab2"]["title"]) ?></h2>
            <!--<input type="number" class="search-box marginBottom10" placeholder="Search by id">-->
            <p id="bodyDetail"><?php echo($data["vehicle"]["tab2"]["errorMsg"]) ?></p>
        </div>

        <div id="tab3" class="tabcontent">
            <h2 class="textCenter"><?php echo($data["vehicle"]["tab3"]["title"]) ?></h2>
            <table>
                <thead>
                <tr>
                    <td><?php echo $data["vehicle"]["tab1"]["id"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["desc"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["date"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["state"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["type"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["participateUsers"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["button"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["buttonD"] ?></td>
                </tr>
                </thead>
                <tbody id="">
                <?php echo($data["vehicle"]["tab3"]["errorMsg"]) ?>
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

    const vehicle = new VehicleAdmin()
    let dataVehicle = []

    async function fillList() {
        const bodyList = document.getElementById('bodyList')
        bodyList.innerHTML = ""
        dataVehicle = await vehicle.getAllVehicle()
        console.log(dataVehicle)
        createBodyTableau(bodyList, dataVehicle, [], [vehicle.msg["See"]], ["seeDetail"], "id_vehicule")

    }

    async function seeDetail(id_vehicle) {
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

        vehicleInfo.append(idVehicle, nameVehicle, capacity, idWarehouse, seats);
        vehicleCard.append(vehicleInfo);

        return vehicleCard;
    }


    async function onload() {
        startLoading()
        openTab('tab1')

        await vehicle.connect()
        fillList()

        stopLoading()
    }

    onload()

</script>
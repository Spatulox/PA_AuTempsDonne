<?php include("../includes/loadLang.php");?>

<!DOCTYPE html>
<html>
<head>

    <?php include("../includes/head.php"); ?>

    <title><?php echo($data["gestPlanning"]["title"]) ?></title>
</head>
<body>

<?php include("../includes/header.php");?>

<main>

    <div id="popupGestion" class="popupGestion flexAround nowrap">
        <div class="popupGestion-content">
            <span class="close-button">&times;</span>
            <h2 class="underline"><?php echo($data["gestPlanning"]["tab1"]["title"]) ?></h2>
            <p id="popupGestionTitle">Des trucs</p>
            <hr>
            <h4><?php echo($data["gestPlanning"]["tab1"]["participateUsers"]) ?> :</h4>
            <p id="popupGestionBody">

            </p>

            <div class="flex flexAround">
                <button type="button" onclick="addUserPopup()"><?php echo($data["gestPlanning"]["addUser"]) ?></button>
                <button type="button"><?php echo($data["gestPlanning"]["assign"]) ?></button>
            </div>
        </div>
        <div class="popupGestion-content">
            <span class="close-button">&times;</span>
            <ul>Coucou</ul>
        </div>
    </div>

    <div class="width80 marginAuto marginBottom30">
        <h1 class="textCenter"><?php echo($data["gestPlanning"]["title"]) ?></h1>

        <div class="tab flex flexAround nowrap">
            <button class="tablinks width100" onclick="openTab('tab1')"><?php echo($data["gestPlanning"]["tab1"]["title"]) ?></button>
            <button class="tablinks width100" onclick="openTab('tab2')"><?php echo($data["gestPlanning"]["tab2"]["title"]) ?></button>
            <button class="tablinks width100" onclick="openTab('tab3')"><?php echo($data["gestPlanning"]["tab3"]["title"]) ?></button>
        </div>

        <div id="tab1" class="tabcontent">
            <h3><?php echo($data["gestPlanning"]["tab1"]["title"]) ?></h3>
            <table>
                <thead>
                <tr>
                    <td>ID_Planning</td>
                    <td><?php echo $data["gestPlanning"]["tab1"]["desc"] ?></td>
                    <td><?php echo $data["gestPlanning"]["tab1"]["date"] ?></td>
                    <td><?php echo $data["gestPlanning"]["tab1"]["state"] ?></td>
                    <td><?php echo $data["gestPlanning"]["tab1"]["type"] ?></td>
                    <td><?php echo $data["gestPlanning"]["tab1"]["participateUsers"] ?></td>
                    <td><?php echo $data["gestPlanning"]["tab1"]["button"] ?></td>
                </tr>
                </thead>
                <tbody id="bodyAssign">
                <!-- Les lignes de données seront insérées ici par JavaScript -->
                </tbody>
            </table>
        </div>

        <div id="tab2" class="tabcontent">
            <h3><?php echo($data["gestPlanning"]["tab2"]["title"]) ?></h3>
            <table>
                <thead>
                <tr>
                    <td>ID_Planning</td>
                    <td><?php echo $data["gestPlanning"]["tab1"]["desc"] ?></td>
                    <td><?php echo $data["gestPlanning"]["tab1"]["date"] ?></td>
                    <td><?php echo $data["gestPlanning"]["tab1"]["state"] ?></td>
                    <td><?php echo $data["gestPlanning"]["tab1"]["type"] ?></td>
                    <td><?php echo $data["gestPlanning"]["tab1"]["participateUsers"] ?></td>
                </tr>
                </thead>
                <tbody id="bodyDaily">
                <!-- Les lignes de données seront insérées ici par JavaScript -->
                </tbody>
            </table>
        </div>

        <div id="tab3" class="tabcontent">
            <h3><?php echo($data["gestPlanning"]["tab3"]["title"]) ?></h3>
            <table>
                <thead>
                <tr>
                    <td>ID_Planning</td>
                    <td><?php echo $data["gestPlanning"]["tab1"]["desc"] ?></td>
                    <td><?php echo $data["gestPlanning"]["tab1"]["date"] ?></td>
                    <td><?php echo $data["gestPlanning"]["tab1"]["state"] ?></td>
                    <td><?php echo $data["gestPlanning"]["tab1"]["type"] ?></td>
                    <td><?php echo $data["gestPlanning"]["tab1"]["participateUsers"] ?></td>
                    <td><?php echo $data["gestPlanning"]["tab1"]["button"] ?></td>
                    <td><?php echo $data["gestPlanning"]["tab1"]["buttonD"] ?></td>
                </tr>
                </thead>
                <tbody id="bodyWaiting">
                <!-- Les lignes de données seront insérées ici par JavaScript -->
                </tbody>
            </table>
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

    if (tab && idExistInPage("tab" + tab)) {
        openTab('tab' + tab)
    } else {
        //openTab('tab3')
        openTab('tab1')
    }
</script>

<script>
    const closeButton = document.getElementsByClassName("close-button")[0];
    const closeButton2 = document.getElementsByClassName("close-button")[1];

    closeButton.addEventListener("click", () => {
        const popup = document.getElementById("popupGestion");
        popup.style.display = "none";
    });

    closeButton2.addEventListener("click", () => {
        const popup = document.getElementById("popupGestion");
        popup.style.display = "none";
    });
</script>

<script type="text/javascript" defer>

    const planning = new PlanningAdmin()

    let validatePlanningData = {}

    let dailyPlanning = {}

    async function addUserPopup() {
        const fieldMain = document.getElementById("popupGestionBody");

        // Créer le conteneur de l'élément
        const elementContainer = document.createElement("div");
        elementContainer.classList.add("element-container");

        // Créer l'élément
        const element = document.createElement("span");
        element.classList.add("element");
        element.textContent = "Entrez une valeur";

        // Créer la coche
        const checkmark = document.createElement("span");
        checkmark.classList.add("checkmark");
        checkmark.innerHTML = "&times;"; // Code Unicode pour la coche
        checkmark.classList.add("pointer");

        // Ajouter un écouteur d'événement pour supprimer l'élément
        checkmark.addEventListener("click", () => {
            elementContainer.remove();
        });

        // Ajouter l'élément et la coche au conteneur
        elementContainer.appendChild(element);
        elementContainer.appendChild(checkmark);

        // Ajouter le conteneur au champ principal
        fieldMain.appendChild(elementContainer);
    }



    async function assignPlanning(id){

        const popup = document.getElementById("popupGestion");
        startLoading()

        let data = validatePlanningData
        data.forEach(item => {
            if(item.id_planning == id){
                data = item
                return;
            }
        })

        console.log(data)

        const popupTitle = document.getElementById("popupGestionTitle")
        popupTitle.innerHTML = data.description + " - " + data.date_activite
        document.getElementById("popupGestionBody").innerHTML = ""
        //addUserPopup()


        replaceCharacters()
        stopLoading()
        popup.style.display = "flex";
    }

    async function fillWaitingPlanning(){
        const tbody = document.getElementById("bodyWaiting")
        tbody.innerHTML = ""
        const waitingPlanning = await planning.getWaitPlanning()
        createBodyTableau(tbody,waitingPlanning, ["id_index_planning", "id_activite"], [planning.msg["Validate"], planning.msg["Delete"]], ["validatePlanning", "deletePlanning"], "id_planning")
        replaceCharacters()
    }

    async function fillAssignPlanning(){
        const tbody = document.getElementById("bodyAssign")
        tbody.innerHTML = ""
        validatePlanningData = await planning.getNoAffectPlanning()
        createBodyTableau(tbody, validatePlanningData, ["id_index_planning", "id_activite"], [planning.msg["Assign"]], ["assignPlanning"], "id_planning")
        replaceCharacters()
    }

    async function fillDailyPlanning(){

        const today = new Date();
        const year = today.getFullYear();
        let month = today.getMonth() + 1; // Les mois sont indexés à partir de 0
        let day = today.getDate();

        if(month < 10){
            month = "0"+month
        }
        if(day < 10){
            day = "0"+day
        }
        const formattedDate = `${year}-${month}-${day}`;

        const tbody = document.getElementById("bodyDaily")
        tbody.innerHTML = ""
        const dailyPlanning = await planning.getAffectByDatePlanning(formattedDate)

        createBodyTableau(tbody, dailyPlanning, ["id_index_planning", "id_activite"])
        replaceCharacters()
    }

    async function validatePlanning(id){
        startLoading()
        await planning.validatePlanning(id)
        fillWaitingPlanning()
        stopLoading()
    }

    async function onload() {
        startLoading()
        await planning.connect()

        fillAssignPlanning()
        fillDailyPlanning()
        fillWaitingPlanning()

        stopLoading()
    }

    onload()
</script>
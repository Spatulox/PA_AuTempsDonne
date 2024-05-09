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
            <h4 id="h4"><?php echo($data["gestPlanning"]["tab1"]["participateUsers"]) ?> : 0</h4>
            <p id="popupGestionBody">

            </p>
            <p id="select"></p>
            <div class="flex flexAround nowrap">
                <button type="button" onclick="addUserPopup()"><?php echo($data["gestPlanning"]["addUser"]) ?></button>
                <button type="button" onclick="assignUser()"><?php echo($data["gestPlanning"]["assign"]) ?></button>
            </div>
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
            <input id="inputDate" type="date" class="marginBottom20 search-box" oninput="searchDaily()">
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

    closeButton.addEventListener("click", () => {
        const popup = document.getElementById("popupGestion");
        popup.style.display = "none";
    });

</script>

<script type="text/javascript" defer>

    const planning = new PlanningAdmin()
    const user = new UserAdmin()

    let idPlanningToSend = 0

    let users = []

    let emailToAssign = []

    let validatePlanningData = {}

    let dailyPlanning = {}

    async function addUserPopup() {

        const fieldMain = document.getElementById("popupGestionBody");

        // Créer le conteneur de l'élément
        const elementContainer = document.createElement("div");
        elementContainer.classList.add("element-container");

        const idSelectValue = document.getElementById("idSelect").value

        if(idSelectValue === "Choisisser une addresse email"){
            return
        }


        if(emailToAssign.includes(idSelectValue)){
            popup("Vous ne pouvez pas attribuer une même activité au même utilisateur plusieurs fois :/")
            return
        }
        emailToAssign.push(idSelectValue)

        let header = document.getElementById("h4").innerHTML
        header = header.split(" : ")
        header[1] = +(header[1].trim())
        header[1] += 1
        header = header.join(" : ")
        document.getElementById("h4").innerHTML = header

        // Créer l'élément
        const element = document.createElement("span");
        element.classList.add("element");
        element.textContent = idSelectValue;

        // Créer la coche
        const checkmark = document.createElement("span");
        checkmark.classList.add("checkmark");
        checkmark.innerHTML = "&times;"; // Code Unicode pour la coche
        checkmark.classList.add("pointer");

        // Ajouter un écouteur d'événement pour supprimer l'élément
        checkmark.addEventListener("click", () => {
            const index = emailToAssign.indexOf(idSelectValue);
            if (index !== -1) {
                emailToAssign.splice(index, 1);
            }
            elementContainer.remove();

            let header = document.getElementById("h4").innerHTML
            header = header.split(" : ")
            header[1] = +(header[1].trim())
            header[1] -= 1
            header = header.join(" : ")
            document.getElementById("h4").innerHTML = header

        });

        // Ajouter l'élément et la coche au conteneur
        elementContainer.appendChild(element);
        elementContainer.appendChild(checkmark);

        // Ajouter le conteneur au champ principal
        fieldMain.appendChild(elementContainer);
    }

    async function assignUser(){

        if(emailToAssign.length === 0){
            popup("Vous devez assigner au moins un utilisateur")
            return
        }
        //Requête API

        if(idPlanningToSend === 0){
            popup("Erreur lors de la sélection du planning, veuillez rafraichir la page")
            return
        }

        startLoading()
        for (const em in emailToAssign) {
            leEmail = emailToAssign[em]

            idUser = await user.getUserViaEmail(leEmail)

            await planning.userJoinPlanning(idUser.id_user, idPlanningToSend)

        }
        stopLoading()

        idPlanningToSend = 0

        let header = document.getElementById("h4").innerHTML
        header = header.split(" : ")
        header[1] = +(header[1].trim())
        header[1] = 0
        header = header.join(" : ")
        document.getElementById("h4").innerHTML = header

        emailToAssign = []
        const popupG = document.getElementById("popupGestion");
        popupG.style.display = "none";

        startLoading()
        await fillAssignPlanning()
        await fillDailyPlanning()
        stopLoading()

    }


    async function assignPlanning(id){

        emailToAssign = []

        startLoading()

        idPlanningToSend = id

        let header = document.getElementById("h4").innerHTML
        header = header.split(" : ")
        header[1] = +(header[1].trim())
        header[1] = 0
        header = header.join(" : ")
        document.getElementById("h4").innerHTML = header


        const popupG = document.getElementById("popupGestion");
        let data = validatePlanningData
        data.forEach(item => {
            if(item.id_planning == id){
                data = item
                return;
            }
        })

        const popupTitle = document.getElementById("popupGestionTitle")
        popupTitle.innerHTML = data.description + " - " + data.date_activite
        popupBody = document.getElementById("popupGestionBody")
        popupBody.innerHTML = ""

        await updateListUser(data.date_activite)


        replaceCharacters()
        stopLoading()
        popupG.style.display = "flex";
    }

    async function fillWaitingPlanning(){
        const tbody = document.getElementById("bodyWaiting")
        tbody.innerHTML = ""
        const waitingPlanning = await planning.getWaitPlanning()

        if(waitingPlanning.length > 0) {
            createBodyTableau(tbody, waitingPlanning, ["id_index_planning", "id_activite"], [planning.msg["Validate"], planning.msg["Delete"]], ["validatePlanning", "deletePlanning"], "id_planning")
            replaceCharacters()
        }
    }

    async function fillAssignPlanning(){
        const tbody = document.getElementById("bodyAssign")
        tbody.innerHTML = ""
        validatePlanningData = await planning.getNoAffectPlanning()

        if(validatePlanningData.length > 0) {
            createBodyTableau(tbody, validatePlanningData, ["id_index_planning", "id_activite"], [planning.msg["Assign"]], ["assignPlanning"], "id_planning")
            replaceCharacters()
        }
    }

    async function searchDaily(){
        let dateStr = document.getElementById("inputDate").value
        dateStr = dateStr.split("T")
        fillDailyPlanning(dateStr)
    }

    async function fillDailyPlanning(dateStr = null){

        let formattedDate = ""
        if(dateStr == null){
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
            formattedDate = `${year}-${month}-${day}`;
        } else {
            formattedDate = dateStr
        }

        const tbody = document.getElementById("bodyDaily")
        tbody.innerHTML = ""
        const dailyPlanning = await planning.getAffectByDatePlanning(formattedDate)
        console.log(formattedDate)
        console.log(dailyPlanning)

        if(dailyPlanning.length > 0){
            createBodyTableau(tbody, dailyPlanning, ["id_index_planning", "id_activite"])
        }
        replaceCharacters()
    }

    async function validatePlanning(id){
        startLoading()
        await planning.validatePlanning(id)
        fillWaitingPlanning()
        stopLoading()
    }

    async function updateListUser(dateStr){
        const date = new Date(dateStr);
        const jourSemaine = date.getDay();
        users = await user.getUserDispoByDay(jourSemaine+1)

        const selectBody = document.getElementById("select")
        selectBody.innerHTML = ""

        let options = []

        let i = 0
        for (const key in users) {
            options[i] ={
                "value": users[key].email,
                "text": users[key].email
            }
            i++
        }

        const select = createSelect(options, "Choisisser une addresse email")
        select.id = "idSelect"
        selectBody.appendChild(select)

    }

    async function onload() {
        startLoading()
        await planning.connect()
        await user.connect()

        document.getElementById("inputDate").value = ""


        fillAssignPlanning()
        fillDailyPlanning()
        fillWaitingPlanning()

        stopLoading()
    }

    onload()
</script>
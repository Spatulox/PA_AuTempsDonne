<?php include("../includes/loadLang.php"); ?>

<!DOCTYPE html>
<html>
<head>

    <?php include("../includes/head.php"); ?>

    <title><?php echo($data["gestDemande"]["title"]) ?></title>
</head>
<body>

<?php include("../includes/header.php"); ?>

<main>

    <div id="popupGestion" class="popupGestion flexAround nowrap">
        <div class="popupGestion-content">
            <span class="close-button">&times;</span>
            <h2 class="underline"><?php echo($data["gestDemande"]["Create Trajet"]) ?></h2>
            <p id="popupGestionTitle"></p>
            <hr>
            <h4 id="h4"><?php echo($data["gestDemande"]["Location to pass"]) ?> : 0</h4>
            <p id="select1"></p>
            <p id="popupGestionBody"></p>
            <p id="select2"></p>
            <p id="select3"></p>
            <input type="datetime-local" id="dateSelect">
            <div class="flex flexAround nowrap">
                <button type="button" onclick="addAddressPopup()"><?php echo($data["gestDemande"]["addRequest"]) ?></button>
                <button type="button" onclick="valideData()"><?php echo($data["gestDemande"]["create"]) ?></button>
            </div>
        </div>
    </div>

    <div class="width90 marginAuto marginBottom30">
        <h1 class="textCenter"><?php echo($data["gestDemande"]["title"]) ?></h1>

        <div class="tab flex flexAround nowrap">
            <button class="tablinks width100"
                    onclick="openTab('tab1')"><?php echo($data["gestDemande"]["tab1"]["title"]) ?></button>
            <button class="tablinks width100"
                    onclick="openTab('tab2')"><?php echo($data["gestDemande"]["tab2"]["title"]) ?></button>
            <button class="tablinks width100"
                    onclick="openTab('tab3')"><?php echo($data["gestDemande"]["tab3"]["title"]) ?></button>
        </div>

        <div id="tab1" class="tabcontent">
            <h2><?php echo($data["gestDemande"]["tab1"]["title"]) ?></h2>
            <table>
                <thead>
                <tr>
                    <td><?php echo $data["gestDemande"]["tab1"]["id"] ?></td>
                    <td><?php echo $data["gestDemande"]["tab1"]["desc"] ?></td>
                    <td><?php echo $data["gestDemande"]["tab1"]["type"] ?></td>
                    <td><?php echo $data["gestDemande"]["tab1"]["state"] ?></td>
                    <td><?php echo $data["gestDemande"]["tab1"]["date"] ?></td>
                    <td><?php echo $data["gestDemande"]["tab1"]["activite"] ?></td>
                    <td><?php echo $data["gestDemande"]["tab1"]["id_planning"] ?></td>
                    <td><?php echo $data["gestDemande"]["tab1"]["user"] ?></td>
                    <td><?php echo $data["gestDemande"]["tab1"]["?"] ?></td>
                    <td><?php echo $data["gestDemande"]["tab1"]["button"] ?></td>
                </tr>
                </thead>
                <tbody id="bodyList">
                <!-- Les lignes de données seront insérées ici par JavaScript -->
                </tbody>
            </table>
        </div>

        <div id="tab2" class="tabcontent">
            <h2><?php echo($data["gestDemande"]["tab2"]["title"]) ?></h2>
            <p id="bodyMeh">
            </p>
        </div>

        <div id="tab3" class="tabcontent">
            <h2><?php echo($data["gestDemande"]["tab3"]["title"]) ?></h2>
            <table>
                <thead>
                <tr>
                    <td><?php echo $data["gestDemande"]["tab1"]["id"] ?></td>
                    <td><?php echo $data["gestDemande"]["tab1"]["desc"] ?></td>
                    <td><?php echo $data["gestDemande"]["tab1"]["type"] ?></td>
                    <td><?php echo $data["gestDemande"]["tab1"]["state"] ?></td>
                    <td><?php echo $data["gestDemande"]["tab1"]["date"] ?></td>
                    <td><?php echo $data["gestDemande"]["tab1"]["activite"] ?></td>
                    <td><?php echo $data["gestDemande"]["tab1"]["id_planning"] ?></td>
                    <td><?php echo $data["gestDemande"]["tab1"]["user"] ?></td>
                    <td><?php echo $data["gestDemande"]["tab1"]["?"] ?></td>
                    <td><?php echo $data["gestDemande"]["tab1"]["button"] ?></td>
                </tr>
                </thead>
                <tbody id="bodyListWait">
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
    const closeButton = document.getElementsByClassName("close-button")[0];

    closeButton.addEventListener("click", () => {
        const popup = document.getElementById("popupGestion");
        popup.style.display = "none";
    });

</script>

<script type="text/javascript" defer>

    const request = new DemandeAdmin()
    const user = new UserAdmin()
    const activity = new ActiviteAdmin()
    let email = []
    let demande = []
    let acti_desc = []

    let lesDataToSendGroup = {
        "id_demande":[],
        "id_depart":-1,
        "id_arriver":-1,
        "date": "1970-01-01 00:00:00"
    }

    async function fillListDemande() {
        const bodyList = document.getElementById("bodyList")
        bodyList.innerHTML = ""

        demande = await request.getAllDemande()
        await formateMainData()

        let lesData = []

        for (const key in demande) {

            if (demande[key].etat === "Validé") {
                lesData[key] = demande[key]
            }

        }

        createBodyTableau(bodyList, lesData, [], [user.msg["Details"]], ["seeDetails"], "id_demande")
        await fillListDemandeWait()
        replaceCharacters()
    }

    async function fillListDemandeWait(popup = false) {
        let bodyList

        if(popup === false){
            bodyList = document.getElementById("bodyListWait")
        } else {
            bodyList = document.getElementById("popupAsk")
        }

        bodyList.innerHTML = ""

        let lesData = []

        for (const key in demande) {

            if (demande[key].etat === "En Attente") {
                lesData[key] = demande[key]
            }

        }

        //createBodyTableau(bodyList, lesData, [], [user.msg["Validate"]], ["validate"], "id_demande")
        createBodyTableau(bodyList, lesData, [], [user.msg["Details"]], ["seeDetails"], "id_demande")
        replaceCharacters()
    }

    async function formateData() {

        // Get all the user to associate the email to the id user
        email = await user.getAllUser()
        let tmp = {}
        for (const key in email) {
            tmp[email[key].id_user] = email[key].email
        }
        email = []
        email = tmp

        // Get all the activite to associate the desc activite to the id activite

        acti_desc = await activity.getAllActivite()
        tmp = {}
        for (const key in acti_desc) {
            tmp[acti_desc[key].id_activite] = acti_desc[key].nom_activite
        }
        acti_desc = []
        acti_desc = tmp
    }

    async function formateMainData() {

        const etat = {
            "1": "En Attente",
            "0": "Validé",
            "-1": "Terminé"
        }

        for (const key in demande) {

            demande[key].etat = etat[demande[key].etat]
            demande[key].id_user = email[demande[key].id_user]
            demande[key].id_activite = acti_desc[demande[key].id_activite]
        }

    }

    async function formateOneData(data, showButton = null) {

        const output = document.getElementById('bodyMeh');

        output.innerHTML = ""

        const card = document.createElement('div');
        card.classList.add('card');

        const title = document.createElement('h2');
        title.textContent = `Demande #${data.id_demande}`;
        card.appendChild(title);

        const description = document.createElement('p');
        description.textContent = `Description: ${data.desc_demande}`;
        card.appendChild(description);

        const activity = document.createElement('p');
        activity.textContent = `Activité: ${data.activite}`;
        activity.id = "detailActivite"
        card.appendChild(activity);

        const activityId = document.createElement('p');
        activityId.textContent = `ID Activité: ${data.id_activite}`;
        card.appendChild(activityId);

        const status = document.createElement('p');
        status.textContent = `État: ${data.etat}`;
        card.appendChild(status);

        const user2 = document.createElement('p');
        user2.textContent = `Utilisateur: ${data.id_user}`;
        card.appendChild(user2);

        output.appendChild(card);

        if (data.etat == "En Attente") {

            let button = createButton(request.msg["Validate"])
            button.setAttribute("onclick", "validateRequest(" + data.id_demande + ")")
            button.classList.add("marginTop20")
            button.classList.add("marginRight20")
            card.appendChild(button)


            button = createButton(request.msg["Delete"])
            button.setAttribute("onclick", "deleteRequest(" + data.id_demande + ")")
            button.classList.add("marginTop20")
            card.appendChild(button)

            output.appendChild(card);

        }

        output.classList.add("width50")
        output.classList.add("marginAuto")

    }

    async function validateRequest(id_demande) {
        startLoading()

        const detailActivite = document.getElementById("detailActivite").innerHTML

        if(detailActivite.split(": ")[1].trim() === "groupe"){

            await requestData()
            stopLoading()

        } else if (detailActivite.split(": ")[1].trim() === "seul"){
            await request.validateSoloDemande(id_demande)
            await reload()
            stopLoading()
        }
    }

    function emptyDetail(){
        const bodyMeh = document.getElementById("bodyMeh")
        bodyMeh.innerHTML = ""
    }

    async function requestData(){

        const lapopup = document.getElementById("popupGestion")
        const popupGestionBody = document.getElementById("popupGestionBody")
        popupGestionBody.innerHTML = ""

        const button = createButton("Valide Data")
        button.setAttribute("onclick", "valideData()")

        const idselect = document.getElementById("select1")
        idselect.innerHTML = ""

        const idselect2 = document.getElementById("select2")
        idselect2.innerHTML = ""

        const idselect3 = document.getElementById("select3")
        idselect3.innerHTML = ""

        const entrepot = new EntrepotAdmin()
        await entrepot.connect()

        let entre = await entrepot.getEntrepot()

        let option = []
        const debut = "Choose Storehouse"

        for (const key in entre) {

            option[key] = {
                "value": entre[key].id_entrepot,
                "text": entre[key].nom
            }

        }

        let h1 = document.createElement("h4")
        h1.innerHTML = "Choose start Storehouse"
        h1.classList.add("textCenter")
        h1.classList.add("underline")
        idselect.appendChild(h1)

        let select1 = createSelect(option, debut)
        idselect.appendChild(select1)

        h1 = document.createElement("h4")
        h1.innerHTML = "Choose Requests to link"
        h1.classList.add("textCenter")
        h1.classList.add("underline")
        popupGestionBody.appendChild(h1)


        let lesData = []

        for (const key in demande) {

            if (demande[key].etat === "En Attente") {
                lesData[key] = demande[key]
            }

        }


        options = []

        for (const key in lesData) {

            options[key] = {
                "value": lesData[key].id_demande,
                "text": lesData[key].desc_demande + " : " + lesData[key].id_demande
            }

        }


        let select3 = createSelect(options, "Choose Request to link")
        idselect2.appendChild(select3)


        h1 = document.createElement("h4")
        h1.innerHTML = "Choose end Storehouse"
        h1.classList.add("textCenter")
        h1.classList.add("underline")
        idselect3.appendChild(h1)

        let select2 = createSelect(option, debut)
        idselect3.appendChild(select2)

        lapopup.style.display = "flex"


    }

    async function addAddressPopup(){

        const popupGestionBody = document.getElementById("popupGestionBody")

        const selectValue = document.querySelector("#select2 > select")

        const start = document.querySelector("#select3 > select")

        const end = document.querySelector("#select3 > select")

        if(start.value.trim() === "Choose Storehouse" || end.value.trim() === "Choose Storehouse"){
            popup("Vous devez sélectionner un entrepot de départ et de fin avant")
            return
        }

        if(selectValue.value.trim() === "Choose Request to link"){
            popup("Vous devez sélectionner une demande au minimum pour la lier")
            return
        }


        const selectElement = document.querySelector("#select2 > select"); // Sélectionne l'élément <select>
        const selectedOption = selectElement.options[selectElement.selectedIndex]; // Récupère l'option sélectionnée
        const selectedInnerHTML = selectedOption.innerHTML;
        console.log(selectedInnerHTML)

        if(popupGestionBody.innerHTML.includes(selectedInnerHTML)){
            popup("Vous ne pouvez pas associer plusieurs fois la même demande dans un trajet")
            return
        }

        lesDataToSendGroup.id_depart = start.value
        lesDataToSendGroup.id_arriver = end.value
        lesDataToSendGroup.id_demande.push(selectValue.value)
        popupGestionBody.innerHTML += selectedInnerHTML +"<br>"
    }

    async function valideData(){
        startLoading()

        const dateSelect = document.getElementById("dateSelect")
        lesDataToSendGroup.date = dateSelect.value

        if( lesDataToSendGroup["id_demande"].length === 0){
            popup('Vous devez mettre des données avant de valider')
            stopLoading()
            return
        }

        if(lesDataToSendGroup.id_demande.length === 0){
            popup("Error, wrong request length")
            stopLoading()
            return
        }

        if(lesDataToSendGroup.id_depart === -1 || lesDataToSendGroup.id_arriver === -1){
            popup("Error, wrong start or end")
            stopLoading()
            return
        }

        if(lesDataToSendGroup.date === "1970-01-01 00:00:00"){
            popup("Error, wrong date")
            stopLoading()
            return
        }
        console.log(lesDataToSendGroup)
        await request.validateGroupDemande(lesDataToSendGroup)
        lesDataToSendGroup = {
            "id_demande":[],
            "id_depart":-1,
            "id_arriver":-1,
            "date": "1970-01-01 00:00:00"
        }

        const popup = document.getElementById("popupGestion");
        popup.style.display = "none";

        await reload()

        stopLoading()
    }

    async function deleteRequest(id_demande) {
        startLoading()
        await request.deleteDemande(id_demande)
        await reload()
        stopLoading()
    }

    async function seeDetails(id) {
        startLoading()
        const objectWithId = demande.find(obj => obj.id_demande == id);
        await formateOneData(objectWithId)
        replaceCharacters()
        openTab('tab2')
        stopLoading()
    }

    async function reload() {
        startLoading()
        demande = await request.getAllDemande()
        await formateMainData()
        await fillListDemande()
        emptyDetail()
        openTab('tab1')
        stopLoading()
    }

    async function onload() {
        startLoading()
        openTab('tab1')
        await request.connect()
        await user.connect()
        await activity.connect()

        await formateData()

        await fillListDemande()
        stopLoading()
    }

    onload()

</script>

<style>
    .card {
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 10px;
    }

    .card h2 {
        margin-top: 0;
    }

    .card p {
        margin: 5px 0;
    }
</style>
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
            <input type="datetime-local" id="dateSelectDebut">
            <input type="datetime-local" id="dateSelectEnd">
            <p id="select4"></p>
            <div class="flex flexAround nowrap marginTop20">
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
    const activity = new Activite()
    const vehicle = new VehicleAdmin()
    let email = []
    let demande = []
    let acti_desc = []

    let lesDataToSendGroup = {
        "id_demande":[],
        "id_depart":-1,
        "id_arriver":-1,
        "date": "1970-01-01 00:00:00",
        "id_vehicle": null
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

            if (demande[key].etat === "En Attente" && demande[key].activite === "groupe") {
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

        const dateSelectDebut = document.getElementById("dateSelectDebut");
        const dateSelectFin = document.getElementById("dateSelectEnd");
        if(dateSelectDebut.value && dateSelectFin.value){
            selectAvailableVehicle()
        }
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

        if(popupGestionBody.innerHTML.includes(selectedInnerHTML)){
            popup("Vous ne pouvez pas associer plusieurs fois la même demande dans un trajet")
            return
        }

        lesDataToSendGroup.id_depart = start.value
        lesDataToSendGroup.id_arriver = end.value
        lesDataToSendGroup.id_demande.push(selectValue.value)
        popupGestionBody.innerHTML += selectedInnerHTML +"<br>"

        await selectAvailableVehicle()
    }

    document.getElementById("dateSelectDebut").addEventListener("change", ()=>{
        selectAvailableVehicle()
    })
    document.getElementById("dateSelectEnd").addEventListener("change", ()=>{
        selectAvailableVehicle()
    })

    async function selectAvailableVehicle(){
        // Check the available vehicle
        startLoading()
        const dateSelectDebut = document.getElementById("dateSelectDebut");
        const dateSelectFin = document.getElementById("dateSelectEnd");
        if(!dateSelectDebut.value || !dateSelectFin.value){
            popup("Vous devez donner une heure de départ et de fin")
            return
        }
        let startDate = new Date(dateSelectDebut.value);
        const endDate = new Date(dateSelectFin.value);

        const startDateString = startDate.toISOString();
        const endDateString = endDate.toISOString();

        if(!vehicle.isValidDate(dateSelectDebut.value)){
            popup("Erreur..")
            stopLoading()
            return
        }
        if(!vehicle.isValidDate(dateSelectFin.value)){
            popup("Erreur..")
            stopLoading()
            return
        }

        if(vehicle.isDateInThePast(dateSelectDebut.value)){
            popup("Start Date is in the past :/")
            stopLoading()
            return
        }
        if(vehicle.isDateInThePast(dateSelectFin.value)){
            popup("End Date is in the past :/")
            stopLoading()
            return
        }


        const data = await vehicle.getAssociationAvailableVehicle(startDateString, endDateString);

        const options = [];
        for (const key in data) {
            options.push({
                "value": data[key].id_vehicule,
                "text": `${data[key].nom_du_vehicules}`
            });
        }

        const select4 = createSelect(options, "Choose a Vehicle");
        const idselect4 = document.getElementById("select4");
        if (idselect4) {
            idselect4.innerHTML = '';
            idselect4.appendChild(select4);
        } else {
            console.error("Element 'idselect4' not found");
        }
        stopLoading()
    }

    async function valideData(){
        startLoading()

        const dateSelect = document.getElementById("dateSelectDebut")
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

        const selectElement = document.querySelector("#select4 > select");
        if(selectElement.value === "Choose a Vehicle" || selectElement.value === null){
            popup("Sélectionnez un véhicle avant de valider")
        }

        lesDataToSendGroup.id_vehicule = selectElement.value
        if (lesDataToSendGroup.id_vehicule === null) {
            popup("Error, wrong start or end")
            stopLoading()
            return
        }

        if(lesDataToSendGroup.date === "1970-01-01 00:00:00" || lesDataToSendGroup.date === ""){
            popup("Error, wrong date")
            stopLoading()
            return
        }

        const returnData = await request.validateGroupDemande(lesDataToSendGroup)

        const duh = await calcSpeedAddress(returnData, lesDataToSendGroup.id_vehicule)

        if(duh === false){
            popup("Error lors du calcul du chemin le plus rapide")
            stopLoading()
            return
        }

        const docDefinition = {
            header: {
                text: 'Trajet optimisé',
                style: 'header'
            },
            content: [
                ...duh.map(adresse => ({
                    text: adresse,
                    style: 'adresse'
                }))
            ],
            styles: {
                header: {
                    fontSize: 18,
                    bold: true,
                    margin: [0, 0, 0, 10]
                }
            }
        };

        pdfMake.createPdf(docDefinition).download(`Trajet.pdf`);

        lesDataToSendGroup = {
            "id_demande":[],
            "id_depart":-1,
            "id_arriver":-1,
            "date": "1970-01-01 00:00:00"
        }

        const popupG = document.getElementById("popupGestion");
        popupG.style.display = "none";

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
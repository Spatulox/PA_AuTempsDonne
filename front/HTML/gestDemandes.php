<?php include("../includes/loadLang.php");?>

<!DOCTYPE html>
<html>
<head>

    <?php include("../includes/head.php"); ?>

    <title><?php echo($data["gestDemande"]["title"]) ?></title>
</head>
<body>

<?php include("../includes/header.php");?>

<main>

    <div class="width90 marginAuto marginBottom30">
        <h1 class="textCenter"><?php echo($data["gestDemande"]["title"]) ?></h1>

        <div class="tab flex flexAround nowrap">
            <button class="tablinks width100" onclick="openTab('tab1')"><?php echo($data["gestDemande"]["tab1"]["title"]) ?></button>
            <button class="tablinks width100" onclick="openTab('tab2')"><?php echo($data["gestDemande"]["tab2"]["title"]) ?></button>
            <button class="tablinks width100" onclick="openTab('tab3')"><?php echo($data["gestDemande"]["tab3"]["title"]) ?></button>
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
                    <td><?php echo $data["gestDemande"]["tab1"]["?"] ?></td>
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
                    <td><?php echo $data["gestDemande"]["id"] ?></td>
                    <td><?php echo $data["gestDemande"]["desc"] ?></td>
                    <td><?php echo $data["gestDemande"]["type"] ?></td>
                    <td><?php echo $data["gestDemande"]["state"] ?></td>
                    <td><?php echo $data["gestDemande"]["date"] ?></td>
                    <td><?php echo $data["gestDemande"]["activite"] ?></td>
                    <td><?php echo $data["gestDemande"]["?"] ?></td>
                    <td><?php echo $data["gestDemande"]["user"] ?></td>
                    <td><?php echo $data["gestDemande"]["?"] ?></td>
                </tr>
                </thead>
                <tbody id="bodyBruh">
                <!-- Les lignes de données seront insérées ici par JavaScript -->
                </tbody>
            </table>
        </div>

    </div>

</main>

<?php include("../includes/footer.php");?>

</body>
</html>

<script type="text/javascript" defer>

    const request = new DemandeAdmin()
    const user = new UserAdmin()
    const activity = new ActiviteAdmin()
    let email = []
    let demande = []
    let acti_desc = []

    async function fillListDemande(){
        const bodyList = document.getElementById("bodyList")
        bodyList.innerHTML = ""

        demande = await request.getAllDemande()
        await formateMainData()
        console.log(demande)

        createBodyTableau(bodyList, demande, [], [user.msg["Details"]], ["seeDetails"], "id_demande")
        replaceCharacters()
    }

    async function formateData(){

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

    async function formateMainData(){

        const etat = {
            "1":"En Attente",
            "2":"Validé",
            "-1":"Refusée"
        }

        for (const key in demande) {

            demande[key].etat = etat[demande[key].etat]
            demande[key].id_user = email[demande[key].id_user]
            demande[key].id_activite = acti_desc[demande[key].id_activite]
        }
        
    }

    async function formateOneData(data){

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

        let button = createButton(request.msg["Validate"])
        button.setAttribute("onclick", "validateRequest("+data.id_demande+")")
        button.classList.add("marginTop20")
        button.classList.add("marginRight20")
        card.appendChild(button)


        button = createButton(request.msg["Delete"])
        button.setAttribute("onclick", "deleteRequest("+data.id_demande+")")
        button.classList.add("marginTop20")
        card.appendChild(button)

        output.appendChild(card);
        output.classList.add("width50")
        output.classList.add("marginAuto")

    }

    async function validateRequest(id_demande){

    }

    async function deleteRequest(id_demande){
        startLoading()
        await request.deleteDemande(id_demande)
        stopLoading()
        reload()
    }

    async function seeDetails(id){
        startLoading()
        const objectWithId = demande.find(obj => obj.id_demande == id);
        console.log(objectWithId)
        await formateOneData(objectWithId)
        openTab('tab2')
        stopLoading()
    }

    async function reload(){
        startLoading()
        await formateMainData()
        openTab('tab1')
        stopLoading()
    }

    async function onload(){
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
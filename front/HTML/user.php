<?php include("../includes/loadLang.php"); ?>

<!DOCTYPE html>
<html>
<head>

    <?php include("../includes/head.php"); ?>

    <title><?php echo($data["user"]["title"]) ?></title>
</head>
<body>

<?php include("../includes/header.php"); ?>

<main>

    <div class="width80 marginAuto">
        <h1 class="textCenter"><?php echo($data["user"]["title"]) ?></h1>

        <div class="tab flex flexAround nowrap">
            <button class="tablinks width100"
                    onclick="openTab('tab1')"><?php echo($data["user"]["tab1"]["title"]) ?></button>
            <button class="tablinks width100"
                    onclick="openTab('tab2')"><?php echo($data["user"]["tab2"]["title"]) ?></button>
            <button class="tablinks width100"
                    onclick="openTab('tab3')"><?php echo($data["user"]["tab3"]["title"]) ?></button>
            <button class="tablinks width100"
                    onclick="openTab('tab4')"><?php echo($data["user"]["tab4"]["title"]) ?></button>
        </div>

        <div id="tab1" class="tabcontent marginBottom20">
            <h3><?php echo $data["user"]["tab1"]["title"] ?></h3>

            <!--<div><input class="marginBottom20 search-box" type="text" id="searchUser" oninput="searchUser()"
                        placeholder="Email..."></div>-->

            <table>
                <thead>
                <tr>
                    <td>ID_User</td>
                    <td><?php echo $data["user"]["tab1"]["name"] ?></td>
                    <td>Email</td>
                    <td>Telephone</td>
                    <td>Role</td>
                    <td><?php echo $data["user"]["tab1"]["storehouse"] ?></td>
                    <td><?php echo $data["user"]["tab1"]["buttonSee"] ?></td>
                </tr>
                </thead>
                <tbody id="tbodyUser">
                </tbody>
            </table>
        </div>

        <div id="tab2" class="tabcontent">
            <h3 class="textCenter"><?php echo $data["user"]["tab2"]["title"] ?></h3>
            <div id="tab2Child" class="width50 padding10 marginBottom20 marginAuto border">
                <?php echo $data["user"]["tab2"]["errorMsg"] ?>
            </div>
        </div>

        <div id="tab3" class="tabcontent">
            <h3 class="textCenter"><?php echo $data["user"]["tab3"]["title"] ?></h3>
            <div id="tab3Child" class="widthAuto padding10 marginBottom20 marginAuto">
                <table>
                    <thead>
                    <tr>
                        <th>ID Planning</th>
                        <th>Description</th>
                        <th>Date Activité</th>
                        <th>Description Activité</th>
                        <th>Nom Index Planning</th>
                    </tr>
                    </thead>
                    <tbody id="tab3ChildBody">
                    <!-- Les lignes de données seront insérées ici par JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <div id="tab4" class="tabcontent">
            <h3 class="textCenter"><?php echo $data["user"]["tab4"]["title"] ?></h3>
            <div id="tab4Child" class="widthAuto padding10 marginBottom20 marginAuto">
                <table>
                    <thead>
                    <tr>
                        <td>ID_User</td>
                        <td><?php echo $data["user"]["tab1"]["name"] ?></td>
                        <td>Email</td>
                        <td>Telephone</td>
                        <td>Role</td>
                        <td><?php echo $data["user"]["tab1"]["storehouse"] ?></td>
                        <td><?php echo $data["user"]["tab4"]["button"] ?></td>
                    </tr>
                    </thead>
                    <tbody id="tab4ChildBody">
                    <!-- Les lignes de données seront insérées ici par JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <?php

    ?>

</main>

<?php include("../includes/footer.php"); ?>

</body>
</html>

<script type="text/javascript" defer>
    const tab = getParamFromUrl("tab")

    if (tab && idExistInPage("tab" + tab)) {
        openTab('tab' + tab)
    } else {
        openTab('tab1')
    }
</script>


<script type="text/javascript" defer>

    const lang = getCookie("lang")

    const user = new UserAdmin()
    const planning = new PlanningAdmin()

    let data = []
    let plannings = {}
    let userWait = []

    async function fillTbodyUser() {
        const tbodyUser = document.getElementById("tbodyUser")
        tbodyUser.innerHTML = ""

        if (tbodyUser === undefined) {
            return
        }

        data.forEach(item => {
            const row = document.createElement('tr');

            const idCell = document.createElement('td');
            idCell.textContent = item.id_user;
            row.appendChild(idCell);

            const nomPrenomCell = document.createElement('td');
            nomPrenomCell.textContent = `${item.nom} ${item.prenom}`;
            row.appendChild(nomPrenomCell);

            const emailCell = document.createElement('td');
            emailCell.textContent = item.email;
            row.appendChild(emailCell);

            const telephoneCell = document.createElement('td');
            telephoneCell.textContent = item.telephone;
            row.appendChild(telephoneCell);

            const roleCell = document.createElement('td');
            roleCell.textContent = user.roleArray[item.id_role];
            row.appendChild(roleCell);

            const entrepotCell = document.createElement('td');
            entrepotCell.textContent = item.id_entrepot || 'N/A';
            row.appendChild(entrepotCell);

            const buttonCell = document.createElement('td');
            const button = createButton(dico[lang]["See"])
            button.setAttribute("onclick", "showUser(" + item.id_user + ")")
            buttonCell.appendChild(button)
            row.appendChild(buttonCell);

            tbodyUser.appendChild(row);
        });
    }

    async function fillPlanningTab3(id = null) {
        let container = ""
        if(id != null){
            plannings = await planning.getPlanningByIdUSer(id)

            container = document.getElementById('tab3ChildBody');
            container.innerHTML = ""
        } else {
            plannings = []
        }

        // Créer les lignes de données
        if (plannings.length > 0) {


            plannings.forEach(item => {
                const row = document.createElement('tr');
                const values = [item.id_planning, item.description, item.date_activite, item.activity_desc, item.nom_index_planning];
                values.forEach(value => {
                    const td = document.createElement('td');
                    td.textContent = value;
                    row.appendChild(td);
                });
                container.appendChild(row);
            });
        } else {
            const emptyRow = document.createElement('tr');
            const emptyCell = document.createElement('td');
            emptyCell.textContent = 'No Data';
            emptyCell.classList.add('empty-message');
            emptyCell.colSpan = 5;
            emptyRow.appendChild(emptyCell);
            container.appendChild(emptyRow);
        }

        replaceCharacters()

    }

    async function fillTab4() {
        userWait = await user.getWaitingUser()

        const tab4ChildBody = document.getElementById("tab4ChildBody")
        tab4ChildBody.innerHTML = ""

        if (tab4ChildBody === undefined) {
            return
        }

        userWait.forEach(item => {
            const row = document.createElement('tr');

            const idCell = document.createElement('td');
            idCell.textContent = item.id_user;
            row.appendChild(idCell);

            const nomPrenomCell = document.createElement('td');
            nomPrenomCell.textContent = `${item.nom} ${item.prenom}`;
            row.appendChild(nomPrenomCell);

            const emailCell = document.createElement('td');
            emailCell.textContent = item.email;
            row.appendChild(emailCell);

            const telephoneCell = document.createElement('td');
            telephoneCell.textContent = item.telephone;
            row.appendChild(telephoneCell);

            const roleCell = document.createElement('td');
            roleCell.textContent = item.id_role;
            row.appendChild(roleCell);

            const entrepotCell = document.createElement('td');
            entrepotCell.textContent = item.id_entrepot || 'N/A';
            row.appendChild(entrepotCell);

            const buttonCell = document.createElement('td');
            const button = createButton(dico[lang]["Validate"])
            button.setAttribute("onclick", "validateUSer(" + item.id_user + ")")
            buttonCell.appendChild(button)
            row.appendChild(buttonCell);

            tab4ChildBody.appendChild(row);
        });

    }

    async function showUser(id) {
        const userWithId = data.find(item => item.id_user == id);

        const lesDivs = document.createElement("div")
        const divDisplay = document.createElement("div")
        const divUpdate = document.createElement("div")


        // Create display info
        const userInfoContainer = document.getElementById("tab2Child");
        userInfoContainer.innerHTML = ""

        let idDuRoleActuel = 0

        for (const [key, value] of Object.entries(userWithId)) {
            if (key != "apikey") {
                if(key == "id_role"){
                    idDuRoleActuel = value
                }
                const element = createLabelValueElement(key, value);
                divDisplay.appendChild(element);
            }
        }


        // Create Update part
        for (const [key, value] of Object.entries(userWithId)) {
            if (key != "apikey") {
                keyOk = ["nom", "prenom", "telephone", "id_role"]
                if (keyOk.includes(key) && key != 'id_role') {
                    const element = createInputPlaceholderElement(key);
                    divUpdate.appendChild(element);
                } else if (key === "id_role"){
                    const options = [
                        { value: 1, text: user.roleArray[1] },
                        { value: 2, text: user.roleArray[2] },
                        { value: 3, text: user.roleArray[3] },
                        { value: 4, text: user.roleArray[4] },
                        { value: 5, text: user.roleArray[5] }
                    ];
                    let debut = user.roleArray[idDuRoleActuel]
                    const select = createSelect(options, debut)
                    select.id="in_role"
                    select.classList.add("marginTop10")
                    divUpdate.appendChild(select)
                }
            }
        }

        // Données des options



        // Boté des divs
        lesDivs.classList.add("flex")
        lesDivs.classList.add("flexAround")
        lesDivs.classList.add("nowrap")

        // Append part
        lesDivs.appendChild(divDisplay)
        lesDivs.appendChild(divUpdate)

        userInfoContainer.appendChild(lesDivs)

        // Create button to Update
        let button = createButton(dico[lang]["Update"] + dico[lang]["user"])
        button.setAttribute("onclick", "updateLeUser()")
        button.classList.add("marginTop20")
        button.classList.add("marginBottom10")
        button.classList.add("marginAuto")
        button.classList.add("block")
        userInfoContainer.appendChild(button)

        // Create button to Update
        button = createButton(dico[lang]["Delete"] + dico[lang]["user"])
        button.setAttribute("onclick", "deleteLeUser(" + userWithId.id_user + ")")
        button.classList.add("marginTop20")
        button.classList.add("marginBottom10")
        button.classList.add("marginAuto")
        button.classList.add("block")
        userInfoContainer.appendChild(button)

        openTab('tab2')
        fillPlanningTab3(+id)
        replaceCharacters()
    }

    async function validateUSer(id) {

        const resp = await user.updateUserValidate(id)
        fillTab4()
        fillTbodyUser()
    }

    async function searchUser() {
        const emailToSearch = document.getElementById("searchUser").value

        if (emailToSearch == "") {
            data = await user.getAllUser()
        } else {
            data = await user.getUserViaEmail(emailToSearch)
        }

        fillTbodyUser()
    }

    function createLabelValueElement(label, value) {
        const container = document.createElement("div");
        const labelElement = document.createElement("span");

        labelElement.textContent = `${label} :`;
        labelElement.classList.add("underline")
        labelElement.classList.add("bold")
        const valueElement = document.createElement("span");
        if (label === "id_role") {
            valueElement.textContent = " " + user.roleArray[value] + " ("+value+")" || " N/A";
        } else {
            valueElement.textContent = " " + value || " N/A"
        }
        valueElement.id = "va_" + label
        container.appendChild(labelElement);
        container.appendChild(valueElement);
        return container;
    }

    function createInputPlaceholderElement(placeholder_Name) {
        const container = document.createElement("div");
        const inputElement = document.createElement("input");
        inputElement.classList.add("marginTop10")
        inputElement.name = placeholder_Name;
        inputElement.id = "in_" + placeholder_Name;
        inputElement.placeholder = `${placeholder_Name}`;
        container.appendChild(inputElement);
        return container;
    }

    async function onload() {
        startLoading()
        await user.connect()

        data = await user.getAllUser()

        await planning.connect()
        fillTbodyUser()
        fillPlanningTab3()
        fillTab4()
        replaceCharacters()
        stopLoading()
    }

    async function updateLeUser() {
        let in_nom = document.getElementById("in_nom").value
        let in_prenom = document.getElementById("in_prenom").value
        //let in_email = document.getElementById("in_email").value
        let in_telephone = document.getElementById("in_telephone").value

        let in_role = document.getElementById("in_role").value

        const va_nom = document.getElementById("va_nom").innerHTML
        const va_prenom = document.getElementById("va_prenom").innerHTML
        const va_email = document.getElementById("va_email").innerHTML
        const va_tel = document.getElementById("va_telephone").innerHTML

        const va_role = document.getElementById("va_id_role").innerHTML.split('(')[1].split(")")[0]

        let va_id_user = document.getElementById("va_id_user").innerHTML

        in_nom = in_nom ? in_nom : va_nom
        in_prenom = in_prenom ? in_prenom : va_prenom
        in_telephone = in_telephone ? in_telephone : va_tel
        in_role = in_role ? in_role : null


        startLoading()
        if(in_role !== va_role){
            const rep = await user.updateUserRole(in_role, va_id_user)

            va_id_user = va_id_user.split(" ")
            va_id_user = va_id_user[va_id_user.length -1]

        }

        const response = await user.updateUser(va_email, in_prenom, in_telephone, in_nom)

        if (response) {
            data = await user.getAllUser()
            showUser(+va_id_user)
        }

        stopLoading()
    }

    async function deleteLeUser(id) {
        const response = await user.deleteUser(id)
    }

    window.addEventListener('load', () => {
        onload()
    });

</script>
<?php include("../includes/loadLang.php");?>

<!DOCTYPE html>
<html>
<head>

    <?php include("../includes/head.php"); ?>

    <title><?php echo($data["user"]["title"]) ?></title>
</head>
<body>

<?php include("../includes/header.php");?>

<main>

    <div class="width80 marginAuto">
        <h1 class="textCenter"><?php echo($data["user"]["title"]) ?></h1>

        <div class="tab flex flexAround nowrap">
            <button class="tablinks width100" onclick="openTab('tab1')"><?php echo($data["user"]["tab1"]["title"]) ?></button>
            <button class="tablinks width100" onclick="openTab('tab2')"><?php echo($data["user"]["tab2"]["title"]) ?></button>
            <button class="tablinks width100" onclick="openTab('tab3')"><?php echo($data["user"]["tab3"]["title"]) ?></button>
        </div>

        <div id="tab1" class="tabcontent marginBottom20">
            <h3><?php echo$data["user"]["tab1"]["title"] ?></h3>
            
            <div><input class="marginBottom20 search-box" type="text" id="searchUser" oninput="searchUser()" placeholder="Email..."></div>

            <table>
                <thead>
                <tr>
                    <td>ID_User</td>
                    <td><?php echo$data["user"]["tab1"]["name"]?></td>
                    <td>Email</td>
                    <td>Telephone</td>
                    <td>Role</td>
                    <td><?php echo$data["user"]["tab1"]["storehouse"]?></td>
                    <td><?php echo$data["user"]["tab1"]["buttonSee"]?></td>
                </tr>
                </thead>
                <tbody id="tbodyUser">
                </tbody>
            </table>
        </div>

        <div id="tab2" class="tabcontent">
            <h3 class="textCenter"><?php echo$data["user"]["tab2"]["title"]?></h3>
            <div id="tab2Child" class="width50 padding10 marginBottom20 marginAuto border">
                <?php echo$data["user"]["tab2"]["errorMsg"]?>
            </div>
        </div>

        <div id="tab3" class="tabcontent">
            <h3 class="textCenter"><?php echo$data["user"]["tab3"]["title"]?></h3>
            <div id="tab2Child" class="width50 padding10 marginBottom20 marginAuto border">
                <?php echo$data["user"]["tab3"]["errorMsg"]?>
            </div>
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

    if(tab && idExistInPage("tab"+tab)){
        openTab('tab'+tab)
    } else {
        openTab('tab1')
    }
</script>


<script type="text/javascript" defer>

    const lang = getCookie("lang")

    const user = new UserAdmin()
    let data = {}

    async function fillTbodyUser(){
        const tbodyUser = document.getElementById("tbodyUser")

        if(tbodyUser === undefined){
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
            roleCell.textContent = item.id_role;
            row.appendChild(roleCell);

            const entrepotCell = document.createElement('td');
            entrepotCell.textContent = item.id_entrepot || 'N/A';
            row.appendChild(entrepotCell);

            const buttonCell = document.createElement('td');
            const button = createButton(dico[lang]["See"])
            button.setAttribute("onclick", "showUser("+item.id_user+")")
            buttonCell.appendChild(button)
            row.appendChild(buttonCell);

            tbodyUser.appendChild(row);
        });
    }

    async function showUser(id){
        const userWithId = data.find(item => item.id_user == id);

        const lesDivs = document.createElement("div")
        const divDisplay = document.createElement("div")
        const divUpdate = document.createElement("div")


        // Create display info
        const userInfoContainer = document.getElementById("tab2Child");
        userInfoContainer.innerHTML = ""

        for (const [key, value] of Object.entries(userWithId)) {
            if(key != "apikey"){
                const element = createLabelValueElement(key, value);
                divDisplay.appendChild(element);
            }
        }


        // Create Update part
        for (const [key, value] of Object.entries(userWithId)) {
            if(key != "apikey"){
                keyOk = ["nom", "prenom", "email", "telephone", "role"]
                if(keyOk.includes(key)){
                    const element = createInputPlaceholderElement(key);
                    divUpdate.appendChild(element);
                }
            }
        }

        // Boté des divs
        lesDivs.classList.add("flex")
        lesDivs.classList.add("flexAround")
        lesDivs.classList.add("nowrap")

        // Append part
        lesDivs.appendChild(divDisplay)
        lesDivs.appendChild(divUpdate)

        userInfoContainer.appendChild(lesDivs)

        // Create button to Update
        let button = createButton(dico[lang]["Update"]+dico[lang]["user"])
        button.setAttribute("onclick", "updateLeUser()")
        button.classList.add("marginTop20")
        button.classList.add("marginBottom10")
        button.classList.add("marginAuto")
        button.classList.add("block")
        userInfoContainer.appendChild(button)

        // Create button to Update
        button = createButton(dico[lang]["Delete"]+dico[lang]["user"])
        button.setAttribute("onclick", "deleteLeUser("+userWithId.id_user+")")
        button.classList.add("marginTop20")
        button.classList.add("marginBottom10")
        button.classList.add("marginAuto")
        button.classList.add("block")
        userInfoContainer.appendChild(button)

        openTab('tab2')
    }

    async function searchUser(){
        const emailToSearch = document.getElementById("searchUser").value

        if(emailToSearch == ""){
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
        valueElement.textContent = " "+ value || " N/A";
        valueElement.id = "va_"+label
        container.appendChild(labelElement);
        container.appendChild(valueElement);
        return container;
    }

    function createInputPlaceholderElement(placeholder_Name) {
        const container = document.createElement("div");
        const inputElement = document.createElement("input");
        inputElement.classList.add("marginTop10")
        inputElement.name = placeholder_Name;
        inputElement.id = "in_"+placeholder_Name;
        inputElement.placeholder = `${placeholder_Name}`;
        container.appendChild(inputElement);
        return container;
    }

    async function onload(){
        await user.connect()
        data = await user.getAllUser()

        fillTbodyUser()
    }

    async function updateLeUser(){
        let in_nom = document.getElementById("in_nom").value
        let in_prenom = document.getElementById("in_prenom").value
        let in_email = document.getElementById("in_email").value
        let in_telephone = document.getElementById("in_telephone").value

        const va_nom = document.getElementById("va_nom").innerHTML
        const va_prenom = document.getElementById("va_prenom").innerHTML
        const va_email = document.getElementById("va_email").innerHTML
        const va_tel = document.getElementById("va_telephone").innerHTML

        in_nom = in_nom ? in_nom : va_nom
        in_prenom = in_prenom ? in_prenom : va_prenom
        in_email = in_email ? in_email : va_email
        in_telephone = in_telephone ? in_telephone : va_tel

        console.log(in_nom)

        const response = await user.updateUser(in_email, in_prenom, in_telephone, in_nom)

        if(response){
            data = await user.getAllUser()
            showUser(response["id_user"])
        }
    }

    async function deleteLeUser(id){
        const response = await user.deleteUser(id)
    }

    onload()

</script>
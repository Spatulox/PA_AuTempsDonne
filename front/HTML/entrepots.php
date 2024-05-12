<?php include("../includes/loadLang.php");?>

<!DOCTYPE html>
<html>
	<head>

		<?php include("../includes/head.php"); ?>

		<title><?php echo($data["gestEntrepot"]["title"]) ?></title>
	</head>
	<body>

		<?php include("../includes/header.php");?>

		<main>

            <div class="width80 marginAuto">
                <h1 class="textCenter"><?php echo($data["storehouse"]["title"]) ?></h1>

                <div class="tab flex flexAround nowrap">
                    <button class="tablinks width100"
                            onclick="openTab('tab2')"><?php echo($data["stock"]["tab2"]["title"]) ?></button>
                    <button class="tablinks width100"
                            onclick="openTab('tab3')"><?php echo($data["stock"]["tab3"]["title"]) ?></button>
                    <button class="tablinks width100"
                            onclick="openTab('tab4')"><?php echo($data["stock"]["tab4"]["title"]) ?></button>
                    <button class="tablinks width100"
                            onclick="openTab('tab5')"><?php echo($data["stock"]["tab5"]["title"]) ?></button>
                </div>

                <div id="tab1" class="tabcontent marginBottom20">
                    <h2 class="flex flexCenter"><?php echo($data["stock"]["tab1"]["title"]) ?></h2>
                    <table class="">
                        <thead>
                        <tr>
                            <td><?php echo($data["storehouse"]["tab1"]["IDStorehouse"]) ?></td>
                            <td><?php echo($data["storehouse"]["tab1"]["name"]) ?></td>
                            <td><?php echo($data["storehouse"]["tab1"]["ParkingPlace"]) ?></td>
                            <td><?php echo($data["storehouse"]["tab1"]["Addresses"]) ?></td>
                            <td><?php echo($data["storehouse"]["tab1"]["Rangement"]) ?></td>
                            <td><?php echo($data["storehouse"]["tab1"]["See"]) ?></td>
                        </tr>
                        </thead>
                        <tbody id="bodyList"></tbody>
                    </table>
                </div>

                <div id="tab2" class="tabcontent marginBottom20">
                    <h2 class="flex flexCenter"><?php echo($data["stock"]["tab2"]["title"]) ?></h2>
                    <table class="">
                        <thead>
                        <tr>
                            <td><?php echo($data["stock"]["tab1"]["ID_Stock"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["QTE"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["Date_Entry"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["Date_Exit"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["Date_perem"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["desc"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["IdEtagere"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["Storehouse_desc"]) ?></td>
                        </tr>
                        </thead>
                        <tbody id="bodyDetail">
                        <tr>
                            <td colspan="8"><?php echo($data["stock"]["tab2"]["errorMsg"]) ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div id="tab3" class="tabcontent marginBottom20">
                    <h2 class="flex flexCenter"><?php echo($data["stock"]["tab3"]["title"]) ?></h2>

                    <p id="messageAddProduct" class="textCenter marginBottom10"></p>

                    <p id="bodyAddStock" class="border"><?php echo($data["stock"]["tab2"]["errorMsg"]) ?></p>

                </div>

                <div id="tab4" class="tabcontent marginBottom20">
                    <h2 class="flex flexCenter"><?php echo($data["stock"]["tab4"]["title"]) ?></h2>
                    <table class="">
                        <thead>
                        <tr>
                            <td><?php echo($data["stock"]["tab1"]["ID_Stock"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["QTE"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["Date_Entry"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["Date_perem"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["desc"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["IdEtagere"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["Storehouse_desc"]) ?></td>
                        </tr>
                        </thead>
                        <tbody id="bodyAddRemove">
                        <tr>
                            <td colspan="8"><?php echo($data["stock"]["tab2"]["errorMsg"]) ?></td>
                        </tr>
                        </tbody>
                    </table>
                    <div id="bodyAddRemove2"></div>
                </div>

                <div id="tab5" class="tabcontent marginBottom20">
                    <h2 class="flex flexCenter"><?php echo($data["stock"]["tab5"]["title"]) ?></h2>
                    <table class="">
                        <thead>
                        <tr>
                            <td><?php echo($data["stock"]["tab1"]["ID_Stock"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["QTE"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["Date_Entry"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["Date_perem"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["desc"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["IdEtagere"]) ?></td>
                            <td><?php echo($data["stock"]["tab1"]["Storehouse_desc"]) ?></td>
                        </tr>
                        </thead>
                        <tbody id="bodyExited"></tbody>
                    </table>
                </div>
            </div>

		</main>

		<?php include("../includes/footer.php");?>

	</body>
</html>


<script type="text/javascript" defer>
    const stock = new StockAdmin()
    const entrepot = new EntrepotAdmin()
    let entre = []
    let lesStocks = []

    const user = new User()

    async function fillList() {
        entre = await user.myEntrepot()
        await seeStock(user.entrepot)
    }

    async function fillExitedStocks(id_entrepot) {
        const bodyExited = document.getElementById("bodyExited")
        bodyExited.innerHTML = ""

        const exited = await stock.getExitedStock(id_entrepot)
        createBodyTableau(bodyExited, exited, ["id_entrepot", 'id_produit', "desc_produit", "date_sortie"])
        replaceCharacters()

    }

    async function seeStock(id) {
        startLoading()
        const bodyDetail = document.getElementById("bodyDetail")

        lesStocks = await stock.getStockById(id)

        bodyDetail.innerHTML = ""

        createBodyTableau(bodyDetail, lesStocks, ["id_entrepot", 'id_produit', "desc_produit"], [entrepot.msg["See"]], ["addRemoveStockDetail"], "id_produit")

        fillExitedStocks(id)
        fillAddStockTab(id)
        replaceCharacters()
        openTab('tab2')
        stopLoading()
    }

    async function addRemoveStockDetail(id_produit) {

        const htmlelement = document.getElementById("bodyAddRemove")
        htmlelement.innerHTML = ""

        const tab4 = document.getElementById("tab4")

        const id = findObjectByIdProduit(lesStocks, id_produit)

        const obj = formatObjectToHTML(htmlelement, id)
        if (obj === false) {
            popup("Erreur lors du retrieve des données")
            stopLoading()
            return
        }

        const trucChiantQuiReste = document.getElementById("trucChiantQuiReste")
        if (trucChiantQuiReste !== null) {
            trucChiantQuiReste.remove()
        }

        const div = document.createElement("div")
        div.id = "trucChiantQuiReste"
        const button = createButton("Retrieve")

        button.classList.add("block")
        button.classList.add("marginTop10")
        button.classList.add("marginBottom30")
        button.classList.add("width100")
        button.setAttribute("onclick", "retrieveFromStock(" + id_produit + ")")

        div.classList.add("flex")
        div.classList.add("flexCenter")
        div.classList.add("width50")
        div.classList.add("wrap")
        div.classList.add("marginAuto")

        div.appendChild(button)

        tab4.appendChild(div)

        CreateLesInputs("bodyAddRemove2", false)

        const bodyAddRemove2 = document.getElementById("bodyAddRemove2")
        bodyAddRemove2.classList.add("marginTop30")
        bodyAddRemove2.classList.add("flex")
        bodyAddRemove2.classList.add("flexCenter")
        bodyAddRemove2.classList.add("width50")
        bodyAddRemove2.classList.add("wrap")
        bodyAddRemove2.classList.add("marginAuto")

        replaceCharacters()
        openTab('tab4')

    }

    async function addStock() {
        const selectbodyAddStock = document.getElementById("selectbodyAddStock")
        const qte_produitbodyAddStock = document.getElementById("qte_produitbodyAddStock")
        const date_perem = document.getElementById("date_perem")
        const selectProductTypeNameYeet = document.getElementById("selectProductTypeNameYeet")

        const messageAddProduct = document.getElementById("messageAddProduct")
        messageAddProduct.innerHTML = ""
        messageAddProduct.classList.add("border")
        messageAddProduct.classList.add("underline")

        const date = today()

        const data = {
            "quantite_produit": qte_produitbodyAddStock.value,
            "date_entree": date,
            "date_sortie": "NULL",
            "date_peremption": date_perem.value ? date_perem.value : "NULL",
            "desc_produit": selectProductTypeNameYeet[selectProductTypeNameYeet.value].text,
            "id_produit": selectProductTypeNameYeet.value,
            "id_entrepot": selectbodyAddStock.value
        }

        const response = await stock.createStock(data)

        let yaunelmessage = false
        let message = ""

        if(response.create == null){
            messageAddProduct.style.color="red"
        } else {
            messageAddProduct.style.color="green"
        }

        if (response.msg != "") {
            yaunelmessage = true
            message += response.msg + "<br>"
        }

        if (response.msg_tab != null) {
            yaunelmessage = true
            for (const key in response.msg_tab) {
                message += "- " + response.msg_tab[key] + "<br>"

            }

        }

        if (yaunelmessage === true) {
            popup(message)
            messageAddProduct.innerHTML = message
        }
    }

    function createLabelValueElement(label, value) {
        const container = document.createElement("div");
        const labelElement = document.createElement("span");

        labelElement.textContent = `${label} :`;
        labelElement.classList.add("underline")
        labelElement.classList.add("bold")
        const valueElement = document.createElement("span");
        if (label === "id_role") {
            valueElement.textContent = " " + user.roleArray[value] + " (" + value + ")" || " N/A";
        } else {
            valueElement.textContent = " " + value || " N/A"
        }
        valueElement.id = "va_" + label
        container.appendChild(labelElement);
        container.appendChild(valueElement);
        return container;
    }

    async function fillAddStockTab() {
        const bodyAddStock = document.getElementById("bodyAddStock")
        bodyAddStock.innerHTML = ""
        CreateLesInputs("bodyAddStock", true, false)

        const div = document.createElement("div")
        div.innerHTML = "Date Peremption (if needed) : "

        const input = createInput("Date peremption", "date_perem")
        input.type = "date"

        div.appendChild(input)

        bodyAddStock.appendChild(div)

        const product = new ProductAdmin()

        let productData = await product.getAllProduct()
        let option = []
        const debut = "Choose Product type"

        for (const key in productData) {

            option[key] = {
                "value": productData[key].id_produit,
                "text": productData[key].nom_produit
            }

        }

        // ID Produit a retirer
        let yeet = createSelect(option, debut)
        yeet.id = "selectProductTypeNameYeet"
        bodyAddStock.appendChild(yeet)

        const button = createButton(stock.msg["Add"])
        button.setAttribute("onclick", "addStock()")
        bodyAddStock.appendChild(button)
    }

    async function retrieveFromStock(id) {

        const qte_produitbodyAddRemove2 = document.getElementById("qte_produitbodyAddRemove2").value
        const date_inputbodyAddRemove2 = document.getElementById("date_inputbodyAddRemove2").value

        if (qte_produitbodyAddRemove2 === "" || date_inputbodyAddRemove2 === "") {
            popup("Vous devez remplir tous les champs avant de récupérer un produit du stock")
            return
        }

        //lesStocks

        let leDataDeMerde = findObjectByIdProduit(lesStocks, id)

        let count = 0
        for (const key in leDataDeMerde) {
            count += (+leDataDeMerde[key].quantite_produit)
        }

        if (qte_produitbodyAddRemove2 > count) {
            popup("Vous ne pouvez pas retirer plus de " + count + " produits dans cet entrepot")
            return
        }
        startLoading()

        leDataDeMerde = leDataDeMerde[0]

        const data = {
            "quantite_produit": qte_produitbodyAddRemove2,
            "date_entree": "NULL",
            "date_sortie": date_inputbodyAddRemove2,
            "date_peremption": leDataDeMerde.date_peremption,
            "desc_produit": leDataDeMerde.desc_produit,
            "id_produit": id,
            "id_entrepot": leDataDeMerde.id_entrepot
        }

        await stock.addRetrieveFromEntrepot(data)

        //Refresh stocks :
        lesStocks = await stock.getStockById(leDataDeMerde.id_entrepot)
        await seeStock(leDataDeMerde.id_entrepot)
        await addRemoveStockDetail(id)
        openTab('tab4')

        stopLoading()
    }

    function CreateLesInputs(idToTake, doSelect = true, doDate = true) {

        const bodyAddStock = document.getElementById(idToTake)
        bodyAddStock.innerHTML = ""

        const div = document.createElement("div")

        if (doSelect == true) {
            let option = []
            const debut = "Choose Stockhouse"

            for (const key in entre) {

                option[key] = {
                    "value": entre[key].id_entrepot,
                    "text": entre[key].nom
                }

            }

            // ID Produit a retirer
            const leSeect = createSelect(option, debut)
            leSeect.id = "select" + idToTake
            div.appendChild(leSeect)
        }

        // Qte à retirer
        const inputQte = createInput("Qte Product", "qte_produit" + idToTake)
        inputQte.type = "number"
        div.appendChild(inputQte)

        if (doDate === true) {
            // Date du retirage
            const inputDate = createInput("DateInput", "date_input" + idToTake)
            inputDate.type = "date"

            const today = new Date();
            const formattedDate = today.toISOString().slice(0, 10);
            inputDate.value = formattedDate;

            div.appendChild(inputDate)
        }

        bodyAddStock.appendChild(div)

    }

    function findObjectByIdProduit(objectArray, idToFind) {

        let dataToSendBack = []

        for (const key in objectArray) {

            if (objectArray[key].id_produit == idToFind) {

                dataToSendBack.push(objectArray[key])
            }

        }
        if (dataToSendBack.length == 0) {
            return false
        }
        return dataToSendBack
    }

    function findObjectByIdStock(objectArray, idToFind) {

        let dataToSendBack = []

        for (const key in objectArray) {

            if (objectArray[key].id_stock == idToFind) {
                dataToSendBack.push(objectArray[key])
            }

        }
        if (dataToSendBack.length == 0) {
            return false
        }
        return dataToSendBack
    }

    function formatObjectToHTML(htmlelement, obj) {

        let html = ''
        obj.forEach(item => {
            html += `
              <tr>
                <td>${item.id_stock}</td>
                <td>${item.quantite_produit}</td>
                <td>${item.date_entree}</td>
                <td>${item.date_peremption}</td>
                <td>${item.produit_desc}</td>
                <td>${item.id_etagere}</td>
                <td>${item.entrepot_desc}</td>
              </tr>
            `;
        });

        html += `
              </tbody>
            </table>
          `;

        htmlelement.classList.add('table-container');
        htmlelement.innerHTML = html;
    }

    async function refreshData() {
        entre = await entrepot.getEntrepot()
    }

    async function onload() {
        startLoading()

        openTab("tab2")

        await user.connect()
        await fillList()
        await fillAddStockTab()
        replaceCharacters()

        if((await user.me()).entrepot == null){

            redirect("./planning.php?message=Vous n'êtes pas affecté à un entrepot")
            return
        }
        stopLoading()
    }

    onload()

</script>


<?php include("../includes/loadLang.php"); ?>

<!DOCTYPE html>
<html>
<head>

    <?php include("../includes/head.php"); ?>

    <title><?php echo($data["stock"]["title"]) ?></title>
</head>
<body>

<?php include("../includes/header.php"); ?>

<main>

    <div class="width80 marginAuto">
        <h1 class="textCenter"><?php echo($data["stock"]["title"]) ?></h1>

        <div class="tab flex flexAround nowrap">
            <button class="tablinks width100"
                    onclick="openTab('tab1')"><?php echo($data["stock"]["tab1"]["title"]) ?></button>
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

<?php include("../includes/footer.php"); ?>

</body>
</html>


<script type="text/javascript" defer>
    const stock = new StockAdmin()
    const entrepot = new EntrepotAdmin()
    let entre = []
    let lesStocks = []

    async function fillList() {

        const bodyList = document.getElementById("bodyList")
        bodyList.innerHTML = ""
        entre = await entrepot.getEntrepot()
        createBodyTableau(bodyList, entre, ["id_addresse"], [entrepot.msg["See"]], ["seeStock"], "id_entrepot")
        replaceCharacters()
    }

    async function fillExitedStocks(id_entrepot){
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

    async function addStock(id_stock) {
        CreateLesInputs("bodyAddStock")
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
            //console.log(key)
            count += (+leDataDeMerde[key].quantite_produit)
        }

        if(qte_produitbodyAddRemove2 > count){
            popup("Vous ne pouvez pas retirer plus de "+count+ " produits dans cet entrepot")
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

    function CreateLesInputs(idToTake, doSelect = true) {

        const bodyAddStock = document.getElementById(idToTake)
        bodyAddStock.innerHTML = ""

        const div = document.createElement("div")

        if (doSelect == true) {
            let option = []
            const debut = "Choose"

            for (const key in entre) {

                option[key] = {
                    "value": entre[key].id_entrepot,
                    "text": entre[key].nom
                }

            }

            // ID Produit a retirer
            div.appendChild(createSelect(option, debut))
        }

        // Qte à retirer
        const inputQte = createInput("Qte Product", "qte_produit" + idToTake)
        inputQte.type = "number"
        div.appendChild(inputQte)

        // Date du retirage
        const inputDate = createInput("DateInput", "date_input" + idToTake)
        inputDate.type = "date"

        const today = new Date();
        const formattedDate = today.toISOString().slice(0, 10);
        inputDate.value = formattedDate;

        div.appendChild(inputDate)

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

    async function refreshData(){
        entre = await entrepot.getEntrepot()
    }

    function onload() {
        startLoading()

        openTab("tab1")
        fillList()

        stopLoading()
    }

    onload()

</script>

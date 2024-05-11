<?php include("../includes/loadLang.php"); ?>

<!DOCTYPE html>
<html>
<head>

    <?php include("../includes/head.php"); ?>

    <title><?php echo($data["product"]["title"]) ?></title>
</head>
<body>

<?php include("../includes/header.php"); ?>

<main>

    <div class="width80 marginAuto marginBottom30">
        <h1 class="textCenter"><?php echo($data["product"]["title"]) ?></h1>

        <div class="tab flex flexAround nowrap">
            <button class="tablinks width100"
                    onclick="openTab('tab1')"><?php echo($data["product"]["tab1"]["title"]) ?></button>
            <button class="tablinks width100"
                    onclick="openTab('tab2')"><?php echo($data["product"]["tab2"]["title"]) ?></button>
            <button class="tablinks width100"
                    onclick="openTab('tab3')"><?php echo($data["product"]["tab3"]["title"]) ?></button>
        </div>

        <div id="tab1" class="tabcontent">
            <h2 class="textCenter"><?php echo($data["product"]["tab1"]["title"]) ?></h2>
            <table>
                <thead>
                <tr>
                    <td><?php echo $data["product"]["tab1"]["id"] ?></td>
                    <td><?php echo $data["product"]["tab1"]["name"] ?></td>
                    <td><?php echo $data["product"]["tab1"]["type"] ?></td>
                    <td><?php echo $data["product"]["tab1"]["button"] ?></td>
                </tr>
                </thead>
                <tbody id="bodyList">
                <!-- Les lignes de données seront insérées ici par JavaScript -->
                </tbody>
            </table>
        </div>

        <div id="tab2" class="tabcontent">
            <h2 class="textCenter"><?php echo($data["product"]["tab2"]["title"]) ?></h2>
            <!--<input type="number" class="search-box marginBottom10" placeholder="Search by id">-->
            <p id="product-container"
               class="border width60 textCenter"><?php echo($data["product"]["tab2"]["errorMsg"]) ?></p>
        </div>

        <div id="tab3" class="tabcontent">
            <h2 class="textCenter"><?php echo($data["product"]["tab3"]["title"]) ?></h2>
            <hr>
            <div class="textCenter">
                <input id="nameProd" class="marginTop10 search-box" type="text"
                       placeholder="<?php echo($data["product"]["tab1"]["name"]) ?>"><br>
                <div id="leSelectAremplir"
                     class="marginTop10 marginAuto search-box"><?php echo($data["vehicle"]["tab2"]["errorMsg"]) ?>,
                    Impossible to select product type
                </div>
                <br>
                <input class="marginTop30" type="button" onclick="addProduct()"
                       value="<?php echo($data["product"]["tab3"]["title"]) ?>">
            </div>
        </div>
    </div>

</main>
<?php include("../includes/footer.php"); ?>

</body>
</html>

<script type="text/javascript" defer>

    const product = new ProductAdmin()
    let data = []

    async function fillList() {
        data = await product.getAllProduct()
        console.log(data)

        const bodyFill = document.getElementById("bodyList")
        bodyFill.innerHTML = ""

        createBodyTableau(bodyFill, data, ["id_type"], [product.msg["See"]], ["seeDetail"], "id_produit")
        replaceCharacters()

    }

    async function seeDetail(id_product = null) {
        const dataTmp = await product.getProduct(id_product)
        dataTmp.innerHTML = ""

        if (id_product == null) {
            return
        }

        startLoading()

        formData(dataTmp)
        replaceCharacters()
        openTab('tab2')
        stopLoading()
    }

    async function addProduct() {
        startLoading()

        const nameProd = document.getElementById("nameProd")
        const typeProd = document.getElementById("typeProd")

        if(nameProd == null || typeProd == null){
            popup("Vous devez choisir un nom et un type de produit")
            stopLoading()
            return
        }

        const data = {
            "nom_produit":nameProd.value,
            "type":typeProd.value
        }

        await product.createProduct(data)
        fillList()
        stopLoading()

    }

    async function deleteProduct(id){
        startLoading()
        await product.deleteProduct(id)
        await fillList()
        openTab('tab1')
        stopLoading()
    }

    async function fillProductSelect() {

        let select = document.getElementById("leSelectAremplir")
        select.innerHTML = ""

        const type = await product.getAllTypeProduct()

        let option = []
        const debut = "Choose"

        for (const key in type) {

            option[key] = {
                "value": type[key].id_produit,
                "text": type[key].nom_produit
            }

        }
        const newSelect = createSelect(option, debut)
        newSelect.classList.add("marginTop10")
        newSelect.classList.add("search-box")
        newSelect.id = "typeProd"


        select.parentNode.replaceChild(newSelect, select)
        replaceCharacters()
    }

    function formData(product) {

        const productContainer = document.getElementById('product-container');
        productContainer.innerHTML = ""

        const productCard = document.createElement('div');
        productCard.classList.add('product-card');

        const productName = document.createElement('h2');
        productName.textContent = product.nom_produit;

        const productType = document.createElement('p');
        productType.innerHTML = `Type : <span class="type">${product.type_string}</span>`;

        const productId = document.createElement('p');
        productId.textContent = `ID : ${product.id_produit}`;

        const button = createButton("Delete")
        button.setAttribute("onclick", "deleteProduct("+product.id_produit+")")
        button.id = product.id_produit

        productCard.appendChild(productName);
        productCard.appendChild(productType);
        productCard.appendChild(productId);

        productContainer.appendChild(productCard);
        productContainer.appendChild(button)

    }

    async function onload() {
        startLoading()
        openTab('tab1')

        await product.connect()
        await fillList()
        //await fillProductSelect()


        stopLoading()
    }

    onload()

</script>
<?php include("../includes/loadLang.php");?>

<!DOCTYPE html>
<html>
<head>

    <?php include("../includes/head.php"); ?>

    <title><?php echo($data["storehouse"]["title"]) ?></title>
</head>
<body>

<?php include("../includes/header.php");?>

<main>

    <div class="width80 marginAuto">
        <h1 class="textCenter"><?php echo($data["storehouse"]["title"]) ?></h1>

        <div class="tab flex flexAround nowrap">
            <button class="tablinks width100" onclick="openTab('tab1')"><?php echo($data["storehouse"]["tab1"]["title"]) ?></button>
            <button class="tablinks width100" onclick="openTab('tab2')"><?php echo($data["storehouse"]["tab2"]["title"]) ?></button>
            <button class="tablinks width100" onclick="openTab('tab3')"><?php echo($data["storehouse"]["tab3"]["title"]) ?></button>
        </div>

        <div id="tab1" class="tabcontent marginBottom20">
            <h3 class="flex flexCenter"><?php echo($data["storehouse"]["tab1"]["title"]) ?></h3>
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
            <h3 class="flex flexCenter"><?php echo($data["storehouse"]["tab2"]["title"]) ?></h3>
            <p class="border marginBottom10" id="bodyDetail"><?php echo($data["storehouse"]["tab2"]["errorMsg"]) ?> <button type="button" class="width100" onclick="openTab('tab1')"><?php echo($data["storehouse"]["tab1"]["title"]) ?></button> </p>
        </div>

        <div id="tab3" class="tabcontent marginBottom20">
            <h3 class="flex flexCenter"><?php echo($data["storehouse"]["tab3"]["title"]) ?></h3>

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


<script>

    const entrepot = new Entrepot()
    let dataEntrepot = []

    async function fillListing(){
        const bodyList = document.getElementById("bodyList")
        dataEntrepot = await entrepot.getEntrepot()
        createBodyTableau(bodyList, dataEntrepot,["id_addresse"], [entrepot.msg["See"]], ["seeEntrepot"], "id_entrepot")
        replaceCharacters()
    }

    async function seeEntrepot(id){
        startLoading()

        let data = await entrepot.getEntrepot(id)

        if(data === false){
            popup(`L'entrepôt ${id} n'existe pas`)
            stopLoading()
            return
        }


        const formattedEntrepot = formatEntrepot(data[0]);

        const bodyDetail = document.getElementById("bodyDetail")

        bodyDetail.innerHTML = formattedEntrepot

        replaceCharacters()

        openTab("tab2")

        stopLoading()

    }

    async function createShelf(id_entrepot){
        startLoading()

        let place = document.getElementById("inputPlace")

        if(!place){
            popup("Impossible de retrieve le nombre de place")
            stopLoading()
            return
        }

        place = place.value

        if(place === ""){
            popup("Vous devez donner un nombre entier pour la place dans l'étagère")
            stopLoading()
            return
        }

        await entrepot.createShelf(id_entrepot, +place)

        const entrepotElement = document.querySelector('.entrepot');
        const premierPElement = entrepotElement.querySelector('p').innerHTML.split(" : ")[1].trim();
        seeEntrepot(+premierPElement)

        stopLoading()
    }

    async function deleteShelf(id){
        startLoading()
        await entrepot.deleteShelf(id)

        const entrepotElement = document.querySelector('.entrepot');
        const premierPElement = entrepotElement.querySelector('p').innerHTML.split(" : ")[1].trim();

        seeEntrepot(+premierPElement)
    }

    function formatEntrepot(entrepotV) {
        let formattedEntrepot = `<div class="entrepot">
            <h2>${entrepotV.nom}</h2>
            <p><?php echo($data["storehouse"]["tab1"]["IDStorehouse"]) ?> : ${entrepotV.id_entrepot}</p>
            <p><?php echo($data["storehouse"]["tab1"]["Addresses"]) ?> : ${entrepotV.addresse_desc}</p>
            <p><?php echo($data["storehouse"]["tab1"]["ParkingPlace"]) ?> : ${entrepotV.parking}</p>
            <h3><?php echo($data["storehouse"]["tab1"]["Rangement"]) ?> :</h3>
            <ul>`;
        entrepotV.rangement.forEach(rangements => {
                formattedEntrepot += `<li class="marginBottom10">
                  <div class="rangement-item">
                    <span>
                      <?php echo($data["storehouse"]["tab1"]["shelfId"]) ?> : ${rangements.id_etagere}<br>
                      <?php echo($data["storehouse"]["tab1"]["shelf"]) ?> : ${rangements.nombre_de_place}
                    </span>
                    <button type="button" class="btn-action" onclick="deleteShelf(${rangements.id_etagere})">${entrepot.msg["Delete"]}</button>
                  </div>
                </li>`;
            });

                formattedEntrepot += `</ul>
          </div>`;

          let createShelf = '<hr class="marginTop30"><div>' +
              "<h2><?php echo($data["storehouse"]["tab2"]["createShelf"]) ?></h2>" +
              '<input id="inputPlace" class="marginBottom10" type="number" placeholder="<?php echo($data["storehouse"]["tab1"]["shelf"]) ?>"><br>'+
              `<input type="button" onclick="createShelf(${entrepotV.id_entrepot})" value="${entrepot.msg["Create"]}"><br>`+
              "</div>"

        formattedEntrepot += createShelf

        return formattedEntrepot;
    }


    async function onload(){
        startLoading()

        await entrepot.connect()

        fillListing()



        stopLoading()
    }


    onload()

</script>
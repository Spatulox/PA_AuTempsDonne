<?php include("../includes/loadLang.php"); ?>

<!DOCTYPE html>
<html>
<head>

    <?php include("../includes/head.php"); ?>

    <title><?php echo($data["vehicle"]["title"]) ?></title>
</head>
<body>

<?php include("../includes/header.php"); ?>

<main>

    <div class="width80 marginAuto marginBottom30">
        <h1 class="textCenter"><?php echo($data["vehicle"]["title"]) ?></h1>

        <div class="tab flex flexAround nowrap">
            <button class="tablinks width100"
                    onclick="openTab('tab1')"><?php echo($data["vehicle"]["tab1"]["title"]) ?></button>
            <button class="tablinks width100"
                    onclick="openTab('tab2')"><?php echo($data["vehicle"]["tab2"]["title"]) ?></button>
            <button class="tablinks width100"
                    onclick="openTab('tab3')"><?php echo($data["vehicle"]["tab3"]["title"]) ?></button>
        </div>

        <div id="tab1" class="tabcontent">
            <h2><?php echo($data["vehicle"]["tab1"]["title"]) ?></h2>
            <table>
                <thead>
                <tr>
                    <td><?php echo $data["vehicle"]["tab1"]["id"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["desc"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["date"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["state"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["type"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["participateUsers"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["button"] ?></td>
                </tr>
                </thead>
                <tbody id="bodyAssign">
                <!-- Les lignes de données seront insérées ici par JavaScript -->
                </tbody>
            </table>
        </div>

        <div id="tab2" class="tabcontent">
            <h2><?php echo($data["vehicle"]["tab2"]["title"]) ?></h2>
            <table>
                <thead>
                <tr>
                    <td><?php echo $data["vehicle"]["tab1"]["id"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["desc"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["date"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["state"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["type"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["participateUsers"] ?></td>
                </tr>
                </thead>
                <tbody id="">
                <!-- Les lignes de données seront insérées ici par JavaScript -->
                </tbody>
            </table>
        </div>

        <div id="tab3" class="tabcontent">
            <h2><?php echo($data["vehicle"]["tab3"]["title"]) ?></h2>
            <table>
                <thead>
                <tr>
                    <td><?php echo $data["vehicle"]["tab1"]["id"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["desc"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["date"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["state"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["type"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["participateUsers"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["button"] ?></td>
                    <td><?php echo $data["vehicle"]["tab1"]["buttonD"] ?></td>
                </tr>
                </thead>
                <tbody id="">
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

    function onload() {
        openTab('tab1')
    }

    onload()

</script>
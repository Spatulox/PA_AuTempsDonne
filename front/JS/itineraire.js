const btn = document.getElementById("btnItineraire")

//let googleApiKey = 'AIzaSyC9WzDphICufUy1vaD1xjwhK3cI7pWJi9c';

function initMap(){

}

btn.addEventListener("click", async () => {
    const address = {
        "address": [3, 4, 5, 6, 7, 3]
    };
    const response = await fetch("http://localhost:8081/index.php/trajet", optionPost(address));
    const data = await response.json();

    // Save id of the address as values. The key of the object if the name of the address
    const interData = {}
    for (const dataKey in data.addresse) {

        interData[data.addresse[dataKey]] = address.address[dataKey]

    }

    const sortedAddresses = [];
    const startAddress = data.addresse[0]; // Adresse de l'entrepôt de départ
    sortedAddresses.push(startAddress);

    const endAddress = data.addresse[data.addresse.length - 1]; // Adresse de l'entrepôt d'arrivée

    const intermediateAddresses = data.addresse.slice(1, data.addresse.length - 1); // Adresses de passage intermédiaires

    while (intermediateAddresses.length > 0) {
        let origin = sortedAddresses[sortedAddresses.length - 1];

        let shortestDistance = Infinity;
        let closestAddress = null;
        //console.log("-------------------------------");
        //console.log(`Origin: ${origin}`);

        for (let i = 0; i < intermediateAddresses.length; i++) {
            const destination = intermediateAddresses[i];
            const distance = await getDistance(origin, destination);
            //console.log(destination, distance);

            if (distance < shortestDistance) {
                shortestDistance = distance;
                closestAddress = destination;
                //console.log(`Nouvelle adresse la plus proche: ${closestAddress} (distance: ${shortestDistance})`);
            }
        }

        if (closestAddress !== null) {
            sortedAddresses.push(closestAddress);
            //console.log(`Ajout ${closestAddress} dans sortedAddresses`);
        } else {
            //console.log('Aucune adresse proche');
        }

        const indexToRemove = intermediateAddresses.indexOf(closestAddress);
        if (indexToRemove !== -1) {
            intermediateAddresses.splice(indexToRemove, 1);
        }
        //console.log(`Adresses restantes: ${intermediateAddresses}`);
    }

    sortedAddresses.push(endAddress); // Ajouter l'adresse de l'entrepôt d'arrivée à la fin
    console.log("Sorted addresses:", sortedAddresses);


    // Create the data to send it to the api
    const dataToSend = {}
    let array = []

    // Get the id (values) for the address (key)
    for (const addressKey in sortedAddresses) {
        array.push(interData[sortedAddresses[addressKey]])

        console.log(sortedAddresses[addressKey])

    }

    dataToSend["address"] = array

    // API AUTEMPDONNEE
    const response2 = awaitfetch(endpointDuTrajet, optionPost(dataToSend))

    if(response2.ok){
        showPopup("Le trajet a été crée")
    } else {
        const data = await response2.json();

        if (data.hasOwnProperty("message")){
            showPopup(data.message)
        }
    }



});

function getCookie(cookieName) {
    const cookies = document.cookie.split(';');

    for (let i = 0; i < cookies.length; i++) {
        let cookie = cookies[i].trim();
        if (cookie.startsWith(cookieName + '=')) {
            return cookie.substring(cookieName.length + 1);
        }
    }

    return null;
}


function optionGet(){

    const apikey = getCookie("apikey")

    if(apikey == null){
        alert('Pas d\'apikey dans les cookies :/')
        return false
    }

    const options = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'apikey': apikey
        }
    };
    return options
}

/**
 * Create the header option for a POST request
 * @param data
 * @returns {{headers: {apikey: string, "Content-Type": string}, method: string, body: string}}
 */
function optionPost(data) {

    const apikey = getCookie("apikey")

    if(apikey == null){
        alert('Pas d\'apikey dans les cookies :/')
        return false
    }

   const options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'apikey': apikey
        },
        body: JSON.stringify(data)
    };

    return options;
}

/**
 * Create the header option for a PUT request
 * @returns {{headers: {apikey: string, "Content-Type": string}, method: string}}
 */
function optionPut(data) {

    const apikey = getCookie("apikey")

    if(apikey == null){
        alert('Pas d\'apikey dans les cookies :/')
        return false
    }

    const options = {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'apikey': apikey
        },
        body: JSON.stringify(data)
    };

    return options;
}

/**
 * Create the header option for a DELETE request
 * @returns {{headers: {apikey: string, "Content-Type": string}, method: string}}
 */
function optionDelete(data) {

    const apikey = getCookie("apikey")

    if(apikey == null){
        alert('Pas d\'apikey dans les cookies :/')
        return false
    }

    const options = {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'apikey': apikey
        }
    };

    return options;
}
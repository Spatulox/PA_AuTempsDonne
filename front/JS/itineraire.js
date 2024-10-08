const btn = document.getElementById("btnItineraire")

//let googleApiKey = 'AIzaSyC9WzDphICufUy1vaD1xjwhK3cI7pWJi9c';

function initMap() {

}

//btn.addEventListener("click", async () => {
async function calcSpeedAddress(addressData, id_vehicule) {
    const address = {
        "address": addressData,
        "id_vehicule":id_vehicule
    };

    const response = await fetch(ipAddressApi+"/trajet", optionPost(address));

    if(!response.ok){
        console.log(await response.text())
        return false
    }
    const data = await response.json();

    // Save id of the address as values. The key of the object if the name of the address
    const interData = {}
    for (const dataKey in data.addresse) {

        interData[data.addresse[dataKey]] = address.address[dataKey]

    }

    console.log(data)
    //SAME ADDRESS AS FIRST
    const sortedAddresses = [];
    const startAddress = data.addresse[0]; // Adresse de l'entrepôt de départ
    sortedAddresses.push(startAddress);

    const endAddress = data.addresse[data.addresse.length - 1]; // Adresse de l'entrepôt d'arrivée

    const intermediateAddresses = data.addresse.slice(1, data.addresse.length - 1); // Adresses de passage intermédiaires

    while (intermediateAddresses.length > 0) {
        let origin = sortedAddresses[sortedAddresses.length - 1];

        let shortestDistance = Infinity;
        let closestAddress = null;

        for (let i = 0; i < intermediateAddresses.length; i++) {
            const destination = intermediateAddresses[i];
            console.log(origin, destination)
            const distance = await getDistance(origin, destination);

            if (distance < shortestDistance) {
                shortestDistance = distance;
                closestAddress = destination;
            }
        }

        if (closestAddress !== null) {
            sortedAddresses.push(closestAddress);
        } else {
            alert('Aucune adresse proche');
        }

        const indexToRemove = intermediateAddresses.indexOf(closestAddress);
        if (indexToRemove !== -1) {
            intermediateAddresses.splice(indexToRemove, 1);
        }
    }

    sortedAddresses.push(endAddress); // Ajouter l'adresse de l'entrepôt d'arrivée à la fin

    // Create the data to send it to the api
    const dataToSend = {}
    let array = []

    // Get the id (values) for the address (key)
    for (const addressKey in sortedAddresses) {
        array.push(interData[sortedAddresses[addressKey]])
    }

    dataToSend["address"] = array
    dataToSend["id_vehicule"] = id_vehicule

    let trajet = new TrajetAdmin()

    await trajet.connect()
    await trajet.createTrajet(dataToSend)

    return sortedAddresses
}

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


function optionGet() {

    const apikey = getCookie("apikey")

    if (apikey == null) {
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

    if (apikey == null) {
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

    if (apikey == null) {
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
function optionDelete() {

    const apikey = getCookie("apikey")

    if (apikey == null) {
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
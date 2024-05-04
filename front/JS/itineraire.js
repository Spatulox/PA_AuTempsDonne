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

    const sortedAddresses = [];
    const startAddress = data.addresse[0]; // Adresse de l'entrepôt de départ
    sortedAddresses.push(startAddress);

    const endAddress = data.addresse[data.addresse.length - 1]; // Adresse de l'entrepôt d'arrivée

    const intermediateAddresses = data.addresse.slice(1, data.addresse.length - 1); // Adresses de passage intermédiaires

    while (intermediateAddresses.length > 0) {
        let origin = sortedAddresses[sortedAddresses.length - 1];

        let shortestDistance = Infinity;
        let closestAddress = null;
        console.log("-------------------------------");
        console.log(`Origin: ${origin}`);

        for (let i = 0; i < intermediateAddresses.length; i++) {
            const destination = intermediateAddresses[i];
            const distance = await getDistance(origin, destination);
            console.log(destination, distance);

            if (distance < shortestDistance) {
                shortestDistance = distance;
                closestAddress = destination;
                console.log(`Nouvelle adresse la plus proche: ${closestAddress} (distance: ${shortestDistance})`);
            }
        }

        if (closestAddress !== null) {
            sortedAddresses.push(closestAddress);
            console.log(`Ajout ${closestAddress} dans sortedAddresses`);
        } else {
            console.log('Aucune adresse proche');
        }

        const indexToRemove = intermediateAddresses.indexOf(closestAddress);
        if (indexToRemove !== -1) {
            intermediateAddresses.splice(indexToRemove, 1);
        }
        console.log(`Adresses restantes: ${intermediateAddresses}`);
    }

    sortedAddresses.push(endAddress); // Ajouter l'adresse de l'entrepôt d'arrivée à la fin
    console.log("Sorted addresses:", sortedAddresses);
});



async function  fetchSync(url, options){

    if(options === false){
        popup("Impossible de se connecter, veuillez entrer vos identifiant sur la page de connexion")
        return false
    }

    const response = await fetch(url, options)

    if(response.ok){
        const message = await response.json()
        if(message.hasOwnProperty("message")){
            popup(message.message)
            return true
        }
        return message
    }
    else{
        const text = await response.json()
        alertDebug(`Impossible de réaliser cette requête (${response.statusText}) : ${response.url}`)
        if(text.hasOwnProperty("message")) {
            alertDebug(text.message)
            popup(text.message)
        }
        return false
    }

}

function compareAnswer(response, msg = null){
    if(response === false && msg != null){
        alertDebug(msg)
        popup(msg)
        return false
    }
    else if(response === false && msg != null){
        return false
    }
    else if (response === true){
        return false
    }
    return response
}

function optionGet(){

    const options = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'apikey': `8f96e6e91f136ea4ee7150d8a656cc57ab1de2021dac5e78e3a79242cf88c055`
        }
    };
    return options
}

function optionGetNoCors(){

    const options = {
        method: 'GET',
        mode: "no-cors",
        headers: {
            'Content-Type': 'application/json'
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
   const options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'apikey': `8f96e6e91f136ea4ee7150d8a656cc57ab1de2021dac5e78e3a79242cf88c055`
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
  const options = {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'apikey': `8f96e6e91f136ea4ee7150d8a656cc57ab1de2021dac5e78e3a79242cf88c055`
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

    const options = {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'apikey': `8f96e6e91f136ea4ee7150d8a656cc57ab1de2021dac5e78e3a79242cf88c055`
        },
        body: JSON.stringify(data)
    };

    return options;
}
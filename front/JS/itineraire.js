



const btn = document.getElementById("btnItineraire")

btn.addEventListener("click", async ()=>{

    const response = await fetch("http://localhost:8081/index.php/trajet", optionGet())

    const data = await response.json()
    console.log(data)

})





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
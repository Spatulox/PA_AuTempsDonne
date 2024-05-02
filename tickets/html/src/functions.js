function showPopup(message) {
    console.log(message)
    document.getElementById("errorMessage").textContent = message;
    document.getElementById("errorPopup").classList.add("active");

    setTimeout(()=>{
        closePopup()
    }, 5000)
}

function closePopup() {
    document.getElementById("errorPopup").classList.remove("active");
}

//
//-------------------------------------------------------------------------------------
//

// Function to redirect to the menu
function redirectToMainMenu(){
    window.location.href = `/`;
}

// Function to redirect to the reservation list
function redirectToMainList() {
    window.location.href = `/list`;
}

// Function to redirect to create a reservation
function redirectToCreateTicket() {
    window.location.href = `/list/create`;
}

// Function to redirect to the solo reservation corresponding to the id entered
function redirectToIdList(id) {
    window.location.href = `/list?idTicket=${id}`;
}

//
// ---------------------------------------------------------------------------------------------------------------------
//

async function creerTicket() {
    // Récupérer les valeurs du formulaire
    const description = document.getElementById("description").value;
    const categorie = document.querySelector('select[name="categorie"]').value;

    if(categorie === ""){
        showPopup("Vous devez choisir une catégorie")
        return
    }

    // Prendre le cookie de l'apikey
    //const idUser = document.querySelector('input[name="idUser"]').value;

    // Préparer les données à envoyer
    const form = {
        "description": description,
        "categorie": categorie
    }

    console.log(form)

    let response = await fecthSynch("/create", optionPost(form))
}

//
// ---------------------------------------------------------------------------------------------------------------------
//


async function fecthSynch(url, data){

    try {
        const response = await fetch(url, data);

        if (response.ok) {

            try{
                console.log(response)
                const message = await response.text();
                showPopup(message)
            }
            catch (err){
                console.log(err)
            }

            return response
        } else {
            const errorMessage = await response.text();
            showPopup('Erreur' + errorMessage);
            return false
        }
    } catch (error) {
        // Erreur de réseau
        showPopup('Erreur lors de la requête :', error);
        return false
    }
}

//
//-------------------------------------------------------------------------------------
//

function optionPost(formData){
    return {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'apikey': '8f96e6e91f136ea4ee7150d8a656cc57ab1de2021dac5e78e3a79242cf88c055'
        },
        body: JSON.stringify(formData)
    }
}

//
//-------------------------------------------------------------------------------------
//

function optionGet(formData){
    return {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'apikey': '1234'
        }
    }
}

//
//-------------------------------------------------------------------------------------
//

// Called in every page when they finisehd to load, to detect a message in the url
const urlParams = new URLSearchParams(window.location.search);

if (urlParams.has('message')) {
    const messageValue = urlParams.get('message');
    showPopup(messageValue)
}

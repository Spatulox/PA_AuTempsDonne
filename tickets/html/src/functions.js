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
    let url1 = window.location.href

    console.log(url1)
    let url = ""

    url = url1.split("?")

    const indexMessage = url.findIndex(param => param.includes("message"));

    // Suppression de l'élément si trouvé
    if (indexMessage !== -1) {
        url.splice(indexMessage, 1);
    }

    // Redirect without any filter
    let newUrl = ""
    if(url.length > 1) {

        if(url[0].includes("conversation")){
            url[0] = url[0].replace("conversation", "/list?me=true")
        }

        url.pop()
        newUrl = url.join("/")
        window.location.href = `${newUrl}`
        return

    }

    // Redirect to the parent page
    url = url1.split("/")

    if(url.length >= 4){
        url.pop()
        newUrl = url.join("/")
        window.location.href = `${newUrl}`
    }



}

async function redirectAddMessage(){

    const messageToSend = document.getElementById("messageToSend").value
    console.log(messageToSend)

    const id_ticket = document.getElementById("id_ticket").innerHTML
    console.log(id_ticket)

    const data = {
        "id_ticket":id_ticket,
        "message":messageToSend
    }
    const response = await fecthSynch("/message", optionPost(data))

    window.location.reload()

    scrollToBottomOfMessages();
}

// Function to redirect to the reservation list
function redirectToConversation(id) {
    window.location.href = `/conversation?idTicket=${id}`;
}

// Function to redirect to the solo reservation corresponding to the id entered
function redirectToIdList(id) {
    window.location.href = `/list?idTicket=${id}`;
}

async function redirectToClaim(id){

   const response = await fecthSynch(`/claim?idTicket=${id}`, optionGet())

    if(typeof(response) == "string"){
        window.location.reload()
    }
}

async function redirectToCloseList(id){
    const response = await fecthSynch(`/close?idTicket=${id}`, optionGet())

    if(typeof(response) == "string"){
        window.location.reload()
    }
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

    let response = await fecthSynch("/create", optionPost(form))

    if(typeof(response) == "string"){
        window.location.href = "/list"
    }
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
                return message
            }
            catch (err){
                console.log(err)
            }

            return response
        } else {
            const errorMessage = await response.text();
            showPopup('Erreur : ' + errorMessage);
            return false
        }
    } catch (error) {
        // Erreur de réseau
        showPopup('Erreur lors de la requête : ', error);
        return false
    }
}

//
//-------------------------------------------------------------------------------------
//

function optionPost(formData){

    let cookie = getCookie("apikey")
    if(cookie === null){
        alert("Le Cookie ets nul, on ne peux pas s'authentifier")
        window.location.href("http://localhost:8083/HTML/signup_login.php")
        return
    }

    return {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'apikey': cookie
        },
        body: JSON.stringify(formData)
    }
}

//
//-------------------------------------------------------------------------------------
//

function optionGet(){

    let cookie = getCookie("apikey")
    if(cookie === null){
        alert("Le Cookie est nul, on ne peux pas s'authentifier")
        window.location.href("http://localhost:8083/HTML/signup_login.php")
        return
    }

    return {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'apikey': cookie
        }
    }
}

//
//-------------------------------------------------------------------------------------
//

function getCookie(name) {
    const cookies = document.cookie;

    const cookieArray = cookies.split(';');

    for (let i = 0; i < cookieArray.length; i++) {
        const cookie = cookieArray[i].trim();

        if (cookie.startsWith(name + '=')) {
            return cookie.substring(name.length + 1);
        }
    }

    return null;
}

//
//-------------------------------------------------------------------------------------
//

function scrollToBottomOfMessages() {
    const groupMessages = document.querySelectorAll('.groupMessage');
    groupMessages.forEach(message => {
        message.scrollIntoView({ behavior: 'smooth', block: 'end' });
    });
}

//
//-------------------------------------------------------------------------------------
//






// Called when a page is loaded
const urlParams = new URLSearchParams(window.location.search);
const groupMessage = document.getElementsByClassName('groupMessage')

// check if there is a "message" in the url
if (urlParams.has('message')) {
    const messageValue = urlParams.get('message');
    showPopup(messageValue)
}

// Detect if it's the conversation page
if (groupMessage.length > 0) {
    setInterval((async ()=>{
        await refreshMessages()

        scrollToBottomOfMessages()
    }), 5000)
}

// This is to scrollBottom for conversation part
window.addEventListener('load', scrollToBottomOfMessages);

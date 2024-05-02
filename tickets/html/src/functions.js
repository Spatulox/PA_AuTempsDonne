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
    window.location.href = `/reservation`;
}

// Function to redirect to create a reservation
function redirectToCreateReserv() {
    window.location.href = `/reservation/create`;
}

function redirectToCreateRoom(){
    window.location.href = `/salle/create`;
}

// Function to redirect to the solo reservation corresponding to the id room entered
function redirectToRoomList() {
    const idRoom = document.querySelector('input[name="idRoom"]').value;
    window.location.href = `/reservation/list?idRoom=${idRoom}`;
}

// Function to redirect to the solo reservation corresponding to the date entered
function redirectToDateList() {
    const idRoom = document.querySelector('input[name="idDate"]').value;
    window.location.href = `/reservation/list?idDate=${idRoom}`;
}

// Function to redirect to the solo reservation corresponding to the id entered
function redirectToIdList(id) {
    window.location.href = `/reservation/list?idReserv=${id}`;
}

// Function to delete a reservation
// Then redirect to the list
function redirectDelete(id){
    fetch(`/reservation/cancel?idReserv=${id}`, {
        method: 'GET'
    })
    .then(response => {
        if (response.ok) {
            response.text().then(message => {
                window.location.href = `/reservation?message=${message}`;
                //showPopup(message);
            });
        } else {
            response.text().then(errorMessage => {
                showPopup(errorMessage);
            });
        }
    })
    .catch(error => {
        showPopup('Erreur lors de la requête :', error);
    });
}

function redirectDeleteSalles(idSalle){
    fetch(`/salle/cancel?idSalle=${idSalle}`, {
        method: 'GET'
    })
        .then(response => {
            if (response.ok) {
                response.text().then(message => {
                    window.location.href = `/salle/getRoomAll?message=${message}`;
                    //showPopup(message);
                });
            } else {
                response.text().then(errorMessage => {
                    showPopup(errorMessage);
                });
            }
        })
        .catch(error => {
            showPopup('Erreur lors de la requête :', error);
        });
}

// Function to update the state of the reservation
// Then redirect to the list
function redirectUpdate(id){

    // Récupérer l'élément select
    var selectElement = document.querySelector('select[name="etat"]');

    // Récupérer la valeur sélectionnée
    var etat = selectElement.value;

    fetch(`/reservation/update?idReserv=${id}?etat=${etat}`, {
        method: 'GET'
    })
    .then(response => {
        if (response.ok) {
            response.text().then(message => {
                window.location.href = `/reservation/list?idReserv=${id}`;
            });
        } else {
            response.text().then(errorMessage => {
                showPopup(errorMessage);
            });
        }
    })
    .catch(error => {
        showPopup('Erreur lors de la requête :', error);
    });
}

//
//-------------------------------------------------------------------------------------
//

// Used in the creerReservation.html file
// Get all the rooms available when creating a reservation
async function getAllRoomAvailable() {

    const horaire_start_date = document.getElementById("horaire_start_date").value
    const horaire_start_time = document.getElementById("horaire_start_time").value

    const horaire_end_date = document.getElementById("horaire_end_date").value
    const horaire_end_time = document.getElementById("horaire_end_time").value

    const ulCreateReservation = document.getElementById("ulCreateReservation")

    if (horaire_start_date == null || horaire_start_time == null || horaire_end_date == null || horaire_end_time == null){
        return
    }

     // Vérifier le format de la date
    let dateRegex = /^\d{4}-\d{2}-\d{2}$/;
    if (!dateRegex.test(horaire_start_date) || !dateRegex.test(horaire_end_date)) {
        showPopup("La date doit être au format AAAA-MM-JJ");
        return;
    }

    // Vérifier le format de l'heure
    let timeRegex = /^\d{2}:\d{2}$/;
    if (!timeRegex.test(horaire_start_time) || !timeRegex.test(horaire_end_time)) {
        showPopup("L'heure doit être au format HH:MM");
        return;
    }

    startDateTime = horaire_start_date + " " + horaire_start_time
    endDateTime = horaire_end_date + " " + horaire_end_time


    const data = {
        startDateTime: startDateTime,
        endDateTime: endDateTime
    };

    try {
        const response = await fetch('/salle/getAllAvail', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });


        if (response.ok) {
            const data = await response.json();

            const ulCreateReservation = document.getElementById('ulCreateReservation');
            ulCreateReservation.innerHTML = ""

            if(data.length > 0){
                data.forEach(salle => {
                    let li = document.createElement("li");
                    let tmp = `${salle.IdSalle} : ${salle.NomSalle} (${salle.PlaceSalle} Places)`;
                    li.textContent = tmp;
                    ulCreateReservation.appendChild(li);
                    console.log(tmp);
                });
            }
            else{
                ulCreateReservation.innerHTML = "Veuillez choisir une date et heure de départ et de fin pour voir les salles disponibles"
            }



            
        } else {
            const errorMessage = await response.text();
            showPopup(errorMessage);
        }
    } catch (error) {
        showPopup('Erreur lors de la requête :', error);
    }
}

//
//-------------------------------------------------------------------------------------
//

// akt the /reservation/export to export the BDD in a json file
async function exportReservJson(){
    try {
        const response = await fetch('/reservation/export', {
            method: 'GET',
        });


        if (response.ok) {
            const msg = await response.text();
            showPopup(msg)

            const buttonAskToDownload = document.getElementById("buttonAskToDownload")
            buttonAskToDownload.innerHTML = "Télécharger le fichier"
            buttonAskToDownload.setAttribute('onclick', 'dataDownload()');


        } else {
            const errorMessage = await response.text();
            showPopup(errorMessage);
        }
    } catch (error) {
        showPopup('Erreur lors de la requête :', error);
    }
}

//
//-------------------------------------------------------------------------------------
//

// Used in interface after the click to export json
// Used in export.json, line 185
// The server send the file to the client
function dataDownload(){
    fetch('/download')
        .then(response => response.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.setAttribute('download', 'data.json');
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);

            // Demander la confirmation à l'utilisateur
            showYoutubePopup();
        });
}

//
//-------------------------------------------------------------------------------------
//

/*
async function updloadJson(){
    const form = document.getElementById("upload-form")
    const fileInput = document.getElementById("file-input")
    const file = fileInput.files[0]

    const formData = new FormData()
    formData.append("reservation", file)

    try{
        const data = {
            method: "POST",
            body: formData
        }

        const response = await fetch("/reservation/import", data)

        if(response.ok){
            showPopup(await response.text())
        }
        else{
            showPopup(await response.text())
        }
    }
    catch (err){
        showPopup("Error : "+err)
    }
}
*/

//
//-------------------------------------------------------------------------------------
//

// Show the video of M. Sananes singing Rap in suit
function showYoutubePopup() {
    // Créer la popup
    const popup = document.createElement('div');
    popup.classList.add('popup');

    // Ajouter l'iframe YouTube
    const iframe = document.createElement('iframe');
    iframe.src = 'https://www.youtube.com/embed/L0TB1IkhVds?autoplay=1';
    iframe.width = '560';
    iframe.height = '315';
    iframe.frameborder = '0';
    iframe.allow = 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture';
    iframe.allowfullscreen = true;

    popup.appendChild(iframe);
    document.body.appendChild(popup);

    document.addEventListener('click', (event) => {
        if (!popup.contains(event.target)) {
            popup.style.display = 'none';
        }
    });
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

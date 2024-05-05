// functions.js

async function connexion() {

    const email = document.getElementById("emailCo").value;
    const password = document.getElementById("motdepasseCo").value;

    //Première mannière de se connecter (avec email et mot de passe)
    const premiereManiere = (async ()=>{
        const user = await new User(email, password)
        await user.connect()
    })

    // Second manière de se connecter : (sans email ni password)
    const secondManiere = (async ()=> {
        const user = await new User();
        if(user.apikey == null){
            return
        }
        await user.connect()
        return user
    })

    const user = await new User(email, password)
    //const user = await new User()
    if(!await user.connect()){
        return
    }

    if(window.location.href.includes("?return=")){
        redirect(getParamFromUrl("return"))
        return
    }

    redirect("./moncompte.php")

    

    // ----------- User -----------
    //console.log(await user.me())
    //console.log(await user.me(true))
    //console.log(await user.getUser(id_user))
    //console.log(await user.getAllUser())
    //console.log(await user.getWaitingUser())
    //await user.updateUser(lastname, firstname, phone, email)
    //await user.deleteUser()
    //await user.deleteUser(id_user)
    //user.logout()

    // --------- Planning ---------
    //console.log(await user.planning())
    //console.log(await user.allPlanning())

    // --------- Entrepot ---------
    //console.log(await user.getEntrepot())
    //console.log(await user.getEntrepot(1))
    //await user.createEntrepot("CoucouTest", "ChezToi,PasChezMoi")
    //await user.updateEntrepot(3, "coucouRIP", null)
    //await user.updateEntrepot(4, null, "coucouRIP2")
    //await user.updateEntrepot(5, "test", "test2")



}

async function deconnection(){
    const user = await new User()
    await user.logout()
    redirect("./index.php")
}

async function signup(){

    let getSelectedValue = (()=>{
        const radios = document.getElementsByName('statut');
        for (let i = 0; i < radios.length; i++) {
            if (radios[i].checked) {
                return radios[i].value
            }
        }
        return false
    })



    const nom = document.getElementById("nomInsc").value
    const prenom = document.getElementById("prenomInsc").value
    const telephone = document.getElementById("phoneInsc").value

    const email = document.getElementById("emailInsc").value
    const password = document.getElementById("motdepasseInsc").value

    if(!nom || !prenom || !email || !password){
        popup("Veuillez remplir le formulaire...")
        return
    }

    const role = getSelectedValue()
    if(role === false){
        popup("Vous devez spécifier un rôle :/")
        return
    }

    const data = {
        "nom": nom,
        "prenom": prenom,
        "telephone": telephone,
        "email" : email,
        "mdp": password,
        "role": role
    }
    console.log(data)

    const options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    };


    // Fetch the api

    const response = await fetch("http://localhost:8081/index.php/user", options)

    if(!response.ok){
        const text = await response.json()
        alertDebug(`Impossible de réaliser cette requête (${response.statusText}) : ${response.url}`)
        if(text.hasOwnProperty("message")) {
            alertDebug(text.message)
            popup(text.message)
        }
        return false
    }

    const message = await response.json()
    if(message.hasOwnProperty("message")){
        popup(message.message)
        return true
    }

    const user = new User(email, password)
    await user.connect()
    user.printUser()

    redirect("./moncompte.php?message=Votre compte est en attente de validation auprès de la modération")

}


async function myAccount(){
    const c_nom = document.getElementById("c_nom")
    const c_prenom = document.getElementById("c_prenom")
    const c_email = document.getElementById("c_email")
    const c_telephone = document.getElementById("c_telephone")
    const c_date_inscription = document.getElementById("c_date_inscription")
    const c_entrepot = document.getElementById("c_entrepot")
    const c_role = document.getElementById("c_role")

    const user = new User()
    await user.connect()


    c_nom.innerHTML = "Nom : " + user.nom
    c_prenom.innerHTML = "Prénom : " + user.prenom
    c_email.innerHTML = "Email : " + user.email
    c_telephone.innerHTML = "Téléphone : " + user.telephone
    c_date_inscription.innerHTML = "Date inscription : " + user.date_inscription
    c_entrepot.innerHTML = "Entrepot : " + user.entrepotString
    c_role.innerHTML = "Role : " + user.roleString

    if(user.index == 3){
        const h1 = document.getElementsByTagName("h1")[0]
        h1.innerHTML += " (En attente de validation)"
        popup("Votre compte est en attente de validation par la modération")
    }
}

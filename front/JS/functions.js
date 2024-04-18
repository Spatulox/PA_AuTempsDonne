// functions.js
function redirect(page, message = null) {
    if (message) {
        window.location.href = page + "?message=" + message;
    } else {
        window.location.href = page;
    }
}

async function connexion() {

    const email = document.getElementById("emailCo").value;
    const password = document.getElementById("motdepasseCo").value;

    //Première mannière de se connecter (avec email et mot de passe)
    const premiereManiere = (async ()=>{
        const user = await new User(email, password)//.connect();
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
    if(!await user.connect()){
        popup("Impossible de se connecter :/ 🔁")
        return
    }

    redirect("moncompte.php")
    return

    //alert("Update user")


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



}

async function deconnection(){
    const user = await new User()
    await user.logout()
    redirect("./index.php")
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
    c_entrepot.innerHTML = "Entrepot : " + user.entrepot
    c_role.innerHTML = "Role : " + user.roleString
}

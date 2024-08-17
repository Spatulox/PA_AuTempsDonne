    // functions.js

async function connexion() {
    startLoading()

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
        stopLoading()
        return user
    })

    const user = await new User(email, password)
    //const user = await new User()
    if(!await user.connect()){
        stopLoading()
        return
    }

    if(window.location.href.includes("?return=")){
        redirect(getParamFromUrl("return"))
        stopLoading()
        return
    }

    redirect("./moncompte.php")
}

async function deconnection(){
    const user = await new User()
    await user.logout()
    redirect("./index.php")
}


async function myAccount(){
    startLoading()
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
    stopLoading()
}

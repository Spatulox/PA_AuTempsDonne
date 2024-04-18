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



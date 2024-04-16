// functions.js
function redirect(page, message = null) {
    if (message) {
        //window.location.href = page + "?message=" + message;
    } else {
        //window.location.href = page;
    }
}

async function connexion() {

    const email = document.getElementById("emailCo").value;
    const password = document.getElementById("motdepasseCo").value;

    //const user = await new User(email, password)//.connect();
    const user = await new User();
    await user.connect()

    console.log(await user.apikey)
    console.log(await user.nom)

    console.log(await user.me())

    //const plann = await user.planning()
    //const test = user.apikey
    //const test2 = user.connect()
    //const test3 = user.


}


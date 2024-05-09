// Spatulox

function redirect(page, message = null) {
    if (message) {
        window.location.href = page + "?message=" + message;
    } else {
        window.location.href = page;
    }
}

/**
 * Retun the thing in the url corresponding to the param
 * @param param
 * @returns {string}
 */
function getParamFromUrl(param){
    let data = ""
    try{
        data = window.location.href.split("?"+param+"=")[1].split("?")[0]
    } catch {
        data = false
    }
    return data
}

function idExistInPage(id){
    const idExist = document.getElementById(id)

    if(idExist){
        return true
    } else {
        return false
    }
}

function createButton(value, id = null, name = null){
    const button = document.createElement("button")
    button.type = "button"
    button.innerHTML = value

    if(name != null){
        button.name = name
    }
    if (id != null){
        button.id = id
    }
    return button
}

/**
 * Set a cookie
 * @param name name of the cookie
 * @param value value of the cookie
 * @param days number of days for the cookie to stay
 * @param sameSite lax by default
 */
function setCookie(name, value, days, sameSite = 'Lax') {
    let expires = "";
    if (days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }

    let cookieString = name + "=" + (value || "") + expires + "; path=/";

    // Ajout de l'attribut SameSite
    if (sameSite === 'None') {
        cookieString += "; SameSite=None; Secure";
    } else {
        cookieString += "; SameSite=" + sameSite;
    }
    document.cookie = cookieString;
}

/**
 * Get a cookie
 * @param cookieName : Name of the cookie to get
 * @returns {null|string}
 */
function getCookie(cookieName) {
    const cookies = document.cookie.split(';');

    for (let i = 0; i < cookies.length; i++) {
        let cookie = cookies[i].trim();
        if (cookie.startsWith(cookieName + '=')) {
            return cookie.substring(cookieName.length + 1);
        }
    }

    return null;
}
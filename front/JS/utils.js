// Spatulox

function redirect(page, message = null) {
    if (message) {
        window.location.href = page + "?message=" + message;
    } else {
        window.location.href = page;
    }
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
class General {

    constructor() {
        this.lang = getCookie("lang")
        this.msg = dico[this.lang]
    }



    /**
     * Use to fetch the api
     * @param url : Specifie it to request an endpoint
     * @param options option get by this.optionGet() or this.optionPost()
     * @returns {Promise<any|boolean>}
     */
    async fetchSync(url, options, showMessage = true) {

        if (options === false) {
            popup("Impossible de se connecter, veuillez entrer vos identifiant sur la page de connexion")
            return false
        }

        const response = await fetch(url, options)

        if (response.ok) {
            const message = await response.json()
            if (message.hasOwnProperty("message") && showMessage === true) {
                popup(message.message)
                return
            }
            return message
        } else {
            const text = await response.json()
            if(showMessage === true){
                alertDebug(`Impossible de réaliser cette requête (${response.statusText}) : ${response.url}`)
            }
            if (text.hasOwnProperty("message")) {
                if(showMessage === true){
                    popup(text.message)
                }
            }
            return false
        }

    }

    compareAnswer(response, msg = null) {
        if (response === false && msg != null) {
            //alertDebug(msg)
            popup(msg)
            return false
        } else if (response === true) {
            return false
        }
        return response
    }


    /**
     * Set a cookie
     * @param name name of the cookie
     * @param value value of the cookie
     * @param days number of days for the cookie to stay
     * @param sameSite lax by default
     */
    setCookie(name, value, days, sameSite = 'Lax') {
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
    getCookie(cookieName) {
        const cookies = document.cookie.split(';');

        for (let i = 0; i < cookies.length; i++) {
            let cookie = cookies[i].trim();
            if (cookie.startsWith(cookieName + '=')) {
                return cookie.substring(cookieName.length + 1);
            }
        }

        return null;
    }

    /**
     * Delete the specified cookie
     * @param cookieName
     */
    deleteCookie(cookieName) {
        // Définir la date d'expiration à une date passée (1er janvier 2000)
        document.cookie = `${cookieName}=; expires=Thu, 01 Jan 2000 00:00:00 UTC; path=/;`

    }


    /**
     * Create the header option for GET request
     * @returns {{headers: {apikey: string, "Content-Type": string}, method: string}}
     */
    optionGet() {

        if (this.apikey === "hidden") {
            this.loginApi()
        }

        const options = {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'apikey': `${this.apikey}`
            }
        };
        return options
    }

    /**
     * Create the header option for a POST request
     * @param data
     * @returns {{headers: {apikey: string, "Content-Type": string}, method: string, body: string}}
     */
    optionPost(data) {
        if (this.apikey === "hidden") {
            this.loginApi();
        }

        const options = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'apikey': `${this.apikey}`
            },
            body: JSON.stringify(data)
        };

        return options;
    }

    /**
     * Create the header option for a PUT request
     * @returns {{headers: {apikey: string, "Content-Type": string}, method: string}}
     */
    optionPut(data) {
        if (this.apikey === "hidden") {
            this.loginApi();
        }

        const options = {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'apikey': `${this.apikey}`
            },
            body: JSON.stringify(data)
        };

        return options;
    }

    /**
     * Create the header option for a DELETE request
     * @returns {{headers: {apikey: string, "Content-Type": string}, method: string}}
     */
    optionDelete() {
        if (this.apikey === "hidden" || this.apikey === null) {
            this.loginApi();
        }

        const options = {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'apikey': `${this.apikey}`
            },
            //body: JSON.stringify(data)
        };

        return options;
    }
}
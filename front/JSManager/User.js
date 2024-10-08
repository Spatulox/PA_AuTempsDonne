class User extends General{

    constructor(email = null, password = null) {
        super();
        this.adresse = ipAddressApi;
        this.apikey = null;
        this.id_user = null;
        this.nom = null;
        this.prenom = null;
        this.email = null;
        this.telephone = null;
        this.date_inscription = null;
        this.role = null;
        this.roleString = null
        this.password = null
        this.entrepot = null
        this.entrepotString = null
        this.index = null
        this.premiumDate = null
        this.premiumTime = null

        this.roleArray = ["roleBait", "Dirigeant", "Administrateur", "Bénévole", "Bénéficiaire", "Prestataire"]

        if (password === null || email === null) {
            this.loginApi()
        } else {
            this.email = email
            this.password = password
        }
    }

    //
    //----------------------------------CONNEXION--------------------------------------------------------------------------
    //

    /**
     * Create the user with the apikey saved in a cookie
     * @returns {boolean} - Returns true if the connection is successful, false otherwise.
     */
    loginApi() {
        let cookie = this.getCookie("apikey")

        let etagere = window.location.href.split("/")
        etagere = etagere[etagere.length -1]
        if(cookie == null && etagere !== "etagere.php"){
            // The message is only for debug, it gonna be deleted anyway
            alertDebug("Vous ne pouvez pas vous connecter (const user = new User() ) sans avoir un cookie, vous devez mettre l'email et le mot de passe")
            redirect("./signup_login.php?return=" + window.location.href)
            return false
        }
        this.apikey = cookie
        return true;
    }



    /**
     * Connect the user from to api with username and password.
     * @returns {boolean} - Returns true if the connection is successful, false otherwise.
     */
    async connect(){
        console.log("Connect")

        if(this.email == "" && this.password == "" && this.apikey == null){
            popup("You need to give the email and the password")
            return false
        }

        // If the apikey is inside the class
        // loginApi detect it and store it inside before this
        if(this.apikey != null){
            if(this.getBigCookie()){
                console.log("Cookies déjà existant")
                return true
            }
            console.log("Meh")
            await this.me(true)
            return true
        }

        // -------------------------------PEUT ETRE PAS OUF EN FAIT, JE SAIS PAS----------------------------- //
        if(this.getCookie("apikey")){
            console.log("Je passe là")
            this.apikey = this.getCookie("apikey")
            this.me(true)
            return true
        }

        // ??????????????????????????????????????????????????????????????????????
        /*// There is thing stored inside the class
        if(this.email == "" || this.password == ""){
          await this.me()
          return true
        }*/


        const data = {
            "email": this.email,
            "mdp": this.password
        };

        console.log("login")
        const rep = await this.fetchSync(this.adresse  + "/login", this.optionPost(data))
        if(!this.compareAnswer(rep)){
            return false

        }
        this.setBigCookie(rep)
        this.setVar(rep)
        this.setCookie("apikey", rep.apikey, 7);
        if(rep.premiumTime){
            this.setCookie("premiumTime", rep.premiumTime, 7)
            this.setCookie("premiumDate", rep.premiumDate, 7)
        }
        await this.myEntrepot()
        return true
    }


    //
    //----------------------------------METHODES--------------------------------------------------------------------------
    //




    //------------------------------------USER------------------------------------
    /**
     *
     * @param forceLoad null by default, can be set to 1 to request the API to update informations
     * @returns Data of user
     */
    async me(forceLoad = false){

        if(this.nom == null || forceLoad === true){
            const rep = await this.fetchSync(this.adresse  + "/user", this.optionGet())
            if(!this.compareAnswer(rep, "Impossible de récupérer vos informations")){
                return false
            }
            this.setVar(rep)
            this.setBigCookie(rep)
            await this.myEntrepot()
            return rep
        }
        else{
            return {
                apikey: "hidden",
                nom: this.nom,
                prenom: this.prenom,
                email: this.email,
                telephone: this.telephone,
                date_inscription: this.date_inscription,
                role: this.role,
                roleString : this.roleString,
                entrepot: this.entrepot,
                premiumDate: this.premiumDate,
                premiumTime: this.premiumTime
            };
        }
    }

    /**
     * Update the user with informations
     * @param nom
     * @param prenom
     * @param telephone
     * @param email
     * @returns {Promise<void>}
     */
    async updateUser(nom, prenom, telephone, email){
        if (nom == null && this.nom == null){
            await this.me(true)
        } else if(nom != null){
            this.nom = nom
        }

        if(prenom == null && this.prenom == null){
            await this.me(true)
        } else if(prenom != null){
            this.prenom = prenom
        }

        if(telephone == null && this.telephone == null){
            await this.me(true)
        } else if(telephone != null){
            this.telephone = telephone
        }

        if(email == null && this.email == null){
            await this.me(true)
        } else if(email != null){
            this.email = email
        }


        const data = {
            "email" : this.email,
            "prenom" : this.prenom,
            "telephone" : this.telephone,
            "nom" : this.nom
        }

        const newBigCookie = {
                "id_user":this.id_user,
                "nom":this.nom,
                "prenom":this.prenom,
                "email":this.email,
                "telephone":this.telephone,
                "date_inscription":this.date_inscription,
                "id_role":this.role,
                "password":null,
                "id_entrepot":this.id_entrepot,
                "entrepot":this.nom,
                "roleString":this.roleArray[this.role],
                "index":this.id_index,
                "premiumDate":this.premiumDate,
                "premiumTime":this.premiumTime
        }

        await this.setBigCookie(newBigCookie)
        console.log("Mise a jour en cours")

        const response = await this.fetchSync(this.adresse+"/user", this.optionPut(data))
        if(!this.compareAnswer(response)){
            return false
        }
        return response

    }

    /**
     * Retrieve only one user with his id
     * @param id of the user
     * @returns {Promise<void>}
     */
    async getUser(id = null){
        if(id != null && typeof(id) != "number"){
            popup("Vous devez spécifier un ID d'utilisateur sous forme de nombre")
            return false
        }
        let response = await this.fetchSync(this.adresse+'/user/'+id, this.optionGet())
        if(!this.compareAnswer(response, "Impossible de récupérer les utilisateurs en attente")){
            return false
        }
        return response
    }

    /**
     * Log out the user (rewrite the apikey)
     */
    logout() {
        this.apikey = '';
        this.deleteCookie("apikey")
        this.deleteCookie("userData")
    }

    /**
     * Set all the var of user instance
     * @param rep : response from the api
     * @returns {boolean}
     */
    setVar(rep){
        if(rep.apikey != "hidden"){
            this.apikey = rep.apikey
        }
        this.id_user = rep.id_user
        this.nom = rep.nom
        this.prenom = rep.prenom
        this.email = rep.email
        this.telephone = rep.telephone
        this.date_inscription = rep.date_inscription
        this.role = rep.id_role
        this.password = null
        this.entrepot = rep.id_entrepot
        this.roleString = this.roleArray[rep.id_role]
        this.index = rep.id_index
        this.premiumDate = rep.premiumDate
        this.premiumTime = rep.premiumTime

        return true
    }

    getBigCookie(){
        let rep = getCookie("userData")
        if(!rep){
            return false
        }

        rep = decodeURIComponent(rep);
        rep = JSON.parse(rep);

        this.id_user = rep.id_user
        this.nom = rep.nom
        this.prenom = rep.prenom
        this.email = rep.email
        this.telephone = rep.telephone
        this.date_inscription = rep.date_inscription
        this.role = rep.id_role
        this.password = null
        this.entrepot = rep.id_entrepot
        this.entrepotString = rep.entrepot
        this.roleString = this.roleArray[rep.id_role]
        this.index = rep.index
        this.premiumDate = rep.premiumDate
        this.premiumTime = rep.premiumTime

        return true
    }
    async setBigCookie(rep){
        console.log("Setting big cookies")
        if(rep.apikey != "hidden"){
            this.apikey = rep.apikey
        }

        let entrepot = await this.myEntrepot()

        const userData = {
            "id_user":rep.id_user,
            "nom":rep.nom,
            "prenom":rep.prenom,
            "email":rep.email,
            "telephone":rep.telephone,
            "date_inscription":rep.date_inscription,
            "id_role":rep.id_role,
            "password":null,
            "id_entrepot":1,//entrepot[0].id_entrepot,
            "entrepot":1,//entrepot[0].nom,
            "roleString":this.roleArray[rep.id_role],
            "index":rep.id_index,
            "premiumDate":rep.premiumDate,
            "premiumTime":rep.premiumTime,
        }
        this.setCookie("userData", encodeURIComponent(JSON.stringify(userData)), 7)
        console.log("Big Cooked set")
        return true
    }

    /**
     * Get the entrepot link to the user.
     * Need to connect the user before executing this command cause it need the user.entrepot ID before retriving the name
     * @returns {Promise<void>}
     */
    async myEntrepot(){
        const entre = await this.getEntrepot()
        if(!entre){
            this.entrepotString = "Unknown"
            return false
        }
        for (let i of entre){
            if(this.entrepot === i.id_entrepot){
                this.entrepotString = i.nom
                break
            }
            this.entrepotString = entre[0].nom
        }

        this.entrepotString = this.entrepotString.replace("Ã´", "ô")

        return await this.getEntrepot(this.entrepot)
    }

    async myPlanning(){
        let response = await this.fetchSync(this.adresse+'/planning/me', this.optionGet())

        if(!this.compareAnswer(response)){
            return false
        }
        return response
    }

    /**
     *
     * @param id of an entrepot | can be null
     * @returns {Promise<*|boolean>}
     */
    async getEntrepot(id = null){
        let tmp
        if(id != null){
            tmp = "/"+id
        }
        else{
            tmp =""
        }
        let response = await this.fetchSync(this.adresse+'/entrepot'+tmp, this.optionGet())
        if(!this.compareAnswer(response, "Impossible de récupérer les entrepots")){
            return false
        }
        return response
    }

    /**
     * Use console.log() to print information saved in the user class
     */
    printUser(){
        console.log("apikey : "+this.apikey)
        console.log("nom : "+this.nom)
        console.log("prenom : "+this.prenom)
        console.log("email : "+this.email)
        console.log("telephone : "+this.telephone)
        console.log("date_inscription : "+this.date_inscription)
        console.log("role : "+this.role)
        console.log("role string : "+this.roleString)
        console.log("entrepot : "+this.entrepot)
        console.log("entrepot string : "+this.entrepotString)
        console.log("Premium Date : "+this.premiumDate)
        console.log("Premium Time : "+this.premiumTime)
    }

}
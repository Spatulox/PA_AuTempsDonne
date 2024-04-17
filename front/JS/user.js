class User {

  constructor(email = null, password = null) {
    this.adresse = "http://localhost:8081/index.php";
    this.apikey = null;
    this.nom = null;
    this.prenom = null;
    this.email = null;
    this.telephone = null;
    this.date_inscription = null;
    this.role = null;
    this.password = null

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

    if(cookie == null){
      alertDebug("Bug de cookie")
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

    if(this.email == null || this.password == null){
      await this.me()
      return true
    }
    const data = {
      email: this.email,
      mdp: this.password
    };

    const options = {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    };

    const rep = await this.fetchSync(this.adresse  + "/login", options)
    if(rep === false){
      popup("Impossible")
      return false
    }
    this.setVar(rep)
    this.setCookie("apikey", rep.apikey, 7);
    return true
  }


  //
  //----------------------------------METHODES--------------------------------------------------------------------------
  //

  /**
   *
   * @param forceLoad null by default, can be set to 1 to request the API
   * @returns Data of user
   */
  async me(forceLoad = false){

    if(this.nom == null || forceLoad === true){
      const rep = await this.fetchSync(this.adresse  + "/user", this.optionGet())
      if(rep === false){
        return false
      }
      //alertDebug(rep)
      this.setVar(rep)
      return rep
      //return true
    }
    else{
      return {
        apikey: "hidden",
        nom: this.nom,
        prenom: this.prenom,
        email: this.email,
        telephone: this.telephone,
        date_inscription: this.date_inscription,
        role: this.role
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

    const data2 = {
      "email" : this.email,
      "prenom" : "yeeeteeee",
      "telephone" : this.telephone,
      "nom" : this.nom
    }

    const response = await this.fetchSync(this.adresse+"/user", this.optionPut(data2))
    console.log(response)
    alertDebug("Yetye")
    if(!this.compareAnswer(response, "Impossible de mettre à jour l'utilisateur")){
      return false
    }
    alertDebug("Mise à jour terminée")
    return response

  }

  /**
   * Get the plannig of the user with the apikey
   * @returns {Promise<any|boolean>}
   */
  async allPlanning(){
    let response = await this.fetchSync(this.adresse+'/planning', this.optionGet())
    if(!this.compareAnswer(response, "Impossible de récupérer les plannings")){
      return false
    }
    alertDebug("Tout les planning ont été récupéré")
    return response
  }

  /**
   * Get the planning of the user
   * @returns {Promise<*|boolean>}
   */
  async planning(){
    let response = await this.fetchSync(this.adresse+'/planning/me', this.optionGet())

    if(!this.compareAnswer(response, "Impossible de récupérer votre planning")){
      return false
    }
    alertDebug("Votre planning a été récupéré")
    return response
  }


  /**
   * Log out the user (rewrite the apikey)
   */
  logout() {
    this.apikey = '';
    this.setCookie("apikey", "", 7)
  }

  //
  //------------------------------------UTILS--------------------------------------------------------------------------
  //

  /**
   * Use to fetch the api
   * @param url : Specifie it to request an endpoint
   * @param options option get by this.optionGet() or this.optionPost()
   * @returns {Promise<any|boolean>}
   */
  async fetchSync(url, options){

    if(options === false){
      popup("Impossible de se connecter, veuillez entrer vos identifiant sur la page de connexion")
      return false
    }

    const response = await fetch(url, options)

    if(response.ok){
      return await response.json()
    }
    else{
      alertDebug(`Impossible de réaliser cette requête (${response.statusText}) : ${response.url}`)
      popup(`Impossible de réaliser cette requête`)
      console.log(response)
      return false
    }

  }

  compareAnswer(response, msg){
    if(response === false){
      alertDebug(msg)
      popup(msg)
      this.logout()
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
   * Set all the var of user instance
   * @param rep : response from the api
   * @returns {boolean}
   */
  setVar(rep){
    this.apikey = rep.apikey
    this.apikey = rep.apikey
    this.nom = rep.nom
    this.prenom = rep.prenom
    this.email = rep.email
    this.telephone = rep.telephone
    this.date_inscription = rep.date_inscription
    this.role = rep.role
    this.password = null

    return true
  }

  /**
   * Create the header option for GET request
   * @returns {{headers: {apikey: string, "Content-Type": string}, method: string}}
   */
  optionGet(){

    if(this.apikey === "hidden"){
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

  optionPut(data) {
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

}

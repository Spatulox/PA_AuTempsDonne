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
    this.entrepot = null
    this.roleString = null

    this.roleArray = ["Dirigeant", "Administrateur", "Bénévole", "Bénéficiaire", "Prestataire"]

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

    if(this.apikey != null){
      await this.me(true)
      return
    }
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
        role: this.role,
        roleString : this.roleString,
        entrepot: this.entrepot
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

    const response = await this.fetchSync(this.adresse+"/user", this.optionPut(data))
    console.log(response)
    alertDebug("Yetye")
    if(!this.compareAnswer(response, "Impossible de mettre à jour l'utilisateur")){
      return false
    }
    alertDebug("Mise à jour terminée")
    return response

  }

  /**
   * Unreference the user using the apikey
   */
  async deleteUser(id = null){
    if(this.apikey === null){
      alert("Apikey null")
      return
    }

    let complementPath = ""
    if(id != null){
      complementPath = `/${id}`
    }

    let response = await this.fetchSync(this.adresse+'/user'+complementPath, this.optionDelete())
    console.log(response)
    if(!this.compareAnswer(response, "Impossible de supprimer l'utilisateur")){
      return false
    }
    alertDebug("Votre compte à bien été désactivé")
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
    console.log(response)
    if(!this.compareAnswer(response, "Impossible de récupérer les utilisateurs en attente")){
      return false
    }
    return response
  }

  /**
   * Retrieve all the users
   * @returns {Promise<boolean>}
   */
  async getAllUser(){
    let response = await this.fetchSync(this.adresse+'/user/all', this.optionGet())
    console.log(response)
    if(!this.compareAnswer(response, "Impossible de récupérer les utilisateurs")){
      return false
    }
    return response
  }

  /**
   * Only retrieve the waiting for validation users
   * @param id
   * @returns {Promise<void>}
   */
  async getWaitingUser(){
    let response = await this.fetchSync(this.adresse+'/user/validate', this.optionGet())
    if(!this.compareAnswer(response, "Impossible de récupérer les utilisateurs en attente")){
      return false
    }
    return response
  }









  //-----------------------------------ENTREPOTS-----------------------------------

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
    let response = await this.fetchSync(this.adresse+'/entrepot'+tmp, this.optionGet())
    if(!this.compareAnswer(response, "Impossible de récupérer les entrepots")){
      return false
    }
    return response
  }

  /**
   * Update an entrepot
   * @param name
   * @param localisation
   * @returns {Promise<*|boolean>}
   */
  async createEntrepot(name = null, localisation = null){
    if(name == null || localisation == null){
      alertDebug("Vous devez spécifier un nom et une localisation pour créer un entrepot");
      return
    }
    const data ={
      name: name,
      localisation : localisation
    }
    let response = await this.fetchSync(this.adresse+'/entrepot', this.optionPost(data))
    if(!this.compareAnswer(response, "Impossible de récupérer les entrepots")){
      return false
    }
    return response
  }

  /**
   * Update an entrepot
   * @param id_entrepot to update
   * @param nom_entrepot new name | can be null
   * @param localisation new localisation | can be null
   * @returns {Promise<*|boolean>}
   */
  async updateEntrepot(id_entrepot, nom_entrepot = null, localisation = null){

    const data = {
      "id_entrepot":id_entrepot,
      "nom_entrepot":nom_entrepot,
      "localisation":localisation
    }
    let response = await this.fetchSync(this.adresse+'/entrepot', this.optionPost(data))
    if(!this.compareAnswer(response)){
      return false
    }
    return response
  }

  /**
   * Delete an entrepot
   * @param id_entrepot to delete
   * @returns {Promise<*|boolean>}
   */
  async deleteEntrepot(id_entrepot){
    if(typeof(id_entrepot) != "number"){
      alertDebug("Il faut un nombre entier pour delete un entrepot")
      return
    }
    const data = {
      "id_entrepot": id_entrepot
    }
    let response = await this.fetchSync(this.adresse+'/entrepot', this.optionDelete(data))
    if(!this.compareAnswer(response)){
      return false
    }
    return response
  }









  //------------------------------------PLANNING------------------------------------

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
    popup("Votre planning a été récupéré")
    return response
  }

  //------------------------------------OTHER------------------------------------
  /**
   * Log out the user (rewrite the apikey)
   */
  logout() {
    this.apikey = '';
    this.deleteCookie("apikey")
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
      const message = await response.json()
      //console.log(message)
      if(message.hasOwnProperty("message")){
        popup(message.message)
        return true
      }
      return message
    }
    else{
      const text = await response.json()
      alertDebug(`Impossible de réaliser cette requête (${response.statusText}) : ${response.url}`)
      //alertDebug(`${text.message}`)
      popup(`${text.message}`)
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
    else if (response === true){
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
  deleteCookie(cookieName){
    // Définir la date d'expiration à une date passée (1er janvier 2000)
    document.cookie = `${cookieName}=; expires=Thu, 01 Jan 2000 00:00:00 UTC; path=/;`

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
    this.nom = rep.nom
    this.prenom = rep.prenom
    this.email = rep.email
    this.telephone = rep.telephone
    this.date_inscription = rep.date_inscription
    this.role = rep.id_role
    this.password = null
    this.entrepot = rep.id_entrepot
    this.roleString = this.roleArray[rep.id_role]

    return true
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
    console.log("role string"+this.roleString)
    console.log("entrepot : "+this.entrepot)
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
      }
    };

    return options;
  }

}

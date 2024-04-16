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
    if(rep == false){
      popup("Impossible")
      return false
    }
    this.setVar(rep)
    this.setCookie("apikey", rep.apikey, 7);
    return true
  }

  async me(forceLoad = null){

    if(this.nom == null || forceLoad == null){
      const rep = await this.fetchSync(this.adresse  + "/user", this.optionGet())
      if(rep == false){
        return false
      }
      alertDebug(rep)
      this.setVar(rep)
      return true
    }
    else{
      return {
        apikey: this.apikey,
        nom: this.nom,
        prenom: this.prenom,
        email: this.email,
        telephone: this.telephone,
        date_inscription: this.date_inscription,
        role: this.role
      };
    }


  }

  //
  //----------------------------------METHODES--------------------------------------------------------------------------
  //

  // Méthodes de la classe User
  getApikey() {
    return this.apikey;
  }

  setApikey(newApikey) {
    this.apikey = newApikey;
  }

  async planning(){
    let response = await this.fetchSync(this.adresse+'/planning', this.optionGet())
    if(response == false){
      popup("Impossible de récupérer les plannings")
      this.logout()
      return false
    }

    return response
  }



  logout() {
    this.apikey = '';
    this.setCookie("apikey", "", 7)
  }

  //
  //------------------------------------UTILS--------------------------------------------------------------------------
  //

  async fetchSync(url, options){

    if(options == false){
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


  /**
   *
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

  getCookie(cookieName) {
    // Récupère tous les cookies sous forme de tableau
    const cookies = document.cookie.split(';');

    // Parcourt le tableau de cookies
    for (let i = 0; i < cookies.length; i++) {
      let cookie = cookies[i].trim();

      // Vérifie si le cookie commence par le nom recherché
      if (cookie.startsWith(cookieName + '=')) {
        // Renvoie la valeur du cookie
        return cookie.substring(cookieName.length + 1);
      }
    }

    // Si le cookie n'est pas trouvé, renvoie null
    return null;
  }


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

  optionGet(){

    if(this.apikey == "hidden"){
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

}

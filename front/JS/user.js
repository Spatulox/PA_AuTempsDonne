class User {
  constructor(apikey) {
    this.apikey = apikey;
  }

  // Méthodes de la classe User
  getApikey() {
    return this.apikey;
  }

  setApikey(newApikey) {
    this.apikey = newApikey;
  }

  getPlanning(){
    let content = fetch('/api/planning', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(requestBody)
    })
    .then(response => response.json())
    .then(data => {
      // Traiter les données du planning
      return data;
    })
    .catch(error => {
      console.error('Erreur lors de la récupération du planning :', error);
      throw error;
    });

    return content
  }


  getAccount(){
    let content = fetch('/api/account', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(requestBody)
    })
    .then(response => response.json())
    .then(data => {
      // Traiter les données du planning
      return data;
    })
    .catch(error => {
      console.error('Erreur lors de la récupération du planning :', error);
      throw error;
    });

    return content
  }


  logout() {
    this.apikey = '';
  }
}

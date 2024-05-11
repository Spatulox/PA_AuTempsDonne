class DemandeAdmin extends Admin{

    async getAllDemande(){
        let response = await this.fetchSync(this.adresse + '/demande/all', this.optionGet())
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }
}
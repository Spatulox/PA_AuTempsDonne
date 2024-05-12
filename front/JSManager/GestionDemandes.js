class DemandeAdmin extends Admin{

    async getAllDemande(){
        let response = await this.fetchSync(this.adresse + '/demande/all', this.optionGet())
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }


    async deleteDemande(id){
        let response = await this.fetchSync(this.adresse + '/demande/'+id, this.optionDelete())
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }
}
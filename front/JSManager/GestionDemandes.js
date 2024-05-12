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

    async validateSoloDemande(id){
        let response = await this.fetchSync(this.adresse + '/demande/validate/'+id, this.optionPost())
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }

    async validateGroupDemande(data){

        let response = await this.fetchSync(this.adresse + '/demande/groupe', this.optionPost(data))
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }

}
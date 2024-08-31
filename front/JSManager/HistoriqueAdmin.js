class HistoriqueAdmin extends Admin {
       
    async getAllHistorique() {
             
        let response = await this.fetchSync(this.adresse+'/historique', this.optionGet())
        if(!this.compareAnswer(response)){
            return false
        }
        return response
    }

}
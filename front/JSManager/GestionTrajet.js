class TrajetAdmin extends Admin{


    async getTrajetById(id){
        let response = await this.fetchSync(this.adresse + '/trajet/'+id, this.optionGet())
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }

    async getAddressByIdArray(data){
        let response = await this.fetchSync(this.adresse + '/trajet', this.optionPost(data))
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }

    async createTrajet(data){
        let response = await this.fetchSync(this.adresse + '/trajet/create', this.optionPost(data))
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }
}
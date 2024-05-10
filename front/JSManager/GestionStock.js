class StockAdmin extends Admin{

    async getAllStock(){
        let response = await this.fetchSync(this.adresse + '/stock', this.optionGet())
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }

    async getStockById(id){
        let response = await this.fetchSync(this.adresse + '/stock/'+id, this.optionGet())
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }

    async addRetrieveFromEntrepot(data){
        let response = await this.fetchSync(this.adresse + '/stock', this.optionPost(data))
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }

    async getExitedStock(id_entrepot){
        let response = await this.fetchSync(this.adresse + '/stock', this.optionGet())
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }
}
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
}
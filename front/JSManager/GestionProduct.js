class ProductAdmin extends Admin {
    async getAllProduct(){
        let response = await this.fetchSync(this.adresse + '/produit', this.optionGet())
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }

    async getAllTypeProduct(){
        let response = await this.fetchSync(this.adresse + '/produit/type', this.optionGet())
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }

    async getProduct(id){
        let response = await this.fetchSync(this.adresse + '/produit/'+id, this.optionGet())
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }

    async createProduct(data){
        let response = await this.fetchSync(this.adresse + '/produit', this.optionPost(data))
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }

    async deleteProduct(id){
        let response = await this.fetchSync(this.adresse + '/produit/'+id, this.optionDelete(data))
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }

}
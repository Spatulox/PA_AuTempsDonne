class Request extends User{

    async createRequest(desc, id_activite = null, date_activite = null, produit = null){


        if(produit == null){
            popup("Il faut des produits")
            return false
        }

        const data = {
            "desc_demande": desc,
            "activite": "groupe",
            "id_activite": 6,
            "produit":produit
        }

        let response = await this.fetchSync(this.adresse+'/demande', this.optionPost(data))

        if(!this.compareAnswer(response)){
            return false
        }
        return response
    }

    async getRequest(){
        let response = await this.fetchSync(this.adresse+'/demande', this.optionGet())

        if(!this.compareAnswer(response)){
            return false
        }
        return response
    }

}
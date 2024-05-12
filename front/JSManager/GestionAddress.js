class AddressAdmin extends Admin{

    async getAllAddress(){
        let response = await this.fetchSync(this.adresse+'/adresse', this.optionGet())
        if(!this.compareAnswer(response)){
            return false
        }
        return response
    }

    async createAddress(string){

        const data = {
            "address":string
        }

        let response = await this.fetchSync(this.adresse+'/adresse/create', this.optionPost(data))
        if(!this.compareAnswer(response)){
            return false
        }
        return response
    }

    async deleteAddress(id){
        let response = await this.fetchSync(this.adresse+'/adresse/'+id, this.optionDelete())
        if(!this.compareAnswer(response)){
            return false
        }
        return response
    }

}
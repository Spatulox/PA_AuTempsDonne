class VehicleAdmin extends Admin{

    async getAllVehicle(){
        let response = await this.fetchSync(this.adresse+'/vehicule', this.optionGet())
        if(!this.compareAnswer(response)){
            return false
        }
        return response
    }

    async getVehicleById(id){
        let response = await this.fetchSync(this.adresse+'/vehicule/'+id, this.optionGet())
        if(!this.compareAnswer(response)){
            return false
        }
        return response
    }

    async createVehicle(id){
        let response = await this.fetchSync(this.adresse+'/vehicule/'+id, this.optionPost())
        if(!this.compareAnswer(response)){
            return false
        }
        return response
    }

    async deleteVehicle(id){
        let response = await this.fetchSync(this.adresse+'/vehicule/'+id, this.optionDelete())
        if(!this.compareAnswer(response)){
            return false
        }
        return response
    }

}
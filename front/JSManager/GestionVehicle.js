class VehicleAdmin extends Admin{

    async getAllVehicle(){
        let response = await this.fetchSync(this.adresse+'/vehicule', this.optionGet())
        if(!this.compareAnswer(response)){
            return false
        }
        return response
    }

    async getAllMyVehicle(){
        let response = await this.fetchSync(this.adresse+'/vehicule/me', this.optionGet())
        if(!this.compareAnswer(response)){
            return false
        }
        return response
    }

    async getAvailableVehicle(start, end){

        console.log(start, end)
        if(!this.isValidDate(start) || !this.isValidDate(end)){
            popup("Dates are not valid")
            return
        }

        if(this.isDateInThePast(start) || this.isDateInThePast(end)){
            popup("Date start and date end cannot be in the past")
            return
        }

        const data = {
            "date_start" : start,
            "date_end" : end
        }

        let response = await this.fetchSync(this.adresse+'/vehicule/available', this.optionPost(data))
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

    async createVehicle(data){
        let response = await this.fetchSync(this.adresse+'/vehicule/', this.optionPost(data))
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

    isDateInThePast(dateString) {
        let date = new Date(dateString.split("T")[0].split(" ")[0]);
        if(isNaN(date)){
            console.log("dateString n'est pas valide")
            return false
        }

        date = date.toISOString().split('T')[0]

        console.log(dateString.split("T")[0].split(" ")[0])


        let today = new Date()
        today = today.toISOString().split('T')[0]

        // Vérifier si la date est valide
        console.log(date >= today)
        return !(date >= today);

    }

    isValidDate(dateString) {
        // Convertir la chaîne de caractères en objet Date
        let date = new Date(dateString.split("T")[0].split(" ")[0]);
        if(isNaN(date)){
            console.log("dateString n'est pas valide")
            return false
        }
        return true
        /*
        date = date.toISOString().split('T')[0]

        console.log(dateString.split("T")[0].split(" ")[0])


        let today = new Date()
        today = today.toISOString().split('T')[0]

        // Vérifier si la date est valide
        console.log(date >= today)
        return date >= today;
        */
    }






}
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

    async getAssociationVehicle(){
        let response = await this.fetchSync(this.adresse+'/vehicule/association', this.optionGet())
        if(!this.compareAnswer(response)){
            return false
        }
        return response
    }

    async getAssociationAvailableVehicle(start, end){

        if(!this.isValidDate(start) || !this.isValidDate(end)){
            popup("Dates are not valid")
            return false
        }

        if(this.isDateInThePast(start) || this.isDateInThePast(end)){
            popup("Date start and date end cannot be in the past")
            return false
        }

        const data = {
            "date_start" : start.split('T').join(" ").split(".")[0],
            "date_end" : end.split('T').join(" ").split(".")[0]
        }

        let response = await this.fetchSync(this.adresse+'/vehicule/available/assoc', this.optionPost(data))
        if(!this.compareAnswer(response)){
            return false
        }
        return response
    }

    async getAvailableVehicle(start, end){

        if(!this.isValidDate(start) || !this.isValidDate(end)){
            popup("Dates are not valid")
            return false
        }

        if(this.isDateInThePast(start) || this.isDateInThePast(end)){
            popup("Date start and date end cannot be in the past")
            return false
        }

        const data = {
            "date_start" : start.split('T').join(" ").split(".")[0],
            "date_end" : end.split('T').join(" ").split(".")[0]
        }

        let response = await this.fetchSync(this.adresse+'/vehicule/available', this.optionPost(data))
        if(!this.compareAnswer(response)){
            return false
        }
        return response
    }

    // The automatic booked vehicle for user role 1, 2, 4
    // Take all my vehicle booked by other person id I'm a user role 3
    async getAllBookedVehicle(){
        let response = await this.fetchSync(this.adresse+'/vehicule/booked', this.optionGet())
        if(!this.compareAnswer(response)){
            return false
        }
        return response
    }

    // Get all vehicle I Have booked If i'm a user role 3
    async getVehicleIHaveBookedBenevole(){
        let response = await this.fetchSync(this.adresse+'/vehicule/booked/me', this.optionGet())
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

    async bookingVehicle(id, start, end){
        const data = {
            "id_vehicule":id,
            "date_start":start,
            "date_end":end
        }

        let response = await this.fetchSync(this.adresse+'/vehicule/booked', this.optionPost(data))
        if(!this.compareAnswer(response)){
            return false
        }
        return response
    }

    async unBook(id_service){
        let response = await this.fetchSync(this.adresse+'/vehicule/unbooked/'+id_service, this.optionDelete())
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

        //console.log(dateString.split("T")[0].split(" ")[0])


        let today = new Date()
        today = today.toISOString().split('T')[0]

        // Vérifier si la date est valide
        //console.log(date >= today)
        return !(date >= today);

    }

    isValidDate(dateString) {
        // Convertir la chaîne de caractères en objet Date
        let date = new Date(dateString);
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
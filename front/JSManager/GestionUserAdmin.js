class UserAdmin extends Admin{



    async updateUser(email, prenom, telephone, nom){

        const data = {
            "email" : email.trim(),
            "prenom" : prenom,
            "telephone" : telephone,
            "nom" : nom
        }

        const response = await this.fetchSync(this.adresse+"/user/", this.optionPut(data))
        if(!this.compareAnswer(response, this.msg["Update"] + this.msg["failed"])){
            popup(this.msg["Update"] + this.msg["failed"])
            return false
        }
        popup(this.msg["Update"] + this.msg["success"])
        return response

    }

    async getUserDispoByDay(day, date){

        const data = {
            "id_jour":day,
            "date":date
        }

        let response = await this.fetchSync(this.adresse+'/user/date', this.optionPost(data))
        if(!this.compareAnswer(response)){
            return false
        }
        return response

    }

    /**
     * Retrieve all the users
     * @returns {Promise<boolean>}
     */
    async getAllUser(){
        let response = await this.fetchSync(this.adresse+'/user/all', this.optionGet())
        if(!this.compareAnswer(response, "Impossible de récupérer les utilisateurs")){
            return false
        }
        return response
    }

    async getUserViaEmail(email){
        let response = await this.fetchSync(this.adresse+'/user/email/'+email, this.optionGet())
        if(!this.compareAnswer(response, "Impossible de récupérer les utilisateurs")){
            return false
        }
        return response
    }

    /**
     * Only retrieve the waiting for validation users
     * @param id
     * @returns {Promise<void>}
     */
    async getWaitingUser(){
        let response = await this.fetchSync(this.adresse+'/user/validate', this.optionGet(), false)
        if(!this.compareAnswer(response)){
            return false
        }
        return response
    }

    /**
     *
     * @param data data to post
     * @returns {Promise<*|boolean>}
     */
    async updateUserValidate(id){

        const data = {
            "id_user": id,
            "id_index": 2
        };

        let response = await this.fetchSync(this.adresse+'/user/validate', this.optionPut(data), false)
        if(!this.compareAnswer(response)){
            return false
        }
        return response
    }

    async updateUserRole(id_role, id_user){

        id_role = id_role.trim()

        const data = {
            "id_role": id_role
        };

        id_user = id_user.trim()

        let response = await this.fetchSync(this.adresse+'/user/role/'+id_user, this.optionPut(data))
        if(!this.compareAnswer(response)){
            return false
        }
        return response
    }

    /**
     * Unreference the user using the apikey
     */
    async deleteUser(id = null){
        if(this.apikey === null){
            alert("Apikey null")
            return
        }

        let complementPath = ""
        if(id != null){
            complementPath = `/${id}`
        } else {
            return
        }

        let response = await this.fetchSync(this.adresse+'/user'+complementPath, this.optionDelete())
        if(!this.compareAnswer(response)){
            return false
        }
        popup("Votre compte à bien été désactivé")
        return response

    }
}
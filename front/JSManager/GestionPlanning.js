class PlanningAdmin extends Admin{

    /**
     * Get the plannig of the user with the apikey
     * @returns {Promise<any|boolean>}
     */
    async getAllPlanning(){
        let response = await this.fetchSync(this.adresse+'/planning', this.optionGet())
        if(!this.compareAnswer(response, this.msg["impossible"] + this.msg["to"] + this.msg["retrieved"] + this.msg["all"] + this.msg["planning"])){
            return false
        }
        return response
    }

    /**
     * Get the plannig of the user with the apikey On doit les valider
     * @returns {Promise<any|boolean>}
     */
    async getWaitPlanning(){
        let response = await this.fetchSync(this.adresse+'/planning/wait', this.optionGet())
        if(!this.compareAnswer(response, this.msg["impossible"] + this.msg["to"] + this.msg["retrieved"] + this.msg["all"] + this.msg["planning"])){
            return false
        }
        return response
    }

    // Tout ceux qui sont validé (AssignTask) et non assigné
    async getNoAffectPlanning(){
        let response = await this.fetchSync(this.adresse+'/planning/affecte', this.optionGet())
        if(!this.compareAnswer(response)){
            return false
        }
        return response
    }

    // Daily planning
    async getAffectByDatePlanning(date){
        const data = {
            "date_activite":date+""
        }

        let response = await this.fetchSync(this.adresse+'/planning/date', this.optionPost(data), false)
        if(!this.compareAnswer(response)){
            return false
        }
        return response
    }

    async userJoinPlanning(id_user, id_planning){

        const data = {
            "user_id": id_user,
            "id_planning": id_planning
        }

        let response = await this.fetchSync(this.adresse+'/planning/join_activity', this.optionPost(data))
        if(!this.compareAnswer(response)){
            return false
        }
        return response
    }

    /**
     * Tout ceux qui sont validé (AssignTask)
     * Get the plannig of the user with the apikey
     * @returns {Promise<any|boolean>}
     */
    async getValidatePlanning(){
        let response = await this.fetchSync(this.adresse+'/planning/validate', this.optionGet())
        if(!this.compareAnswer(response, this.msg["Impossible"] + this.msg["to"] + this.msg["retrieved"] + this.msg["all"] + this.msg["planning"])){
            return false
        }
        //popup(this.msg["All"] + this.msg["planning"]+this.msg["retrieved"])
        return response
    }

    /**
     * Get the planning of the user
     * @returns {Promise<*|boolean>}
     */
    async getMyPlanning(){
        let response = await this.fetchSync(this.adresse+'/planning/me', this.optionGet())

        if(!this.compareAnswer(response, this.msg["Impossible"] + this.msg["to"] + this.msg["retrieved"] + this.msg["your"] + this.msg["planning"])){
            return false
        }
        //popup(this.msg["Planning"]+this.msg["retrieved"])
        return response
    }

    async getPlanningByIdUSer(id){

        if(typeof(id) !== "number"){
            popup("Vous devez spécifier un nombre entier")
            return false
        }

        let response = await this.fetchSync(this.adresse+'/planning/'+id, this.optionGet(), false)

        if(!this.compareAnswer(response)){
            return false
        }
        //popup(this.msg["The"] + this.msg["planning"] + this.msg["has been"] + this.msg["retrieved"])
        return response
    }

    async validatePlanning(id){
        if(typeof(id) !== "number"){
            popup("Vous devez spécifier un nombre entier")
            return false
        }

        const data = {
            "id_planning": +id
        }

        let response = await this.fetchSync(this.adresse+'/planning/validate/'+id, this.optionPut(data), false)

        if(!this.compareAnswer(response)){
            return false
        }
        return response

    }

}
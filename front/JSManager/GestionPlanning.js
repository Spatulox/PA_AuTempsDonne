class PlanningAdmin extends Admin{

    /**
     * Get the plannig of the user with the apikey
     * @returns {Promise<any|boolean>}
     */
    async allPlanning(){
        let response = await this.fetchSync(this.adresse+'/planning', this.optionGet())
        if(!this.compareAnswer(response, this.msg["impossible"] + this.msg["to"] + this.msg["retrieved"] + this.msg["all"] + this.msg["planning"])){
            return false
        }
        popup(this.msg["all"] + this.msg["planning"]+this.msg["retrieved"])
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
        popup(this.msg["planning"]+this.msg["retrieved"])
        return response
    }

    async getPlanningByIdUSer(id){

        if(typeof(id) !== "number"){
            showPopup("Vous devez spécifier un nombre entier")
            return false
        }

        let response = await this.fetchSync(this.adresse+'/planning/'+id, this.optionGet())

        if(!this.compareAnswer(response)){
            return false
        }
        //popup(this.msg["The"] + this.msg["planning"] + this.msg["has been"] + this.msg["retrieved"])
        return response
    }

}
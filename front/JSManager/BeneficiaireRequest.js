class Request extends User{

    async createRequest(desc, id_activite, date_activite){

        const data = {
            "desc_demande": desc,
            "activite": "seul",
            "id_activite": id_activite,
            "date_act": date_activite
        }

        let response = await this.fetchSync(this.adresse+'/planning/me', this.optionPost(data))

        if(!this.compareAnswer(response)){
            return false
        }
        //popup(this.msg["Planning"]+this.msg["retrieved"])
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
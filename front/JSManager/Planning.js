class Planning extends User{

    /**
     * Get the planning of the user
     * @returns {Promise<*|boolean>}
     */
    async planning(){
        let response = await this.fetchSync(this.adresse+'/planning/me', this.optionGet())

        if(!this.compareAnswer(response, "Impossible de récupérer votre planning")){
            return false
        }
        popup("Votre planning a été récupéré")
        return response
    }

}
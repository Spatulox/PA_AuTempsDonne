class Activite extends User{

    async getAllActivite(){
        let response = await this.fetchSync(this.adresse + '/activite', this.optionGet())
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }

}
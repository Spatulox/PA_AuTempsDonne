class DonAdmin extends Admin{

    async getAllDon(){
        let response = await this.fetchSync(this.adresse + '/don', this.optionGet())
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }
}
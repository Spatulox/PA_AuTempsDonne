class File extends User{
    async getMyFiles(){
        let response = await this.fetchSync(this.adresse + '/fichier/me', this.optionGet())
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }

    async getFilesByUser(id){
        let response = await this.fetchSync(this.adresse + '/fichier/'.id, this.optionGet())
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }

    async retrieveFile(name){
        const data = {
            "name_file":name
        }
        let response = await this.fetchSync(this.adresse + '/fichier/'.id, this.optionPost(data))
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }
}
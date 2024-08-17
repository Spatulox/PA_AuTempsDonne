class GestionMail extends User{

    async sendMail(subject, htmlString, emailArray = null){

        const data = {
            "subject": subject,
            "htmlString": htmlString,
            "emailToSend": emailArray
        }

        let response = await this.fetchSync(this.adresse + '/mail', this.optionPost(data))
        if (!this.compareAnswer(response)) {
            return false
        }

        return response
    }

}
class Entrepot extends Admin {


    //-----------------------------------ENTREPOTS-----------------------------------

    /**
     *
     * @param id of an entrepot | can be null
     * @returns {Promise<*|boolean>}
     */
    async getEntrepot(id = null) {
        let tmp
        if (id != null) {
            tmp = "/" + id
        } else {
            tmp = ""
        }
        let response = await this.fetchSync(this.adresse + '/entrepot' + tmp, this.optionGet(), false)
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }

    /**
     * Update an entrepot
     * @param name
     * @param localisation
     * @returns {Promise<*|boolean>}
     */
    async createEntrepot(nom_entrepot = null, localisation = null) {
        if (name == null || localisation == null) {
            popup("Vous devez spécifier un nom et une localisation pour créer un entrepot");
            return
        }
        const data = {
            "nom": nom_entrepot,
            "localisation": localisation,
        }
        let response = await this.fetchSync(this.adresse + '/entrepot', this.optionPost(data))
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }

    /**
     * Update an entrepot
     * @param id_entrepot to update
     * @param nom_entrepot new name | can be null
     * @param localisation new localisation | can be null
     * @returns {Promise<*|boolean>}
     */
    async updateEntrepot(id_entrepot, nom_entrepot = null, localisation = null) {

        const data = {
            "id_entrepot": id_entrepot,
            "nom": nom_entrepot,
            "localisation": localisation
        }
        let response = await this.fetchSync(this.adresse + '/entrepot', this.optionPut(data))
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }

    /**
     * Delete an entrepot
     * @param id_entrepot to delete
     * @returns {Promise<*|boolean>}
     */
    async deleteEntrepot(id_entrepot) {
        if (typeof (id_entrepot) != "number") {
            popup("Il faut un nombre entier pour delete un entrepot")
            return
        }

        let response = await this.fetchSync(this.adresse + '/entrepot/delete_entrepot/'+id_entrepot, this.optionDelete())
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }

    async createShelf(id_entrepot, place){

        if (typeof (place) != "number") {
            popup("Il faut un nombre entier pour ajouter un entrepot")
            return
        }

        const data = {
            "id_entrepot":id_entrepot,
            "etagere":[{
                'nombre_de_place':place
            }]
        }

        let response = await this.fetchSync(this.adresse + '/entrepot/up', this.optionPost(data))
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }

    async deleteShelf(id) {

        if (typeof (id) != "number") {
            popup("Il faut un nombre entier pour delete un entrepot")
            return
        }

        let response = await this.fetchSync(this.adresse + '/entrepot/delete_etagere/'+id, this.optionDelete())
        if (!this.compareAnswer(response)) {
            return false
        }
        return response

    }


}
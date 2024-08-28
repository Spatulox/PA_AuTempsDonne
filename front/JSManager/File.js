class File extends User{
    async getMyFiles(){
        let response = await this.fetchSync(this.adresse + '/fichier/me', this.optionGet())
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }

    async getFilesByUser(id){
        let response = await this.fetchSync(this.adresse + '/fichier/'+id, this.optionGet(), false)
        if (!this.compareAnswer(response)) {
            return false
        }
        return response
    }

    async retrieveFile(name, id){
        const data = {
            "name_file":name
        }

        try {
            const response = await fetch(this.adresse + '/fichier/'+id, this.optionPost(data));

            if (!response.ok) {
                console.log("Error")
                alert("Error")
            }

            // Obtenir le nom du fichier depuis les headers de la réponse
            const contentDisposition = response.headers.get('Content-Disposition');
            let fileName = name; // Utiliser le nom fourni par défaut
            if (contentDisposition) {
                const fileNameMatch = contentDisposition.match(/filename="?(.+)"?/i);
                if (fileNameMatch) {
                    fileName = fileNameMatch[1];
                }
            }

            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = fileName;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        } catch (error) {
            console.error('Download error:', error);
        }

    }
}
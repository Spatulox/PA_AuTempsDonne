function createSelect(options, debut = null) {
    // Créer l'élément <select>
    const selectElement = document.createElement('select');

    if(debut != null){
        const optionElement = document.createElement('option');
        optionElement.text = debut;
        const hr = document.createElement("hr")
        //selectElement.add(optionElement);
        selectElement.appendChild(optionElement)
        selectElement.appendChild(hr)
    }

    // Ajouter les options
    options.forEach(option => {
        const optionElement = document.createElement('option');
        optionElement.value = option.value;
        optionElement.text = option.text;
        selectElement.add(optionElement);
    });

    // Retourner l'élément <select>
    return selectElement;
}
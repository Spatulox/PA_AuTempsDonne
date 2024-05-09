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



function createBodyTableau(tbody, dataArray, hiddenKeys = [], buttonText = null, nameFunction = null, keyInsideFunction = null) {
    dataArray.forEach(item => {
        const row = document.createElement('tr');

        for (const [key, value] of Object.entries(item)) {
            if (!hiddenKeys.includes(key)) {
                const cell = document.createElement('td');

                if (Array.isArray(value)) {
                    const cellContent = document.createElement('ul');
                    cellContent.classList.add("noPadding");
                    cellContent.classList.add('cell-content');
                    value.forEach(arrayItem => {
                        const itemElement = document.createElement('li');
                        itemElement.textContent = arrayItem;
                        cellContent.appendChild(itemElement);
                    });
                    cell.appendChild(cellContent);
                } else if (key === 'id_role') {
                    cell.textContent = user.roleArray[value];
                } else {
                    cell.textContent = value || "N/A";
                }

                row.appendChild(cell);
            }
        }

        if (Array.isArray(buttonText) && Array.isArray(nameFunction) && buttonText.length === nameFunction.length) {
            for (let i = 0; i < buttonText.length; i++) {
                const buttonCell = document.createElement('td');
                const button = createButton(buttonText[i]);
                button.setAttribute("onclick", `${nameFunction[i]}(${item[keyInsideFunction]})`);
                buttonCell.appendChild(button);
                row.appendChild(buttonCell);
            }
        } else if (buttonText !== null && nameFunction !== null && keyInsideFunction !== null) {
            const buttonCell = document.createElement('td');
            const button = createButton(buttonText);
            button.setAttribute("onclick", `${nameFunction}(${item[keyInsideFunction]})`);
            buttonCell.appendChild(button);
            row.appendChild(buttonCell);
        }

        tbody.appendChild(row);
    });
    return tbody;
}

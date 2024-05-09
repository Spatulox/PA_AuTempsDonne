function replaceCharacters() {
    const body = document.body;
    const textNodes = getTextNodes(body);

    textNodes.forEach(node => {
        const text = node.nodeValue;
        const replacedText = text.replace(/Ã‰/g, 'É')
            .replace(/Ã©/g, 'é')
            .replace(/Ã¨/g, 'è')
            .replace(/Ã¢/g, 'â')
            .replace(/Ã«/g, 'ë')
            .replace(/Ã®/g, 'î')
            .replace(/Ã´/g, 'ô')
            .replace(/Ã¹/g, 'ù')
            .replace(/Ã»/g, 'û')
            .replace(/Ã§/g, 'ç');

        if (replacedText !== text) {
            const newNode = document.createTextNode(replacedText);
            const parentNode = node.parentNode;
            parentNode.replaceChild(newNode, node);
        }
    });
}

function getTextNodes(node) {
    let textNodes = [];

    for (let child = node.firstChild; child !== null; child = child.nextSibling) {
        if (child.nodeType === Node.TEXT_NODE) {
            textNodes.push(child);
        } else {
            textNodes = textNodes.concat(getTextNodes(child));
        }
    }

    return textNodes;
}

//setInterval(replaceCharacters, 1000);
replaceCharacters()
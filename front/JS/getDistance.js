/*async function getDistance(origin, destination) {
    return new Promise((resolve, reject) => {
        var distanceMatrixService = new google.maps.DistanceMatrixService();

        var request = {
            origins: [origin],
            destinations: [destination],
            travelMode: 'DRIVING',
            unitSystem: google.maps.UnitSystem.METRIC,
            avoidHighways: false,
            avoidTolls: false,
        };

        distanceMatrixService.getDistanceMatrix(request, function (response, status) {
            if (status == 'OK' && response.rows[0] && response.rows[0].elements[0] && response.rows[0].elements[0].distance) {
                var distanceText = response.rows[0].elements[0].distance.text;
                var distanceValue = parseFloat(distanceText.replace(',', '.'));
                resolve(distanceValue);
            } else {
                reject('N/A');
            }
        });
    });
}*/

async function getDistance(origin, destination) {
    return new Promise((resolve, reject) => {
        const distanceMatrixService = new google.maps.DistanceMatrixService();

        const request = {
            origins: [origin],
            destinations: [destination],
            travelMode: 'DRIVING',
            unitSystem: google.maps.UnitSystem.METRIC,
            avoidHighways: false,
            avoidTolls: false,
        };

        distanceMatrixService.getDistanceMatrix(request, function (response, status) {
            if (status !== 'OK') {
                reject(new Error(`Distance Matrix request failed with status: ${status}`));
                return;
            }

            if (!response.rows[0] || !response.rows[0].elements[0]) {
                reject(new Error('Invalid response structure: missing rows or elements'));
                return;
            }

            const element = response.rows[0].elements[0];

            if (element.status !== 'OK') {
                reject(new Error(`Route calculation failed: ${element.status}`));
                return;
            }

            if (!element.distance) {
                reject(new Error('Distance information not available in the response'));
                return;
            }

            const distanceText = element.distance.text;
            const distanceValue = parseFloat(distanceText.replace(',', '.'));

            if (isNaN(distanceValue)) {
                reject(new Error(`Failed to parse distance value: ${distanceText}`));
                return;
            }

            resolve(distanceValue);
        });
    });
}
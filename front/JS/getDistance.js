async function getDistance(origin, destination) {
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
}

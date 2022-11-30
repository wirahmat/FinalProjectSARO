function fetchPolygon(position){
    var location = position.coords.latitude + "," + position.coords.longitude;
    console.log(position.coords.accuracy);
}

function fetchWarningName(total){
    var status = "";
    if (total > 100){
        status = "High Crime Rate";
    }
    else if (total >= 50 && total < 100){
        status = "Considerable Crime Rate";
    }
    else if (total >=10 && total < 50){
        status = "Moderate Crime Rate";
    }
    else if (total < 10){
        status = "Low Crime Rate";
    }
    return status;
}

function isMarkerInsidePolygon(combine) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var lat = position.coords.latitude;
            var long = position.coords.longitude;
            // console.log(combine);
            // console.log(combine[26][2]);
            var polyPoints = combine[26][0][0];      
            var intersections = 0;
            var crime_value = 0;
            var inside = false;
            for (var i = 0, j = polyPoints.length - 1; i < polyPoints.length; j = i++) {
                var xi = polyPoints[i][1], yi = polyPoints[i][0];
                var xj = polyPoints[j][1], yj = polyPoints[j][0];
                // console.log(xi,yi,xj,yj);

                // var intersect = ((yi > long) != (yj > long)) && (lat < (xj - xi) * (long - yi) / (yj - yi) + xi);
                // if (((yi > long) != (yj > long)) && (lat < (xj - xi) * (long - yi) / (yj - yi) + xi)) 
                // {
                //     inside = !inside;
                // };
                if (yj == yi && yj == long && lat > Math.min(xj, xi) && lat < Math.max(xj, xi)) { // Check if point is on an horizontal polygon boundary
                    console.log("benar point is on an horizontal polygon boundary");
                    return true;    
                }
                    
                if (long > Math.min(yj, yi) && long <= Math.max(yj, yi) && lat <= Math.max(xj, xi) && yj != yi) {
                    ss = (long - yj) * (xi - xj) / (yi - yj) + xj;
                    if (ss == lat) { // Check if point is on the polygon boundary (other than horizontal)
                        console.log("benar point is on the polygon boundary (other than horizontal)");
                        return true;
                    }
                    if (xj == xi || lat <= ss) {
                        console.log("benar intersection");
                        crime_value = combine[26][2];
                        intersections++; 
                    } 
                } 
            }
            // If the number of edges we passed through is odd, then it’s in the polygon.
            if (intersections % 2 != 0) {
                console.log("benar intersection ganjil" + intersections);
                alert("You are in " + fetchWarningName(crime_value) + " Area, the crime total in this Area is " + crime_value);
                return true;
            } else {
                console.log("salah intersection genap" + intersections);
                return false;
            }
            // console.log(inside);

            // popup.setLatLng(latLng);
            // popup.setContent('This is your current location');
            // popup.openOn(geolocationMap);

            // geolocationMap.setView(latLng);
        });
        // function() {
        //     geolocationErrorOccurred(true, popup, geolocationMap.getCenter());
        // });
    } else {
        //No browser support geolocation service
        alert("Geolocation is not supported by this browser.");
    }
    // var polyPoints = poly.getLatLngs();      
    // var x = marker.getLatLng().lat, y = marker.getLatLng().lng;

    // var inside = false;
    // for (var i = 0, j = polyPoints.length - 1; i < polyPoints.length; j = i++) {
    //     var xi = polyPoints[i].lat, yi = polyPoints[i].lng;
    //     var xj = polyPoints[j].lat, yj = polyPoints[j].lng;

    //     var intersect = ((yi > y) != (yj > y))
    //         && (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
    //     if (intersect) inside = !inside;
    // }

    // return inside;
};
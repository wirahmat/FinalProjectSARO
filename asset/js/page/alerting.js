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
    var subdistrict = "";
    var postal_code = "";
    var village = "";
    var polyPoints;
    var get_sub_data;
    var crime_value = 0;
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var lat = position.coords.latitude; //(x)
            var long = position.coords.longitude; //(y)
            var query = "lat=" + lat + "&lon=" + long;
            $.get('https://nominatim.openstreetmap.org/reverse?format=jsonv2&'+ query, function(data){//geocoder nominatim
                // console.log(data);
                if(data.address.postcode != ""){
                    postal_code = data.address.postcode;
                }
                else{
                    postal_code = "none";
                }
                if(data.address.village != ""){
                    village = data.address.village;
                }
                else{
                    village = "none";
                }
                if(data.address.suburb != ""){
                    subdistrict = data.address.suburb;
                }
                else{
                    subdistrict = "none";
                }
                console.log(postal_code, village, subdistrict);
                $.ajax({
                    url: base_url + "/saferoutefinpro/home/get_user_subdistrict",
                    method:"POST",
                    data:{postal_code:postal_code, village:village},
                    success:function(response){
                        // console.log("Sukses");
                        get_sub_data = JSON.parse(response);
                        // document.getElementById('close-modal-statistic-detail').click();
                        // $('#show_data').html(response);
                        // dataChart();
                        // console.log(get_sub_data[0].kecamatan);
                        for(var i = 0; i < combine.length; i++){
                            if (get_sub_data[0].kecamatan == combine[i][1]){
                                polyPoints = combine[i][0][0]; //get polygon location
                                // polyPoints = combine[26][0][0];  
                                crime_value = combine[i][2];    
                                console.log(polyPoints);
                                var intersections = 0;
                                var inside = false;
                                //https://www.ignatiuz.com/blog/gis/how-to-check-whether-point-lies-inside-outside-the-polygon/
                                for (var i = 0, j = polyPoints.length - 1; i < polyPoints.length; j = i++) { //i value move to j, for example if i = 0, next loop j became j=0
                                    // console.log(i,j);
                                    var xi = polyPoints[i][1], yi = polyPoints[i][0]; //initial first point 
                                    var xj = polyPoints[j][1], yj = polyPoints[j][0]; //initial last point
                                    if (yj == yi && yj == long && lat > Math.min(xj, xi) && lat < Math.max(xj, xi)) { // Check if point is on an horizontal polygon boundary
                                        // console.log("benar point is on an horizontal polygon boundary");
                                        return true;    
                                    }
                                        
                                    if (long > Math.min(yj, yi) && long <= Math.max(yj, yi) && lat <= Math.max(xj, xi) && yj != yi) {
                                        ss = (long - yj) * (xi - xj) / (yi - yj) + xj; //for determine direction of the virtual line
                                        // console.log(lat);
                                        // console.log("(" + long + "-"+ yj + ") * (" + xi + "-" + xj + ") / (" + yi + "-" + yj + ") + " + xj);
                                        // console.log(ss);
                                        if (ss == lat) { // Check if point is on the polygon boundary (other than horizontal)
                                            // console.log("benar point is on the polygon boundary (other than horizontal)");
                                            return true;
                                        }
                                        if (xj == xi || lat <= ss) {
                                            // console.log("benar intersection");
                                            // console.log(xj + "==" + xi + " || " + lat + " <= " + ss);
                                            // crime_value = combine[26][2];
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
                            }
                        }
                    }
                }); 
            });
        });
    } else {
        //No browser support geolocation service
        alert("Geolocation is not supported by this browser.");
    }
};
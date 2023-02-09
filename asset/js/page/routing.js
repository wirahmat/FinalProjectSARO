//Routing System--------------------------------------------------------------
var routingMaster = null;
var close = null;

var removeRoutingControl = function () {
    if (routingMaster != null) {
        map.removeControl(routingMaster);
        map.removeControl(close);
        map.removeControl(infoRoute);
        routingMaster = null;
        close = null;
    }
};

var infoRoute = null;

function routing(position){
    if (routingMaster != null){
        removeRoutingControl();
    }
    else{
        routingMaster = L.Routing.control({
            waypoints: [
                L.latLng(position.coords.latitude, position.coords.longitude)
            ],
            geocoder: L.Control.Geocoder.nominatim(),
            show : true
        }).addTo(map);
        closeRoutingButton();
        routingMaster.on('routeselected', function(e) {
            var route = e.route;
            var routeCoordinate = route.coordinates;
            // Do something with the route here
            // console.log(routeCoordinate.length);
            var subPoly = combine;
            var subdistrict = [];
            var postal_code = [];
            var village = [];
            var polyPoints;
            var get_sub_data;
            var crime_value = 0;
            var routeLineSample = [];
            // console.log(subPoly);
            var loopRange = Math.ceil(routeCoordinate.length / 6);
            console.log(loopRange);
            for (var i = 0; i <= routeCoordinate.length; i+=loopRange){
                var lat = routeCoordinate[i].lat;
                var long = routeCoordinate[i].lng;
                routeLineSample.push([lat,long]);
                // var query = "lat=" + lat + "&lon=" + long; 
                // $.get('https://nominatim.openstreetmap.org/reverse?format=jsonv2&'+ query, function(data){//geocoder nominatim
                //     if(data.address.suburb != ""){
                //         subdistrict.push(data.address.suburb);
                //     }
                //     else if(data.address.village != ""){
                //         village.push(data.address.village);
                //     }
                //     else if(data.address.postcode != ""){
                //         postal_code.push(data.address.postcode);
                //     }
                // });
            }
            routeLineSample.push([routeCoordinate[routeCoordinate.length - 1].lat,routeCoordinate[routeCoordinate.length - 1].lng]);
            // console.log([routeCoordinate[routeCoordinate.length - 1].lat,routeCoordinate[routeCoordinate.length - 1].lng]);
            var loop = routeLineSample.length -1;
            console.log(loop);
            console.log(routeLineSample);
            var startloop = 0;
            getAddressDetail();
            console.log(routeLineSample);
            function getAddressDetail() {
                if (infoRoute != null){
                    map.removeControl(infoRoute);
                }
                setTimeout(function() {   //  call a 1s setTimeout when the loop is called
                    // console.log('hello');   //  your code here
                    var query = "lat=" + routeLineSample[startloop][0] + "&lon=" + routeLineSample[startloop][1]; 
                    console.log(query);
                    $.get('https://nominatim.openstreetmap.org/reverse?format=jsonv2&'+ query, function(data){//geocoder nominatim
                        if(data.address.suburb != ""){
                            subdistrict.push(data.address.suburb);
                        }
                        if(data.address.village != ""){
                            village.push(data.address.village);
                        }
                        if(data.address.postcode != ""){
                            postal_code.push(data.address.postcode);
                        }
                    });
                // });
                    // console.log(routeLineSample[startloop]);
                    if (startloop < loop) {
                        startloop++;
                        getAddressDetail();
                    }
                    else{

                        console.log(startloop, loop);
                        console.log(subdistrict);
                        console.log(postal_code);
                        console.log(village);
                        $.ajax({
                            url: base_url + "/saferoutefinpro/home/get_user_subdistrict_route",
                            method:"POST",
                            data:{postal_code:postal_code, village:village},
                            success:function(response){
                                console.log("sukses masuk ajax");
                                console.log(response);
                                get_sub_data = JSON.parse(response);
                                var data_route_sub = [];
                                console.log(get_sub_data);
                                for (var i = 0; i < get_sub_data.length; i++){
                                    if (get_sub_data[i].length != 0){
                                        data_route_sub.push(get_sub_data[i][0].kecamatan);
                                    }
                                    // for(var j = 0; j < get_sub_data[i].length; j++){
                                    //     var flag_sub_j = [];
                                    //     if(get_sub_data[i][j].kecamatan == sub_flag){
                                    //         data_route_sub.push(get_sub_data[i][j].kecamatan);
                                    //         break;
                                    //     }
                                    //     else if(get_sub_data[i][j].kecamatan != sub_flag){
                                    //         flag_sub_j = [get_sub_data[i][j].kecamatan];
                                    //         if (j == (get_sub_data[i].length - 1)){
                                                
                                    //         }
                                    //     }
                                    // }
                                }
                                var data_info = [];
                                var total_crime_flag;
                                $.ajax({
                                    url: base_url + "/saferoutefinpro/home/get_report_route",
                                    method:"POST",
                                    data: {data_route_sub:data_route_sub},
                                    success:function(response){
                                        var infoAroundRoute = JSON.parse(response);
                                        console.log(infoAroundRoute);
                                        console.log(infoAroundRoute[0]);
                                        console.log(infoAroundRoute[0][0].crime_name);
                                        console.log(infoAroundRoute[0][0].subdistrict);
                                        console.log(infoAroundRoute[0][0].total);
                                        for(var i = 0; i < infoAroundRoute.length; i++){
                                            total_crime_flag = 0;
                                            for(var j = 0; j < infoAroundRoute[i].length; j++){
                                                total_crime_flag += Number(infoAroundRoute[i][j].total);
                                            }
                                            data_info.push(total_crime_flag);
                                        }
                                        infoRoute = L.control({position: 'bottomright'});

                                        infoRoute.onAdd = function (map) {
                                            var div = L.DomUtil.create('div', 'infoRoute');
                                            var flagSub = "";
                                            div.innerHTML += '<p>You will pass through the subdistrict</p>';
                                            for (var i = 0; i < infoAroundRoute.length; i++) {
                                                console.log(infoAroundRoute[i][0].subdistrict);
                                                if(flagSub != infoAroundRoute[i][0].subdistrict){
                                                    div.innerHTML += '<p><b>' + infoAroundRoute[i][0].subdistrict + ' </b> has <b>' + data_info[i] + '</b> Cases</p>';
                                                    flagSub = infoAroundRoute[i][0].subdistrict;
                                                }
                                                // for(var j = 0; j < infoAroundRoute[i].length; j++){
                                                //     div.innerHTML += '<p>' + infoAroundRoute[i][j].total + '</p>';     
                                                // }
                                            }
                                            return div;
                                        };
                                        infoRoute.addTo(map);
                                        console.log(data_info);    
                                    }
                                });
                                console.log(data_route_sub);
                            }
                        });
                        // for (var i = 0; i < )
                    }
                }, 1100)
            }
            // console.log(subdistrict);
            // console.log(postal_code);
            // console.log(village);
            
            
            // $.get('https://nominatim.openstreetmap.org/reverse?format=jsonv2&'+ query, function(data){//geocoder nominatim
            //     if(data.address.postcode != ""){
            //         postal_code.push(data.address.postcode);
            //     }
            //     // else{
            //     //     postal_code = "none";
            //     // }
            //     if(data.address.village != ""){
            //         village.push(data.address.village);
            //     }
            //     // else{
            //     //     village = "none";
            //     // }
            //     if(data.address.suburb != ""){
            //         subdistrict.push(data.address.suburb);
            //     }
            //     // else{
            //     //     subdistrict = "none";
            //     // }
            //     // $.ajax({
            //     //     url: base_url + "/saferoutefinpro/home/get_user_subdistrict",
            //     //     method:"POST",
            //     //     data:{postal_code:postal_code, village:village},
            //     //     success:function(response){
            //     //         get_sub_data = JSON.parse(response);
            //     //         for(var i = 0; i < combine.length; i++){
            //     //             if (get_sub_data == ""){
            //     //                 break;
            //     //             }
            //     //             else if (get_sub_data[0].kecamatan == combine[i][1]){
            //     //                 polyPoints = combine[i][0][0]; //get polygon location
            //     //                 crime_value = combine[i][2];    
            //     //                 console.log(polyPoints);
            //     //                 var intersections = 0;
            //     //                 var inside = false;

            //     //                 //https://www.ignatiuz.com/blog/gis/how-to-check-whether-point-lies-inside-outside-the-polygon/
            //     //                 for (var i = 0, j = polyPoints.length - 1; i < polyPoints.length; j = i++) { //i value move to j, for example if i = 0, next loop j became j=0
            //     //                     var xi = polyPoints[i][1], yi = polyPoints[i][0]; //initial first point 
            //     //                     var xj = polyPoints[j][1], yj = polyPoints[j][0]; //initial last point
            //     //                     if (yj == yi && yj == long && lat > Math.min(xj, xi) && lat < Math.max(xj, xi)) { // Check if point is on an horizontal polygon boundary
            //     //                         return true;    
            //     //                     }
                                                            
            //     //                     if (long > Math.min(yj, yi) && long <= Math.max(yj, yi) && lat <= Math.max(xj, xi) && yj != yi) {
            //     //                         ss = (long - yj) * (xi - xj) / (yi - yj) + xj; //for determine direction of the virtual line
            //     //                         if (ss == lat) { // Check if point is on the polygon boundary (other than horizontal)
            //     //                             return true;
            //     //                         }
            //     //                         if (xj == xi || lat <= ss) {
            //     //                             intersections++; 
            //     //                         } 
            //     //                     } 
            //     //                 }
            //     //                 // If the number of edges we passed through is odd, then it’s in the polygon.
            //     //                 if (intersections % 2 != 0) {
            //     //                     console.log("benar intersection ganjil" + intersections);
            //     //                     if (crime_value > 10){
            //     //                         alert("You are in " + fetchWarningName(crime_value) + " Area, the crime total in this Area is " + crime_value);
            //     //                         return true;
            //     //                     }
            //     //                 } else {
            //     //                         console.log("salah intersection genap" + intersections);
            //     //                         return false;
            //     //                 }
            //     //             }
            //     //         }
            //     //     }
            //     // }); 
            // });
            // // console.log(route.coordinates);
        });
    }
}

function closeRoutingButton(){
    close = L.control();

    close.onAdd = function (map) {
        this._div = L.DomUtil.create('div', 'close'); // Create a div with a class "close"
        this.update();
        return this._div;
    };
    close.update = function () {
        this._div.innerHTML = '<a onclick="closeRouting()">Close Routing</a>';
    };
    close.addTo(map);
}

function closeRouting(){
    removeRoutingControl();
}
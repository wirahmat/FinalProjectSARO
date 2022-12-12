var data;

//Get data for choropleth
fetchLatlongJSON();
function fetchLatlongJSON(){
    $.ajax({
        url: base_url + "/saferoutefinpro/home/get_latlong_json",
        method:"POST",
        success:function(response){
            data = JSON.parse(response);
        }
    });
}

// https://stackoverflow.com/questions/71553840/calculate-the-centerpoint-of-multiple-latitude-longitude-coordinate-pairs
function calculateCenter (locations, lengthData)  {
	console.log("SUKSES MASUK CALCULATE CENTER");
	var latArr = [];
	var longArr = [];
	var latitude = 0;
	var longitude = 0;
	var accuracy = 0;
	const indexObj = Object.keys(locations);
	const valueObj = Object.values(locations);
	var clusters = 0;
	var noise = 0;
	var total = 0;
	var flag = 0;
	var centerpoint = [];
	var R = 6371; // Radius of earth in KM
	var lengthObj = Object.keys(valueObj).length;

	for (var index of valueObj){
		for(var valueIndex of index){
			flag += 1;
			var detailedValue = Object.values(valueIndex);
			if (detailedValue[3] == clusters){
				if (detailedValue[3] == 0){
					noise += 1;
				}
				else if (detailedValue[3] != 0){
					total += 1;
					latArr.push(detailedValue[1]);
					longArr.push(detailedValue[2]);
					accuracy += detailedValue[0];
					latitude += detailedValue[1];
					longitude += detailedValue[2];

					if (flag == lengthData){
						var longmax = Math.max(...longArr);
						var longmin = Math.min(...longArr);
						var indexmax = longArr.indexOf(longmax);
						var indexmin = longArr.indexOf(longmin);

						//https://stackoverflow.com/questions/27928/calculate-distance-between-two-latitude-longitude-points-haversine-formula
						var lat1 = latArr[indexmax];
						var lat2 = latArr[indexmin];
						var lon1 = longArr[indexmax];
						var lon2 = longArr[indexmin];

						var dLat = (lat2 * (Math.PI / 180)) - (lat1 * (Math.PI / 180));
						var dLon = (lon2 * (Math.PI / 180)) - (lon1 * (Math.PI / 180));
						var a = (Math.sin(dLat/2) * Math.sin(dLat/2)) + (Math.cos(lat1 * (Math.PI / 180)) * Math.cos(lat2 * (Math.PI / 180)) * Math.sin(dLon/2) * Math.sin(dLon/2));
						var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
						var d = R * c;
						var range = d;  
						
						var centeredAcc = accuracy;
						var centeredLat = latitude / total;
						var centeredLong = longitude / total;
						centerpoint.push([centeredLat, centeredLong, range, centeredAcc]);
					}
				}
			}
			else if (detailedValue[3] != clusters){
				var longmax = Math.max(...longArr);
				var longmin = Math.min(...longArr);
				var indexmax = longArr.indexOf(longmax);
				var indexmin = longArr.indexOf(longmin);

				//https://stackoverflow.com/questions/27928/calculate-distance-between-two-latitude-longitude-points-haversine-formula
				var lat1 = latArr[indexmax];
				var lat2 = latArr[indexmin];
				var lon1 = longArr[indexmax];
				var lon2 = longArr[indexmin];

				var dLat = (lat2 * (Math.PI / 180)) - (lat1 * (Math.PI / 180));
				var dLon = (lon2 * (Math.PI / 180)) - (lon1 * (Math.PI / 180));
				var a = (Math.sin(dLat/2) * Math.sin(dLat/2)) + (Math.cos(lat1 * (Math.PI / 180)) * Math.cos(lat2 * (Math.PI / 180)) * Math.sin(dLon/2) * Math.sin(dLon/2));
				var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
				var d = R * c;
				var range = d;  
				
				clusters = detailedValue[3];
				var centeredAcc = accuracy;
				var centeredLat = latitude / total;
				var centeredLong = longitude / total;
				centerpoint.push([centeredLat, centeredLong, range, centeredAcc, total]);
				total = 1;
				latitude = 0;
				longitude = 0;
				accuracy = 0;
				latArr = [];
				longArr = [];

				latArr.push(detailedValue[1]);
				longArr.push(detailedValue[2]);
				accuracy += detailedValue[0];
				latitude += detailedValue[1];
				longitude += detailedValue[2];
			}
		}
	}
	console.log(noise);
	console.log(centerpoint);
	return centerpoint;
};

//Finding optimal epsilon ----------------------------------------------------------------
//https://stackoverflow.com/questions/16774935/javascript-function-nearest-geographical-neighbor
function toRad(Value) {
    return Value * Math.PI / 180;
}

function haversine(lat1,lat2,lng1,lng2){
    rad = 6372.8; // for km Use 3961 for miles
    deltaLat = toRad(lat2-lat1);
    deltaLng = toRad(lng2-lng1);
    lat1 = toRad(lat1);
    lat2 = toRad(lat2);
    a = Math.sin(deltaLat/2) * Math.sin(deltaLat/2) + Math.sin(deltaLng/2) * Math.sin(deltaLng/2) * Math.cos(lat1) * Math.cos(lat2); 
    c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
    return  rad * c;
}
function calculate(){
	var lati1 = data[0].location.latitude;
	var long1 = data[0].location.longitude;
    var result = haversine(lati1,data[1].location.latitude ,long1,data[1].location.longitude);
        for (var i=1;i<Object.keys(data).length;i++){ 
        var ans = haversine(lati1,data[i].location.latitude ,long1,data[i].location.longitude);
        if (ans < result){ //nearest 
            result = ans;
        }       
    }
    console.log("Result " +result);
	return result;
}

// Hotspot crime using jBDSCAN----------------------------------------------------------------
var hotspotMaster = [];
var hotspots_mark = new L.layerGroup ();

function getHotspotCrime(){
	var masuk = []
	var epsilon = calculate();
	var minPoints = 1; 

	// Configure a DBSCAN instance.
	var dbscanner = jDBSCAN().eps(epsilon).minPts(minPoints).distance('HAVERSINE').data(data);
	var point_assignment_result = dbscanner();
	var cluster_centers = dbscanner.getClusters(); 
	for (var i = 0; i < data.length; i++){
		data[i].location.cluster = point_assignment_result[i];
		masuk.push(data[i].location);
	};

	const groupByCompany = masuk.groupBy((car) => {
		return car.cluster;
	});
	
	var centerClustering = calculateCenter(groupByCompany, point_assignment_result.length);
	centerClustering.shift();

	for (var circle of centerClustering) {
		hotspotMaster.push((L.circle([circle[0],circle[1]],{color: "red", fillColor: "#f03", radius: circle[3]}).addTo(hotspots_mark)).bindPopup("There are "+circle[4]+" cases within a "+circle[3]+" meter radius"));
	}
}
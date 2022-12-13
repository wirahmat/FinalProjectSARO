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
						var centeredAcc = accuracy;
						var centeredLat = latitude / total;
						var centeredLong = longitude / total;
						centerpoint.push([centeredLat, centeredLong, centeredAcc, total]);
					}
				}
			}
			else if (detailedValue[3] != clusters){				
				clusters = detailedValue[3];
				var centeredAcc = accuracy;
				var centeredLat = latitude / total;
				var centeredLong = longitude / total;
				centerpoint.push([centeredLat, centeredLong, centeredAcc, total]);

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
    // console.log("Result " +result);
	return result;
}

// Hotspot crime using jBDSCAN----------------------------------------------------------------
var hotspotMaster = [];
var hotspots_mark = new L.layerGroup ();

function getHotspotCrime(){
	var dataWithCluster = []
	var epsilon = calculate();
	var minPoints = 1; 

	// Configure a DBSCAN instance.
	var dbscanner = jDBSCAN().eps(epsilon).minPts(minPoints).distance('HAVERSINE').data(data);
	var point_assignment_result = dbscanner();
	// var cluster_centers = dbscanner.getClusters(); 
	for (var i = 0; i < data.length; i++){
		data[i].location.cluster = point_assignment_result[i];
		dataWithCluster.push(data[i].location);
	};
	const groupByCluster = dataWithCluster.groupBy((clusterGroup) => {
		return clusterGroup.cluster;
	});
	
	var centerClustering = calculateCenter(groupByCluster, point_assignment_result.length);
	centerClustering.shift();

	for (var circle of centerClustering) { 
		hotspotMaster.push((L.circle([circle[0],circle[1]],{color: "red", fillColor: "#f03", radius: circle[2]}).addTo(hotspots_mark)).bindPopup("There are "+circle[3]+" cases within a "+circle[2]+" meter radius"));
	}
}
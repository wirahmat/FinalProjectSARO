var data;

fetchLatlongJSON();

function fetchLatlongJSON(){
    $.ajax({
        url: base_url + "/saferoutefinpro/home/get_latlong_json",
        method:"POST",
        success:function(response){
            data = JSON.parse(response);
			// data = response;
            // console.log(data[0]);
            // console.log(response);
            // $('#crime-type').html(response);
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
	// console.log(indexObj);
	// console.log(valueObj);
	var clusters = 0;
	var noise = 0;
	var total = 0;
	var flag = 0;
	var centerpoint = [];
	var R = 6371; // Radius of earth in KM
	// console.log(indexObj);
	// console.log(valueObj);
	var lengthObj = Object.keys(valueObj).length;
	// console.log(length);
	for (var index of valueObj){
		// console.log(index);
		for(var valueIndex of index){
			flag += 1;
			// console.log(valueIndex);
			var detailedValue = Object.values(valueIndex);
			// console.log(detailedValue);
			if (detailedValue[3] == clusters){
				if (detailedValue[3] == 0){
					noise += 1;
				}
				else if (detailedValue[3] != 0){
					// console.log(clusters);
					total += 1;
					latArr.push(detailedValue[1]);
					longArr.push(detailedValue[2]);
					accuracy += detailedValue[0];
					latitude += detailedValue[1];
					longitude += detailedValue[2];
					// console.log(detailedValue);
					// console.log(flag);
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
						
						var centeredAcc = accuracy / total;
						var centeredLat = latitude / total;
						var centeredLong = longitude / total;
						centerpoint.push([centeredLat, centeredLong, range, centeredAcc]);
					}
				}
			}
			else if (detailedValue[3] != clusters){
				// console.log(latArr);
				// console.log(clusters);
				// console.log(longArr);
				// console.log(total);
				var longmax = Math.max(...longArr);
				var longmin = Math.min(...longArr);
				var indexmax = longArr.indexOf(longmax);
				var indexmin = longArr.indexOf(longmin);

				// var rangeLat = Math.max(...latArr) - Math.min(...latArr);
				// var rangeLong = Math.max(...longArr) - Math.min(...longArr);

				//https://stackoverflow.com/questions/27928/calculate-distance-between-two-latitude-longitude-points-haversine-formula
				var lat1 = latArr[indexmax];
				var lat2 = latArr[indexmin];
				var lon1 = longArr[indexmax];
				var lon2 = longArr[indexmin];

				// console.log(lat1, lat2);

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
				centerpoint.push([centeredLat, centeredLong, range, centeredAcc]);
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
				// console.log(clusters);
			}
		}
	}
	// // console.log(noise);
	// console.log(flag);
	// console.log(centerpoint);
	return centerpoint;
	// for (var location of locations) {
	// 	console.log(location);
	// //   longitude += location.geoCode.longitude;
	// //   latitude += location.geoCode.latitude;
	// }
	// latitude = latitude / locations.length;
	// longitude = longitude / locations.length;
  
	// return {latitude, longitude};
};

var hotspotMaster = [];
var hotspots_mark = new L.layerGroup ();

function getHotspotCrime(){
	var masuk = []
	var epsilon = 200;
	var angle_to_meter_ratio = 	0.01 / 1.11 
	var angle_to_meter = epsilon * angle_to_meter_ratio;
	var minPoints = 3; //Because the data is 3 dimensional, there is 3 feature/ attributes inside the data 

	// Configure a DBSCAN instance.
	var dbscanner = jDBSCAN().eps(angle_to_meter).minPts(minPoints).distance('HAVERSINE').data(data);
	var point_assignment_result = dbscanner();
	var cluster_centers = dbscanner.getClusters(); 
	// console.log(cluster_centers);
	// console.log(point_assignment_result.length);
	for (var i = 0; i < data.length; i++){
		// console.log(data[i].location);
		// masuk.push(data[i].location);
		data[i].location.cluster = point_assignment_result[i];
		masuk.push(data[i].location);
	};
	// console.log(masuk);

	// https://edisondevadoss.medium.com/javascript-group-an-array-of-objects-by-key-afc85c35d07e
	// let group = masuk.reduce((r, a) => {
	// 	// console.log("a", a);
	// 	// console.log('r', r);
	// 	r[a.cluster] = [...r[a.cluster] || [], a];
	// 	return r;
	//    }, {});

	// console.log("group", group);
	const groupByCompany = masuk.groupBy((car) => {
		return car.cluster;
	});
	// console.log(groupByCompany);
	
	var centerClustering = calculateCenter(groupByCompany, point_assignment_result.length);
	// console.log(centerClustering);
	centerClustering.shift();
	for (var circle of centerClustering) {
		// console.log(circle[3]);
		hotspotMaster.push(L.circle([circle[0],circle[1]],{color: "red", fillColor: "#f03", radius: circle[3]}).addTo(hotspots_mark));
		// pointsMaster.push(L.marker([points.latitude_pos, points.longitude_pos]).on('click', markerOnClick));
		// pointsMaster[i].bindPopup("<a href='google.com'>Daerah Berbahaya</a> " + i);
		// points_data = [pointsMaster[i], points.crime_name, ]
	}
	  
	// const byClusters = masuk.group(({ cluster }) => cluster);
	// console.log(byClusters);
	// L.circle([50.5, 30.5], {radius: 200}).addTo(map);
	// var circle = L.circle([cluster_centers[0].location.latitude, cluster_centers[0].location.longitude ], {
	// 	color: "red",
	// 	fillColor: "#f03",
	// 	fillOpacity: 0.5,
	// 	radius: 2000
	// }).addTo(map);  
}

// var dbscanner = jDBSCAN()
// 	.eps(30)
// 	.minPts(1)
// 	.distance('EUCLIDEAN')
// 	.data(data_XY);

// var cluster_centers = dbscanner.getClusters(); 
// console.log(dbscanner());

// use to hide or show layer
$("#hotspot").click(function(event) {
    event.preventDefault();
    if(map.hasLayer(hotspots_mark)) {
        $(this).removeClass('selected');
        map.removeLayer(hotspots_mark);
    } else {
        map.addLayer(hotspots_mark);        
        $(this).addClass('selected');
        getHotspotCrime();
    }
});
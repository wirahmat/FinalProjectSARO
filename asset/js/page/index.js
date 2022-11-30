var base_url = window.location.origin;

// Initialize the map
var map = L.map('map').setView([-6.127788, 107.313304], 11);

// Set map boundary and bouce back
// map.setMaxBounds(map.getBounds());

// Load a tile layer, load stamen as tile layer
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);
// L.tileLayer('https://stamen-tiles-{s}.a.ssl.fastly.net/toner/{z}/{x}/{y}{r}.{ext}', {
// 	subdomains: 'abcd',
// 	minZoom: 1,
// 	maxZoom: 16,
// 	ext: 'png',
// }).addTo(map);

var info = L.control();

info.onAdd = function (map) {
    this._div = L.DomUtil.create('div', 'info'); // Create a div with a class "info"
    this.update();
    return this._div;
};

// Method that we will use to update the control based on feature properties passed
info.update = function (props) {
    this._div.innerHTML = '<h4>Crime Total</h4>' +  (props ?
    '<b>' + props.NAME_3 + '</b><br />' + props.CRIME_VAL + ' Cases'
    : 'Click Choropleth and<br> Hover over a subdistrict');
};

info.addTo(map);

function getColor(d) {
    return d > 100 ? '#800026' :
           d > 50  ? '#BD0026' :
           d > 20  ? '#E31A1C' :
           d > 10  ? '#FC4E2A' :
           d > 5   ? '#FD8D3C' :
           d > 2   ? '#FEB24C' :
           d > 1   ? '#FED976' :
                      '#FFEDA0';
}
//ROUTING SYSTEM
function getLocation(action) {
    if (action == 'route'){
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(routing);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }
    else if (action == 'report'){
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(fetchLocationReport);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }
    else if (action == 'getPolygon'){
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(fetchPolygon);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }
}
    

// function showPosition(position) {
//     document.getElementById("start_location").value = position.coords.latitude + ", " + position.coords.longitude;
//     alert("Latitude: " + position.coords.latitude + " Longitude: " + position.coords.longitude);
// }

var routingMaster = null;
var close = null;

var removeRoutingControl = function () {
    if (routingMaster != null) {
        map.removeControl(routingMaster);
        map.removeControl(close);
        routingMaster = null;
        close = null;
    }
};

function routing(position){
    if (routingMaster != null){
        removeRoutingControl();
    }
    else{
        // var start = document.getElementById("start_location").value;
        // var finish = document.getElementById("destination").value;
        // console.log(start, finish);
        // console.log(typeof(start));
        routingMaster = L.Routing.control({
            waypoints: [
                L.latLng(position.coords.latitude, position.coords.longitude)
            ],
            geocoder: L.Control.Geocoder.nominatim(),
            show : true
        }).addTo(map);
        closeRoutingButton();
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
    // routingMaster.show = false;
    removeRoutingControl();
}

var popup = L.popup();

function onMapClick(e) {
    var point = e.latlng.toString();
    var radius = 10;
    var pointOnly = point.replace('LatLng(','');
    var pointDelete = pointOnly.replace(')','');
    var complete = pointDelete + "," + radius;
    popup
    .setLatLng(e.latlng)
    .setContent("<a data-toggle='modal' data-target='#report' onclick='reportWithClick("+complete+")'>Report at this point </a>" + e.latlng.toString() + radius)
    .openOn(map);
}
map.on('click', onMapClick);


// create control and add to map
var lc = L.control.locate({
    locateOptions: {enableHighAccuracy: true},
    strings: {
        title: "Show me where I am, yo!"
    },
    _drawMarker: function() {
         // override to customize the marker
       }
}).addTo(map);

// request location update and set location
// lc.start();

// function PointCrime(){
//     fetch("./static/assets/clustering.json")
//         .then(response=>response.json())
//         .then(data=>{
//         var mydata = data;
//         var index = mydata.length;
//         for (i=0; i<=index-1; i++){
//             var circle = L.circle([mydata[i].latitude, mydata[i].longitude], {
//             color: 'red',
//             fillColor: '#f03',
//             fillOpacity: 0.5,
//             radius: mydata[i].range
//             }).addTo(map);
//         circle.bindPopup("Daerah Berbahaya");
//         }
            
//         });
//     }
// PointCrime();

function defaultStyle(feature) {
    return {
        fillColor: getColor(feature.properties.CRIME_VAL),
        weight: 1,
        opacity: 0.7,
        color: '#999',
        dashArray: '3',
        fillOpacity: 0.4,
    };
}

// var selectedStyle = {
//     fillColor: 'E14D2A',
//     weight: 2,
//     opacity: 1,
//     color: '#cc0000',
//     fillOpacity: 0.2   
// }

function highlightFeature(e) {
    var layer = e.target;

    layer.setStyle({
        weight: 5,
        color: '#666',
        dashArray: '',
        fillOpacity: 0.9
    });

    if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
        layer.bringToFront();
    }
    info.update(layer.feature.properties);
}

function resetHighlight(e) {
    geoLayer.resetStyle(e.target);
    info.update();
}

function zoomToFeature(e) {
    map.fitBounds(e.target.getBounds());
}

// Use geopackage to set a layer

// var jakartaDistrict = L.geoPackageFeatureLayer([], {
//     geoPackageUrl: "./asset/geopackage/Kecamatan-DKI-Jakarta.gpkg",
//     layerName: 'Kecamatan-Karawang',
//     onEachFeature : function(feature, layer, e){
//         // var name = feature.properties.name;
//         // var crimerate = feature.properties.crimerate;
//         layer.on('click', function() {layer.bindPopup(": " );});

//         layer.on('mouseout', function() {layer.closePopup();});

//         layer.on({
//             mouseover: highlightFeature,
//             mouseout: resetHighlight,
//             click: zoomToFeature
//         });
//     }
// }).addTo(map);


// geojson = L.geoJson(jakartaDistrict, {
//     style: style,
//     onEachFeature: onEachFeature
// }).addTo(map);

var combine = [];
var val = new L.layerGroup ()
// Use geojson to set a layer

var karawangDistrict = $.getJSON(base_url+"/saferoutefinpro/asset/geopackage/gadm36_IDN_3.geojson", function(data){
    geoLayer = L.geoJson(data, {
        // style: function(feature){
        //     return{
        //         fillOpacity: 0.8,
        //         weight: 2.5,
        //         opacity: 1,
        //         color:"#008CFF"
        //     };
        // },
        // style: function(feature){
        //     return{
        //         fillColor: getColor(feature.properties.CRIME_VAL),
        //             weight: 1,
        //             opacity: 0.7,
        //             color: '#999',
        //             dashArray: '3',
        //             fillOpacity: 0.2
        //     };
        // },
        style : defaultStyle,
        onEachFeature : function(feature, layer, e){
            var polygon = feature.geometry.coordinates;
            var subdistrict = feature.properties.NAME_3;
            var crime_value = feature.properties.CRIME_VAL;
            combine.push([polygon,subdistrict,crime_value]);
            // console.log(polygon);
            // console.log(subdistrict);
            // console.log(combine[0][0]);

            // var name = feature.properties.name;
            // var crimerate = feature.properties.crimerate;
            // layer.on('click', function() {layer.bindPopup(": " );});
            
            // layer.on('mouseout', function() {layer.closePopup();});

            // layer.on('mouseover', () => {
            //     layer.setStyle(selectedStyle);
            //     info.update(layer.feature.properties);
            // });
            // layer.on('mouseout', () => {
            //     layer.setStyle(defaultStyle); 
            //     info.update();
            // });
              
            layer.on({
                mouseover: highlightFeature,
                mouseout: resetHighlight
            });
        }
    }).addTo(val);
    isMarkerInsidePolygon(combine);
    // lc.start();
})

// use to hide or show layer
$("#choropleth").click(function(event) {
    event.preventDefault();
    if(map.hasLayer(val)) {
        $(this).removeClass('selected');
        map.removeLayer(val);
        removeLegend();
    } else {
        map.addLayer(val);        
        $(this).addClass('selected');
        addLegend();
    }
});

var legend = null;
// Legend Map

function addLegend(){
    legend = L.control({position: 'bottomright'});

    legend.onAdd = function (map) {

        var div = L.DomUtil.create('div', 'info legend'),
            grades = [0, 1, 2, 5, 10, 20, 50, 100],
            labels = [];

        // loop through our density intervals and generate a label with a colored square for each interval
        for (var i = 0; i < grades.length; i++) {
            div.innerHTML +=
                '<i style="background:' + getColor(grades[i] + 1) + '"></i> ' +
                grades[i] + (grades[i + 1] ? '&ndash;' + grades[i + 1] + '<br>' : '+');
        }

        return div;
    };

    legend.addTo(map);
}

function removeLegend(){
    if (legend != null) {
        map.removeControl(legend);
        legend = null;
    }
}

var base_url = window.location.origin;

// Initialize the map
var map = L.map('map',{
    zoomControl: false
}).setView([-6.127788, 107.313304], 11);

//Menu Button
L.easyButton('<span><i class="fa-solid fa-bars"></i> Menu</span>', function(btn, map){
    openNav();
}, 'Menu Bar', 'menu').addTo(map);

//Layer Button
L.easyButton('<span><i class="fa-solid fa-layer-group"></i> Layer</span>', function(btn, map){
}, '', 'filter').setPosition('topleft').addTo(map);

//Zoom Button
L.control.zoom({
    position: 'topleft'
}).addTo(map);

// Load a tile layer, load stamen as tile layer
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

// Choropleth Information
var info = L.control();

info.onAdd = function (map) {
    this._div = L.DomUtil.create('div', 'info'); // Create a div with a class "info"
    this.update();
    return this._div;
};

// Method that we will use to update the control based on feature properties passed
info.update = function (props) {
    this._div.innerHTML = '<h4><b>Crime Total</b></h4>' +  (props ?
    '<b>' + props.NAME_3 + '</b><br />' + props.CRIME_VAL + ' Cases'
    : 'Click Choropleth and<br> Hover over a subdistrict');
};

// Filter Content -------------------------------------------------------
const filterBtn = `
	<a href="#" id="choropleth" class="menu-btn" onclick="choroplethFilter()"><i class="icon fa-solid fa-map"></i><br><span>Choropleth Map</span></a><br><br>
    <a href="#" id="hotspot" class="menu-btn" onclick="hotspotFilter()"><i class="icon fa-solid fa-map-location-dot"></i><br><span>Hotspot Crime</span></a>
`;

// const statisticBtn = `
// 	<a href="#" id="choropleth" class="menu-btn" onclick="choroplethFilter()"><i class="icon fa-solid fa-map"></i><br><span>Choropleth Map</span></a><br><br>
//     <a href="#" id="hotspot" class="menu-btn" onclick="hotspotFilter()"><i class="icon fa-solid fa-map-location-dot"></i><br><span>Hotspot Crime</span></a>
// `;

$(document).ready(function(data){
    $('#filter').popover({
        placement: 'right',
        html: true,
        content: filterBtn
    });
    // $('#statistic-button').popover({
    //     placement: 'left',
    //     html: true,
    //     content: statisticBtn
    // });
});

function choroplethFilter(){
    const layerbut = document.getElementById('filter');
    document.getElementById("choropleth").style.color = "white";
    document.getElementById("hotspot").style.color = "#818181";
    $('#filter').popover('hide');
    if(map.hasLayer(hotspots_mark)) {
        $(this).removeClass('selected');
        map.removeLayer(hotspots_mark);
    } 
    if(map.hasLayer(val)) {
        $(this).removeClass('selected');
        map.removeLayer(val);
        removeLegend();
        map.removeControl(info);
        layerbut.innerHTML = '<span><i class="fa-solid fa-layer-group"></i> Layer</span>';
        layerbut.style.width = "30px";
        layerbut.style.height = "30px";
    } else {
        map.addLayer(val);        
        $(this).addClass('selected');
        addLegend();
        info.addTo(map);
        layerbut.innerHTML = '<i class="icon fa-solid fa-map"></i>';
        layerbut.style.width = "100px";
        layerbut.style.height = "50px";
    }
    map.setView([-6.127788, 107.313304], 10);
}

function hotspotFilter(){
    const layerbut = document.getElementById('filter');
    document.getElementById("choropleth").style.color = "#818181";
    document.getElementById("hotspot").style.color = "white";
    $('#filter').popover('hide');
    if(map.hasLayer(val)) {
        $(this).removeClass('selected');
        map.removeLayer(val);
        removeLegend();
        map.removeControl(info);
    }
    // use to hide or show layer
    if(map.hasLayer(hotspots_mark)) {
        $(this).removeClass('selected');
        map.removeLayer(hotspots_mark);
        layerbut.innerHTML = '<span><i class="fa-solid fa-layer-group"></i> Layer</span>';
        layerbut.style.width = "30px";
        layerbut.style.height = "30px";
    } else {
        map.addLayer(hotspots_mark);        
        $(this).addClass('selected');
        getHotspotCrime();
        layerbut.innerHTML = '<i class="icon fa-solid fa-map-location-dot"></i>';
        layerbut.style.width = "100px";
        layerbut.style.height = "50px";
    }
    map.setView([-6.127788, 107.313304], 10);
}

// function showInputDate(){
//     var dateInputStart = document.getElementById("starting_date");
//     var dateInputEnd = document.getElementById("end_date");
//     var displayInputStart = dateInputStart.style.display;
//     var displayInputEnd = dateInputEnd.style.display;
//     console.log(displayInputStart);
//     console.log(displayInputStart);
//     if (displayInputStart == "" || displayInputStart == "none"){
//         displayInputStart = "block";
//         displayInputEnd = "block";
//     }
//     else{
//         displayInputStart = "none";
//         displayInputEnd = "none";
//     }
//     console.log(displayInputStart);
//     console.log(displayInputStart);
// }

//Coloring Choropleth----------------------------------------------------------
var valArr = [];
function getColor(d) {
    return d >= valArr[6] ? '#800026' :
           d >= valArr[5] ? '#BD0026' :
           d >= valArr[4] ? '#E31A1C' :
           d >= valArr[3] ? '#FC4E2A' :
           d >= valArr[2] ? '#FD8D3C' :
           d >= valArr[1] ? '#FEB24C' :
           d >= valArr[0] ? '#FED976' : '#FFEDA0';
}

//Get Location-----------------------------------------------------------------
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

//Report Popup-----------------------------------------------------------------------------------
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

// create control and add to map----------------------------------------------------------------
var lc = L.control.locate({
    locateOptions: {enableHighAccuracy: true},
    strings: {
        title: "Show me where I am"
    },
}).addTo(map);

// setting up style for choropleth 
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

// Loading geojson choropleth data----------------------------------------------------
var combine = [];
var val = new L.layerGroup ()
var subVal = [];

// Use geojson to set a layer
var karawangDistrict = $.getJSON(base_url+"/saferoutefinpro/asset/geopackage/gadm36_IDN_3.geojson", function(data){
    geoLayer = L.geoJson(data, {
        style : defaultStyle,
        onEachFeature : function(feature, layer, e){
            var polygon = feature.geometry.coordinates;
            var subdistrict = feature.properties.NAME_3;
            var crime_value = feature.properties.CRIME_VAL;
            combine.push([polygon,subdistrict,crime_value]);
            subVal.push(Number(crime_value));             
            layer.on({
                mouseover: highlightFeature,
                mouseout: resetHighlight,
                click: zoomToFeature
            });
        }
    }).addTo(val);
    isMarkerInsidePolygon(combine);
})

//Legend for Choropleth---------------------------------------------------------------
//https://stackoverflow.com/questions/27450216/how-do-i-create-a-distribution-group-of-a-range-of-numbers-by-a-given-number-of
function legendDistribution() {
    var arr = [];
    var max = Math.max(...subVal); //rest operator
    var repeatval = max / 7;
    var bottomlimit = 0;

    for (var i = 0; i < max; i += repeatval) {
        if (Math.ceil(i) != i || i==0) {
            bottomlimit = Math.ceil(i);
        } 
        else {
            bottomlimit = Math.ceil(i)+1;
        }
        arr.push({
            "limit": (Math.floor(repeatval+i)),
            "literal": bottomlimit + "-" + (Math.floor(repeatval+i))
        });
        valArr.push((Math.floor(repeatval+i)));
    }
    geoLayer.resetStyle();
    return arr; 
}

var legend = null;

// Legend Map
function addLegend(){
    legend = L.control({position: 'bottomleft'});
    var distributionL = legendDistribution();
    var gradeArr = [];
    for(var j = 0; j < distributionL.length; j++){
        gradeArr.push(distributionL[j].limit);
    }
    legend.onAdd = function (map) {
        gradeArr.pop()
        var div = L.DomUtil.create('div', 'info legend'),
            grades = gradeArr,
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

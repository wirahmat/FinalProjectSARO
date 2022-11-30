var base_url = window.location.origin;

load_data();

function load_data(){
    $.ajax({
        url: base_url + "/saferoutefinpro/home/get_data_report",
        method:"POST",
        success:function(response){
            // console.log(query);
            // console.log( filter);
            // console.log("2" + data);
            document.getElementById('close-modal-statistic-detail').click();
            $('#show_data').html(response);
        }
    });
}
// getDetailReport('\"Klari\"');

function getDetailReport(subdistrict){
    $.ajax({
        url: base_url + "/saferoutefinpro/home/get_detail_data_report",
        method:"POST",
        data:{subdistrict:subdistrict},
        success:function(response){
            // console.log(query);
            // console.log( filter);
            // console.log("2" + data);
            document.getElementById('close-modal-statistic').click();
            $('#show_data_detail').html(response);
        }
    });
}

var pointsMaster = [];
var closeMarks = null;

var removePointsControl = function () {
    if (pointsMaster != null) {
        // map.removeLayer(points_mark);
        map.removeControl(points_mark);
        map.removeControl(closeMarks);
        pointsMaster = [];
        closeMarks = null;
    }
};

function clearPointButton(){
    if (closeMarks == null){
        closeMarks = L.control();

        closeMarks.onAdd = function (map) {
            this._div = L.DomUtil.create('div', 'close'); // Create a div with a class "close"
            this.update();
            return this._div;
        };
        closeMarks.update = function () {
            this._div.innerHTML = '<a onclick="closePoints()">Clear</a>';
        };
        closeMarks.addTo(map);
    }
}

function closePoints(){
    // routingMaster.show = false;
    removePointsControl();
}

var points_mark;
var points_data;
function getPointReport(subdistrict, crime_name){

    $.ajax({
        url: base_url + "/saferoutefinpro/home/get_report_points",
        method:"POST",
        data:{subdistrict:subdistrict, crime_name:crime_name},
        success:function(response){
            data = JSON.parse(response);
            console.log(data);
            points_data = data;
            var i = 0;
            for (var points of data) {
                pointsMaster.push(L.marker([points.latitude_pos, points.longitude_pos]).on('click', markerOnClick));
                // pointsMaster[i].bindPopup("<a href='google.com'>Daerah Berbahaya</a> " + i);
                // points_data = [pointsMaster[i], points.crime_name, ]
                i += 1;
            }
            points_mark = L.layerGroup(pointsMaster).addTo(map);
        }
    });
    clearPointButton();
}

function markerOnClick(i){
    for (var datas of points_data){
        if (i.latlng.lat == datas.latitude_pos){
            var point_detail = [datas.crime_name, datas.description_crime, datas.file_name, datas.input_date, datas.latitude_pos, datas.longitude_pos, datas.subdistrict];
            console.log(point_detail);
            // alert(datas.crime_name + " " + datas.description_crime + " " + datas.file_name + " " + datas.input_date + " " + datas.latitude_pos + " " + datas.longitude_pos + " " + datas.subdistrict);
            $("#subdistrict").text(point_detail[6].toUpperCase());
            $("#crime_name").text(point_detail[0]);
            $("#descr").text(point_detail[1]);
            $("#latlong").text(point_detail[4] + ", " + point_detail[5]);
            var img = document.getElementById("image_crime");
            img.src = base_url + '/saferoutefinpro/asset/upload/' + point_detail[2];
            $('#detailed_point').modal('show'); 
        }
    }
    
}
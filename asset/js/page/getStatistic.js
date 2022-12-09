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
            // console.log(response);
            $('#show_data').html(response);
            dataChart();
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
            dataDetailChart(subdistrict);
        }
    });
}

var pointsMaster = [];
var closeMarks = null;
var points_mark;
var points_data;

function getPointReportAll(action){
    $.ajax({
        url: base_url + "/saferoutefinpro/home/get_all_report_points",
        method:"POST",
        data:{action:action},
        success:function(response){
            // console.log(response);
            var dataPointReport = JSON.parse(response);
            // console.log(dataPointReport);
            points_data = dataPointReport;
            var i = 0;
            for (var points of dataPointReport) {
                pointsMaster.push(L.marker([points.latitude_pos, points.longitude_pos]).on('click', markerOnClick));
                // pointsMaster[i].bindPopup("<a href='google.com'>Daerah Berbahaya</a> " + i);
                // points_data = [pointsMaster[i], points.crime_name, ]
                i += 1;
            }
            console.log(points_data[0].latitude_pos);
            console.log(points_data[0].longitude_pos);
            points_mark = L.layerGroup(pointsMaster).addTo(map);
            // console.log( filter);
            // console.log("2" + data);
            document.getElementById('close-modal-statistic-detail').click();
            document.getElementById('close-modal-statistic').click();
            map.setView([points_data[0].latitude_pos, points_data[0].longitude_pos], 12.2);
            // $('#show_data_detail').html(response);
        }
    });
    clearPointButton();
}

function dataChart(){
    $.ajax({
        url: base_url + "/saferoutefinpro/home/get_data_chart",
        method:"POST",
        success:function(response){
            var label = [];
            var value = [];
            data_sub = JSON.parse(response);
            // console.log(data[0].subdistrict);
            for (var i in data_sub) {
                label.push(data_sub[i].subdistrict);
                value.push(data_sub[i].total);
            }
            // console.log(label);
            // console.log(value);
            var ctx = document.getElementById('myChart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: label,
                    datasets: [{
                        label: 'Total Crime per Subdistrict',
                        // backgroundColor: 'rgb(252, 116, 101)',
                        // borderColor: 'rgb(255, 255, 255)',
                        data: value
                    }]
                },
                options: {}
            });
            // $('#show_data_detail').html(response);
        }
    });
}

function dataDetailChart(subdistrict){
    $.ajax({
        url: base_url + "/saferoutefinpro/home/get_detail_data_chart",
        method:"POST",
        data:{subdistrict:subdistrict},
        success:function(response){
            var label = [];
            var value = [];
            data_sub_detail = JSON.parse(response);
            // console.log(data);
            // console.log(data[0].subdistrict);
            for (var i in data_sub_detail) {
                label.push(data_sub_detail[i].crime_name);
                value.push(data_sub_detail[i].total);
            }
            console.log(label);
            console.log(value);
            var ctx = document.getElementById('detailedChart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: label,
                    datasets: [{
                        label: 'Total Crime in Subdisctrict',
                        // backgroundColor: 'rgb(252, 116, 101)',
                        // borderColor: 'rgb(255, 255, 255)',
                        data: value
                    }]
                },
                options: {}
            });
        }
    });
}

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


function getPointReport(subdistrict, crime_name){

    $.ajax({
        url: base_url + "/saferoutefinpro/home/get_report_points",
        method:"POST",
        data:{subdistrict:subdistrict, crime_name:crime_name},
        success:function(response){
            var dataPointReport = JSON.parse(response);
            console.log(dataPointReport);
            points_data = dataPointReport;
            // console.log(points_data);
            var i = 0;
            for (var points of dataPointReport) {
                pointsMaster.push(L.marker([points.latitude_pos, points.longitude_pos]).on('click', markerOnClick));
                // pointsMaster[i].bindPopup("<a href='google.com'>Daerah Berbahaya</a> " + i);
                // points_data = [pointsMaster[i], points.crime_name, ]
                i += 1;
            }
            points_mark = L.layerGroup(pointsMaster).addTo(map);
            map.setView([points_data[0].latitude_pos, points_data[0].longitude_pos], 12.4);
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
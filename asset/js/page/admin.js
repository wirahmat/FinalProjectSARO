var base_url = window.location.origin;

load_data_admin();

function load_data_admin(){
    $.ajax({
        url: base_url + "/saferoutefinpro/admin/get_all_data_admin",
        method:"POST",
        success:function(response){
            $('#showAllData').html(response);
            dataChart();
            dataChartCrime();
            validationStatus();
        }
    });
}

var monthFilter = "";
var yearFilter = "";

calendar();

function calendar(){
    let months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    // var month_selected = (new Date).getMonth(); // current month
    var option = '';
    option = '<option disabled selected>Month</option>'; // first option

    for (let i = 0; i < months.length; i++) {
        let month_number = (i + 1);

        // value month number with 0. [01 02 03 04..]
        let month = (month_number <= 9) ? '0' + month_number : month_number;

        // or value month number. [1 2 3 4..]
        // let month = month_number;

        // or value month names. [January February]
        // let month = months[i];

        // let selected = (i === month_selected ? ' selected' : '');
        option += '<option value="' + month + '">' + months[i] + '</option>';
    }
    document.getElementById("month").innerHTML = option;
    // console.log(document.getElementById("month").value);

    let year_satart = 2010;
    let year_end = (new Date).getFullYear(); // current year
    let year_selected = (new Date).getFullYear();
    // yearFilter = year_selected;

    let option2 = '';
    option2 = '<option disabled selected>Year</option>'; // first option

    for (let i = year_satart; i <= year_end; i++) {
        // let selected = (i === year_selected ? ' selected' : '');
        option2 += '<option value="' + i + '">' + i + '</option>';
        // option2 += '<option value="' + i + '"' + selected + '>' + i + '</option>';
    }

    document.getElementById("year").innerHTML = option2;
    // console.log(document.getElementById("year").value);
}

function validateData(report_id, action){
    swal({
        title: "Are you sure want to Validate this data?",
        text: "Please double check the data before validating the data!!",
        icon: "warning",
        buttons: [
            'No, cancel it!',
            'Yes, I am sure!'
          ],
        dangerMode: true,
    }).then((isConfirm) => {
        if (isConfirm) {
            $.ajax({
                url: base_url + "/saferoutefinpro/admin/send_validate_data",
                method: "POST",
                data:{report_id: report_id, action: action},
                success: function(){
                    swal({
                        title: 'Success!',
                        text: 'Data is successfully validated!',
                        icon: 'success'
                    });
                },
                complete: function() {
                    setInterval(function() {
                        window.location.reload();
                    }, 2000);
                }
            })
        } 
        // else {
        //     swal("Cancelled", "Your data is not validated", "error");
        // }
    });
}
var chart = null;
var chartCrime = null;

function dataChart(monthFilter, yearFilter){
    // alert(monthFilter + " " + yearFilter)
    var date_info_start = ""; 
    var date_info_end = ""; 
    if (monthFilter != undefined  && yearFilter != undefined ){
        date_info_start = yearFilter+"-"+monthFilter+"-1"; 
        date_info_end = yearFilter+"-"+monthFilter+"-31"; 
    }
    var ctx = document.getElementById('myChart').getContext('2d');
    
    // alert(date_info_start + "AND" + date_info_end);
    // console.log("MASUK");
    $.ajax({
        url: base_url + "/saferoutefinpro/admin/get_data",
        method:"POST",
        data:{date_info_start: date_info_start, date_info_end: date_info_end},
        success:function(response){
            // alert("matanya");
            var label = [];
            var value = [];
            var data_sub = JSON.parse(response);
            // console.log(data_sub);
            for (var i in data_sub) {
            // for (var i = 0; i < 10; i++) {
                label.push(data_sub[i].subdistrict);
                value.push(data_sub[i].total);
            }
            document.getElementById("top-crime-sub").innerHTML = "";
            if (label.length > 3){
                // alert("lebih dari 3")
                for(var i = 0; i < 3; i++){
                    $("#top-crime-sub").append("<b><span style='color:red'>"+label[i]+"</span><br> <span style='color:red'>"+ value[i]+" Case(s)</span><br><br></b>");
                    // document.getElementById("subdistrict_name").innerText += "<span style='color:red'>"+ label[i]+"</span>";
                    // document.getElementById("total_crime").innerText += "<span style='color:red'>"+ value[i]+"</span>";
                }
            }
            else if (label.length < 3){
                // alert("kurang dari 3")
                for(var i = 0; i < label.length; i++){
                    $("#top-crime-sub").append("<b><span style='color:red'>"+ label[i]+"</span><br> <span style='color:red'>"+ value[i]+" Case(s)</span><br><br></b>");
                    // document.getElementById("subdistrict_name").innerText += "<span style='color:red'>"+ label[i]+"</span>";
                    // document.getElementById("total_crime").innerText += "<span style='color:red'>"+ value[i]+"</span>";
                }
            }
    
            // document.getElementById("subdistrict_name").innerText = label[0];
            // document.getElementById("total_crime").innerText = value[0];
            
            if (chart == null){
                chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: label,
                        datasets: [{
                            label: 'Total Crime per Subdistrict',
                            data: value
                        }]
                    },
                    options: {}
                });
            }
            else{
                chart.destroy();
                chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: label,
                        datasets: [{
                            label: 'Total Crime per Subdistrict',
                            data: value
                        }]
                    },
                    options: {}
                });
            }
        }
    });
}    

function dataChartCrime(monthFilter, yearFilter){
    // alert(monthFilter + " " + yearFilter)
    var date_info_start = ""; 
    var date_info_end = ""; 
    if (monthFilter != undefined && yearFilter != undefined ){
        date_info_start = yearFilter+"-"+monthFilter+"-1"; 
        date_info_end = yearFilter+"-"+monthFilter+"-31"; 
    }
    var ctx = document.getElementById('myChart2').getContext('2d');

    $.ajax({
        url: base_url + "/saferoutefinpro/admin/get_data_crime",
        method:"POST",
        data:{date_info_start: date_info_start, date_info_end: date_info_end},
        success:function(response){
            // alert("matanya");
            var label = [];
            var value = [];
            var data_sub = JSON.parse(response);
            // console.log(data_sub);
            for (var i in data_sub) {
            // for (var i = 0; i < 10; i++) {
                label.push(data_sub[i].crime_name);
                value.push(data_sub[i].total);
            }
            
            if (chartCrime == null){
                chartCrime = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: label,
                        datasets: [{
                            label: 'Total Crime per Crime Name',
                            data: value,
                            backgroundColor: '#c6fc03',
                        }],
                    },
                    options: {}
                });
            }
            else{
                chartCrime.destroy();
                chartCrime = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: label,
                        datasets: [{
                            label: 'Total Crime per Crime Name',
                            data: value,
                            backgroundColor: '#c6fc03',
                        }], 
                    },
                    options: {}
                });
            }
        }
    });
}    

function validationStatus(monthFilter, yearFilter){
    var date_info_start = ""; 
    var date_info_end = "";
    var total_count = 0;
    if (monthFilter != undefined && yearFilter != undefined ){
        date_info_start = yearFilter+"-"+monthFilter+"-1"; 
        date_info_end = yearFilter+"-"+monthFilter+"-31"; 
    } 
    $.ajax({
        url: base_url + "/saferoutefinpro/admin/get_validation",
        method:"POST",
        data:{date_info_start: date_info_start, date_info_end: date_info_end},
        success:function(response){
            console.log("SUKSES")
            var label = [];
            var value = [];
            var data_sub = JSON.parse(response);
            // console.log(data_sub);
            for (var i in data_sub) {
            // for (var i = 0; i < 10; i++) {
                label.push(data_sub[i].validation);
                value.push(data_sub[i].total);
            }
            document.getElementById("valid-status").innerHTML = "";
            for(var i = 0; i < label.length; i++){
                if (label[i] != ""){
                    $("#valid-status").append("<b><span style='font-weight: bold;'>"+label[i]+"</span><br> <span style='font-weight: bold;'>"+ value[i]+" Case(s)</span><br><br></b>");
                }
                else {
                    $("#valid-status").append("<b><span style='font-weight: bold;'>Not Yet Validated</span><br> <span style='font-weight: bold;'>"+ value[i]+" Case(s)</span><br><br></b>");
                }
            }
            for(var i = 0; i < value.length; i++){
                total_count += parseInt(value[i]);
            }
            document.getElementById("total-crime").innerHTML = "";
            $("#total-crime").append("<b><span style='font-weight: bold;'>"+total_count+" Case(s)</span><br>");
        }
    });
}

function resetFilter(){
    monthFilter = "";
    yearFilter = "";
    dataChart();
    dataChartCrime();
    validationStatus();
    document.getElementById("month").selectedIndex = 0;
    document.getElementById("year").selectedIndex = 0;
}

$(document).ready(function(){

    document.getElementById('month').addEventListener('change', function(){
        monthFilter = this.value;
        dataChart(monthFilter, yearFilter);
        dataChartCrime(monthFilter, yearFilter);
        validationStatus(monthFilter, yearFilter);
        // alert(monthFilter + " " + yearFilter);
    });
      
    document.getElementById('year').addEventListener('change', function(){
        yearFilter = this.value;
        dataChart(monthFilter, yearFilter);
        dataChartCrime(monthFilter, yearFilter);
        validationStatus(monthFilter, yearFilter);
        // alert(monthFilter + " " + yearFilter);
    });
  
});

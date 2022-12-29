var base_url = window.location.origin;

function showInputDate(action,part){
    var now = new Date();
    var justDateNow = now.getFullYear() + "-" + (now.getMonth() + 1) + "-" +  now.getDate();;
    var fixDate;
    if (part == "SubdistrictReport"){
        if (action == "0"){
            justDateNow = "";
            fixDate = "";
            load_data(fixDate, justDateNow);
        }
        else if (action == "1"){
            // console.log(now);
            // justDateNow = 
            // console.log(justDateNow);
            now.setMonth(now.getMonth() - 1);
            fixDate = now.getFullYear() + "-" + (now.getMonth() + 1) + "-" +  now.getDate();
            // console.log("sukses1 " + fixDate);
            // console.log(typeof(fixDate));
            load_data(fixDate, justDateNow);
        }
        else if (action == "6"){
            // justDateNow = now.getFullYear() + "-" + (now.getMonth() + 1) + "-" +  now.getDate();
            now.setMonth(now.getMonth() - 6);
            fixDate = now.getFullYear() + "-" + (now.getMonth() + 1) + "-" +  now.getDate();
            // console.log("sukses6 " + fixDate);
            load_data(fixDate, justDateNow);
        }
        else if (action == "12"){
            // justDateNow = now.getFullYear() + "-" + (now.getMonth() + 1) + "-" +  now.getDate();
            now.setMonth(now.getMonth() - 12);
            fixDate = now.getFullYear() + "-" + (now.getMonth() + 1) + "-" +  now.getDate();
            // console.log("sukses12 " + fixDate);
            load_data(fixDate, justDateNow);
        }
        else if (action == "custom"){
            $('#starting_date').attr('max', justDateNow);
            $('#end_date').attr('max', justDateNow);
            console.log("suksescustom");
            $("#starting_date").toggle();
            var startdate = document.getElementById("starting_date");
            var enddate = document.getElementById("end_date");
            startdate.valueAsDate = new Date();
            // enddate.valueAsDate = new Date();
    
            startdate.onchange = function(){
                $("#end_date").toggle();
                // console.log(this.value);
            }
    
            enddate.onchange = function(){
                var date1 = startdate.value;
                var date2 = enddate.value;
                load_data(date1, date2);
                // console.log(this.value, date1, date2);
            }
        }
    }
    else if (part == "CrimeNameReport"){
        if (action == "0"){
            justDateNow = "";
            fixDate = "";
            load_data_crime_name(fixDate, justDateNow);
        }
        else if (action == "1"){
            // console.log(now);
            // justDateNow = 
            // console.log(justDateNow);
            now.setMonth(now.getMonth() - 1);
            fixDate = now.getFullYear() + "-" + (now.getMonth() + 1) + "-" +  now.getDate();
            // console.log("sukses1 " + fixDate);
            // console.log(typeof(fixDate));
            load_data_crime_name(fixDate, justDateNow);
        }
        else if (action == "6"){
            // justDateNow = now.getFullYear() + "-" + (now.getMonth() + 1) + "-" +  now.getDate();
            now.setMonth(now.getMonth() - 6);
            fixDate = now.getFullYear() + "-" + (now.getMonth() + 1) + "-" +  now.getDate();
            // console.log("sukses6 " + fixDate);
            load_data_crime_name(fixDate, justDateNow);
        }
        else if (action == "12"){
            // justDateNow = now.getFullYear() + "-" + (now.getMonth() + 1) + "-" +  now.getDate();
            now.setMonth(now.getMonth() - 12);
            fixDate = now.getFullYear() + "-" + (now.getMonth() + 1) + "-" +  now.getDate();
            // console.log("sukses12 " + fixDate);
            load_data_crime_name(fixDate, justDateNow);
        }
        else if (action == "custom"){
            $('#starting_date').attr('max', justDateNow);
            $('#end_date').attr('max', justDateNow);
            console.log("suksescustom");
            $("#starting_date").toggle();
            var startdate = document.getElementById("starting_date");
            var enddate = document.getElementById("end_date");
            startdate.valueAsDate = new Date();
            // enddate.valueAsDate = new Date();
    
            startdate.onchange = function(){
                $("#end_date").toggle();
                // console.log(this.value);
            }
    
            enddate.onchange = function(){
                var date1 = startdate.value;
                var date2 = enddate.value;
                load_data_crime_name(date1, date2);
                // console.log(this.value, date1, date2);
            }
        }
    }
    
}


// function time_filter(){
//     var time = document.getElementById("filterBy").value;
//     var search = document.getElementById("logistic-search").value;

//     if(search != '' || filter != '')
//     {
//         load_table(search,filter);
//     }
//     else
//     {
//         load_table();
//     }
    
// }
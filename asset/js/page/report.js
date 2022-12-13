var base_url = window.location.origin;

//Get current location for report
function fetchLocationReport(position){
    var location = position.coords.latitude + "," + position.coords.longitude;
    document.getElementById("location").value = location;
    document.getElementById("accuracy").value = position.coords.accuracy;
}

//Get data for dropdown report
fetchCrime();
function fetchCrime(){
    $.ajax({
        url: base_url + "/saferoutefinpro/home/get_crime",
        method:"POST",
        success:function(response){
            $('#crime-type').html(response);
        }
    });
}

//Get location coordinate from clicking 
function reportWithClick(lat,long,accuracy){
    document.getElementById("location").value = lat + "," + long;
    document.getElementById("accuracy").value = accuracy;
}

//Submit report  
function submitReport(){
    var formEl = document.forms.submit_form;
    var formData = new FormData(formEl);
    var entitiesForm = [];

    var subdistrict = "";
    var postal_code = "";
    var village = "";

    var name = document.getElementById("type_of_crime").value;
    var location = document.getElementById("location").value;
    var description = document.getElementById("description").value;
    var photo = document.getElementById("file1").value;

    var location_split = location.split(",");

    formData.append("name", name);
    formData.append("location", location);

    if (![name,location,description,photo].every(Boolean)) {
        swal("Oops!", "Pastikan seluruh input terisi!", "warning");
    } else {
        var jsonData;
        var query = "lat=" + location_split[0] + "&lon=" + location_split[1];
        $.get('https://nominatim.openstreetmap.org/reverse?format=jsonv2&'+ query, function(data){//geocoder nominatim
            if(data.address.postcode != ""){
                postal_code = data.address.postcode;
            }
            else{
                postal_code = "none";
            }
            if(data.address.village != ""){
                village = data.address.village;
            }
            else{
                village = "none";
            }
            if(data.address.suburb != ""){
                subdistrict = data.address.suburb;
            }
            else{
                subdistrict = "none";
            }
            formData.append("postal_code", postal_code);
            formData.append("village", village);
            formData.append("subdistrict", subdistrict);

            for (var pair of formData.entries()) {
                entitiesForm.push(pair[1]);
            }
            
            $.ajax({
                type: "POST",
                url: base_url + "/saferoutefinpro/home/add_report",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                success: function(response) {
                    sweetAlert("Congratulation", "Your Report has been Saved", "success");
                    $('#report').modal('hide');
                    document.getElementById('close-modal-report').click();
                },
                complete: function() {
                    // Show LOADING icon
                    $("#loader").hide();
                    document.getElementById("submit_form").reset();
                    setInterval(function() {
                        window.location.reload();
                    }, 2000);
                },
            }); 
        });  
    }
}
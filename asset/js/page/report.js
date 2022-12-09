var base_url = window.location.origin;

function fetchLocationReport(position){
    var location = position.coords.latitude + "," + position.coords.longitude;
    console.log(location);
    document.getElementById("location").value = location;
    document.getElementById("accuracy").value = position.coords.accuracy;
}

fetchCrime();

function fetchCrime(){
    $.ajax({
        url: base_url + "/saferoutefinpro/home/get_crime",
        method:"POST",
        success:function(response){
            // console.log(response);
            $('#crime-type').html(response);
        }
    });
}

function reportWithClick(lat,long,accuracy){
    document.getElementById("location").value = lat + "," + long;
    document.getElementById("accuracy").value = accuracy;
    // console.log(lat,long);
}

function submitReport(){

    // $("#file1").change(function() {
    //     var file = this.files[0];
    //     var fileType = file.type;
    //     var match = ['image/jpeg', 'image/png', 'image/jpg'];
    //     if(!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]) )){
    //         alert('Sorry, only PDF, DOC, JPG, JPEG, & PNG files are allowed to upload.');
    //         $("#file").val('');
    //         return false;
    //     }
    // });

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
        $.get('https://nominatim.openstreetmap.org/reverse?format=jsonv2&'+ query, function(data){
            console.log(data);
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
            console.log(name,location,description,photo, postal_code, village, subdistrict);
            for (var pair of formData.entries()) {
                entitiesForm.push(pair[1]);
                console.log(pair); 
                // console.log(pair[0]+ ', ' + pair[1]); 
            }
            $.ajax({
                type: "POST",
                url: base_url + "/saferoutefinpro/home/add_report",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                beforeSend: function() {
                    // Show LOADING icon
                    $("#loader").show();
                },
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
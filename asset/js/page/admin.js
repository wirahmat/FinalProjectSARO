var base_url = window.location.origin;

load_data_admin();

function load_data_admin(){
    $.ajax({
        url: base_url + "/saferoutefinpro/admin/get_all_data_admin",
        method:"POST",
        success:function(response){
            $('#showAllData').html(response);
        }
    });
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

$(function(){
    devicesTable();
})

function devicesTable(){
   $.ajax({
        url: '../controllers/get_devices.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            // console.log(response)
            $('#devicesTableTbody').empty()
            $.each(response, function(i, field) {
                var row = $('#devicesTableTbodyTemplate').html();
                row = row.replace('Number',i+1);
                row = row.replace('DeviceName',field.DeviceName ?? '')
                row = row.replace('IPAddress',field.IPAddress)
                row = row.replace('Location_val',`${field.barangay_name}, ${field.municipality_name}, ${field.province_name}`)
                
                
                $('#devicesTableTbody').append(row)
            });
            
            // $('#dataTable').DataTable().draw();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.responseText);
            // alert(thrownError);
        }
        
    }); 
}

function checkForDevices() {
    $.ajax({
        url: '../controllers/check_new_users.php', // URL of your server-side script
        method: 'GET',
        success: function(response) {
            if (response === 'true') {
                // There are new records, perform your desired action
                devicesTable()
               
                // console.log('New updates found!');
                // For example, you could update the UI or display a notification
            } else if (response === 'false') {
                // No new records
                // console.log('No new updates');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

setInterval(checkForDevices, 5000); // Adjust the interval as needed

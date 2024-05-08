$(function(){
    devicesTable();
})

function devicesTable(){
   $.ajax({
        url: '../controllers/get_devices.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log(response)
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

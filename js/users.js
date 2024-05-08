$.ajax({
    url: '../controllers/get_users.php',
    type: 'GET',
    dataType: 'json',
    success: function(response) {
        console.log(response)
        $('#usersTableTbody').empty()
        $.each(response, function(i, field) {
            var row = $('#usersTableTbodyTemplate').html();
            row = row.replace('Number',i+1);
            row = row.replace('DeviceName',field.DeviceName ?? '')
            row = row.replace('IPAddress',field.IPAddress)
            row = row.replace('EmailAdd',`${field.UserEmail}`)
            
            
            $('#usersTableTbody').append(row)
            $('#usersTable').DataTable({
                "order": [[ 0, "desc" ]] // Sorts the first column (index 0) in descending order
            }).draw();
        });
    },
    error: function (xhr, ajaxOptions, thrownError) {
        console.log(xhr.responseText);
        alert(thrownError);
    }
    
});
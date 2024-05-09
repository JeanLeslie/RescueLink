function usersTable(){
    $.ajax({
        url: '../controllers/get_users.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            // console.log(response)
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
}

function checkForUsers() {
    $.ajax({
        url: '../controllers/check_new_users.php', // URL of your server-side script
        method: 'GET',
        success: function(response) {
            if (response === 'true') {
                // There are new records, perform your desired action
                usersTable()
               
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
usersTable()
setInterval(checkForUsers, 5000); // Adjust the interval as needed
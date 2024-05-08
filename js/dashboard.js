function dashboardTableAndCards(){
    $.ajax({
        url: '../controllers/get_detections.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            // console.log('detection',response)
            $('#dashboardTbody').empty()
            // var select = $('#location_barangay');
            var fireOG = false;
            var smokeOG = false;
            // select.empty().append('<option selected="">Barangay*</option>');
            $.each(response, function(i, field) {
                console.log(field)
                var row = $('#dashboardTbodyTemplate').html()
                row = row.replace('odd_even',i%2 ? 'even': 'odd')
                if (field.StatusCode == 'OG'){
                    row = row.replace('detect',field.Name == 'Fire'?'danger text-white':'success text-white')

                    if (field.Name == 'Fire' && !fireOG){
                        $('#divOnGoingFire')
                            .removeClass('border-left-danger')
                            .addClass('border-left-dark bg-danger text-white');
                        $('#fire_IPAdd').text(field.IPAddress)
                        $('#fire_Loc').text(`${field.barangay_name}, ${field.municipality_name}, ${field.province_name}`)
                        $('#fire_Time').text(field.FormattedDateTime)
                        fireOG = true;
                    }
                    if (field.Name == 'Smoke' && !smokeOG){
                        $('#divOnGoingSmoke')
                            .removeClass('border-left-success')
                            .addClass('border-left-dark bg-success text-white');
                        $('#smoke_IPAdd').text(field.IPAddress)
                        $('#smoke_Loc').text(`${field.barangay_name}, ${field.municipality_name}, ${field.province_name}`)
                        $('#smoke_Time').text(field.FormattedDateTime)
                        smokeOG = true;
                    }
                }
                row = row.replace('Detection',field.Name)
                row = row.replace('IPAddress',field.IPAddress)
                row = row.replace('Location',`${field.barangay_name}, ${field.municipality_name}, ${field.province_name}`)
                row = row.replace('StartDate',field.FormattedDateTime)
                row = row.replace('DeviceName',field.DeviceName ?? '')  
                row = row.replace('Status',field.Status)
                $('#dashboardTbody').append(row)
            });
    
            $('#divCardDetected').empty()
            $.each(response, function(i, field) {
                if (field.StatusCode == 'OG'){
                
                    var row = $('#card'+field.Name+'Template').html();
                    row = row.replace('DateTime',field.FormattedDateTime);
                    row = row.replace('Detection',field.Name)
                    row = row.replace('IPAddress',field.IPAddress)
                    row = row.replace('Location_val',`${field.barangay_name}, ${field.municipality_name}, ${field.province_name}`)
                    row = row.replace('Level_value','01')
                    
                    var regex = /drive\.google\.com\/file\/d\/([^\/]+)/;
                    // Use match() to find the matched substring
                    var match = (field.ImageLink).match(regex);
                    // Check if there's a match
                    if (match) {
                        // Extract the desired substring after 'drive.google.com/file/d/'
                        var fileId = match[1]; // The captured group ([^\/]+) contains the fileId
                        console.log("File ID:", fileId);
                        row = row.replace('IMG_SOURCE','https://drive.google.com/thumbnail?id='+fileId+'&sz=w1000')
                    } else {
                        console.log("No match found.");
                        row = row.replace('IMG_SOURCE',field.ImageLink)
                    }
                    $('#divCardDetected').append(row)
                }
            });
            
            $('#dashboardTable').DataTable({
                "order": [[ 0, "desc" ]] // Sorts the first column (index 0) in descending order
            }).draw();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.responseText);
            alert(thrownError);
        }
        
    });
}
dashboardTableAndCards();

function playSound() {
    try {
        var audio = new Audio('../digital-alarm-clock.mp3'); // Replace 'notification_sound.mp3' with the path to your sound file
        audio.play();
    } catch (e){
        console.error('An error occurred:', error);
    }
}
function checkForNewRecords() {
    $.ajax({
        url: '../controllers/check_new_records.php', // URL of your server-side script
        method: 'GET',
        success: function(response) {
            console.log(response);
            if (response === 'true') {
                // There are new records, perform your desired action
                dashboardTableAndCards()
                
                playSound(); //try 
               
                console.log('New records found!');
                // For example, you could update the UI or display a notification
            } else if (response === 'false') {
                // No new records
                console.log('No new records');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}
// Call the function initially
checkForNewRecords();

// Set an interval to periodically check for new records (every 5 seconds in this example)
setInterval(checkForNewRecords, 5000); // Adjust the interval as needed
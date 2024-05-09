function detectionsTableAndCards(){
    $.ajax({
        url: '../controllers/get_detections.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {

            $('#detectionCards').empty()
            $.each(response, function(i, field) {
                var row = $('#detection'+field.Name+'Template').html();
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
                    row = row.replace('IMG_SOURCE','https://drive.google.com/thumbnail?id='+fileId+'&sz=w1000')
                } else {
                    // console.log("No match found.");
                    row = row.replace('IMG_SOURCE',field.ImageLink)
                }
                $('#detectionCards').append(row)
            });
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.responseText);
            alert(thrownError);
        }
        
    });
}
function checkForNewDetections() {
    $.ajax({
        url: '../controllers/check_new_records.php', // URL of your server-side script
        method: 'GET',
        success: function(response) {
            if (response === 'true') {
                // There are new records, perform your desired action
                detectionsTableAndCards()
                
                playSound(); //try 
               
                // console.log('New records found!');
                // For example, you could update the UI or display a notification
            } else if (response === 'false') {
                // No new records
                // console.log('No new records');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}
function playSound() {
    try {
        var audio = new Audio('../digital-alarm-clock.mp3'); // Replace 'notification_sound.mp3' with the path to your sound file
        audio.play();
    } catch (e){
        console.error('An error occurred:', error);
    }
}

detectionsTableAndCards()
// Set an interval to periodically check for new records (every 5 seconds in this example)
setInterval(checkForNewDetections, 5000); // Adjust the interval as needed
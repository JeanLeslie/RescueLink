
var GDriveLink = "https://drive.google.com/drive/folders/1irdyJCZKWUfIY2bHdx47mvAlhaYMYFI4?usp=sharing"

function detectionsTableAndCards(){
    $.ajax({
        url: '../controllers/get_detections.php',
        type: 'GET',
        //dataType: 'json',
        success: function(response) {

            $('#detectionCards').empty()
            $.each(response, function(i, field) {
                var row = $('#detectionStatusTemplate').html();
                row = row.replace(/Number/g,field.RecordId);
                row = row.replace('DetectionType',field.Name);
                row = row.replace('DateTime',field.FormattedDateTime);
                row = row.replace('color',field.TypeColor);
                row = row.replace('Detection',field.Name)
                row = row.replace('IPAddress',field.IPAddress)
                $location_address = "";
                if (field.barangay_name != null){
                     $location_address=`${field.barangay_name}, ${field.municipality_name}, ${field.province_name}`
                }
                row = row.replace('Location_val',$location_address)
                row = row.replace('Level_value',field.TypeDescription)
                row = row.replace('GDrive_Link',GDriveLink)
                
                if (field.ImageLink != null){
                    var regex = /drive\.google\.com\/file\/d\/([^\/]+)/;
                    // Use match() to find the matched substring
                    var match = (field.ImageLink).match(regex);
                    // Check if there's a match
                    if (match) {
                        // Extract the desired substring after 'drive.google.com/file/d/'
                        var fileId = match[1]; // The captured group ([^\/]+) contains the fileId
                        // console.log("File ID:", fileId);
                        // row = row.replace('IMG_SOURCE','https://drive.google.com/thumbnail?id='+fileId+'&sz=w1000')
                        row = row.replace('IMG_SOURCE','https://lh3.googleusercontent.com/d/'+fileId+'=w1000')
                    } else {
                        console.log("No match found.");
                        row = row.replace('IMG_SOURCE',field.ImageLink)
                    }
                    $('#detectionCards').append(row)

                }else{
                    row = row.replace('IMG_SOURCE','..\\img\\'+(field.Name).toLowerCase()+'.jpg');


                    $('#detectionCards').append(row)
                    // $('#cardStatusImg'+ field.RecordId).attr('src','')
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

var audio; // Declare audio outside the function to persist between calls
var stopTimeout; // Variable to store the timeout ID

function playSound() {
    try {
        if (!audio) {
            audio = new Audio('../digital-alarm-clock.mp3'); // Initialize the audio only once
            // Attach event listener to replay when audio ends
            audio.addEventListener('ended', function() {
                if (isPlaying) {
                    audio.currentTime = 0; // Reset playback position
                    audio.play(); // Replay the audio
                }
            });
        }

        // Check if the sound is already playing
        if (!audio.paused) {
            // Clear the previous timeout if the sound is still playing
            clearTimeout(stopTimeout);
        }

        // Play the sound
        audio.play();
        isPlaying = true;

        // Restart the timeout for stopping the sound after 1 minute (60000 milliseconds)
        stopTimeout = setTimeout(function() {
            isPlaying = false; // Stop replaying
            audio.pause();
            audio.currentTime = 0; // Reset the playback position
        }, 60000);

    } catch (e) {
        console.error('An error occurred:', e);
    }
}

detectionsTableAndCards()
// Set an interval to periodically check for new records (every 5 seconds in this example)
setInterval(checkForNewDetections, 5000); // Adjust the interval as needed
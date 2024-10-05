dashboardTableAndCards();

// Call the function initially
checkForNewRecords();

// Set an interval to periodically check for new records (every 5 seconds in this example)
setInterval(checkForNewRecords, 5000); // Adjust the interval as needed
// setInterval(checkForUpdates, 5000); // Adjust the interval as needed

var firstDataTableRun = true;
function dashboardTableAndCards(){
    console.log('updating...')
    $.ajax({
        url: '../controllers/get_detections.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('detection',response)
            $('#dashboardTbody').empty()
            // var select = $('#location_barangay');
            var fireOG = false;
            var smokeOG = false;
            // select.empty().append('<option selected="">Barangay*</option>');
            $.each(response, function(i, field) {
                // console.log(field)
                var row = $('#dashboardTbodyTemplate').html()
                row = row.replace('odd_even',i%2 ? 'even': 'odd')
                if (field.StatusCode == 'OG'){
                    row = row.replace('detect',field.TypeColor)
                    $location_address = ""
                    if (field.barangay_name != null){
                         $location_address = `${field.barangay_name}, ${field.municipality_name}, ${field.province_name}`
                    }

                    if (field.Name == 'Fire' && !fireOG){
                        $('#divOnGoingFire')
                            .removeClass('border-left-danger')
                            .addClass('border-left-dark bg-danger text-white');
                        $('#fire_IPAdd').text(field.IPAddress)
                        $('#fire_Loc').text($location_address)
                        $('#fire_Time').text(field.FormattedDateTime)
                        fireOG = true;
                    }
                    if (field.Name == 'Smoke' && !smokeOG){
                        $('#divOnGoingSmoke')
                            .removeClass('border-left-success')
                            .addClass('border-left-dark bg-success text-white');
                        $('#smoke_IPAdd').text(field.IPAddress)
                        $('#smoke_Loc').text($location_address)
                        $('#smoke_Time').text(field.FormattedDateTime)
                        smokeOG = true;
                    }
                }
                row = row.replace('Detection',field.Name ?? '')
                row = row.replace('IPAddress',field.IPAddress?? '')
                $location_address = ""
                if (field.barangay_name != null){
                    $location_address = `${field.barangay_name}, ${field.municipality_name}, ${field.province_name}`
                }
                row = row.replace('Location',$location_address)
                row = row.replace('StartDate',field.FormattedDateTime?? '')
                row = row.replace('DeviceName',field.DeviceName ?? '')  
                row = row.replace('Status',field.Status?? '')
                $('#dashboardTbody').append(row)
            });
    
            $('#divCardDetected').empty()
            let hasOG = response.some(function(field) {
                return field.StatusCode && field.StatusCode.includes('OG');
            });
            
            if (hasOG){
                $('#divLeftSide').show()
            } else {
                $('#divLeftSide').hide()
            }


            $.each(response, function(i, field) {
                if (field.StatusCode == 'OG' && field.Name != "None"){
                     
                    var row = $('#cardStatusTemplate').html();
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
                        $('#divCardDetected').append(row)

                    }else{
                        row = row.replace('IMG_SOURCE','');

                        $('#divCardDetected').append(row)
                        $('#cardStatusImg'+ field.RecordId).remove()
                    }

                    // var row = $('#card'+field.Name+'Template').html();
                    // row = row.replace('DateTime',field.FormattedDateTime);
                    // row = row.replace('Detection',field.Name)
                    // row = row.replace('IPAddress',field.IPAddress)
                    // row = row.replace('Location_val',`${field.barangay_name}, ${field.municipality_name}, ${field.province_name}`)
                    // row = row.replace('Level_value',field.TypeDescription)

                    // if (field.ImageLink != null){
                    //     var regex = /drive\.google\.com\/file\/d\/([^\/]+)/;
                    //     // Use match() to find the matched substring
                    //     var match = (field.ImageLink).match(regex);
                    //     // Check if there's a match
                    //     if (match) {
                    //         // Extract the desired substring after 'drive.google.com/file/d/'
                    //         var fileId = match[1]; // The captured group ([^\/]+) contains the fileId
                    //         // console.log("File ID:", fileId);
                    //         // row = row.replace('IMG_SOURCE','https://drive.google.com/thumbnail?id='+fileId+'&sz=w1000')
                    //         row = row.replace('IMG_SOURCE','https://lh3.googleusercontent.com/d/'+fileId+'=w1000')
                    //     } else {
                    //         console.log("No match found.");
                    //         row = row.replace('IMG_SOURCE',field.ImageLink)
                    //     }
                    // }else{
                    //     row = row.replace('IMG_SOURCE','')

                    // }
                }
            });

            if (!$.fn.DataTable.isDataTable('#dataTable')) {
                $('#dataTable').DataTable({
                    "order": [[ 0, "desc" ]] // Sorts the first column (index 0) in descending order
                }).draw();
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.responseText);
            alert(thrownError);
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
function checkForNewRecords() {

    $.ajax({
        url: '../controllers/check_new_records.php', // URL of your server-side script
        method: 'GET',
        success: function(response) {
            if (response === 'true') {
                // There are new records, perform your desired action
                dashboardTableAndCards()
                
                playSound(); //try 
                console.log('checking For New Records...   true')

                // console.log('New records found!');
                // For example, you could update the UI or display a notification
            } else if (response === 'false') {
                // No new records
                console.log('checking For New Records...   false')
                // console.log('No new records');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}
// function checkForUpdates() {
//     $.ajax({
//         url: '../controllers/check_updates_records.php', // URL of your server-side script
//         method: 'GET',
//         success: function(response) {
//             if (response === 'true') {
//                 // There are new records, perform your desired action
//                 dashboardTableAndCards()
               
//                 // console.log('New updates found!');
//                 // For example, you could update the UI or display a notification
//             } else if (response === 'false') {
//                 // No new records
//                 // console.log('No new updates');
//             }
//         },
//         error: function(xhr, status, error) {
//             console.error('Error:', error);
//         }
//     });
// }
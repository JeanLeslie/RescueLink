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
                console.log("File ID:", fileId);
                row = row.replace('IMG_SOURCE','https://drive.google.com/thumbnail?id='+fileId+'&sz=w1000')
            } else {
                console.log("No match found.");
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
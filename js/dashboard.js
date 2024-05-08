$.ajax({
    url: '../controllers/get_detections.php',
    type: 'GET',
    dataType: 'json',
    success: function(response) {
        console.log('detection',response)
        $('#dashboardTbody').empty()
        // var select = $('#location_barangay');
    
        // select.empty().append('<option selected="">Barangay*</option>');
        $.each(response, function(i, field) {
            var row = $('#dashboardTbodyTemplate').html()
            row = row.replace('odd_even',i%2 ? 'even': 'odd')
            if (field.StatusCode == 'OG'){
                row = row.replace('detect',field.Name == 'Fire'?'danger text-white':'success text-white')
            }
            row = row.replace('Detection',field.Name)
            row = row.replace('IPAddress',field.IPAddress)
            row = row.replace('Location',`${field.barangay_name}, ${field.municipality_name}, ${field.province_name}`)
            row = row.replace('StartDate',field.DateTime)
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
    },
    error: function (xhr, ajaxOptions, thrownError) {
        console.log(xhr.responseText);
        alert(thrownError);
    }
    
});
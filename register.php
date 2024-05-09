<?php
    include('config/db_connect.php');
    //write query for region
    $sql = 'SELECT * FROM table_region ORDER BY region_name';
    $result = mysqli_query($conn,$sql);
    //fetch as an array
    $regions = mysqli_fetch_all($result,MYSQLI_ASSOC);

    mysqli_free_result($result);//free from memory
    
    $email = $ip_add = $region = $province = $city = $barangay = $password = $repeat_pass = $streetAddress= '';
    $errors = array ('email'=>'','ip_add'=>'','region'=>'','province'=>'','city'=>'','barangay'=>'','password'=>'','repeat_pass'=>'','confirm_pass'=>'');
    // echo '<script> alert("' .(($_POST['Password'])).'") </script>';
    // echo '<script> alert("RepeatPassword","' .(($_POST['RepeatPassword'])).'") </script>';
    
    if (isset($_POST['submit'])) {
        if(empty($_POST['Email'])){
			$errors['email'] = 'An email is required';
		} else{
			$email = $_POST['Email'];
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
				$errors['email'] = 'Email must be a valid email address';
			}
		}

        if(empty($_POST['IPAddress'])){
			$errors['ip_add'] = 'An IP Adress is required';
		} else{
			$ip_add = $_POST['IPAddress'];
			if(!filter_var($ip_add, FILTER_VALIDATE_IP)){
				$errors['ip_add'] = 'IP Address must be a valid IP address';
			}
		}

        if(empty($_POST['Region'])){
			$errors['region'] = 'A Region is required';
		}

        if(empty($_POST['Province'])){
			$errors['province'] = 'A Province is required';
		}
        if(empty($_POST['City'])){
			$errors['city'] = 'A City is required';
		}
        if(empty($_POST['Barangay'])){
			$errors['barangay'] = 'A Barangay is required';
		}
        // Password validation
        if (empty($_POST['Password'])) {
            $errors['password'] = 'A Password is required';
        }

        if (empty($_POST['RepeatPassword'])) {
            $errors['repeat_pass'] = 'A Repeat Password is required';
        }

        if (!empty($_POST['Password']) && !empty($_POST['RepeatPassword'])) {
            if ($_POST['Password'] !== $_POST['RepeatPassword']) {
                $errors['confirm_pass'] = 'Password does not match';
            }
        }

        if(array_filter($errors)){
			//echo 'errors in form';
		} else {
			// escape sql chars
			$email = mysqli_real_escape_string($conn, $_POST['Email']);
            $ip_add = mysqli_real_escape_string($conn, $_POST['IPAddress']);;
            $barangay = mysqli_real_escape_string($conn, $_POST['Barangay']);
            $password = mysqli_real_escape_string($conn, $_POST['Password']);
            $streetAddress = mysqli_real_escape_string($conn, $_POST['StreetAddress']);
			// create sql
            $sql_insert_device = "INSERT INTO devicestable
                                (IPAddress, BarangayId, UserEmail, Password, StreetAddress,CreatedDateTime,DeviceName)
                                Values
                                ('$ip_add',$barangay,'$email','$password','$streetAddress', NOW(),NULL)";

			// save to db and check
			if(mysqli_query($conn, $sql_insert_device)){
				// success
				header('Location: login.php');
			} else {
				echo 'query error: '. mysqli_error($conn);
			}

			
		}
    }
    
    mysqli_close($conn);
    function getUserIpAddr(){
        // if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //     //ip from share internet
        //     $ip = $_SERVER['HTTP_CLIENT_IP'];
        // }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //     //ip pass from proxy
        //     $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        // }else{
        //     $ip = $_SERVER['REMOTE_ADDR'];
        // }
        // return $ip;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.ipify.org');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $public_ip = curl_exec($ch);
        curl_close($ch);
        return $public_ip;

    }

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Rescue Link - Register</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/mycss.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form class="user" action="register.php" method="POST">
                                <div class="form-group">
                                        <input type="email" class="form-control form-control-user" name="Email" id="exampleEmail"
                                            placeholder="*Email" required>
                                            <span class="badge border-danger border-1 text-danger"><?php echo $errors['email']?></span>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="exampleLastName"
                                        placeholder="<?php Echo "*IP Address: ".getUserIpAddr() ?>" name="IPAddress" list="your_ip" required>
                                        <datalist id="your_ip">
                                            <option value="<?php Echo getUserIpAddr() ?>"></option>
                                            <option value="abc">abc</option>
                                        </datalist>
                                        <span class="badge border-danger border-1 text-danger"><?php echo $errors['ip_add']?></span>
                                </div>
                                <div class="form-group row  mb-0 mb-sm-3">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <select class="form-control form-select" aria-label="Default select example" id="location_region" name="Region" required>
                                            <option selected="">*Region</option>
                                            
                                            <?php foreach ($regions as $region){ ?>
                                                <option value=<?php echo htmlspecialchars($region['region_id']) ?>>
                                                <?php echo htmlspecialchars($region['region_name']).' - '.htmlspecialchars($region['region_description'])  ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <span class="badge border-danger border-1 text-danger"><?php echo $errors['region']?></span>
                                    </div>
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <select class="form-control form-select" aria-label="Default select example" disabled id="location_province" name="Province" required>
                                            <option selected="">*Province</option>
                                            <option value="1">One</option>
                                            <option value="2">Two</option>
                                            <option value="3">Three</option>
                                        </select>
                                        <span class="badge border-danger border-1 text-danger"><?php echo $errors['province']?></span>
                                    </div>
                                </div>
                                <div class="form-group row mb-0 mb-sm-3">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <select class="form-control form-select" aria-label="Default select example" disabled id="location_city" name="City" required>
                                            <option selected="">*City</option>
                                            <option value="1">One</option>
                                            <option value="2">Two</option>
                                            <option value="3">Three</option>
                                        </select>
                                        <span class="badge border-danger border-1 text-danger"><?php echo $errors['city']?></span>
                                    </div>
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <select class="form-control form-select" aria-label="Default select example" disabled id="location_barangay" name="Barangay" required>
                                            <option selected="">*Barangay</option>
                                            <option value="1">One</option>
                                            <option value="2">Two</option>
                                            <option value="3">Three</option>
                                        </select>
                                        <span class="badge border-danger border-1 text-danger"><?php echo $errors['barangay']?></span>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <input type="text" class="form-control form-control-user" id="exampleInputAddress"
                                        placeholder="Street Address" disabled name="StreetAddress">
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user"
                                            id="exampleInputPassword" placeholder="*Password" name="Password" required>
                                        <span class="badge border-danger border-1 text-danger"><?php echo $errors['password']?></span>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user"
                                            id="exampleRepeatPassword" placeholder="*Repeat Password" name="RepeatPassword" required>
                                        <span class="badge border-danger border-1 text-danger"><?php echo $errors['repeat_pass']?></span>
                                    </div>
                                </div>
                                <span class="badge border-danger border-1 text-danger"><?php echo $errors['confirm_pass']?></span>
                                <button class="btn btn-primary btn-user btn-block" type="submit" name="submit" value="Submit">
                                    Register Account
                                            </button>
                                <!-- <hr>
                                <a href="index.html" class="btn btn-google btn-user btn-block">
                                    <i class="fab fa-google fa-fw"></i> Register with Google
                                </a>
                                <a href="index.html" class="btn btn-facebook btn-user btn-block">
                                    <i class="fab fa-facebook-f fa-fw"></i> Register with Facebook
                                </a> -->
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="forgot-password.php">Forgot Password?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="login.php">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    

    <!-- MY SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#location_region').change(function() {
            var regionId = $(this).val();
            if (regionId != 'Region*'){
                $.ajax({
                    url: 'controllers/get_provinces.php',
                    type: 'GET',
                    data: { region_id: regionId },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response)
                        var select = $('#location_province');
                    
                        select.empty().append('<option selected="">Province*</option>');
                        $.each(response, function(key, value) {
                            select.append('<option value="' + value.province_id + '">' + value.province_name + '</option>');
                        });
                        select.prop('disabled', false);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(xhr.responseText);
                        alert(thrownError);
                    }
                    
                });
            } else {
                $('#location_province').prop('disabled', true);
                $('#location_city').prop('disabled', true);
                $('#location_barangay').prop('disabled', true);
                $('#exampleInputAddress').prop('disabled', true);
            }
        });
        $('#location_province').change(function() {
            var provinceId = $(this).val();
            if (provinceId != 'Province*'){
                $.ajax({
                    url: 'controllers/get_cities.php',
                    type: 'GET',
                    data: { province_id: provinceId },
                    dataType: 'json',
                    success: function(response) {
                        // console.log('cities',response)
                        var select = $('#location_city');
                    
                        select.empty().append('<option selected="">City*</option>');
                        $.each(response, function(key, value) {
                            select.append('<option value="' + value.municipality_id + '">' + value.municipality_name + '</option>');
                        });
                        select.prop('disabled', false);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(xhr.responseText);
                        alert(thrownError);
                    }
                    
                });
            } else {
                $('#location_city').prop('disabled', true);
                $('#location_barangay').prop('disabled', true);
                $('#exampleInputAddress').prop('disabled', true);
            }
        });
        $('#location_city').change(function() {
            var cityId = $(this).val();
            if (cityId != 'City*'){
                $.ajax({
                    url: 'controllers/get_barangays.php',
                    type: 'GET',
                    data: { municipality_id: cityId },
                    dataType: 'json',
                    success: function(response) {
                        // console.log('barangay',response)
                        var select = $('#location_barangay');
                    
                        select.empty().append('<option selected="">Barangay*</option>');
                        $.each(response, function(key, value) {
                            select.append('<option value="' + value.barangay_id + '">' + value.barangay_name + '</option>');
                        });
                        select.prop('disabled', false);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(xhr.responseText);
                        alert(thrownError);
                    }
                    
                });
            } else {
                $('#location_barangay').prop('disabled', true);
                $('#exampleInputAddress').prop('disabled', true);
            }
        });
        $('#location_barangay').change(function() {
            var barangayId = $(this).val();
            if (barangayId != 'Barangay*'){
                $('#exampleInputAddress').prop('disabled', false);
            }else {
                $('#exampleInputAddress').prop('disabled', true);
            }
        });
    });
    </script>

</body>

</html>
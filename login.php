<?php
    include('config/db_connect.php');
    session_start();
    $email = $password = '';
    $errors = array ('email'=>'','password'=>'','verify'=>'');
    // print_r ($_POST['Email']);
    // print_r ($_POST['Password']);
    if($_SERVER['QUERY_STRING'] == 'incorrectlogin'){
        $errors['verify'] = 'Incorrect Email/IP Address or Password.';
    }

    if($_SERVER['QUERY_STRING'] == 'norecords'){
        $errors['verify'] = 'Not on system';
    }
    if (isset($_POST['submit'])) {
        if(empty($_POST['Email'])){
			$errors['email'] = 'An email is required';
		} else{
			$email = $_POST['Email'];
			if((!filter_var($email, FILTER_VALIDATE_EMAIL))&&(!filter_var($email, FILTER_VALIDATE_IP))){
				$errors['email'] = 'Must be a valid email address / IP Address';
			}
		}
    }
    if(empty($_POST['Password'])){
        $errors['password'] = 'A Password is required';
    }

    if(array_filter($errors)){
        //echo 'errors in form';
    } else {
        // escape sql chars
        $email = mysqli_real_escape_string($conn, $_POST['Email']);
        $password = mysqli_real_escape_string($conn, $_POST['Password']);

        $sql = "SELECT * FROM devicestable WHERE (UserEmail='$email' OR IPAddress = '$email')AND Password='$password'";
        $sql_admin = "SELECT * FROM admins WHERE (Username='$email') AND Password='$password';";
        // echo '<script> alert("'.$sql_admin.'")</script> ';
        
        $result = mysqli_query($conn, $sql);
        $result_admin = mysqli_query($conn, $sql_admin);
        // echo '<script> alert('.mysqli_num_rows($result_admin).')</script> ';
        if (mysqli_num_rows($result_admin) === 1) {
            
            $row_admin = mysqli_fetch_assoc($result_admin);

            
            echo '<script> alert("hey: '.print_r($row_admin).'")</script> ';

            if (($row_admin['Username'] === $email) && $row_admin['Password'] === $password) {
                $_SESSION['UserEmail'] = $row_admin['Username'];
                $_SESSION['IsAdmin'] = true;
                header("Location: views/dashboard.php");

                exit();

            }
        }
        elseif (mysqli_num_rows($result) === 1) {

            $row = mysqli_fetch_assoc($result);
            print_r($row);

            if ((($row['UserEmail'] === $email) || ($row['IPAddress'] === $email) ) && $row['Password'] === $password) {

                $_SESSION['UserEmail'] = $row['UserEmail'];

                $_SESSION['IPAddress'] = $row['IPAddress'];

                $_SESSION['DevicesId'] = $row['DevicesId'];

                $_SESSION['IsAdmin'] = false;

                header("Location: views/dashboard.php");

                exit();

            }else{

                header("Location: login.php?incorrectlogin");

                exit();

            }

        }else{

            header("Location: login.php?norecords");

            exit();

        }

        // // create sql
        // $sql_insert_device = "INSERT INTO devicestable
        //                     (IPAddress, BarangayId, UserEmail, Password, StreetAddress,CreatedDateTime,DeviceName)
        //                     Values
        //                     ('$ip_add',$barangay,'$email','$password','$streetAddress', NOW(),NULL)";

        // // save to db and check
        // if(mysqli_query($conn, $sql_insert_device)){
        //     // success
        //     header('Location: login.php');
        // } else {
        //     echo 'query error: '. mysqli_error($conn);
        // }

        
    }
    mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
 
    <title>Rescue Link - Login</title>

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

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    <form class="user" action="login.php" method="POST">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                                id="exampleInputEmail" name="Email" aria-describedby="emailHelp"
                                                placeholder="Enter Email Address..." required>
                                            <span class="badge border-danger border-1 text-danger"><?php echo $errors['email']?></span>
                                        </div>

                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="Password" name="Password" required>
                                        <span class="badge border-danger border-1 text-danger"><?php echo $errors['verify']?></span></br>
                                        <span class="badge border-danger border-1 text-danger"><?php echo $errors['password']?></span>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Remember
                                                    Me</label>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary btn-user btn-block" type="submit" name="submit" value="Submit">
                                            Login
                                        </button>
                                        <!-- <hr>
                                        <a href="index.html" class="btn btn-google btn-user btn-block">
                                            <i class="fab fa-google fa-fw"></i> Login with Google
                                        </a>
                                        <a href="index.html" class="btn btn-facebook btn-user btn-block">
                                            <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                                        </a> -->
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.php">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Create an Account!</a>
                                    </div>
                                </div>
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

</body>

</html>
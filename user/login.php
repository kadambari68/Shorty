<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<link rel="stylesheet" href="../assets/css/sweetAlertButton.css">
<link rel="stylesheet" href="../assets/css/login.css">
<link rel="stylesheet" href="./assets/css/btn-new.css">

<!-- jQuery CDN -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


<?php

session_start();
error_reporting(0);
?>
<?php
if (isset($_POST['submit'])) {
    require('../admin/dBconn/database.php');
    $database = new Database();
    $db = $database->connect();

    $email = mysqli_real_escape_string($db,$_POST['email']);
    $password = mysqli_real_escape_string($db,$_POST['password']);
    
    $originalPass = $password;
    $password = md5($password);

    $query = "SELECT * from users where email='$email' and password ='$password'";
    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) == 1) {

        ob_start();
        if (isset($_POST['rememberme'])) {
            setcookie('emailcookie', $email, time() + 86400);
            setcookie('passwordcookie', $originalPass, time() + 86400);
        }

        $row = mysqli_fetch_array($result);
        $_SESSION["uno"] = "" . $row['uniqueNo'] . "";


        $_SESSION['start'] = time();
        $_SESSION['expire'] = $_SESSION['start'] + (60 * 60);
        echo "<script>window.location.replace('./')</script>";
    } else {

        $query = "SELECT * from users where email='$email'";
        $result = mysqli_query($db, $query);

        if (mysqli_num_rows($result) == 1) {
                //Alert Message 
                $msz = "Wrong Password !!";
                $type = "error";
                $redirection = "./login";
                include "./swal.php";
        } else {

                //Alert Message 
                $msz = "Please Register First !!";
                $type = "error";
                $redirection = "./register";
                include "./swal.php";
        }

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Login</title>
    <meta content="Admin Dashboard" name="description" />
    <meta content="themesdesign" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />


    <link href="../assets/img/logo.webp" rel="icon" />

    <!-- Bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <link href="../assets/img/logo.png" rel="icon" />


    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/plugins/animate/animate.css" rel="stylesheet" type="text/css">
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="assets/css/style.css" rel="stylesheet" type="text/css">

</head>


<!-- <body class="fixed-left" style="background-image:url(assets/images/background.jpg)"> -->

<body class="fixed-left" style="  ">

    <!-- Begin page -->
    <!--<div class="accountbg"></div>-->
    <div id="stars"></div>
    <div id="stars2"></div>
    <div class="wrapper-page">

        <div class="card">
            <div class="card-body">

                <h3 class="text-center mt-0">
                    <a href="./login" class="logo logo-admin"><img class="vert-move"
                            src="../assets/img/loginpage--logo.webp" height="100" alt="logo"></a>
                </h3>

                <h6 class="text-center">Login / Sign In</h6>

                <div class="p-3">

                    <form class="form-horizontal" method="POST" id="login" aria-label="Login Form">

                        <div class="form-group row">
                            <div class="col-12">
                                <div class="inputIconContainer">
                                    <i class="bi bi-envelope-fill"></i>
                                    <input class="form-control  " type="email" required="" name="email"
                                        aria-required="true" aria-label="Email" data-parsley-type="email" id="email"
                                        placeholder="Email" value="<?php if (isset($_COOKIE['emailcookie'])) {
                                            echo $_COOKIE['emailcookie'];
                                        } ?>" onkeyup="validate(event)">
                                </div>
                                <p class="error hidden" id="emailError">Please enter Valid email</p>
                            </div>
                        </div>



                        <div class="form-group row">
                            <div class="col-12">
                                <div class="input-group" id="passwordBorder">
                                    <div class="inputIconContainer" id="password-iconDiv">
                                        <i class="bi bi-key-fill"></i>
                                        <input type="password" class="form-control " value="<?php if (isset($_COOKIE['passwordcookie'])) {
                                            echo $_COOKIE['passwordcookie'];
                                        } ?>" id="password" required name="password" placeholder="Password"
                                            aria-required="true" aria-label="Password" onkeyup="validate(event)">
                                    </div>
                                    <div class="input-group-append eye">
                                        <span class="input-group-text" onclick="changeType()">
                                            <!-- <img id="eyei" src="https://gvaprofile.com/app/show_hide_eye.png" onclick="changeType()"  style="height:20px; width: 20px;"/></span> -->
                                            <i id="eyei" onclick="" class="fa fa-eye-slash ml-np15 mt-p4 zIndexTop"
                                                aria-hidden="true"></i>
                                    </div>
                                </div>
                                <p class="error hidden" id="passwordError">Please fill this field</p>
                            </div>

                        </div>

                        <div class="form-group row">
                            <div class="col-12">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="rememberme"
                                        id="customCheck1" aria-checked="false">
                                    <label class="custom-control-label" for="customCheck1">Remember me</label>
                                    <i class="ti-info-alt tooltips" data-placement="top" data-toggle="tooltip"
                                        data-original-title="Save your password"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-center row m-t-20">
                            <div class="col-12">
                                <button class="btn  btn-block waves-effect waves-light btn-new btn-Login" id="submit"
                                    name="submit" type="submit" aria-label="Log In">Log In</button>
                            </div>
                        </div>

                        <div class="form-group m-t-10 mb-0 row">
                            <div class="col-sm-7 m-t-20">
                                <a href="../" class="text-muted"><i class="mdi mdi-home" aria-hidden="true"></i> &nbsp;
                                    Home
                                    Page</a>
                            </div>
                            <div class="col-sm-5 m-t-20">
                                <a href="./register" class="text-muted" aria-label="Create an account?"><i
                                        class="mdi mdi-account-circle" aria-hidden="true"></i> &nbsp;
                                    Create an account</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        let loop = 0;
        let password = document.querySelector('#password');
        let eyei = document.querySelector('#eyei');
        function changeType() {
            if (loop % 2 == 0) {
                eyei.classList.remove("fa-eye-slash");
                eyei.classList.add("fa-eye");
                password.type = "text";
            } else {
                eyei.classList.remove("fa-eye");
                eyei.classList.add("fa-eye-slash");
                password.type = "password";
            }
            loop++;
        }

        // Function for inline validation of fields
        function validate(e) {
            const emailRegex = /\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}\b/;
            const error = document.getElementById(`${e.target.name}Error`);
            if (e.target.name === "email") {
                const valid = emailRegex.test(e.target.value);
                (valid) ? error.classList.add("hidden") : error.classList.remove("hidden");
                (valid) ? e.target.style.border = "2px solid #04cb04" : e.target.style.border = "2px solid red";
            } else {
                const div = document.getElementById("passwordBorder").style;
                (!e.target.value) ? error.classList.remove("hidden") : error.classList.add("hidden");
                (!e.target.value) ? div.border = "2px solid red" : div.border = "none";
            }
        }


    </script>

    <!-- jQuery  -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/modernizr.min.js"></script>
    <script src="assets/js/detect.js"></script>
    <script src="assets/js/fastclick.js"></script>
    <script src="assets/js/jquery.slimscroll.js"></script>
    <script src="assets/js/jquery.blockUI.js"></script>
    <script src="assets/js/waves.js"></script>
    <script src="assets/js/jquery.nicescroll.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>


    <!-- Parsley js -->
    <!-- <script src="assets/plugins/parsleyjs/parsley.min.js"></script>
        <script src="assets/pages/form-validation.init.js"></script> -->

</body>

</html>
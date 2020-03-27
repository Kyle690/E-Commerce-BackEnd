<?php
/**
 * User: kyle
 * Date: 2018/04/25
 * Time: 5:42 PM
 */
flush();
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: -1"); // Proxies.


?>
<!DOCTYPE html>
<html>
<head>


    <meta http-equiv="pragma" content="no-cache">

    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>Vanita Pasta | Admin Login</title>

    <!-- Site favicon -->
    <!-- <link rel="shortcut icon" href="images/favicon.ico"> -->

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="vendors/styles/style.css">
</head>
<body>
    <div class="login-wrap customscroll d-flex align-items-center flex-wrap justify-content-center pd-20">
    <div class="login-box bg-white box-shadow pd-30 border-radius-5">
        <img src="img/Vanita_Logo.png" alt="login" class="login-img">
        <h4 class="text-center mb-30">Admin Panel</h4>
        <div id="login_result">

        </div>
        <form name="admin_login_form" action="admin_login_process.php" method="POST">
            <div class="input-group custom input-group-lg">
                <input type="email" class="form-control" placeholder="Email" name="email" id="email" maxlength="100" required = "required">
                <div class="input-group-append custom">
                    <span class="input-group-text"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                </div>
                <div class="invalid-feedback d-none text-center" id="email_validation">
                    Please check your email.
                </div>
            </div>
            <div class="input-group custom input-group-lg">
                <input type="password" class="form-control" placeholder="**********" name="password" maxlength="100" id="password" required  = "required">
                <div class="input-group-append custom">
                    <span class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></span>
                </div>
                <div class="invalid-feedback d-none text-center" id="password_validation">
                    Please check your password.
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="input-group">
                            <input class="btn btn-outline-primary btn-lg btn-block" type="submit" name="admin_login_details" id="admin_login_submit" value="Sign In">

                        <!-- <a class="btn btn-outline-primary btn-md btn-block" href="admin_dashboard/admin_home.php">Sign In</a>-->
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="forgot-password padding-top-10"><a href="forgot_password.php">Forgot Password</a></div>
                </div>
            </div>
        </form>
    </div>
</div>
    <script src="http://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $("document").ready(function () {
            $("#admin_login_submit").click(function(e){
               e.preventDefault();
               var email = $("#email").val();
               var password = $("#password").val();
               var reg =  /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
               var password_pattern = /['!@#$%*\]\[()=_+{}:\";?,.\/A-Za-z0-9\s-]/;
               var emailValid = true;
               var passwordValid = true;



               if( reg.test(admin_login_form.email.value) == false ){
                   $("#email").addClass("is-invalid");
                   $("#email_validation").removeClass("d-none");
                    emailValid = false;
               }else if (emailValid == true){
                   $("#email").removeClass('is-invalid');
                   $("#email_validation").addClass('d-none');
                   emailValid = true;
               }
               if (password_pattern.test(admin_login_form.password.value) == false) {
                    $("#password").addClass('is-invalid');
                    $("#password_validation").removeClass("d-none");
                    passwordValid = false

                } else if (passwordValid == true){
                   $("#password").removeClass('is-invalid');
                   $("#password_validation").addClass('d-none');

                    if(email != '' && password != ''){
                        $.ajax({
                            url:"admin_login_process.php",
                            method: "POST",
                            data:{ email: email, password: password },
                            success: function(data){
                                    var login = data;
                                if(login == "true"){

                                    window.location.replace("admin_dashboard/admin_home.php");

                                }else{
                                    $("#login_result").text(data);
                                }
                                //$("#login_result").text("Username and password don't match, please try again.");
                                //window.open(admin_dashboard/admin_home.php);
                            }
                        })
                    }
               }


            })
        })
    </script>
</body>
</html>
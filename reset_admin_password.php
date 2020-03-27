<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/23
 * Time: 1:32 PM
 */
include_once "inc/database.php";
$db = new Database();




if(isset($_GET['email']) && isset($_GET['token'])){
    $email = mysqli_real_escape_string($con, $_GET['email']);
    $token = mysqli_real_escape_string($con, $_GET['token']);

    $sql_check = "SELECT id FROM admin_user WHERE email='$email' AND token='$token' AND token<>'' AND token_expire_date > NOW()";
    $result = $db->select($sql_check);
    if(sizeof($result) != 1){

        header("location: index.php");
    }
}
else {
    header("location: index.php");
} ?>
<!DOCTYPE html>
<html>
<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>Vanita Pasta | Admin Reset Password</title>

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
        <h4 class="text-center mb-30">Admin | New Password</h4>
        <p class="text-center">Please enter your new password</p>
        <div id="login_result">

        </div>
        <form name="admin_reset_password" action="#" method="POST">
            <div class="input-group custom input-group-lg">
                <input type="hidden" value="<?php echo $email ?>" id="email">
                <input type="password" class="form-control" placeholder="Password" name="password" id="new_password" maxlength="100" required = "required">
                <div class="input-group-append custom">
                    <span class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></span>
                </div>
                <div class="invalid-feedback d-none text-center" id="password_validation">
                    Please check your password.
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="input-group">
                            <input class="btn btn-outline-primary btn-lg btn-block" type="button"  id="admin_reset_password" value="Reset Password">


                    </div>
                    <p class="text-center" id="response"></p>
                </div>

            </div>
        </form>
    </div>
</div>
<script src="http://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

    <script type="text/javascript">
$('document').ready(function () {
   $("#admin_reset_password").click(function(e) {
       e.preventDefault();
       var password = $("#new_password").val();
       var email = $("#email").val();
       var password_pattern = /['!@#$%*\]\[()=_+{}:\";?,.\/A-Za-z0-9\s-]/;
       var passwordValid = true;
       if(password != '') {
           $("#password").addClass('is-invalid');
           $("#password_validation").removeClass("d-none");
       }
           if (password_pattern.test(password) == false) {
               $("#password").addClass('is-invalid');
               $("#password_validation").removeClass("d-none");
               passwordValid = false

           } else if (passwordValid == true) {
               $("#password").removeClass('is-invalid');
               $("#password_validation").addClass('d-none');


               $.ajax({
                   url: "rest_password_process.php",
                   method: "POST",
                   //dataType: "json",
                   data: {
                       password: password, email: email
                   }, success: function (data) {
                       alert(data);
                        window.location.href = "index.php";
                      /* if (!response.success)
                           $("#response").html(response.msg).css('color', "red");
                       else
                           $("#response").html(response.msg).css('color', "green");*/
                   }


               });
           }

   });
});
</script>
</body>
</html>





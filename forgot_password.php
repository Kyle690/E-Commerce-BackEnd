<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/22
 * Time: 12:12 PM
 */
include_once "inc/database.php";
$db = new Database();
function generateNewString($len = 10) {
    $token = "poiuztrewqasdfghjklmnbvcxy1234567890";
    $token = str_shuffle($token);
    $token = substr($token, 0, $len);

    return $token;
}

function redirectToLoginPage() {
    header('Location: login.php');
    exit();
}


if(isset($_POST['email'])){

    $email = mysqli_real_escape_string($con, $_POST['email']);
    $sql_valid_sql = "SELECT * FROM admin_user WHERE email = '".$email."'";
    $email_valid = $db->select($sql_valid_sql);


    if(sizeof($email_valid) == 1){

        $token = generateNewString();
        $sql_token = "UPDATE admin_user SET token = '".$token."', token_expire_date= DATE_ADD(NOW(), INTERVAL 5 MINUTE) WHERE email = '".$email."'";

        if($con->query($sql_token)){

            require_once 'src/plugins/PHPMailer/src/Exception.php';
            require_once 'src/plugins/PHPMailer/src/PHPMailer.php';
            require_once 'src/plugins/PHPMailer/src/SMTP.php';

            $mail = new PHPMailer\PHPMailer\PHPMailer();
            $mail->isSMTP();
            $mail->Host = "timmy.aserv.co.za";
            $mail->SMTPSecure = "ssl";
            $mail->Port = 465;
            $mail->SMTPAuth = true;
            $mail->Username = 'no_reply@creativeplatform.co.za';
            $mail->Password = 'Noselicks101';
            $mail->addAddress($email);
            $mail->setFrom("no_reply@creativeplatform.co.za", "Admin Panel");
            $mail->Subject = "Reset Password";
            $mail->isHTML(true);
            $mail->Body = "
	            Hi,<br><br>
	            
	            In order to reset your password, please click on the link below:<br>
	            <a href='
	            http://admin.creativeplatform.co.za/admin/reset_admin_password.php?email=$email&token=$token
	            '>http://admin.creativeplatform.co.za/admin/reset_admin_password.php?email=$email&token=$token</a><br><br>
	            
	            Kind Regards,<br>
	            Vanita Admin
	        ";

            if($mail->send()){
                exit(json_encode(array("status" => 1, "msg" => 'Please Check Your Email Inbox!')));
            }
            else{
                exit(json_encode(array("status" => 0, "msg" => 'Something Wrong Just Happened! Please try again!')));
            }





        }



    }else
        exit(json_encode(array("status" => 0, "msg" => 'No User with that email!')));


}




?>
<!DOCTYPE html>
<html>
<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>Vanita Pasta | Admin Forgot Password</title>

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
        <h4 class="text-center mb-30">Admin | Forgot Password</h4>
        <p class="text-center">Enter your email to reset password</p>
        <div id="login_result">

        </div>
        <form name="admin_forgot_password" action="#" method="POST">
            <div class="input-group custom input-group-lg">
                <input type="email" class="form-control" placeholder="Email" name="email" id="email" maxlength="100" required = "required">
                <div class="input-group-append custom">
                    <span class="input-group-text"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                </div>
                <div class="invalid-feedback d-none text-center" id="email_validation">
                    <h5>Please check your email. </h5>
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

        $(document).ready(function () {
            $("#admin_reset_password").click(function(e){
               // e.preventDefault();
                var email = $("#email").val();
                var reg =  /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
                var emailValid = true;

                if( reg.test(admin_forgot_password.email.value) == false ){
                    $("#email").addClass("is-invalid");
                    $("#email_validation").removeClass("d-none");
                    emailValid = false;
                }else if (emailValid == true){
                    $("#email").removeClass('is-invalid');
                    $("#email_validation").addClass('d-none');
                    emailValid = true;
                }

                if(emailValid == true){
                   // alert(email);
                    $.ajax({
                        url: 'forgot_password.php',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            email: email
                        }, success: function (response) {
                            if (!response.success)
                                $("#response").html(response.msg).css('color', "red");
                            else
                                $("#response").html(response.msg).css('color', "green");
                        }
                    });
                }



            })
        })
    </script>




</body>
</html>

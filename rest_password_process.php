<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/23
 * Time: 2:09 PM
 */
include_once "inc/database.php";
$db = new Database();

if(isset($_POST['password'])){
    $email = $_POST['email'];
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $newPassword = password_hash($password, PASSWORD_BCRYPT);

    $sql_password = "UPDATE admin_user SET password = ?, token=? WHERE email = '".$email."'";
    $token_clear =' ';
    if($stmt = $mysqli->prepare($sql_password)){
        $stmt->bind_param('ss', $newPassword,$token_clear );

        if($stmt->execute()){
           // exit(json_encode(array("status" => 1, "msg" => 'Password Updated')));
            echo "Password Updated";
        }else{
            // failer with the execute of stmt
            exit(json_encode(array("status" => 0, "msg" => 'error with execute')));
        }
    }else{
        // failure with preparing the statment
        exit(json_encode(array("status" => 0, "msg" => 'error with stmt')));
    }
    $stmt->close();

// Close connection
    $mysqli->close();
}


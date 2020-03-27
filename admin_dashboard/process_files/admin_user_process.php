<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/23
 * Time: 3:03 PM
 */
include "../inc/functions.php";
secure_session_start();
include_once '../../inc/database.php';
$db = new Database();
if(isset($_SESSION['admin_id'])){






    if(isset($_POST['email'])){
        $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
        $email = mysqli_real_escape_string($con, $_POST['email']);

        $sql_check_email = "SELECT email FROM admin_user WHERE email = '$email'";
        $email_check = $db->select($sql_check_email);
        if(sizeof($email_check) == 0){

            $sql_insert = "INSERT INTO admin_user (firstName, lastName, email) VALUES (?,?,?)";
            if($stmt= $mysqli->prepare($sql_insert)){
                $stmt->bind_param('sss',$first_name, $last_name, $email );

                if($stmt->execute()){
                    exit(json_encode(array("status" => 1, "msg" => 'user updated')));
                }else{
                    // failer with the execute of stmt
                    exit(json_encode(array("status" => 0, "msg" => 'error with execute')));
                }
            }else{
                // failure with preparing the statment
                exit(json_encode(array("status" => 0, "msg" => 'error with stmt')));
            }

        }else{
            exit(json_encode(array("status" => 0, "msg" => 'Email already exists')));
        }




    }
    $stmt->close();

// Close connection
    $mysqli->close();
}else{
    header('Location: ../index.php');
};

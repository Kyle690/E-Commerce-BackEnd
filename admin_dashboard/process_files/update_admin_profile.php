<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/01
 * Time: 12:04 PM
 */
include "../inc/functions.php";
secure_session_start();
include_once '../../inc/database.php';
$db = new Database();


if(isset($_SESSION['admin_id'])){
    $admin_id = $_SESSION['admin_id'];


    if (isset($_POST['admin_update'])){
        if(isset($_POST['admin_first_name'])){
            $firstName = mysqli_real_escape_string($con, $_POST['admin_first_name']);
        }
        if(isset($_POST['admin_last_name'])){
            $lastName = mysqli_real_escape_string($con, $_POST['admin_last_name']);
        }
        if(isset($_POST['admin_email'])){
            $email = mysqli_real_escape_string($con, $_POST['admin_email']);
        }
        $sql = "UPDATE admin_user SET firstName= ?, lastName = ?, email= ? WHERE id = '".$admin_id."'";

        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("sss",$firstName_param,$lastName_param, $email_param);

            $firstName_param = $firstName;
            $lastName_param = $lastName;
            $email_param = $email;


            if($stmt->execute()){
                //echo "update successful";
                header("Location: ../admin_home.php");

            }else {
            echo "Something went wrong with the Sql entry!";
            }
            } else{
            echo "Something went wrong with preparing the statement! ";
            }

            // Close statement
            $stmt->close();

            // Close connection
            $mysqli->close();
        }
}else{
    header('Location: ../../index.php');
}
?>




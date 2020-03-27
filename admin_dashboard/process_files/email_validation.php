<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/08
 * Time: 3:23 PM
 */
include "../inc/functions.php";
secure_session_start();
include_once '../../inc/database.php';
$db = new Database();
if(isset($_POST['email_check'])){
    $email_check = mysqli_real_escape_string($con, $_POST['email_check']);
    $sql6 = "SELECT email FROM customer_details WHERE email = '".$email_check."'";
    $result = $db->select($sql6);



    if(sizeof($result)<1){
        echo "1";

    }else{
        echo "false";

    }
}


?>


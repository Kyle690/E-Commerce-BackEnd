<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/19
 * Time: 1:10 PM
 */
include "../inc/functions.php";
secure_session_start();
include_once '../../inc/database.php';
$db = new Database();


if(isset($_SESSION['admin_id'])){
    $admin_id = $_SESSION['admin_id'];
    $date = date("Y-m-d H-i-s");
    if(isset($_POST['payment_details'])){
        $merchant_id = mysqli_real_escape_string($con, $_POST['merchant_id']);
        $merchant_key = mysqli_real_escape_string($con, $_POST['merchant_key']);

        $sql_check = "SELECT * FROM payment_gateway";
        $result = $db->select($sql_check);
        if(sizeof($result) == 0){
            $sql_insert = "INSERT INTO payment_gateway (merchant_id, merchant_key, admin_id, date_updated) VALUES (?,?,?,?)";
            if($stmt= $mysqli->prepare($sql_insert)){
                $stmt->bind_param('ssss', $merchant_id, $merchant_key, $admin_id, $date);

                if($stmt->execute()){
                    header('location: ../settings/payment_gateway_setup.php');
                }else{
                    // failer with the execute of stmt
                    echo "error with executing sql ";
                }
            }else{
                // failure with preparing the statment
                echo "error with preparing the statement";
            }
        }else if(sizeof($result)==1){
            $sql_update = "UPDATE payment_gateway SET merchant_id=?,merchant_key=?, admin_id=?, date_updated=? WHERE id = '".$result[0]['id']."'";
            if($stmt= $mysqli->prepare($sql_update)){
                $stmt->bind_param('ssss', $merchant_id, $merchant_key, $admin_id, $date);

                if($stmt->execute()){
                    header('location: ../settings/payment_gateway_setup.php');
                }else{
                    // failer with the execute of stmt
                    echo "error with executing sql ";
                }
            }else{
                // failure with preparing the statment
                echo "error with preparing the statement";
            }
        }
    }

    $stmt->close();

// Close connection
    $mysqli->close();




}else{
    header('Location: ../../index.php');
}
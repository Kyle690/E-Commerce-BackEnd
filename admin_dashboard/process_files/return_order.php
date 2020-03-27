<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/18
 * Time: 4:04 PM
 */

include "../inc/functions.php";
secure_session_start();
include_once '../../inc/database.php';
$db = new Database();
if(isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $date = date("Y-m-d H-i-s");
    if(isset($_POST['order_num'])){
        $order_number  = mysqli_real_escape_string($con, $_POST['order_num']);
        $return_message = mysqli_real_escape_string($con, $_POST['rtn_message']);

        $sql_return = "INSERT INTO order_returns (order_num, admin_id, message, date_returned) VALUES (?,?,?,?)";
        if($stmt= $mysqli->prepare($sql_return)){
            $stmt->bind_param('iiss', $order_number, $admin_id,  $return_message, $date);

            if($stmt->execute()){
               $order_message_return =TRUE;
            }else{
                // failer with the execute of stmt
                echo "error with executing sql ";
            }
        }else{
            // failure with preparing the statment
            echo "error with preparing the statement";
        }
        $status = 'returned';
        if($order_message_return){
            $sql_update_order = "UPDATE order_status SET status=? WHERE order_num ='".$order_number."'";
            if ($stmt = $mysqli->prepare($sql_update_order)) {
                $stmt->bind_param('s', $status);


                if ($stmt->execute()) {
                    echo "Order Returned";

                } else {
                    // failer with the execute of stmt
                    echo "error with executing sql ";
                }
            } else {
                // failure with preparing the statment
                echo "error with preparing the statment";
            }
        }
    }





}else{
    header('Location: ../../index.php');

}
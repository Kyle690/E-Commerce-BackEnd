<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/14
 * Time: 5:19 PM
 */
include "../inc/functions.php";
secure_session_start();
include_once '../../inc/database.php';
$db = new Database();
include '../../inc/email_processing.php';
$update_client = new email_processing();
if(isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $date = date("Y-m-d H-i-s");

    if(isset($_POST['order_action'])){

        $order_num = $_POST['order_num'];
        if($_POST['order_action'] == 'processing') {
            $status = "processing" ;

            $sql_order_process = "UPDATE order_status SET status=?, date_processed=?, processed_id=? WHERE order_num = '".$order_num."'";
        }else if ($_POST['order_action'] == 'delivering'){
            $sql_order_process = "UPDATE order_status SET status=?, date_delivery=?, delivery_id=? WHERE order_num = '".$order_num."'";
            $status = "delivering";
        }else if ($_POST['order_action'] == 'delivered'){
            $sql_order_process = "UPDATE order_status SET status=?, date_delivered=?, delivered_id=? WHERE order_num = '".$order_num."'";
            $status = "delivered";
        }





            if ($stmt = $mysqli->prepare($sql_order_process)) {
                $stmt->bind_param('sss', $status,$date, $admin_id);


                if ($stmt->execute()) {
                    $sql_completed = TRUE;

                } else {
                    // failer with the execute of stmt
                    echo "error with executing sql ";
                }
            } else {
                // failure with preparing the statment
                echo "error with preparing the statment";
            }
            if($sql_completed){
            $order_num_array = array();
            array_push($order_num_array, $order_num );
            $update_client->order_update($order_num, $status, $db);
            }


    }

    if(isset($_POST['order_action_pay'])){
        $order_num = $_POST['order_num'];
        $payStatus = $_POST['payStatus'];

        $sql_pay = "UPDATE orders SET payment_status=? WHERE order_num = '".$order_num."'";

        if ($stmt = $mysqli->prepare($sql_pay)) {
            $stmt->bind_param('s', $payStatus);


            if ($stmt->execute()) {
                echo "Order Updated";

            } else {
                // failer with the execute of stmt
                echo "error with executing sql ";
            }
        } else {
            // failure with preparing the statment
            echo "error with preparing the statment";
        }



    }









}else{
    header('Location: ../../index.php');

}
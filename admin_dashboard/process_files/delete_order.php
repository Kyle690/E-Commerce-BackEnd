<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/07/08
 * Time: 2:00 PM
 */include "../inc/functions.php";
secure_session_start();
include_once '../../inc/database.php';
$db = new Database();
if(isset($_SESSION['admin_id'])){
    if(isset($_POST['order_num'])){
        $ordernum = mysqli_real_escape_string($con, $_POST['order_num']);
        //delete order from orders
        $sql_orders = "DELETE FROM orders WHERE order_num='$ordernum'";

        if($con->query($sql_orders) === TRUE){
            $delete_from_orders = TRUE;
        }else{
            exit(json_encode(array("status" => 0, "msg" => "Cant delete from order")));
        }
        //delete order from order products
        if($delete_from_orders){
            $sql_order_product = "DELETE FROM order_product WHERE order_number = '$ordernum'";
            if($con->query($sql_order_product) === TRUE){
                $deleted_from_order_product = TRUE;
            }else{
                exit(json_encode(array("status" => 0, "msg" => "Cant delete from order products")));
            }
        }
        //delete order from order totals
        if($deleted_from_order_product){
            $sql_order_totals = "DELETE FROM order_totals WHERE order_number = '$ordernum'";
            if($con->query($sql_order_totals) === TRUE){
                $deleted_from_order_total = TRUE;
            }else{
                exit(json_encode(array("status" => 0, "msg" => "Cant delete from order total")));
            }
        }

        // delete order from order status
        if($deleted_from_order_total){
            $sql_delete_status = "DELETE FROM order_status WHERE order_num = '$ordernum' ";
            if($con->query($sql_delete_status) === TRUE){
                exit(json_encode(array("status" => 1, "msg" => "Order Deleted Successfully!")));
            }else{
                exit(json_encode(array("status" => 0, "msg" => "Cant delete from order status")));
            }
        }

    }else{
        exit(json_encode(array("status" => 0, "msg" => "Process file error")));
    }
}
else{
    header('Location: ../../index.php');
};
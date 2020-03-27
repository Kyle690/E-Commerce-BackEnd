<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/05/03
 * Time: 7:02 PM
 */
include "../inc/functions.php";
secure_session_start();
include_once '../../inc/database.php';
$db = new Database();
include "../../inc/email_processing.php";
$update_client = new email_processing();
if(isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $date = date("Y-m-d H-i-s");

        if( isset($_POST['bulk_proces'])){
            $bulk_process = $_POST['bulk_proces'];

            $status = 'processing';
            for($i=0; $i < sizeof($bulk_process); $i++){
                $sql_process = "UPDATE order_status SET status=?, date_processed=?, processed_id=? WHERE order_num = '".$bulk_process[$i]."'";
                if ($stmt = $mysqli->prepare($sql_process)) {
                    $stmt->bind_param('ssi', $status,$date, $admin_id);


                    if ($stmt->execute()) {
                       $db_processing_updated = TRUE;

                    } else {
                        // failer with the execute of stmt
                        echo "error with executing sql ";
                    }
                } else {
                    // failure with preparing the statment
                    echo "error with preparing the statment";
                }
                if($db_processing_updated){
                    $process = 'processing';
                    $update_client->order_update($bulk_process[$i],$process, $db);

                }



            }
            //if($processing){
                //echo 'Orders Updated.';
            //}


        }


        if(isset($_POST['bulk_delivery'])){
            $bulk_delivery = $_POST['bulk_delivery'];
            $status = 'delivering';
            for($i=0; $i < sizeof($bulk_delivery); $i++){
                $sql_delivery = "UPDATE order_status SET status=?, date_delivery=?, delivery_id=? WHERE order_num = '".$bulk_delivery[$i]."'";
                if ($stmt = $mysqli->prepare($sql_delivery)) {
                    $stmt->bind_param('ssi', $status,$date, $admin_id);


                    if ($stmt->execute()) {
                        $db_delivery_updated= TRUE;

                    } else {
                        // failer with the execute of stmt
                        echo "error with executing sql ";
                    }
                } else {
                    // failure with preparing the statment
                    echo "error with preparing the statment";
                }
                if($db_delivery_updated){
                    $process = 'delivering';
                    $update_client->order_update($bulk_delivery[$i],$process, $db);

                }
            }


            /*if($delivering){
                echo 'Orders Updated.';
            }*/




        }

        if(isset($_POST['bulk_delivered'])){
            $bulk_delivered = $_POST['bulk_delivered'];
            $status = 'delivered';
            for($i=0; $i < sizeof($bulk_delivered); $i++){
                $sql_delivered = "UPDATE order_status SET status=?, date_delivered=?, delivered_id=? WHERE order_num = '".$bulk_delivered[$i]."'";
                if ($stmt = $mysqli->prepare($sql_delivered)) {
                    $stmt->bind_param('ssi', $status,$date, $admin_id);


                    if ($stmt->execute()) {
                        $db_delivered_updated= TRUE;
                        $client_email_sent = TRUE;
                    } else {
                        // failer with the execute of stmt
                        echo "error with executing sql ";
                    }
                } else {
                    // failure with preparing the statment
                    echo "error with preparing the statment";
                }
                if($db_delivered_updated){
                    $process = 'delivered';
                    $update_client->order_update($bulk_delivered[$i],$process, $db);

                }
            }

            if($client_email_sent){
                echo 'Orders Updated.';
            }
        }

    $stmt->close();

// Close connection
    $mysqli->close();
}else{
    header('Location: ../../index.php');

}
?>
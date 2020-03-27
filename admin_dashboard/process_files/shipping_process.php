<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/19
 * Time: 8:48 PM
 */
include "../inc/functions.php";
secure_session_start();
include_once '../../inc/database.php';
$db = new Database();
if(isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $date = date("Y-m-d H-i-s");

    if(isset($_POST['shipping_details'])){
        $cost_per_kilometer = mysqli_real_escape_string($con, $_POST['cost_per_kilometer']);
        $free_shipping_distance = mysqli_real_escape_string($con, $_POST['free_shipping_distance']);
        $max_distance = mysqli_real_escape_string($con, $_POST['max_distance']);
        $google_key = mysqli_real_escape_string($con, $_POST['google_key']);

        $sql_check = "SELECT * FROM shipping_details";
        $result = $db->select($sql_check);
        if(sizeof($result) == 0){
            $sql_insert = "INSERT INTO shipping_details (cost_per_km, free_ship, max_dist, google_api, admin_id, dat_updated) VALUES (?,?,?,?,?,?)";
            if($stmt= $mysqli->prepare($sql_insert)){
                $stmt->bind_param('dddsis',$cost_per_kilometer,$free_shipping_distance, $max_distance, $google_key, $admin_id, $date);

                if($stmt->execute()){
                    header('location: ../settings/shipping.php');
                }else{
                    // failer with the execute of stmt
                    echo "error with executing sql ";
                }
            }else{
                // failure with preparing the statment
                echo "error with preparing the statement";
            }
        } elseif (sizeof($result)==1){
            $sql_update = "UPDATE shipping_details SET cost_per_km=?, free_ship=?, max_dist=?, google_api=?, admin_id=?, dat_updated=? WHERE id ='".$result[0]['id']."'";
            if($stmt= $mysqli->prepare($sql_update)){
                $stmt->bind_param('dddsis',$cost_per_kilometer,$free_shipping_distance, $max_distance, $google_key, $admin_id, $date);

                if($stmt->execute()){
                    header('location: ../settings/shipping.php');
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
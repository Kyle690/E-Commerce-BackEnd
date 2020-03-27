<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/19
 * Time: 12:25 PM
 */
include "../inc/functions.php";
secure_session_start();
include_once '../../inc/database.php';
$db = new Database();


if(isset($_SESSION['admin_id'])){
    $admin_id = $_SESSION['admin_id'];
    $date = date("Y-m-d H-i-s");


    if(isset($_POST['store_details'])){
        $name = mysqli_real_escape_string($con, $_POST['store_name']);
        $contact= mysqli_real_escape_string($con, $_POST['store_contact_num']);
        $vat_num = mysqli_real_escape_string($con, $_POST['store_vat_num']);
        $address = mysqli_real_escape_string($con, $_POST['store_address']);
        $street = mysqli_real_escape_string($con, $_POST['store_street']);
        $suburb = mysqli_real_escape_string($con, $_POST['store_suburb']);
        $city = mysqli_real_escape_string($con, $_POST['store_city']);
        $postal = mysqli_real_escape_string($con, $_POST['postal']);


        $sql_check = "SELECT * FROM store_details";
        $result = $db->select($sql_check);
        if(sizeof($result) == 0){


            $sql_insert = "INSERT INTO store_details (name, contact_num, address, street, suburb, city,	postal,	admin_id, date_updated,	vat_num) VALUES (?,?,?,?,?,?,?,?,?,?)";
            if($stmt= $mysqli->prepare($sql_insert)){
                $stmt->bind_param('ssssssssss', $name,$contact, $address, $street, $suburb, $city,$postal, $admin_id, $date, $vat_num);

                if($stmt->execute()){
                    header('location: ../settings/stroe_details.php');
                }else{
                    // failer with the execute of stmt
                    echo "error with executing sql ";
                }
            }else{
                // failure with preparing the statment
                echo "error with preparing the statement";
            }



        }else if(sizeof($result) == 1){

            $sql_update = "UPDATE store_details SET name=?, contact_num=?, address=?, street=?, suburb=?, city=?,	postal=?,	admin_id=?, date_updated=?,	vat_num=? WHERE id ='".$result[0]['id']."' ";
            if($stmt= $mysqli->prepare($sql_update)){
                $stmt->bind_param('ssssssssss', $name,$contact, $address, $street, $suburb, $city,$postal, $admin_id, $date, $vat_num);

                if($stmt->execute()){
                    header('location: ../settings/stroe_details.php');
                }else{
                    // failer with the execute of stmt
                    echo "error with executing sql ";
                }
            }else{
                // failure with preparing the statment
                echo "error with preparing the statement";
            }
        }else{
            header('location: ../settings/stroe_details.php');
        }












    }





}else{
    header('Location: ../../index.php');
}
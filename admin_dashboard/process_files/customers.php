<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/07
 * Time: 9:00 PM
 */
include "../inc/functions.php";
secure_session_start();
include_once '../../inc/database.php';
$db = new Database();
if(isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];

    $dateAdded = date("Y-m-d H-i-s");
    // add customer
        if(isset($_POST['add_customer'])){

            if(isset($_POST['first_name'])){
                $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
            }
            if(isset($_POST['last_name'])){
                $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
            }
            if(isset($_POST['email'])){
                $email= mysqli_real_escape_string($con, $_POST['email']);
            }
            if(isset($_POST['contact_num'])){
                $contact_num = mysqli_real_escape_string($con, $_POST['contact_num']);
            }
            if(isset($_POST['ship_building'])){
                $ship_building= mysqli_real_escape_string($con, $_POST['ship_building']);
            }
            if(isset($_POST['ship_address'])){
                $ship_building = mysqli_real_escape_string($con, $_POST['ship_building']);
            }
            if(isset($_POST['ship_street'])){
                $ship_street = mysqli_real_escape_string($con, $_POST['ship_street']);
            }
            if(isset($_POST['ship_suburb'])){
                $ship_suburb = mysqli_real_escape_string($con, $_POST['ship_suburb']);
            }
            if(isset($_POST['ship_city'])){
                $ship_city = mysqli_real_escape_string($con, $_POST['ship_city']);
            }
            if(isset($_POST['ship_province'])){
                $ship_province = mysqli_real_escape_string($con, $_POST['ship_province']);
            }
            if(isset($_POST['ship_postal'])){
                $ship_postal = mysqli_real_escape_string($con, $_POST['ship_postal']);
            }
            if(isset($_POST['accepts_marketing'])){
                if($_POST['accepts_marketing'] == 'yes'){
                    $marketing = 'yes';
                }

            }else{
                $marketing = 'no';
            }
            if(isset($_POST['accepts_newsletter'])) {
                if ($_POST['accepts_newsletter']=='yes'){
                    $newsletter = 'yes';
                }else{
                    $newsletter = 'no';
                }
            }
            $sql5 = "SELECT email FROM customer_details WHERE email = '".$email."'";
            $result = $db->select($sql5);
            if(sizeof($result) == 0) {


                $sql = "INSERT INTO customer_details (first_name, last_name, email, contact_num, marketing, date_created, newsletter) VALUES (?,?,?,?,?,?,?)";
                if ($stmt = $mysqli->prepare($sql)) {
                    $stmt->bind_param('sssssss', $first_name_param, $last_name_param, $email_param, $contact_num_param, $marketing_param, $date_created_param, $newsletter);
                    $first_name_param = $first_name;
                    $last_name_param = $last_name;
                    $email_param = $email;
                    $contact_num_param = $contact_num;
                    $marketing_param = $marketing;
                    $date_created_param = $dateAdded;


                    if ($stmt->execute()) {
                        $success = TRUE;

                    } else {
                        // failer with the execute of stmt
                        echo "error with executing sql ";
                    }
                } else {
                    // failure with preparing the statment
                    echo "error with preparing the statment";
                }
                if ($success == TRUE) {
                    $sql2 = "SELECT id FROM customer_details ORDER BY id DESC";
                    $result_id = $db->select($sql2);
                    $lastId = $result_id[0]['id'];

                    // sql to add shipping details
                    $sql3 = "INSERT INTO customer_shipping_details (customer_id, building_name, street, suburb , city, province, postal_code) VALUES(?,?,?,?,?,?,?)";
                    if ($stmt = $mysqli->prepare($sql3)) {
                        $stmt->bind_param('sssssss', $customer_id_param, $building_name_param, $street_param, $suburb_param, $city_param, $province_param, $postal_code_param);
                        $customer_id_param = $lastId;
                        $building_name_param = $ship_building;
                        $street_param = $ship_street;
                        $suburb_param = $ship_suburb;
                        $city_param = $ship_city;
                        $province_param = $ship_province;
                        $postal_code_param = $ship_postal;


                        if ($stmt->execute()) {
                            header("location: ../customers/customers.php");

                        } else {
                            // failer with the execute of stmt
                            echo "error with executing sql of adderss ";
                        }
                    } else {
                        // failure with preparing the statment
                        echo "error with preparing the statment";
                    }
                }
            }else{
                echo "email already exisists";
            }
        }


    // Update customer data
    if(isset($_POST['edit_customer'])){
        $customer_edit_id = $_POST['customer_id'];
        if(isset($_POST['first_name'])){
            $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
        }
        if(isset($_POST['last_name'])){
            $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
        }
        if(isset($_POST['email'])){
            $email= mysqli_real_escape_string($con, $_POST['email']);
        }
        if(isset($_POST['contact_num'])){
            $contact_num = mysqli_real_escape_string($con, $_POST['contact_num']);
        }
        if(isset($_POST['ship_building'])){
            $ship_building= mysqli_real_escape_string($con, $_POST['ship_building']);
        }
        if(isset($_POST['ship_address'])){
            $ship_building = mysqli_real_escape_string($con, $_POST['ship_building']);
        }
        if(isset($_POST['ship_street'])){
            $ship_street = mysqli_real_escape_string($con, $_POST['ship_street']);
        }
        if(isset($_POST['ship_suburb'])){
            $ship_suburb = mysqli_real_escape_string($con, $_POST['ship_suburb']);
        }
        if(isset($_POST['ship_city'])){
            $ship_city = mysqli_real_escape_string($con, $_POST['ship_city']);
        }
        if(isset($_POST['ship_province'])){
            $ship_province = mysqli_real_escape_string($con, $_POST['ship_province']);
        }
        if(isset($_POST['ship_postal'])){
            $ship_postal = mysqli_real_escape_string($con, $_POST['ship_postal']);
        }
        if(isset($_POST['accepts_marketing'])){
            if($_POST['accepts_marketing'] == 'yes'){
                $marketing = 'yes';
            }

        }else{
            $marketing = 'no';
        }
        if(isset($_POST['accepts_newsletter'])){
            if($_POST['accepts_newsletter'] == 'yes'){
                $newsletter = 'yes';
            }else{
                $newsletter = 'no';
            }
        }



            $sql = "UPDATE customer_details SET first_name = ?, last_name = ?, email= ?, contact_num=?, marketing=?, newsletter=? WHERE id = '".$customer_edit_id."'";

            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param('ssssss', $first_name_param, $last_name_param, $email_param, $contact_num_param, $marketing_param, $newsletter_param);
                $first_name_param = $first_name;
                $last_name_param = $last_name;
                $email_param = $email;
                $contact_num_param = $contact_num;
                $marketing_param = $marketing;
                $newsletter_param = $newsletter;

                if ($stmt->execute()) {
                    $success = TRUE;

                } else {
                    // failer with the execute of stmt
                    echo "error with executing sql ";
                }
            } else {
                // failure with preparing the statment
                echo "error with preparing the statment";
            }
            if ($success == TRUE) {

                // sql to update shipping details
                $sql3 = "UPDATE customer_shipping_details SET  building_name= ?, street=?, suburb =? , city= ?, province=?, postal_code=? WHERE customer_id = '".$customer_edit_id."' ";
                if ($stmt = $mysqli->prepare($sql3)) {
                    $stmt->bind_param('ssssss',  $building_name_param, $street_param, $suburb_param, $city_param, $province_param, $postal_code_param);

                    $building_name_param = $ship_building;
                    $street_param = $ship_street;
                    $suburb_param = $ship_suburb;
                    $city_param = $ship_city;
                    $province_param = $ship_province;
                    $postal_code_param = $ship_postal;


                    if ($stmt->execute()) {
                        header("location: ../customers/customers.php");

                    } else {
                        // failer with the execute of stmt
                        echo "error with executing sql of adderss ";
                    }
                } else {
                    // failure with preparing the statment
                    echo "error with preparing the statment";
                }
            }

    }



}else{
    header('Location: ../../../../index.php');
}

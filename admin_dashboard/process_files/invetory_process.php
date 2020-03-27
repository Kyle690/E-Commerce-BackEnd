<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/11
 * Time: 1:01 PM
 */
include "../inc/functions.php";
secure_session_start();
include_once '../../inc/database.php';
$db = new Database();
if(isset($_SESSION['admin_id'])){
    $admin_id = $_SESSION['admin_id'];
    $date_updated = date("Y-m-d H-i-s");
    if(isset($_POST['updated_inventory'])){

        if(isset($_POST['product_variant_sku_qty'])){
            $var_sku = $_POST['product_variant_sku_qty'];
            $var_stock = array();
            foreach($var_sku as $value){
                $var_sku_escaped= mysqli_real_escape_string($con, $value);
                array_push($var_stock, $var_sku_escaped);
            }
            //echo "stock:";
            //print_r($var_stock);
            //echo "<br>";
        }
        if(isset($_POST['sku_id'])){
            $var_id = $_POST['sku_id'];
            $var_id_array = array();
            foreach($var_id as $value){
                $var_id_escaped= mysqli_real_escape_string($con, $value);
                array_push($var_id_array, $var_id_escaped );
            }
            //echo"id:";
            //print_r($var_id_array);
        }
        //print_r($var_stock);
       // print_r($var_id_array);

        for ($i = 0; $i<(sizeof($var_id_array)); $i++){

            $sql = "UPDATE product_variants SET var_stock = ?, date_updated=? WHERE id = $var_id_array[$i]";

            if($stmt= $mysqli->prepare($sql)){
                $stmt->bind_param('ss', $var_stock[$i], $date_updated);



                if($stmt->execute()){
                   header("location: ../catalog/inventory.php");

                   // http_redirect("../catalog/inventory.php");
                }else{
                    // failure with the execute of stmt
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
};
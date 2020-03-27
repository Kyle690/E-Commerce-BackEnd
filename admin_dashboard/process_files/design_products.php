<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/18
 * Time: 10:22 PM
 */
include "../inc/functions.php";
secure_session_start();
include_once '../../inc/database.php';
$db = new Database();


if(isset($_SESSION['admin_id'])){
    $admin_id = $_SESSION['admin_id'];

    if(isset($_POST['main_img'])){

        $main_img = $_POST['main_img'];
        $product_1 = $_POST['products_1st'];
        $product_2 = $_POST['product_2'];
        $product_3 = $_POST['product_3'];

        // Check if result is in the db
        $sql = "SELECT * FROM design_products";
        $result = $db->select($sql);
        // if there is a result update
        if(sizeof($result) == 1) {
            $sql_update = "UPDATE design_products SET main_product_id=?, 1st_product=?, 2nd_product=?, 3rd_product=? WHERE id='" . $result[0]['id'] . "'";
            if ($stmt = $mysqli->prepare($sql_update)) {
                $stmt->bind_param("iiii",$main_img,$product_1, $product_2, $product_3 );


                if ($stmt->execute()) {
                    //echo "update successful";
                    echo "Design Updated";

                } else {
                    echo "Something went wrong with the Sql entry!";
                }
            } else {
                echo "Something went wrong with preparing the statement! ";
            }

            // Close statement
            $stmt->close();

            // Close connection
            $mysqli->close();

        }if(sizeof($result) == 0){
            $sql_insert = "INSERT INTO design_products (main_product_id, 1st_product, 2nd_product, 3rd_product) VALUES (?,?,?,?)";
            if ($stmt = $mysqli->prepare($sql_insert)) {
                $stmt->bind_param("iiii",$main_img,$product_1, $product_2, $product_3 );


                if ($stmt->execute()) {
                    //echo "update successful";
                    echo "Design Updated";

                } else {
                    echo "Something went wrong with the Sql entry!";
                }
            } else {
                echo "Something went wrong with preparing the statement! ";
            }

        }

    }else{
        echo "error with form";
    }







}else{
    header('Location: ../../index.php');
}
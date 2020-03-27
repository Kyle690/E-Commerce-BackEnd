<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/20
 * Time: 12:40 PM
 */

include "../inc/functions.php";
secure_session_start();
include_once '../../inc/database.php';
$db = new Database();

if(isset($_SESSION['admin_id'])){

    if(isset($_POST['img_kind'])){
        $delete_id = $_POST['img_id'];
        if($_POST['img_kind'] == 'category'){
            $sql_cat = "SELECT * FROM cat_img_gal WHERE id='".$delete_id."'";
            $result = $db->select($sql_cat);
            $file_name = "../../../storefront/img/category_img/".$result[0]['file_name'];

            if (unlink($file_name)){
               $sql_delete_file = "DELETE FROM cat_img_gal WHERE id='".$delete_id."'";
                if($stmt = $mysqli->prepare($sql_delete_file)){
                   // $stmt->bind_param('i', $delete_id);

                    if($stmt->execute()){
                        // sql was successfull
                        echo "Image Deleted";
                    }else{
                        // failure with the execute of stmt
                        echo "error with the sql of info";
                    }
                }else{
                    // failure with preparing the statment
                    echo 'error with preparing the statement of info';
                }
            }

        }else if ($_POST['img_kind'] == 'product'){
            $sql_prod = "SELECT * FROM prod_img_gal WHERE id='".$delete_id."'";
            $result = $db->select($sql_prod);
            $file_name = "../../../storefront/img/product_img/".$result[0]['file_name'];

            if (unlink($file_name)){
                $sql_delete_file = "DELETE FROM prod_img_gal WHERE id='".$delete_id."'";
                if($stmt = $mysqli->prepare($sql_delete_file)){
                    // $stmt->bind_param('i', $delete_id);

                    if($stmt->execute()){
                        // sql was successfull
                        echo "Image Deleted";
                    }else{
                        // failure with the execute of stmt
                        echo "error with the sql of info";
                    }
                }else{
                    // failure with preparing the statment
                    echo 'error with preparing the statement of info';
                }
            }
        }


        $stmt->close();

// Close connection
        $mysqli->close();

    }



}else{
    header('Location: ../../../../index.php');

}

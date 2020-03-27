<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/05
 * Time: 9:33 PM
 */
include '../../inc/database.php';
$db = new Database();
include_once ("../inc/functions.php");
secure_session_start();
if(isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];

    $dateAdded = date("Y-m-d H-i-s");
    $final_success = FALSE;
    // Add new product
    if(isset($_POST['new_product'])){
        if(isset($_POST['prod_title'])){
            $prod_title = mysqli_real_escape_string($con, $_POST['prod_title']);
        }
        if(isset($_POST['prod_description'])){
            $prod_description = mysqli_real_escape_string($con, $_POST['prod_description']);
        }
        // array
        if(isset($_POST['var_name'])){
            $var_name = $_POST['var_name'];
            foreach($var_name as $value){
                $value = mysqli_real_escape_string($con, $value);
            }
        }
        // array
        if(isset($_POST['var_sku'])){
            $var_sku = $_POST['var_sku'];
            foreach($var_sku as $value){
                $value = mysqli_real_escape_string($con, $value);
            }
        }
        // array
        if(isset($_POST['var_price'])){
            $var_price = $_POST['var_price'];
            foreach($var_price as $value){
                $value = mysqli_real_escape_string($con, $value);
            }
        }
        if(isset($_POST['main_img'])){
            $main_img = mysqli_real_escape_string($con, $_POST['main_img']);
        }
        if(isset($_POST['2nd_img'])){
            $_img2 = mysqli_real_escape_string($con, $_POST['2nd_img']);
        }
        if(isset($_POST['3rd_img'])){
            $_img3 = mysqli_real_escape_string($con, $_POST['3rd_img']);
        }
        if(isset($_POST['4th_img'])){
            $_img4 = mysqli_real_escape_string($con, $_POST['4th_img']);
        }
        if(isset($_POST['prod_ingredients'])){
            $prod_ingredients = mysqli_real_escape_string($con, $_POST['prod_ingredients']);
        }
        if(isset($_POST['prod_cooking'])){
            $prod_cooking = mysqli_real_escape_string($con, $_POST['prod_cooking']);
        }
        if(isset($_POST['prod_storage'])){
            $prod_storage = mysqli_real_escape_string($con, $_POST['prod_storage']);
        }
        // array
        if(isset($_POST['prod_categories'])){

            $prod_categories = $_POST['prod_categories'];
            $checked_cat = array();
                foreach($prod_categories as $value) {
                    $new_prod_cat = mysqli_real_escape_string($con, $value);
                    array_push($checked_cat, $new_prod_cat);
                    }

                $prod_cat_str = implode(",", $checked_cat);
            }
        if(isset($_POST['product_seo_title'])){
            $product_seo_title = mysqli_real_escape_string($con, $_POST['product_seo_title']);
        }
        if(isset($_POST['product_seo_desc'])){
            $product_seo_description = mysqli_real_escape_string($con, $_POST['product_seo_desc']);
        }

        $prod_status = 'enabled';

        $sql = "INSERT INTO products (title, description, ingredients, cooking, storage, main_img, 2nd_img,	3rd_img, 4th_img, seo_title, seo_description, date_added, admin_user_id, status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)" ;
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('ssssssssssssss', $title_param, $prod_description_param, $prod_ingredients_param, $prod_cooking_param, $prod_storage_param, $main_img_param, $_img2_param, $_img3_param, $_img4_param, $product_seo_title_param, $product_seo_description_param, $dateAdded_param, $admin_id_param, $prod_status );
            $title_param = $prod_title;
            $prod_description_param = $prod_description;
            $prod_ingredients_param = $prod_ingredients;
            $prod_cooking_param = $prod_cooking;
            $prod_storage_param = $prod_storage;
            $main_img_param = $main_img;
            $_img2_param = $_img2;
            $_img3_param = $_img3;
            $_img4_param = $_img4;
            $product_seo_title_param = $product_seo_title;
            $product_seo_description_param = $product_seo_description;
            $dateAdded_param = $dateAdded;
            $admin_id_param = $admin_id;

            if($stmt->execute()){
                // sql was successfull
                $success = TRUE;
            }else{
                // failure with the execute of stmt
                echo "error with the sql of info";
            }
        } else{
            // failure with preparing the statment
            echo 'error with preparing the statement of info';
        }
        // add product variants to db
        if($success == TRUE){
            $sql2 = "SELECT id FROM products ORDER BY id DESC ";
            $result = $db->select($sql2);
            $last_id = $result[0]['id'];
            $var_name_length = sizeof($var_name);

            $sql3 = "INSERT INTO product_variants (product_id, var_name, var_sku_code, var_price, date_added, admin_id) VALUES (?,?,?,?,?,?)";

            for($i = 0; $i< $var_name_length; $i++){
                if($stmt = $mysqli->prepare($sql3)) {
                    $stmt->bind_param("ssssss", $product_id_param, $var_name_param, $var_sku_param, $var_price_param, $dateAdded_param, $admin_id_param);

                    $product_id_param = $last_id;
                    $var_name_param = $var_name[$i];
                    $var_sku_param = $var_sku[$i];
                    $var_price_param = $var_price[$i];
                    $dateAdded_param = $dateAdded;
                    $admin_id_param = $admin_id;

                if($stmt->execute()){
                        // sql was successfull
                        $second_success = TRUE;
                    }else{
                        // failure with the execute of stmt
                        echo "error with the sql of var";
                        $second_success = FALSE;
                    }
                }else{
                    // failure with preparing the statement
                    echo 'error with preparing the statement of var';
                    $second_success = FALSE;
                }
            }
            // add categories of product
            if($second_success == TRUE){
                $sql4 = "INSERT INTO category_products (category_id, product_id) VALUES (?,?)";
                if($stmt = $mysqli->prepare($sql4)){
                    $stmt->bind_param('ss',  $prod_cat_str_param , $product_id_param);

                    $prod_cat_str_param = $prod_cat_str;
                    $product_id_param = $last_id;

                    if($stmt->execute()){
                        // sql was successfull
                        $final_success = TRUE;
                    }else{
                        // failure with the execute of stmt
                        echo "error with the sql of prod_cat";
                        $final_success = FALSE;
                    }
                }else{
                    // failure with preparing the statment
                    echo 'error with preparing the statement of prod_cat';
                    $final_success = FALSE;
                }
            }
        }


        if($final_success == TRUE){
            header("location: ../catalog/products.php");
        }

    }

    // Edit Product
    if(isset($_POST['edit_product'])){
        $product_id = $_POST['product_id'];

        if(isset($_POST['prod_title'])){
            $prod_title = mysqli_real_escape_string($con, $_POST['prod_title']);
        }
        if(isset($_POST['prod_description'])){
            //$prod_description = mysqli_real_escape_string($con, $_POST['prod_description']);
            $prod_description = $_POST['prod_description'];
        }
        // array
        if(isset($_POST['var_name'])){
            $var_name = $_POST['var_name'];
            foreach($var_name as $value){
                $var_name_escaped = mysqli_real_escape_string($con, $value);
            }
        }
        // array
        if(isset($_POST['var_sku'])){
            $var_sku = $_POST['var_sku'];
            foreach($var_sku as $value){
                $var_sku_escaped= mysqli_real_escape_string($con, $value);
            }
        }
        // array
        if(isset($_POST['var_price'])){
            $var_price = $_POST['var_price'];

            foreach($var_price as $value){
                $var_price_escaped = mysqli_real_escape_string($con, $value);
            }
        }
        if(isset($_POST['main_img'])){
            $main_img = mysqli_real_escape_string($con, $_POST['main_img']);
        }
        if(isset($_POST['2nd_img'])){
            $_img2 = mysqli_real_escape_string($con, $_POST['2nd_img']);
        }
        if(isset($_POST['3rd_img'])){
            $_img3 = mysqli_real_escape_string($con, $_POST['3rd_img']);
        }
        if(isset($_POST['4th_img'])){
            $_img4 = mysqli_real_escape_string($con, $_POST['4th_img']);
        }
        if(isset($_POST['prod_ingredients'])){
            $prod_ingredients = mysqli_real_escape_string($con, $_POST['prod_ingredients']);
        }
        if(isset($_POST['prod_cooking'])){
            $prod_cooking = mysqli_real_escape_string($con, $_POST['prod_cooking']);
        }
        if(isset($_POST['prod_storage'])){
            $prod_storage = mysqli_real_escape_string($con, $_POST['prod_storage']);
        }
        // array
        if(isset($_POST['prod_categories'])){

            $prod_categories = $_POST['prod_categories'];
            $checked_cat = array();
            foreach($prod_categories as $value) {
                $new_prod_cat = mysqli_real_escape_string($con, $value);
                array_push($checked_cat, $new_prod_cat);
            }

            $prod_cat_str = implode(",", $checked_cat);
        }
        if(isset($_POST['product_seo_title'])){
            $product_seo_title = mysqli_real_escape_string($con, $_POST['product_seo_title']);
        }
        if(isset($_POST['product_seo_desc'])){
            $product_seo_description = mysqli_real_escape_string($con, $_POST['product_seo_desc']);
        }
        $prod_status = mysqli_real_escape_string($con, $_POST['prod_status']);
        //echo $_POST['prod_description'];

        $sql = "UPDATE products SET title=?, description=?, ingredients=?, cooking=?, storage=?, main_img=?, 2nd_img=?, 3rd_img=?, 4th_img=?, seo_title=?, seo_description=?, date_modified=?, admin_user_id =?, status=?  WHERE id = '".$product_id."'";

       if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('ssssssssssssss', $title_param, $prod_description_param, $prod_ingredients_param, $prod_cooking_param, $prod_storage_param, $main_img_param, $_img2_param, $_img3_param, $_img4_param, $product_seo_title_param, $product_seo_description_param, $dateUpdated_param, $admin_id_param, $prod_status );
            $title_param = $prod_title;
            $prod_description_param = $prod_description;
            $prod_ingredients_param = $prod_ingredients;
            $prod_cooking_param = $prod_cooking;
            $prod_storage_param = $prod_storage;
            $main_img_param = $main_img;
            $_img2_param = $_img2;
            $_img3_param = $_img3;
            $_img4_param = $_img4;
            $product_seo_title_param = $product_seo_title;
            $product_seo_description_param = $product_seo_description;
            $dateUpdated_param = $dateAdded;
            $admin_id_param = $admin_id;

            if($stmt->execute()){
                // sql was successfull
                $success = TRUE;
            }else{
                // failure with the execute of stmt
                echo "error with the sql of info";
            }
        } else{
            // failure with preparing the statment
            echo 'error with preparing the statement of info';
        }
        // add product variants to db
        if($success == TRUE){
            $sql2 = "SELECT id FROM product_variants WHERE product_id ='".$product_id."'";
            $result = $db->select($sql2);
            $var_db_length = sizeof($result);
            $var_name_length_edited = sizeof($var_name);

            if($var_db_length < $var_name_length_edited){
                $var_difference = $var_name_length_edited - $var_db_length;
                echo $var_difference; // STILL NEEDS TO BE FIGURED OUT
            }

            for($i = 0; $i < $var_db_length; $i++){

                $sql3 = "UPDATE  product_variants SET  var_name=?, var_sku_code=?, var_price=? WHERE id = '".$result[$i]['id']."'";
                //echo $sql3;

                if($stmt = $mysqli->prepare($sql3)) {
                    $stmt->bind_param("sss",  $var_name_param, $var_sku_param, $var_price_param);


                        $var_name_param = $var_name[$i];
                        $var_sku_param = $var_sku[$i];
                        $var_price_param = $var_price[$i];


                        if($stmt->execute()){
                            // sql was successfull
                            $second_success = TRUE;
                        }else{
                            // failure with the execute of stmt
                            echo "error with the sql of var";
                            $second_success = FALSE;
                        }
                    }
                        else{
                        // failure with preparing the statement
                        echo 'error with preparing the statement of var';
                        $second_success = FALSE;
                    }
                }

            if($second_success == TRUE){
               $check_if_More_variants =  $var_name_length_edited - $var_db_length;

               if ($check_if_More_variants > 0 ){
                    $i_new_val = $var_db_length;
                   for($i = ($i_new_val); $i< $var_name_length_edited; $i++){

                        $sql4 = "INSERT INTO product_variants (product_id, var_name, var_sku_code, var_price, date_added, admin_id) VALUES (?,?,?,?,?,?)";

                       if($stmt = $mysqli->prepare($sql4)) {
                           $stmt->bind_param("ssssss", $product_id_param, $var_name_param, $var_sku_param, $var_price_param, $dateAdded_param, $admin_id_param);

                           $product_id_param = $product_id;
                           $var_name_param = $var_name[$i];
                           $var_sku_param = $var_sku[$i];
                           $var_price_param = $var_price[$i];
                           $dateAdded_param = $dateAdded;
                           $admin_id_param = $admin_id;

                           if($stmt->execute()){
                               // sql was successfull
                               $third_success = TRUE;
                               echo "sql was successfull";
                           }else{
                               // failure with the execute of stmt
                               echo "error with the sql of var";
                               $third_success = FALSE;
                           }
                       }else{
                           // failure with preparing the statement
                           echo 'error with preparing the statement of var';
                           $third_success = FALSE;
                       }
                   }
               }else{
                   $third_success = TRUE;
               }
            }

            // add categories of product
            if($third_success == TRUE){
                $sql5 = "SELECT * FROM category_products WHERE  product_id = '".$product_id."'";
                $result = $db->select($sql5);
                if(sizeof($result) == 1){
                    $sql4 = "UPDATE category_products SET category_id=? WHERE product_id = '".$product_id."'";
                        if($stmt = $mysqli->prepare($sql4)){
                            $stmt->bind_param('s',  $prod_cat_str_param );

                            $prod_cat_str_param = $prod_cat_str;

                            if($stmt->execute()){
                                // sql was successfull
                                $final_success = TRUE;
                            }else{
                                // failure with the execute of stmt
                                echo "error with the sql of prod_cat update";
                                $final_success = FALSE;
                            }
                        }else{
                            // failure with preparing the statment
                            echo 'error with preparing the statement of prod_cat update';
                            $final_success = FALSE;
                        }
                } else {
                    $sql4 = "INSERT INTO category_products (category_id, product_id) VALUES (?,?)";
                    if($stmt = $mysqli->prepare($sql4)){
                        $stmt->bind_param('ss',  $prod_cat_str_param , $product_id_param);

                        $prod_cat_str_param = $prod_cat_str;
                        $product_id_param = $product_id;

                        if($stmt->execute()){
                            // sql was successfull
                            $final_success = TRUE;
                        }else{
                            // failure with the execute of stmt
                            echo "error with the sql of prod_cat added";
                            $final_success = FALSE;
                        }
                    }else{
                        // failure with preparing the statment
                        echo 'error with preparing the statement of prod_cat added';
                        $final_success = FALSE;
                    }

                }



            }
        }


        if($final_success == TRUE){
            header("location: ../catalog/products.php");
        }

    }





    // Delete product
    if(isset($_POST['delete_product'])){
        if(isset($_POST['confirm_delete_value'])){
            $confirm_delete_value = mysqli_real_escape_string($con, $_POST['confirm_delete_value']);
            //echo $confirm_delete_value;
           $sql = "DELETE FROM products WHERE id = '".$confirm_delete_value."'";
            if($stmt = $mysqli->prepare($sql)){



                if($stmt->execute()){
                    // sql was successfull
                    $success = TRUE;
                }else{
                    // failure with the execute of stmt
                    echo "error with the sql of info";
                }
            }else{
                // failure with preparing the statment
                echo 'error with preparing the statement of info';
            }
            if($success == TRUE){
                $sql2 = "DELETE FROM product_variants WHERE product_id = '".$confirm_delete_value."'";
                if($stmt = $mysqli->prepare($sql2)){


                    if($stmt->execute()){
                        // sql was successfull
                        $success_2 = TRUE;
                    }else{
                        // failure with the execute of stmt
                        echo "error with the sql of info";
                    }
                }else{
                    // failure with preparing the statment
                    echo 'error with preparing the statement of info';
                }
                if($success_2 == TRUE){
                    $sql3 = "DELETE FROM category_products WHERE product_id = '".$confirm_delete_value."'";
                    if($stmt = $mysqli->prepare($sql3)){
                       

                        if($stmt->execute()){
                            // sql was successfull
                            $deleted = TRUE;

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
            if($deleted == TRUE){
                header("location:../catalog/products.php ");
            }else{
                echo "system error";
            }
        }
    }







   // $stmt->close();

// Close connection
    //$mysqli->close();






}else{
    header('Location: ../../index.php');
}
?>
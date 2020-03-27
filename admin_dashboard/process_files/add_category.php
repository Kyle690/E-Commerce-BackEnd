<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/02
 * Time: 12:26 PM
 */
include "../inc/functions.php";
secure_session_start();
include_once '../../inc/database.php';
$db = new Database();
if(isset($_SESSION['admin_id'])){
    $admin_id = $_SESSION['admin_id'];
    // add category to db
    if(isset($_POST['new_category'])) {
        if(isset($_POST['cat_title'])){
            $cat_title = mysqli_real_escape_string($con, $_POST['cat_title']);
        }
        if(isset($_POST['cat_description'])){
            $cat_description = mysqli_real_escape_string($con, $_POST['cat_description']);
        }
        if(isset($_POST['category_img_file'])){
            $cat_img_file = mysqli_real_escape_string($con, $_POST['category_img_file']);
        }else{$cat_img_file='';}
        if(isset($_POST['seo_title'])){
            $cat_SEO_title = mysqli_real_escape_string($con, $_POST['seo_title']);
        }
        if(isset($_POST['seo_descrip'])){
            $cat_SEO_descrip = mysqli_real_escape_string($con, $_POST['seo_descrip']);
        }
        $cat_status = 'enabled';


        //echo $cat_description;


        $sql = "INSERT INTO categories (title, description, img_src, seo_title, seo_description, status) VALUES(?,?,?,?,?,?)";
        if($stmt= $mysqli->prepare($sql)){
            $stmt->bind_param('ssssss', $title_param, $descrip_param, $img_name_param, $seo_title_param, $seo_descrip_param, $status_param);
            $title_param = $cat_title;
            $descrip_param = $cat_description;
            $img_name_param = $cat_img_file;
            $seo_title_param = $cat_SEO_title;
            $seo_descrip_param = $cat_SEO_descrip;
            $status_param = $cat_status;


            if($stmt->execute()){
                header("location: ../catalog/categories.php#");
                //redirect("../catalog/categories.php");
            }else{
                // failer with the execute of stmt
                echo "error with executing sql ";
            }
        }else{
            // failure with preparing the statment
            echo "error with preparing the statment";
        }

        }



    // edit category of db
    if(isset($_POST['cat_data_update'])){
            if(isset($_POST['cat_title'])){
                $cat_title = mysqli_real_escape_string($con, $_POST['cat_title']);
            }
            if(isset($_POST['cat_description'])){
                $cat_description = mysqli_real_escape_string($con, $_POST['cat_description']);
            }else{$cat_description='';}
            if(isset($_POST['category_img_file'])){
                $cat_img_file = mysqli_real_escape_string($con, $_POST['category_img_file']);
            }else{$cat_img_file = '';}
            if(isset($_POST['seo_title_cat'])){
                $cat_SEO_title = mysqli_real_escape_string($con, $_POST['seo_title_cat']);
            }else{$cat_SEO_title = '';}
            if(isset($_POST['seo_descrip_cat'])){
                $cat_SEO_descrip = mysqli_real_escape_string($con, $_POST['seo_descrip_cat']);
            }else{$cat_SEO_descrip = '';}
            $cat_status = $_POST['cat_status'];
            $cat_id = $_POST['cat_id'];

            $sql = "UPDATE categories SET title=?, description=?, img_src=?, seo_title=?, seo_description=?, status=? WHERE id = '".$cat_id."' ";
            if($stmt= $mysqli->prepare($sql)){
                $stmt->bind_param('ssssss', $title_param, $descrip_param, $img_name_param, $seo_title_param, $seo_descrip_param, $status_param);
                $title_param = $cat_title;
                $descrip_param = $cat_description;
                $img_name_param = $cat_img_file;
                $seo_title_param = $cat_SEO_title;
                $seo_descrip_param = $cat_SEO_descrip;
                $status_param = $cat_status;


                if($stmt->execute()){
                    header("location: ../catalog/categories.php#");
                }else{
                    // failer with the execute of stmt
                    echo "error with executing sql ";
                }
            }else{
                // failure with preparing the statment
                echo "error with preparing the statment";
            }
        }


    // delete category of db
    if(isset($_POST['cat_delete'])){
            $cat_id =  $_POST['catergory_id'];
            $sql = "DELETE FROM categories WHERE id='".$cat_id."'";
            if($stmt = $mysqli->prepare($sql)){


                if($stmt->execute()){
                    // sql was successfull
                    header("location: ../catalog/categories.php#");
                }else{
                    // failure with the execute of stmt
                    echo "error with the sql";
                }
            }else{
                // failure with preparing the statment
                echo 'error with preparing the statement';
            }
        }

    $stmt->close();

// Close connection
    $mysqli->close();
}else{
    header('Location: ../../index.php#');
};
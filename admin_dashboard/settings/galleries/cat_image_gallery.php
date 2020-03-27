<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/19
 * Time: 10:16 PM
 */
include "../../inc/functions.php";
secure_session_start();
include_once "../../../inc/database.php";
$db = new Database();
if(isset($_SESSION['admin_id'])){
    $admin_id = $_SESSION['admin_id'];

    if(isset($_POST['cat_image'])){

    echo '
     <div class="pd-ltr-20 customscroll customscroll-10-p height-100-p xs-pd-20-10">
        <div class="row">
            <!-- BreadCrumbs -->
            <div class="col-md-10 offset-1">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../admin_home.php">Home</a></li>
                    <li class="breadcrumb-item">Settings</li>
                    <li class="breadcrumb-item">Gallery</li>
                    <li class="breadcrumb-item active" aria-current="page">Category Gallery</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="float-left">Edit Images</h5>
                        <button class="btn btn-outline-secondary btn-sm cancel_gal float-right">Cancel</button>
                    </div>
                </div><br>
            </div>
            
        </div>
        <div class="container">
        <div class="row">
        
        
        ';

        $sql_cat_img = "SELECT * FROM cat_img_gal";
        $cat_imgs = $db->select($sql_cat_img);

        foreach ($cat_imgs as $cat_img){
            echo"
            <div class='col-sm-3 text-center'>
            <div class='card'>
                <div class='card-body'>
                        <img src='../../../storefront/img/category_img/{$cat_img['file_name']}' class='img-fluid' >
                    <h6>File name: {$cat_img['file_name']} </h6><br>
                    <button class='btn btn-sm btn-outline-danger delete_img' data-kind='category' data-id='{$cat_img['id']}' >Delete</button>
                </div>
            </div><br>
        
        </div>
            
            
            
            
            ";
        }






       echo'</div>
            </div>
    ';






    }










}else{
    header('Location: ../../../index.php');
};

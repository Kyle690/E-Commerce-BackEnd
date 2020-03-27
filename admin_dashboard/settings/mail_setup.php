<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/04/26
 * Time: 1:21 PM
 */
include ("../inc/sub_head.php");
// Top Nav bar
include("../inc/sub_top_nav_bar.php");
//side_nav
include ('../inc/sub_side_nav_bar.php');
?>
<!-- Main container -->
<div class="main-container" id="main-container">
    <div class="pd-ltr-20 customscroll customscroll-10-p height-100-p xs-pd-20-10">
        <div class="row">
            <!-- BreadCrumbs -->
            <div class="col-md-10 offset-1">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../admin_home.php">Home</a></li>
                    <li class="breadcrumb-item">Settings</li>
                    <li class="breadcrumb-item active" aria-current="page">Mail Setup</li>
                </ol>
            </div>
        </div>
        <div class="col-sm-10">
            <div class="card">
                <div class="card-header">
                    <h6 class="float-left">Email Accounts</h6>
                    <button class="btn btn-success btn-sm float-right">Update details</button>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Contact Form Email</label>
                        <input class="form-control" type="email" name="contact_email" placeholder="info@email.com">
                    </div>
                    <div class="form-group">
                        <label>Order Email</label>
                        <input class="form-control" type="email" name="order_email" placeholder="orders@email.com">
                    </div>
                    <div class="form-group">
                        <label>Marketing Email</label>
                        <input class="form-control" type="email" name="marketing_email" placeholder="sales@email.com">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-sm-12">
    <div class=" footer-wrap bg-white pd-20 mb-20 border-radius-5 box-shadow">
        <p>Copyright - Vania Pasta 2018</p>
    </div>
</div>
<script src="../../vendors/scripts/script.js"></script>

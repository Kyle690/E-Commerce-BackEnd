<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/05/04
 * Time: 10:34 AM
 */
include "../inc/functions.php";
secure_session_start();
if(!isset($_SESSION['admin_id'])) {
    header('Location: ../../index.php');
}
    ?>
<div class="pd-ltr-20 customscroll customscroll-10-p height-100-p xs-pd-20-10">
    <div class="container">
        <div class="row">
            <!-- BreadCrumbs -->
            <div class="col-md-8 offset-1">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../admin_home.php">Home</a></li>
                    <li class="breadcrumb-item"><a>Customers</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Customer</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="container">
        <form  action="../process_files/customers.php" id="new_customer_form" method="POST">
        <div class="row">
            <div class="col-sm-12">

                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-outline-secondary btn-sm float-right" id="cancel_addCust">Cancel</button>
                        <input type="button" class="btn btn-outline-success btn-sm float-right save_btn add_customer_submit" id="" value="Save" name="add_customer">
                    </div>
                </div><br>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h6>Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" class="form-control" name="first_name" maxlength="55" placeholder="John" required>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" class="form-control" name="last_name" maxlength="55" placeholder="Smith">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" id="email" maxlength="255" placeholder="dj@me.com" required>
                        </div>
                        <div class="form-group">
                            <label>Contact Number</label>
                            <input type="text" class="form-control" name="contact_num" maxlength="15" placeholder="0739135274">
                        </div>
                        <div class="form-check" >
                            <input type="checkbox" class="form-check-input" name="accepts_marketing" value="yes" checked>
                            <label class="form-check-label">Accepts marketing</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="accepts_newsletter" value="yes" checked>
                            <label class="form-check-label">Accepts Newsletter</label>
                        </div>
                    </div>
                </div><br>

            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h6>Shipping Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Building(s) name</label>
                            <input class="form-control" name="ship_building" id="ship_building" type="text" maxlength="70">
                        </div>

                        <div class="form-group">
                            <label>Street Address</label>
                            <input class="form-control" name="ship_street" id="ship_street" type="text" maxlength="70">
                        </div>
                        <div class="form-group">
                            <label>Suburb</label>
                            <input class="form-control" name="ship_suburb" id="ship_suburb" type="text" maxlength="70">
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <input class="form-control" name="ship_city"  id="ship_city" type="text" maxlength="70">
                        </div>
                        <div class="form-group">
                            <label>Province</label>
                            <select class="form-control" name="ship_province" id="ship_province">
                                <option>Gauteng</option>
                                <option>Free state</option>
                                <option>Limpopo</option>
                                <option>Eastern Cape</option>
                                <option>Western Cape</option>
                                <option>Mpumalanga</option>
                                <option>North West</option>
                                <option>Kwazulu-Natal</option>
                                <option>Northern Cape</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Postal Code</label>
                            <input class="form-control" name="ship_postal" id="ship_postal" type="text" maxlength="5">
                            <div id="submitInput">

                            </div>

                        </div>

                    </div>
                </div><br>
            </div>
            </form>
        </div>
    </div>
    <div class="col-sm-12">
        <div class=" footer-wrap bg-white pd-20 mb-20 border-radius-5 box-shadow">
            <p>Copyright - Vania Pasta 2018</p>
        </div>
    </div>

</div>
<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        /*
        $("form").submit(function(e){
            e.preventDefault();
        */

        $(document).on("click", ".add_customer_submit", function () {

           var email = $("#email").val();


           $.ajax({
               url: "../process_files/email_validation.php",
               type: "POST",
               data: {email_check: email},
               success: function (data){
                    var email_status = data;

                   if(email_status == 1){
                       $("#submitInput").append('<input type="hidden" name="add_customer">');
                       $("#new_customer_form").submit();
                   }else{
                       alert("Email is already in use!");
                   }

               }
           });
       })
    });

</script>




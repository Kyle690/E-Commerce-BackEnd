<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/23
 * Time: 2:46 PM
 */

include ("../inc/sub_head.php");
// Top Nav bar
include("../inc/sub_top_nav_bar.php");
//side_nav
include ('../inc/sub_side_nav_bar.php');
?>
<div class="main-container" id="main-container">
    <div class="pd-ltr-20 customscroll customscroll-10-p height-100-p xs-pd-20-10">
        <div class="row">
            <!-- BreadCrumbs -->
            <div class="col-md-10 offset-1">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../admin_home.php">Home</a></li>
                    <li class="breadcrumb-item">Settings</li>
                    <li class="breadcrumb-item active" aria-current="page">Add New Admin User</li>

                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">

                <div class="card">
                    <div class="card-header">
                        <h6>To a new admin user to your system fill in the from below.</h6><br>
                    </div>
                    <form id="new_user" method="post" action="../process_files/admin_user_process.php">
                    <div class="card-body">
                        <div class="form-group">
                            <label>First name</label>
                            <input type="text" name="first_name" id="firstname" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Last name</label>
                            <input type="text" name="last_name" id="lastname" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <p><b>Note:</b> The new user must click on the reset password link to set up a password and be able to login.</p>
                    </div>
                    <div class=" card-footer text-center">
                        <input type="button" class="btn btn-sm btn-outline-primary" id="submitbtn" value="Submit"  name="new_admin"><br>
                    </div>
                    </form>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h6>Admin Profiles</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover table-stripped table-responsive-sm">
                            <thead>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Last logged in</th>
                            </thead>
                            <tbody>
                            <?php
                            $sql_admins = "SELECT * FROM admin_user";
                            $result = $db->select($sql_admins);
                            foreach ($result as $detail){
                                echo"
                                    <tr>
                                        <td>{$detail['firstName']} {$detail['lastName']}</td>
                                        
                                        <td>{$detail['email']}</td>
                                        <td>{$detail['last_logged_in']}</td>
                                    </tr>
                                
                                ";                            }


                     ?>
                            </tbody>
                        </table>

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
<script type="text/javascript">
    $(document).ready(function () {

        $("#submitbtn").click(function(){

            var firstName = $("#firstname").val();
            var lastName = $("#lastname").val();
            var email = $("#email").val();

            alert(firstName+lastName+email);
            if(email == '') {
                alert("Check your inputs");
            }else{

                $.ajax({
                    url:'../process_files/admin_user_process.php',
                    method: "POST",
                    dataType: 'json',
                    data: {first_name: firstName, last_name:lastName, email:email},
                    success: function (response) {
                        if (!response.success)
                            alert("Email already exists");
                        else
                           alert("User addded");
                            window.location.reload();

                    }
                })
            }






        })



    })
</script>
</body>
</html>
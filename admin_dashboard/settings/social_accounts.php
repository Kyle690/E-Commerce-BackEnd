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
$sql_social = "SELECT * FROM social_links";
$result = $db->select($sql_social);

if(sizeof($result)== 1){
    $facebook = $result[0]['facebook'];
    $instagram = $result[0]['instagram'];
    $youtube = $result[0]['youtube'];
    $google = $result[0]['google_plus'];
    $date = $result[0]['date_updated'];
    $display_date = date('Y-m-d', strtotime($date));
} else {
    $facebook = '';
    $instagram = '';
    $youtube = '';
    $google = '';
    $date = '';
    $display_date = '';
}

?>
<div class="main-container" id="main-container">
    <div class="pd-ltr-20 customscroll customscroll-10-p height-100-p xs-pd-20-10">
        <div class="row">
            <!-- BreadCrumbs -->
            <div class="col-md-10 offset-1">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../admin_home.php">Home</a></li>
                    <li class="breadcrumb-item">Settings</li>
                    <li class="breadcrumb-item active" aria-current="page">Social accounts Settings</li>

                </ol>
            </div>
        </div>
        <form method="POST" action="../process_files/social_process.php">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <input type="submit" class="btn btn-success btn-sm float-right" name="social_details" value="Update">
                        <h6>Last updated on: <?php echo $display_date ?> </h6>
                    </div>
                </div>
            </div>
        </div><br>
        <div class="row">
            <div class="col-sm-12 text-center">
                <div class="card">
                    <div class="card-body">
                        <h6>Set social Links</h6>
                    </div>
                </div><br>
            </div>
            <div class="col-sm-6">
                <div class="card">
                   <div class="card-body">
                       <div class="form-group">
                           <label class="float-left">Facebook</label>
                           <i style="font-size: 40px; color: #3B5998;" class="fa fa-facebook-official float-right"></i><br>
                           <input type="url" class="form-control" name="facebook" placeholder="www.facebook.com" value="<?php echo $facebook ?>">
                       </div><br>
                       <div class="form-group">
                           <label class="float-left">Instagram</label>
                           <i style="font-size: 40px; color: #fb3958;" class="fa fa-instagram float-right"></i><br>
                           <input type="url" class="form-control" name="instagram" placeholder="www.instagram.com" value="<?php echo $instagram ?>">
                       </div>
                   </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label class="float-left">Youtube</label>
                            <i style="font-size: 40px; color: #ff0000;" class="fa fa-youtube float-right"></i><br>
                            <input type="url" class="form-control" name="youtube" placeholder="www.youtube.com" value="<?php echo $youtube ?>">
                        </div><br>
                        <div class="form-group">
                            <label class="float-left">Google Plus</label>
                            <i style="font-size: 40px; color: #d34836;" class="fa fa-google-plus-official float-right"></i><br>
                            <input type="url" class="form-control" name="google" placeholder="www.plus.google.com/" value="<?php echo $google ?>">
                        </div>

                    </div>
                </div>
            </div>
        </div>
        </form>
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
        $("#ship_update").click( function () {
            /*
            var cost_pKm = $("#cost_per_kilometer").val();
            var free_shipping_distance = $("#free_shipping_distance").val();
            var max_dist = $("#max_distance").val();
            var free_ship_distance = $("#free_shipping_distance").val();
            var goodle_key = $("#google_key").val();


            */

        })

    })

</script>
</body>
</html>

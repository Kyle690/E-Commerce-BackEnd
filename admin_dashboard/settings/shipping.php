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

$sql_ship = "SELECT * FROM shipping_details";
$result = $db->select($sql_ship);
if(sizeof($result) == 1){
    $cp_kilometer = $result[0]['cost_per_km'] ;
    $free_ship = $result[0]['free_ship'];
    $max_dist = $result[0]['max_dist'];
    $api_key = $result[0]['google_api'];
    $date = $result[0]['dat_updated'];
    $display_date =  date('Y-m-d', strtotime($date));
}else{
    $cp_kilometer = '';
    $free_ship = '';
    $max_dist = '';
    $api_key = '';
    $date = '';
    $display_date =  '';
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
                    <li class="breadcrumb-item active" aria-current="page">Shipping Settings</li>

                </ol>
            </div>
        </div>
        <form method="POST" action="../process_files/shipping_process.php">


        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <input type="submit" class="btn btn-success btn-sm float-right"  id="ship_update" name="shipping_details" value="Update">
                        <h6>Last updated on: <?php echo $display_date ?></h6>
                    </div>
                </div>
            </div>
        </div><br>
        <div class="row">
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-body">
                        <h6>Set shipping settings</h6>
                        <p style="font-size: 14px">* <b>Note:</b> Enter only numbers</p>
                        <br>
                        <div class="form-group">
                            <label>Cost per km</label>
                            <input type="number" class="form-control" id="cost_per_kilometer" name="cost_per_kilometer" maxlength="5" value="<?php echo $cp_kilometer ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Free shipping Distance</label>
                            <input type="number" class="form-control" id="free_shipping_distance" name="free_shipping_distance" maxlength="5" value="<?php echo $free_ship ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Max distance</label>
                            <input class="form-control" type="number" id="max_distance" name="max_distance" maxlength="5" value="<?php echo $max_dist ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Google Api Matrix Key</label>
                            <input class="form-control" type="text" id="google_key" name="google_key" value="<?php echo $api_key ?>" required>
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

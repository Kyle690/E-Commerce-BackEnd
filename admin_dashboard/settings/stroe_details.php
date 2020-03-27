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

$sql_details = "SELECT * FROM store_details";
$details = $db->select($sql_details);
if(sizeof($details) == 1) {
    $name = $details[0]['name'];
    $contact = $details[0]['contact_num'];
    $address = $details[0]['address'];
    $street = $details[0]['street'];
    $suburb = $details[0]['suburb'];
    $city = $details[0]['city'];
    $postal = $details[0]['postal'];
    $vat_num = $details[0]['vat_num'];


} else{
    $name = '';
    $contact = '';
    $address = '';
    $street = '';
    $suburb = '';
    $city = '';
    $postal = '';
    $vat_num = '';
}



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
                    <li class="breadcrumb-item active" aria-current="page">Store Details</li>

                </ol>
            </div>
        </div>
        <form method="POST" action="../process_files/store_details_process.php">
        <div class="row">

            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <input type="submit" class="btn btn-success btn-sm float-right" name="store_details" value="Update">
                    </div>
                </div>
            </div>
        </div> <br>
        <div class="row">
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-header">
                        <h6>Store details</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Store Name</label>
                            <input class="form-control" name="store_name" required type="text" maxlength="50" value="<?php echo $name?>">
                        </div>
                        <div class="form-group">
                            <label>Contact No</label>
                            <input class="form-control" name="store_contact_num" required type="tel" maxlength="20" value="<?php echo $contact ?>">
                        </div>
                        <div class="form-group">
                            <label>Vat No</label>
                            <input class="form-control" name="store_vat_num" required type="text" maxlength="50" value="<?php echo $vat_num ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <h6>Address</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Address</label>
                            <input class="form-control" type="text" name="store_address" required placeholder="unit 22, crocker road industrial" value="<?php echo $address?>">
                        </div>
                        <div class="form-group">
                            <label>Street</label>
                            <input class="form-control" type="text" name="store_street" required placeholder="crocker_road" value="<?php echo $street?>">
                        </div>
                        <div class="form-group">
                            <label>Suburb</label>
                            <input class="form-control" type="text" name="store_suburb" required placeholder="germiston_industries" value="<?php echo $suburb?>">
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <input class="form-control" type="text" name="store_city" required placeholder="germiston" value="<?php echo $city?>">
                        </div>
                        <div class="form-group">
                            <label>Postal Code</label>
                            <input class="form-control" type="text" name="postal" placeholder="germiston" value="<?php echo $postal?>">
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
    </form>
    <div class="col-sm-12">
        <div class=" footer-wrap bg-white pd-20 mb-20 border-radius-5 box-shadow">
            <p>Copyright - Vania Pasta 2018</p>
        </div>
    </div>
</div>
<script src="../../vendors/scripts/script.js"></script>
<script src="../../src/plugins/dropzone/src/dropzone.js"></script>
<script type="text/javascript">
    $(document).ready(function () {





    })

</script>
</body>
</html>


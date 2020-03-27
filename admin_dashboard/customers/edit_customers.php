<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/05/04
 * Time: 2:50 PM
 */

include_once ("../inc/functions.php");
secure_session_start();

if( isset($_SESSION ['admin_firstName'])){
    $admin_firstName = $_SESSION['admin_firstName'];
}else{
    header("location:../../index.php");
    //echo "error with cookie";
}
include_once ('../../inc/database.php');
$db = new Database();

if(isset($_POST['customer_id'])) {
    $customer_id = $_POST['customer_id'];
    $sql = "SELECT * FROM customer_details WHERE id = '".$customer_id."'";
    $customer_details = $db->select($sql);
    if(sizeof($customer_details) == 1){
        $first_Name = $customer_details[0]['first_name'];
        $last_Name = $customer_details[0]['last_name'];
        $email = $customer_details[0]['email'];
        $contact_num = $customer_details[0]['contact_num'];
        $accepts_marketing = $customer_details[0]['marketing'];
        $accepts_newsletter = $customer_details[0]['newsletter'];

        $sql_ship = "SELECT * FROM customer_shipping_details WHERE customer_id = '".$customer_id."'";
        $customer_ship_details = $db->select($sql_ship);
        if(sizeof($customer_ship_details) == 1){
            $buildingname = $customer_ship_details[0]['building_name'] ;
            $street = $customer_ship_details[0]['street'] ;
            $suburb =  $customer_ship_details[0]['suburb'] ;
            $city =  $customer_ship_details[0]['city'] ;
            $province =  $customer_ship_details[0]['province'] ;
            $postal =  $customer_ship_details[0]['postal_code'] ;
        }else{
            $buildingname = '' ;
            $street = '' ;
            $suburb =  '';
            $city =  '' ;
            $province = '' ;
            $postal = '' ;
        }

    }else{
        echo "Problem with the db";
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
                    <li class="breadcrumb-item active" aria-current="page">Edit Customer</li>
                </ol>
            </div><br>
        </div>
    </div>
    <div class="container">
        <form method="POST" action="../process_files/customers.php">
        <div class="row">

            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <input type="hidden" name="customer_id" value="<?php echo $customer_id;?>">
                        <button type="button" class="btn btn-outline-secondary btn-sm float-right" id="cancel_addCust">Cancel</button>
                        <input type="submit" class="btn btn-outline-success btn-sm float-right save_btn" value="Save" name="edit_customer">
                    </div><br>
                </div>
            </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h6>Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" class="form-control" name="first_name" value="<?php echo $first_Name; ?>" maxlength="55" placeholder="John" required>
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" class="form-control" name="last_name" maxlength="55" value="<?php echo $last_Name; ?>" placeholder="Smith">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" id="email" value="<?php echo $email; ?>" maxlength="255" placeholder="dj@me.com" required>
                            </div>
                            <div class="form-group">
                                <label>Contact Number</label>
                                <input type="text" class="form-control" name="contact_num" maxlength="15" value="<?php echo $contact_num; ?>" placeholder="0739135274">
                            </div>
                            <div class="form-check" >
                                <input type="checkbox" class="form-check-input" name="accepts_marketing" value="yes" <?php if($accepts_marketing == 'yes'){echo 'checked';} ?> >
                                <label class="form-check-label">Accepts marketing</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="accepts_newsletter" value="yes" <?php if ($accepts_newsletter == 'yes'){echo 'checked';} ?>>
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
                                <input class="form-control" name="ship_building" id="ship_building" value="<?php echo $buildingname; ?>" type="text" maxlength="70">
                            </div>

                            <div class="form-group">
                                <label>Street Address</label>
                                <input class="form-control" name="ship_street" id="ship_street" value="<?php echo $street; ?>" type="text" maxlength="70">
                            </div>
                            <div class="form-group">
                                <label>Suburb</label>
                                <input class="form-control" name="ship_suburb" id="ship_suburb" value="<?php echo $suburb; ?>" type="text" maxlength="70">
                            </div>
                            <div class="form-group">
                                <label>City</label>
                                <input class="form-control" name="ship_city"  id="ship_city" value="<?php echo $city; ?>" type="text" maxlength="70">
                            </div>
                            <div class="form-group">
                                <label>Province</label>
                                <select class="form-control" name="ship_province"  id="ship_province">
                                    <option><?php echo $province; ?></option>
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
                                <input class="form-control" name="ship_postal" id="ship_postal" value="<?php echo $postal; ?>" type="text" maxlength="5">
                                <div id="submitInput">

                                </div>

                            </div>

                        </div>
                    </div><br>
                </div>
        </div>
        </form>
        </div>

    <div class="col-sm-12">
        <div class=" footer-wrap bg-white pd-20 mb-20 border-radius-5 box-shadow">
            <p>Copyright - Vania Pasta 2018</p>
        </div>
    </div>

</div>

<?php }; ?>

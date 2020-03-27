<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/04/27
 * Time: 11:17 AM
 */
$sql = "SELECT * FROM admin_user WHERE firstName = '".$admin_firstName."'";
$result = $db->select($sql);
if(sizeof($result)==0){
    header("location:../../index.php");


}
else if(sizeof($result)>1){
    header("location:../index.php");
}else{
    $first_name = ($result[0]['firstName']);
    $last_name = ($result[0]['lastName']);
    $email = ($result[0]['email']);

}
?>
<!-- Admin Profile Modal-->
<div class="modal fade" id="admin_profile">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h3>Admin Profile</h3>
                </div>
            </div>
            <div class="modal-body">
                <form method="POST" action="../process_files/update_admin_profile.php">
                    <div class="form-group">
                        <label>First Name</label>
                        <input class="form-control" name="admin_first_name" type="text" value="<?php echo $first_name?>" maxlength="55" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input class="form-control" name="admin_last_name" type="text" value="<?php echo $last_name ?>" maxlength="55">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input class="form-control" name="admin_email" type="email" value="<?php echo $email ?>" maxlength="100" required>
                    </div>


            </div>
            <div class="modal-footer">
                <input type="submit" name="admin_update" class="btn btn-sm btn-success">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
            </form>
        </div>

    </div>

</div>
<!-- Top nav Bar -->
<div class="header clearfix">
    <div class="header-right">
        <div class="brand-logo">
                <img height="50px" width="50px" src="../../img/Vanita_Logo.png" alt="logo" class="mobile-logo">
        </div>
        <div class="menu-icon">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="user-info-dropdown">
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    <span class="user-icon"><i class="fa fa-user-o"></i></span>
                    <span class="user-name">Weclome, <?php echo $admin_firstName; ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="" data-toggle="modal" data-target="#admin_profile"><i class="fa fa-user-md" aria-hidden="true"></i> Profile</a>
                    <a class="dropdown-item" href=""><i class="fa fa-question" aria-hidden="true"></i> Help</a>
                    <a class="dropdown-item" href="../process_files/admin_logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Log Out</a>
                </div>
            </div>

        </div>
        <div class="user-notification"><a href="../../../storefront/index.php" target="_blank">View Shop</a></div>
    </div>
</div>

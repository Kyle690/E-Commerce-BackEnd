<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/19
 * Time: 9:41 PM
 */
include "../inc/functions.php";
secure_session_start();
include_once '../../inc/database.php';
$db = new Database();
if(isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $date = date("Y-m-d H-i-s");
    if(isset($_POST['social_details'])){
        $facebook = mysqli_real_escape_string($con, $_POST['facebook']);
        $instagram = mysqli_real_escape_string($con, $_POST['instagram']);
        $youtube = mysqli_real_escape_string($con, $_POST['youtube']);
        $google_plus = mysqli_real_escape_string($con, $_POST['google']);

        $sql_check = "SELECT * FROM social_links";
        $result = $db->select($sql_check);
        if(sizeof($result) == 0){
            $sql_insert = "INSERT INTO social_links (facebook, instagram, youtube, google_plus, admin_id, date_updated) VALUES (?,?,?,?,?,?)";
            if($stmt= $mysqli->prepare($sql_insert)){
                $stmt->bind_param('ssssis', $facebook, $instagram, $youtube, $google_plus, $admin_id, $date);

                if($stmt->execute()){
                    header('location: ../settings/social_accounts.php');
                }else{
                    // failer with the execute of stmt
                    echo "error with executing sql ";
                }
            }else{
                // failure with preparing the statment
                echo "error with preparing the statement";
            }

        }else if (sizeof($result) == 1){
            $sql_update = "UPDATE social_links SET facebook=?, instagram=?, youtube=?, google_plus=?, admin_id=?, date_updated=? WHERE id ='".$result[0]['id']."'";
            if($stmt= $mysqli->prepare($sql_update)){
                $stmt->bind_param('ssssis', $facebook, $instagram, $youtube, $google_plus, $admin_id, $date);

                if($stmt->execute()){
                    header('location: ../settings/social_accounts.php');
                }else{
                    // failer with the execute of stmt
                    echo "error with executing sql ";
                }
            }else{
                // failure with preparing the statment
                echo "error with preparing the statement";
            }
        }
    }

    $stmt->close();

// Close connection
    $mysqli->close();
}else{
    header('Location: ../../index.php');

}
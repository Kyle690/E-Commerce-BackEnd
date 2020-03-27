<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/05
 * Time: 9:09 PM
 */
include_once ("../inc/functions.php");
secure_session_start();
include '../../inc/database.php';
$db = new Database();
if(isset($_SESSION['admin_id'])) {
    if (isset($_FILES['attachments'])) {
        $msg = "";
        $fileName = $_FILES['attachments']['name'][0];
        $targetFile = "../../../storefront/img/product_img/" . basename($_FILES['attachments']['name'][0]);
        if (file_exists($targetFile)) {
            $msg = array("status" => 0, "msg" => "File already exists!");
        } else if (move_uploaded_file($_FILES['attachments']['tmp_name'][0], $targetFile)) {

            $date_created = date("Y-m-d H-i-sa");
            $sql = "INSERT INTO prod_img_gal (file_name, date_created) VALUES (?,?)";
            if($stmt= $mysqli->prepare($sql)){
                $stmt->bind_param('ss', $fileName_param,$date_created_param);
                $fileName_param = $fileName;
                $date_created_param = $date_created;
                if($stmt->execute()){
                    $msg = array("status" => 1, "msg" => "File Has Been Uploaded", "path" => $targetFile, "fileName" => $fileName);
                }else{
                    // failer with the execute of stmt
                    $msg = "Error with the statement";
                }
            }else{
                // failure with preparing the statement
                $msg =  "error with preparing the statement";
            }

        }
        exit(json_encode($msg));
    }
}
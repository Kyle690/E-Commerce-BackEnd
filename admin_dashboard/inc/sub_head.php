<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/04/27
 * Time: 11:16 AM
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



$sql = "SELECT * FROM admin_user WHERE firstName = '".$admin_firstName."'";
$result = $db->select($sql);
if(sizeof($result)==0){
    echo "Error with sql of admin details";
   //exit( header("location:../../index.php"));


}
else if(sizeof($result)>1){
    header("location:../index.php");
}else{
    $first_name = ($result[0]['firstName']);
    $last_name = ($result[0]['lastName']);
    $email = ($result[0]['email']);

}

?>
<!DOCTYPE html>
<html>
<head>

    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>Vanita Pasta - Admin</title>
    <meta http-equiv=“Pragma” content=”no-cache”>
    <meta http-equiv=“Expires” content=”-1″>
    <meta http-equiv=“CACHE-CONTROL” content=”NO-CACHE”>


    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- plugins -->
    <link rel="stylesheet" type="text/css" href="../../src/plugins/dropzone/src/dropzone.css">
    <!-- switchery css -->
    <link rel="stylesheet" type="text/css" href="../../src/plugins/switchery/dist/switchery.css">
    <!-- bootstrap-tagsinput css -->
    <link rel="stylesheet" type="text/css" href="../../src/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
    <!-- bootstrap-touchspin css -->
    <link rel="stylesheet" type="text/css" href="../../src/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css">
    <link rel="stylesheet" type="text/css" href="../../src/plugins/datatables/media/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="../../src/plugins/datatables/media/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="../../src/plugins/datatables/media/css/responsive.dataTables.css">
    <link rel="stylesheet" type="text/css" href="../inc/richtext.min.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="../../vendors/styles/style.css">
    <link rel="stylesheet" href="../../src/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
    <link rel="stylesheet" href="../../inc/page_loader.css">


</head>

<body>
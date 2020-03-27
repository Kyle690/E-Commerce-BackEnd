<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/01
 * Time: 1:20 PM
 */
include_once ("../inc/functions.php");
secure_session_start();

if( isset( $_SESSION ['admin_firstName'])){
    session_destroy();
    header("location: ../../index.php");
}else{
    header("location:../../index.php");
    //echo "error with cookie";
}
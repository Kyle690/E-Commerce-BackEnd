<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/05/31
 * Time: 4:01 PM
 */

function secure_session_start(){

    $lifetime = 3600;
    $path = "/";
    //$domain = "localhost";
    $secure = FALSE;
    $httponly = TRUE;
    session_set_cookie_params($lifetime, $path, " ", $secure, $httponly);

    session_start();
    //echo"session start function running";

}?>

<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/05/30
 * Time: 10:22 PM
 */
include "admin_dashboard/inc/functions.php";
secure_session_start();
include_once "inc/database.php";
$db = new Database();

$lastLoggedIn= date("Y-m-d H-i-sa");


if(isset($_POST['email'])){

  if(isset($_POST['password'])){
      $email = mysqli_real_escape_string($con, $_POST['email']);
      $password = mysqli_real_escape_string($con,$_POST['password']);

      $sql = "SELECT * FROM admin_user WHERE email = '".$email."'";
      $result = $db->select($sql);
      if(sizeof($result)==0){
          echo "no user with that email address";
      }
      else if(sizeof($result)>1){
          echo "issue with the system";
      }
      else if (sizeof($result) == 1) {
          if(password_verify($password ,$result[0]["password"])===TRUE){

              $_SESSION['admin_firstName'] = ($result[0]["firstName"]);
              $_SESSION['admin_id'] = ($result[0]["id"]);
              $admin_id = $result[0]['id'];

              $sql2 = "UPDATE admin_user SET last_logged_in = ? WHERE id = '".$admin_id."'";
              if($stmt = $mysqli->prepare($sql2)){
                  $stmt->bind_param('s', $lastLoggedIn_param);
                  $lastLoggedIn_param = $lastLoggedIn;
                  if($stmt->execute()){
                      // sql was successfull
                      echo "true";
                      }else{
                          // failure with the execute of stmt
                          echo "Something went wrong with the Sql entry!";
                      }
                  }else{
                      // failure with preparing the statement
                      echo "Something went wrong with preparing the statement for the date! ";
                  }
              }




          }
          else{
              echo "login failed";
          }
      }


}
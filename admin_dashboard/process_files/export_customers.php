<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/20
 * Time: 4:47 PM
 */
include "../inc/functions.php";
secure_session_start();
include_once '../../inc/database.php';
$db = new Database();
if(isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];

    if(isset($_POST['export_customer'])){
        $output = '';
        $sql_export = "SELECT * FROM customer_details WHERE marketing ='yes'";
        $result = $db->select($sql_export);

        $output .="
        <table class='table'>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>email</th>
            <th>Contact Number</th>
        </tr>";

        foreach ($result as $customer){

            $output .="
            <tr>
                <td>{$customer['first_name']}</td>
                <td>{$customer['last_name']}</td>
                <td>{$customer['email']}</td>
                <td>{$customer['contact_num']}</td>
            </tr>
            
            ";

        }

        $output .="
        </table>
        ";
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename= marketing_customers.xls');
        echo "Order Downloaded";
    }







}else{
    header('Location: ../../../../index.php');
}
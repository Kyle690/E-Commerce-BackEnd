<?php
/**

 * User: kyle
 * Date: 2018/04/25
 * Time: 8:59 PM
 */
include_once ("inc/functions.php");
secure_session_start();

if( isset($_SESSION ['admin_firstName'])){
    $admin_firstName = $_SESSION['admin_firstName'];
}else{
    header("location:../index.php");
    //echo "error with cookie";
}
include_once ('../inc/database.php');
$db = new Database();

// head
    include ("inc/head.php");
    // Top Nav bar
    include("inc/top_nav_bar.php");
    //side_nav
    include ('inc/side_nav_bar.php');
$todays_date = date("Y-m-d");
$Return_cust_date = date("Y-m-d ",  strtotime("-1 months"));

    $sql_orders = "SELECT * FROM orders WHERE date_created >= '".$todays_date."' ";
    $orders = $db->select($sql_orders);
    $todays_orders = sizeof($orders);
    if($todays_orders != 0){
        $value = array();
        foreach ($orders as $order){
            $sql_value = "SELECT final_total FROM order_totals WHERE order_number = '".$order['order_num']."'";
            $values = $db->select($sql_value);
            array_push($value, $values[0]['final_total']);
        }
        $total = array_sum($value);

    }else{
        $total = 0;
    }

    $sql_cust_returning = "SELECT * FROM customer_details WHERE date_created <= '".$Return_cust_date."' ";
    $new_cust = $db->select($sql_cust_returning);
    $new_cust_display = sizeof($new_cust);

    $sql_rtn_cust = "SELECT * FROM customer_details WHERE date_last_logged_in <='".$Return_cust_date."'";
    $rtn_cust = $db->select($sql_rtn_cust);
    $rtn_cust_display = sizeof($rtn_cust);

?>
    <!-- Main Container -->
    <div class="main-container" id="main-container">
        <div class="pd-ltr-20 customscroll customscroll-10-p height-100-p xs-pd-20-10">
           <!-- 4stats cards-->
            <div class="row">
                <!-- BreadCrumbs -->
                <div class="col-md-10 offset-1">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"></li>
                        <li class="breadcrumb-item active" aria-current="page">Home</li>
                    </ol>
                </div>

                    <!--Total Orders  -->
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-30">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <div class="card-title">
                                    <div class="text-lg-left">
                                        <h5 class="text-white">Total Orders</h5>
                                        <h6 class="text-white">Today</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="project-info-left">
                                    <div class="icon box-shadow bg-primary text-white">
                                        <i class="fa fa-shopping-cart"></i>
                                    </div>
                                </div>
                                <div class="project-info-right">
                                    <h1 class="text-blue"><?php echo $todays_orders ?></h1>
                                </div>
                            </div>
                            <div class="card-footer bg-primary">
                                    <a href="orders/all_orders.php" class="text-white">View More...</a>
                            </div>
                        </div>
                    </div>
                    <!-- Total sales -->
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-30">
                        <div class="card">
                            <div class="card-header bg-warning">
                                <div class="card-title">
                                    <div class="text-lg-left text-white">
                                        <h5 class="text-white">Total Sales</h5>
                                        <h6 class="text-white">Today</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="project-info-left">
                                    <div class="icon box-shadow bg-warning text-white">
                                        <i class="fa fa-credit-card-alt"></i>
                                    </div>
                                </div>
                                <div class="project-info-right">
                                    <h4 class="text-yellow">R <?php echo $total?></h4>
                                </div>
                            </div>
                            <div class="card-footer bg-warning">
                                <a href="orders/all_orders.php" class="text-white">View More...</a>
                            </div>
                        </div>
                    </div>
                    <!-- Total Customers -->
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-30">
                        <div class="card">
                            <div class="card-header bg-light-purple">
                                <div class="card-title">
                                    <div class="text-lg-left text-white">
                                        <h5 class="text-white">New Customers</h5>
                                        <h6 class="text-white">Last 30 days</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="project-info-left">
                                    <div class="icon box-shadow bg-light-purple text-white">
                                        <i class="fa fa-users"></i>
                                    </div>
                                </div>
                                <div class="project-info-right">
                                    <h1 class="text-light-purple"><?php echo $new_cust_display ?></h1>
                                </div>
                            </div>
                            <div class="card-footer bg-light-purple">
                                <a href="customers/customers.php" class="text-white">View More...</a>
                            </div>
                        </div>
                    </div>
                    <!--Returning Customers -->
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-30">
                        <div class="card">
                            <div class="card-header bg-success">
                                <div class="card-title">
                                    <div class="text-lg-left text-white">
                                        <h5 class="text-white">Returning Customers</h5>
                                        <h6 class="text-white"></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="project-info-left">
                                    <div class="icon box-shadow bg-success text-white">
                                        <i class="fa fa-users"></i>
                                    </div>
                                </div>
                                <div class="project-info-right">
                                    <h1 class="text-green"><?php echo $rtn_cust_display ?></h1>
                                </div>
                            </div>
                            <div class="card-footer bg-success">
                                <a href="customers/customers.php"  class="text-white">View More...</a>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Orders of the day-->
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">
                                    <h5>Todays Orders</h5>
                                    <a href="orders/all_orders.php" class="btn btn-sm btn-primary float-md-right" data-toggle="tooltip" data-placement="top" title="View Orders">
                                        <span><i class="fa fa-eye"></i></span>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table data-table stripe hover nowrap ">
                                    <thead>
                                    <tr>

                                        <th width="7%" class="table-plus datatable-nosort">Order Num</th>
                                        <th width="23%">Date</th>
                                        <th width="20%">Customer</th>
                                        <th width="10%">Payment Status</th>
                                        <th width="20%">Fulfillment Status</th>
                                        <th width="20%">Value</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php

                                    $sql_allOrders = "SELECT * FROM orders WHERE date_created >= '".$todays_date."' ORDER BY order_num DESC";
                                    $orders = $db->select($sql_allOrders);
                                    if(sizeof($orders) == 0){

                                    }else{
                                        foreach($orders as $order){
                                            echo"
                                        <tr>
                                            
                                            <td class='form-check table-plus'>{$order['order_num']}</td>
                                            <td>{$order['date_created']}</td>
                                            <td>{$order['first_name']} {$order['last_name']}</td>
                                            
                                            "; if($order['payment_status'] == 'paid'){
                                                echo"<td><span class='badge badge-pill badge-success'>Paid</span></td>";
                                            } elseif ($order['payment_status'] == 'Waiting Payment'){
                                                echo"<td><span class='badge badge-pill badge-warning'>Waiting Payment</span></td>";
                                            }elseif($order['payment_status'] == 'Unpaid'){
                                                echo"<td><span class='badge badge-pill badge-danger'>UnPaid</span></td>";
                                            }elseif ($order['payment_status']== 'Waiting Payment Payf'){
                                                echo "<td><span class='badge badge-pill badge-info'>Waiting for Payfast</span></td>";
                                            }elseif($order['payment_status'] == 'paid_pf'){
                                                echo "<td><span class='badge badge-pill badge-primary'>Paid Payfast</span></td>";
                                            }

                                            $sql_status = "SELECT status FROM order_status WHERE order_id = {$order['id']}";
                                            $status_check = $db->select($sql_status);
                                            $status = $status_check[0]['status'];

                                            if($status == "Received"){
                                                echo"<td><span class='badge badge-pill badge-secondary'>Received</span></td>";
                                            }elseif ($status == "processing"){
                                                echo"<td><span class='badge badge-pill badge-primary'>Processing</span></td>";
                                            }elseif ($status == "delivering"){
                                                echo"<td><span class='badge badge-pill badge-warning'>Delivering</span></td>";
                                            }elseif($status == "delivered"){
                                                echo"<td><span class='badge badge-pill badge-success'>Delivered</span></td>";
                                            }else if($status == "returned"){
                                                echo"<td><span class='badge badge-pill badge-danger'>Returned</span></td>";
                                            }
                                            $sql_final_total = "SELECT final_total FROM order_totals WHERE order_id ={$order['id']} ";
                                            $total_result = $db->select($sql_final_total);

                                            echo"<td>R ".$total_result[0]['final_total']."</td>";
                                            echo"
                                        </tr>
                                        ";
                                        }
                                    }





                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>






             <div class="footer-wrap bg-white pd-20 mb-20 border-radius-5 box-shadow">
                 <p>Copyright - Vania Pasta 2018</p>
             </div>

<script src="../vendors/scripts/script.js"></script>
<script src="../src/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="../src/plugins/datatables/media/js/dataTables.bootstrap4.js"></script>
<script src="../src/plugins/datatables/media/js/dataTables.responsive.js"></script>
<script src="../src/plugins/datatables/media/js/responsive.bootstrap4.js"></script>
<!-- buttons for Export datatable -->
<script src="../src/plugins/datatables/media/js/button/dataTables.buttons.js"></script>
<script src="../src/plugins/datatables/media/js/button/buttons.bootstrap4.js"></script>
<script src="../src/plugins/datatables/media/js/button/buttons.print.js"></script>
<script src="../src/plugins/datatables/media/js/button/buttons.html5.js"></script>
<script src="../src/plugins/datatables/media/js/button/buttons.flash.js"></script>
<script src="../src/plugins/datatables/media/js/button/pdfmake.min.js"></script>
<script src="../src/plugins/datatables/media/js/button/vfs_fonts.js"></script>
<script>
    $('document').ready(function(){
        $('.data-table').DataTable({
            scrollCollapse: true,
            autoWidth: false,
            responsive: true,
            columnDefs: [{
                targets: "datatable-nosort",
                orderable: false,
            }],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "language": {
                "info": "_START_-_END_ of _TOTAL_ entries",
                searchPlaceholder: "Search"
            },
        });
    });
</script>
       </body>


</html>
<?php ob_end_flush();   ?>

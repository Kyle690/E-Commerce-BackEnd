<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/04/26
 * Time: 1:11 PM
 */include ("../inc/sub_head.php");
// Top Nav bar
include("../inc/sub_top_nav_bar.php");
//side_nav
include ('../inc/sub_side_nav_bar.php');
?>

<div class="modal fade" id="page_loader">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body ">
                <div class="row">
                    <div class="col-sm-4 offset-4">
                        <div class="loader">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="main-container" id="main-container">
    <div class="pd-ltr-20 customscroll customscroll-10-p height-100-p xs-pd-20-10">
        <div class="row">
            <!-- BreadCrumbs -->
            <div class="col-md-10 offset-1">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../admin_home.php">Home</a></li>
                    <li class="breadcrumb-item"><a>Orders</a></li>
                    <li class="breadcrumb-item active" aria-current="page">All orders</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="alert_con">

                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="btn-group" role="group" aria-label="">
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="all_orders_btn">All</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="open_orders_btn">Open</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="pComplete_orders_btn">Partially Complete</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="fulfilled_order_btn">Fulfilled</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="return_rder_btn">Returned</button>
                        </div>
                        <div class="dropdown float-right d-none" id="bulk_orders">
                            <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="update_status_bulk" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Update Fulfillment Status
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#" id="processing_bulk">Processing</a>
                                <a class="dropdown-item" href="#" id="delivering_bulk">Delivering</a>
                                <a class="dropdown-item" href="#" id="delivered_bulk">Delivered</a>

                            </div>
                        </div>
                    </div>
                    <!-- All Orders-->
                    <div class="" id="all_orders">
                        <div class="card-body">
                            <table class=" data-table table-sm stripe hover nowrap">
                                <thead>
                                <tr>
                                    <th width="5%" class="table-plus datatable-nosort"><div class="form-check"><input class="form-check-input" type="hidden" id="#"></div></th>
                                    <th width="2%">Order Num</th>
                                    <th width="23%">Date</th>
                                    <th width="20%">Customer</th>
                                    <th width="10%">Payment Status</th>
                                    <th width="20%">Fulfillment Status</th>
                                    <th width="20%">Value</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sql_allOrders = "SELECT * FROM orders ORDER BY order_num DESC";
                                $orders = $db->select($sql_allOrders);
                                if(sizeof($orders) == 0){
                                    echo "<tr></tr>";
                                }else{
                                    foreach($orders as $order){
                                        echo"
                                        <tr>
                                            <td><div class='form-check table-plus'><input class='form-check-input  text-center' type='hidden'></div></td>
                                            <td><a href='#' class='order_num' data-orderId='{$order['order_num']}'>{$order['order_num']}</a></td>
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
                    <!-- Open Orders-->
                    <div id="open_orders" class="d-none">
                        <div class="card-body">
                            <table class="data-table-export   stripe hover nowrap">
                                <thead>
                                <tr>
                                    <th width="5%" class="table-plus datatable-nosort"><div class="form-check"><input class="form-check-input" type="checkbox" id="select_all_o"></div></th>
                                    <th width="5%">Order Num</th>
                                    <th width="20%">Date</th>
                                    <th width="30%">Customer</th>
                                    <th width="10%">Payment Status</th>
                                    <th width="10%">Fulfillment Status</th>
                                    <th width="20%">Value</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $sql_open_orders = "SELECT * FROM order_status WHERE status = 'Received' ORDER BY order_num DESC";
                                    $open_orders = $db->select($sql_open_orders);
                                    if(sizeof($open_orders)>0){
                                        foreach ($open_orders as $open_order){
                                            $sql_open_order_info = "SELECT * FROM orders WHERE order_num = '{$open_order['order_num']}'";
                                            $order_info_open = $db->select($sql_open_order_info);

                                            foreach ($order_info_open as $order_info){
                                                echo"
                                            <tr>
                                            <td><div class='form-check table-plus'><input class='form-check-input item_check text-center' type='checkbox'></div></td>
                                            <td><a href='#' class='order_num' data-orderId='{$order_info['order_num']}'>{$order_info['order_num']}</a></td>
                                            <td>{$order_info['date_created']}</td>
                                            <td>{$order_info['first_name']} {$order_info['last_name']}</td>";
                                                if($order_info['payment_status'] == 'paid'){
                                                    echo"<td><span class='badge badge-pill badge-success'>Paid</span></td>";
                                                } elseif ($order_info['payment_status'] == 'Waiting Payment'){
                                                    echo"<td><span class='badge badge-pill badge-warning'>Waiting Payment</span></td>";
                                                } elseif ($order_info['payment_status']== 'Waiting Payment Payf'){
                                                    echo "<td><span class='badge badge-pill badge-info'>Waiting for Payfast</span></td>";
                                                }elseif($order_info['payment_status'] == 'paid_pf'){
                                                    echo "<td><span class='badge badge-pill badge-primary'>Paid Payfast</span></td>";
                                                }
                                                else{
                                                    echo"<td><span class='badge badge-pill badge-danger'>UnPaid</span></td>";
                                                }
                                                echo"
                                            <td><span class='badge badge-pill badge-secondary'>Received</span></td>
                                      
                                            ";$sql_final_total = "SELECT final_total FROM order_totals WHERE order_id ={$order_info['id']} ";
                                                $total_result = $db->select($sql_final_total);

                                                echo"<td>R ".$total_result[0]['final_total']."</td></tr>";

                                            }
                                        }
                                    }
                                ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Partially Complete Orders-->
                    <div class="d-none" id="p_complete_orders">
                        <div class="card-body">
                            <table class="data-table-export  stripe hover nowrap">
                                <thead>
                                <tr>
                                    <th width="5%" class="table-plus datatable-nosort"><div class="form-check"><input class="form-check-input" type="checkbox" id="select_all_pc"></div></th>
                                    <th width="5%">Order Num</th>
                                    <th width="20%">Date</th>
                                    <th width="30%">Customer</th>
                                    <th width="10%">Payment Status</th>
                                    <th width="10%">Fulfillment Status</th>
                                    <th width="20%">Value</th>
                                </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $sql_part_orders = "SELECT * FROM order_status WHERE status = 'processing' OR status = 'delivering' ORDER BY order_num DESC ";
                                    $part_orders = $db->select($sql_part_orders);
                                    if(sizeof($part_orders)>0){
                                        foreach ($part_orders as $part_order){
                                            $sql_part_order_info = "SELECT * FROM orders WHERE order_num = '{$part_order['order_num']}'";
                                            $order_info_part = $db->select($sql_part_order_info);

                                            foreach ($order_info_part as $order_part_info){
                                                echo"
                                            <tr>
                                            <td><div class='form-check table-plus'><input class='form-check-input item_check text-center' type='checkbox'></div></td>
                                            <td><a href='#' class='order_num' data-orderId='{$order_part_info['order_num']}'>{$order_part_info['order_num']}</a></td>
                                            <td>{$order_part_info['date_created']}</td>
                                            <td>{$order_part_info['first_name']} {$order_part_info['last_name']}</td>";
                                                if($order_part_info['payment_status'] == 'paid'){
                                                    echo"<td><span class='badge badge-pill badge-success'>Paid</span></td>";
                                                } elseif ($order_part_info['payment_status'] == 'Waiting Payment'){
                                                    echo"<td><span class='badge badge-pill badge-warning'>Waiting Payment</span></td>";
                                                }elseif ($order_part_info['payment_status']== 'Waiting Payment Payf'){
                                                    echo "<td><span class='badge badge-pill badge-info'>Waiting for Payfast</span></td>";
                                                }elseif($order_part_info['payment_status'] == 'paid_pf'){
                                                    echo "<td><span class='badge badge-pill badge-primary'>Paid Payfast</span></td>";
                                                }
                                                else{
                                                    echo"<td><span class='badge badge-pill badge-danger'>UnPaid</span></td>";
                                                }

                                            $sql_status = "SELECT status FROM order_status WHERE order_id = {$order_part_info['id']}";
                                            $status_check = $db->select($sql_status);
                                            $status = $status_check[0]['status'];

                                            if($status == "Received"){
                                                echo"<td><span class='badge badge-pill badge-secondary'>Received</span></td>";
                                            }else if ($status == "processing"){
                                                echo"<td><span class='badge badge-pill badge-primary'>Processing</span></td>";
                                            }else if ($status == "delivering"){
                                                echo"<td><span class='badge badge-pill badge-warning'>Delivering</span></td>";
                                            }else if($status == "delivered"){
                                                echo"<td><span class='badge badge-pill badge-success'>Delivered</span></td>";
                                            }else if($status == "returned"){
                                                echo"<td><span class='badge badge-pill badge-Danger'>Returned</span></td>";
                                            }
                                      
                                            $sql_final_total = "SELECT final_total FROM order_totals WHERE order_id ={$order_part_info['id']} ";
                                                $total_result = $db->select($sql_final_total);

                                                echo"<td>R ".$total_result[0]['final_total']."</td></tr>";

                                            }
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <!-- Fulfilled Order -->
                    <div class="d-none" id="fulfilled_orders">
                        <div class="card-body">
                            <table class="data-table-export stripe hover nowrap" >
                                <thead>
                                <tr>
                                    <th width="5%" class="table-plus datatable-nosort"><div class="form-check"><input class="form-check-input" type="checkbox" id="select_all_f"></div></th>
                                    <th width="5%">Order Num</th>
                                    <th width="20%">Date</th>
                                    <th width="30%">Customer</th>
                                    <th width="10%">Payment Status</th>
                                    <th width="10%">Fulfillment Status</th>
                                    <th width="20%">Value</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sql_del_orders = "SELECT * FROM order_status WHERE status = 'delivered' ORDER BY order_num DESC";
                                $del_orders = $db->select($sql_del_orders);
                                if(sizeof($del_orders)>0){
                                    foreach ($del_orders as $del_order){
                                        $sql_del_order_info = "SELECT * FROM orders WHERE order_num = '{$del_order['order_num']}'";
                                        $order_info_del = $db->select($sql_del_order_info);

                                        foreach ($order_info_del as $order_del_info){
                                            echo"
                                            <tr>
                                            <td><div class='form-check table-plus'><input class='form-check-input item_check text-center' type='checkbox'></div></td>
                                            <td><a href='#' class='order_num' data-orderId='{$order_del_info['order_num']}'>{$order_del_info['order_num']}</a></td>
                                            <td>{$order_del_info['date_created']}</td>
                                            <td>{$order_del_info['first_name']} {$order_del_info['last_name']}</td>";
                                            if($order_del_info['payment_status'] == 'paid'){
                                                echo"<td><span class='badge badge-pill badge-success'>Paid</span></td>";
                                            } elseif ($order_del_info['payment_status'] == 'Waiting Payment'){
                                                echo"<td><span class='badge badge-pill badge-warning'>Waiting Payment</span></td>";
                                            }elseif ($order_del_info['payment_status']== 'Waiting Payment Payf'){
                                                echo "<td><span class='badge badge-pill badge-info'>Waiting for Payfast</span></td>";
                                            }else if($order_del_info['payment_status'] == 'unpaid'){
                                                echo"<td><span class='badge badge-pill badge-danger'>UnPaid</span></td>";
                                            }elseif($order_del_info['payment_status'] == 'paid_pf'){
                                                echo "<td><span class='badge badge-pill badge-primary'>Paid Payfast</span></td>";
                                            }
                                            echo"
                                            <td><span class='badge badge-pill badge-success'>Delivered</span></td>
                                      
                                            ";$sql_final_total = "SELECT final_total FROM order_totals WHERE order_id ={$order_part_info['id']} ";
                                            $total_result = $db->select($sql_final_total);

                                            echo"<td>R ".$total_result[0]['final_total']."</td></tr>";

                                        }
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Returned Orders -->
                    <div class="d-none" id="returned_orders">
                        <div class="card-body">
                            <table class="data-table-export stripe hover nowrap" >
                                <thead>
                                <tr>
                                    <th width="5%" class="table-plus datatable-nosort"><div class="form-check"><input class="form-check-input" type="hidden" id="select_all_f"></div></th>
                                    <th width="5%">Order Num</th>
                                    <th width="20%">Date</th>
                                    <th width="30%">Customer</th>
                                    <th width="10%">Payment Status</th>
                                    <th width="10%">Fulfillment Status</th>
                                    <th width="20%">Value</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sql_ret_orders = "SELECT * FROM order_status WHERE status = 'returned' ORDER BY order_num DESC";
                                $re_orders = $db->select($sql_ret_orders);
                                if(sizeof($re_orders)>0){
                                    foreach ($re_orders as $re_order){
                                        $sql_re_order_info = "SELECT * FROM orders WHERE order_num = '{$re_order['order_num']}'";
                                        $order_info_re = $db->select($sql_re_order_info);

                                        foreach ($order_info_re as $order_re_info){
                                            echo"
                                            <tr>
                                            <td><div class='form-check table-plus'><input class='form-check-input item_check text-center' type='hidden'></div></td>
                                            <td><a href='#' class='order_num' data-orderId='{$order_re_info['order_num']}'>{$order_re_info['order_num']}</a></td>
                                            <td>{$order_re_info['date_created']}</td>
                                            <td>{$order_re_info['first_name']} {$order_re_info['last_name']}</td>";
                                            if($order_re_info['payment_status'] == 'paid'){
                                                echo"<td><span class='badge badge-pill badge-success'>Paid</span></td>";
                                            } elseif ($order_re_info['payment_status'] == 'Waiting Payment'){
                                                echo"<td><span class='badge badge-pill badge-warning'>Waiting Payment</span></td>";
                                            }elseif ($order_re_info['payment_status']== 'Waiting Payment Payf'){
                                                echo "<td><span class='badge badge-pill badge-info'>Waiting for Payfast</span></td>";
                                            }else if($order_re_info['payment_status'] == 'unpaid'){
                                                echo"<td><span class='badge badge-pill badge-danger'>UnPaid</span></td>";
                                            }elseif($order_re_info['payment_status'] == 'paid_pf'){
                                                echo "<td><span class='badge badge-pill badge-primary'>Paid Payfast</span></td>";
                                            }
                                            echo"
                                            <td><span class='badge badge-pill badge-danger'>Returned</span></td>
                                      
                                            ";$sql_final_total_re = "SELECT final_total FROM order_totals WHERE order_id ={$order_re_info['id']} ";
                                            $total_result_re = $db->select($sql_final_total_re);

                                            echo"<td>R ".$total_result_re[0]['final_total']."</td></tr>";

                                        }
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
    <div class="col-sm-12">
        <div class=" footer-wrap bg-white pd-20 mb-20 border-radius-5 box-shadow">
            <p>Copyright - Vania Pasta 2018</p>
        </div>
    </div>
</div>
<script src="../../vendors/scripts/script.js"></script>
<script src="../../src/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="../../src/plugins/datatables/media/js/dataTables.bootstrap4.js"></script>
<script src="../../src/plugins/datatables/media/js/dataTables.responsive.js"></script>
<script src="../../src/plugins/datatables/media/js/responsive.bootstrap4.js"></script>
<!-- buttons for Export datatable -->
<script src="../../src/plugins/datatables/media/js/button/dataTables.buttons.js"></script>
<script src="../../src/plugins/datatables/media/js/button/buttons.bootstrap4.js"></script>
<script src="../../src/plugins/datatables/media/js/button/buttons.print.js"></script>
<script src="../../src/plugins/datatables/media/js/button/buttons.html5.js"></script>
<script src="../../src/plugins/datatables/media/js/button/buttons.flash.js"></script>
<script src="../../src/plugins/datatables/media/js/button/pdfmake.min.js"></script>
<script src="../../src/plugins/datatables/media/js/button/vfs_fonts.js"></script>
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
            }
        });
        $('.data-table-export').DataTable({
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
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'pdf', 'print'
            ]
        });
        var table = $('.select-row').DataTable();
        $('.select-row tbody').on('click', 'tr', function () {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            }
            else {
                table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });
        var multipletable = $('.multiple-select-row').DataTable();
        $('.multiple-select-row tbody').on('click', 'tr', function () {
            $(this).toggleClass('selected');
        });
        // Tabs to open and close tables
        $(function () {
            $('#all_orders_btn').click(function () {
                $('#open_orders').addClass('d-none');
                $('#p_complete_orders').addClass('d-none');
                $('#fulfilled_orders').addClass('d-none');
                $('#returned_orders').addClass('d-none');
                $("#bulk_orders").addClass("d-none");
                $('#all_orders').removeClass('d-none');

                $('input:checkbox').prop('checked',false);


            });
            $('#open_orders_btn').click(function () {
                $('#all_orders').addClass('d-none');
                $('#p_complete_orders').addClass('d-none');
                $('#fulfilled_orders').addClass('d-none');
                $('#returned_orders').addClass('d-none');
                $('#open_orders').removeClass('d-none');
                $("#bulk_orders").removeClass("d-none");

                $('input:checkbox').prop('checked',false);
            });
            $('#pComplete_orders_btn').click(function () {
                $('#all_orders').addClass('d-none');
                $('#open_orders').addClass('d-none');
                $('#fulfilled_orders').addClass('d-none');
                $('#returned_orders').addClass('d-none');
                $('#p_complete_orders').removeClass('d-none');
                $("#bulk_orders").removeClass("d-none");

                $('input:checkbox').prop('checked',false);
            });
            $('#fulfilled_order_btn').click(function () {
                $('#all_orders').addClass('d-none');
                $('#open_orders').addClass('d-none');
                $('#p_complete_orders').addClass('d-none');
                $('#returned_orders').addClass('d-none');
                $('#fulfilled_orders').removeClass('d-none');
                $("#bulk_orders").removeClass("d-none");
                $('input:checkbox').prop('checked',false);

            });
            $("#return_rder_btn").click(function () {
                $('#all_orders').addClass('d-none');
                $('#open_orders').addClass('d-none');
                $('#p_complete_orders').addClass('d-none');
                $('#fulfilled_orders').addClass('d-none');
                $("#bulk_orders").addClass("d-none");
                $('#returned_orders').removeClass('d-none');
                $('input:checkbox').prop('checked',false);
            })

        });
        // open order number
        $(function () {
            $(document).on('click', '.order_num', function () {

                var order_num = $(this).text();
                if(order_num != ''){
                    $.ajax({
                        url: "view_order.php",
                        method: "POST",
                        data: {order_num: order_num},
                        success: function (data) {
                            $('#main-container').html(data);
                        }
                    });
                }
            })
        });
        // cancel order number
        $(document).on('click', '#cancel_vo', function () {
            location.reload();
        });
        // check all function for all
        $('#select_all').click(function(e){
            var table= $(e.target).closest('table');
            $('td input:checkbox',table).prop('checked',this.checked);
        });
        // check all function for open
        $('#select_all_o').click(function(e){
            var table= $(e.target).closest('table');
            $('td input:checkbox',table).prop('checked',this.checked);
        });
        // check all function for p complete
        $('#select_all_pc').click(function(e){
            var table= $(e.target).closest('table');
            $('td input:checkbox',table).prop('checked',this.checked);
        });
        // check all function for fufilled
        $('#select_all_f').click(function(e){
            var table= $(e.target).closest('table');
            $('td input:checkbox',table).prop('checked',this.checked);
        });
        //bulk update status
        $(function () {
            $('#processing_bulk').click(function(){
                 var bulk_array = [];

                $("tr:has(:checked)").each(function() {
                    var checkedData = $(this).find('td .order_num').text();
                    bulk_array.push(checkedData);
                 });
                if( bulk_array != '' ){
                    $("#page_loader").modal("show");
                    $.ajax({
                        url: "../process_files/bulk_orders_processing.php",
                        method: 'POST',
                        data: {bulk_proces: bulk_array},
                        success: function (data) {
                            $("#page_loader").modal("hide");
                            alert(data);
                            location.reload();
                        }
                    })
                } else{
                    alert("No orders selected !")
                }
            });
            $('#delivering_bulk').click(function () {
                var bulk_array = [];

                $("tr:has(:checked)").each(function() {
                    var checkedData = $(this).find('td .order_num').text();
                    bulk_array.push(checkedData);
                })
                if( bulk_array != '' ){
                    $("#page_loader").modal("show");
                    $.ajax({
                        url: "../process_files/bulk_orders_processing.php",
                        method: 'POST',
                        data: {bulk_delivery: bulk_array},
                        success: function (data) {
                            $("#page_loader").modal("hide");
                            alert(data);
                            location.reload();
                        }
                    })
                } else{
                    alert("No orders selected !")
                }
            });
            $('#delivered_bulk').click(function () {
                var bulk_array = [];

                $("tr:has(:checked)").each(function() {
                    var checkedData = $(this).find('td .order_num').text();
                    bulk_array.push(checkedData);
                })
                if( bulk_array != '' ){
                    $("#page_loader").modal("show");
                    $.ajax({
                        url: "../process_files/bulk_orders_processing.php",
                        method: 'POST',
                        data: {bulk_delivered: bulk_array},
                        success: function (data) {
                            $("#page_loader").modal("hide");
                            alert(data);
                            location.reload();
                        }
                    })
                } else{
                    alert("No orders selected !")
                }
            });
        });


        //update order progress
        $(document).on('click', '.update_btn', function () {
            var order_number = $(".order_num_edit").val();
            var update_val = $(this).data('update');


            if(order_number != ''){

                $("#page_loader").modal("show");
                $.ajax({
                    url: "../process_files/update_order.php",
                    method: "POST",
                    data: {order_num: order_number, order_action: update_val },
                    success: function (data) {
                        $("#page_loader").modal("hide");
                        alert(data);

                        open_order(order_number);
                    }
                });
            }

        });
        // Update payment
        $(document).on('click', '#payment_update_btn', function(){
            var order_number = $(".order_num_edit").val();
            var payStatus = $("#update_payment").val();
            var action = 'payment_update';
            if(order_number != ''){
                $.ajax({
                    url: "../process_files/update_order.php",
                    method: "POST",
                    data: {order_num: order_number, payStatus: payStatus, order_action_pay: action },
                    success: function (data) {
                        alert(data);

                        open_order(order_number);
                    }
                });
            }
        });
        // Return Order
        $(document).on('click', '#returnBtn', function () {
           var order_num = $('#order_num_return').val();
           var return_msg = $('#rtn_msg').val();

           if(return_msg.length == 0 ){
               alert("Please fill in a reason for returning the order!");
           } else if(return_msg.length <301 ){
                $.ajax({
                    url: "../process_files/return_order.php",
                    method: 'POST',
                    data: {order_num: order_num, rtn_message: return_msg},
                    success: function (data) {
                        alert(data);

                        open_order(order_num);
                    }
                })

            }else {
                alert("Too many characters in message!");
            }
        });

        $(document).on("click", "#delete_order", function () {
           var order_num = $(this).data('id');
            $.ajax({
                url:"../process_files/delete_order.php",
                method:'POST',
                dataType: 'json',
                data:{order_num: order_num},
                success: function(response){
                    if(response.status == 1){
                        alert(response.msg);
                        location.reload();
                    }else if(response.status == 0){
                        alert(response.msg);
                    }
                }
            })
        });

        function open_order(n) {
            var order_num = n;

            $.ajax({
                url: "view_order.php",
                method: "POST",
                data: {order_num: order_num},
                success: function (data) {
                    $('#main-container').html(data);
                }
            });


        }

    });
</script>






</body>

</html>

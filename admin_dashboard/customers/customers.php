<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/04/26
 * Time: 1:11 PM
 */
include ("../inc/sub_head.php");
// Top Nav bar
include("../inc/sub_top_nav_bar.php");
//side_nav
include ('../inc/sub_side_nav_bar.php');
?>
<!-- Main container -->
<div class="main-container" id="main-container">
    <div class="pd-ltr-20 customscroll customscroll-10-p height-100-p xs-pd-20-10">
        <div class="row">
            <!-- BreadCrumbs -->
            <div class="col-md-10 offset-1">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../admin_home.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Customers</li>
                </ol>
            </div>
        </div>
        <!-- Cards -->
        <div class="row">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="btn-group" role="group" aria-label="">
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="all_customer_btn">All</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="new_cust_btn">New Customers</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="return_cust_btn">Returning Customers</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="mark_cust_btn">Accepts Marketing</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="newsletter_customers_btn">Accepts Newsletter</button>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary float-sm-right add_customer">Add Customer</button>
                    </div>
                    <div id="all_customers" class="">
                        <div class="card-body" >
                            <table class="data-table stripe hover nowrap">
                                <thead>
                                <tr>
                                    <th width="70%" class="table-plus ">Name</th>
                                    <th width="15%">Orders</th>
                                    <th width="15%">Value Spent</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                $sql_customer_all = "SELECT * FROM customer_details";
                                $customers = $db->select($sql_customer_all);
                                foreach ($customers as $customer){
                                    echo "
                                    
                                    <tr>
                                    <td><a href='#' data-id = {$customer['id']}  class='customer_name'>{$customer['first_name']} {$customer['last_name']}</a></td>
                                    <td>";
                                    $sql_order_count = "SELECT order_num FROM orders WHERE customer_id = '".$customer['id']."'";
                                    $no_order = $db->select($sql_order_count);
                                    echo sizeof($no_order)." order(s)
                                    </td>
                                    <td align='right'>R ";
                                    $value_of_orders = "SELECT final_total FROM order_totals WHERE customer_id = '".$customer['id']."'";
                                    $value = $db->select($value_of_orders);
                                    $value_array = array();
                                    foreach ($value as $value_amount){
                                        array_push($value_array, $value_amount['final_total']);
                                    }
                                    $display_value = array_sum($value_array);

                                    echo number_format((float)$display_value,2,',', '')."</td>
                                </tr>
                                    
                                    ";
                                }


                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="new_customers" class="d-none" >
                        <div class="card-body" >
                            <table class="data-table stripe hover nowrap">
                                <thead>
                                <tr>
                                    <th width="70%" class="table-plus">Name</th>
                                    <th width="15%">Orders</th>
                                    <th width="15%">Value Spent</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php


                                $newCust_date = date("Y-m-d ",  strtotime("-1 months"));


                                $sql_customer_new = "SELECT * FROM customer_details WHERE date_created >= '".$newCust_date."'";
                                $customer_new = $db->select($sql_customer_new);
                                if(sizeof($customer_new) != 0){
                                    foreach ($customer_new as $customer){
                                        echo "
                                    
                                    <tr>
                                    <td><a href='#' data-id = {$customer['id']}  class='customer_name'>{$customer['first_name']} {$customer['last_name']}</a></td>
                                    <td>";
                                    $sql_order_count_new = "SELECT order_num FROM orders WHERE customer_id = '".$customer['id']."'";
                                    $no_order_new = $db->select($sql_order_count_new);
                                    echo sizeof($no_order_new)." order(s)
                                    </td>
                                    <td align='right'>R ";
                                    $value_of_orders = "SELECT final_total FROM order_totals WHERE customer_id = '".$customer['id']."'";
                                    $value = $db->select($value_of_orders);
                                    $value_array = array();
                                    foreach ($value as $value_amount){
                                        array_push($value_array, $value_amount['final_total']);
                                    }
                                    $display_value = array_sum($value_array);

                                    echo number_format((float)$display_value,2,',', '')."</td>
                                </tr>
                                    
                                    ";
                                    }
                                }else {
                                    echo"<tr>
                                            <td >No new customers this month</td>
                                            <td></td>
                                            <td></td>
                                           
                                        </tr>";
                                }



                                ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="returning_customers" class="d-none" >
                        <div class="card-body" >
                            <table class="data-table stripe hover nowrap">
                                <thead>
                                <tr>
                                    <th width="70%" class="table-plus">Name</th>
                                    <th width="15%">Orders</th>
                                    <th width="15%">Value Spent</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $newCust_date = date("Y-m-d ",  strtotime("-1 months"));
                                $sql_customer_log = "SELECT * FROM customer_details WHERE date_logged_in >= '".$newCust_date."'";
                                $customer_log = $db->select($sql_customer_log);

                                if(sizeof($customer_log) >= 1){
                                    foreach ($customer_log as $customer){
                                        echo "
                                    
                                    <tr>
                                    <td><a href='#' data-id = {$customer['id']}  class='customer_name'>{$customer['first_name']} {$customer['last_name']}</a></td>
                                    <td>";
                                    $sql_order_count_new = "SELECT order_num FROM orders WHERE customer_id = '".$customer['id']."'";
                                    $no_order_new = $db->select($sql_order_count_new);
                                    echo sizeof($no_order_new)." order(s)
                                    </td>
                                    <td align='right'>R ";
                                    $value_of_orders = "SELECT final_total FROM order_totals WHERE customer_id = '".$customer['id']."'";
                                    $value = $db->select($value_of_orders);
                                    $value_array = array();
                                    foreach ($value as $value_amount){
                                        array_push($value_array, $value_amount['final_total']);
                                    }
                                    $display_value = array_sum($value_array);

                                    echo number_format((float)$display_value,2,',', '')."</td>
                                </tr>
                                    
                                    ";
                                    }
                                }else {
                                echo "
                                    <tr>
                                    <td >No returning customers this month</td>
                                    <td></td>
                                    <td></td>

                                </tr>";
                                }

                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="marketing_customers" class="d-none" >
                        <div class="card-body" >

                            <table class="data-table-export stripe hover nowrap">
                                <thead>
                                <tr>
                                    <th width="70%" class="table-plus">Name</th>
                                    <th width="15%">Email</th>
                                    <th width="15%">Contact Number</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                $sql_customer_market = "SELECT * FROM customer_details WHERE marketing = 'yes'";
                                $customer_market = $db->select($sql_customer_market);

                                if(sizeof($customer_market) >= 1){
                                    foreach ($customer_market as $customer){
                                        echo "
                                            <tr>
                                               <td>{$customer['first_name']} {$customer['last_name']}</td>
                                               <td>{$customer['email']}</td>
                                               <td>{$customer['contact_num']}</td>
                                            </tr>
                                        
                                
                                    
                                    ";
                                    }
                                }

                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="newsletter_customers" class="d-none">
                        <div class="card-body" >

                            <table class="data-table-export stripe hover nowrap">
                                <thead>
                                <tr>
                                    <th width="70%" class="table-plus">Name</th>
                                    <th width="15%">Email</th>
                                    <th width="15%">Contact Number</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                $sql_customer_market = "SELECT * FROM customer_details WHERE newsletter = 'yes'";
                                $customer_market = $db->select($sql_customer_market);

                                if(sizeof($customer_market) >= 1){
                                    foreach ($customer_market as $customer){
                                        echo "
                                           <tr>
                                               <td>{$customer['first_name']} {$customer['last_name']}</td>
                                               <td>{$customer['email']}</td>
                                               <td>{$customer['contact_num']}</td>
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
    <div class="col-sm-12">
        <div class=" footer-wrap bg-white pd-20 mb-20 border-radius-5 box-shadow">
            <p>Copyright - Vania Pasta 2018</p>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
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
            },
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

        $(function () {
            $('#all_customer_btn').click(function () {
                $('#new_customers').addClass('d-none');
                $('#returning_customers').addClass('d-none');
                $('#marketing_customers').addClass('d-none');
                $('#newsletter_customers').addClass("d-none");
                $('#all_customers').removeClass('d-none');
                $('input:checkbox').prop('checked',false);
            });
            $('#new_cust_btn').click(function () {
                $('#all_customers').addClass('d-none');
                $('#returning_customers').addClass('d-none');
                $('#marketing_customers').addClass('d-none');
                $('#newsletter_customers').addClass("d-none");
                $('#new_customers').removeClass('d-none');
                $('input:checkbox').prop('checked',false);
            });
            $('#return_cust_btn').click(function () {
                $('#all_customers').addClass('d-none');
                $('#new_customers').addClass('d-none');
                $('#marketing_customers').addClass('d-none');
                $('#newsletter_customers').addClass("d-none");
                $('#returning_customers').removeClass('d-none');
                $('input:checkbox').prop('checked',false);
            });
            $('#mark_cust_btn').click(function () {
                $('#all_customers').addClass('d-none');
                $('#returning_customers').addClass('d-none');
                $('#new_customers').addClass('d-none');
                $('#newsletter_customers').addClass("d-none");
                $('#marketing_customers').removeClass('d-none');
                $('input:checkbox').prop('checked',false);
            });
            $("#newsletter_customers_btn").click(function(){
                $('#all_customers').addClass('d-none');
                $('#returning_customers').addClass('d-none');
                $('#new_customers').addClass('d-none');
                $('#marketing_customers').addClass("d-none");
                $('#newsletter_customers').removeClass('d-none');
                $('input:checkbox').prop('checked',false);
            })
            $('.customer_name').click(function () {
                var customer_id = $(this).data('id');


                $.ajax({
                    url: 'edit_customers.php',
                    method: 'POST',
                    data:{customer_id: customer_id},
                    success: function (data) {
                        $('.main-container').html(data);
                    }
                })
            })
        });

        $(document).on("click", "#export_cust", function () {
            var export_cust = "export";
            $.ajax({
                url: '../process_files/export_customers.php',
                method: 'POST',
                data:{export_customer: export_cust},
                success: function (data) {
                    alert(data);
                }
            })
        });




        $(function () {
            $('.add_customer').click(function () {
                $.ajax({
                    url: "add_customer.php",
                    data: {},
                    success: function (data) {
                        $('.main-container').html(data);
                    }
                })
            })
        });

        $(document).on('click', '#cancel_addCust', function () {
            location.reload();
        });
    });
</script>

</body>

</html>
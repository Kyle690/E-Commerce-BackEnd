<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/04/26
 * Time: 1:12 PM
 */
include ("../inc/sub_head.php");
// Top Nav bar
include("../inc/sub_top_nav_bar.php");
//side_nav
include ('../inc/sub_side_nav_bar.php');
?>
<div class="main-container" id="main-container">
    <div class="pd-ltr-20 customscroll customscroll-10-p height-100-p xs-pd-20-10">
        <div class="row">
            <!-- BreadCrumbs -->
            <div class="col-md-10 offset-1">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../admin_home.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Analytics</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <!-- Total Product Sales -->
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Total Product sales</h4><br>
                    </div>
                    <div class="card-body">

                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th class="table-plus">Product</th>
                                <th>Qty</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $sql_product = "SELECT product_name,COUNT(*) as count FROM order_product GROUP BY product_name ORDER BY count DESC";
                            $result = $db->select($sql_product);
                            for($i=0; $i<sizeof($result); $i++){
                                echo"
                                <tr>
                                    <td>{$result[$i]['product_name']}</td>
                                    <td>{$result[$i]['count']}</td>
                                </tr>    
                                ";
                            }
                            ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <!-- Total Orders-->
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Total Sales</h4><br>
                    </div>
                    <div class="card-body">
                        <h5 class="float-left">Total Sales of all time:</h5>
                        <h5 class="float-right">
                            R <b>


                            <?php
                            $sql_order_status_id = "SELECT order_id FROM order_status WHERE status != 'returned'";
                            $orders_id = $db->select($sql_order_status_id);
                            $total_array = array();
                            $total_shipping = array();
                            foreach ($orders_id as $order_id) {
                                $sql_totals_id = "SELECT * FROM order_totals WHERE order_id = '" . $order_id['order_id'] . "'";
                                $total_values = $db->select($sql_totals_id);
                                foreach ($total_values as $total_value_in) {

                                    array_push($total_array, $total_value_in['final_total']);
                                    array_push($total_shipping, $total_value_in['ship_total']);

                                }
                            }

                            $final_total = array_sum($total_array);
                            echo $final_total;
                            $total_ship = array_sum($total_shipping);
                            ?>

                            </b></h5><br>
                        <h6 class="float-left">Total Shipping:</h6>
                        <h6 class="float-right">R <?php  echo $total_ship?></h6>
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
    });
</script>
</body>

</html>
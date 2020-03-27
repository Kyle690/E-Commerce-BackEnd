<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/04/26
 * Time: 1:21 PM
 */include ("../inc/sub_head.php");
// Top Nav bar
include("../inc/sub_top_nav_bar.php");
//side_nav
include ('../inc/sub_side_nav_bar.php');
$sql_payment = "SELECT * FROM payment_gateway";
$result = $db->select($sql_payment);
if(sizeof($result)== 1){
    $date = $result[0]['date_updated'];
    $date_display =  date('Y-m-d', strtotime($date));
    $id = $result[0]['merchant_id'];
    $key = $result[0]['merchant_key'];
} else{
    $date_display = '';
    $id = '';
    $key ='';
}


?>
<div class="main-container" id="main-container">
    <div class="pd-ltr-20 customscroll customscroll-10-p height-100-p xs-pd-20-10">
        <form method="POST" action="../process_files/payment_gateway_process.php">
        <div class="row">
            <!-- BreadCrumbs -->
            <div class="col-md-10 offset-1">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../admin_home.php">Home</a></li>
                    <li class="breadcrumb-item">Settings</li>
                    <li class="breadcrumb-item active" aria-current="page">Payment Gateway</li>

                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <p class="float-left">Details last updated on: <?php echo $date_display ?></p>
                        <input type="submit" class="btn btn-success btn-sm float-right" name="payment_details" value="Update">
                    </div>
                </div>
            </div>
        </div><br>
        <div class="row">
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="float-left">Update payment gateway</h4>
                        <img src="../../img/PayFast%20Logo%20Colour.png" class="float-right" height="100px" width="100px" >
                        <br><br>
                        <div class="form-group">
                            <label>Merchant ID:</label>
                            <input type="text" class="form-control" name="merchant_id" value="<?php echo $id?>" required>
                        </div>
                        <div class="form-group">
                            <label>Merchant Key:</label>
                            <input type="text" class="form-control" name="merchant_key"  value="<?php echo $key?>" required>
                        </div>
                    </div>
                </div>
            </div>
        </div><br>

        </form>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Transaction history</h6>
                    </div>
                    <div class="card-body">
                        <table class="data-table-export  stripe hover nowrap">
                            <thead>
                                <th class="table-plus" width="10%" >Order No.</th>
                                <th width="20%">Date</th>
                                <th width="10%">Pay Fast Id</th>
                                <th width="20%">Payment Status</th>
                                <th width="10%">Gross</th>
                                <th width="10%">Fee</th>
                                <th width="10%">Net</th>

                            </thead>
                            <tbody>
                                <?php
                                $sql_payFdata = "SELECT * FROM pf_data";
                                $pf_data = $db->select($sql_payFdata);

                                if(sizeof($pf_data) >= 1 ){
                                    foreach ($pf_data as $detail){
                                        echo "
                                            <tr>
                                                <td>{$detail['order_num']}</td>
                                                <td>{$detail['date_created']}</td>
                                                <td>{$detail['pf_payment_id']}</td>
                                                <td>{$detail['payment_status']}</td>
                                                <td align='right'>R {$detail['net_amount']}</td>
                                                <td align='right'>R {$detail['fee']}</td>
                                                <td align='right'>R {$detail['amount']}</td>
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
<script type="text/javascript">
    $(document).ready(function () {
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



    })

</script>
</body>
</html>
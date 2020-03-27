<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/04/26
 * Time: 1:10 PM
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
                    <li class="breadcrumb-item"><a>Catalog</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Inventory</li>
                </ol>
            </div>
            <!-- Inventory table -->
            <form method="post" action="../process_files/invetory_process.php">
            <div class="row">
                <div class="container-fluid">
                    <div class="col-sm-12">
                     <div class="card">
                        <div class="card-header">
                            <input type="submit" class="btn btn-primary btn-md float-right" name="updated_inventory" id="Update_inventory" value="Update Inventory">
                        </div>
                        <div class="card-body">
                            <table class="data-table-export stripe hover ">
                                <thead>
                                <tr>
                                    <th width="10%" class="table-plus datatable-nosort">Img</th>
                                    <th width="10%">Product</th>
                                    <th width="20%">Variant</th>
                                    <th width="10%">SKU</th>
                                    <th width="15%">Quantity in stock</th>
                                    <th width="35%" class="datatable-nosort"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php


                                $sql = "SELECT * FROM products";
                                $products = $db->select($sql);
                                foreach ($products as $product_info){

                                    $sql_sku = "SELECT * FROM product_variants WHERE product_id = '".$product_info['id']."'";
                                    $sku_code = $db->select($sql_sku);
                                    foreach ($sku_code as $sku){
                                        echo "
                                        <tr>
                                        <td class='table-plus'><img src='../../../storefront/img/product_img/{$product_info['main_img']}' class='img-thumbnail'></td>
                                        <td>{$product_info['title']}</td>
                                        <td>{$sku['var_name']}</td>
                                        <td>{$sku['var_sku_code']}</td>
                                        <td>
                                           <input type='hidden' name='sku_id[]' value='{$sku['id']}'>
                                            <input class='form-control col-6 existing_stock' readonly name='product_variant_sku_qty[]' maxlength='3' value='{$sku['var_stock']}'></td>
                                        <td>
                                        <div class='input-group mb-3'>
                                            <div class='input-group-prepend'>
                                                <button type='button' class='btn btn-outline-success btn-sm add_btn'><i class='fa fa-plus'></i></button>
                                            </div>
                                            <input type='number' class='form-control col-6 qty_num ' value='0'>
                                            <div class='input-group-append'>
                                                <button type='button' class='btn btn-outline-danger btn-sm minus_btn'><i class='fa fa-minus'></i></button>
                                                <button type='button' class='btn btn-outline-info btn-sm update_amt' >Set</button>
                                            </div>
                                        </div></td>
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
            </form>
        </div>
        </div>
    </div>
</div>
<div class="col-sm-12">
    <div class=" footer-wrap bg-white pd-20 mb-20 border-radius-5 box-shadow">
        <p>Copyright - Vania Pasta 2018</p>
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
    $('document').ready(function() {
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
            "lengthMenu": [[50, 70, 100, -1], [50, 70, 100, "All"]],
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


            $(document).on("click", ".add_btn", function(){

                var amt_to_add = $(this).closest('tr').find('.qty_num').val();
                var add_to = $(this).closest('tr').find('.existing_stock').val();
                var added_amount = parseInt(amt_to_add) + parseInt(add_to);

                $(this).closest('tr').find('.existing_stock').val(added_amount);

                $(this).closest('tr').find('.qty_num').val(0);
            });


            $(document).on("click",'.minus_btn', function(){

                var  amt_to_sub = $(this).closest('tr').find('.qty_num').val();
                var minus_from = $(this).closest('tr').find('.existing_stock').val();
                var sub_amt = parseInt(minus_from) - parseInt(amt_to_sub);
                $(this).closest('tr').find('.existing_stock').val(sub_amt);

                $(this).closest('tr').find('.qty_num').val(0);
            });

            $(document).on("click",'.update_amt', function () {
                var update_amt = $(this).closest('tr').find('.qty_num').val();

                $(this).closest('tr').find('.existing_stock').val(update_amt);
                $(this).closest('tr').find('.qty_num').val(0);
            });

    });
</script>
</body>
    <head>
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="-1">

    </head>
</html>
<?php ob_end_flush(); ?>
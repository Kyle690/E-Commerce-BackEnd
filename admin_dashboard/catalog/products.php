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
<!-- confirm product delete modal-->
<div class="modal fade" id="confirm_delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h5>Alert!</h5>
                </div>
            </div>
            <form action="../process_files/product_proces.php" method="POST">
            <div class="modal-body">
                <p>Are you sure you want to delete this product?</p>
                <input type="text" value="" id="confirm_delete_value" name="confirm_delete_value" hidden>
            </div>
            <div class="modal-footer">
                <input type="submit" name="delete_product" class="btn btn-sm btn-danger " value="Delete" >
                <button class="btn btn-sm btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            </div>
            </form>
        </div>

    </div>
</div>
<!-- Main container -->
<div class="main-container" id="main-container">
    <div class="pd-ltr-20 customscroll customscroll-10-p height-100-p xs-pd-20-10">
        <div class="row">
            <!-- BreadCrumbs -->
            <div class="col-md-10 offset-1">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../admin_home.php">Home</a></li>
                    <li class="breadcrumb-item"><a>Catalog</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Products</li>
                </ol>
            </div>
        </div>
        <!-- Cards -->
        <div class="row">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn btn-sm btn-primary float-sm-right add_product">Add Product</button>
                    </div>
                    <div class="card-body">
                        <table class="data-table stripe hover nowrap">
                            <thead>
                            <tr>
                                <th width="20%" class="table-plus datatable-nosort">Img</th>
                                <th width="30%">Name</th>
                                <th width="10%">Status</th>
                                <th width="10%">Inventory</th>
                                <th width="10%">Variants</th>
                                <th width="20%" class="datatable-nosort">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php
                                $sql = "SELECT * FROM products";
                                $product = $db->select($sql);
                                if(sizeof($product)<1){
                                    echo "<tr>
                                             <td colspan='5' class='text-center'>No Products loaded yet</td>
                                       </tr>";
                                }else {
                                    foreach ($product as $product_info) {
                                        echo "
                                        <tr>
                                            <td class='table-plus'><img src='../../../storefront/img/product_img/{$product_info['main_img']}' style='height: 50px; width: 50px'  class='img-thumbnail'></td>
                                            <td>{$product_info['title']}</td>";

                                            if($product_info['status'] == 'enabled'){
                                                    echo '<td><span class="badge badge-pill badge-success">Active</span></td>';
                                                }else if($product_info['status'] ==  'disabled'){
                                                    echo'<td><span class="badge badge-pill badge-danger">Disabled</span></td>';
                                                }


                                            $sql2 = "SELECT * FROM product_variants WHERE product_id = {$product_info['id']}";
                                            $variants = $db->select($sql2);

                                            if(sizeof($variants)<1){
                                                echo"
                                                <td> 0 in stock</td>
                                                <td>0</td>
                                                ";
                                            }else{
                                                $no_of_variant = (sizeof($variants));
                                                $total_stock = array();
                                                foreach ($variants as $stock){
                                                    array_push($total_stock, $stock['var_stock']);
                                                }
                                                $total_stock_sum = array_sum($total_stock);
                                                echo"<td>".$total_stock_sum." total in stock</td>
                                                <td>$no_of_variant</td> ";
                                            }





                                            echo"
                                            <td>
                                                <div class='dropdown'>
                                                    <a class='btn btn-outline-primary dropdown-toggle' href='#' role='button' data-toggle='dropdown'>
                                                        <i class='fa fa-ellipsis-h'></i>
                                                    </a>
                                                    <div class='dropdown-menu dropdown-menu-right'>
                                                        <a class='dropdown-item edit_product' data-id='{$product_info['id']}' href='#'><i class='fa fa-pencil'></i> Edit</a>
                                                        <a class='dropdown-item delete_product' data-id='{$product_info['id']}' href='#'><i class='fa fa-trash'></i> Delete</a>
                                                    </div>
                                                </div>
                                            </td>
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
    <div class="col-sm-12">
        <div class=" footer-wrap bg-white pd-20 mb-20 border-radius-5 box-shadow">
            <p>Copyright - Vania Pasta 2018</p>
        </div>
    </div>
</div>
<script src="../../vendors/scripts/script.js"></script>
<script src = "../inc/jquery.richtext.js"></script>
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
<script src="../../src/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.js"></script>
<script src="../../src/plugins/dropzone/jquery.ui.widget.js" ></script>
<script src="../../src/plugins/dropzone/jquery.iframe-transport.js" ></script>
<script src="../../src/plugins/dropzone/jquery.fileupload.js" ></script>
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
            $(document).on('click', '.edit_product', function(){

                var product_edit_no = $(this).data('id');

                if( product_edit_no != ''){
                    $.ajax({
                        url: "products/edit_product.php",
                        method: "POST",
                        data: {product_edit_no: product_edit_no},
                        success: function (data) {
                            $('#main-container').html(data);

                        }
                    });
                }
            })
        });
        $(function () {

            $(document).on("click", ".delete_product", function(){

                var product_delete_no = $(this).data('id');
                $('#confirm_delete_value').val(product_delete_no);
                $('#confirm_delete').modal("show");
            })
        });


        $(function () {
            $('.add_product').click(function () {
                $('#main-container').load('products/new_product.php');
            })
        });
        // New Product JS
        $(document).on('click', '.new_prod_cancel', function() {
            location.reload();
        });

        $(document).on("click", '.img_uploader', function(){

            $(function  () {
                var files = $("#files");

                $("#fileupload").fileupload({
                    url: '../process_files/product_img_upload.php',
                    dropZone: '#dropZone',
                    dataType: 'json',
                    autoUpload: false
                }).on('fileuploadadd', function (e, data) {
                    var fileTypeAllowed = /.\.(gif|jpg|png|jpeg)$/i;
                    var fileName = data.originalFiles[0]['name'];
                    var fileSize = data.originalFiles[0]['size'];

                    if (!fileTypeAllowed.test(fileName))
                        alert("Only images are allowed")
                    else if (fileSize > 500000)
                        alert('Your file is too big! Max allowed size is: 500KB')
                    else {
                        $("#error").html("");
                        data.submit();
                    }
                }).on('fileuploaddone', function(e, data) {
                    var status = data.jqXHR.responseJSON.status;
                    var msg = data.jqXHR.responseJSON.msg;

                    if (status == 1) {
                        var path = data.jqXHR.responseJSON.path;
                        var fileName = data.jqXHR.responseJSON.fileName;
                        $("#img_gallery").fadeIn().append('<div class="col-sm-3">\n' +
                            '                            <a class="d-block img_to_be_selected" data-file_name = "'+fileName+'" >\n' +
                            '                                <img class="thumbnail img-fluid" data-selected="false" src="../../../storefront/img/product_img/'+fileName+'">\n' +
                            '\n' +
                            '                            </a>\n' +
                            '                        <p>'+fileName+'</p></div>');

                        $(".no_products").addClass("d-none");
                    } else
                        $("#error").html(msg);
                }).on('fileuploadprogressall', function(e,data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $("#progress").html("Completed: " + progress + "%");
                });
            });
        });

    });
</script>

</body>

</html>
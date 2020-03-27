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
            <div class="modal-body">
                <p>Are you sure you want to delete this product?</p>
                <input type="text" value="" id="confirm_delete_value" hidden>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-danger confirm_p_delete">Delete</button>
                <button class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
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
                    <li class="breadcrumb-item active" aria-current="page">Category</li>
                </ol>
            </div>
        </div>
        <!-- Cards -->
        <div id="category_process">
            <div class="row" >
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <a href="new_category.php" class="btn btn-sm btn-primary float-sm-right create_cat">Create Cataegory</a>
                        </div>
                        <div class="card-body">
                            <table class="data-table stripe hover nowrap">
                                <thead>
                                <tr>
                                    <th width="70%" class="table-plus">Title</th>
                                    <th width="10%">Enabled</th>
                                    <th width="20%" class="datatable-nosort">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php


                                    $sql = "SELECT * FROM categories";
                                    $categories = $db->select($sql);
                                    if(sizeof($categories)<1){
                                        echo "<tr>
                                                     <td colspan='4' class='text-center'>No Categories loaded yet</td>
                                               </tr>";
                                    }else{
                                        //echo $categories[0]['title'];
                                        foreach($categories as $category){

                                            echo "
                                        <tr>
                                            <td>{$category['title']}</td>
                                            <td>";
                                                if($category['status'] == 'enabled'){
                                                    echo '<span class="badge badge-pill badge-success">Active</span>';
                                                }else if($category['status'] ==  'disabled'){
                                                    echo'<span class="badge badge-pill badge-danger">Disabled</span>';
                                                }

                                            echo"                          
                                            </td>
                                            <td>
                                                <div class='dropdown'>
                                                    <a class='btn btn-outline-primary dropdown-toggle' href='#' role='button' data-toggle='dropdown'>
                                                        <i class='fa fa-ellipsis-h'></i>
                                                    </a>
                                                    <div class='dropdown-menu dropdown-menu-right'>
                                                        <a class='dropdown-item cat_edit' data-id='{$category['id']}' href='#'><i class='a fa-pencil'></i> Edit</a>
                                                        
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
<script src="../inc/jquery.richtext.js"></script>
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
        $(function(){
            $(".cat_edit").click(function() {
                var cat_edit_no = $(this).data('id');

                if (cat_edit_no != '') {
                    $.ajax({
                        url: "categories/edit_category.php",
                        method: "POST",
                        data: {cat_edit_no: cat_edit_no},
                        success: function (data) {
                            $('#category_process').html(data);
                            $("#txtEditor").richText();
                        }
                    });

                }
            });

        });
        
        $(document).on('click', '.rtn_cat', function() {
            location.reload();
        });

$(document).on("click", '.img_uploader', function(){

    $(function  () {
        var files = $("#files");

        $("#fileupload").fileupload({
            url: 'new_category.php',
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
                $("#images").fadeIn().append("<div class='col-sm-3'><img width='100px' height='100px' data-selected='false' class='img-fluid img_to_be_selected' data-file_name ='"+fileName+"' src='../../../storefront/img/category_img/"+fileName+"'></div>  ");
                $(".no_img_loaded").addClass('d-none');

            } else
                $("#error").html(msg);
        }).on('fileuploadprogressall', function(e,data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $("#progress").html("Completed: " + progress + "%");
        });
    });
});
        // Image to be selected

        $(document).on("click", ".img_to_be_selected", function () {
            var file_name = $(this).data('file_name');
            var selected = $(this).data('selected');
            $(".img_to_be_selected").closest("img").css('border','0px');
            $("#selected_img").val(file_name);

            if( selected == "false"){
                $(this).css('border','2px solid blue');
                $(this).data("selected","true");
            }else{
                $(this).css('border','0px');
                $(this).data("selected","false");
            }
        });
        // Update selected image
        $(document).on("click", ".final_select", function(){

            $('#category_img_selector').modal('hide');
            var file_inputVal = $('#selected_img').val();
            $(".img_to_be_selected").closest("img").css('border','0px');
            $("#cat_img_holder").empty();
            $("#cat_img_holder").append('<img class="img-fluid" width="200px" height="200px" src="../../../storefront/img/category_img/'+file_inputVal+'"><input type="hidden" value="'+file_inputVal+' " name="category_img_file" id="cat_img"/>')
        })

});
</script>

</body>

</html>
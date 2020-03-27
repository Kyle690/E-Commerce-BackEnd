<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/19
 * Time: 10:08 PM
 */
include ("../inc/sub_head.php");
// Top Nav bar
include("../inc/sub_top_nav_bar.php");
//side_nav
include ('../inc/sub_side_nav_bar.php');
?>
<div class="modal fade" id="confirm_delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6>Alert!</h6>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this image?</p>
                <input type="hidden" id="delete_id">
                <input type="hidden" id="img_kind">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" id="confirm_delete_btn"> Delete</button>
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
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
                    <li class="breadcrumb-item">Settings</li>
                    <li class="breadcrumb-item active" aria-current="page">Gallery</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h5>Select a gallery to edit</h5>
                    </div>
                </div><br>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body text-center">
                        <button class="btn btn-outline-primary btn-sm cate_images">Category Images</button>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body text-center">
                        <button class="btn btn-outline-primary btn-sm prod_images">Product Images</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../../vendors/scripts/script.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $(".cate_images").click(function () {
            cat_imgs();

        });
        function cat_imgs(){
            var cat_images = 'cat_images';
            $.ajax({
                url: "galleries/cat_image_gallery.php",
                method: "POST",
                data: {cat_image: cat_images},
                success: function (data) {
                    $('#main-container').html(data);
                }
            });
        }
        $(".prod_images").click(function () {
            prod_imgs();
        });
        function prod_imgs() {
            var product_images = 'prod_images';
            $.ajax({
                url: "galleries/product_img_galllery.php",
                method: "POST",
                data: {product_image: product_images},
                success: function (data) {
                    $('#main-container').html(data);
                }
            });
        }


        $(document).on("click", ".cancel_gal", function () {
            location.reload();
        });

        $(document).on('click', '.delete_img', function () {
            var img_id = $(this).data('id');
            var kind = $(this).data("kind");
            $("#delete_id").val(img_id);
            $("#img_kind").val(kind);
            $("#confirm_delete").modal("show");

        });
        $(document).on('click', '#confirm_delete_btn', function () {
            var img_delete_id = $("#delete_id").val();
            var img_kind = $('#img_kind').val();
            if(img_kind == 'category'){
                $.ajax({
                    url:"../process_files/delete_images.php",
                    method:"POST",
                    data:{img_id: img_delete_id, img_kind: img_kind},
                    success: function (data) {
                        alert(data);
                        $("#confirm_delete").modal("hide");
                        cat_imgs();
                    }
                })
            }else if(img_kind == 'product')
                $.ajax({
                    url:"../process_files/delete_images.php",
                    method:"POST",
                    data:{img_id: img_delete_id, img_kind: img_kind},
                    success: function (data) {
                        alert(data);
                        $("#confirm_delete").modal("hide");
                        prod_imgs();
                    }
                })


        })
    })

</script>
</body>

</html>

<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/04/29
 * Time: 2:53 PM
 */
include '../../../inc/database.php';
$db = new Database();
include_once ("../../inc/functions.php");
secure_session_start();
if(!isset($_SESSION['admin_id'])) {
    header('Location: ../../../index.php');

}
?>
<!-- img selector modal-->
<div class="modal fade" id="img_select_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Select an Image</h5>
                <button class="btn btn-primary btn-sm float-right img_uploader" data-target="#img_uploader_modal" data-toggle="modal">Upload</button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row" id="img_gallery"><?php
                        $sql_1 = "SELECT * FROM prod_img_gal";
                        $img = $db->select($sql_1);
                        if(sizeof($img)<1){
                            echo '<div class="col-sm-12 no_products">
                                                         <h4 class="text-center">No Images loaded yet</h4>
                                                    </div>';
                        }else{
                            foreach ($img as $images_src){
                                                echo"
                                                <div class='col-sm-3'>
                                                    <a class='d-block img_to_be_selected' data-file_name = '{$images_src['file_name']}' >
                                                     <img class='thumbnail img-fluid' data-selected='false' src='../../../storefront/img/product_img/{$images_src['file_name']}'>
                                                    </a>
                                                </div>
                                                       
                                                ";
                                            }
                                        }


                        ?>




                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" value="" id="selected_img">
                <button type="button"  class="btn btn-success btn-sm" id="select_btn">Select</button>
                <button type="button"  class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>

</div>
<!-- img upload modal-->
<div class="modal fade" id="img_uploader_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Upload an Image</h5>
            </div>
            <div class="modal-body">
                <div id="dropZone" style="border: 3px dashed #0088cc; padding: 50px; width: 100%;">
                    <h5>Drag & Drop Files...</h5>
                    <input type="file" id="fileupload" name="attachments[]" multiple>
                </div>
                <h5 id="error"></h5><br>
                <h5 id="progress"></h5>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary btn-sm">Done</button>
            </div>
        </div>

    </div>
</div>
<div class="pd-ltr-20 customscroll customscroll-10-p height-100-p xs-pd-20-10">
    <div class="row">
        <!-- BreadCrumbs -->
        <div class="col-md-10 offset-1">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../../admin_home.php">Home</a></li>
                <li class="breadcrumb-item"><a>Catalog</a></li>
                <li class="breadcrumb-item"><a href="products.php">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page">New Product</li>
            </ol>
        </div>

    </div>
    <div class="row">
        <form method="POST" role="form" action="../process_files/product_proces.php">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-sm btn-secondary float-sm-right new_prod_cancel">Cancel</button>
                    <input type="submit" class="btn btn-sm btn-success float-sm-right" name="new_product" value="Save">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-8">
                    <!--Title & Descriptions -->
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Title</label>
                                <input class="form-control" type="text" name="prod_title" maxlength="50" required>
                            </div><br>
                            <div class="form-group">
                                <label>Description</label>
                                <div class="">
                                    <textarea  class="description" name="prod_description" rows="2" maxlength="300"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <!-- Var card-->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="float-sm-left">Variants</h5>
                            <button type="button" id="add_variant" class="btn btn-secondary btn-sm float-sm-right">Add variant</button>
                            <br>
                            <br>
                            <table class="table table-sm" id="variant_table">
                                <thead>
                                <tr>
                                    <th width="40%">Option Name</th>
                                    <th width="30%"> SKU code</th>
                                    <th width="20%">Price</th>
                                    <th width="10%"></th>
                                </tr>
                                </thead>
                                <tbody id="var_body">
                                <tr>
                                    <td><input class="form-control" type="text" maxlength="60" name="var_name[]" required </td>
                                    <td><input class="form-control" type="text" maxlength="50" name="var_sku[]" ></td>
                                    <td><input class="form-control" type="number" maxlength="6" name="var_price[]" step=".01" required </td>

                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div><br>
                    <!-- Images -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4>Images</h4><br><br>
                                </div>
                                <div class="col-sm-12">
                                    <h6>Main Image</h6><br>
                                    <input name='main_img' type='hidden' value='' id="main_img_input">
                                    <div class="main_img_holder">
                                    </div><br>
                                    <button type="button" data-btn="main_img_sel" class="btn btn-sm btn-secondary img_btn">Choose Image</button>
                                </div><br><br>
                                <div class="col-sm-4">
                                    <h6>2nd Image</h6><br>
                                    <input name='2nd_img' type='hidden' value='' id="2nd_img_input">
                                    <div class="2nd_img_holder">
                                    </div><br>
                                    <button type="button" data-btn="2nd_img_sel"  class="btn btn-sm btn-secondary img_btn">Choose Image</button>
                                </div><br><br>
                                <div class="col-sm-4">
                                    <h6>3rd Image</h6><br>
                                    <input name='3rd_img'type='hidden' value='' id="3rd_img_input">
                                    <div class="3rd_img_holder">

                                    </div><br>
                                    <button type="button" data-btn="3rd_img_sel" class="btn btn-sm btn-secondary img_btn ">Choose Image</button>
                                </div><br><br>
                                <div class="col-sm-4">
                                    <h6>4th Image</h6><br>
                                    <input name='4th_img'type='hidden' value='' id="4th_img_input">
                                    <div class="4th_img_holder">

                                    </div><br>
                                    <button type="button" data-btn="4th_img_sel" class="btn btn-sm btn-secondary img_btn">Choose Image</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <!-- Extra info card-->
                    <br>
                    <div class="card">
                        <div class="card-body">
                            <!-- Ingredients & Cooking & Storage -->
                            <h5>Extra Product info</h5><br>
                            <div class="form-group">
                                <label>Ingredients</label>
                                <div class="">
                                    <textarea  class="ingredeints" name="prod_ingredients" rows="2" maxlength="800"></textarea>
                                </div>
                            </div><br>
                            <div class="form-group">
                                <label>Cooking Instructions</label>
                                <div class="">
                                    <textarea  class="cooking" name="prod_cooking" rows="2" maxlength="800"></textarea>
                                </div>
                            </div><br>
                            <div class="form-group">
                                <label>Storage</label>
                                <div class="">
                                    <textarea  class="storage" name="prod_storage" rows="2" maxlength="800"></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                    <br>


                </div>
                <div class="col-sm-4">
                    <!-- Categories card -->
                    <div class="card">
                        <div class="card-body">
                            <h6>Categories</h6><br>

                                <?php
                                    $sql = "SELECT * FROM categories";
                                    $categories = $db->select($sql);
                                    if(sizeof($categories)<1){
                                        echo "<div class='text-center'><h6>No Categories Loaded yet</h6></div>";
                                    }else{

                                        foreach($categories as $category){
                                            echo "
                                            <div class='form-check'>
                                                <input type='checkbox' class='form-check-input' name='prod_categories[]' value='{$category['id']}'>
                                                <label class='form-check-label'>{$category['title']}</label>
                                            </div>
                                            ";
                                        }
                                    }
                                ?>


                        </div>
                    </div>
                    <br>
                    <div class="card">
                        <div class="card-body">
                            <h6>SEO</h6><br>
                            <div class="form-group">
                                <label>Title</label>
                                <input class="form-control" name="product_seo_title" maxlength="17" >
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea type="text" name="product_seo_desc" value="description" class="form-control" maxlength="32"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        </form>
    </div>
    <br>
    <div class=" footer-wrap bg-white pd-20 mb-20 border-radius-5 box-shadow">
        <p>Copyright - Vania Pasta 2018</p>
    </div>

</div>


<script>

    $(document).ready(function () {
       // $(".txtEditor").richText();
        $(".description").richText();
        $(".ingredeints").richText();
        $(".cooking").richText();
        $(".storage").richText();

        $(function () {
            $("#add_variant").click(function () {
                var maxLenth = $("#variant_table tr").length;
                if(maxLenth <= 4){
                    var addVariantData = '<tr>\n' +
                        '                                    <td><input class="form-control" type="text" maxlength="60" name="var_name[]" required </td>\n' +
                        '                                    <td><input class="form-control" type="text" maxlength="50" name="var_sku[]" ></td>\n' +
                        '                                    <td><input class="form-control" type="number" maxlength="6" name="var_price[]" step=".01" required> </td>\n' +
                        '                                   <td><button class="btn btn-danger btn-sm delete_row"><i class="fa fa-close"></i></button> </td>\n' +
                        '\n' +
                        '                                </tr>'

                    $('#variant_table').find("tbody").append(addVariantData);
                } else {
                    alert("Only 4 variables per product are allowed!");
                }
            })

        });
        // remove variant row function
        $(document).on("click", ".delete_row", function(){
            $(this).closest("tr").remove();
        });
        // change img btn data to select into correct path
        $(document).on("click", ".img_btn", function(){
            var data_btn = $(this).data("btn");
            $("#select_btn").removeClass("main_img_sel");
            $("#select_btn").removeClass("2nd_img_sel");
            $("#select_btn").removeClass(" 3rd_img_sel");
            $("#select_btn").removeClass("4th_img_sel");

            $("#select_btn").addClass(data_btn);
            $("#img_select_modal").modal('show');


        });
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
        $(document).on("click", ".main_img_sel", function(){
            var selected_img = $('#selected_img').val();
            $(".main_img_holder").empty();
            $(".main_img_holder").append("<img src='http://192.168.64.2/vanita_store/storefront/img/product_img/"+selected_img+"' class='img-fluid' width='300px' height='300px'><br>")
            $("#main_img_input").val(selected_img);
            $(".img_to_be_selected").closest("a").css('border','0px');
            $("#selected-img").val(" ");
            $("#img_select_modal").modal("hide");
        });
        $(document).on("click", ".2nd_img_sel", function () {
            var selected_img = $('#selected_img').val();
            $(".2nd_img_holder").empty();
            $(".2nd_img_holder").append("<img src='http://192.168.64.2/vanita_store/storefront/img/product_img/"+selected_img+"' class='img-fluid' width='150px' height='150px'><br>")
            $("#2nd_img_input").val(selected_img);
            $(".img_to_be_selected").closest("a").css('border','0px');
            $("#selected-img").val(" ");
            $("#img_select_modal").modal("hide");
        });
        $(document).on("click", ".3rd_img_sel", function () {
            var selected_img = $('#selected_img').val();
            $(".3rd_img_holder").empty();
            $(".3rd_img_holder").append("<img src='http://192.168.64.2/vanita_store/storefront/img/product_img/"+selected_img+"' class='img-fluid' width='150px' height='150px'><br>")
            $("#3rd_img_input").val(selected_img);
            $(".img_to_be_selected").closest("a").css('border','0px');
            $("#selected-img").val(" ");
            $("#img_select_modal").modal("hide");
        });
        $(document).on("click", ".4th_img_sel", function () {
            var selected_img = $('#selected_img').val();
            $(".4th_img_holder").empty();
            $(".4th_img_holder").append("<img src='http://192.168.64.2/vanita_store/storefront/img/product_img/"+selected_img+"' class='img-fluid' width='150px' height='150px'><br>")
            $("#4th_img_input").val(selected_img);
            $(".img_to_be_selected").closest("a").css('border','0px');
            $("#selected-img").val(" ");
            $("#img_select_modal").modal("hide");
        });


    });
</script>

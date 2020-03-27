<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/04/29
 * Time: 7:05 PM
 */


include '../../../inc/database.php';
$db = new Database();
include_once ("../../inc/functions.php");
secure_session_start();
if(!isset($_SESSION['admin_id'])) {
header('Location: ../../../index.php');

}
if(isset($_POST['product_edit_no'])) {
    $editProductId = $_POST['product_edit_no'];


    $sql = "SELECT * FROM products WHERE id = '" . $editProductId . "'";
    $result = $db->select($sql);

    $seo_title = $result[0]['seo_title'];
    $seo_description = $result[0]['seo_description'];
    $status = $result[0]['status'];
}

?>

<!-- img selector modal-->
<div class="modal fade" id="img_select_modal">
    <div class="modal-dialog modal-lg">
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
                                                    <p>{$images_src['file_name']}</p>
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
                <li class="breadcrumb-item active" aria-current="page">Edit Product</li>
            </ol>
        </div>

    </div>
    <div class="row">
        <form method="POST" role="form" action="../process_files/product_proces.php" id="edit_products">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-sm btn-secondary float-sm-right new_prod_cancel">Cancel</button>
                        <input type="submit" class="btn btn-sm btn-success float-sm-right" name="edit_product" value="Save">
                        <input type="hidden" value="<?php echo $editProductId ?>" name="product_id">
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
                                    <input class="form-control" type="text" name="prod_title" maxlength="40" value="<?php echo $result[0]['title'] ?>" required>
                                </div><br>
                                <div class="form-group">
                                    <label>Description</label>
                                    <div>
                                        <textarea class="description" form="edit_products" name="prod_description" id="prod_description" rows="2" maxlength="300">
                                            <?php echo $result[0]['description']?></textarea>

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
                                    <?php
                                        $sql2 = "SELECT * FROM product_variants WHERE product_id = '".$editProductId."'";
                                        $var = $db->select($sql2);
                                        foreach ($var as $var_val){
                                            echo "
                                            <tr>
                                                <td><input class='form-control' type='text' maxlength='60'name='var_name[]' value='{$var_val['var_name']}' </td>
                                                <td><input class='form-control' type='text' maxlength='50' name='var_sku[]' value='{$var_val['var_sku_code']}' ></td>
                                                <td><input class='form-control' type='number' maxlength='6' name='var_price[]' step='.01' value='{$var_val['var_price']}' </td>
                                                <td><button class='btn btn-danger btn-sm delete_row' type='button'><i class='fa fa-close'></i></button> </td>
                                            </tr>
                                            ";
                                        }
                                    ?>

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
                                        <input name='main_img' type='hidden' value='<?php echo $result[0]['main_img'] ?>' id="main_img_input">
                                        <div class="main_img_holder">
                                            <img src='../../../storefront/img/product_img/<?php echo $result[0]['main_img'] ?>' class='img-fluid' width='300px' height='300px'><br>
                                        </div><br>
                                        <button type="button" data-btn="main_img_sel" class="btn btn-sm btn-secondary img_btn">Choose Image</button>
                                    </div><br><br>
                                    <div class="col-sm-4">
                                        <h6>2nd Image</h6><br>
                                        <input name='2nd_img' type='hidden' value='<?php echo $result[0]['2nd_img'] ?>' id="2nd_img_input">
                                        <div class="2nd_img_holder">
                                            <img src='../../../storefront/img/product_img/<?php echo $result[0]['2nd_img'] ?>' class='img-fluid' width='150px' height='150px'><br>
                                        </div><br>
                                        <button type="button" data-btn="2nd_img_sel"  class="btn btn-sm btn-secondary img_btn">Choose Image</button>
                                    </div><br><br>
                                    <div class="col-sm-4">
                                        <h6>3rd Image</h6><br>
                                        <input name='3rd_img'type='hidden' value='<?php echo $result[0]['3rd_img'] ?>' id="3rd_img_input">
                                        <div class="3rd_img_holder">
                                            <img src='../../../storefront/img/product_img/<?php echo $result[0]['3rd_img'] ?>' class='img-fluid' width='150px' height='150px'><br>
                                        </div><br>
                                        <button type="button" data-btn="3rd_img_sel" class="btn btn-sm btn-secondary img_btn ">Choose Image</button>
                                    </div><br><br>
                                    <div class="col-sm-4">
                                        <h6>4th Image</h6><br>
                                        <input name='4th_img'type='hidden' value='<?php echo $result[0]['4th_img'] ?>' id="4th_img_input">
                                        <div class="4th_img_holder">
                                            <img src='../../../storefront/img/product_img/<?php echo $result[0]['4th_img'] ?>' class='img-fluid' width='150px' height='150px'><br>
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
                                        <textarea  class="ingredeints" form="edit_products" name="prod_ingredients" id="prod_ingredients" rows="2" maxlength="800">
                                            <?php echo $result[0]['ingredients']?></textarea>
                                    </div>
                                </div><br>
                                <div class="form-group">
                                    <label>Cooking Instructions</label>
                                    <div class="">
                                        <textarea  class="cooking"  form="edit_products" name="prod_cooking" id="prod_cooking" rows="2" maxlength="800"><?php echo$result[0]['cooking']?></textarea>
                                    </div>
                                </div><br>
                                <div class="form-group">
                                    <label>Storage</label>
                                    <div class="">
                                        <textarea  class="storage" form="edit_products" name="prod_storage" id="prod_storage" rows="2" maxlength="800"><?php echo$result[0]['storage']?></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <br>



                    </div>
                    <div class="col-sm-4">
                        <!-- Categories card -->
                        <div class="card">
                            <div class="card-header"><h6>Categories</h6></div>
                            <div class="card-body">



                                <?php

                                $sql3 = "SELECT * FROM categories";
                                $categories = $db->select($sql3);
                                if(sizeof($categories)<1){
                                    echo "<div class='text-center'><h6>No Categories Loaded yet</h6></div>";
                                }else{
                                    $cat_selected_array = array();
                                    $sql4 = "SELECT * FROM category_products WHERE product_id= '".$editProductId."'";
                                    $result = $db->select($sql4);
                                    $cat_array = array();
                                    if(sizeof($result) == 1){
                                        $cat_db_array = explode(',', $result[0]['category_id']);

                                        foreach ($cat_db_array as $value){
                                            array_push($cat_array, $value );
                                        }
                                    }


                                    foreach($categories as $category){

                                        if(in_array($category['id'], $cat_array)){

                                            echo "
                                            <div class='form-check'>
                                                <input type='checkbox' class='form-check-input' name='prod_categories[]' checked='checked' value='{$category['id']}'>
                                                <label class='form-check-label'>{$category['title']}</label>
                                            </div>
                                            ";

                                        }else{
                                            echo "
                                            <div class='form-check'>
                                                <input type='checkbox' class='form-check-input' name='prod_categories[]' value='{$category['id']}'>
                                                <label class='form-check-label'>{$category['title']}</label>
                                            </div>
                                            ";
                                        }

                                    }





                                }
                                ?>

                            </div>

                        </div>

                        <br>

                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">SEO</h6>
                            </div>
                            <div class="card-body">

                                <div class="form-group">
                                    <label>Title</label>
                                    <input class="form-control" name="product_seo_title" maxlength="17" value="<?php echo $seo_title ?>" >

                                </div>

                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea type="text" name="product_seo_desc"  class="form-control" maxlength="32"><?php echo $seo_description?></textarea>
                                </div>
                            </div>
                        </div><br>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="prod_status" class="form-control">
                                        <?php
                                         if($status == 'enabled'){
                                             echo "<option value='enabled'>Enabled</option>
                                                   <option value='disabled'>Disabled</option> ";
                                         }else if($status == 'disabled'){
                                             echo "<option value='disabled'>Disabled</option>
                                                   <option value='enabled'>Enabled</option> ";
                                         }


                                        ?>

                                    </select>
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
        $(".description").richText();
        $(".ingredeints").richText();
        $(".cooking").richText();
        $(".storage").richText();

        $(function () {
            $("#add_variant").click(function () {
                var maxLenth = $("#variant_table tr").length;
                if(maxLenth <= 4){
                    var addVariantData = '<tr>\n' +
                        '                                    <td><input class="form-control" type="text" maxlength="60" name="var_name[]" </td>\n' +
                        '                                    <td><input class="form-control" type="text" maxlength="50" name="var_sku[]" ></td>\n' +
                        '                                    <td><input class="form-control" type="number" maxlength="6" name="var_price[]" step=".01"> </td>\n' +
                        '                                   <td><button type="button" class="btn btn-danger btn-sm delete_row"><i class="fa fa-close"></i></button> </td>\n' +
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
            var var_table_length = $("#variant_table tr").length;
            if(var_table_length > 2){
                $(this).closest("tr").remove();
            }else{
                alert("You need atlast one variant of the product");
            }

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
            $(".main_img_holder").append("<img src='../../../storefront/img/product_img/"+selected_img+"' class='img-fluid' width='300px' height='300px'><br>")
            $("#main_img_input").val(selected_img);
            $(".img_to_be_selected").closest("a").css('border','0px');
            $("#selected-img").val(" ");
            $("#img_select_modal").modal("hide");
        });
        $(document).on("click", ".2nd_img_sel", function () {
            var selected_img = $('#selected_img').val();
            $(".2nd_img_holder").empty();
            $(".2nd_img_holder").append("<img src='../../../storefront/img/product_img/"+selected_img+"' class='img-fluid' width='150px' height='150px'><br>")
            $("#2nd_img_input").val(selected_img);
            $(".img_to_be_selected").closest("a").css('border','0px');
            $("#selected-img").val(" ");
            $("#img_select_modal").modal("hide");
        });
        $(document).on("click", ".3rd_img_sel", function () {
            var selected_img = $('#selected_img').val();
            $(".3rd_img_holder").empty();
            $(".3rd_img_holder").append("<img src='../../../storefront/img/product_img/"+selected_img+"' class='img-fluid' width='150px' height='150px'><br>")
            $("#3rd_img_input").val(selected_img);
            $(".img_to_be_selected").closest("a").css('border','0px');
            $("#selected-img").val(" ");
            $("#img_select_modal").modal("hide");
        });
        $(document).on("click", ".4th_img_sel", function () {
            var selected_img = $('#selected_img').val();
            $(".4th_img_holder").empty();
            $(".4th_img_holder").append("<img src='../../../storefront/img/product_img/"+selected_img+"' class='img-fluid' width='150px' height='150px'><br>")
            $("#4th_img_input").val(selected_img);
            $(".img_to_be_selected").closest("a").css('border','0px');
            $("#selected-img").val(" ");
            $("#img_select_modal").modal("hide");
        });



    });
</script>

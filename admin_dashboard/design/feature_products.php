<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/04/26
 * Time: 1:20 PM
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
            <div class="container-fluid">
                <!-- BreadCrumbs -->
                <div class="col-md-10 offset-1">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="../admin_home.php">Home</a></li>
                        <li class="breadcrumb-item active">Design</li>
                        <li class="breadcrumb-item active" aria-current="page">Featured Products</li>
                    </ol>
                </div>
                <!--Main Col -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <p class="float-left">Edit and update the products you want to display on your home page.</p>
                            <button class="btn btn-sm btn-outline-secondary float-right" id="update" >Update</button>
                        </div>
                    </div>
                </div>
            </div>

        </div><br>

        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center">
                                <h4>Template</h4>
                                <h6>Main Product</h6>
                            </div><br>

                            <div class="row">
                                <div class="col-sm-6">
                                    <img src="http://via.placeholder.com/600x600"><br>
                                </div>
                                <div class="col-sm-6" >
                                    <div style="vertical-align: middle">
                                        <h5>Title</h5><br>
                                        <p>Desicription</p>
                                        <a href="#">View Product</a>
                                    </div>

                                </div>

                            </div><br>
                            <div class="row">
                                <div class="col-sm-4 text-center">
                                    <h6>1st Product</h6>
                                    <img src="http://via.placeholder.com/300x300"><br>
                                    <h5 style="font-size: 20px">Title</h5>
                                    <p style="font-size: 10px">Description</p>
                                    <a href="#" style="font-size: 10px">View Product</a>
                                </div>
                                <div class="col-sm-4 text-center">
                                    <h6>2nd product</h6>
                                    <img src="http://via.placeholder.com/300x300"><br>
                                    <h5 style="font-size: 20px">Title</h5>
                                    <p style="font-size: 10px">Description</p>
                                    <a href="#" style="font-size: 10px">View Product</a>
                                </div>
                                <div class="col-sm-4 text-center">
                                    <h6>3rd Product</h6>
                                    <img src="http://via.placeholder.com/300x300"><br>
                                    <h5 style="font-size: 20px">Title</h5>
                                    <p style="font-size: 10px">Description</p>
                                    <a href="#" style="font-size: 10px">View Product</a>
                                </div>
                            </div>
                        </div>

                    </div><br>
                    <div class="card">
                        <div class="card-body">
                            <h4>Main Featured Product</h4><br>
                            <p>Choose one main product to promate as the main product on the home page.</p>
                            <br>
                            <select class="form-control" name="main_product" id="main_product">
                                <?php
                                // load existing products
                                $sql_design = "SELECT * FROM design_products";
                                $result = $db->select($sql_design);
                                $main_product_id = $result[0]['main_product_id'];
                                $product_1_id = $result[0]['1st_product'];
                                $product_2_id = $result[0]['2nd_product'];
                                $product_3_id = $result[0]['3rd_product'];
                                if(isset($main_product_id)){
                                   $sql_product_wid = "SELECT * FROM products WHERE id ='".$main_product_id."' AND status='enabled'";
                                   $main_product = $db->select($sql_product_wid);
                                   echo "<option data-id='".$main_product[0]['id']."'>".$main_product[0]['title']."</option> 
                                   ";



                                }


                                $sql_product = "SELECT * FROM products WHERE status='enabled' ORDER BY id ASC";
                                $products = $db->select($sql_product);
                                foreach ($products as $product){
                                    echo "
                                        <option data-id='{$product['id']}' >{$product['title']}</option>                                
                                    ";

                                }



                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <h4>Featured products</h4><br>
                            <p>Choose 3 products that you would like to promote on the home page</p>
                            <br>
                            <div class="form-group">
                                <label>1st Product</label>
                                <select class="form-control" name="1st_product" id="1st_product">
                                    <?php
                                    if(isset($product_1_id)){
                                        $sql_product_wid1 = "SELECT * FROM products WHERE id ='".$product_1_id."'";
                                        $product_1 = $db->select($sql_product_wid1);
                                        echo "<option data-id='".$product_1[0]['id']."'>".$product_1[0]['title']."</option> 
                                   ";

                                    }

                                    foreach ($products as $product_1st){
                                        echo "
                                        <option data-id='{$product_1st['id']}'>{$product_1st['title']}</option>
                                        ";
                                    }
                                    ?>
                                </select>
                            </div><br>
                            <div class="form-group">
                                <label>2nd Product</label>
                                <select class="form-control" name="2nd_product" id="2nd_product">
                                    <?php
                                    if(isset($product_2_id)){
                                        $sql_product_wid2 = "SELECT * FROM products WHERE id ='".$product_2_id."'";
                                        $product_2 = $db->select($sql_product_wid2);
                                        echo "<option data-id='".$product_2[0]['id']."'>".$product_2[0]['title']."</option> 
                                   ";

                                    }
                                    foreach ($products as $products_2nd){
                                        echo "
                                        <option data-id='{$products_2nd['id']}'>{$products_2nd['title']}</option>
                                        ";
                                    }
                                    ?>
                                </select>
                            </div><br>
                            <div class="form-group">
                                <label>3rd Product</label>
                                <select class="form-control" name="3rd_product" id="3rd_product">
                                    <?php
                                    if(isset($product_3_id)){
                                        $sql_product_wid3 = "SELECT * FROM products WHERE id ='".$product_3_id."'";
                                        $product_3 = $db->select($sql_product_wid3);
                                        echo "<option data-id='".$product_3[0]['id']."'>".$product_3[0]['title']."</option> 
                                   ";

                                    }
                                    foreach ($products as $products_3rd){
                                        echo"
                                        <option data-id='{$products_3rd['id']}'>{$products_3rd['title']}</option>
                                        ";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
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
<script type="text/javascript">
    $(document).ready(function(){
       $("#update").click(function(){
           var main_img_id = $("#main_product option:selected").data("id");
           var product_1st = $("#1st_product option:selected").data("id");
           var product_2 = $("#2nd_product  option:selected").data("id");
           var product_3 = $("#3rd_product option:selected").data("id");

           $.ajax({
               url: "../process_files/design_products.php",
               method: "POST",
               data: {main_img: main_img_id, products_1st: product_1st, product_2: product_2, product_3: product_3},
               success: function (data){
                   alert(data);
                   location.reload();

               }


           })

       })




    });


</script>
</body>

</html>

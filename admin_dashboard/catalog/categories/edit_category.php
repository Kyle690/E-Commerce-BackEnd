<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/04/27
 * Time: 12:06 PM
 *
 *
 */
include "../../inc/functions.php";
secure_session_start();
include_once '../../../inc/database.php';
$db = new Database();
if(isset($_SESSION['admin_id'])) {

    if (isset($_POST['cat_edit_no'])) {
        $cat_edit_no = $_POST['cat_edit_no'];

        $sql = "SELECT * FROM categories WHERE id =  '" . $cat_edit_no . "'";
        $result = $db->select($sql);
        if (sizeof($result) == 1) {
            $cat_title = ($result[0]['title']);
            $cat_description = ($result[0]['description']);
            $cat_img_src = ($result[0]['img_src']);
            $cat_seo_title = ($result[0]['seo_title']);
            $cat_seo_description = ($result[0]['seo_description']);
            $cat_status = ($result[0]['status']);


            $html = '';
            echo '<!--Image gallery select modal-->
                <div class="modal fade" id="category_img_selector">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4>Select an Image </h4>
                                <button type="button" class="btn btn-sm btn-primary float-right img_uploader" data-toggle="modal" data-target="#image_upload">Upload</button>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <input type="hidden" id="selected_img" value="" >
                                    <div class="row text-center" id="images">
                                        ';

                                        $sql_1 = "SELECT * FROM cat_img_gal";
                                        $img = $db->select($sql_1);
                                        if(sizeof($img)<1){
                                            echo '<div class="col-sm-12 no_img_loaded">
                                                         <h4 class="">No Images loaded yet</h4>
                                                    </div>';
                                        }else{
                
                                            foreach ($img as $images_src){
                                                echo"
                                                
                                                    <div class='col-sm-2'>
                                                     <img width='100px' height='100px' data-selected='false' class='img-fluid img_to_be_selected' data-file_name ={$images_src['file_name']} src='../../../storefront/img/category_img/{$images_src['file_name']}'>
                                                    </div>    
                                                ";
                                            }
                                        }
                
                                        echo'
                                        </div>
                                    </div>
                
                
                
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success btn-sm final_select">Select</button>
                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                
                            </div>
                        </div>
                    </div>
                </div>
                <!-- image upload modal-->
                <div class="modal fade" id="image_upload">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4>Upload or drop and image</h4>
                            </div>
                            <div class="modal-body">
                                <div id="dropZone" style="border: 3px dashed #0088cc; padding: 50px; width: 100%;">
                                    <h4>Drag & Drop Files...</h4>
                                    <input type="file" id="fileupload" name="attachments[]" multiple>
                                </div>
                                <h5 id="error"></h5><br>
                                <h5 id="progress"></h5>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary btn-sm"data-dismiss="modal">Done</button>
                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
               <!-- // are you sure you want to delete modal-->
                <div class="modal fade" id="cat_delete_modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="modal-title">
                                    <h5>Alert!</h5>
                                </div>
                            </div>
                            <div class="modal-body">
                                <div>
                                    <h6>Are you sure you want to delete this category? </h6>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <form method ="POST" action="../process_files/add_category.php">
                                    <input type="hidden" name="catergory_id"  value= "';echo $cat_edit_no.'">
                                    <input type="submit" class="btn btn-sm btn-danger" name="cat_delete" value="Delete"/>
                                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                                </form>
                            </div>
                         </div>
                    </div> 
                </div>
                <!--// Main container-->
                <div class="contianer-fluid">
                <form action="../process_files/add_category.php" method="POST">
                    <div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="float-left">Category Name</h5>
                                        <button  type="button" class="btn btn-sm btn-secondary float-md-right rtn_cat" >Cancel</button>
                                        <input type="submit" name="cat_data_update" class="btn btn-sm btn-success float-md-right" value="Save"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <br>
                            
                        <div class="row">
                        
                            <input type="hidden" name="cat_id" value="'; echo $cat_edit_no.'"/>
                            <div class="col-sm-8">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="">
                                            <div class="form-group">
                                                <label>Title</label>
                                                <input class="form-control" type="text" name="cat_title" maxlength="20" required value="';echo $cat_title. '">
                                            </div>
                                            <div class="form-group">
                                                <label>Description</label>
                                                <div class="page-wrapper box-content">
                                                    <textarea  id="txtEditor" name="cat_description" maxlength="255">'; echo $cat_description.'</textarea>
                                                </div>
                                               
                                            </div>
                                            
                                            
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h6>Products</h6>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-hover">
                                            ';
                                                $sql_products_id = "SELECT * FROM category_products";
                                                $cat_prod = $db->select($sql_products_id);
                                                if(sizeof($cat_prod) > 0){
                                                    $product_id_array = array();
                                                    foreach ($cat_prod as $cat_products){
                                                        $cat_prod_array = explode(',',$cat_products['category_id']);
                                                        if(in_array($cat_edit_no, $cat_prod_array)){
                                                            array_push($product_id_array, $cat_products['product_id']);
                                                        }
                                                    }


                                                }

                                              foreach ($product_id_array as $prod_id){
                                                  $sql_prod = "SELECT * FROM products WHERE id = '$prod_id'";
                                                  $prod_name = $db->select($sql_prod);
                                                  echo'<tr>
                                                            <td><img width="50px" height="50px" src="../../../storefront/img/product_img/'.$prod_name[0]['main_img'].'"></td>
                                                            <td><p>'.$prod_name[0]['title'].'</p></td>
                                                        </tr>';
                                              }





                                             echo'       
                                        </table>
                                    </div>
                                </div>
                                <br>
                                 <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">Status</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <select name="cat_status" class="form-control custom-select">
                                            ';
                                                if($cat_status == "enabled"){
                                                    echo'<option value="enabled">Enabled</option>
                                                        <option value="disabled">Disabled</option>';
                                                }else{
                                                    echo'<option value="disabled">Disabled</option>
                                                        <option value="enabled">Enabled</option>';
                                                }

                                                echo'
                                            
                                            </select>
                                    </div>
                                       
                                    </div>
                                    
                                </div>
                                
                                
                            </div>
                            <div class="col-sm-4">
                                
                                <div class="card">
                                    <div class="card-header">
                                        <h6>Category Image</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="cat_img_holder">
                                            '; if($cat_img_src == ''){
                                            echo'<h6 class="text-center">No Image Loaded yet</h6><br>';}
                                            else{
                                            echo'<img class="img-fluid" width="200px" height="200px" src="../../../storefront/img/category_img/'.$cat_img_src.'">
                                            <input type="hidden" value="'.$cat_img_src.' " name="category_img_file" id="cat_img"/>';}
                                            echo'  
                                        </div>
                                            <br>
                                        <button type="button" class="btn btn-secondary btn-sm" data-target="#category_img_selector" data-toggle="modal">Select image</button>
                                    </div>
                                </div>
                                <br>
                                <div class="card">
                                    <div class="card-header">
                                        <h5>SEO Listing</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Title</label>
                                            <input type="text" name="seo_title_cat" value="'; echo $cat_seo_title.'" class="form-control" maxlength="17">
                                        </div>
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea type="text" name="seo_descrip_cat"  class="form-control" maxlength="300">'.$cat_seo_description.'</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="card">
                            <div class="card-body">
                                <button type="button" class="btn btn-sm btn-danger btn-block" data-target="#cat_delete_modal" data-toggle="modal">Delete</button>
                            </div>
                        </div>
            
                    </form>
                    </div>
                </div>
                    
                    
                   
                </div>
    ';
            echo $html;

        }
    }
}else{
    header('Location: ../../../index.php');
}

?>




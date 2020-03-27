<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/04/27
 * Time: 8:48 PM
 */
include_once ("../inc/functions.php");
secure_session_start();
include '../../inc/database.php';
$db = new Database();
if(isset($_SESSION['admin_id'])) {
    if (isset($_FILES['attachments'])) {
        $msg = "";
        $fileName = $_FILES['attachments']['name'][0];
        $targetFile = "../../../storefront/img/category_img/" . basename($_FILES['attachments']['name'][0]);
        if (file_exists($targetFile)) {
            $msg = array("status" => 0, "msg" => "File already exists!");
        } else if (move_uploaded_file($_FILES['attachments']['tmp_name'][0], $targetFile)) {

            $date_created = date("Y-m-d H-i-sa");
            $sql = "INSERT INTO cat_img_gal (file_name, date_added) VALUES (?,?)";
            if($stmt= $mysqli->prepare($sql)){
                $stmt->bind_param('ss', $fileName_param,$date_created_param);
                $fileName_param = $fileName;
                $date_created_param = $date_created;
                if($stmt->execute()){
                    $msg = array("status" => 1, "msg" => "File Has Been Uploaded", "path" => $targetFile, "fileName" => $fileName);
                }else{
                    // failer with the execute of stmt
                    $msg = "Error with the statement";
                }
            }else{
                // failure with preparing the statement
                $msg =  "error with preparing the statement";
            }

        }
        exit(json_encode($msg));
    }
}
include ("../inc/sub_head.php");
// Top Nav bar
include("../inc/sub_top_nav_bar.php");
//side_nav
include ('../inc/sub_side_nav_bar.php');


?>
<!-- Image gallery select modal-->
<div class="modal fade" id="category_img_selector">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Select only one Image </h4>
                <button type="button" class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#image_upload">Upload</button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <input type="hidden" id="selected_img" value="" >
                    <div class="row" id="images">

                        <?php
                            $sql_1 = "SELECT * FROM cat_img_gal";
                        $img = $db->select($sql_1);
                        if(sizeof($img)<1){
                            echo '<div class="col-sm-12 no_img_loaded">
                                         <h4 class="">No Images loaded yet</h4>
                                    </div>';
                        }else{

                            foreach ($img as $images_src){
                                echo"
                                
                                    <div class='col-sm-3'>
                                     <img width='100px' height='100px' data-selected='false' class='img-fluid img_to_be_selected' data-file_name ={$images_src['file_name']} src='../../../storefront/img/category_img/{$images_src['file_name']}'>
                                    </div>    
                                ";
                            }
                        }

                        ?>
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
<!--image upload modal-->
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
// Main Body
<div class="main-container" id="main-container">
    <div class="pd-ltr-20 customscroll customscroll-10-p height-100-p xs-pd-20-10">
        <div class="row">
            <!-- BreadCrumbs -->
            <div class="col-md-10 offset-1">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../admin_home.php">Home</a></li>
                    <li class="breadcrumb-item"><a>Catalog</a></li>
                    <li class="breadcrumb-item "><a href="categories.php">Categories</a></li>
                    <li class="breadcrumb-item active" aria-current="page">New</li>
                </ol>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <form method="POST" action="../process_files/add_category.php">
                        <div class="">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="float-left">New Catergory</h5>
                                    <a href="categories.php" class="btn btn-sm btn-secondary float-md-right text-white">Cancel</a>
                                    <input type="submit" name="new_category" class="btn btn-sm btn-success float-md-right" value="Save">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="">
                                            <div class="form-group">
                                                <label>Title</label>
                                                <input class="form-control" type="text" name="cat_title" placeholder="Category name" maxlength="20" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Description</label>
                                                <div class="page-wrapper box-content">

                                                    <textarea  id="txtEditor" name="cat_description"></textarea>
                                                </div>
                                            </div>
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

                                        </div>

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
                                            <label> SEO Title</label>
                                            <input type="text" name="seo_title" placeholder="Title" class="form-control" maxlength="17">
                                        </div>
                                        <div class="form-group">
                                            <label> SEO Description</label>
                                            <textarea type="text" name="seo_descrip" placeholder="description" class="form-control" maxlength="300"></textarea>
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
    </div>
</div>


<script src="http://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
<script src="../../vendors/scripts/script.js"></script>
<script src = "../inc/jquery.richtext.js"></script>

<script src="../../src/plugins/dropzone/jquery.ui.widget.js" ></script>
<script src="../../src/plugins/dropzone/jquery.iframe-transport.js" ></script>
<script src="../../src/plugins/dropzone/jquery.fileupload.js" ></script>
<script>
    $(function () {
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

    $(document).ready(function() {
        $("#txtEditor").richText();
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
        $(".final_select").click(function () {

            $('#category_img_selector').modal('hide');
            var file_inputVal = $('#selected_img').val();
            $(".img_to_be_selected").closest("img").css('border','0px');
            $("#cat_img_holder").empty();
            $("#cat_img_holder").append('<img class="img-fluid" width="200px" height="200px" src="../../../storefront/img/category_img/'+file_inputVal+'"><input type="hidden" value="'+file_inputVal+' " name="category_img_file" id="cat_img"/>')
        })
    });
</script>


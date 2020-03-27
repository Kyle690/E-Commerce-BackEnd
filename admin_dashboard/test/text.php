<?php
/**

 * User: kyle
 * Date: 2018/04/27
 * Time: 7:47 PM
 */
if (isset($_FILES['attachments'])) {
    $msg = "";
    $targetFile = "test_img/" . basename($_FILES['attachments']['name'][0]);
    if (file_exists($targetFile))
        $msg = array("status" => 0, "msg" => "File already exists!");
    else if (move_uploaded_file($_FILES['attachments']['tmp_name'][0], $targetFile))
        $msg = array("status" => 1, "msg" => "File Has Been Uploaded", "path" => $targetFile);

    exit(json_encode($msg));
}

include ("../inc/sub_head.php");
// Top Nav bar
include("../inc/sub_top_nav_bar.php");
//side_nav
include ('../inc/sub_side_nav_bar.php');
?>
<div class="modal fade" id="page_loader">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">

                    <div class="col-sm-4">
                        <div class="loader">

                        </div>

                    </div>
                </div>




            </div>
        </div>
    </div>
</div>
<div class="main-container">
    <div class="pd-ltr-20 customscroll customscroll-10-p height-100-p xs-pd-20-10">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
                            <div class="clearfix mb-20">
                                <div class="pull-left">
                                    <h4 class="text-blue">Categories Image</h4>
                                </div>
                            </div>
                            <div>
                                <button class="btn btn-primray" type="button" data-toggle="modal" data-target="#page_loader">Open Modal</button>
                                <div id="dropZone" style="border: 3px dashed #0088cc; padding: 50px; width: 100%;">
                                    <h4>Drag & Drop Files...</h4>
                                    <input type="file" id="fileupload" name="attachments[]" multiple>
                                </div>
                                <h1 id="error"></h1><br><br>
                                <h1 id="progress"></h1><br><br>
                                <div id="files"></div>

                                <h5 id="error"></h5><br><br>
                                <h5 id="progress"></h5><br><br>
                                <div id="files"></div>
                                <input type="button" id="img_upload" name="img_upload" class="btn btn-primary" value="Done">
                            </div>

                        </div>
                    </div>


                </div>

            </div>
        </div>
        <div class="col-sm-8" id="ajax_log">
            <button class="btn btn-success ajax_btn" data-id="1">Ajax</button>
        </div>

        </div>

    </div>
</div>
    <script src="http://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <!--<script src="../../src/plugins/dropzone/src/dropzone.js"></script>
     switchery js
    <script src="../../src/plugins/switchery/dist/switchery.js"></script>-->
<script src="../../vendors/scripts/script.js"></script>
    <script src="../../src/plugins/dropzone/jquery.ui.widget.js" ></script>
    <script src="../../src/plugins/dropzone/jquery.iframe-transport.js" ></script>
    <script src="../../src/plugins/dropzone/jquery.fileupload.js" ></script>

    <script type="text/javascript">

        $(function () {
            var files = $("#files");

            $("#fileupload").fileupload({
                url: 'text.php',
                dropZone: '#dropZone',
                dataType: 'json',
                autoUpload: false
            }).on('fileuploadadd', function (e, data) {
                var fileTypeAllowed = /.\.(gif|jpg|png|jpeg)$/i;
                var fileName = data.originalFiles[0]['name'];
                var fileSize = data.originalFiles[0]['size'];

                if (!fileTypeAllowed.test(fileName))
                    $("#error").html('Only images are allowed!');
                else if (fileSize > 500000)
                    $("#error").html('Your file is too big! Max allowed size is: 500KB');
                else {
                    $("#error").html("");
                    data.submit();
                }
            }).on('fileuploaddone', function(e, data) {
                var status = data.jqXHR.responseJSON.status;
                var msg = data.jqXHR.responseJSON.msg;

                if (status == 1) {
                    var path = data.jqXHR.responseJSON.path;
                    $("#files").fadeIn().append('<p><img style="width: 100px; height: 100px;" src="'+path+'" /></p>');
                } else
                    $("#error").html(msg);
            }).on('fileuploadprogressall', function(e,data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $("#progress").html("Completed: " + progress + "%");
            });
        });








    </script>
    <script type="text/javascript">
       /* $(document).ready(function(){






            $(function(){
                $(".ajax_btn").click(function() {
                    var ajaxBtnId = $(this).data('id');

                    if (ajaxBtnId != '') {
                        $.ajax({
                            url: "ajax_fun.php",
                            method: "POST",
                            data: {ajaxBtnId: ajaxBtnId},
                            success: function (data) {
                                $('#ajax_log').html(data);



                            }
                        });

                    }
                });
            });
        });*/
    </script>
</body>
</html>

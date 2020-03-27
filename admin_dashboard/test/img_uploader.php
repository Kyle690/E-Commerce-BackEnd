<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/04
 * Time: 7:46 PM
 */


?>
<!doctype html>
<head>
    <title>jQuery File Upload Script</title>

</head>
<body>
<div class="main-container">
    <center>
        <img src="images/logo.png"><br><br>
        <div id="dropZone">
            <h1>Drag & Drop Files...</h1>
            <input type="file" id="fileupload" name="attachments[]" multiple>
        </div>
        <h1 id="error"></h1><br><br>
        <h1 id="progress"></h1><br><br>
        <div id="files"></div>
    </center>
</div>


<script src="http://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>

<script type="text/javascript">



    $(function () {
        var files = $("#files");

        $("#fileupload").fileupload({
            url: 'img_uploader.php',
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
</body>
</html>

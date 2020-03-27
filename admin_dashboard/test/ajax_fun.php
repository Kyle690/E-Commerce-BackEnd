<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/04/28
 * Time: 11:40 AM
 */


if($_FILES["file"]["name"] != '')
{
    $test = explode('.', $_FILES["file"]["name"]);
    $ext = end($test);
    $name = rand(100, 999) . '.' . $ext;
    $location = 'test_img/' . $name;
    move_uploaded_file($_FILES["file"]["test_img"], $location);

    echo '<img src="'.$location.'" height="150" width="225" class="img-thumbnail" />';
}


/*
<div class="col-sm-8">
    <div class="card">
        <div class="card-body">
            <div class="">
                <div class="form-group">
                    <label>Title</label>
                    <input class="form-control" type="text" name="cat_title" maxlength="20">
                </div>
                <div  class="html-editor pd-20 bg-white border-radius-4 box-shadow mb-30">
                    <h3 class="weight-400">Descritption</h3>
                    <textarea class="textarea_editor form-control border-radius-0" placeholder="Enter text ..."></textarea>
                </div>
            </div>
        </div>
    </div>
</div>*/
?>
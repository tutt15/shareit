<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/header.php'; ?>
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/leftbar.php'; ?>
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2>Sửa danh mục</h2>
            </div>
        </div>
        <!-- /. ROW  -->
        
        <div class="row">
            <div class="col-md-12">
                <!-- Form Elements -->
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <?php
                                $news_id = 0;
                                if(!isset($_GET['id']) || !preg_match("/^[0-9]+$/", $_GET['id'])){
                                    header('Location: /admin/news');
                                    die();
                                }
                                $news_id = $_GET['id'];
                                $querySltNews = "SELECT * FROM news WHERE id = {$news_id}";
                                $resultSltNews = $mysqli->query($querySltNews);
                                $arNews = $resultSltNews->fetch_assoc();
                                if(empty($arNews)){
                                    $_SESSION['error'] = "Tin không tồn tại";
                                    header('Location: /admin/news');
                                    die();
                                }else{
                                    if($arNews['created_by'] != $_SESSION['userInfo']['id'] && $_SESSION['userInfo']['role'] != 'admin'){
                                        $_SESSION['error'] = "Bạn không có quyền sửa tin này";
                                        header('Location: /admin/news');
                                        die();
                                    }
                                } 
                                $news_name = $arNews['name'];
                                $cat_id = $arNews['cat_id'];
                                $news_picture = $arNews['picture'];
                                $news_preview = $arNews['preview'];
                                $news_detail = $arNews['detail'];
                               
                                if(isset($_POST['submit'])){
                                    $news_name = $_POST['name'];
                                    $cat_id = $_POST['cat_id'];
                                    $news_preview = $_POST['preview'];
                                    $news_detail = $_POST['detail'];
                                    $error = [];
                                    if($news_name == ''){
                                        $error['news_name'] = "Tên tin không được để trống";
                                    }
                                    if($cat_id == ''){
                                        $error['cat_id'] = "Danh mục không được để trống";
                                    }
                                    if($news_preview == ''){
                                        $error['news_preview'] = "Mô tả không được để trống";
                                    }
                                    if($news_detail == ''){
                                        $error['news_detail'] = "Chi tiết không được để trống";
                                    }
                                    if(empty($error)){
                                        $queryCkNews = "SELECT * FROM news WHERE name = '{$news_name}' AND id != $news_id ";
                                        $resultCkNews = $mysqli->query($queryCkNews); 
                                        if($resultCkNews->num_rows > 0){
                                            $error['news_name'] = "Tên tin đã tồn tại";
                                        }else{
                                            if($_FILES['newpicture']['name'] != ''){ 
                                                $path_root = $_SERVER['DOCUMENT_ROOT'];
                                                // if($news_picture != ''){
                                                //     unlink($path_root.'/files/'.$news_picture);
                                                //     $tmp_name = $_FILES['newpicture']['tmp_name'];
                                                //     $path_upload = $path_root.'/files/'.$news_picture;
                                                // }else{
                                                    unlink($path_root.'/files/'.$news_picture);
                                                    $arName = explode('.',$_FILES['newpicture']['name']);
                                                    $typeFile = end($arName);
                                                    $news_picture = 'shareit-'.time().'.'.$typeFile;
                                                    $tmp_name = $_FILES['newpicture']['tmp_name'];
                                                    $path_upload = $path_root.'/files/'.$news_picture;
                                                // }                              
                                                $move_upload = move_uploaded_file($tmp_name,$path_upload);
                                                if($move_upload){
                                                    echo "Upload thành công";
                                                }else{
                                                    echo "Upload thất bại";
                                                }                                      
                                            }else{ 
                                                if(isset($_POST['delpicture'])){ 
                                                    $path_root = $_SERVER['DOCUMENT_ROOT'];
                                                    unlink($path_root.'/files/'.$news_picture);
                                                    $news_picture = '';
                                                }
                                            } 
                                            $queryEditNews = "UPDATE news SET name = '{$news_name}', preview = '{$news_preview}', detail = '{$news_detail}', picture = '{$news_picture}', cat_id = {$cat_id} WHERE id = {$news_id}";
                                            $resultEditNews = $mysqli->query($queryEditNews);
                                            if($mysqli->affected_rows > 0){
                                                $_SESSION['success'] = "Sửa tin thành công";
                                                header('Location: /admin/news');
                                            }else{
                                                $_SESSION['error'] = "Dữ liệu không thay đổi";
                                                header('Location: /admin/news');
                                            }
                                        }
                                    }
                                    
                                }
                            ?>
                            <div class="col-md-12">
                                <form role="form" action="" method="post" enctype="multipart/form-data" >
                                    <div class="form-group">
                                        <label>Tên tin</label>
                                        <input type="text" name="name" value="<?php echo $news_name; ?>" class="form-control" />
                                        <?php if(isset($error['news_name'])){ ?>
                                            <p class="text-error"><?php echo $error['news_name']; ?></p>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Danh mục</label>
                                        <select name="cat_id" class="form-control">
                                        <?php
                                            $query2 = "SELECT * FROM cat";
                                            $result2 = $mysqli->query($query2);
                                            while($row2 = $result2->fetch_assoc()){
                                                if($row2['id'] == $cat_id){
                                        ?>
                                                    <option value="<?php echo $row2['id'] ?>" selected ><?php echo $row2['name'] ?></option>
                                        <?php
                                                }else{
                                        ?>
                                                    <option value="<?php echo $row2['id'] ?>" ><?php echo $row2['name'] ?></option>
                                        <?php
                                                }
                                            }
                                        ?>
                                        </select>
                                        <?php if(isset($error['cat_id'])){ ?>
                                            <p class="text-error"><?php echo $error['cat_id']; ?></p>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Hình ảnh củ</label><br />
                                        <?php
                                            if($news_picture != '' ){
                                        ?>
                                        <img src="/files/<?php echo $news_picture ?>" alt="" width="90px" height="90px"><br />
                                        <input type="checkbox" name="delpicture" value="delpicture" > Xóa hình
                                        <?php
                                            }
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Hình ảnh mới</label>
                                        <input id="inputFile" type="file" name="newpicture" class="form-control" /><br>
                                        <img id="view_avatar" src="" alt="" height="120px" width="120px">
                                        <script>
                                            function readURL(input) {
                                                if (input.files && input.files[0]) {
                                                    var reader = new FileReader();

                                                    reader.onload = function (e) {
                                                        $('#view_avatar').attr('src', e.target.result);
                                                    }

                                                    reader.readAsDataURL(input.files[0]);
                                                }
                                            }

                                            $("#inputFile").change(function () {
                                                readURL(this);
                                            });
                                        </script>
                                    </div>
                                    <div class="form-group">
                                        <label>Mô tả</label>
                                        <textarea name="preview" class="form-control" rows="4"><?php echo $news_preview ?></textarea>
                                        <?php if(isset($error['news_preview'])){ ?>
                                            <p class="text-error"><?php echo $error['news_preview']; ?></p>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Chi tiết</label>
                                        <textarea name="detail" class="form-control ckeditor" rows="4"><?php echo $news_detail ?></textarea>
                                        <?php if(isset($error['news_detail'])){ ?>
                                            <p class="text-error"><?php echo $error['news_detail']; ?></p>
                                        <?php } ?>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-success btn-md">Sửa</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- End Form Elements -->
            </div>
        </div>
        <!-- /. ROW  -->
    </div>
    <!-- /. PAGE INNER  -->
</div>
<!-- /. PAGE WRAPPER  -->
<script type="text/javascript">
    $(document).ready(function(){
        $('#checkVal').validate({
            ignore: [],
            rules:{
                "name": {
                    required: true
                },
                "cat_id": {
                    required: true
                },
                "preview": {
                    required: true
                },
                "detail": {
                    required: true
                }
            },
            messages: {
                "name": {
                    required: 'Không được để trống'
                },
                "cat_id": {
                    required: 'Không được để trống'
                },
                "preview_": {
                    required: 'Không được để trống'
                },
                "detail": {
                    required: 'Không được để trống'
                }
            }
        });
        CKEDITOR.on('instanceReady', function () {
            $.each(CKEDITOR.instances, function (instance) {
                CKEDITOR.instances[instance].document.on("keyup", CK_jQ);
                CKEDITOR.instances[instance].document.on("paste", CK_jQ);
                CKEDITOR.instances[instance].document.on("keypress", CK_jQ);
                CKEDITOR.instances[instance].document.on("blur", CK_jQ);
                CKEDITOR.instances[instance].document.on("change", CK_jQ);
            });
        });

        function CK_jQ() {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
        }
    });
</script>
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/footer.php'; ?>
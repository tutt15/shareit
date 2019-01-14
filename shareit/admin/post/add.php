<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/header.php'; ?>
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/leftbar.php'; ?>
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2>Thêm tin</h2>
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
                                if(isset($_POST['submit'])){
                                    $news_name = $_POST['name'];
                                    $cat_id = $_POST['cat_id'];
                                    $created_by = $_SESSION['userInfo']['id'];
                                    $preview = $_POST['preview'];
                                    $detail = $_POST['detail'];
                                    $fileName = $_FILES['picture']['name'];
                                    $is_slide = 0;
                                    $error = [];
                                    if($news_name == ''){
                                        $error['news_name'] = "Tên tin không được để trống";
                                    }
                                    if($cat_id == ''){
                                        $error['cat_id'] = "Danh mục không được để trống";
                                    }
                                    if($preview == ''){
                                        $error['preview'] = "Mô tả không được để trống";
                                    }
                                    if($detail == ''){
                                        $error['news_detail'] = "Chi tiết không được để trống";
                                    }
                                    if(empty($error)){
                                        $queryCkNews = "SELECT * FROM news WHERE name = '{$news_name}' ";
                                        $resultCkNews = $mysqli->query($queryCkNews); 
                                        if($resultCkNews->num_rows > 0){
                                            $error['cat_name'] = "Tên tin đã tồn tại";
                                        }else{
                                            if($fileName != ''){
                                                $arName = explode('.',$fileName);
                                                $typeFile = end($arName);
                                                $newName = 'shareit-'.time().'.'.$typeFile;
                                                $tmp_name = $_FILES['picture']['tmp_name'];
                                                $path_root = $_SERVER['DOCUMENT_ROOT'];
                                                $path_upload = $path_root.'/files/'.$newName;
                                                $move_upload = move_uploaded_file($tmp_name,$path_upload);
                                                if($move_upload){
                                                    echo "Upload thành công";
                                                }else{
                                                    echo "Upload thất bại";
                                                }
                                            }
                                            /* ------------------------------------------------------- */
                                            $queryAddNews = "INSERT INTO news(name, preview, detail, created_by, picture, cat_id, is_slide) VALUES ('{$news_name}','{$preview}','{$detail}',{$created_by},'{$newName}',{$cat_id},{$is_slide})";
                                            $resultAddNews = $mysqli->query($queryAddNews);
                                            if($mysqli->affected_rows > 0){
                                                $_SESSION['success'] = "Thêm tin thành công";
                                                header('Location: /admin/post');
                                            }else{
                                                $_SESSION['error'] = "Thêm tin thất bại";
                                                header('Location: /admin/post');
                                            }
                                        }
                                    }                          
                                }
                            ?>
                            <div class="col-md-12">
                                <form id="checkVal" role="form" action="" method="post" enctype="multipart/form-data" >
                                    <div class="form-group">
                                        <label>Tên tin</label>
                                        <input type="text" name="name" class="form-control" />
                                        <?php if(isset($error['news_name'])){ ?>
                                            <p class="text-error"><?php echo $error['news_name']; ?></p>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Danh mục tin</label>
                                        <select class="form-control" name="cat_id" id="">
                                            <option value="">--Chọn danh mục--</option>
                                            <?php 
                                                $query2 = "SELECT * FROM cat";
                                                $result2 = $mysqli->query($query2);
                                                while($row2 = $result2->fetch_assoc()){
                                                    $cat_id = $row2['id'];
                                                    $cat_name = $row2['name']; 
                                            ?>
                                                <option value="<?php echo $cat_id ?>"><?php echo $cat_name ?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                        <?php if(isset($error['cat_id'])){ ?>
                                            <p class="text-error"><?php echo $error['cat_id']; ?></p>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Hình ảnh</label>
                                        <input id="inputFile" type="file" name="picture" class="form-control" /><br>
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
                                        <textarea name="preview" class="form-control" rows="4"></textarea>
                                        <?php if(isset($error['news_preview'])){ ?>
                                            <p class="text-error"><?php echo $error['news_preview']; ?></p>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Chi tiết</label>
                                        <textarea id="noidung" name="detail" class="form-control ckeditor" rows="10"></textarea>
                                        <?php if(isset($error['news_detail'])){ ?>
                                            <p class="text-error"><?php echo $error['news_detail']; ?></p>
                                        <?php } ?>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-success btn-md">Thêm</button>
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
                "preview": {
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
<!-- /. PAGE WRAPPER  -->
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/footer.php'; ?>
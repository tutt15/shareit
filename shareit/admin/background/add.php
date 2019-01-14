<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/header.php'; ?>
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/leftbar.php'; ?>

<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2>Thêm ảnh nền</h2>
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
                                    $image = $_FILES['image']['name'];
                                    $error = [];
                                    if($image == ''){
                                        $error['image'] = "Ảnh không được để trống";
                                    }
                                    if(empty($error)){
                                        $arName = explode('.',$image);
                                        $typeFile = end($arName);
                                        $newName = 'shareit-bg-'.time().'.'.$typeFile;
                                        $tmp_name = $_FILES['image']['tmp_name'];
                                        $path_root = $_SERVER['DOCUMENT_ROOT'];
                                        $path_upload = $path_root.'/files/'.$newName;
                                        $move_upload = move_uploaded_file($tmp_name,$path_upload);
                                        $queryAddBg = "INSERT INTO background(image) VALUES ('{$newName}')";
                                        $resultAddBg= $mysqli->query($queryAddBg);
                                        if($mysqli->affected_rows > 0){
                                            $_SESSION['success'] = "Thêm ảnh thành công";
                                            header('Location: /admin/background');
                                        }else{
                                            $_SESSION['error'] = "Thêm ảnh thất bại";
                                            header('Location: /admin/background');
                                        }
                                       
                                    }
                                }
                            ?>
                            <div class="col-md-12">
                                <form role="form" action="" method="post" enctype="multipart/form-data" >
                                    <div class="form-group">
                                        <label>Ảnh nền</label>
                                        <input id="inputFile" type="file" name="image" class="form-control" /><br>
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
                                        <?php if(isset($error['image'])){ ?>
                                            <p class="text-error"><?php echo $error['image']; ?></p>
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
<!-- /. PAGE WRAPPER  -->
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/footer.php'; ?>
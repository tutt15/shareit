<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/header.php'; ?>
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/leftbar.php'; ?>
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2>Thêm người dùng</h2>
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
                                    $user_email = $_POST['user_email'];
                                    $user_password = md5($_POST['user_password']);
                                    $user_fullname = $_POST['user_fullname'];
                                    $user_role = $_POST['user_role'];
                                    $user_avatar = $_FILES['avatar']['name'];
                                    $error = [];
                                    if($user_email == ''){
                                        $error['user_email'] = "email không được để trống";
                                    }
                                    if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)){
                                        $error['user_email'] = "Email không đúng định dạng";
                                    }
                                    if($user_password == ''){
                                        $error['user_password'] = "Password không được để trống";
                                    }
                                    if(strlen($user_password) < 6){
                                        $error['user_password'] = "Password phải có ít nhất 6 ký tự";
                                    }
                                    if($user_fullname == ''){
                                        $error['user_fullname'] = "Fullname không được để trống";
                                    }
                                    if($user_role == ''){
                                        $error['user_role'] = "Role không được để trống";
                                    }
                                    if(empty($error)){
                                        $queryCkUser = "SELECT * FROM user WHERE email = '{$user_email}' ";
                                        $resultCkUser = $mysqli->query($queryCkUser); 
                                        if($resultCkUser->num_rows > 0){
                                            $error['user_email'] = "email đã tồn tại";
                                        }else{
                                            if($user_avatar != ''){
                                                $arName = explode('.',$user_avatar);
                                                $typeFile = end($arName);
                                                $newName = 'shareit-avatar-'.time().'.'.$typeFile;
                                                $tmp_name = $_FILES['avatar']['tmp_name'];
                                                $path_root = $_SERVER['DOCUMENT_ROOT'];
                                                $path_upload = $path_root.'/files/'.$newName;
                                                $move_upload = move_uploaded_file($tmp_name,$path_upload);
                                                if($move_upload){
                                                    echo "Upload thành công";
                                                }else{
                                                    echo "Upload thất bại";
                                                }
                                            }
                                            $queryAddUser = "INSERT INTO user(email, password, fullname, avatar, role) VALUES ('{$user_email}','{$user_password}','{$user_fullname}','{$newName}','{$user_role}')";
                                            $resultAddUser = $mysqli->query($queryAddUser);
                                            if($mysqli->affected_rows > 0){
                                                $_SESSION['success'] = "Thêm người dùng thành công";
                                                header('Location: /admin/user');
                                            }else{
                                                $_SESSION['error'] = "Thêm người dùng thất bại";
                                                header('Location: /admin/user');
                                            }
                                        }
                                    }                          
                                }
                            ?>
                            <div class="col-md-12">
                                <form id="checkVal" role="form" action="" method="post" enctype="multipart/form-data" >
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="user_email" class="form-control" />
                                        <?php if(isset($error['user_email'])){ ?>
                                            <p class="text-error"><?php echo $error['user_email']; ?></p>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Mật khẩu</label>
                                        <input type="password" name="user_password" class="form-control" />
                                        <?php if(isset($error['user_password'])){ ?>
                                            <p class="text-error"><?php echo $error['user_password']; ?></p>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Tên đầy đủ</label>
                                        <input type="text" name="user_fullname" class="form-control" />
                                        <?php if(isset($error['user_fullname'])){ ?>
                                            <p class="text-error"><?php echo $error['user_fullname']; ?></p>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Ảnh đại diện</label>
                                        <input id="chooseimg" type="file" name="avatar" class="form-control" /><br>
                                        <img id="previewimg" src="" alt="" height="120px" width="120px">
                                        <script type="text/javascript">
                                            var file = document.getElementById('chooseimg');
                                            var img = document.getElementById('previewimg');
                                            file.addEventListener("change", function() {
                                                if (this.value) {
                                                    var file = this.files[0];
                                                    var reader = new FileReader();
                                                    reader.onloadend = function () {
                                                        img.src = reader.result;
                                                    };
                                                    reader.readAsDataURL(file);
                                                }
                                            });
                                        </script>
                                    </div>
                                    <div class="form-group">
                                        <label>Chức vụ</label>
                                        <select class="form-control" name="user_role" id="">
                                            <option value="">--Chọn chức vụ--</option>
                                            <option value="admin">admin</option>
                                            <option value="mod">mod</option>
                                            <option value="member">member</option>
                                        </select>
                                        <?php if(isset($error['user_role'])){ ?>
                                            <p class="text-error"><?php echo $error['user_role']; ?></p>
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
                "user_email": {
                    required: true,
                    email: true
                },
                "user_password": {
                    required: true,
                    minlength: 6
                },
                "user_fullname": {
                    required: true
                },
                "user_role": {
                    required: true
                }
            },
            messages: {
                "user_email": {
                    required: 'Không được để trống',
                    email: 'Phải đúng định dạng email'
                },
                "user_password": {
                    required: 'Không được để trống',
                    minlength: 'Phải có ít nhất 6 ký tự'
                },
                "user_fullname": {
                    required: 'Không được để trống'
                },
                "user_role": {
                    required: 'Không được để trống'
                }
            }
        });  
    });
</script>
<!-- /. PAGE WRAPPER  -->
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/footer.php'; ?>
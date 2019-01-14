<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/header.php'; ?>
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/leftbar.php'; ?>
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2>Sửa người dùng</h2>
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
                                $user_id = 0;
                                if(!isset($_GET['id']) || !preg_match("/^[0-9]+$/", $_GET['id'])){
                                    header('Location: /index.php');
                                    die();
                                }
                                $user_id = $_GET['id'];
                                $querySltUser = "SELECT * FROM user WHERE id = {$user_id}";
                                $resultSltUser = $mysqli->query($querySltUser);
                                $arUser = $resultSltUser->fetch_assoc();
                                if(empty($arUser)){
                                    $_SESSION['error'] = "Người dùng không tồn tại";
                                    header('Location: /admin/user');
                                    die();
                                }else{
                                    if($arUser['email'] == 'sharitthanhbui@gmail.com' && $_SESSION['userInfo']['email'] != 'sharitthanhbui@gmail.com'){
                                        $_SESSION['error'] = "Bạn không có quyền sửa người này";
                                        header('Location:/admin/user');
                                        die();
                                    }
                                    if($_SESSION['userInfo']['role'] != 'admin' && $arUser['role'] == 'mod' && $arUser['email'] != $_SESSION['userInfo']['email']){
                                        $_SESSION['error'] = "Bạn không có quyền sửa người này";
                                        header('Location:/admin/user');
                                        die();
                                    }
                                    // if($arUser['email'] == 'sharitthanhbui@gmail.com' && $_SESSION['userInfo']['email'] != 'sharitthanhbui@gmail.com'){
                                    //     $_SESSION['error'] = "Bạn không có quyền sửa người dùng này";
                                    //     header('Location: /admin/user');
                                    //     die();
                                    // }
                                } 
                                $user_email = $arUser['email'];
                                $user_password = $arUser['password'];
                                $user_fullname = $arUser['fullname'];
                                $user_avatar = $arUser['avatar'];
                                $user_role = $arUser['role'];
                                if(isset($_POST['submit'])){
                                    $user_password = $_POST['user_password'];
                                    $user_fullname = $_POST['user_fullname'];
                                    $user_role = $_POST['user_role'];
                                    if($_SESSION['userInfo']['role'] != 'admin'){
                                        if($arUser['role'] != $_POST['user_role']){
                                            $_SESSION['error'] = "Không được phép thay đổi chức vụ";
                                            header('Location: /admin/user');
                                            die();
                                        }
                                    }
                                    $error = [];
                                    if($user_fullname == ''){
                                        $error['user_fullname'] = "Tên đầy đủ không được để trống";
                                    }
                                    if(empty($error)){
                                        if($_FILES['newavatar']['name'] != ''){ 
                                            $path_root = $_SERVER['DOCUMENT_ROOT'];
                                            unlink($path_root.'/files/'.$user_avatar);
                                            $arName = explode('.',$_FILES['newavatar']['name']);
                                            $typeFile = end($arName);
                                            $user_avatar = 'shareit-avatar-'.time().'.'.$typeFile;
                                            $tmp_name = $_FILES['newavatar']['tmp_name'];
                                            $path_upload = $path_root.'/files/'.$user_avatar; 
                                            $move_upload = move_uploaded_file($tmp_name,$path_upload);
                                            if($move_upload){
                                                echo "Upload thành công";
                                            }else{
                                                echo "Upload thất bại";
                                            }                                      
                                        }else{ 
                                            if(isset($_POST['delavatar'])){ 
                                                $path_root = $_SERVER['DOCUMENT_ROOT'];
                                                unlink($path_root.'/files/'.$user_avatar);
                                                $user_avatar = '';
                                            }
                                        }
                                        $queryEditUser = "UPDATE user SET fullname = '{$user_fullname}',avatar = '{$user_avatar}', role = '{$user_role}'";  
                                        if($user_password == ''){
                                            $queryEditUser .= " WHERE id = {$user_id}";
                                        }else{
                                            if(strlen($user_password) < 6){
                                                $error['user_password'] = 'Mật khẩu phải có ít nhất 6 ký tự';
                                            }else{
                                                $user_password = md5($user_password);
                                                $queryEditUser .= ", password = '{$user_password}' WHERE id = {$user_id}";
                                            }
                                        }
                                        if(empty($error)){
                                            $resultEditUser = $mysqli->query($queryEditUser);
                                            if($mysqli->affected_rows > 0){
                                                $_SESSION['success'] = "Sửa người dùng thành công";
                                                header('Location: /admin/user');
                                            }else{
                                                $_SESSION['error'] = "Dữ liệu không thay đổi";
                                                header('Location: /admin/user');
                                            }
                                        }
                                    }     
                                }
                            ?>
                            <div class="col-md-12">
                                <form role="form" action="" method="post" enctype="multipart/form-data" >
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="user_email" value="<?php echo $user_email ?>" class="form-control" readonly />
                                    </div>
                                    <div class="form-group">
                                        <label>Mật khẩu</label>
                                        <input type="password" name="user_password" class="form-control" />
                                    </div>
                                    <div class="form-group">
                                        <label>Tên đầy đủ</label>
                                        <input type="text" name="user_fullname" value="<?php echo $user_fullname ?>" class="form-control" />
                                        <?php if(isset($error['user_fullname'])){ ?>
                                            <p class="text-error"><?php echo $error['user_fullname']; ?></p>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Ảnh đại diện củ</label><br />
                                        <?php
                                            if($user_avatar != '' ){
                                        ?>
                                        <img src="/files/<?php echo $user_avatar ?>" alt="" width="90px" height="90px"><br />
                                        <input type="checkbox" name="delavatar" value="delavatar" > Xóa hình
                                        <?php
                                            }else{
                                        ?>
                                            <span>Chưa có ảnh</span>
                                        <?php
                                            }
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Ảnh đại diện mới</label>
                                        <input id="chooseimg" type="file" name="newavatar" class="form-control" /><br>
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
                                            <?php
                                                if($user_role == 'admin' && $_SESSION['userInfo']['email'] == 'admin'){
                                                    echo '<option value="admin">admin</option>';
                                                }else{
                                                    if($_SESSION['userInfo']['role'] == 'admin'){
                                                        if($user_role == 'admin'){
                                                            echo '<option value="admin" selected>admin</option>';
                                                        }else{
                                                            echo '<option value="admin">admin</option>';
                                                        }    
                                                        if($user_role == 'mod'){
                                                            echo '<option value="mod" selected>mod</option>';
                                                        }else{
                                                            echo '<option value="mod">mod</option>';
                                                        }
                                                        if($user_role == 'member'){
                                                            echo '<option value="member" selected>member</option>';
                                                        }else{
                                                            echo '<option value="member">member</option>';
                                                        }
                                                    }else{
                                                        if($user_role == 'mod'){
                                                            echo '<option value="mod">mod</option>';
                                                        }else{
                                                            echo '<option value="member">member</option>';
                                                        }
                                                    }
                                                }
                                            ?>
                                        </select>
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
                "user_fullname": {
                    required: true
                }
            },
            messages: {
                "user_fullname": {
                    required: 'Không được để trống'
                }
            }
        });  
    });
</script>
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/footer.php'; ?>
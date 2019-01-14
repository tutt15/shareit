<?php
    session_start();
    ob_start();
    require_once $_SERVER['DOCUMENT_ROOT'].'/util/DBConnectionUtil.php';
?>
<?php
    $bg = array();
    $querySltImg = "SELECT * FROM background";
    $resultSltImg = $mysqli->query($querySltImg);
    while($arImg = $resultSltImg->fetch_assoc()){
        $bg[$arImg['id']] = $arImg['image'];
    }
    $key =  array_rand($bg);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="/templates/auth/css/font.css">
    <link rel="stylesheet" href="/templates/auth/css/normalize.min.css">
    <link rel="stylesheet" href="/templates/auth/css/style.css">
    <script src="/templates/auth/js/jquery.min.js"></script>
    <script src="/library/jquery/jquery.validate.min.js"></script>
    <style>
        body{
            background: url('/files/<?php echo $bg[$key] ?>');
            background-size:cover;
        }
    </style>
</head>
<body>
    <div class="form">
        <script>
            var val = '<?php if(isset($_GET['tab'])){echo $_GET['tab'];} ?>';
            if(val != ''){
                jQuery(function () {
                    $('.tab-content > div').not('#'+val).hide();
                    $('#'+val).fadeIn(600);
                });
            }
        </script>
        <ul class="tab-group">
            <li class="tab <?php if($_GET['tab'] != 'signup'){ echo 'active'; } ?>"><a href="#login">Đăng nhập</a></li>
            <li class="tab <?php if($_GET['tab'] == 'signup'){ echo 'active'; } ?>"><a href="#signup">Đăng ký</a></li> 
        </ul>
        <div class="tab-content">
            
            <div id="login">
                <h1>Xin mời đăng nhập</h1>
                <?php
                    if(isset($_POST['login'])){
                        $email = $_POST['email'];
                        $password = $_POST['password'];
                        $error = [];
                        if($email == ''){
                            $error['email'] = "Email không được để trống";
                        }
                        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                            $error['email'] = "Email không đúng định dạng";
                        }
                        if($password == ''){
                            $error['password'] = "Mật khẩu không được để trống";
                        }
                        if(empty($error)){
                            $email = mysqli_real_escape_string($mysqli,$email);
                            $password = mysqli_real_escape_string($mysqli,$password);
                            $password = md5($password);
                            $query = "SELECT * FROM user WHERE email = '$email' AND password = '$password' AND active = 1";
                            $result = $mysqli->query($query);
                            $arUser = $result->fetch_assoc();
                            if(is_array($arUser)){
                                $_SESSION['userInfo'] = $arUser;
                                // if($_SESSION['userInfo']['role'] == 'admin' || ($_SESSION['userInfo']['role'] == 'mod' && $_SESSION['userInfo']['active'] == 1)){
                                //     header('Location:/admin');
                                //     die();
                                // }   
                                header('Location:/');
                            }else{
                        
                ?>
                                <p class="noti-error">Tên đăng nhập hoặc mật khẩu sai (hoặc tài khoản bạn đã bị khóa)!</p>
                <?php
                            }
                        }
                    }
                ?>
                <form action="/auth/login.php?tab=login" method="post">
                    <div class="field-wrap">
                        <label>
                        Email<span class="req">*</span>
                        </label>
                        <input type="email" name="email"  autocomplete="off"/>
                        <?php if(isset($error['email'])){ ?>
                            <p class="text-error"><?php echo $error['email']; ?></p>
                        <?php } ?>
                    </div>
                    <div class="field-wrap">
                        <label>
                        Mật khẩu<span class="req">*</span>
                        </label>
                        <input type="password" name="password"  autocomplete="off"/>
                        <?php if(isset($error['password'])){ ?>
                            <p class="text-error"><?php echo $error['password']; ?></p>
                        <?php } ?>
                    </div>
                    <p class="forgot"><a href="/auth/forgotpw.php">Quên mật khẩu?</a></p>
                    <button name="login" class="button button-block">Đăng nhập</button>
                </form>
            </div>
            <div id="signup">
                <h1>Xin mời đăng ký</h1>
                <?php
                    if(isset($_POST['signup'])){
                        $fullname2 = $_POST['fullname2'];
                        $email2 = $_POST['email2'];
                        $password2 = $_POST['password2'];
                        $confirmpassword2 = $_POST['confirmpassword2'];
                        $error = [];
                        if($fullname2 == ''){
                            $error['fullname2'] = "Họ tên không được để trống";
                        }
                        if($email2 == ''){
                            $error['email2'] = "Email không được để trống";
                        }
                        if(!filter_var($email2, FILTER_VALIDATE_EMAIL)){
                            $error['email2'] = "Email không đúng định dạng";
                        }
                        if($password2 == ''){
                            $error['password2'] = "Mật khẩu không được để trống";
                        }
                        if(strlen($password2) < 6){
                            $error['password2'] = "Mật khẩu phải có ít nhất 6 ký tự";
                        }
                        if($confirmpassword2 == ''){
                            $error['confirmpassword2'] = "Mật khẩu nhập lại không được để trống";
                        }
                        if($password2 != $confirmpassword2){
                            $error['confirmpassword2'] = "Mật khẩu nhập lại không chính xác";
                        }
                        if(empty($error)){
                            $queryCkUser = "SELECT * FROM user WHERE email = '{$email2}' ";
                            $resultCkUser = $mysqli->query($queryCkUser); 
                            if($resultCkUser->num_rows > 0){
                                $error['email2'] = "email đã tồn tại";
                            }else{
                                $password2 = md5($password2);
                                $queryAddUser = "INSERT INTO user(email, password, fullname, role) VALUES ('$email2','$password2','$fullname2', 'member')";
                                $resultAddUser = $mysqli->query($queryAddUser);
                                if($mysqli->affected_rows > 0){
                                    echo '<p class="noti-error">Đăng ký thành công!</p>';
                                }else{
                                    echo '<p class="noti-error">Đăng ký thất bại!</p>';
                                }
                            }
                        }
                    }
                ?>
                <form id="checkVal" action="/auth/login.php?tab=signup" method="post">
                    <div class="field-wrap">
                        <label>
                        Họ tên<span class="req">*</span>
                        </label>
                        <input type="text" name="fullname2" autocomplete="off"/>
                        <?php if(isset($error['fullname2'])){ ?>
                            <p class="text-error"><?php echo $error['fullname2']; ?></p>
                        <?php } ?>
                    </div>
                    <div class="field-wrap">
                        <label>
                        Email<span class="req">*</span>
                        </label>
                        <input type="text" name="email2" autocomplete="off"/>
                        <?php if(isset($error['email2'])){ ?>
                            <p class="text-error"><?php echo $error['email2']; ?></p>
                        <?php } ?>
                    </div>
                    <div class="field-wrap">
                        <label>
                        Mật khẩu<span class="req">*</span>
                        </label>
                        <input type="password" id="password2" name="password2" autocomplete="off"/>
                        <?php if(isset($error['password2'])){ ?>
                            <p class="text-error"><?php echo $error['password2']; ?></p>
                        <?php } ?>
                    </div>
                    <div class="field-wrap">
                        <label>
                        Nhập lại mật khẩu<span class="req">*</span>
                        </label>
                        <input type="password" name="confirmpassword2" autocomplete="off"/>
                        <?php if(isset($error['confirmpassword2'])){ ?>
                            <p class="text-error"><?php echo $error['confirmpassword2']; ?></p>
                        <?php } ?>
                    </div>
                    <button name="signup" class="button button-block">Đăng ký</button>
                </form>
            </div>
        </div>
        <!-- tab-content -->
    </div>
    <!-- /form -->
<script type="text/javascript">
    $(document).ready(function(){
        $('#checkVal').validate({
            rules:{
                "fullname2": {
                    required: true
                },
                "email2": {
                    required: true,
                    email: true
                },
                "password2": {
                    required: true
                },
                "confirmpassword2": {
                    required: true,
                    equalTo: '#password2'
                }
            },
            messages: {
                "fullname2": {
                    required: 'Họ tên không được để trống'
                },
                "email2": {
                    required: 'Email không được để trống',
                    email: 'Email phải đúng định dạng'
                },
                "password2": {
                    required: 'Mật khẩu không được để trống'
                },
                "confirmpassword2": {
                    required: 'Mật khẩu nhập lại không được để trống',
                    equalTo: 'Mật khẩu nhập lại không chính xác'
                }
            },
            errorElement: 'p'
        });
    });
</script>
</body>
<script src="/templates/auth/js/login.js"></script>
</html>
<?php
    ob_end_flush();
?>
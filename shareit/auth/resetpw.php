<?php
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset password</title>
    <link rel="stylesheet" href="/templates/auth/css/font.css">
    <link rel="stylesheet" href="/templates/auth/css/style.css">
    <script src="/templates/auth/js/jquery.min.js"></script>
    <style>
        body{
            background: url('/files/<?php echo $bg[$key] ?>');
        }
    </style>
</head>
<body>
    <?php
        $success = [];
        $error = [];
        $email = isset($_GET['email']) ? $_GET['email'] : '';
        $token = isset($_GET['token']) ? $_GET['token'] : '';
        if($email == '' || $token == ''){
            header('Location: /');
            die();
        }
        $queryCkPw = "SELECT * FROM user WHERE email = '$email' AND token = '$token'";
        $resultCkPw = $mysqli->query($queryCkPw);
        if($resultCkPw->num_rows <= 0){
            header('Location: /');
            die();
        }
        if(isset($_POST['submit'])){
            $password = $_POST['password'];
            $confirmpw = $_POST['confirmpw'];
            $error = [];
            if(strlen($password) < 6){
                $error['password'] = 'Mật khẩu phải có ít nhất 6 ký tứ!';
            }
            if(empty($error)){
                if($password != $confirmpw){
                    $error['confirmpw'] = "Mật khẩu nhập lại không chính xác!";
                }else{
                    $password = md5($password);
                    $queryEditUser = "UPDATE user SET password = '$password' WHERE email = '$email'";
                    $resultEditUser = $mysqli->query($queryEditUser);
                    if($mysqli->affected_rows > 0){
                        $success['pw'] = 'Đổi mật khẩu thành công!';
                    }else{
                        $success['pw'] = 'Mật khẩu không thay đổi!';
                    }
                }
            }          
        }
    ?>
    <div class="form">
        <div class="tab-content">
            <h1>Đặt lại mật khẩu</h1>
            <?php if(isset($success['pw'])){ ?>
                <a class="noti" href="/login">Quay lại trang đăng nhập</a>
                <p><?php echo $success['pw'] ?></p>
            <?php } ?>
            <form action="" method="post">
                <div class="field-wrap">
                    <label>
                    Mật khẩu mới<span class="req">*</span>
                    </label>
                    <input type="password" name="password" required autocomplete="off"/>
                    <?php if(isset($error['password'])){ ?>
                        <p class="text-error"><?php echo $error['password']; ?></p>
                    <?php } ?>
                </div>
                <div class="field-wrap">
                    <label>
                    Nhập lại mật khẩu<span class="req">*</span>
                    </label>
                    <input type="password" name="confirmpw" required autocomplete="off"/>
                    <?php if(isset($error['confirmpw'])){ ?>
                        <p class="text-error"><?php echo $error['confirmpw']; ?></p>
                    <?php } ?>
                </div>
                <button name="submit" class="button button-block">Gửi</button>
            </form>
        </div>
    </div>
</body>
<script src="/templates/auth/js/login.js"></script>
</html>
<?php
    ob_end_flush();
?>
<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/util/DBConnectionUtil.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/util/sendEmail.php';
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
        $email = '';
        if(isset($_POST['reset'])){
            $email = $_POST['email'];
            $error = [];
            $success = [];
            if($email == ''){
                $error['email'] = "Email không được để trống!";
            } 
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $error['email'] = "Email không đúng định dạng";
            }
            if(empty($error)){
                $queryCkUser = "SELECT * FROM user WHERE email = '$email'";
                $resultCkUser = $mysqli->query($queryCkUser);
                $arUser = $resultCkUser->fetch_assoc();
                $token = substr(md5(rand(0,10000)),0,16);
                if(!empty($arUser)){
                    $queryEditToken = "UPDATE user SET token = '$token' WHERE email = '$email'";
                    $resultEditToken = $mysqli->query($queryEditToken);
                    $flag = $mysqli->affected_rows;
                    if($flag > 0){
                        $title = "Lấy lại mật khẩu SHAREIT";
                        $content = "<h2>Lấy lại mật khẩu</h2><br />Chúng tôi đã nhận được yêu cầu thay đổi mật khẩu. Nếu bạn muốn thay đổi thay đổi mật khẩu hãy click vào link này <a href='http://shareit.vne/auth/resetpw.php?email=".$email."&token=".$token."' target='_blank'>Thay đổi mật khẩu</a>";
                        $nTo = "From Shareit";
                        $mTo = $email;
                        $mail = sendMail($title, $content, $nTo, $mTo, $diachicc='');
                        if($mail = 0){
                            $error['email'] = "Lỗi gửi email!";
                        }else{
                            $success['email'] = "Thành công! Vui lòng kiểm tra email.";
                        }
                    }
                }else{
                    $error['email'] = "Không tồn tại email!";
                }   
            }
        }
    ?>
    <div class="form">
        <div class="tab-content">
            <h1>Quên mật khẩu</h1>
            <?php if(isset($success['email'])){ ?>
                <a class="noti" href="/login">Quay lại trang đăng nhập</a>
                <p><?php echo $success['email'] ?></p>
            <?php } ?>
            <form action="" method="post">
                <div class="field-wrap">
                    <label>
                    Email<span class="req">*</span>
                    </label>
                    <input type="email" name="email" required autocomplete="off"/>
                    <?php if(isset($error['email'])){ ?>
                        <p class="text-error"><?php echo $error['email']; ?></p>
                    <?php } ?>
                </div>
                <button name="reset" class="button button-block">Gửi</button>
            </form>
        </div>
    </div>
</body>
<script src="/templates/auth/js/login.js"></script>
</html>
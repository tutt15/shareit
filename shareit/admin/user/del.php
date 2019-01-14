<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/header.php'; ?>

<?php
    $user_id = 0;
    if(!isset($_GET['id']) || !preg_match("/^[0-9]+$/", $_GET['id'])){
        header('Location:/admin/user');
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
        if($arUser['role'] == 'admin'){
            $_SESSION['error'] = "Bạn không có quyền xóa người này";
            header('Location:/admin/user');
            die();
        }
        if($_SESSION['userInfo']['role'] != 'admin' && $arUser['role'] == 'mod' && $arUser['email'] != $_SESSION['userInfo']['email']){
            $_SESSION['error'] = "Bạn không có quyền xóa người này";
            header('Location:/admin/user');
            die();
        }
        // if($arUser['email'] == 'sharitthanhbui@gmail.com' && $_SESSION['userInfo']['email'] != 'sharitthanhbui@gmail.com'){
        //     $_SESSION['error'] = "Bạn không có quyền xóa người dùng này";
        //     header('Location: /admin/user');
        //     die();
        // }
    }

    $querySltPic = "SELECT picture FROM news WHERE created_by = {$user_id}";
    $resultSltPic = $mysqli->query($querySltPic);
    if($resultSltPic->num_rows > 0){
        while($arPic = $resultSltPic->fetch_assoc()){
            if($arPic['picture'] != ''){
                $filePath = $_SERVER['DOCUMENT_ROOT'].'/files/'.$arPic['picture'];
                unlink($filePath);
            }
        }
    }

    $queryDelUser = "DELETE user, news, comment FROM ((user LEFT JOIN comment ON user.id = comment.user_id) LEFT JOIN news ON user.id = news.created_by) WHERE user.id = {$user_id}";
    $resultDelUser = $mysqli->query($queryDelUser);
    if($mysqli->affected_rows > 0){
        $_SESSION['success'] = "Xóa người dùng thành công";
        header('Location:/admin/user');
    }else{
        $_SESSION['error'] = "Xóa người dùng thất bại";
        header('Location:/admin/user');
    }
?>

<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/header.php'; ?>
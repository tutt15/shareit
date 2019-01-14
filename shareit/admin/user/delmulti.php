<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/header.php'; ?>
<?php
    if(isset($_POST['delMultiUser'])){
        $arUser_id = $_POST['delUser'];
        foreach($arUser_id as $user_id){
            
            $queryCkUser = "SELECT * FROM user WHERE id = $user_id";
            $resultCkUser = $mysqli->query($queryCkUser);
            $user = $resultCkUser->fetch_assoc();
            if(empty($user)){
                $_SESSION['error'] = "Người dùng không tồn tại";
                header('Location:/admin/user');
                die();
            }else{
                if($user['role'] == 'admin'){
                    $_SESSION['error'] = "Bạn không có quyền xóa người này";
                    header('Location:/admin/user');
                    die();
                }
                if($_SESSION['userInfo']['role'] != 'admin' && $user['role'] == 'mod' && $user['email'] != $_SESSION['userInfo']['email']){
                    $_SESSION['error'] = "Bạn không có quyền xóa người này";
                    header('Location:/admin/user');
                    die();
                }
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
        }
        if($mysqli->affected_rows > 0){
            $_SESSION['success'] = "Xóa người dùng thành công";
            header('Location:/admin/user');
        }else{
            $_SESSION['error'] = "Xóa người dùng thất bại";
            header('Location:/admin/user');
        }
    }
?>
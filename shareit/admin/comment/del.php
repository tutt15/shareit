<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/header.php'; ?>

<?php
    $cmt_id = 0;
    if(!isset($_GET['id']) || !preg_match("/^[0-9]+$/", $_GET['id'])){
        header('Location:/admin/comment');
        die();
    }
    $cmt_id = $_GET['id'];
    $querySltCmt = "SELECT * FROM comment INNER JOIN user ON comment.user_id = user.id WHERE comment.id = {$cmt_id}";
    $resultSltCmt = $mysqli->query($querySltCmt);
    $arCmt = $resultSltCmt->fetch_assoc();
    if(empty($arCmt)){
        $_SESSION['error'] = "Bình luận không tồn tại";
        header('Location: /admin/comment');
        die();
    }else{
        if($arCmt['user_id'] != $_SESSION['userInfo']['id'] && $_SESSION['userInfo']['role'] != 'admin' && $arCmt['role'] != 'member'){
            $_SESSION['error'] = "Bạn không có quyền xóa tin này";
            header('Location: /admin/news');
            die();
        }
    }
    $queryDelSubCmt = "DELETE FROM comment WHERE parent_id = $cmt_id";
    $mysqli->query($queryDelSubCmt);
    $queryDelCmt = "DELETE FROM comment WHERE id = {$cmt_id}";
    $resultDelCmt = $mysqli->query($queryDelCmt);
    if($mysqli->affected_rows > 0){
        $_SESSION['success'] = "Xóa bình luận thành công";
        header('Location:/admin/comment');
    }else{
        $_SESSION['error'] = "Xóa bình luận thất bại";
        header('Location:/admin/comment');
    }
?>

<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/header.php'; ?>
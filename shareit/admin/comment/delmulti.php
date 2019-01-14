<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/header.php'; ?>
<?php
    if(isset($_POST['delMultiComment'])){
        $arCmt_id = $_POST['delComment'];
        foreach($arCmt_id as $cmt_id){
            $queryDelSubCmt = "DELETE FROM comment WHERE parent_id = $cmt_id";
            $mysqli->query($queryDelSubCmt);
            $query = "DELETE FROM comment WHERE id = {$cmt_id}";
            $result = $mysqli->query($query);
        }
        if($mysqli->affected_rows > 0){
            $_SESSION['success'] = "Xóa bình luận thành công";
            header('Location:/admin/comment');
        }else{
            $_SESSION['error'] = "Xóa các bình luận thất bại";
            header('Location:/admin/comment');
        }
    }
?>
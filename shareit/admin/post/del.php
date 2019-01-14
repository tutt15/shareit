<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/header.php'; ?>

<?php
    $news_id = 0;
    if(!isset($_GET['id']) || !preg_match("/^[0-9]+$/", $_GET['id'])){
        header('Location:/admin/post');
        die();
    }
    $news_id = $_GET['id'];
    $querySltNews = "SELECT created_by, picture, role FROM news INNER JOIN user ON news.created_by = user.id WHERE news.id = {$news_id}";
    $resultSltNews = $mysqli->query($querySltNews);
    $arNews = $resultSltNews->fetch_assoc();
    if(empty($arNews)){
        $_SESSION['error'] = "Tin không tồn tại";
        header('Location: /admin/post');
        die();
    }else{
        if($arNews['created_by'] != $_SESSION['userInfo']['id'] && $_SESSION['userInfo']['role'] != 'admin' && $arNews['role'] != 'member'){
            $_SESSION['error'] = "Bạn không có quyền xóa tin này";
            header('Location: /admin/post');
            die();
        }
    }
    if($arNews['picture'] != ''){
        $filePath = $_SERVER['DOCUMENT_ROOT'].'/files/'.$arNews['picture'];
        unlink($filePath);
    }
    $queryDelNews = "DELETE news, comment FROM news LEFT JOIN comment ON news.id = comment.news_id WHERE news.id = {$news_id}";
    $resultDelNews = $mysqli->query($queryDelNews);
    if($mysqli->affected_rows > 0){
        $_SESSION['success'] = "Xóa tin thành công";
        header('Location:/admin/post');
    }else{
        $_SESSION['error'] = "Xóa tin thất bại";
        header('Location:/admin/post');
    }
?>

<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/header.php'; ?>
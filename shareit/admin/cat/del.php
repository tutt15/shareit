<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/header.php'; ?>
<?php
    if($_SESSION['userInfo']['role'] != 'admin'){
        $_SESSION['error'] = "Bạn không có quyền xóa";
        header('Location: /admin/cat');
        die();
    }    
?>
<?php
    //intval
    $cat_id = 0;
    if(!isset($_GET['id']) || !preg_match("/^[0-9]+$/", $_GET['id'])){
        header('Location: /admin/cat');
        die();
    }
    $cat_id = $_GET['id'];
    $querySltCat = "SELECT * FROM cat WHERE id = {$cat_id}";
    $resultSltCat = $mysqli->query($querySltCat);
    $arCat = $resultSltCat->fetch_assoc();
    if(empty($arCat)){
        $_SESSION['error'] = "Danh mục không tồn tại";
        header('Location: /admin/cat');
        die();
    }
    // $queryDelCmt = "DELETE c.* FROM news n, comment c WHERE n.id = c.news_id AND n.cat_id = {$cat_id}";
    // $queryDelCmt = "DELETE news, comment FROM news LEFT JOIN comment ON news.id = comment.news_id WHERE cat_id = {$cat_id}";
    // $queryDelCat = "DELETE cat, news FROM cat LEFT JOIN news ON cat.id = news.cat_id WHERE cat.id = {$cat_id}";
    $querySltPic = "SELECT picture FROM news WHERE id = {$cat_id}";
    $resultSltPic = $mysqli->query($querySltPic);
    if($resultSltPic->num_rows > 0){
        while($arPic = $resultSltPic->fetch_assoc()){
            if($arPic['picture'] != ''){
                $filePath = $_SERVER['DOCUMENT_ROOT'].'/files/'.$arPic['picture'];
                unlink($filePath);
            }
        }
    }
    $queryDelSubCat = "DELETE FROM cat WHERE parent_id = $cat_id";
    $mysqli->query($queryDelSubCat);
    $queryDelCat = "DELETE cat, news, comment FROM ((news LEFT JOIN comment ON news.id = comment.news_id) RIGHT JOIN cat ON news.cat_id = cat.id) WHERE cat.id = {$cat_id}";
    $resultDelCat = $mysqli->query($queryDelCat);
    if($mysqli->affected_rows > 0){
        $_SESSION['success'] .= "Xóa danh mục thành công";
        header('Location:/admin/cat');
    }else{
        $_SESSION['error'] .= "Xóa danh mục thất bại";
        header('Location:/admin/cat');
    }
?>
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/footer.php'; ?>
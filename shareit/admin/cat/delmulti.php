<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/header.php'; ?>
<?php
    if($_SESSION['userInfo']['role'] != 'admin'){
        $_SESSION['error'] = "Bạn không có quyền Xóa";
        header('Location: /admin/cat');
        die();
    }    
?>
<?php
    if(isset($_POST['delMultiCat'])){
        $arCat_id = $_POST['delCat'];
        foreach($arCat_id as $cat_id){
            $querySltPic = "SELECT picture FROM news WHERE cat_id = {$cat_id}";
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
            $query = "DELETE cat, news, comment FROM ((news LEFT JOIN comment ON news.id = comment.news_id) RIGHT JOIN cat ON news.cat_id = cat.id) WHERE cat.id = {$cat_id}";
            $result = $mysqli->query($query);
        }
        if($mysqli->affected_rows > 0){
            $_SESSION['success'] = "Xóa danh mục thành công";
            header('Location:/admin/cat');
        }else{
            $_SESSION['error'] = "Xóa danh mục thất bại";
            header('Location:/admin/cat');
        }
    }
?>
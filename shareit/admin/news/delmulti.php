<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/header.php'; ?>
<?php
    if(isset($_POST['delMultiNews'])){
        $arNews_id = $_POST['delNews'];
        foreach($arNews_id as $news_id){
            $querySltNews = "SELECT created_by, picture, role FROM news INNER JOIN user ON news.created_by = user.id WHERE news.id = {$news_id}";
            $resultSltNews = $mysqli->query($querySltNews);
            $arNews = $resultSltNews->fetch_assoc();
            if(empty($arNews)){
                $_SESSION['error'] = "Tin không tồn tại";
                header('Location: /admin/news');
                die();
            }else{
                if($arNews['created_by'] != $_SESSION['userInfo']['id'] && $_SESSION['userInfo']['role'] != 'admin'){
                    $_SESSION['error'] = "Bạn không có quyền xóa tin này";
                    header('Location: /admin/news');
                    die();
                }
            }
            $querySltPic = "SELECT picture FROM news WHERE id = {$news_id}";
            $resultSltPic = $mysqli->query($querySltPic);
            if($resultSltPic->num_rows > 0){
                while($arPic = $resultSltPic->fetch_assoc()){
                    if($arPic['picture'] != ''){
                        $filePath = $_SERVER['DOCUMENT_ROOT'].'/files/'.$arPic['picture'];
                        unlink($filePath);
                    }
                }
            }

            $query = "DELETE news, comment FROM news LEFT JOIN comment ON news.id = comment.news_id WHERE news.id = {$news_id}";
            $result = $mysqli->query($query);
        }
        if($mysqli->affected_rows > 0){
            $_SESSION['success'] = "Xóa tin thành công";
            header('Location:/admin/news');
        }else{
            $_SESSION['error'] = "Xóa tin thất bại";
            header('Location:/admin/news');
        }
    }
?>
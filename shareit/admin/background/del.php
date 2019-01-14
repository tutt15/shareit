<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/header.php'; ?>

<?php
    //intval
    $bg_id = 0;
    if(!isset($_GET['id']) || !preg_match("/^[0-9]+$/", $_GET['id'])){
        header('Location: /admin/background');
        die();
    }
    $bg_id = $_GET['id'];
    $querySltBg = "SELECT * FROM background WHERE id = {$bg_id}";
    $resultSltBg = $mysqli->query($querySltBg);
    $arBg = $resultSltBg->fetch_assoc();
    if(empty($arBg)){
        $_SESSION['error'] = "Ảnh nền không tồn tại";
        header('Location: /admin/background');
        die();
    }
    $filePath = $_SERVER['DOCUMENT_ROOT'].'/files/'.$arBg['image'];
    unlink($filePath);
    $queryDelBg = "DELETE FROM background WHERE id= $bg_id";
    $mysqli->query($queryDelBg);
    if($mysqli->affected_rows > 0){
        $_SESSION['success'] .= "Xóa ảnh nền thành công";
        header('Location:/admin/background');
    }else{
        $_SESSION['error'] .= "Xóa ảnh nền thất bại";
        header('Location:/admin/background');
    }
?>
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/footer.php'; ?>
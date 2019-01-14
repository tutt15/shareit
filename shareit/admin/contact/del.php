<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/header.php'; ?>
<?php
    if($_SESSION['userInfo']['role'] != 'admin'){
        header('Location: /admin');
        die();
    }
?>
<?php
    $contact_id = 0;
    if(!isset($_GET['id']) || !preg_match("/^[0-9]+$/", $_GET['id'])){
        header('Location:/admin/contact');
        die();
    }
    $contact_id = $_GET['id'];
    $querySltContact = "SELECT * FROM contact WHERE id = {$contact_id}";
    $resultSltContact = $mysqli->query($querySltContact);
    $arContact = $resultSltContact->fetch_assoc();
    if(empty($arContact)){
        $_SESSION['error'] = "Liên hệ không tồn tại";
        header('Location: /admin/contact');
        die();
    }
    $queryDelContact = "DELETE FROM contact WHERE id = {$contact_id}";
    $resultDelContact = $mysqli->query($queryDelContact);
    if($mysqli->affected_rows > 0){
        $_SESSION['success'] = "Xóa liên hệ thành công";
        header('Location:/admin/contact');
    }else{
        $_SESSION['error'] = "Xóa liên hệ thất bại";
        header('Location:/admin/contact');
    }
?>

<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/header.php'; ?>
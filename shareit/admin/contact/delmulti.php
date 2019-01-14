<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/header.php'; ?>
<?php
    if(isset($_POST['delMultiContact'])){
        $arContact_id = $_POST['delContact'];
        foreach($arContact_id as $contact_id){
            $query = "DELETE FROM contact WHERE id = {$contact_id}";
            $result = $mysqli->query($query);
        }
        if($mysqli->affected_rows > 0){
            $_SESSION['success'] = "Xóa liên hệ thành công";
            header('Location:/admin/contact');
        }else{
            $_SESSION['error'] = "Xóa liên hệ thất bại";
            header('Location:/admin/contact');
        }
    }
?>
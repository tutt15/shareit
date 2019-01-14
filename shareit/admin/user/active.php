<?php require_once $_SERVER['DOCUMENT_ROOT'].'/util/DBConnectionUtil.php'; ?>
<?php
	$vitri = $_POST['avitri'];
	$trangthai = $_POST['atrangthai'];
	if($trangthai == 'deactive'){
        $trangthai = 'active';
        $active = 1;
	}else{
        $trangthai = 'deactive';
        $active = 0;
	}
    
        $user_id = substr($vitri, 3);
        // $error = [];
        // $queryCkUser = "SELECT * FROM user WHERE id = $user_id";
        // $resultCkUser = $mysqli->query($queryCkUser);
        // $user = $resultCkUser->fetch_assoc();
        // if(empty($user)){
        //     $error['user_id'] = 'Không tồn tại';
        // }else{
        //     if($arUser['role'] == 'admin' || ($arUser['role'] == 'mod' && $arUser['email'] != $_SESSION['userInfo']['email'])){
        //         $error['user_id'] = 'Không có quyền xóa';
        //     }
        // }
        // if(empty($error)){
            $query = "UPDATE user SET active = {$active} WHERE id = {$user_id}";
            $result = $mysqli->query($query);
            echo $trangthai;
        // }
?>
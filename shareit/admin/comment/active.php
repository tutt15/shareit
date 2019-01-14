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
    
        $cmt_id = substr($vitri, 3);
        $query = "UPDATE comment SET active = {$active} WHERE id = {$cmt_id}";
        $result = $mysqli->query($query);
        echo $trangthai;
?>
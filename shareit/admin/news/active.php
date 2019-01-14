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
    
        $news_id = substr($vitri, 3);
        $query = "UPDATE news SET active = {$active} WHERE id = {$news_id}";
        $result = $mysqli->query($query);
        echo $trangthai;
?>
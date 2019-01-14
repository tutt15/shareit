<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/header.php'; ?>
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/leftbar.php'; ?>
<?php
    if($_SESSION['userInfo']['role'] != 'admin'){
        $_SESSION['error'] = "Bạn không có quyền thêm";
        header('Location: /admin/cat');
        die();
    } 
?>
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2>Thêm danh mục</h2>
            </div>
        </div>
        <!-- /. ROW  -->
        
        <div class="row">
            <div class="col-md-12">
                <!-- Form Elements -->
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <?php
                                if(isset($_POST['submit'])){
                                    $cat_name = $_POST['cat_name'];
                                    $parent_id = $_POST['parent_id'];
                                    $error = [];
                                    if($cat_name == ''){
                                        $error['cat_name'] = "Tên danh mục không được để trống";
                                    }
                                    if($parent_id == ''){
                                        $error['cat_parent_id'] = "Danh mục cha không được để trống";
                                    }
                                    if(empty($error)){
                                        $querySltCat = "SELECT * FROM cat WHERE name = '{$cat_name}'";
                                        $resultSltCat = $mysqli->query($querySltCat);
                                        if($resultSltCat->num_rows > 0){
                                            $error['cat_name'] = "Tên danh mục đã tồn tại";
                                        }else{
                                            $queryAddCat = "INSERT INTO cat(name, parent_id) VALUES ('{$cat_name}',{$parent_id})";
                                            $resultAddCat = $mysqli->query($queryAddCat);
                                            if($mysqli->affected_rows > 0){
                                                $_SESSION['success'] = "Thêm danh mục thành công";
                                                header('Location: /admin/cat');
                                            }else{
                                                $_SESSION['error'] = "Thêm danh mục thất bại";
                                                header('Location: /admin/cat');
                                            }
                                        }
                                    }
                                }
                            ?>
                            <div class="col-md-12">
                                <form role="form" action="" method="post" >
                                    <div class="form-group">
                                        <label>Tên danh mục</label>
                                        <input type="text" name="cat_name" class="form-control" required />
                                        <?php if(isset($error['cat_name'])){ ?>
                                            <p class="text-error"><?php echo $error['cat_name']; ?></p>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Danh mục cha</label>
                                        <?php
                                            $querySltCat = "SELECT * FROM cat";
                                            $resultSltCat = $mysqli->query($querySltCat);
                                            $categories = array();
                                            while($arCat = $resultSltCat->fetch_assoc()){
                                                $categories[] = $arCat;
                                            }
                                            function showCategories($categories, $parent_id = 0, $char = '')
                                            {
                                                foreach ($categories as $key => $item)
                                                {
                                                    if ($item['parent_id'] == $parent_id)
                                                    {
                                                        echo '<option value="'.$item[id].'">';
                                                            echo $char . $item['name'];
                                                        echo '</option>';
                                                        unset($categories[id]);
                                                        showCategories($categories, $item['id'], $char.'|---');
                                                    }
                                                }
                                            }
                                        ?>
                                        <select name="parent_id" class="form-control" required >
                                            <option value="">--Chọn danh mục--</option>
                                            <option value="0">Không</option>
                                           
                                            <?php showCategories($categories); ?>
                                        </select>
                                        <?php if(isset($error['cat_parent_id'])){ ?>
                                            <p class="text-error"><?php echo $error['cat_parent_id']; ?></p>
                                        <?php } ?>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-success btn-md">Thêm</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- End Form Elements -->
            </div>
        </div>
        <!-- /. ROW  -->
    </div>
    <!-- /. PAGE INNER  -->
</div>
<!-- /. PAGE WRAPPER  -->
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/footer.php'; ?>
<?php
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'].'/util/DBConnectionUtil.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/util/ConstantUtil.php';
    $search = ' ';
    if(isset($_POST["search"]))  
    {  
      $search = $_POST["search"];  
    }
    $queryTSD = "SELECT COUNT(*) AS TSD FROM cat WHERE parent_id = 0 AND name LIKE '%$search%'";
    $resultTSD = $mysqli->query($queryTSD);
    $arTmp = $resultTSD->fetch_assoc();
    $total_pages = $arTmp['TSD'];
    $row_count  = ROW_COUNT;
    $total_pages = ceil($total_pages / $row_count);
    $current_page = 1;
    if(isset($_POST['page'])){
        $current_page = $_POST['page'];
    }
    $offset = ($current_page - 1) * $row_count;
?>
<script>
    $(document).ready(function(){
        $("#checkAllId").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    });
</script>
<form action="/admin/cat/delMulti.php" method="POST">
<table class="table table-striped table-bordered table-hover" id="dataTables-example">
    <thead>
        <tr>
            <?php
                if($_SESSION['userInfo']['role'] == 'admin'){
            ?>
            <th class="text-center"><input type="checkbox" id="checkAllId"></th>
            <?php
                }
            ?>
            <th>ID</th>
            <th>Tên danh mục</th>
            <?php if($_SESSION['userInfo']['role'] == 'admin'){ ?>
            <th width="160px">Chức năng</th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php
            $querySltCat = "SELECT * FROM cat WHERE parent_id = 0 AND name LIKE '%$search%' LIMIT {$offset},{$row_count}";
            $resultSltCat = $mysqli->query($querySltCat);
            if($mysqli->affected_rows > 0){
                while($arCat = mysqli_fetch_assoc($resultSltCat)){
                    $cat_id = $arCat['id'];
                    $cat_name = $arCat['name'];
                    $cat_parent_id  = $arCat['parent_id'];
        ?>
        <tr class="gradeX">
            <?php
                if($_SESSION['userInfo']['role'] == 'admin'){
            ?>
            <td class="text-center"><input type="checkbox" class="ckdelCat" name="delCat[]" value="<?php echo $cat_id ?>" ></td>
            <?php
                }
            ?>
            <td><?php echo $cat_id ?></td>
            <td>
                <?php echo $cat_name ?>
                <ul class="list-cat">
                <?php
                    $querySltSubCat = "SELECT * FROM cat WHERE parent_id = {$cat_id} ";
                    $resultSltSubCat = $mysqli->query($querySltSubCat);
                    if($resultSltSubCat->num_rows > 0){
                        while($arSubCat = $resultSltSubCat->fetch_assoc()){
                            $subcat_id = $arSubCat['id'];
                            $subcat_name = $arSubCat['name'];
                ?>
                    <li>
                        <?php echo $subcat_id ?> | <?php echo $subcat_name ?> 
                        <?php 
                            if($_SESSION['userInfo']['role'] == 'admin'){
                        ?>
                            | <a href="/admin/cat/edit.php?id=<?php echo $subcat_id ?>" class="btn btn-primary btn-xs" >Sửa</a> <a href="/admin/cat/del.php?id=<?php echo $subcat_id ?>" onclick="return confirm('Bạn có chắc chắn xóa danh mục không (xóa cả bài viết, bình luận)?')" class="btn btn-danger btn-xs" >Xóa</a>
                        <?php
                            }
                        ?>
                    </li>
                <?php
                        }
                    }
                ?>
                </ul>
            </td>
            <?php
                if($_SESSION['userInfo']['role'] == 'admin'){
            ?>
            <td class="center">
                <a href="/admin/cat/edit.php?id=<?php echo $cat_id ?>" title="" class="btn btn-primary"><i class="fa fa-edit "></i> Sửa</a>
                <a href="/admin/cat/del.php?id=<?php echo $cat_id ?>" title="" onclick="return confirm('Bạn có chắc chắn xóa danh mục không (xóa cả bài viết, bình luận)?')" class="btn btn-danger"><i class="fa fa-times"></i> Xóa</a>                                            
            </td>
            <?php
                }
            ?>
        </tr>
        <?php
                }
            }
        ?>
    </tbody>
</table>
<div class="row">
    <div class="col-sm-6">
        <div class="dataTables_info" id="dataTables-example_info">
            <?php
                if($_SESSION['userInfo']['role'] == 'admin'){
            ?>
            <input class="btn btn-warning btn-delmutil" onclick="return confirm('Bạn có chắc chắn xóa không? (xóa cả bài viết và bình luận)')" type="submit" name="delMultiCat" value="Xóa tất cả">
            <?php
                }
            ?>
        </div>
    </div>
    <div class="col-sm-6" style="text-align: right;">
        <div class="dataTables_paginate paging_simple_numbers" id="dataTables-example_paginate">
        <ul style="margin:0" class="pagination">
                <?php
                    for($i = 1; $i <= $total_pages; $i++){
                        if($i == $current_page){
                ?>
                <li class="paginate_button active" aria-controls="dataTables-example" tabindex="0"><a href="#"><?php echo $i ?></a></li>
                <?php
                        }else{
                ?>
                <li class="paginate_button " aria-controls="dataTables-example" tabindex="0"><a class="pagination_link" id="<?php echo $i ?>" href="javascript:void(0)"><?php echo $i ?></a></li>          
                <?php            
                        }
                    }
                ?>
            </ul>
        </div>
    </div>
</div>
</form>

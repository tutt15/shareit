<?php
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'].'/util/DBConnectionUtil.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/util/ConstantUtil.php';
    $search = ' ';
    if(isset($_POST["search"]))  
    {  
      $search = $_POST["search"];  
    }
?>
<script>
    $(document).ready(function(){
        $("#checkAllId").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    });
</script>
<!-- LIMIT {$offset},{$row_count} -->
<?php
    $querySltCat = "SELECT * FROM cat WHERE name LIKE '%$search%'";
    $resultSltCat = $mysqli->query($querySltCat);
    while($arCat = mysqli_fetch_assoc($resultSltCat)){
        $categories[] = $arCat;
    }
    function showCategories($categories, $parent_id = 0, $char = ''){
        foreach($categories as $key => $item){
            if($item['parent_id'] == $parent_id){
                echo '<tr>';
                    if($_SESSION['userInfo']['role'] == 'admin'){
                    echo '<td class="text-center">';
                        echo '<input type="checkbox" class="ckdelCat" name="delCat[]" value="'.$item['id'].'" >';
                    echo '</td>';
                    }
                    echo '<td>';
                        echo $item['id'];
                    echo '</td>';
                    echo '<td>';
                        echo $char . $item['name'];
                    echo '</td>';
                    if($_SESSION['userInfo']['role'] == 'admin'){
                    echo '<td class="center">';
                        echo '<a href="/admin/cat/edit.php?id='.$item['id'].'" title="" class="btn btn-primary"><i class="fa fa-edit "></i> Sửa</a> ';
                        echo '<a href="/admin/cat/del.php?id='.$item['id'].'" title="" onclick="return confirm(\'Bạn có chắc chắn xóa danh mục không (xóa cả bài viết, bình luận)?\')" class="btn btn-danger"><i class="fa fa-times"></i> Xóa</a>';
                    echo '</td>';
                    }
                echo '</tr>';
                unset($categories[$key]);
                showCategories($categories, $item['id'], $char.'|------- ');
            }
        }
    }
?>
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
        <tr class="gradeX">
            <?php
                showCategories($categories);
                // echo '<pre>';
                // print_r($categories);
                // echo '</pre>';
            ?>
        </tr>
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
</div>
</form>

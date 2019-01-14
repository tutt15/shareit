<?php
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'].'/util/DBConnectionUtil.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/util/ConstantUtil.php';
    $search = ' ';
    if(isset($_POST["search"]))  
    {  
      $search = $_POST["search"];  
    }
    if(isset($_POST["active"]))  
    {  
      $active = $_POST["active"];  
    }
    if($active == ''){
        $queryTSD = "SELECT COUNT(*) AS TSD FROM ((news INNER JOIN cat ON news.cat_id = cat.id) INNER JOIN user ON news.created_by = user.id) WHERE (news.name LIKE '%$search%' OR cat.name LIKE '%$search%' OR user.fullname LIKE '%$search%' OR user.role LIKE '%$search%') AND user.role = 'member'";        
    }else{
        $queryTSD = "SELECT COUNT(*) AS TSD FROM ((news INNER JOIN cat ON news.cat_id = cat.id) INNER JOIN user ON news.created_by = user.id) WHERE (news.name LIKE '%$search%' OR cat.name LIKE '%$search%' OR user.fullname LIKE '%$search%' OR user.role LIKE '%$search%') AND user.role = 'member' AND news.active = {$active}";
    }
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
<form action="/admin/post/delMulti.php" method="POST">
<table class="table table-striped table-bordered table-hover" id="dataTables-example">
    <thead>
        <tr>
            <th class="text-center"><input type="checkbox" id="checkAllId"></th>
            <th>ID</th>
            <th>Tên tin</th>
            <th>Danh mục</th>
            <th>Tác giả</th>
            <th>Chức vụ</th>
            <th>Lượt đọc</th>
            <th>Hình ảnh</th>
            <th>Trạng thái</th>
            <th width="160px">Chức năng</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if($active == ''){
                $querySltNews = "SELECT news.id AS news_id, news.name AS news_name, cat.name AS cat_name, email, fullname, role, picture, view, news.active AS news_active FROM ((news INNER JOIN cat ON news.cat_id = cat.id) INNER JOIN user ON news.created_by = user.id) WHERE (news.name LIKE '%$search%' OR cat.name LIKE '%$search%' OR user.fullname LIKE '%$search%' OR user.role LIKE '%$search%') AND user.role = 'member' ORDER BY news.id DESC LIMIT {$offset},{$row_count}";
            }else{
                $querySltNews = "SELECT news.id AS news_id, news.name AS news_name, cat.name AS cat_name, email, fullname, role, picture, view, news.active AS news_active FROM ((news INNER JOIN cat ON news.cat_id = cat.id) INNER JOIN user ON news.created_by = user.id) WHERE (news.name LIKE '%$search%' OR cat.name LIKE '%$search%' OR user.fullname LIKE '%$search%' OR user.role LIKE '%$search%') AND user.role = 'member' AND news.active = {$active} ORDER BY news.id DESC LIMIT {$offset},{$row_count}";
            }
            $resultSltNews = $mysqli->query($querySltNews);
            if($mysqli->affected_rows > 0){
                while($arNews = mysqli_fetch_assoc($resultSltNews)){
                    $news_id = $arNews['news_id'];
                    $news_name = $arNews['news_name'];
                    $cat_name = $arNews['cat_name'];
                    $email = $arNews['email'];
                    $fullname = $arNews['fullname'];
                    $role = $arNews['role'];
                    $picture = $arNews['picture'];
                    $view = $arNews['view'];
                    $news_active = $arNews['news_active'];
        ?>
        <tr class="gradeX">
            <?php
                if($_SESSION['userInfo']['role'] == 'admin' || $email == $_SESSION['userInfo']['email']){
            ?>
            <td class="text-center"><input type="checkbox" name="delNews[]" value="<?php echo $news_id ?>" ></td>
            <?php
                }else{
            ?>
                <td></td>
            <?php
                }
            ?>
            <td><?php echo $news_id ?></td>
            <td><?php echo $news_name ?></td>
            <td><?php echo $cat_name ?></td>
            <td><?php echo $fullname ?></td>
            <td><?php echo $role ?></td>
            <td><?php echo $view ?></td>
            <td>
                <?php
                    if($picture != ''){
                ?>
                <img src="/files/<?php echo $picture ?>" alt="" height="90px" width="90px">
                <?php
                    }else{
                ?>
                    <strong>Không có hình</strong>
                <?php
                    }
                ?>
            </td>
            <td class="text-center">
                <?php
                    if($_SESSION['userInfo']['role'] == 'admin' || $email == $_SESSION['userInfo']['email']){
                ?>
                    <?php
                    if($news_active == 1){
                        $act = 'active';
                    }else{
                        $act = 'deactive';
                    }
                    ?>
                <a id="act<?php echo $news_id ?>" href="javascript:void(0)" title="" onclick="return getActive('act<?php echo $news_id ?>','<?php echo $act ?>')"><img src="/files/<?php echo $act ?>.gif" alt="" /></a>
                <?php
                    }
                ?>
            </td>
            <td class="center">   
                <?php
                    if($_SESSION['userInfo']['role'] == 'admin' || $email == $_SESSION['userInfo']['email'] ){
                ?>
                <a href="/admin/post/edit.php?id=<?php echo $news_id ?>" title="" class="btn btn-primary"><i class="fa fa-edit "></i> Sửa</a>
                <?php
                    }
                ?>
                <?php
                    if($_SESSION['userInfo']['role'] == 'admin' || $email == $_SESSION['userInfo']['email']){
                ?>
                <a href="/admin/post/del.php?id=<?php echo $news_id ?>" title="" onclick="return confirm('Bạn có chắc chắn xóa không? (xóa cả bình luận)')" class="btn btn-danger"><i class="fa fa-times"></i> Xóa</a>                                            
                <?php
                    }
                ?>
            </td>
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
            <input class="btn btn-warning btn-delmutil" onclick="return confirm('Bạn có chắc chắn xóa không? (xóa cả bình luận)')" type="submit" name="delMultiNews" value="Xóa tất cả">
        </div>
    </div>
    <div class="col-sm-6" style="text-align: right;">
        <div class="dataTables_paginate paging_simple_numbers" id="dataTables-example_paginate">
        <ul style="margin:0" class="pagination">
                <?php
                    for($i = 1; $i <= $total_pages; $i++){
                        if($i == $current_page){
                ?>
                <li class="paginate_button active" aria-controls="dataTables-example" tabindex="0"><span><?php echo $i ?></span></li>
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

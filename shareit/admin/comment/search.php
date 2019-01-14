<?php
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'].'/util/DBConnectionUtil.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/util/ConstantUtil.php';
    $search = ' ';
    $active = '';
    if(isset($_POST["search"]))  
    {  
      $search = $_POST["search"];  
    }
    if(isset($_POST["active"]))  
    {  
      $active = $_POST["active"];  
    }
    if($active == ''){
        $queryTSD = "SELECT COUNT(*) AS TSD FROM ((comment INNER JOIN news ON comment.news_id = news.id) INNER JOIN user ON comment.user_id = user.id) WHERE news.name LIKE '%$search%' OR user.fullname LIKE '%$search%' OR comment.content LIKE '%$search%'";
    }else{
        $queryTSD = "SELECT COUNT(*) AS TSD FROM ((comment INNER JOIN news ON comment.news_id = news.id) INNER JOIN user ON comment.user_id = user.id) WHERE (news.name LIKE '%$search%' OR user.fullname LIKE '%$search%' OR comment.content LIKE '%$search%') AND comment.active = $active";
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
<form action="/admin/comment/delMulti.php" method="POST">
<table class="table table-striped table-bordered table-hover" id="dataTables-example">
    <thead>
        <tr>
            <th class="text-center"><input type="checkbox" id="checkAllId"></th>
            <th>ID bình luận</th>
            <th>Tên tin</th>
            <th>Người bình luận</th>
            <th>Nội dung</th>
            <th>Trạng thái</th>
            <th width="160px">Chức năng</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if($active == ''){
                $querySltCmt = "SELECT role, comment.id AS cmt_id, news.name AS news_name, email, content, comment.active AS cmt_active FROM ((comment INNER JOIN news ON comment.news_id = news.id) INNER JOIN user ON comment.user_id = user.id) WHERE news.name LIKE '%$search%' OR email LIKE '%$search%' OR content LIKE '%$search%' ORDER BY comment.id DESC LIMIT {$offset},{$row_count}";
            }else{
                $querySltCmt = "SELECT role, comment.id AS cmt_id, news.name AS news_name, email, content, comment.active AS cmt_active FROM ((comment INNER JOIN news ON comment.news_id = news.id) INNER JOIN user ON comment.user_id = user.id) WHERE (news.name LIKE '%$search%' OR email LIKE '%$search%' OR content LIKE '%$search%') AND comment.active = $active ORDER BY comment.id DESC LIMIT {$offset},{$row_count}";
            }
            $resultSltCmt = $mysqli->query($querySltCmt);
            if($mysqli->affected_rows > 0){
                while($arCmt = mysqli_fetch_assoc($resultSltCmt)){
                    $cmt_id = $arCmt['cmt_id'];
                    $news_name = $arCmt['news_name'];
                    $user_email = $arCmt['email'];
                    $user_role = $arCmt['role'];
                    $cmt_content = $arCmt['content'];
                    $cmt_active = $arCmt['cmt_active'];
        ?>
        <tr class="gradeX">
            <?php
                if($_SESSION['userInfo']['role'] == 'admin' || $user_email == $_SESSION['userInfo']['email'] || $user_role == 'member'){
            ?>
            <td class="text-center"><input type="checkbox" name="delComment[]" value="<?php echo $cmt_id ?>" ></td>
            <?php
                }else{
            ?>
                <td></td>
            <?php
                }
            ?>
            <td><?php echo $cmt_id ?></td>
            <td><?php echo $news_name ?></td>
            <td><?php echo $user_email ?></td>
            <td><?php echo $cmt_content ?></td>
            <td class="text-center">
                <?php
                    if($_SESSION['userInfo']['role'] == 'admin' || $user_email == $_SESSION['userInfo']['email'] || $user_role == 'member'){
                ?>
                <?php
                if($cmt_active == 1){
                    $act = 'active';
                }else{
                    $act = 'deactive';
                }
                ?>
                <a id="act<?php echo $cmt_id ?>" href="javascript:void(0)" title="" onclick="return getActive('act<?php echo $cmt_id ?>','<?php echo $act ?>')"><img src="/files/<?php echo $act ?>.gif" alt="" /></a>
                <?php
                    }
                ?>
            </td>
            <td class="center"> 
                <?php
                    if($_SESSION['userInfo']['role'] == 'admin' || $user_email == $_SESSION['userInfo']['email'] || $user_role == 'member'){
                ?>
                <a href="/admin/comment/del.php?id=<?php echo $cmt_id ?>" title="" onclick="return confirm('Bạn có chắc chắn xóa không?')" class="btn btn-danger"><i class="fa fa-times"></i> Xóa</a>                                            
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
            <input class="btn btn-warning btn-delmutil" onclick="return confirm('Bạn có chắc chắn xóa không?')" type="submit" name="delMultiComment" value="Xóa tất cả">
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

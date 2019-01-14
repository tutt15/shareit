<?php
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'].'/util/DBConnectionUtil.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/util/ConstantUtil.php';
    $search = '';
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
        $queryTSD = "SELECT COUNT(*) AS TSD FROM user WHERE email LIKE '%$search%' OR fullname LIKE '%$search%' OR role LIKE '%$search%'";
    }else{
        $queryTSD = "SELECT COUNT(*) AS TSD FROM user WHERE (email LIKE '%$search%' OR fullname LIKE '%$search%' OR role LIKE '%$search%') AND active = $active";
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
<form action="/admin/user/delMulti.php" method="POST">
<table class="table table-striped table-bordered table-hover" id="dataTables-example">
    <thead>
        <tr>
            <th class="text-center"><input type="checkbox" id="checkAllId"></th>
            <th>ID</th>
            <th>Email</th>
            <th>Tên</th>
            <th class="text-center">Ảnh</th>
            <th>Chức vụ</th>
            <th class="text-center">Trạng thái</th>
            <th width="160px">Chức năng</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if($active == ''){
                $querySltUser = "SELECT id, email, fullname, avatar, role, active FROM user WHERE (email LIKE '%$search%' OR fullname LIKE '%$search%' OR role LIKE '%$search%') ORDER BY id DESC LIMIT {$offset},{$row_count}";
            }else{
                $querySltUser = "SELECT id, email, fullname, avatar, role, active FROM user WHERE (email LIKE '%$search%' OR fullname LIKE '%$search%' OR role LIKE '%$search%') AND active = $active ORDER BY id DESC LIMIT {$offset},{$row_count}";
            }
            
            $resultSltUser = $mysqli->query($querySltUser);
            if($mysqli->affected_rows > 0){
                while($arUser = mysqli_fetch_assoc($resultSltUser)){
                    $user_id = $arUser['id'];
                    $user_email = $arUser['email'];
                    $user_fullname = $arUser['fullname'];
                    $user_avatar = $arUser['avatar'];
                    $user_role = $arUser['role'];
                    $user_active = $arUser['active'];
        ?>
        <tr class="gradeX">
            <?php
                if(($_SESSION['userInfo']['role'] == 'admin' || $user_role == 'member') && $user_role != 'admin'){
            ?>
            <td class="text-center"><input type="checkbox" name="delUser[]" value="<?php echo $user_id ?>" ></td>
            <?php
                }else{
            ?>
            <td></td>
            <?php
                }
            ?>
            <td><?php echo $user_id ?></td>
            <td><?php echo $user_email ?></td>
            <td><?php echo $user_fullname ?></td>
            <td class="text-center">
                <?php
                    if($user_avatar == ''){
                ?>
                    <img src="/files/user-icon2.png" alt="" width="50" height="50">
                <?php
                    }else{
                ?>
                    <img src="/files/<?php echo $user_avatar ?>" alt="" width="50" height="50" style="border-radius:50%">
                <?php
                    }
                ?>
            </td>
            <td><?php echo $user_role ?></td>
            <td class="text-center">
                <?php
                    if(($_SESSION['userInfo']['role'] == 'admin' || $user_role == 'member') && $user_role != 'admin'){
                ?>
                    <?php
                    if($user_active == 1){
                        $act = 'active';
                    }else{
                        $act = 'deactive';
                    }
                    ?>
                <a id="act<?php echo $user_id ?>" href="javascript:void(0)" title="" onclick="return getActive('act<?php echo $user_id ?>','<?php echo $act ?>')"><img src="/files/<?php echo $act ?>.gif" alt="" /></a>
                <?php
                    }
                ?>
            </td>
            <td class="center">   
                <?php
                    if($_SESSION['userInfo']['role'] == 'admin' || $user_email == $_SESSION['userInfo']['email'] || $user_role == 'member'){
                ?>
                <a href="/admin/user/edit.php?id=<?php echo $user_id ?>" title="" class="btn btn-primary"><i class="fa fa-edit "></i> Sửa</a>
                <?php
                    }
                ?>
                <?php
                    if(($_SESSION['userInfo']['role'] == 'admin' || $user_role == 'member') && $user_role != 'admin'){
                ?>
                <a href="/admin/user/del.php?id=<?php echo $user_id ?>" title="" onclick="return confirm('Bạn có chắc chắn xóa không? (cả bài viết và bình luận)')" class="btn btn-danger"><i class="fa fa-times"></i> Xóa</a>                                            
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
            <input class="btn btn-warning btn-delmutil" onclick="return confirm('Bạn có chắc chắn xóa không? (cả bài viết, bình luận)')" type="submit" name="delMultiUser" value="Xóa tất cả">
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

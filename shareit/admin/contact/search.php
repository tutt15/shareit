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
        $queryTSD = "SELECT COUNT(*) AS TSD FROM contact WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR subject LIKE '%$search%' OR message LIKE '%$search%'";
    }else{
        $queryTSD = "SELECT COUNT(*) AS TSD FROM contact WHERE (name LIKE '%$search%' OR email LIKE '%$search%' OR subject LIKE '%$search%' OR message LIKE '%$search%') AND active = $active";    
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
<form action="/admin/contact/delmulti.php" method="POST">
<table class="table table-striped table-bordered table-hover" id="dataTables-example">
    <thead>
        <tr>
            <th class="text-center"><input type="checkbox" id="checkAllId"></th>
            <th>ID</th>
            <th>Tên người gửi</th>
            <th>Email</th>
            <th>Chủ đề</th>
            <th>Nội dung</th>
            <th>Trạng thái</th>
            <th width="160px">Chức năng</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if($active == ''){
                $querySltContact = "SELECT * FROM contact WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR subject LIKE '%$search%' OR message LIKE '%$search%' ORDER BY id DESC LIMIT {$offset},{$row_count}";
            }else{
                $querySltContact = "SELECT * FROM contact WHERE (name LIKE '%$search%' OR email LIKE '%$search%' OR subject LIKE '%$search%' OR message LIKE '%$search%') AND active = $active ORDER BY id DESC LIMIT {$offset},{$row_count}";
            }
            $resultSltContact = $mysqli->query($querySltContact);
            if($mysqli->affected_rows > 0){
                while($arContact = mysqli_fetch_assoc($resultSltContact)){
                    $id = $arContact['id'];
                    $name = $arContact['name'];
                    $email = $arContact['email'];
                    $subject = $arContact['subject'];
                    $message = $arContact['message'];
                    $active = $arContact['active'];
        ?>
        <tr class="gradeX">
            <td class="text-center"><input type="checkbox" name="delContact[]" value="<?php echo $id ?>" ></td>
            <td><?php echo $id ?></td>
            <td><?php echo $name ?></td>
            <td><?php echo $email ?></td>
            <td><?php echo $subject ?></td>
            <td><?php echo $message ?></td>
            <td class="text-center">
                <?php
                    if($active == 1){
                ?>
                <img src="/files/active.gif" alt="" />
                <?php
                    }else{
                ?>
                <img src="/files/deactive.gif" alt="" />
                <?php
                    }
                ?>
            </td>
            <td class="center">  
                <a href="/admin/contact/reply.php?id=<?php echo $id ?>" title="" class="btn btn-info"> Trả lời</a>                                            
                <a href="/admin/contact/del.php?id=<?php echo $id ?>" title="" onclick="return confirm('Bạn có chắc chắn xóa không?')" class="btn btn-danger"><i class="fa fa-times"></i> Xóa</a>                                            
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
            <input class="btn btn-warning btn-delmutil" onclick="return confirm('Bạn có chắc chắn xóa không?')" type="submit" name="delMultiContact" value="Xóa tất cả">
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

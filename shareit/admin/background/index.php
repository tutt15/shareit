<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/header.php'; ?>
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/leftbar.php'; ?>

<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2>Quản lý ảnh nền</h2>
            </div>
        </div>
        <!-- /. ROW  -->

        <div class="row">
            <div class="col-md-12">
                <!-- Advanced Tables -->
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <div class="row">
                                <div class="col-sm-1">
                                    <?php
                                        if($_SESSION['userInfo']['role'] == 'admin'){
                                    ?>
                                    <a href="add.php" class="btn btn-success btn-md"><i class="fa fa-plus"></i> Thêm</a>
                                    <?php
                                        }
                                    ?>
                                </div>
                                <div class="col-sm-4">
                                    <?php
                                        if(isset($_SESSION['success'])){
                                    ?>
                                    <p class="alert alert-success text-center"><?php echo $_SESSION['success']; unset($_SESSION['success']) ?></p>
                                    <?php
                                        }
                                    ?> 
                                    <?php
                                        if(isset($_SESSION['error'])){
                                    ?>
                                    <p class="alert alert-danger text-center"><?php echo $_SESSION['error']; unset($_SESSION['error']) ?></p>
                                    <?php
                                        }
                                    ?>
                                </div>
                            </div>
                            <br />
                            <div id="content-table"><!-- content-table -->
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Ảnh</th>
                                            <th width="160px">Chức năng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $querySltBg = "SELECT * FROM background";
                                            $resultSltBg = $mysqli->query($querySltBg);
                                            while($arBg = $resultSltBg->fetch_assoc()){
                                        ?>
                                        <tr class="gradeX">
                                            <td><?php echo $arBg['id'] ?></td>
                                            <td><img src="/files/<?php echo $arBg['image'] ?>" alt="" height="90x" width="150"></td>
                                            <td><a href="/admin/background/del.php?id=<?php echo $arBg['id'] ?>" onclick="return confirm('Bạn có chắc chắn xóa không?')" class="btn btn-danger"><i class="fa fa-times"></i> Xóa</a></td>
                                        </tr>
                                        <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div><!-- /content-table -->                
                            
                        </div>

                    </div>
                </div>
                <!--End Advanced Tables -->
            </div>
        </div>
    </div>

</div>
<!-- /. PAGE INNER  -->
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/footer.php'; ?>
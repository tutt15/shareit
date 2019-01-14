<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/header.php'; ?>
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/leftbar.php'; ?>
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2>TRANG QUẢN TRỊ VIÊN</h2>
            </div>
        </div>
        <!-- /. ROW  -->
        <div class="row">
            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="panel panel-back noti-box">
                    <span class="icon-box bg-color-green set-icon">
                    <i class="fa fa-bars"></i>
                </span>
                    <div class="text-box">
                        <p class="main-text"><a href="/admin/cat" title="">Quản lý danh mục</a></p>
                        <?php
                            $query = "SELECT * FROM cat";
                            $result = $mysqli->query($query);
                            $row_count = $result->num_rows;
                        ?>
                        <p class="text-muted">Có <?php echo $row_count ?> danh mục</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="panel panel-back noti-box">
                    <span class="icon-box bg-color-blue set-icon">
                    <i class="fa fa-bell-o"></i>
                </span>
                    <div class="text-box">
                        <p class="main-text"><a href="/admin/news" title="">Quản lý tin tức</a></p>
                        <?php
                            $query = "SELECT * FROM news INNER JOIN user ON news.created_by = user.id WHERE user.role != 'member'";
                            $result = $mysqli->query($query);
                            $row_count = $result->num_rows;
                        ?>
                        <p class="text-muted">Có <?php echo $row_count ?> tin</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="panel panel-back noti-box">
                    <span class="icon-box bg-color-red set-icon">
                    <i class="fa fa-archive"></i>
                </span>
                    <div class="text-box">
                        <p class="main-text"><a href="/admin/post" title="">Quản lý duyệt tin</a></p>
                        <?php
                            $query = "SELECT * FROM news INNER JOIN user ON news.created_by = user.id WHERE user.role = 'member'";
                            $result = $mysqli->query($query);
                            $row_count = $result->num_rows;
                        ?>
                        <p class="text-muted">Có <?php echo $row_count ?> tin</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="panel panel-back noti-box">
                    <span class="icon-box bg-color-brown set-icon">
                    <i class="fa fa-rocket"></i>
                </span>
                    <div class="text-box">
                        <p class="main-text"><a href="/admin/user" title="">Quản lý người dùng</a></p>
                        <?php
                            $query = "SELECT * FROM user";
                            $result = $mysqli->query($query);
                            $row_count = $result->num_rows;
                        ?>
                        <p class="text-muted">Có <?php echo $row_count ?> người dùng</p>
                    </div>
                </div>
            </div>
            <?php if($_SESSION['userInfo']['role'] == 'admin'){ ?>
            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="panel panel-back noti-box">
                    <span class="icon-box bg-color-yellow set-icon">
                    <i class="fa fa-bell"></i>
                </span>
                    <div class="text-box">
                        <p class="main-text"><a href="/admin/contact" title="">Quản lý liên hệ</a></p>
                        <?php
                            $query = "SELECT * FROM contact";
                            $result = $mysqli->query($query);
                            $row_count = $result->num_rows;
                        ?>
                        <p class="text-muted">Có <?php echo $row_count ?> người dùng</p>
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="panel panel-back noti-box">
                    <span class="icon-box bg-color-red set-icon">
                    <i class="fa fa-comments"></i>
                </span>
                    <div class="text-box">
                        <p class="main-text"><a href="/admin/comment" title="">Quản lý bình luận</a></p>
                        <?php
                            $query = "SELECT * FROM comment";
                            $result = $mysqli->query($query);
                            $row_count = $result->num_rows;
                        ?>
                        <p class="text-muted">Có <?php echo $row_count ?> bình luận</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="panel panel-back noti-box">
                    <span class="icon-box bg-color-green set-icon">
                    <i class="fa fa-adjust"></i>
                </span>
                    <div class="text-box">
                        <p class="main-text"><a href="/admin/background" title="">Quản lý ảnh nền</a></p>
                        <?php
                            $query = "SELECT * FROM background";
                            $result = $mysqli->query($query);
                            $row_count = $result->num_rows;
                        ?>
                        <p class="text-muted">Có <?php echo $row_count ?> ảnh nền</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- /. PAGE WRAPPER  -->
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/footer.php'; ?>
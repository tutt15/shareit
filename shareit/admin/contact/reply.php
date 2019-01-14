<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/header.php'; ?>
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/leftbar.php'; ?>
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/util/sendEmail.php'; ?>
<?php
    if($_SESSION['userInfo']['role'] != 'admin'){
        header('Location: /admin');
        die();
    }
?>
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2>Trả lời liên hệ</h2>
            </div>
        </div>
        <!-- /. ROW  -->
        <hr />
        <div class="row">
            <div class="col-md-12">
                <!-- Form Elements -->
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <?php
                                $id = 0;
                                if(!isset($_GET['id']) || !preg_match("/^[0-9]+$/", $_GET['id'])){
                                    header('Location: /admin/cat');
                                    die();
                                }
                                $id = $_GET['id'];
                                $querySltContact = "SELECT * FROM contact WHERE id = {$id}";
                                $resultSltContact = $mysqli->query($querySltContact);
                                $arContact = $resultSltContact->fetch_assoc();
                                if(empty($arContact)){
                                    $_SESSION['error'] = "Liên hệ không tồn tại";
                                    header('Location: /admin/contact');
                                    die();
                                } 
                                $name = $arContact['name'];
                                $email = $arContact['email'];
                                if(isset($_POST['submit'])){
                                    $subject = $_POST['subject'];
                                    $content = $_POST['content'];
                                    $error = [];
                                    if($subject == ''){
                                        $error['subject'] = "Tên danh mục không được để trống";
                                    }
                                    if($content == ''){
                                        $error['content'] = "Danh mục cha không được để trống";
                                    }
                                    if(empty($error)){
                                        $mail = sendMail($subject, $content, $name, $email, $diachicc='');
                                        if($mail = 0){
                                            $_SESSION['error'] .= "Trả lời liên hệ thất bại!";
                                            header('Location:/admin/contact');
                                        }else{
                                            $queryEditContact = "UPDATE contact SET active = 1 WHERE id = $id";
                                            $resultEditContact = $mysqli->query($queryEditContact);
                                            $_SESSION['success'] .= "Trả lời liên hệ thành công!";
                                            header('Location:/admin/contact');
                                        }
                                    }
                                }
                            ?>
                            <div class="col-md-12">
                                <form role="form" action="" method="post" >
                                    <div class="form-group">
                                        <label>Chủ đề</label>
                                        <input type="text" name="subject" class="form-control" required />
                                        <?php if(isset($error['subject'])){ ?>
                                            <p class="text-error"><?php echo $error['subject']; ?></p>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Nội dung</label>
                                        <textarea name="content" class="form-control" required cols="30" rows="10"></textarea>
                                        <?php if(isset($error['content'])){ ?>
                                            <p class="text-error"><?php echo $error['content']; ?></p>
                                        <?php } ?>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-success btn-md">Trả lời</button>
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
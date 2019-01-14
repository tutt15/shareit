<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/util/DBConnectionUtil.php';
    $cmt_parent_id = $_POST['cmt_parent_id'];
    $cmt_content = $_POST['cmt_content'];
    $user_id = $_POST['user_id'];
    $news_id = $_POST['news_id'];
    $query = "INSERT INTO comment(content,user_id,parent_id,news_id) VALUES ('$cmt_content',$user_id,$cmt_parent_id,$news_id)";
    $result = $mysqli->query($query);
    $insert_id = $mysqli->insert_id;
    $cmt_content = nl2br($cmt_content);
    $querySltRep = "SELECT fullname, avatar, comment.date_create AS cmt_date FROM comment INNER JOIN user ON comment.user_id = user.id WHERE comment.id = $insert_id";
    $resultSltRep = $mysqli->query($querySltRep);
    $arRep = $resultSltRep->fetch_assoc();
    $fullname = $arRep['fullname'];
    $avatar = $arRep['avatar'];
    $cmt_date = $arRep['cmt_date'];
?>
<li>
    <div class="comment-list left-padding">
        <div class="single-comment justify-content-between d-flex">
            <div class="user justify-content-between d-flex">
                <div class="thumb">
                    <?php
                        if($avatar != ''){
                    ?>
                    <img src="/files/<?php echo $avatar ?>" alt="" height="60" width="60">
                    <?php
                        }else{
                    ?>
                    <img src="/files/user-icon2.png" alt="" height="60" width="60">
                    <?php
                        }
                    ?> 
                </div>
                <div class="desc">
                    <h5><a href="#"><?php echo $fullname ?></a></h5>
                    <p class="date"><?php echo date( "d/m/Y H:i:s", strtotime($cmt_date)) ?></p>
                    <p class="comment">
                        <?php echo $cmt_content ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</li>
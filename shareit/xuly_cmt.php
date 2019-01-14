<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/util/DBConnectionUtil.php';
    $content = $_POST['content'];
    $user_id = $_POST['userid'];
    $news_id = $_POST['newsid'];
    $query = "INSERT INTO comment(content,user_id,parent_id,news_id) VALUES ('$content',$user_id,0,$news_id)";
    $result = $mysqli->query($query);
    $insert_id = $mysqli->insert_id;
    $content = nl2br($content);
    $querySltCmt = "SELECT fullname, avatar, comment.date_create AS cmt_date FROM comment INNER JOIN user ON comment.user_id = user.id WHERE comment.id = $insert_id";
    $resultSltCmt = $mysqli->query($querySltCmt);
    $arCmt = $resultSltCmt->fetch_assoc();
    $fullname = $arCmt['fullname'];
    $avatar = $arCmt['avatar'];
    $cmt_date = $arCmt['cmt_date'];
    $cmt_date = date( "d/m/Y H:i:s", strtotime($cmt_date));
?>
<li>
    <div class="comment-list">
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
                    <p class="date"><?php echo $cmt_date ?></p>
                    <p class="comment"><?php echo $content ?></p>
                </div>
            </div>
            <div class="reply-btn">
                <a href="javascript:void(0)" class="btn-reply text-uppercase rep-cmt" data-rep="<?php echo $insert_id ?>">Trả lời</a>
            </div>
        </div>
    </div>
    <ul class="rep-list<?php echo $insert_id ?>">
    </ul>
</li>


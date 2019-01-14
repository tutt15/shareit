<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/shareit/inc/header.php'; ?>
		
		<div class="site-main-container">
			<!-- Start top-post Area -->
			<section class="top-post-area pt-10">
				<div class="container no-padding">
					<div class="row">
						<div class="col-lg-12">
							<div class="hero-nav-area">
								<?php
									$news_id = 0;
									if(!isset($_GET['id']) || !preg_match("/^[0-9]+$/", $_GET['id'])){
										header('Location:/');
										die();
									}
									$news_id = $_GET['id'];
									$querySltNews = "SELECT news.id AS news_id, picture, news.name AS news_name, cat.id AS cat_id, cat.name AS cat_name , fullname, date_create, view, preview, detail FROM ((news INNER JOIN user ON news.created_by = user.id) INNER JOIN cat ON news.cat_id = cat.id) WHERE user.active = 1 AND news.active = 1 AND news.id = $news_id";
									$resultSltNews = $mysqli->query($querySltNews);
									$arNews = $resultSltNews->fetch_assoc();
									if(empty($arNews)){
										header('Location: /');
										die();
									} 
									$cat_id = $arNews['cat_id'];
									$cat_name = $arNews['cat_name'];
									$news_name = $arNews['news_name'];
									$user_fullname = $arNews['fullname'];
									$news_date_create = date( "d/m/Y", strtotime($arNews['date_create']));
									$news_view = $arNews['view'];
									$news_detail = $arNews['detail'];
									$news_picture = $arNews['picture'];
									$news_view = $news_view + 1;
									$queryUpView = "UPDATE news SET view = $news_view WHERE id = $news_id";
									$mysqli->query($queryUpView);
								?>
								<h1 class="text-white"><?php echo $cat_name ?></h1>
								<p class="text-white link-nav"><a href="/">Home </a>  <span class="lnr lnr-arrow-right"></span><a href="cat.php?id=<?php echo $cat_id ?>"><?php echo $cat_name ?></a></p>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- End top-post Area -->
			<!-- Start latest-post Area -->
			<section class="latest-post-area pb-120">
				<div class="container no-padding">
					<div class="row">
						<div class="col-lg-8 post-list">
                            <div class="single-post-wrap">
                                <div class="feature-img-thumb relative">
                                    <?php
                                        if($news_picture == ''){
                                    ?>
                                    <img class="img-fluid item" src="/files/white-picture.png" alt="" width="710" height="310">
                                    <?php
                                        }else{
                                    ?>
                                    <img class="img-fluid item" src="/files/<?php echo $news_picture ?>" alt="" width="710" height="310" >
                                    <?php
                                        }
                                    ?>
								</div>
                                <div class="content-wrap">
                                    <h3><?php echo $news_name ?></h3>
									<ul class="meta pb-20">
										<li><a href="#"><span class="lnr lnr-user"></span><?php echo $user_fullname ?></a></li>
										<li><span class="lnr lnr-calendar-full"></span><?php echo $news_date_create ?></li>
										<li><span class="lnr lnr-bubble"></span><?php echo $news_view ?> Views</li>
									</ul>
									<p><?php echo $news_detail ?></p>
                                    <div class="navigation-wrap justify-content-between d-flex">
                                        <?php
                                            $queryPrevNews = "SELECT news.id AS news_id, news.name AS news_name FROM ((news INNER JOIN user ON news.created_by = user.id) INNER JOIN cat ON news.cat_id = cat.id) WHERE news.cat_id = $cat_id AND user.active = 1 AND news.active = 1 AND news.id < $news_id ORDER BY news.id DESC LIMIT 1";
                                            $resultPrevNews = $mysqli->query($queryPrevNews);
                                            $preNews = $resultPrevNews->fetch_assoc();
                                            $preNews_id = $preNews['news_id'];
                                            $preNews_name = $preNews['news_name'];
                                            $urlSlugDetail = '/chi-tiet/'.utf8ToLatin($preNews_name).'-'.$preNews_id.'.html';
                                        ?>
                                        <?php
                                            if($preNews_id != ''){
                                        ?>
                                        <a class="prev" href="<?php echo $urlSlugDetail ?>"><i class="lnr lnr-arrow-left"></i>Prev Post</a>
                                        <?php
                                            }else{
                                        ?>
                                        <span class="prev"><i class="lnr lnr-arrow-left"></i>Prev Post</span>
                                        <?php
                                            }
                                        ?>
                                        <?php
                                            $queryNextNews = "SELECT news.id AS news_id, news.name AS news_name FROM ((news INNER JOIN user ON news.created_by = user.id) INNER JOIN cat ON news.cat_id = cat.id) WHERE news.cat_id = $cat_id AND user.active = 1 AND news.active = 1 AND news.id > $news_id LIMIT 1";
                                            $resultNextNews = $mysqli->query($queryNextNews);
                                            $nextNews = $resultNextNews->fetch_assoc();
                                            $nextNews_id = $nextNews['news_id'];
                                            $nextNews_name = $nextNews['news_name'];
                                            $urlSlugDetail = '/chi-tiet/'.utf8ToLatin($nextNews_name).'-'.$nextNews_id.'.html';
                                        ?>
                                        <?php
                                            if($nextNews_id != ''){
                                        ?>
                                        <a class="next" href="<?php echo $urlSlugDetail ?>">Next Post<span class="lnr lnr-arrow-right"></span></a>
                                        <?php
                                            }else{
                                        ?>
                                        <span class="next">Next Post<i class="lnr lnr-arrow-right"></i></span>
                                        <?php
                                            }
                                        ?>
                                    </div>
                                </div>
<script>
    $(document).ready(function(){
        $('.submit-cmt').click(function(){
            content = $('.cmt-content').val();
            userid = $('.cmt-content').attr('data-userid');
            newsid= $('.submit-cmt').attr('data-newsid');
            $.ajax({
                url: "/xuly_cmt.php",
                type: "post",
                data: {
                    content:content,
                    userid:userid,
                    newsid:newsid
                },
                async:true,
                success:function(data){
                    // var getData = $.parseJSON(data);
                    if($("#comment-list-item li").length == 0){
                        $("#comment-list-item").html(data);
                    }else{
                        $("#comment-list-item li:eq(0)").before(data);
                    }
                    $(".cmt-content").val("");
                }
            });
            return false;
        });
        $('#comment-list-item').on('click','.submit-rep',function(){
            cmt_id = $(this).attr('data-cmtid');
            content = $('.rep-content'+cmt_id).val();
            user_id = $('.cmt-content').attr('data-userid');
            news_id= $('.submit-cmt').attr('data-newsid');
            $.ajax({
                url: '/xuly_rep.php',
                type: 'post',
                data: {
                    cmt_parent_id:cmt_id,
                    cmt_content:content,
                    user_id:user_id,
                    news_id:news_id
                },
                async:true,
                success:function(data){
                    $('.rep-list'+cmt_id).append(data);
                    $('.rep-content'+cmt_id).val("");
                }
            });
            return false;
        });
        $('#comment-list-item').on('click','.rep-cmt',function(){
            cmt_id = $(this).attr('data-rep');
            $(this).hide();
            // // $('.rep-form'+cmt_id).slideToggle();
            $.ajax({
                url: "/xuly_formrep.php",
                type: "post",
                data: {
                    cmt_id: cmt_id
                },
                async:true,
                success:function(data){
                    $(".rep-list"+cmt_id+":eq(0)").after(data);
                    // $('.rep-form'+cmt_id).slideToggle();
                }
            });
            return false;
        });
    });
</script>                       
                                <?php
                                    if(empty($_SESSION['userInfo'])){
                                ?>
                                    <p class="alert alert-warning text-center"><b>Bạn cần đăng nhập để bình luận</b></p>
                                <?php
                                    }else{
                                ?>
                                <div class="comment-form">
                                    <h4>Bình luận</h4>
                                    <form>
                                        <div class="form-group">
                                            <textarea class="form-control mb-10 cmt-content" rows="5" name="message" data-userid="<?php echo $_SESSION['userInfo']['id'] ?>" placeholder="Messege" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Messege'" required=""></textarea>
                                        </div>
                                        <a href="javascript:void(0)" class="primary-btn text-uppercase submit-cmt" data-newsid="<?php echo $news_id ?>" >Bình luận</a>
                                    </form>
                                </div>
                                <?php
                                    }
                                ?>
                                <div class="comment-sec-area">
                                    <div class="container">
                                        <div class="row flex-column">
                                            <ul id="comment-list-item">
                                                <?php
                                                    $queryCmt = "SELECT comment.id AS cmt_id, content, comment.date_create AS cmt_date, fullname, avatar FROM ((comment INNER JOIN user ON comment.user_id = user.id) INNER JOIN news ON comment.news_id = news.id) WHERE comment.active = 1 AND user.active = 1 AND news.active = 1 AND news.id = $news_id AND comment.parent_id = 0 ORDER BY comment.id DESC";
                                                    $resultCmt = $mysqli->query($queryCmt);
                                                    if(!($resultCmt->num_rows > 0)){
                                                        echo '<span>Chưa có bình luận nào</span>';
                                                    }else{
                                                        while($arCmt = $resultCmt->fetch_assoc()){
                                                            $cmt_id = $arCmt['cmt_id'];
                                                            $user_avatar = $arCmt['avatar'];
                                                            $user_fullname = $arCmt['fullname'];
                                                            $cmt_content = nl2br($arCmt['content']);
                                                            $cmt_date = date( "d/m/Y H:i:s", strtotime($arCmt['cmt_date']));
                                                ?>
                                                <li>
                                                    <div class="comment-list">
                                                        <div class="single-comment justify-content-between d-flex">
                                                            <div class="user justify-content-between d-flex">
                                                                <div class="thumb">
                                                                    <?php
                                                                        if($user_avatar != ''){
                                                                    ?>
                                                                    <img src="/files/<?php echo $user_avatar ?>" alt="" height="60" width="60">
                                                                    <?php
                                                                        }else{
                                                                    ?>
                                                                    <img src="/files/user-icon2.png" alt="" height="60" width="60">
                                                                    <?php
                                                                        }
                                                                    ?>
                                                                </div>
                                                                <div class="desc">
                                                                    <h5><a href="#"><?php echo $user_fullname ?></a></h5>
                                                                    <p class="date"><?php echo $cmt_date ?></p>
                                                                    <p class="comment">
                                                                        <?php echo $cmt_content ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <?php
                                                                if(isset($_SESSION['userInfo'])){
                                                            ?>
                                                            <div class="reply-btn">
                                                                <a href="javascript:void(0)" class="btn-reply text-uppercase rep-cmt" data-rep="<?php echo $cmt_id ?>">Trả lời</a>
                                                            </div>
                                                            <?php
                                                                }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <ul class="rep-list<?php echo $cmt_id ?>">
                                                        <?php
                                                            $queryRep = "SELECT comment.id AS cmt_id, content, comment.date_create AS cmt_date, fullname, avatar FROM ((comment INNER JOIN user ON comment.user_id = user.id) INNER JOIN news ON comment.news_id = news.id) WHERE comment.active = 1 AND user.active = 1 AND news.active = 1 AND news.id = $news_id AND comment.parent_id = $cmt_id ORDER BY comment.id DESC";
                                                            $resultRep = $mysqli->query($queryRep);
                                                            while($arRep = $resultRep->fetch_assoc()){
                                                                // $cmt_id_rep = $arRep['cmt_id'];
                                                                $user_fullname = $arRep['fullname'];
                                                                $user_avatar = $arRep['avatar'];
                                                                $cmt_content = nl2br($arRep['content']);
                                                                $cmt_date = date( "d/m/Y h:m:s", strtotime($arRep['cmt_date']));
                                                        ?>
                                                        <li>
                                                            <div class="comment-list left-padding">
                                                                <div class="single-comment justify-content-between d-flex">
                                                                    <div class="user justify-content-between d-flex">
                                                                        <div class="thumb">
                                                                            <?php
                                                                                if($user_avatar != ''){
                                                                            ?>
                                                                            <img src="/files/<?php echo $user_avatar ?>" alt="" height="60" width="60">
                                                                            <?php
                                                                                }else{
                                                                            ?>
                                                                            <img src="/files/user-icon2.png" alt="" height="60" width="60">
                                                                            <?php
                                                                                }
                                                                            ?>
                                                                        </div>
                                                                        <div class="desc">
                                                                            <h5><a href="#"><?php echo $user_fullname ?></a></h5>
                                                                            <p class="date"><?php echo $cmt_date ?></p>
                                                                            <p class="comment">
                                                                                <?php echo $cmt_content ?>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <?php
                                                            }
                                                        ?>
                                                    </ul>
                                                </li>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </ul>
                                
                                        </div>
                                    </div>
                                </div>
                            </div>
						</div>
                        <div class="col-lg-4">
                            <?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/shareit/inc/rightbar.php'; ?>
                        </div>
					</div>
				</div>
			</div>
		</section>
		<!-- End latest-post Area -->
	</div>
	
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/shareit/inc/footer.php'; ?>